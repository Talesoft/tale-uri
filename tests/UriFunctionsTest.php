<?php declare(strict_types=1);

namespace Tale\Test;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\UriInterface;
use Tale\Uri;
use function Tale\uri;
use function Tale\uri_factory;
use function Tale\uri_parse;
use function Tale\uri_parser_callback;
use function Tale\uri_parser_parse_url;
use function Tale\uri_render;
use function Tale\uri_renderer_callback;
use function Tale\uri_renderer_simple;

class UriFunctionsTest extends TestCase
{
    /**
     * @covers ::\Tale\uri
     */
    public function testUri(): void
    {
        $uri = uri('test', 'test.host', 1234, uri_renderer_callback(function (UriInterface $uri) {
            return "{$uri->getScheme()} - {$uri->getHost()} - {$uri->getPort()}";
        }));
        self::assertSame('test - test.host - 1234', (string)$uri);
    }

    /**
     * @covers ::\Tale\uri_factory
     */
    public function testUriFactory(): void
    {
        $factory = uri_factory(uri_parser_callback(function (string $uriString, Uri\RendererInterface $renderer) {
            return uri('', '', null, $renderer)->withPath('/some/test/path')->withFragment($uriString);
        }, uri_renderer_callback(function (UriInterface $uri) {
            return "{$uri->getPath()} - {$uri->getFragment()}";
        })));
        self::assertSame('/some/test/path - some-fragment', (string)$factory->createUri('some-fragment'));
    }

    /**
     * @covers ::\Tale\uri_parser_parse_url
     */
    public function testUriParserParseUrl(): void
    {
        $parser = uri_parser_parse_url(uri_renderer_callback(function (UriInterface $uri) {
            return "{$uri->getPath()} - {$uri->getFragment()}";
        }));
        self::assertInstanceOf(Uri\Parser\ParseUrlParser::class, $parser);
        self::assertSame(
            '/some/path - some-fragment',
            (string)$parser->parse('http://some.host/some/path#some-fragment')
        );
    }

    /**
     * @covers ::\Tale\uri_parser_callback
     */
    public function testUriParserCallback(): void
    {
        $parser = uri_parser_callback(function (string $uriString, Uri\RendererInterface $renderer) {
            [$path, $fragment] = explode(':', $uriString);
            return uri('', '', null, $renderer)->withPath($path)->withFragment($fragment);
        }, uri_renderer_callback(function (UriInterface $uri) {
            return "{$uri->getPath()} - {$uri->getFragment()}";
        }));
        self::assertInstanceOf(Uri\Parser\CallbackParser::class, $parser);
        self::assertSame('/some/path - some-fragment', (string)$parser->parse('/some/path:some-fragment'));
    }

    /**
     * @covers ::\Tale\uri_renderer_simple
     */
    public function testUriRendererSimple(): void
    {
        $renderer = uri_renderer_simple();
        self::assertInstanceOf(Uri\Renderer\SimpleRenderer::class, $renderer);
    }

    /**
     * @covers ::\Tale\uri_renderer_callback
     */
    public function testUriRendererCallback(): void
    {
        $renderer = uri_renderer_callback(function (UriInterface $uri) {
            return 'test string';
        });
        self::assertInstanceOf(Uri\Renderer\CallbackRenderer::class, $renderer);
        self::assertSame('test string', $renderer->render(uri('http', 'whatever', 1234)->withPath('/test')));
    }

    /**
     * @covers ::\Tale\uri_parse
     */
    public function testUriParse(): void
    {
        self::assertSame('http://google.com', (string)uri_parse('http://google.com'));
        self::assertSame(
            'some string',
            (string)uri_parse('http://google.com', uri_renderer_callback(function (UriInterface $uri) {
                return 'some string';
            }))
        );
    }

    /**
     * @covers ::\Tale\uri_render
     */
    public function testUriRender(): void
    {
        self::assertSame('http://google.com/test', uri_render(uri('http', 'google.com')->withPath('/test')));
    }
}

<?php declare(strict_types=1);

namespace Tale\Test;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\UriInterface;
use Tale\Uri\Renderer\SimpleRenderer;
use function Tale\uri;

/**
 * @coversDefaultClass \Tale\Uri\Renderer\SimpleRenderer
 */
class SimpleRendererTest extends TestCase
{
    /**
     * @covers ::render
     *
     * @dataProvider provideUris
     *
     * @param string $expectedString
     * @param UriInterface $uri
     */
    public function testRender(string $expectedString, UriInterface $uri): void
    {
        $renderer = new SimpleRenderer();
        self::assertSame($expectedString, $renderer->render($uri));
    }

    public function provideUris(): array
    {
        return [
            ['', uri()],
            ['/', uri()->withPath('/')],
            ['/some-path', uri()->withPath('/some-path')],
            ['http:/some-path', uri('http')->withPath('/some-path')],
            ['file:///some-path', uri('file')->withPath('/some-path')],
            [
                'urn:isan:0000-0000-9E59-0000-O-0000-0000-2',
                uri('urn')->withPath('isan:0000-0000-9E59-0000-O-0000-0000-2')
            ],
            ['http://test.com/some-path', uri('http', 'test.com')->withPath('/some-path')],
            ['http://test.com?val1=key1', uri('http', 'test.com')->withQuery('val1=key1')],
            ['http://test.com/?val1=key1', uri('http', 'test.com')->withPath('/')->withQuery('val1=key1')],
            [
                'http://test.com/some-path?val1=key1',
                uri('http', 'test.com')->withPath('/some-path')->withQuery('val1=key1')
            ],
            ['http://test.com#some-fragment', uri('http', 'test.com')->withFragment('some-fragment')],
            ['http://test.com/#some-fragment', uri('http', 'test.com')->withPath('/')->withFragment('some-fragment')],
            [
                'http://test.com/some-path#some-fragment',
                uri('http', 'test.com')->withPath('/some-path')->withFragment('some-fragment')
            ],
            [
                'http://test.com/some-path?val1=key1#some-fragment',
                uri('http', 'test.com')->withPath('/some-path')->withQuery('val1=key1')->withFragment('some-fragment')
            ],
        ];
    }
}

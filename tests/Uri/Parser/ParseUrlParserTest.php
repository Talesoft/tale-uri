<?php declare(strict_types=1);

namespace Tale\Test;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\UriInterface;
use Tale\Uri\Parser\ParseUrlParser;
use Tale\Uri\RendererInterface;

/**
 * @coversDefaultClass \Tale\Uri\Parser\ParseUrlParser
 */
class ParseUrlParserTest extends TestCase
{
    /**
     * @covers ::parse
     * @dataProvider provideUrisToParse
     */
    public function testParse(string $uriString): void
    {
        $parser = new ParseUrlParser();
        self::assertSame($uriString, (string)$parser->parse($uriString));
    }

    public function provideUrisToParse(): array
    {
        return [
            [''],
            ['/'],
            ['//some.host?val1=key1'],
            ['https://google.com'],
            ['file:///some-path#some-fragment'],
            ['http://example.com/some-path?val1=key1#some-fragment'],
            ['ftp://some-user:some-pass@some-host.com/test']
        ];
    }

    /**
     * @covers ::parse
     * @expectedException \InvalidArgumentException
     */
    public function testParseThrowsExceptionOnMalformedUri(): void
    {
        $parser = new ParseUrlParser();
        $uri = $parser->parse(':');
    }

    /**
     * @covers ::__construct
     */
    public function testConstructMakesUseOfPassedRenderer(): void
    {
        $parser = new ParseUrlParser(new class implements RendererInterface
        {
            public function render(UriInterface $uri): string
            {
                return 'test string';
            }
        });
        $uri = $parser->parse('http://test.com/test');
        self::assertSame('test string', (string)$uri);
    }
}

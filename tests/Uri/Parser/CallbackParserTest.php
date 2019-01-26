<?php declare(strict_types=1);

namespace Tale\Test;

use PHPUnit\Framework\TestCase;
use Tale\Uri;
use Tale\Uri\RendererInterface;
use function Tale\uri_parse;

/**
 * @coversDefaultClass \Tale\Uri\Parser\CallbackParser
 */
class CallbackParserTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::parse
     */
    public function testParse(): void
    {
        $parser = new Uri\Parser\CallbackParser(function (string $uriString, RendererInterface $renderer) {
            return uri_parse('https://google.com/search', $renderer)->withQuery("q={$uriString}");
        });
        self::assertSame('https://google.com/search?q=some%20string', (string)$parser->parse('some string'));
    }
}

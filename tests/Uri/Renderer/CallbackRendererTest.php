<?php declare(strict_types=1);

namespace Tale\Test;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\UriInterface;
use Tale\Uri\Renderer\CallbackRenderer;
use function Tale\uri_parse;

/**
 * @coversDefaultClass \Tale\Uri\Renderer\CallbackRenderer
 */
class CallbackRendererTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::render
     */
    public function testRender(): void
    {
        $renderer = new CallbackRenderer(function (UriInterface $uri) {
            return "Let's go to {$uri->getHost()}!";
        });
        self::assertSame('Let\'s go to google.com!', $renderer->render(uri_parse('https://google.com')));
    }
}

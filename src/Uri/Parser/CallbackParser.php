<?php declare(strict_types=1);

namespace Tale\Uri\Parser;

use Psr\Http\Message\UriInterface;
use Tale\Uri\ParserInterface;
use Tale\Uri\Renderer\SimpleRenderer;
use Tale\Uri\RendererInterface;

final class CallbackParser implements ParserInterface
{
    /** @var callable */
    private $callback;

    /** @var RendererInterface */
    private $renderer;

    public function __construct(callable $callback, RendererInterface $renderer = null)
    {
        $this->callback = $callback;
        $this->renderer = $renderer ?? new SimpleRenderer();
    }

    public function parse(string $uriString): UriInterface
    {
        $callback = $this->callback;
        return $callback($uriString, $this->renderer);
    }
}

<?php declare(strict_types=1);

namespace Tale\Uri\Renderer;

use Psr\Http\Message\UriInterface;
use Tale\Uri\RendererInterface;

final class CallbackRenderer implements RendererInterface
{
    /** @var callable */
    private $callback;

    /**
     * CallbackRenderer constructor.
     * @param callable $callback
     */
    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    public function render(UriInterface $uri): string
    {
        $callback = $this->callback;
        return $callback($uri);
    }
}

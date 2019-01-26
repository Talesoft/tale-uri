<?php declare(strict_types=1);

namespace Tale\Uri;

use Psr\Http\Message\UriInterface;

interface RendererInterface
{
    public function render(UriInterface $uri): string;
}

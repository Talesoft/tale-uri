<?php declare(strict_types=1);

namespace Tale\Uri;

use Tale\Uri;
use Psr\Http\Message\UriInterface;

trait FactoryTrait
{
    public function createUri(string $uri = ''): UriInterface
    {
        return Uri::parse($uri);
    }
}

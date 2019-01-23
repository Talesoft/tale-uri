<?php declare(strict_types=1);

namespace Tale;

use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;
use Tale\Uri\Factory;

function uri(string $uri = ''): UriInterface
{
    return uri_factory()->createUri($uri);
}

function uri_factory(): UriFactoryInterface
{
    return new Factory();
}

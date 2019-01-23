<?php declare(strict_types=1);

namespace Tale;

use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;
use Tale\Uri\Factory;

function uri(
    string $scheme = '',
    string $user = '',
    string $password = '',
    string $host = '',
    ?int $port = null,
    string $path = '',
    string $query = '',
    string $fragment = ''
): UriInterface {
    return new Uri($scheme, $user, $password, $host, $port, $path, $query, $fragment);
}

function uri_parse(string $uri = ''): UriInterface
{
    return Uri::parse($uri);
}

function uri_factory(): UriFactoryInterface
{
    return new Factory();
}

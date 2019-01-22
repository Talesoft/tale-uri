<?php declare(strict_types=1);

namespace Tale\Uri;

use Tale\Uri;
use Psr\Http\Message\UriInterface;

trait FactoryTrait
{
    public function createUri(string $uri = ''): UriInterface
    {
        $parts = @parse_url($uri);
        if ($parts === false) {
            throw new \InvalidArgumentException('The given URI is malformed');
        }
        return new Uri(
            $parts['scheme'] ?? '',
            $parts['user'] ?? '',
            $parts['password'] ?? '',
            $parts['host'] ?? '',
            isset($parts['port']) ? (int)$parts['port'] : null,
            $parts['path'] ?? '',
            $parts['query'] ?? '',
            $parts['fragment'] ?? ''
        );
    }
}
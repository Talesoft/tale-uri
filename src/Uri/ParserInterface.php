<?php declare(strict_types=1);

namespace Tale\Uri;

use Psr\Http\Message\UriInterface;

interface ParserInterface
{
    public function parse(string $uriString): UriInterface;
}
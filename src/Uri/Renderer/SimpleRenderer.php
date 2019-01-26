<?php declare(strict_types=1);

namespace Tale\Uri\Renderer;

use Psr\Http\Message\UriInterface;
use Tale\Uri\RendererInterface;

final class SimpleRenderer implements RendererInterface
{
    public function render(UriInterface $uri): string
    {
        $scheme = $uri->getScheme();

        $authority = $uri->getAuthority();
        if ($authority !== '') {
            $authority = "//{$authority}";
        }

        $path = $uri->getPath();
        if ($path !== '' && $authority === '' && $scheme === 'file') {
            $path = "//{$path}";
        }

        if ($scheme !== '') {
            $scheme .= ':';
        }

        $query = $uri->getQuery();
        if ($query !== '') {
            $query = "?{$query}";
        }

        $fragment = $uri->getFragment();
        if ($fragment !== '') {
            $fragment = "#{$fragment}";
        }
        return implode('', [$scheme, $authority, $path, $query, $fragment]);
    }
}
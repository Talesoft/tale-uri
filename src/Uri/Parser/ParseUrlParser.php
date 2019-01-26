<?php declare(strict_types=1);

namespace Tale\Uri\Parser;

use Psr\Http\Message\UriInterface;
use Tale\Uri;
use Tale\Uri\ParserInterface;
use Tale\Uri\Renderer\SimpleRenderer;
use Tale\Uri\RendererInterface;

final class ParseUrlParser implements ParserInterface
{
    /** @var RendererInterface */
    private $renderer;

    public function __construct(RendererInterface $renderer = null)
    {
        $this->renderer = $renderer ?? new SimpleRenderer();
    }

    public function parse(string $uriString): UriInterface
    {
        if ($uriString === '') {
            return new Uri('', '', null, $this->renderer);
        }
        $parts = parse_url($uriString);
        if ($parts === false) {
            throw new \InvalidArgumentException('The given URI is malformed');
        }
        $scheme = $parts['scheme'] ?? '';
        $host = $parts['host'] ?? '';
        $port = isset($parts['port']) ? (int)$parts['port'] : null;
        $uri = new Uri($scheme, $host, $port, $this->renderer);
        if (isset($parts['user'])) {
            $uri = $uri->withUserInfo($parts['user'], $parts['pass'] ?? null);
        }
        if (isset($parts['path'])) {
            $uri = $uri->withPath($parts['path']);
        }
        if (isset($parts['query'])) {
            $uri = $uri->withQuery($parts['query']);
        }
        if (isset($parts['fragment'])) {
            $uri = $uri->withFragment($parts['fragment']);
        }
        return $uri;
    }
}

<?php declare(strict_types=1);

namespace Tale;

use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;
use Tale\Uri\Parser\CallbackParser;
use Tale\Uri\Parser\ParseUrlParser;
use Tale\Uri\ParserInterface;
use Tale\Uri\Renderer\CallbackRenderer;
use Tale\Uri\Renderer\SimpleRenderer;
use Tale\Uri\RendererInterface;

function uri(
    string $scheme = '',
    string $host = '',
    ?int $port = null,
    RendererInterface $renderer = null
): UriInterface {
    return new Uri($scheme, $host, $port, $renderer);
}

function uri_factory(ParserInterface $parser = null): UriFactoryInterface
{
    return new UriFactory($parser);
}

function uri_parser_parse_url(RendererInterface $renderer = null): ParserInterface
{
    return new ParseUrlParser($renderer);
}

function uri_parser_callback(callable $callback, RendererInterface $renderer = null): ParserInterface
{
    return new CallbackParser($callback, $renderer);
}

function uri_renderer_simple(): RendererInterface
{
    return new SimpleRenderer();
}

function uri_renderer_callback(callable $callback): RendererInterface
{
    return new CallbackRenderer($callback);
}

function uri_parse(string $uri, RendererInterface $renderer = null): UriInterface
{
    return uri_parser_parse_url($renderer)->parse($uri);
}

function uri_render(UriInterface $uri): string
{
    return uri_renderer_simple()->render($uri);
}

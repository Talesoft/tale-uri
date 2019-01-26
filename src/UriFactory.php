<?php declare(strict_types=1);

namespace Tale;

use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;
use Tale\Uri\Parser\ParseUrlParser;
use Tale\Uri\ParserInterface;

final class UriFactory implements UriFactoryInterface
{
    /** @var ParserInterface */
    private $parser;

    /**
     * UriFactory constructor.
     * @param ParserInterface $parser
     */
    public function __construct(ParserInterface $parser = null)
    {
        $this->parser = $parser ?? new ParseUrlParser();
    }

    public function createUri(string $uri = ''): UriInterface
    {
        return $this->parser->parse($uri);
    }
}

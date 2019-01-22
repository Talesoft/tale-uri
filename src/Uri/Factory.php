<?php declare(strict_types=1);

namespace Tale\Uri;

use Psr\Http\Message\UriFactoryInterface;

final class Factory implements UriFactoryInterface
{
    use FactoryTrait;
}
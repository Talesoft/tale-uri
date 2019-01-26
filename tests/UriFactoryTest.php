<?php declare(strict_types=1);

namespace Tale\Test;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\UriInterface;
use Tale\Uri\ParserInterface;
use Tale\UriFactory;
use function Tale\uri;
use function Tale\uri_factory;

/**
 * @coversDefaultClass \Tale\UriFactory
 */
class UriFactoryTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::createUri
     */
    public function testCreateUri(): void
    {
        $factory = new UriFactory();
        $uri = $factory->createUri('http://test.host/some-path');
        self::assertSame('http', $uri->getScheme());
        self::assertSame('test.host', $uri->getHost());
        self::assertSame('/some-path', $uri->getPath());
    }
    /**
     * @covers ::__construct
     * @covers ::createUri
     * @expectedException \InvalidArgumentException
     */
    public function testCreateUriThrowsExceptionOnMalformedUri(): void
    {
        $factory = new UriFactory();
        $uri = $factory->createUri(':');
    }

    /**
     * @covers ::__construct
     */
    public function testConstructMakesUseOfPassedParser(): void
    {
        $factory = uri_factory(new class implements ParserInterface
        {
            public function parse(string $uriString): UriInterface
            {
                return uri()->withUserInfo('test-user', 'test-password');
            }
        });
        $uri = $factory->createUri('http://test.com/test');
        self::assertSame('test-user:test-password', $uri->getUserInfo());
    }
}

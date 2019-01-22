<?php declare(strict_types=1);

namespace Tale\Test\Uri;

use PHPUnit\Framework\TestCase;
use Tale\Uri\Factory;

/**
 * @coversDefaultClass \Tale\Uri\Factory
 */
class FactoryTest extends TestCase
{
    /**
     * @covers ::createUri
     */
    public function testCreateUri(): void
    {
        $factory = new Factory();
        $uri = $factory->createUri('http://test.host/some-path');
        self::assertSame('http', $uri->getScheme());
        self::assertSame('test.host', $uri->getHost());
        self::assertSame('/some-path', $uri->getPath());
    }
    /**
     * @covers ::createUri
     * @expectedException \InvalidArgumentException
     */
    public function testCreateUriThrowsExceptionOnMalformedUri(): void
    {
        $factory = new Factory();
        $uri = $factory->createUri(':');
    }
}

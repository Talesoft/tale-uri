<?php declare(strict_types=1);

namespace Tale\Test;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\UriInterface;
use Tale\Uri;
use function Tale\uri;

/**
 * @coversDefaultClass \Tale\Uri
 */
class UriTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::getScheme
     * @covers ::filterScheme
     *
     * @dataProvider provideSchemes
     *
     * @param string $expectedScheme
     * @param string $scheme
     */
    public function testGetScheme(string $expectedScheme, string $scheme): void
    {
        $uri = new Uri($scheme);
        self::assertSame($expectedScheme, $uri->getScheme());
    }

    /**
     * @covers ::__construct
     * @covers ::getScheme
     * @covers ::withScheme
     * @covers ::filterScheme
     *
     * @dataProvider provideSchemes
     *
     * @param string $expectedScheme
     * @param string $scheme
     */
    public function testWithScheme(string $expectedScheme, string $scheme): void
    {
        $uri = new Uri('test');
        $newUri = $uri->withScheme($scheme);
        self::assertNotSame($uri, $newUri);
        self::assertSame('test', $uri->getScheme());
        self::assertSame($expectedScheme, $newUri->getScheme());
    }

    /**
     * @covers ::__construct
     * @covers ::withScheme
     * @covers ::filterScheme
     *
     * @dataProvider provideNonStringArguments
     * @expectedException \InvalidArgumentException
     *
     * @param $argumentValue
     */
    public function testWithSchemeThrowsExceptionOnInvalidArgument($argumentValue): void
    {
        $uri = new Uri();
        $uri->withScheme($argumentValue);
    }

    public function provideSchemes(): array
    {
        return [
            ['', ''],
            ['http', 'http'],
            ['file', 'file:'],
            ['ssl+git', 'ssl+git']
        ];
    }

    /**
     * @covers ::__construct
     * @covers ::getUserInfo
     * @covers ::filterUser
     * @covers ::filterPassword
     * @covers ::encode
     *
     * @dataProvider provideUserInfos
     * @param string $expectedUserInfo
     * @param string $user
     * @param string $password
     */
    public function testGetUserInfo(string $expectedUserInfo, string $user, string $password): void
    {
        $uri = uri()->withUserInfo($user, $password);
        self::assertSame($expectedUserInfo, $uri->getUserInfo());
    }

    /**
     * @covers ::__construct
     * @covers ::getUserInfo
     * @covers ::withUserInfo
     * @covers ::filterUser
     * @covers ::filterPassword
     * @covers ::encode
     *
     * @dataProvider provideUserInfos
     *
     * @param string $expectedUserInfo
     * @param string $user
     * @param string $password
     */
    public function testWithUserInfo(string $expectedUserInfo, string $user, string $password): void
    {
        $uri = uri()->withUserInfo('test-user', 'test-password');
        $newUri = $uri->withUserInfo($user, $password);
        self::assertNotSame($uri, $newUri);
        self::assertSame('test-user:test-password', $uri->getUserInfo());
        self::assertSame($expectedUserInfo, $newUri->getUserInfo());
    }

    /**
     * @covers ::__construct
     * @covers ::withUserInfo()
     * @covers ::filterUser
     *
     * @dataProvider provideNonStringArguments
     * @expectedException \InvalidArgumentException
     *
     * @param $argumentValue
     */
    public function testWithUserInfoThrowsExceptionOnInvalidUser($argumentValue): void
    {
        $uri = new Uri();
        $uri->withUserInfo($argumentValue);
    }

    /**
     * @covers ::__construct
     * @covers ::withUserInfo()
     * @covers ::filterPassword
     *
     * @dataProvider provideInvalidPasswords
     * @expectedException \InvalidArgumentException
     *
     * @param $argumentValue
     */
    public function testWithUserInfoThrowsExceptionOnInvalidPassword($argumentValue): void
    {
        $uri = new Uri();
        $uri->withUserInfo('test', $argumentValue);
    }

    public function provideUserInfos(): array
    {
        return [
            //expectedUserInfo, user, password
            ['', '', ''],
            ['', '', 'test-password'],
            ['some-user', 'some-user', ''],
            ['some-user:some-password', 'some-user', 'some-password'],
            ['some%23user:some%23password', 'some%23user', 'some#password']
        ];
    }

    /**
     * @covers ::__construct
     * @covers ::getHost
     * @covers ::filterHost
     */
    public function testGetHost(): void
    {
        $uri = new Uri('', 'test.host');
        self::assertSame('test.host', $uri->getHost());

        $uri = new Uri('', 'TEST.hoST');
        self::assertSame('test.host', $uri->getHost());
    }

    /**
     * @covers ::__construct
     * @covers ::withHost
     * @covers ::getHost
     * @covers ::filterHost
     */
    public function testWithHost(): void
    {
        $uri = new Uri('', 'test.host');
        $newUri = $uri->withHost('other.host');
        self::assertNotSame($uri, $newUri);
        self::assertSame('test.host', $uri->getHost());
        self::assertSame('other.host', $newUri->getHost());
    }

    /**
     * @covers ::__construct
     * @covers ::withHost
     * @covers ::filterHost
     *
     * @dataProvider provideNonStringArguments
     * @expectedException \InvalidArgumentException
     *
     * @param $argumentValue
     */
    public function testWithHostThrowsExceptionOnInvalidArgument($argumentValue): void
    {
        $uri = new Uri();
        $uri->withHost($argumentValue);
    }

    /**
     * @covers ::__construct
     * @covers ::getPort
     * @covers ::filterPort
     */
    public function testGetPort(): void
    {
        $uri = new Uri('', '', 15);
        self::assertSame(15, $uri->getPort());
        
        $uri = new Uri('', '', 0);
        self::assertNull($uri->getPort());
    }

    /**
     * @covers ::__construct
     * @covers ::withPort
     * @covers ::getPort
     * @covers ::filterPort
     */
    public function testWithPort(): void
    {
        $uri = new Uri('', '', 15);
        $newUri = $uri->withPort(27);
        self::assertNotSame($uri, $newUri);
        self::assertSame(15, $uri->getPort());
        self::assertSame(27, $newUri->getPort());
    }

    /**
     * @covers ::__construct
     * @covers ::withPort
     * @covers ::filterPort
     *
     * @dataProvider provideInvalidPorts
     * @expectedException \InvalidArgumentException
     *
     * @param $argumentValue
     */
    public function testWithPortThrowsExceptionOnInvalidArgument($argumentValue): void
    {
        $uri = new Uri();
        $uri->withPort($argumentValue);
    }

    /**
     * @covers ::__construct
     * @covers ::getAuthority
     * @covers ::filterHost
     * @covers ::filterPort
     * @covers ::filterUser
     * @covers ::filterPassword
     *
     * @dataProvider provideUrisForGetAuthorityTest
     *
     * @param string $expectedAuthority
     * @param UriInterface $uri
     */
    public function testGetAuthority(string $expectedAuthority, UriInterface $uri): void
    {
        self::assertSame($expectedAuthority, $uri->getAuthority());
    }

    public function provideUrisForGetAuthorityTest(): array
    {
        return [
            ['', uri()],
            ['', uri('', '', 20)],
            ['test.host:20', uri('', 'test.host', 20)],
            ['', uri()->withUserInfo('test')],
            ['user@test.host', uri('', 'test.host')->withUserInfo('user')],
            ['user@test.host:35', uri('', 'test.host', 35)->withUserInfo('user')],
            ['test.host', uri('', 'test.host')->withUserInfo('', 'test')],
            ['', uri('', '', 20)->withUserInfo('test', 'test')],
            ['user:pass@test.host:20', uri('', 'test.host', 20)->withUserInfo('user', 'pass')],

        ];
    }

    /**
     * @covers ::__construct
     * @covers ::getPath
     * @covers ::filterPath
     * @covers ::encode
     *
     * @dataProvider providePaths
     *
     * @param string $expectedPath
     * @param string $path
     */
    public function testGetPath(string $expectedPath, string $path): void
    {
        $uri = uri()->withPath($path);
        self::assertSame($expectedPath, $uri->getPath());
    }

    /**
     * @covers ::__construct
     * @covers ::getPath
     * @covers ::withPath
     * @covers ::filterPath
     * @covers ::encode
     *
     * @dataProvider providePaths
     *
     * @param string $expectedPath
     * @param string $path
     */
    public function testWithPath(string $expectedPath, string $path): void
    {
        $uri = uri()->withPath('/test-path');
        $newUri = $uri->withPath($path);
        self::assertNotSame($uri, $newUri);
        self::assertSame('/test-path', $uri->getPath());
        self::assertSame($expectedPath, $newUri->getPath());
    }

    /**
     * @covers ::__construct
     * @covers ::withPath
     * @covers ::filterPath
     *
     * @dataProvider provideInvalidPaths
     * @expectedException \InvalidArgumentException
     *
     * @param $argumentValue
     */
    public function testWithPathThrowsExceptionOnInvalidArgument($argumentValue): void
    {
        $uri = new Uri();
        $uri->withPath($argumentValue);
    }

    public function providePaths(): array
    {
        return [
            ['', ''],
            ['/', '/'],
            ['/some-path', '/some-path'],
            ['/some%23path/sub%C2%A7path', '/some%23path/sub§path']
        ];
    }

    /**
     * @covers ::__construct
     * @covers ::getQuery
     * @covers ::filterQuery
     * @covers ::encode
     *
     * @dataProvider provideQueries
     *
     * @param string $expectedQuery
     * @param string $query
     */
    public function testGetQuery(string $expectedQuery, string $query): void
    {
        $uri = uri()->withQuery($query);
        self::assertSame($expectedQuery, $uri->getQuery());
    }

    /**
     * @covers ::__construct
     * @covers ::getQuery
     * @covers ::withQuery
     * @covers ::filterQuery
     * @covers ::encode
     *
     * @dataProvider provideQueries
     *
     * @param string $expectedQuery
     * @param string $query
     */
    public function testWithQuery(string $expectedQuery, string $query): void
    {
        $uri = uri()->withQuery('val1=key1');
        $newUri = $uri->withQuery($query);
        self::assertNotSame($uri, $newUri);
        self::assertSame('val1=key1', $uri->getQuery());
        self::assertSame($expectedQuery, $newUri->getQuery());
    }

    /**
     * @covers ::__construct
     * @covers ::withQuery
     * @covers ::filterQuery
     *
     * @dataProvider provideNonStringArguments
     * @expectedException \InvalidArgumentException
     *
     * @param $argumentValue
     */
    public function testWithQueryThrowsExceptionOnInvalidArgument($argumentValue): void
    {
        $uri = new Uri();
        $uri->withQuery($argumentValue);
    }

    public function provideQueries(): array
    {
        return [
            //expectedFragment, fragment
            ['', ''],
            ['key1', 'key1'],
            ['key1=val1', '?key1=val1'],
            ['?key1=val1', '??key1=val1'],
            ['key1=val1', 'key1=val1'],
            ['key1=val1&key2=val2', 'key1=val1&key2=val2'],
            ['ke%23y1=val1&key%232=val2', 'ke#y1=val1&key%232=val2']
        ];
    }

    /**
     * @covers ::__construct
     * @covers ::getFragment
     * @covers ::filterFragment
     * @covers ::encode
     *
     * @dataProvider provideFragments
     *
     * @param string $expectedFragment
     * @param string $fragment
     */
    public function testGetFragment(string $expectedFragment, string $fragment): void
    {
        $uri = uri()->withFragment($fragment);
        self::assertSame($expectedFragment, $uri->getFragment());
    }

    /**
     * @covers ::__construct
     * @covers ::withFragment
     * @covers ::getFragment
     * @covers ::filterFragment
     * @covers ::encode
     *
     * @dataProvider provideFragments
     *
     * @param string $expectedFragment
     * @param string $fragment
     */
    public function testWithFragment(string $expectedFragment, string $fragment): void
    {
        $uri = uri()->withFragment('test-fragment');
        $newUri = $uri->withFragment($fragment);
        self::assertNotSame($uri, $newUri);
        self::assertSame('test-fragment', $uri->getFragment());
        self::assertSame($expectedFragment, $newUri->getFragment());
    }

    /**
     * @covers ::__construct
     * @covers ::withFragment
     * @covers ::filterFragment
     *
     * @dataProvider provideNonStringArguments
     * @expectedException \InvalidArgumentException
     *
     * @param $argumentValue
     */
    public function testWithFragmentThrowsExceptionOnInvalidArgument($argumentValue): void
    {
        $uri = new Uri();
        $uri->withFragment($argumentValue);
    }

    public function provideFragments(): array
    {
        return [
            //expectedFragment, fragment
            ['test-fragment', 'test-fragment'],
            ['test-fragment', '#test-fragment'],
            ['%23test-fragment', '##test-fragment'],
            ['/test/fragment', '/test/fragment'],
            ['%C2%A7fragment', '§fragment'],
            ['%C2%A7fragment', '%C2%A7fragment']
        ];
    }

    /**
     * @covers ::__construct
     * @covers ::__toString
     */
    public function testToString(): void
    {
        $uri = uri('http', 'test.com')->withPath('/some-path')->withQuery('val1=key1')->withFragment('some-fragment');
        $expected = 'http://test.com/some-path?val1=key1#some-fragment';
        //We call it twice to make sure it's consistent across multiple string-casts
        self::assertSame($expected, (string)$uri);
        self::assertSame($expected, (string)$uri);
    }

    /**
     * @covers ::__clone
     */
    public function testClone(): void
    {
        $uri = uri('http', 'test.com')->withPath('/some-path')->withQuery('val1=key1')->withFragment('some-fragment');
        self::assertSame('http://test.com/some-path?val1=key1#some-fragment', (string)$uri);
        $newUri = $uri->withScheme('https')
            ->withHost('other.host')
            ->withPath('/other-path');
        self::assertSame('https://other.host/other-path?val1=key1#some-fragment', (string)$newUri);
    }

    public function provideNonStringArguments(): array
    {
        return [
            [1],
            [1.1],
            [true],
            [null],
            [[]],
            [[1]],
            [new \stdClass()],
            [stream_context_create()]
        ];
    }

    public function provideInvalidPasswords(): array
    {
        return [
            [1],
            [1.1],
            [true],
            [[]],
            [[1]],
            [new \stdClass()],
            [stream_context_create()]
        ];
    }

    public function provideInvalidPaths(): array
    {
        return array_merge($this->provideNonStringArguments(), [
            ['?some=query'],
            ['/?some=query'],
            ['#some-fragment'],
            ['/#some-fragment']
        ]);
    }

    public function provideInvalidPorts(): array
    {
        return [
            [-30],
            [1.1],
            [1224445],
            [true],
            [new \stdClass()],
            [stream_context_create()]
        ];
    }
}

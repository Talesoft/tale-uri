
[![Packagist](https://img.shields.io/packagist/v/talesoft/tale-uri.svg?style=for-the-badge)](https://packagist.org/packages/talesoft/tale-uri)
[![License](https://img.shields.io/github/license/Talesoft/tale-uri.svg?style=for-the-badge)](https://github.com/Talesoft/tale-uri/blob/master/LICENSE.md)
[![CI](https://img.shields.io/travis/Talesoft/tale-uri.svg?style=for-the-badge)](https://travis-ci.org/Talesoft/tale-uri)
[![Coverage](https://img.shields.io/codeclimate/coverage/Talesoft/tale-uri.svg?style=for-the-badge)](https://codeclimate.com/github/Talesoft/tale-uri)

Tale Uri
========

What is Tale Uri?
-----------------

This is a basic and lightweight implementation of the 
`Psr\Http\Message\UriInterface` and the `Psr\Http\Message\UriFactoryInterface`. 

It doesn't add any extra methods, they are straight and direct implementations
without any overhead.

It's useful in cases where you simply just want URI abstraction,
but not a full HTTP layer with it. It's also useful for library
authors for testing with dependencies on `Psr\Http\Message\UriInterface`

Installation
------------

```bash
composer require talesoft/tale-uri
```

Usage
-----

`use Tale\Uri;`

The heart, `Tale\Uri`, is just a basic and direct implementation
of PSR-7's `Psr\Http\Message\UriInterface`.

```php
$uri = new Uri('https', '', '', 'google.com', '/search', 'q=test');

echo $uri->getAuthority(); 
//"google.com"

echo (string)$uri; 
//"https://google.com/search?q=test"
    
```

### Using the factory

`use Tale\Uri\Factory;`

The factory is a direct implementation of PSR-17's 
`Psr\Http\Message\UriFactoryInterface` and works with
PHP's `parse_url`. You can always use your own factory
to create new `Tale\Uri` instances.

```php
use Tale\Uri\Factory;

$factory = new Factory();

$uri = $factory->createUri('mysql://root:pass@localhost:3306/test');

echo $uri->getScheme();
//"mysql"

echo $uri->getUserInfo(); 
//"root:pass"

echo $uri->getHost();
//"root"
```

If you have a Dependency Injection container, you can inject
the factory if you registered it as a service

```php
use Psr\Http\Message\UriFactoryInterface;

class MyService
{
    private $uriFactory;
    
    public function __construct(UriFactoryInterface $uriFactory)
    {
        $this->uriFactory = $uriFactory;    
    }
    
    public function doStuff(): void
    {
        $uri = $this->uriFactory->createUri('https://google.com');
        
        //etc. etc.
    }
}
```

#### Roll your own URI factory

`use Tale\Uri\FactoryTrait;`

If you already have some kind of URI or HTTP factory
or want to roll your own one, there is a trait for you to use
that basically gives you the full functionality of the
default factory.

```php
use Psr\Http\Message\UriFactoryInterface;

class MyUriFactory implements UriFactoryInterface
{
    use FactoryTrait;
    
    //other implementations and stuff
}
```

You can then register it in your DI container and all services
will start using your own implementation.


**That's it.**

This is and probably will ever be the single purpose and content 
of this library (along with 100% test coverage)


### Q & A

**Q. Why is there no `Uri::fromString(string $uri)` method or similar?**

This library is designed to create the least possible dependencies
in order to adhere to the URI standards. A `Uri::fromString(string $uri)`, by 
design, would need to be opinionated on the way strings are parsed 
to Uri instances and `parse_url` is not always what you want. That's
also the reason why the factory is written as loose as possible, it's
basically just an adapter for `parse_url` to be swapped out by
your own implementation at any point.
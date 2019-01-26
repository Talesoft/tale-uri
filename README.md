
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
composer req talesoft/tale-uri
```

Usage
-----

Check out the [Functions File](https://github.com/Talesoft/tale-uri/blob/master/src/functions.php) 
to see all things this library does.

### Parse and modify URIs easily

```php
use function Tale\uri_parse;

$uri = uri_parse('https://google.com/search');
//$uri is a strict implementation of PSR-7's UriInterface

echo $uri->getScheme(); //"https"
echo $uri->getHost(); "google.com"
echo $uri->getPath(); //"/search"

echo $uri->withHost("talesoft.codes"); "https://talesoft.codes/search"
```

### Create an URI factory for DI containers

```php
use Psr\Http\Message\UriFactoryInterface;
use Tale\UriFactory;

$container->add(UriFactory::class);

//...

$uriFactory = $container->get(UriFactoryInterface::class);

$uri = $uriFactory->createUri('https://example.com#test');

echo $uri->getFragment(); //"test"
```
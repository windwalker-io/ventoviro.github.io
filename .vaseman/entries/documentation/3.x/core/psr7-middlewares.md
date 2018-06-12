---
layout: documentation.twig
title: Psr7 and Middlewares

---

Windwlaker Application follows [PSR7 Http Messages](http://www.php-fig.org/psr/psr-7/) standard, provides an interface
to handle request and response.

## Get Request and Response

```php
$app = \Windwalker\Ioc::getApplication();

$request = $app->request;
$response = $app->response;
```

Get Request data:

```php
$request->getHeaders();
$request->getSeverParams();
$request->getUploadedFiles();
```

Set response body:

```php
$response->getBody()->write('Hello World');
```

Set Response headers:

```php
// Response is immutable so we must reutrn new object.
$response = $response->withHeader('X-Foo', 'Bar');

// Set new object back to app
$app->setResponse($response);
```

Use Json response:

```php
use Windwalker\Http\Response\JsonResponse;

$app->setResponse(new JsonResponse(['foo' => 'bar']));
```

See [Windwalker Http Package](https://github.com/ventoviro/windwalker-http)

## Middlewares

We can add middleware to process before and after logic when application running, create a middleware class:

```php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Windwalker\Middleware\MiddlewareInterface;

class MyMiddleware extends AbstractWebMiddleware
{
	/**
	 * Middleware logic to be invoked.
	 *
	 * @param   Request                      $request  The request.
	 * @param   Response                     $response The response.
	 * @param   callable|MiddlewareInterface $next     The next middleware.
	 *
	 * @return  Response
	 */
	public function __invoke(Request $request, Response $response, $next = null)
	{
	    // Perform before

		$response = $next($request, $response);

		// Perform after

		return $response;
	}
}
```

Register this middleware to `etc/app/web.php` (of `dev.php` if only use in debug mode).
Use numeric key name to control the execution ordering, the biggest number will execute first,
and the smaller number will nearer to core logic.

```php
// ...

    'middlewares' => [
        //900  => \Windwalker\Core\Application\Middleware\SessionRaiseMiddleware::class,
        //800  => \Windwalker\Core\Application\Middleware\RoutingMiddleware::class,
        500 => MyMiddleware::class
    ]
```

You can also use callback as middleware:

```php
// ...

    'middlewares' => [
        500 => ['MyClass', 'execute']
    ]
```

If you want to use `Closure` as middleware, do not add it to config file since it will break the cache serializer,
add it in application `init()`.

```php
<?php
// src/Windwalker/Web/Application.php

namespace Windwalker\Web;

use Windwalker\Core\Application\WebApplication;
use Windwalker\Core\Provider;

class Application extends WebApplication
{
	// ...

	protected function init()
	{
		parent::init();

		$this->addMiddleware(function (Request $request, Response $response, $next = null)
        {
            // ...
        });
	}
```

Or write a trait to add group of middlewares:

```php
trait MyMiddlewaresTrait
{
    // Must have boot{TraitName}() method then application will suto boot it
    // since it is a BootableTrait instance
	public function bootMyMiddlewaresTrait()
	{
		$this->addMiddleware(MyMiddleware::class, 600);
		$this->addMiddleware(function () { ... }, 400);
		$this->addMiddleware([$this, 'callback'], 400);
	}
}
```

Then use it in Application:

```php
class Application extends WebApplication
{
	use MyMiddlewaresTrait;

	// ...
```

## Middleware in Package

Package uses same interface as application so we can also use `AbstractWebMiddleware` or Psr7 invokable callable
as package middleware.

Add middleware in package config:

```php
// etc/package/flower.php
// OR
// src/Flower/Resources/config/config.dist.php

    'middlewares' => [
        500 => MyMiddleware::class
    ]
```

You can also add middleware runtime in package `boot()` method:

```php
class FlowerPackage extends AbstractPackage
{
	// ...

	protected function boot()
	{
		parent::boot();

		$this->addMiddleware(function (Request $request, Response $response, $next = null)
        {
            // ...
        });
	}
```

Also supports trait:

```php
class FlowerPackage extends AbstractPackage
{
    use MyMiddlewaresTrait;

    // ...
```

### Set All Response by JSON of a Package
 
In package config:

```php
    'middlewares' => [
        \Windwalker\Core\Application\Middleware\JsonResponseWebMiddleware::class
    ]
```

Now all routing to this package will return JSON response no matter error or not.

## Available Middlewares for Application and Packages
  
- `Windwalker\Core\Application\Middleware\JsonResponseWebMiddleware`
- `Windwalker\Core\Application\Middleware\RoutingMiddleware`
- `Windwalker\Core\Application\Middleware\SessionRaiseMiddleware`

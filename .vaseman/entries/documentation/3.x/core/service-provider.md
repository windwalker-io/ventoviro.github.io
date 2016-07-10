---
layout: documentation.twig
title: Service Provider

---

## Introduction of Service Provider

Service Provider is an useful way to encapsulate logic for creating objects and services. For example, a mailer library,
custom listeners or 3rd tools. Some libraries need a bootstrapping process, we should put this process in Service Provider.

Service Provider works with IoC Container, we must implement the `\Windwalker\DI\ServiceProviderInterface`
and write all our logic in `register()` and `boot()` method then set our objects into container.
 
### Basic Provider Example

Take a look of this example code, in a original way, if we have a MongoDB driver to bootstrap, we'll write an bootstrap file.

##### Original Way

``` php
// ./import.mongodb.php

// This is a fake MongoDB connector
$conn = new MongoDBConnection($config['database']['mongodb']);

return $conn;
```

And include this file:

``` php
$mongo = include_once __DIR__ . '/import.mongodb.php';
```

This is not the best practice to include a 3rd service in our system, so we use provider to handle it.

##### Windwalker Way

In Windwalker, Create a `MongoDBServiceProvider`, and set MongoDB connection to Container.

``` php
use Windwalker\DI\Container;
use Windwalker\DI\ServiceProviderInterface;

class MongoDBServiceProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $closure = function (Container $container)
       {
           $config = $container->get('config');

           return new MongoDBConnection($config->get('database.mongodb'));
       }

        $container->share(MongoDBServiceProvider::class, $closure)->alias('mongo.db', MongoDBServiceProvider::class);
    }
}

```

Register this provider in container:

``` php
$container->registerServiceProvider(new MongoDBServiceProvider);
```

Then get this object from Container, the `MongoDBConnection` will be lazy loading, MongoDB will connected after your first get it:

``` php
$mongo = $container->get('mongo.db');

// OR

$mongo = Ioc::get('mongo.db');
```

About how to use IoC (DI) Container, see: [IoC Container](ioc-container.html)

## Registering Providers

All Service Providers are registered in config, add your own or 3rd providers in `etc/app/windwalker.php` or `etc/app/web.php`:

``` php
// etc/app/windwalker.php

// ...

    'providers' => [
        'mongodb' => MongoDBServiceProvider::class
    ]

// ...
```

## Boot Service

If the service need to be boot when system initializing, we can add a `boot()` method to provider class.
This is an error handler provider example, we must register error handler instantly after providers registered.

``` php
class ErrorHandlingProvider implements ServiceProviderInterface
{
	public function boot(Container $container)
	{
		$handler = $container->get('error.handler');

		// Register instantly after providers registered.
		$handler->register();
	}

	public function register(Container $container)
	{
		$closure = function (Container $container)
		{
			return $container->createSharedObject(ErrorManager::class);
		};

		$container->share('error.handler', $closure);
	}
}
```

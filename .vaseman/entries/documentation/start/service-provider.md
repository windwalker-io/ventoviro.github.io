layout: documentation.twig
title: Service Provider

---

# Introduction of Service Provider

Service Provider is an useful way to encapsulate logic of creating objects and services. For example, a mailer library, 
custom listeners or 3rd tools. Some libraries need a bootstrapping process, we should put this process in Service Provider.

Service Provider will working with IoC Container, we'll implement the `\Windwalker\DI\ServiceProviderInterface` 
and write all our logic in `register()` method and set our service objects into container.
 
## Basic Provider Example

Take a look of this example code, in a original way, if we have a MongoDB driver to bootstrap, we'll write an bootstrap file.

### Original Way

``` php
// import.mongodb.php

$conn = new MongoDBConnection($config['database']['mongodb']);

return $conn;
```

And include this file:

``` php
$mongo = include_once 'import.mongodb.php';
```

### Windwalker Way

In Windwalker, we can create a `MongoDBServiceProvider`, and set MongoDB connection to Container.

``` php
use Windwalker\DI\Container;
use Windwalker\DI\ServiceProviderInterface;

class MongoDBServiceProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container->share('mongo.db', function (Container $container)
        {
            $config = $container->get('config');

            return new MongoDBConnection($config->get('database.mongodb'));
        });
    }
}

```

Register this provider to container:

``` php
$container->registerServiceProvider(new MongoDBServiceProvider);
```

Then get this object from Container, this process will be lazy loading, MongoDB will connected after your first get it:

``` php
$mongo = $container->get('mongo.db');

// OR

$mongo = Ioc::get('mongo.db');
```

About how to use IoC (DI) Container, see: [IoC Container](ioc-container.html)

# Registering Providers

All Service Providers are registered in `Application::loadProviders()`. For example, open `/src/Windwalker/Web/Application.php`, 
you will see `loadProviders()`, there is an array `$providers` here, help ue override default providers, now we add our Service Provider here:

``` php
public function loadProviders()
{
    $providers = parent::loadProviders();

    // Default providers ...

    /*
     * Custom Providers:
     * -----------------------------------------
     * You can add your own providers here. If you installed a 3rd party packages from composer,
     * but this package need some init logic, create a service provider to do this and register it here.
     */

    // Custom Providers here...
    $providers['mongodb'] = new MongoDBServiceProvider;

    return $providers;
}
```

There are 3 positions in default Windwalker Application we can add providers:

- `src/Windwalker/Web/Application.php`
- `src/Windwalker/Console/Application/php`
- `src/Windwalker.php`

If you want to use service for Web application, add provider in `Web\Application`, else you can add provider in `Console\Application`
for console use, or add it in `Windwalker.php` for both.


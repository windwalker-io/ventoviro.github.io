---
layout: documentation.twig
title: IoC Container
redirect:
    2.1: start/ioc-container

---

## What is Ioc Container

Windwalker DI is a [dependency injection](http://en.wikipedia.org/wiki/Dependency_injection) tools,
provide us an [IOC](http://en.wikipedia.org/wiki/Inversion_of_control) container to manage objects and data.
We also support service provider to help developers build their service in a universal interface.

> For more information about IOC and DI, please see
[Inversion of Control Containers and the Dependency Injection pattern](http://martinfowler.com/articles/injection.html) by Martin Fowler.

## Get IoC Container

Use internal container in controller and Application:

```php
$session = $this->container->get('system.session');
```

Or use static method in `Ioc` class to get it:

```php
$container = \Windwalker\Ioc::factory(); // This container is singleton
```

Now we can store objects into it.

```php
$input = new Input();

$container->set('my.input', $input);

$input = $container->get('my.input');
```

Or get it by `Ioc:get()` statically:

```php
\Windwalker\Ioc::get('my.input');
```

### Get Sub Container

Every package will use a child container, if the key in child container not found, container will search from parent:

```php
// Get FlowerPackage container
$container = Ioc::factory('flower');

// Set something
$container->set('sakura', 'Sakura');

// sakura exists
$sakura = $container->get('sakura');

// rose not exists, will find from parent.
$rose = $container->get('rose');
```

Directly get object from sub container by `Ioc`:

```php
\Windwalker\Ioc::get('sakura', 'flower');
```

## Lazy Loading

Sometimes we will hope not create object instantly, we can use callback to create object.

```php
// Set a closure into it
$container->set('input', function(Container $container)
{
    return new Input();
});

// Will call this closure when we get it
$input = $container->get('input');
```

But if we use `set()` method to set callback, this object will be recreated when every time we try to get it.

## Shared Object (Singleton)

Use `set('foo', $object, true)` or `share('foo', $object)` to make an object singleton, we'll always get the same instance.

```php
// Share a closure
$container->share('input', function(Container $container)
{
    return new Input();
});

// Will will always get same instance
$input = $container->get('input');

// The second argument of get() can force create new instance
$newInput = $container->get('input', true);

// Use readable constant
$newInput = $container->get('input', Container::FORCE_NEW);
```

## Protect Object

Use `protect()` to prevent others override your important object.

```php
$container->protect(
    'input',
    function(Container $container)
    {
        return new Input();
    },
    true // Shared or not
);

// We can still get this object
$input = $container->get('input');

// @Throws OutOfBoundsException
$container->set('input', $otherInput);
```

## Alias

```php
$container->share('system.application', $app)
    ->alias('app', 'system.application');

// Same as system.application
$app = $container->get('app');
```

## Creating Objects

### New Instance

Container can help us create an object and auto inject required arguments to constructor.

```php
use Windwalker\IO\Input;
use Windwalker\Structure\Structure;

class MyClass
{
    public $input;
    public $config;

    public function __construct(Input $input, Structure $config)
    {
        $this->input = $input;
        $this->config = $config;
    }
}

$myObject = $container->newInstance('MyClass');

$myObject->input; // Input
$myObject->config; // Structure
```

## Creating Objects

### New Instance

Container can help us create an object and auto inject required arguments to constructor.

```php
use Windwalker\IO\Input;
use Windwalker\Structure\Structure;

class MyClass
{
    public $input;
    public $config;

    public function __construct(Input $input, Structure $config)
    {
        $this->input = $input;
        $this->config = $config;
    }
}

$myObject = $container->newInstance('MyClass');

$myObject->input; // Input
$myObject->config; // Structure
```

### Create Object

Create object will new an instance instantly and set this class into container, we can get new instance everytime
when we get it by class name.

```php
$myObject = $container->createObject('MyClass');

// Now we can get this class with new instance from container
$anotherMyObject = $container->get('MyObject');
```

Use `createSharedObject()` to set object as singleton.

```php
// Now this object is singleton
$myObject = $container->createSharedObject('MyClass');
```

### Create with Custom Arguments

If we have some constructor arguments without class hint, container will send the default value to constructor.
We can override this un-hinted arguments:

This is a constructor without default value and class hinted.

```php
class AnotherClass
{
    public function _construct(RepositoryInterface $repository, $config)
    {
        $bar = $config['foo']
    }
}

// Let's create this object with custom arguments:
$container->newInstance('AnotherClass', ['config' => ['foo' => 'bar']]);
```

You can set multiple level arguments:

```php
use Windwalker\Repository\RepositoryInterface;

// ... AnotherClass

$config = [
    'config' => ['foo' => 'bar'],
    'Windwalker\Repository\RepositoryInterface' => [
        'options' => $options,
        'db' => DatabaseFactory::getDbo()
    ]
];

$container->newInstance('AnotherClass', $config);
```


### Prepare Object

We can set a class as prepared, then it will be created when we really need it:

```php
$container->prepareObject('MyClass');

// If we get MyClass, this class will be created.
$myObject = $container->get('MyClass');
```

Add second argument if you want to configure something after object created:

```php
$container->prepareObject('MyClass', function (MyClass $myClass, Container $container)
{
    $myClass->debug = true;

    return $myClass;
});
```

We can also prepare a shared object:

```php

// This objct will be singleton
$container->prepareSharedObject('MyClass'[, extending]);
```

### Prepare Creating Arguments

We can prepare some named arguments which will be injected to constructor when object creating.

```php
// Set class meta
$container->whenCreating('MyRepository')
    ->setArgumemt('config', $config)
    ->setArgument('db', $db);

// ...

$object = $container->newInstance('MyRepository');
```

Or just created it instantly:

```php
$container->whenCreating('MyClass')
    ->setArgumemt('config', $config)
    ->setArgument('db', $db)
    ->newInstance();
```

## Binding Classes

Sometimes we hope to inject particular object we want, we can bind a class as key to let Container know what you want to
instead the dependency object.

Here is a class which dependent to an interface, we can bind a sub class to container then container will use `MyRepository`
to be instance of `RepositoryInterface` and inject it to `MyClass`.

```php
use Windwalker\Repository\RepositoryInterface;
use Windwalker\Structure\Structure;

class MyClass
{
    public $repository;
    public $config;

    public function __construct(RepositoryInterface $repository, Structure $config)
    {
        $this->repository = $repository;
        $this->config = $config;
    }
}

class MyRepository implement RepositoryInterface
{
}

// Bind MyRepository as AbstractRepository
$container->share('Windwalker\Repository\RepositoryInterface', function()
{
    return new MyRepository();
});

$myObject = $container->createObject('MyClass');

$myObject->repository; // MyRepository
```

Use `bind()` to quickly bind a class without callback, container will use `newInstance()` to create it when needed.

```php
$container->bind('Windwalker\Repository\RepositoryInterface', 'MyRepository');

// `MyRepository` will auto created because we bind it to `RepositoryInterface`
$container->createObject('MyClass');
```

Use `bindShared()` to bind a class as singleton:

```php
$container->bindShared('Windwalker\Repository\RepositoryInterface', 'MyRepository');
```

You can add callback as second argument, this way is totally same as `share()` and `set()`:

```php
$container->bind('Windwalker\Repository\RepositoryInterface', function (Contaienr $container)
{
    return new MyObject();
});
```

## Extending

Container allows you to extend an object, the new instance or closure will wrap the original one and you can do more
extending configuration, this is a sample:

```php
// Create an item first
$container->share('flower', function()
{
    // Create a empty object
    return new Flower();
});

$container->extend('flower', function($origin, Container $container)
{
    // Set a property to this object
    $origin->name = 'sakura';

    return $origin;
});

$flower = $container->get('flower');

$flower->name; // sakura
```

## Container Aware

The `ContainerAwareInterface` help us get and set Container as a system, constructor, we often use it on application or controller classes.

```php
use Windwalker\DI\ContainerAwareInterface

class MyController implements ContainerAwareInterface
{
    protected $container;

    public function getContainer()
    {
        return $this->container;
    }

    public function setContainer(Container $container)
    {
        $this->container = $container;
    }

    public function execute()
    {
        $container = $this->getContainer();
    }
}
```

### Using Trait

In PHP 5.4, you can use `ContainerAwareTrait` to create an aware object.

```php
class MyController implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function execute()
    {
        $container = $this->getContainer();
    }
}
```

## Service Providers

Service providers is an useful way to encapsulate logic of creating objects and services.
Just implements the `Windwalker\DI\ServiceProviderInterface`.

```php
use Windwalker\DI\Container
use Windwalker\DI\ServiceProviderInterface

class DatabaseServiceProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container->share('db', function (Container $container)
        {
            $options = $container->get('config')->get('database');

            return DatabaseFactory::getDbo($options['driver'], $options);
        });

        // Or use callable
        $container->share('query', array($this, 'getQuery'));
    }

    public function getQuery(Container $container)
    {
        return new MysqlQueery();
    }
}

$container->registerServiceProvider(new DatabaseServiceProvider());
```

## Ioc Class

`\Windwalker\Ioc` provides a easy way to get system objects, the benefit to use these methods is that IDE can identify
what object we get, and provides auto-complete:

```php
$db    = \Windwalker\Ioc::getDatabase();
$input = \Windwalker\Ioc::getInput();
$app   = \Windwalker\Ioc::getApplication();
```

![160331-0001](https://cloud.githubusercontent.com/assets/1639206/14169419/38066a96-f75a-11e5-91da-a3c433bc4771.jpg)

If you want to add your own object in `Ioc`, edit the `/src/Windwalker/Ioc.php` file:

```php
<?php
// /src/Windwalker/Ioc.php`

namespace Windwalker;

abstract class Ioc extends \Windwalker\Core\Ioc
{
    /**
     * Add this docblock that your IDE can identify what you get.
     *
     * @return  MyObject
     */
	public static function getMyObject()
	{
		return static::get('my.object');
	}
}
```

Now you can use this method in everywhere.

```php
\Windwalker\Ioc::getMyObject();
```

## Facade

Windwalker has a Facade class to help us use proxy pattern to call object methods statically

```php
// Create a object into conteiner
$container->share('my.router', new Router());
```

```php
use Windwalker\Core\Facade\AbstractProxyFacade;

class MyRouter extends AbstractProxyFacade
{
    // Same as the container key, then we can auto match the object in container.
    protected static $_key = 'my.router';
}
```

Now just call the Router methods statically from `MyRouter`.

```php
MyRouter::route('route');

// Same as...

$container->get('my.router')->route('route');
```

To make IDE support auto-complete, we can add PhpDoc to class.

```php
/**
 * The Router class.
 *
 * @see \Windwalker\Router\Router
 *
 * @method  static  string  build($route, $queries = array(), $type = RestfulRouter::TYPE_RAW)
 *
 * ...
 *
 */
class MyRouter extends AbstractProxyFacade
{
    protected static $_key = 'my.router';
}
```

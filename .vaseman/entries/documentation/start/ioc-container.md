layout: documentation.twig
title: IoC Container

---

# What is Ioc Container
 
Windwalker DI is a [dependency injection](http://en.wikipedia.org/wiki/Dependency_injection) tools,
provide us an [IOC](http://en.wikipedia.org/wiki/Inversion_of_control) container to manage objects and data.
We also support service provider to help developers build their service in a universal interface.

> For more information about IOC and DI, please see
[Inversion of Control Containers and the Dependency Injection pattern](http://martinfowler.com/articles/injection.html) by Martin Fowler.

# Get IoC Container

Use internal container in controller and Application:

``` php
$session = $this->container->get('system.session');
```

Or use `Ioc` static class to get it:

``` php
$container = \Windwalker\Ioc::factory(); // This container is singleton
```

Now we can store objects into it.

``` php
$input = new Input;

$container->set('my.input', $input);

$input = $container->get('my.input');
```

Or directly get it by `Ioc`:

``` php
\Windwalker\Ioc::get('my.input');
```

## Get Sub Container

Every package will use a child container, if the key in child container not found, container will search from parent:

``` php
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

``` php
\Windwalker\Ioc::get('sakura', 'flower');
```

# Lazy Loading

Sometimes we will hope not create object instantly, we can use callback to create object.

``` php
// Set a closure into it
$container->set('input', function(Container $container)
{
    return new Input;
});

// Will call this closure when we get it
$input = $container->get('input');
```

But if we use `set()` method to set callback, this object will be recreated when every time we try to get it.

# Shared Object (Singleton)

Use `set('foo', $object, true)` or `share('foo', $object)` to make an object singleton, we'll always get the same instance.

``` php
// Share a closure
$container->share('input', function(Container $container)
{
    return new Input;
});

// Will will always get same instance
$input = $container->get('input');

// The second argument of get() can force create new instance
$newInput = $container->get('input', true);

// Use readable constant
$newInput = $container->get('input', Container::FORCE_NEW);
```

# Protect Object

Use `protect()` to prevent others override your important object.

``` php
$container->protect(
    'input',
    function(Container $container)
    {
        return new Input;
    },
    true // Shared or not
);

// We can still get this object
$input = $container->get('input');

// @Throws OutOfBoundsException
$container->set('input', $otherInput);
```

# Alias

It is convenience to set an alias to key of objects which we often use.

``` php
$container->share('system.application', $app)
    ->alias('app', 'system.application');

// Same as system.application
$app = $container->get('app');
```

So it is a good way that we can build IOC registry:

``` php
$config = array(
    'ioc.registry' => array(
        'app'     => 'system.application',
        'input'   => 'system.input',
        'session' => 'system.session'
    )
);

// Your own IOC class follows your rule
IOC::setRegistry($config['ioc.registry']);
IOC::setContainer($container);

// Will get system.session from Container
$session = IOC::getSession();
```

# Facade

`\Windwalker\Ioc` provides a facade pattern to quickly get system objects, the benefit to use these methods is that IDE can identify
which object we get, and provides auto-complete:

``` php
$db    = \Windwalker\Ioc::getDatabase();
$input = \Windwalker\Ioc::getInput();
$app   = \Windwalker\Ioc::getApplication();
```

See: [Ioc Methods](#)

If you want to add your own object in `Ioc`, edit the `/src/Windwalker/Ioc.php` file:

``` php
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



# Build Object

Container can build an object and auto inject the needed dependency objects.

``` php
use Windwalker\IO\Input;
use Windwalker\Registry\Registry;

class MyClass
{
    public $input;
    public $config;

    public function __construct(Input $input, Registry $config)
    {
        $this->input = $input;
        $this->config = $config;
    }
}

$myObject = $container->buildObject('MyClass');

$myObject->input; // Input
$myObject->config; // Registry
```

## Binding Classes

Sometimes we hope to inject particular object we want, we can bind a class as key to let Container know what you want to
instead the dependency object.

Here is a class but dependency to an abstract class, we can bind a sub class to container for use.

``` php
use Windwalker\Model\AbstractModel;
use Windwalker\Registry\Registry;

class MyClass
{
    public $model;
    public $config;

    public function __construct(AbstractModel $model, Registry $config)
    {
        $this->model = $model;
        $this->config = $config;
    }
}

class MyModel extends AbstractModel
{
}

// Bind MyModel as AbstractModel
$container->share('Windwalker\\Model\\AbstractModel', function()
{
    return new MyModel;
});

$myObject = $container->buildObject('MyClass');

$myObject->model; // MyModel
```

# Extending

Container allows you to extend an object, the new instance or closure will override the original one, this is a sample:

``` php
// Create an item first
$container->share('flower', function()
{
    return new Flower;
});

$container->extend('flower', function($origin, Container $container)
{
    $origin->name = 'sakura';

    return $origin;
});

$flower = $container->get('flower');

$flower->name; // sakura
```

# Container Aware

The `ContainerAwareInterface` help us get and set Container as a system, constructor, we often use it on application or controller classes.

``` php
use Windwalker\DI\ContainerAwareInterface;

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

## Using Trait

In PHP 5.4, you can use `ContainerAwareTrait` to create an aware object.

``` php
class MyController implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function execute()
    {
        $container = $this->getContainer();
    }
}
```

# Service Providers

Service providers is an useful way to encapsulate logic of creating objects and services.
Just implements the `Windwalker\DI\ServiceProviderInterface`.

``` php
use Windwalker\DI\Container;
use Windwalker\DI\ServiceProviderInterface;

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
        return new MysqlQueery;
    }
}

$container->registerServiceProvider(new DatabaseServiceProvider);
```

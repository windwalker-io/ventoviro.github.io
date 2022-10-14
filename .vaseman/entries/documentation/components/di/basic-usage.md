---
layout: global.documentation-layout
title: Basic Usage
menu: components/di
---

# Basic Usage

## Create A Container

Just new an instance.

```php
use Windwalker\DI\Container;

$container = new Container();
```

Now we can store objects into it.

```php
use Windwalker\DI\Container;

$input = new Input();

$container->set('input', $input);

$input = $container->get('input');
```

## Lazy Loading

Sometimes we will hope not create object instantly, we can use callback to create object.

```php
// Set a closure into it
$container->set('input', fn (Container $container) => new Input());

// Will call this closure when we get it
$input = $container->get('input');

// Always get new object.
$input !== $container->get('input');
```

## Shared Object (Singleton)

If we use `set()` method to set this callback, then every time we try to get it, Container will return a new object.

Use `share('foo', $object)` to make an object singleton, we'll always get the same instance.

```php
use Windwalker\DI\Container;

// Share a closure
$container->share('input', fn (Container $container) => new Input());

// Which is same as:
$container->set('input', fn (Container $container) => new Input(), Container::SHARED);

// We will always get same instance
$input = $container->get('input');

// The second argument of get() can force create new instance
$newInput = $container->get('input', true);
```

## Protect Object

Use `protect()` to prevent others override your important object.

```php
$container->protect(
    'input',
    fn (Container $container) => new Input(),
    Container::SHARED
);

// We can still get this object
$input = $container->get('input');

// Trying to override this key, will throw OutOfBoundsException
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
    public function __construct(public Input $input, public Collection $config)
    {
    }
}

$myObject = $container->newInstance(MyClass::class);

$myObject->input; // Input
$myObject->config; // Structure
```

### Create Object

Create object will new an instance instantly and set this class into container, we can get new instance everytime
when we get it by class name.

```php
$sakura = $container->createObject(Sakura::class);

// Now we can get this class with new instance from container
$sakura !== $container->get(Sakura::class);
```

Use `createSharedObject()` to set object as singleton.

```php
// Now this object is singleton 
$katana = $container->createSharedObject(Katana::class);

$katana === $container->get(Katana::class); // Same
```

### Create with Custom Arguments

If we have some constructor arguments without class hint, container will send the default value to constructor.
We can override this un-hinted arguments:

This is a constructor without default value and class hinted.

```php
class AnotherClass
{
    public function _construct(ModelInterface $model, $config)
    {
        $bar = $config['foo']
    }
}

// Let's create this object with custom arguments:
$container->newInstance('AnotherClass', ['config' => ['foo' => 'bar']]);
```

You can set multiple level arguments:

```php
use Windwalker\Model\ModelInterface;

// ... AnotherClass

$config = [
    'config' => ['foo' => 'bar'],
    'Windwalker\Model\ModelInterface' => [
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
$container->whenCreating('MyModel')
    ->setArgumemt('config', $config)
    ->setArgument('db', $db);

// ...

$object = $container->newInstance('MyModel');
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

Here is a class which dependent to an interface, we can bind a sub class to container then container will use `MyModel`
to be instance of `ModelInterface` and inject it to `MyClass`.

```php
use Windwalker\Model\ModelInterface;
use Windwalker\Structure\Structure;

class MyClass
{
    public $model;
    public $config;

    public function __construct(ModelInterface $model, Structure $config)
    {
        $this->model = $model;
        $this->config = $config;
    }
}

class MyModel implement ModelInterface
{
}

// Bind MyModel as AbstractModel
$container->share('Windwalker\Model\ModelInterface', function()
{
    return new MyModel;
});

$myObject = $container->createObject('MyClass');

$myObject->model; // MyModel
```

Use `bind()` to quickly bind a class without callback, container will use `newInstance()` to create it when needed.

```php
$container->bind('Windwalker\Model\ModelInterface', 'MyModel');

// `MyModel` will auto created because we bind it to `ModelInterface`
$container->createObject('MyClass');
```

Use `bindShared()` to bind a class as singleton:

```php
$container->bindShared('Windwalker\Model\ModelInterface', 'MyModel');
```

You can add callback as second argument, this way is totally same as `share()` and `set()`:

```php
$container->bind('Windwalker\Model\ModelInterface', function (Contaienr $container)
{
    return new MyObject;
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
    return new Flower;
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

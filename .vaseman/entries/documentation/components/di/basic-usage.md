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

Sometimes we will hope not to create object instantly, we can use callback to create object.

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

Container can help us create object and automatic inject required arguments to constructor.

```php
use Windwalker\IO\Input;
use Windwalker\Structure\Structure;

// Prepare dependencies into container
$container->share(Input::class, $input);
$container->share(Collection::class, $config);

// Define a class
class MyClass
{
    public function __construct(public Input $input, public Collection $config)
    {
    }
}

// And create it.
$myObject = $container->newInstance(MyClass::class);

// Now dependencies will be automatic injected
$myObject->input; // Input
$myObject->config; // Collection
```

### Autowire

If you try to create an object with dependencies which is not set in container, container will throw exception.

```php
class MyCar
{
    public function __construct(public Wheel $wheel, public Light $light)
    {
    }
}

// @throw DefinitionResolveException
$container->newInstance(MyCar::class);
```

You can add autowire settings to options.

```php
$car = $container->newInstance(MyCar::class, [], Container::AUTO_WIRE);
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
$container->newInstance(AnotherClass::class, ['config' => ['foo' => 'bar']]);
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

$container->newInstance(AnotherClass::class, $config);
```


### Prepare Object

We can set a class as prepared, then it will be created when we really need it:

```php
$container->prepareObject(MyClass::class);

// If we get MyClass, this class will be created.
$myObject = $container->get(MyClass::class);
```

Add second argument if you want to configure something after object created:

```php
$container->prepareObject(MyClass::class, function (MyClass $myClass, Container $container) {
    $myClass->debug = true;
    
    return $myClass;
});
```

We can also prepare a shared object:

```php

// This object will be singleton
$container->prepareSharedObject(MyClass::class[, extending]);
```

### Prepare Creating Arguments

We can prepare some named arguments which will be injected to constructor when object creating.

```php
// Set class meta
$container->whenCreating(MyModel::class)
    ->setArgumemt('config', $config)
    ->setArgument('db', $db);

// ...

$object = $container->newInstance(MyModel::class);
```

Or just created it instantly:

```php
$container->whenCreating(MyModel::class)
    ->setArgumemt('config', $config)
    ->setArgument('db', $db)
    ->newInstance();
```

## Binding Classes

Sometimes when we are creating object, we may want to inject particular class which wer want, and different 
from origin dependencies.

For example: Here is a class which dependent to `ModelInterface`, we can bind a subclass to container then container 
will use `MyModel` class to be an instance of `ModelInterface` and inject it to `MyClass`.

```php
use Windwalker\Model\ModelInterface;
use Windwalker\Data\Collection;

class MyClass
{
    public function __construct(public ModelInterface $model, public Collection $config)
    {
        
    }
}

class MyModel implements ModelInterface
{
}

// Bind MyModel as ModelInterface
$container->share(ModelInterface::class, fn () => new MyModel());

$myObject = $container->createObject(MyClass::class);

$myObject->model; // MyModel
```

Use `bind()` to quickly bind a class without callback, container will use `newInstance()` to create it when needed.

```php
$container->bind(ModelInterface::class, MyModel::class);

// `MyModel` will auto created because we bind it to `ModelInterface`
$container->createObject(MyClass::class);
```

Use `bindShared()` to bind a class as singleton:

```php
$container->bindShared(ModelInterface::class, MyModel::class);
```

You can add callback as second argument, this way is totally same as `share()` and `set()`:

```php
$container->bind(ModelInterface::class, fn () => new MyObject());
```

## Extending

Container allows you to extend an object, the new instance or closure will wrap the original one and you can do more
extending configuration, this is a sample:

```php
// Create an object first
$container->share('flower', function() {
    // Create an empty object
    return new Flower();
});

$container->extend('flower', function(Flower $flower, Container $container) {
    // Set a property to this object
    $flower->name = 'sakura';

    return $flower;
});

$flower = $container->get('flower');

$flower->name; // sakura
```

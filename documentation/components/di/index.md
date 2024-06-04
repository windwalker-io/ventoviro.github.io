---
layout: doc
title: Introduction
part: Components
component: di
---

# Introduction

Windwalker DI is a [dependency injection](http://en.wikipedia.org/wiki/Dependency_injection) tools,
it provides an [IoC](http://en.wikipedia.org/wiki/Inversion_of_control) container to manage objects and dependencies.

We also support service provider to help developers build their service in a universal interface.

In 4.x, Windwalker DI has been totally rewrote.

## Installation

Install via composer

```bash
composer require windwalker/di ^4.0
```

## Use in Windwalker

### Dependency Injection

DI Container is part of Windwalker, you don't need to use it directly. You can inject it in any service or custom class.

```php
class MyClass 
{
    public function __construct(protected FooService $fooService) 
    {
    }
}
```

Then, register it to `etc/di.php`:

```php
    // ...

    [
        'bindings' => [
            FooService::class,
            MyClass::class
        ]   
    ]
```

Now, you can inject your class in View or Controller:

```php
#[Controller]
class DashboardController
{
    public function index(MyClass $myClass)
    {
        //
    }
}
```

### Directly use Container

If you want to directly use Container, you can inject itself.

```php
use \Windwalker\DI\Container;

#[Controller]
class DashboardController
{
    public function index(Container $container)
    {
        $fooService = $container->get(FooService::class);
    }
}
```

Or use `AppContext`, it is a container wrapper:

```php
use \Windwalker\Core\Application\AppContext;

#[Controller]
class DashboardController
{
    public function index(AppContext $app)
    {
        $fooService = $app->make(FooService::class);
    }
}
```

## Use as Standalone Component

If you want to use Container without Windwalker core, just create it.

```php
$container = new \Windwalker\DI\Container();

$container->share(MyClass::class, new MyClass());

$myClass = $container->get(MyClass::class);
```

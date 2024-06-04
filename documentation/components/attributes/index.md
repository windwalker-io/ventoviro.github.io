---
layout: doc
title: Introduction
component: attributes
---

# Introduction

This package provides a universal interface to manage [PHP8 Attributes](https://stitcher.io/blog/attributes-in-php-8)
and help developers construct the attribute processors.

## Installation

Install via composer.

```bash
composer require windwaker/attributes ^4.0
```

## Use in Windwalker

In Windwalker framework, attributes works with DI Container.

At `/etc/di.php`, you can register your own attributes:

```php
use Windwalker\Attributes\AttributeType;

    // ...

        'attributes' => [
            MyAttribute::class => AttributeType::CLASSES
        ]
    ]

    // ...
```

Then add it to your class:

```php

#[MyAttribute]
class MyClass
{
    // ...
}
```

Now you can inject this class or create it by container, attributes will auto work:

```php
// Make it
$app->make(MyClass::class);

$container->newInstance(MyClass::class);

// Or inject it
public function __construct(MyClass $my)
{}
```


## Use as Standalone Component

Just create `AttributesResolver` object and register attributes.

```php
use Windwalker\Attributes\AttributesResolver;
use Windwalker\Attributes\AttributeType;

$attributes = new AttributesResolver();
$attributes->registerAttribute(\Wrapper::class, AttributeType::CLASSES);

// Now, try to wrap an object.  
#[\Wrapper] 
class Foo {
    
}

$foo = new \Foo();
$foo = $attributes->decorateObject($foo);
$foo instanceof \Wrapper;
$foo->inner instanceof \Foo;
```

## Getting Started

First, you must create your own Attributes. This is a simple example wrapper to wrap any object.

```php
use Windwalker\Attributes\AttributeHandler;
use Windwalker\Attributes\AttributeInterface;

#[\Attribute(\Attribute::TARGET_CLASS)]
class Wrapper implements AttributeInterface
{
    public object $inner;
    public function __invoke(AttributeHandler $handler): callable
    {
        return function () use ($handler) {
            $this->inner = $handler();
            return $this;
        };
    }
}
```

In `__invoke()`, always return a callback, you can do what you want in this callback.

The `$handler()` will return the value which return by previous attribute handler.
All callbacks will be added to a stack and run after all attributes processed. This is very similar
to middleware handler.

Then, register this attribute to resolver.

```php
use Windwalker\Attributes\AttributesResolver;
use Windwalker\Attributes\AttributeType;

$attributes = new AttributesResolver();
$attributes->registerAttribute(\Wrapper::class, AttributeType::CLASSES);

// Now, try to wrap an object.  
#[\Wrapper] 
class Foo {
    
}

$foo = new \Foo();
$foo = $attributes->decorateObject($foo);
$foo instanceof \Wrapper;
$foo->inner instanceof \Foo;
```

---
layout: global.documentation-layout
title: Attributes
menu: components/di
---

# Attributes

Container can resolve attributes as decorator, by default, there has 2 basic attributes you can use.

## #[Autowire]

For example, if you create a class with dependencies which has not set to container, 
you will get error. 

```php
class Flower
{
    public function __construct(public Sakura $sakura) 
    {}
}

$container->newInstance(Flower::class); // Error
```

But we can use `#[Autowire]` to auto create dependent objects, you must manually register this attribute first.

```php
use Windwalker\Attributes\AttributeType;
use Windwalker\DI\Attributes\Autowire;

// Register it
$container->getAttributesResolver()
    ->registerAttribute(Autowire::class, AttributeType::PARAMETERS);

class Flower
{
    public function __construct(#[Autowire] public Sakura $sakura) 
    {}
}

$flower = $container->newInstance(Flower::class); // No error

$flower->sakura; // Sakura object
```

It will always create new object everytime:

```php
$flower1 = $container->newInstance(Flower::class);
$flower2 = $container->newInstance(Flower::class);

$flower1->sakura !== $flower2->sakura;
```

More about using attribute decorators, please see [Attributes Document](../attributes/)

## #[Inject]

`#[Inject]` is an attribute to make Container inject object to a property. The class which you want to inject, should 
be set into Container before you inject it.

```php
use Windwalker\Attributes\AttributeType;
use Windwalker\DI\Attributes\Inject;

// Register attribute.
$container->getAttributesResolver()
    ->registerAttribute(Inject::class, AttributeType::PROPERTIES | AttributeType::PARAMETERS);

// Prepare it.
$container->prepareSharedObject(Sakura::class);

class Flower
{
    #[Inject]
    protected Sakura $sakura;

    public function __construct() 
    {}
}

$flower = $container->newInstance(Flower::class);

$flower->sakura; // Sakura object
```

`#[Inject]` can also use on parameters:

```php
    public function __construct(#[Inject] public Sakura $sakura) 
    {}
```

Or directly set a class name:

```php
    #[Inject(Sakura::class)]
    protected FlowerInterface $flower;
```

## #[Service]

`#[Service]` is similar to `#[Autowire], it will create a new object if class haven't set into Container. 
But after first created it, then it will always get same object.

```php
use Windwalker\Attributes\AttributeType;
use Windwalker\DI\Attributes\Service;

// Register attribute.
$container->getAttributesResolver()
    ->registerAttribute(Service::class, AttributeType::PARAMETERS);

class Flower
{
    public function __construct(#[Service] public Sakura $sakura) 
    {}
}

$flower1 = $container->newInstance(Flower::class);
$flower2 = $container->newInstance(Flower::class);

// Same
$flower1->sakura === $flower2->sakura;
```

You can also direct a class name:

```php
    public function __construct(#[Service(Foo::class)] public FooInterface $foo) 
    {}
```


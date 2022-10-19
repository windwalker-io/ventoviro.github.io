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

More about using attribute decorators, please see [Attributes Document](../attributes/)


---
layout: doc
title: Available Types & Actions
component: attributes
---

# Available Types & Actions

Currently, there has `7` types, You can use `registerAttribute()` to control attribute working scope.

- `AttributeType::CLASSES`: Same with `Attribute::TARGET_CLASS`
- `AttributeType::CLASS_CONSTANTS`: Same with `Attribute::TARGET_CLASS_CONSTANT`
- `AttributeType::METHODS`: Same with `Attribute::TARGET_METHOD`
- `AttributeType::FUNCTIONS`: Same with `Attribute::TARGET_FUNCTION`
- `AttributeType::CALLABLE`: **Special type only provided by `AttributeType`.**
- `AttributeType::PROPERTIES`: Same with `Attribute::TARGET_PROPERTY`
- `AttributeType::PARAMETERS`: Same with `Attribute::TARGET_PARAMETER`

## Object & Classes

```php
use Windwalker\Attributes\AttributesResolver;
use Windwalker\Attributes\AttributeType;

// Work on Class and Object
$attributes->registerAttribute(\Decorator::class, AttributeType::CLASSES);

// Decorate existing object
$object = $attributes->decorateObject($object);

// Create object from class and decorate it.
$object = $attributes->createObject(\Foo::class, ...$args);
```

## Function & Method

```php
use Windwalker\Attributes\AttributesResolver;
use Windwalker\Attributes\AttributeType;

// Work on method and function.
$attributes->registerAttribute(\AOP::class, AttributeType::METHODS | AttributeType::FUNCTIONS);
$object = $attributes->resolveMethods(new SomObject());
```

## Callable

Callable type is a special type, allows `AttributesResolver` to call any callable and
wrap the calling process. You can replace parameters or change the return value.

This type works on methods, functions, closures and any callable.

```php
use Windwalker\Attributes\AttributesResolver;
use Windwalker\Attributes\AttributeType;

// Work on method, function, Closure or callable.
$attributes->registerAttribute(\Autowire::class, AttributeType::CALLABLE);

$result = $attributes->call($callable, ...$args);
```

## Properties

```php
use Windwalker\Attributes\AttributesResolver;
use Windwalker\Attributes\AttributeType;

// Work on object properties
$attributes->registerAttribute(\Inject::class, AttributeType::PROPERTIES);

$object = new class {
    #[\Inject]
    protected ?\Foo $foo = null;
};

$object = $attributes->resolveProperties($object);
```

## Parameters

```php
use Windwalker\Attributes\AttributesResolver;
use Windwalker\Attributes\AttributeType;

// Work on callable parameters.
$attributes->registerAttribute(\StrUpper::class, AttributeType::PROPERTIES);

$func = function (
    #[\StrUpper]
    $foo    
) {
    return $foo;
};

$result = $attributes->call($func, ['flower'], /* $context to bind this */); // "FLOWER"
```

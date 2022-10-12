---
layout: global.documentation-layout
title: Getting Started
part: Components
chapter: Attributes
menu: components/attributes
---

# Write Your Own Attribute Handlers

## Object & Classes

This is a Decorator example:

```php
use Windwalker\Attributes\AttributeHandler;
use Windwalker\Attributes\AttributeInterface;
#[\Attribute(\Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class Decorator implements AttributeInterface
{
    protected string $class;
    
    protected array $args = [];
    
    public function __construct(string $class, ...$args)
    {
        $this->class = $class;
        $this->args = $args;
    }
    public function __invoke(AttributeHandler $handler): callable
    {
        return fn (...$newInstanceArgs) => new ($this->class)($handler(...$newInstanceArgs), ...$this->args); 
    }
}
```

There are 2 methods can decorate object or class.

- `decorateObject(object $object): object`
- `createObject(string $class, ...$args): object`

If you call `decorateObject($object)`, the `$handler(<void>)` will only return object which you sent into.

And if you call `createObject($class, ...$args)`, the `$handler(...$args)` will create object
by the class and pass `...$args` to constructor.

Then, use your own function wrap it, all handlers will be a callback stack and called after all attributes processed.

Example:

```php
use Windwalker\Attributes\AttributesResolver;
use Windwalker\Attributes\AttributeType;

#[\Decorator(\Component::class, ['template' => 'foo.php'])]
class Foo 
{
    //
}

$attributes = new AttributesResolver();

// Work on Class and Object
$attributes->registerAttribute(\Decorator::class, AttributeType::CLASSES);

// Decorate existing object
$component = $attributes->decorateObject($object);

// Create object from class and decorate it.
$component = $attributes->createObject(\Foo::class, ...$args);
```

## Use Custom Object Builder

If you want to integrate with some Container packages, please set custom object builder.

```php
$attributes->setBuilder(function (string $class, ...$args) use ($container) {
    return $container->createObject($class, ...$args);
});
```

> TODO: Support custom call() handler.

## Functions & Methods

Functions & Methods type will not return anything, use this type to determine attributes exists and do something else.
This is an example to register methods to another object.

```php
use Windwalker\Attributes\AttributeHandler;
use Windwalker\Attributes\AttributeInterface;
#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class ListenTo implements AttributeInterface
{
    public function __construct(protected string $event) 
    {
        //
    }
    public function __invoke(AttributeHandler $handler): callable
    {
        return function () use ($handler) {
            $provider = $handler->getResolver()->getOption('provider');
            $listener = $handler();
            $provider->addListener(
                $this->event,
                $listener
            );
            return $listener;
        };
    }
}
```

The `$handler()` will just return method callable array `[$object, 'method_name'']` or function name.

```php
use Windwalker\Attributes\AttributesResolver;
use Windwalker\Attributes\AttributeType;
class Subscriber 
{
    #[\ListenTo(\FooEvent::class)]
    public function foo()
    {
        //
    }
}
$attributes = new AttributesResolver();
$attributes->registerAttribute(\ListenTo::class, AttributeType::METHODS | AttributeType::FUNCTIONS);
$attributes->resolveMethods(new \Subscriber());
```

## Callable

An example to control HTTP allow methods and Json Response.

```php
use Windwalker\Attributes\AttributeHandler;
use Windwalker\Attributes\AttributeInterface;
#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::TARGET_FUNCTION)]
class Method implements AttributeInterface
{
    protected array $allows = [];
    
    public function __construct(string|array $allows = [])
    {
        $this->allows = array_map('strtoupper', (array) $allows);
    }
    public function __invoke(AttributeHandler $handler): callable
    {
        return function ($request, $reqHandler) use ($handler) {
            if (!in_array($request->getMethod(), $this->allows, true)) {
                throw new \RuntimeException('Invalid Method', 405);
            }
            // You can change parameters here.
    
            $res = $handler($request, $reqHandler);
            // You can also modify return value.
            return $res;
        }; 
    }
}
```

```php
use Windwalker\Attributes\AttributeHandler;
use Windwalker\Attributes\AttributeInterface;
#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::TARGET_FUNCTION)]
class Json implements AttributeInterface
{
    public function __invoke(AttributeHandler $handler): callable
    {
        return function ($request, $reqHandler) use ($handler) {
            $res = $handler($request, $reqHandler);
            $res = $res->withHeader('Content-Type', 'application/json');
            return $res;
        }; 
    }
}
```

The `$handler(...$args)` in callable attributes is to call the target callable, we can change/validate parameters
or modify the return value.

Usage:

```php
use Windwalker\Attributes\AttributesResolver;
use Windwalker\Attributes\AttributeType;
class Controller 
{
    #[\Method('GET')]
    #[\Json]
    public function index():Response
    {
        return new Response();
    }
}
$attributes = new AttributesResolver();
$attributes->registerAttribute(\Method::class, AttributeType::CALLABLE);
$attributes->registerAttribute(\Json::class, AttributeType::CALLABLE);
// Call
$jsonResponse = $attributes->call(
    [new \Controller(), 'index'], // Callable 
    [$request, 'handler' => $resHandler], // Args should be array, support php8 named arguments
    [?object $context = null] // Context is an object wll bind as this for the callable, default is NULL. 
);
```

## Parameters

An example to handler parameters to upper case

```php
use Windwalker\Attributes\AttributeHandler;
use Windwalker\Attributes\AttributeInterface;
#[\Attribute(\Attribute::TARGET_PARAMETER)]
class Upper implements AttributeInterface
{
    public function __invoke(AttributeHandler $handler): callable
    {
        return fn () => strtoupper((string) $handler());
    }
}
```

The `$handler()` in parameter attributes is to simply get parameter values, you can modify this value and return it.

```php
use Windwalker\Attributes\AttributesResolver;
use Windwalker\Attributes\AttributeType;
class Http 
{
    public static function request(
        #[\Upper]
        string $method,
        mixed $data = null,
        array $options = []
    ) {
        // $method should always upper case.
    }
}
$attributes = new AttributesResolver();
$attributes->registerAttribute(\Upper::class, \Attribute::TARGET_PARAMETER);
// Decorate existing object
$jsonResponse = $attributes->call([\Http::class, 'request'], ['POST', 'foo=bar']);
```

## Properties

This is an example to handle all properties of an object.

```php
use Windwalker\Attributes\AttributeHandler;
use Windwalker\Attributes\AttributeInterface;
#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Wrapper implements AttributeInterface
{
    public function __invoke(AttributeHandler $handler): callable
    {
        /** @var $ref ReflectionProperty */
        $ref = $handler->getReflector();
        // Since php8 supports union type, we should get first exists class type as possible type.
        $type = ReflectionHelper::getFirstExistsClassType($ref);
        $class = $type->getName();
        return fn () => new $class($handler());
    }
}
```

The `$handler()` in properties attributes is to simply get property values, you can modify this value and return it.
No matter these properties are public or protected, AttributesResolver will force set value into it.

Usage:

```php
use Windwalker\Attributes\AttributesResolver;
use Windwalker\Attributes\AttributeType;
$object = new class {
    #[\Wrapper]
    protected ?Collection $options = null;
};
$attributes = new AttributesResolver();
$attributes->registerAttribute(\Wrapper::class, \Attribute::TARGET_PROPERTY);
$object = $attributes->resolveProperties($object);
```
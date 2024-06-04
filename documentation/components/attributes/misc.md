---
layout: doc
title: Miscellaneous
component: attributes
---

# Miscellaneous

## About `AttributeHandler`

`AttributeHandler` is the only parameter of our attribute processor.

```php
use Windwalker\Attributes\AttributeHandler;
use Windwalker\Attributes\AttributeInterface;
#[\Attribute]
class MyAttribute implements AttributeInterface
{
    public function __invoke(AttributeHandler $handler): callable
    {
        /** 
         * $ref can be:
         * @see \ReflectionObject for classes type 
         * @see \ReflectionClass  for classes type
         * @see \ReflectionFunctionAbstract for callable type
         * @see \ReflectionParameter for parameters type
         * @see \ReflectionProperty for properties type
         */
        $ref = $handler->getReflector();
      
        // The AttributesResolver object
        $resolver = $handler->getReflector(); 
        // Get previous result
        $result = $handler(...);
    }
}
```

## Integrate to Any Objects

You can create AttributesResolver in some object to help this object handle attributes, here we use EventDispatcher as example:

```php
use Windwalker\Attributes\AttributesAwareTrait;
use Windwalker\Attributes\AttributesResolver;
use Windwalker\Attributes\AttributeType;
class EventDispatcher 
{
    use AttributesAwareTrait;
    public function __construct()
    {
        $this->prepareAttributes($this->getAttributesResolver());
    }
    protected function prepareAttributes(AttributesResolver $resolver)
    {
        $resolver->registerAttribute(\ListenerTo::class, AttributeType::METHODS);
        $resolver->setOption('dispatcher', $this);
    }
    
    public function addListener(callable $callable)
    {
        // Register listener        
    }
    
    public function subscribe(object $subscriber)
    {
        $this->getAttributesResolver()->resolverMethods($subscriber);        
    }
}
```

Set object to option, then you can access it in attribute handler:

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
            $provider = $handler->getResolver()->getOption('dispatcher');
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

## Run if Attributes Exists

`AttributesResolver` provides a simple static methods to run any callback if attribute exists.

```php
use Windwalker\Attributes\AttributesAccessor;
$object = new Foo();
AttributesAccessor::runAttributeIfExists(
    new ReflectionObject($object), // Send any reflections
    SomeAttribute::class,
    function (SomeAttribute $attr) {
        // Run anything you want
    }
);
$ref = new ReflectionObject($object);
AttributesAccessor::runAttributeIfExists(
    $ref->getMethod('foo'), // Send ReflectionMethod
    SomeAttribute::class,
    function (SomeAttribute $attr) {
        // Run anything you want
    }
);
```

## Available Handling Methods

| Method | Description |
| --- | --- |
|`createObject(string $class, ...$args): object`| Create object by class and decorate it.|
|`decorateObject(object $object): object`| Decorate an exists object.|
|`call(callable $callable, $args = [], ?object $context = null): mixed`| Call a callable, this will resolve methods, functions and their parameters.|
|`resolveProperties(object $instance): object`| Modify object properties values.|
|`resolveMethods(object $instance): object`| Resolve methods but won't change anything, just call your custom handler.|
|`resolveConstants(object $instance): object`| Resolve class constants but won't change anything, just call your custom handler.|
|`resolveObjectMembers(object $instance): object`| This will run `resolveProperties()`, `resolveConstants()` and `resolveConstants()` one time.|

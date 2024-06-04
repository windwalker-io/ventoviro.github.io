---
layout: doc
title: Parameters
component: di
---

# Parameters

Container can create a parameters structure to resolve object and dependencies.

## Resolve Factories

This is a sample parameters, we set a class name as param: `car`, then we can use `resolve()` to create it.

```php
use Windwalker\DI\Parameters;

$container->setParameters(
    [
        'car' => MyCar::class
    ]
);

class MyCar
{}

$car = $container->resolve($container->getParam('car'));

// Same as 

$car = $container->resolve(MyCar::class);
```

Most of all, object may have some constructor arguments, we can use `\Windwalker\DI\create()` to define the factory 
with some custom arguments.

```php
use Windwalker\DI\Parameters;

use function Windwalker\DI\create;

$container->setParameters(
    [
        // Define factory by create()
        'car' => create(
            MyCar::class,
            crew: 4
        )
    ]
);

class MyCar
{
    public function __construct(public int $crew)
    {}
}

$car = $container->resolve($container->getParam('car'));
// Same as
$car = $container->resolve(\Windwalker\ref('car'));
// Same as
$car = $container->resolveParam('car');
```

### Define Factory with Dependencies

You may need a class with dependencies, for example:

```php
class CachePool
{
    public function __construct(protected StorageInterface $storage)
    {}
}
```

There are 2 ways to handle dependencies, one is use callback:

```php
[
    'cache' => create(
        fn (Container $container) => new CachePool(
            // Also handle FileStorage's dependencies
            $container->newInstance(
                FileStorage::class,
                path: '/path/to/storage/cache',
                ttl: 300
            )
        )
    )
]
```

The second is use configuration with `ref()`, `ref()` is a wrapper to make `Container::getParams()` direct to another 
position:

```php
[
    'cache' => create(
        CachePool::class,
        storage: \Windwalker\ref('storages.file')
    ),
    'storages' => [
        'file' => FileStorage::class
    ] 
]
```

Or a callback of argument, the first level closure will resolve as argument factory:

```php
[
    'cache' => create(
        CachePool::class,
        storage: fn (Container $container) => ->newInstance(
            FileStorage::class,
            path: '/path/to/storage/cache',
            ttl: 300
        )
    )
]
```

If you want to set a pure closure into it, use `\Windwalker\raw()` to wrap it.

```php
create(
    Runner::class,
    // Use raw() to wrap closure
    task: \Windwalker\raw(
        function () {
            // ...
        }
    )
)
```

### Array of Objects

Container can only handle first level of callback, for an array of objects, you must use callback factory 
to handle dependencies:

Below is not work:

```php
[
    'log' => create(
        Logger::class,
        // This will not work, Container won't resolve this factories
        handlers: [
            \Windwalker\ref('log_handlers.file'), 
            \Windwalker\ref('log_handlers.mail'), 
            \Windwalker\ref('log_handlers.redis'),
        ]
    ),
    'log_handlers' => [
        'file' => FileLogHandler::class,
        'mail' => MailLogHandler::class,
        'redis' => RedisLogHandler::class,
    ]
]
```

Use this instead.

```php
[
    'log' => create(
        Logger::class,
        handlers: function (Container $container) {
            return [
                $container->resolveParam('log_handlers.file'), 
                $container->resolveParam('log_handlers.mail'), 
                $container->resolveParam('log_handlers.redis'),
            ];
        }
    ),
    // ...
]
```

-----

## About `resolve()`

`resolve()` is a method to handle any object or parameter definitions of Container.

```php
// Send a class name or id, if this name exists in container, will get it.
$container->resolve('foo');
$container->resolve(Foo::class);

// Send a class name which is not set, will create it.
$container->resolve(SomeClass::class);

// Send a definition object, will resolve this definition.
$container->resolve(
    new \Windwalker\DI\Definition\StoreDefinition(
        SomeClass::class
    )
);

// Send a callback, will call it as factory.
$container->resolve(function (\Windwalker\DI\Container $container) {
    return $container->newInstance(SomeObject::class, ['foo' => $container->get('foo')]);
});

// Send a params ref, will get this param value and resolve it
$container->resolve(\Windwalker\ref('foo.bar.classname'));

// Same as 
$container->resolveParam('foo.bar.classname');
```

## Merge

You may merge a config array to an exists position.

```php
$container->mergeParameters(
    'foo.bar',
    [
        'yoo' => 'The data you want to merge'
    ]
);
```

By default, `mergeParameters()` won't override exists values, only merge values not exists in Container.
If you want to override them, add flag:

```php
$container->mergeParameters(
    'foo.bar',
    [
        'yoo' => 'The data you want to merge'
    ],
    $container::MERGE_OVERRIDE
);
```

Also supports recursive:

```php
$container->mergeParameters(
    'foo.bar',
    [
        'yoo' => [
            'goo' => 'The data you want to merge'
        ]
    ],
    $container::MERGE_RECURSIVE
);
```

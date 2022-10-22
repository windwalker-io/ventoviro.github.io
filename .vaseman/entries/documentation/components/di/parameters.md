---
layout: global.documentation-layout
title: Parameters
menu: components/di
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
$container->setParameters(
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
);
```

The second is use configuration with `ref()`, `ref()` is a wrapper to make `Container::getParams()` direct to another 
position:

```php
$container->setParameters(
    [
        'cache' => create(
            CachePool::class,
            sorage: \Windwalker\ref('storages.file')
        ),
        'storages' => [
            'file' => FileStorage::class
        ] 
    ]
);
```

Or a callback of argument, the first level closure will resolve as argument factory:

```php
$container->setParameters(
    [
        'cache' => create(
            CachePool::class,
            sorage: fn (Container $container) => ->newInstance(
                FileStorage::class,
                path: '/path/to/storage/cache',
                ttl: 300
            )
        )
    ]
);
```

If you want to set a pure closure into it, use `\Windwalker\raw()` to wrap it.

```php
$container->setParameters(
    [
        'cache' => create(
            Runner::class,
            task: \Windwalker\raw(
                function () {
                    // ...
                }
            )
        )
    ]
);
```

### Array of Objects

Container can only handle first level of callback, for an array of objects, you must use callback factory 
to handle dependencies:

Below is not work:

```php
[
    'cache' => create(
        Logger::class,
        // This will not work, Container won't resolve this factories
        handlers: [
            fn (Container) => $container->newInstance(FileLogHandler::class), 
            fn (Container) => $container->newInstance(AwsLogHandler::class), 
            create(RedisLogHandler::class), 
        ]
    )
]
```



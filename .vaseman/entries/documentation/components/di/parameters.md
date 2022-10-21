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

Most of all, object may have some constructor arguments, we can use `\Windwalker\DI\create()` to define the factory.

```php
use Windwalker\DI\Parameters;

use function Windwalker\DI\create;

$container->setParameters(
    [
        // Define by create()
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


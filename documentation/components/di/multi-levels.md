---
layout: doc
title: Multi Levels
component: di
---

# Multi Levels

Windwalker Container supports a nested structure, you can create many children of a parent Container.
This is useful if in a long-running daemon program, which may fork a child Container in every loop or tasks.

## Create Child

Use `createChild()` to create a child Container, then get something which has set in parent: 

```php
$container = new \Windwalker\DI\Container();
$container->share('foo', new Foo());

$childContainer = $container->createChild();
$container->get('foo') === $childContainer->get('foo'); // Will same with parent
```

You can override object in child

```php
$childContainer->share('foo', new Foo());

$container->get('foo') !== $childContainer->get('foo');
```

## Multi-Levels Children

The Level N Container can also get object from parent. 

```php
$containerL2 = $parentContainer->createChild();
$containerL3 = $containerL2->createChild();

$containerL3->get('foo') === $parentContainer->get('foo');

// Get current level
$containerL3->getLevel(); // 3
```


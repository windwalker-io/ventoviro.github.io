---
layout: documentation.twig
title: Class Resolvers

---

## MVC Resolvers

Windwalker uses resolver to find MVC classes. By default, package will find MVC in it's folder. For example, if there
has a `flower` package and a route with `Sakura` controller matched:

``` php
sakura:
    pattern: /sakura
    controller: Sakura
```

The Flower package will get `Flower\Controller\Sakura\{action}` as main controller. But if this class not exists,
we can prepare a set of default controller in other package, and tell windwalker to get these default controller instead.

Add this line in package `boot()` or any position before package execute.

``` php
public function boot()
{
    parent::boot();

    // Register Animal package namespace to find classes
    $mvcResolver = $this->getMvcResolver();

    $mvcResolver->addNamespace('Animal', Priority::NORMAL);
}
```

Now if `Flower\Controller\Sakura\{action}` not exists, Windwalker will find controller from `Animal\Controller\Sakura\{action}`.
It is flexible to help us organize our packages.

## View And Model

You can also use the same way to add default View and Model paths.

``` php
$this->getMvcResolver()->addNamespace('Animal', Priority::NORMAL);
```

When controller `$this->getView('Sakura')` or `$this->getModel('Sakura')`, and these two classes not exists, Windwalker
will find from Animal package as default object.

We can also set default path separately.

``` php
$mvcResolver = $this->getMvcResolver();

$mvcResolver->getControllerResolver()->addNamespace('Animal\Controller');
$mvcResolver->getViewResolver()->addNamespace('Animal\View');
$mvcResolver->getModelResolver()->addNamespace('Animal\Model');
```

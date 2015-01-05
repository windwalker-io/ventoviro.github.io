layout: documentation.twig
title: View and Templating

---

# Use Default View Object

In controller, you can do anything you want, but if you hope to render some template, the View object will help you. 
In this case, we create a default `HtmlView` to render template:

``` php
// /src/Flower/Controller/Sakuras/GetController.php

class GetController extends Controller
{
	protected function doExecute()
	{
		$view = new HtmlView;

		return $view->render();
	}
}
```

Then create a php file in `/templates/default.php`. This template will be rendered.

``` php
<?php
// templates/default.php
?>
Hello World
```

> NOTE: Please check you use `\Windwalker\Core\View\HtmlView` not `\Windwalker\View\HtmlView`
 These 2 classes is similar but not the same.

## Push Controller Information into View

But this is not a good position to locate template, we will hope it at `templates/flower/sakuras`.
So add this code to push controller config into View, View will know every thing about Controller:

``` php
// /src/Flower/Controller/Sakuras/GetController.php

$view = new HtmlView;

// Push config into View
$view->setConfig($this->config);

return $view->render();
```

Move template file to `/templates/flower/sakuras/default.php`, and Windwalker will found it.

## Use Package Templates

If your View is in a package, you can put your template at `{Package}/Templates/{view}`, For example, we move `default.php` 
to `/src/Flower/Templates/sakuras`, View will find this position priority than root templates folder. 
So we can make our templates following package folders.

## The Ordering of Template Paths
 
Windwalker will follow this orders to find templates, you can override any template in a higher priority position:

```
[0] => /src/[Package]/Templates/[view]
[1] => /templates/[package]/[view]
[2] => /templates
[3] => /vendor/windwalker/core/src/Core/Resources/Templates  <-- Here is core templates
```

## Use other layouts

The `foo.bar.baz` will matched `foo/bar/baz.php` file. If you didn't set any layout name, `default` will instead.

``` php
// Will find: edit.php
$view->setLayout('edit')->render();

// Will find: foo/bar/baz.php
$view->setLayout('foo.bar.baz')->render();
```

## Custom Template Paths

Windwalker View use `SplPriorityQueue` to sort paths, if we want to add path, we should provide the priority flag.

``` php
use Windwalker\Utilities\Queue\Priority;

// ...

$view->addPath('/my/template/path1', Priority::HIGH);
$view->addPath('/my/template/path2', Priority::NORMAL);
```

### Add to Global

Add paths to global that we don't need to set it every time, you must run this code before controller executed,
for example, you can run in `onAfterInitialise` event (See [Event](../more/event.html)) , `YourPackage::initialise()` or `Application::initialise()`:

``` php
// Add global paths
\Windwalker\Core\Renderer\RendererHelper::addPath('/my/path', Priority::ABOVE_NORMAL);
```

See also: [Windwalker View](https://github.com/ventoviro/windwalker/tree/staging/src/View#htmlview)
/ [SplPriorityQueue](http://php.net/manual/en/class.splpriorityqueue.php)

## Add Data

View can maintain some data and use it in template:

``` php
// Set it when construct
$view = new HtmlView(array('foo' => 'bar'));

// Use setter
$view->set('foo', 'bar');

// Use Array Access
$view['foo'] = 'bar';
```

Then we can get this variable in template:

``` php
<?php
// templates/flower/sakuras/default.php

$foo = $data->foo;
?>
Hello <?php echo $foo;?>
```

# Create View Classes

We are still using default View, but it is not so customizable. Let's extend it to a new View object:

``` php
<?php
// src/Flower/View/SakurasHtmlView.php

namespace Flower\View;

use Windwalker\Core\View\HtmlView;

class SakurasHtmlView extends HtmlView
{
	protected function prepareData($data)
	{
		$data->created = $data->created->format('Y-m-d');
	}
}
```

Always remember add `Html` in view name, sometimes we will need `JsonView` or `XmlView`. Then we can create this view in controller:

``` php
<?php
// /src/Flower/Controller/Sakuras/GetController.php

namespace Flower\Controller\Sakuras;

use Flower\View\SakurasHtmlView;
use Windwalker\Core\Controller\Controller;

class GetController extends Controller
{
	protected function doExecute()
	{
		$view = new SakurasHtmlView;
		
		// We don't need to push config now, if View located in package folder, 
		// it will guess all information which is needed. 

		$view['created'] = new \DateTime('now');

		return $view->render();
	}
}
```

The purpose of custom View object is that we can set data format in it, so controller dose not need to worry about 
how to show data, just consider how to send data into View and redirect pages.

# PHP Engine

PHP engine use [Windwalker Renderer](https://github.com/ventoviro/windwalker-renderer) to render page, 
this package provides a simple interface similar to Twig that support template extending.

## Include Sub Template

Use `load()` to load other template file as a block. The first argument is file path, the second argument is new data
to merge with original data.

``` php
echo $this->load('sub.template', array('bar' => 'baz'));
```

Example to load `foo/article.php`:

``` html
<?php
// foo/article.php

/** @var  $data  \Windwalker\Data\Data  */
$title = $data['title'];
?>
<h1><?php echo $this->escape($title); ?></h1>

<?php foreach ($data->articles as $article): ?>
    <?php echo $this->load('foo.article', array('bar' => 'baz')); ?>
<?php endforeach; ?>
```

## Extends Parent Template

In Windwalker Renderer, there is a powerful function like Twig or Blade, we provide `extend()` method to extends
parent template. (`extends` in php is a reserved string, so we can only use `extend`)

For example, this is the parent `_global/html.php` template:

``` html
<!-- _global/html.php -->
<Doctype html>
<html>
<head>
    <title><?php $this->block('title');?>Home<?php $this->endblock(); ?></title>
</head>
<body>
    <div class="container">
    <?php $this->block('body');?>
        <h2>Home page</h2>
    <?php $this->endblock(); ?>
    </div>
</body>
</html>
```

And we can extends it in our View:

``` html
<?php
// foo/article.php
$this->extend('_global.html');
?>

<?php $this->block('title');?>Article<?php $this->endblock(); ?>

<?php $this->block('body');?>
    <article>
        <h2>Article</h2>
        <p>FOO</p>
    </article>
<?php $this->endblock(); ?>
```

The result will be:

``` html
<Doctype html>
<html>
<head>
    <title>Article</title>
</head>
<body>
    <div class="container">
        <article>
            <h2>Article</h2>
            <p>FOO</p>
        </article>
    </div>
</body>
</html>
```

## Show Parent

We can echo parent data in a block:

``` html
<?php $this->block('body');?>
    <?php echo $this->parent(); ?>
    <article>
        <h2>Article</h2>
        <p>FOO</p>
    </article>
<?php $this->endblock(); ?>
```

Result:

``` html
<h2>Home page</h2>
<article>
    <h2>Article</h2>
    <p>FOO</p>
</article>
```

See: [Windwalker Renderer](https://github.com/ventoviro/windwalker-renderer#windwalker-renderer)

# Blade Engine

Blade is a simple, yet powerful templating engine provided with [Laravel](http://laravel.com/). It is driven by template inheritance and sections. 
All Blade templates should use the `.blade.php` extension.

There are 2 ways to use Blade engine in View, first is directly create it.

``` php
use Windwalker\Core\View\BladeHtmlView;

// Use Blade view
$view = new BladeHtmlView;

$view['foo'] = $bar;

echo $view->setLayout('flower.sakura')->render();
```

The second is extend it.

``` php
use Windwalker\Core\View\BladeHtmlView;

class MyBladeHtmlView extends BladeHtmlView
{

}

$view = new MyBladeHtmlView;

echo $view->setLayout('flower.sakura')->render();
```

## Get Data in Blade Engine

In Blade template we don't need to use `$data`, all properties are at top level:

``` php
{{{ $item->title }}}

{{ $uri['base.path'] }}
```

## How to Use Blade Engine

See: [Blade Templating](http://laravel.com/docs/4.2/templates)

# Twig Engine

Twig is a well-known template language for PHP, created by Sensio. It uses a syntax similar to the Django and Jinja template languages.

There are 2 ways to use Twig engine, similar to Blade engine:

``` php
use Windwalker\Core\View\TwigHtmlView;

$view = new TwigHtmlView;
```

OR

``` php
use Windwalker\Core\View\TwigHtmlView;

class MyTwigHtmlView extends TwigHtmlView
{

}

$view = new MyTwigHtmlView;
```

## How to Use Twig

See: [Twig Documentation](http://twig.sensiolabs.org/documentation)

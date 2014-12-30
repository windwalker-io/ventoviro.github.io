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

If your View is in a package, you can put your template at `{Package}/Templates`, For example, we move `default.php` 
to `/src/Flower/Templates/flower/sakuras`, View will find this position priority than root templates folder. 
So we can make our templates following package folders.

## Use other layouts

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

Add paths to global that we don't need to set it every time, you must run this code before controller executed,
for example, you can run in `onAfterInitialise` event (See [Event](../more/event.html)) or `Package::initialise()`:

``` php
// Add global paths
\Windwalker\Core\Renderer\RendererHelper::addPath('/my/path', Priority::ABOVE_NORMAL);
```

See also: [Windwalker View](https://github.com/ventoviro/windwalker/tree/staging/src/View#htmlview)

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
// src/Flower/Controller/View/SakurasHtmlView.php

namespace Flower\Controller\View;

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

use Flower\Controller\View\SakurasHtmlView;
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

## Use Blade or Twig Engine

``` php
// Use Blade view
$view = new BladeHtmlView;

// OR Twig view
$view = new TwigHtmlView;
```

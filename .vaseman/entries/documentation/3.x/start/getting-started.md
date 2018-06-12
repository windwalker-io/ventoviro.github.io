---
layout: documentation.twig
title: Getting Started

---

This chapter, we'll start a simple page with MVC to show how to develop simple web application in Windwalker.

We assume you install Windwalker in `http://localhost/windwalker`, so you must use `http://localhost/windwalker/www` to open Windwalker home page.

## Create First Controller

Let's create a php class in `src/Flower/Controller/Sakura/GetContrtoller.php`.

The `Flower` is package name, and `Sakura` is controller name, `GetController` means it is a default `GET` action, this equals to
`indexAction` in other frameworks, while Windwalker uses single action controller, so every controller class only handles one action.

```php
<?php
// src/Flower/Controller/Sakura/GetController.php

namespace Flower\Controller\Sakura;

use Windwalker\Core\Controller\AbstractController;

/**
 * Sakura Controller
 */
class GetController extends AbstractController
{
	protected function doExecute()
	{
		return 'Hello World';
	}
}

```

Now, open `http://path/to/windwalker/www/flower/sakura` in browser, you will see `Hello World`.

Windwalker Controller follows single responsibility principle, you can add more logic to a controller but won't be confused by too many different actions in one class.

## Render View Template

We wish we can render a HTML template to browser, so please add a template file to `src/Flower/Templates/sakura/default.php`

```php
<?php
// src/Flower/Templates/sakura/default.php
?>
<h1>Hello <?php echo $this->escape($name); ?></h1>
```

And modify `GetController::doExecute()`, we get a View object, push a variable into it, then all `render()` method to return template.

```php
// src/Flower/Controller/Sakura/GetController.php

// ...

protected function doExecute()
{
    $view = $this->getView();

    $view['name'] = 'Sakura';

    return $view->render();

    // Another quick way
    return $this->renderView('Sakura', 'default', 'php', ['name' => 'Sakura']);
}

```

We'll get `<h1>Hello Sakura</h1>` in browser.

### Add HTML Frame

By extends parent layout, we can wrap our template by a parent template.

```php
<?php
// src/Flower/Templates/sakura/default.php
/**
 * @var  $this  \Windwalker\Core\Renderer\PhpRenderer
 */
?>

<?php $this->extend('_global.html') ?>

<!-- This will override parent `content` block -->
<?php $this->block('content'); ?>
    <div class="container">
        <h1>Hello <?php echo $this->escape($name); ?></h1>
    </div>
<?php $this->endblock(); ?>
```

The result

![p-2016-07-08-001](https://cloud.githubusercontent.com/assets/1639206/16676719/cddea284-44ff-11e6-8af7-c759e6826d5d.jpg)

> You can find `_global.html` in `templates/_globa/html.php`, this is the global template path.

> If you get `Route: home not found` message in DEBUG mode, just open `etc/dev/config.yml` and set `routing.debug` to `false`.

### Change View Layout

We support different template layout for one controller, the default is `default` layout, now try to add a `photo` layout.

```php
<?php
// src/Flower/Templates/sakura/photo.php
?>
<h1>Hello <?php echo $this->escape($name); ?></h1>
<img src="https://i.imgur.com/WVpwzJ9.jpg" alt="Sakura">
```

Then we render `photo.php` by `$view->setLayout('photo')`.

```php
// src/Flower/Controller/Sakura/GetController.php

// ...

protected function doExecute()
{
    $view = $this->getView();

    $view['name'] = 'Sakura';

    return $view->setLayout('photo')->render();
}
```

### Use Edge Template Engine

Edge is Windwalker built-in template engine to support Windwalker build flexible layout. It is a clone of Laravel Blade engine now but
add some new features.

We create a template named `default.edge.php` or `default.blade.php`.

```php
{{-- src/Flower/Templates/sakura/default.edge.php --}}

@extends('_global.html')

@section('content')
<div class="container">
    <h1>Hello {{ $name }} in edge engine</h1>
</div>
@stop
```

Render this template in Controller

```php
protected function doExecute()
{
    return $this->renderView('Sakura', 'default', 'edge', ['name' => 'Sakura']);
}
```

Will output

![p-2016-07-08-002](https://cloud.githubusercontent.com/assets/1639206/16676825/8c4911e0-4501-11e6-9495-3c0461e66732.jpg)

## Complete Ths Package

Our namespace start with `Flower\`, in Windwalker, we call this "Flower package", currently we haven't create package
 class, but controller and routing are works because Windwalker has a default simple routing help us mapping controller
 with URL, so `/flower/sakura` will auto direct to `Flower\Controller\Sakura\GetController`.

Simple routing is very slow so we can disable it in `etc/config.yml`

```yaml
# etc/config.yml

routing:
    simple_route: false
```

After set `simple_route` to `false`, you will see your application return
`RouteNotFoundException (404) Unable to handle request for route "flower/sakura"`

Now we must prepare a standard package environment so we can use more advanced functions in the future.

### Add Package Class

Create a `FlowerPackage` first.

```php
<?php
// Flower/FlowerPackage.php

namespace Flower;

use Windwalker\Core\Package\AbstractPackage;

class FlowerPackage extends AbstractPackage
{

}
```

Next, register it to `etc/app/web.php`, the `flower` key name is alias of your package, you can customize it if package name conflict.

```php
// ...

    'packages' => [
		'flower' => \Flower\FlowerPackage::class
	],

// ...
```

### Register Routing

And then, add a new routing profile to `etc/routing.yml`.

```yaml
# ...

flower:
    pattern: /flower
    package: flower
```

> You can also do this by using `$ php windwalker package install flower` in CLI after package registered.

The last step, create package routing at `src/Flower/routing.yml`.

```yaml
# src/Flower/routing.yml

sakura:
    pattern: /sakura
    controller: Sakura
```

OK, the Sakura page will return. To register a package and routing is a bit of bother, but it will be very
flexible if we want to organize a lot of controllers and routes in the future, if we have a big system and many developers.

## Model Repository

Windwalker provides a simple `ModelRepository` class to help you organize your data source.

Add SakuraModel class.

```php
<?php
// src/Flower/Model/SakuraModel.php

namespace Flower\Model;

use Windwalker\Core\Model\ModelRepository;

class SakuraModel extends ModelRepository
{
	public function getContent()
	{
		return [
			['title' => 'foo'],
			['title' => 'bar'],
			['title' => 'yoo'],
		];
	}
}
```

Use this Model in controller.

```php
// ...

/** @var SakuraModel $model */
$model   = $this->getModel();
$content = $model->getContent();

return $this->renderView('Sakura', 'default', 'edge', ['content' => $content]);
```

Modify the template to loop `content` variable.

```php
@extends('_global.html')

@section('content')
<div class="container">
    <h1>Hello Sakura</h1>
    <ul>
        @foreach ($content as $item)
        <li>{{ $item['title'] }}</li>
        @endforeach
    </ul>
</div>
@stop
```

The result:

![p-2016-07-08-003](https://cloud.githubusercontent.com/assets/1639206/16677127/bb37159e-4504-11e6-8096-9ca92d211738.jpg)

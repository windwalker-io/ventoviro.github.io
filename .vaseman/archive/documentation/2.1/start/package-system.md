---
layout: documentation.twig
title: Package System
redirect:
    3.x: core/package-system

---

## What is Package?

Package is the main component of Windwalker's structure. Here is a image that describe the package system:

![package](https://cloud.githubusercontent.com/assets/1639206/5579031/b4c50ed8-906e-11e4-8964-a1f2d949fc88.png)

From above image, we will know there can be multiple packages and its' own MVC system in Windwalker. That makes our application
 more flexible. For example, we can create a `FrontendPackage` and an `AdminPackage` as front-end and back-end respectively.
 And an `ApiPackage` to provide RESTful API for mobile APP.

Every package is pretty much a simple application having MVC, routing, configuration and database schema:

![mockup_3](https://cloud.githubusercontent.com/assets/1639206/5579086/ff7483ea-906f-11e4-9663-31c9276493af.png)

### Use Package as Extension

Package can be used as extensions for developer. You can create a package and submit it to [Packagist](https://packagist.org/).
Then anyone can install it by composer.

![mockup_2](https://cloud.githubusercontent.com/assets/1639206/5579085/ff715d8c-906f-11e4-92dc-43c3839e0ef8.png)

## Create Package

We will use `Flower` as example package. Create a php class in `/src/Flower/FlowerPackage.php`:

``` php
<?php
// /src/Flower/FlowerPackage.php

namespace Flower;

use Windwalker\Core\Package\AbstractPackage;

class FlowerPackage extends AbstractPackage
{
}
```

Then add this package to `/src/Windwalker/Web/Application.php`, at the `loadPackage()` method:

``` php
// /src/Windwalker/Web/Application.php
use Flower\FlowerPackage;

class Application extends WebApplication
{
    // ...

    public function loadPackages()
    {
        $packages = Windwalker::loadPackages();

        /*
         * Get Packages for This Application
         * -----------------------------------------
         * If you want a package only use in this application or want to override a global package,
         * set it here. Example: $packages[] = new Flower\FlowerPackage;
         */

        // Add package here, the array key is package name, you can customize it.
        $packages['flower'] = new FlowerPackage;

        return $packages;
    }

    // ...
}
```

The array key is package name alias, you can customize it. For example, If you use `$packages['flower']`, then you can
use `\Windwalker\Core\Package\PackageHelper::getPackage('flower')` to get this package object. But if you use

``` php
$packages['bar'] = new FlowerPackage;
```

You will have to get `FlowerPackage` it by  `\Windwalker\Core\Package\PackageHelper::getPackage('bar')`.

## Add Package Routing

Create `/src/Flower/routing.yml`, then add some routes:

``` yaml
## /src/Flower/routing.yml

sakura:
    pattern: /sakura(/id)
    controller: Sakura

sakuras:
    pattern: /sakuras
    controller: Sakuras

roses:
    pattern: /roses
    controller: Roses
```

Different from the global routing, you don't need to write all controller namespace, just write controller short name,
the package will auto find this controller. For example: `controller: Sakura` will find `Flower\Controller\Sakura\{action}` to execute.

We have to register this routes to root routing file. Open `/etc/routing.yml` And add this route profile.

``` yaml
## /etc/routing.yml

flower:
    pattern: /flower
    package: flower
```

The `package: flower` tells Windwalker to import all Flower package's routes, and all patterns will prefix with: `/flower/...`,
the compiled routes will look like:

``` yaml
flower@sakura:
    pattern: /flower/sakura(/id)
    controller: Sakura

flower@sakuras:
    pattern: /flower/sakuras
    controller: Sakuras

flower@roses:
    pattern: /flower/roses
    controller: Roses
```

Use browser to open `/flower/sakuras`, Windwalker will find `Flower\Controller\Sakuras\GetController` to render page.
We can create a controller to match this route:

``` php
<?php
// src/Flower/Controller/Sakuras/GetController.php

namespace Flower\Controller\Sakuras;

use Windwalker\Core\Controller\Controller;

class GetController extends Controller
{
	protected function doExecute()
	{
		return 'Output of Sakuras Controller';
	}
}
```

About how routing and controller work, please see [Routing And Controller](routing-controller.html) section.

## Add & Get Packages

Use `PackageResolver`.

``` php
$resolver = $container->get('package.resolver');

$resolver->getPackage('flower'); // Get flower package

$resolver->getPackage(); // Get current package

$resoler->addPackage('alias', $package); // Add new package
```

Use `PackageHelper`, this class is a facade of `PackageResolver`.

``` php
use Windwalker\Core\Package\PackageHelper;

PackageHelper::getPackage('flower'); // Get flower package

PackageHelper::getPackage(); // Get current package

PackageHelper::addPackage('alias', $package); // Add new package
```

You can also get package from Application.

``` php
$app = Ioc::getApplication();

$app->getPackage([$alias|null]);
```

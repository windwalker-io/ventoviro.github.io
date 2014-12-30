layout: documentation.twig
title: Package System

---

# What is Package?

Package is the main component of Windwalker's structure. Here is a image that describe the package system:

![package](https://cloud.githubusercontent.com/assets/1639206/5579031/b4c50ed8-906e-11e4-8964-a1f2d949fc88.png)

From this image, we will know there can be multiple packages and its' own MVC system in Windwalker. That make our application
 more flexible. For example, we can create a `FrontendPackage` and an `AdminPackage` to maintain your front-end and back-end.
 And an `ApiPackage` to provide RESTful API for mobile APP.
 
Every package just similar to a little application, it contains MVC, routing, configuration and database schema:

![mockup_3](https://cloud.githubusercontent.com/assets/1639206/5579086/ff7483ea-906f-11e4-9663-31c9276493af.png)
  
## Use Package as Extension

Package can be extensions for other developer. You can create a package and submit it to [Packagist](https://packagist.org/).
Then everyone are able to install it by composer.

![mockup_2](https://cloud.githubusercontent.com/assets/1639206/5579085/ff715d8c-906f-11e4-92dc-43c3839e0ef8.png)

# Create Package

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

The array key is package name, you can customize it. For example, If you use `$packages['flower']`, you can use `PackageHelper::getPackage('flower')`
to get this package object. But if you use `$packages['bar']`, you will have to get it by  `PackageHelper::getPackage('bar')`.

# Add Package Routing

Create `routing.yml` at `/src/Flower`, then add some routes:

``` apache
# /src/Flower/routing.yml

sakura:
    pattern: /sakura(/id)
    controller: Flower\Controller\Sakura
    
sakuras:
    pattern: /sakuras
    controller: Flower\Controller\Sakuras
    
roses:
    pattern: /roses
    controller: Flower\Controller\Roses
```

We have to register this routes to root routing file. Open `/etc/routing.yml` And add this route profile.

``` apache
# /etc/routing.yml

flower:
    pattern: /flower
    package: flower
```

The `package: flower` tells Windwalker to import all Flower package's routes, and all patterns will prefix with: `/flower`, 
the compiled routes will look like:

``` html
flower:sakura:
    pattern: /flower/sakura(/id)
    controller: Flower\Controller\Sakura
    
flower:sakuras:
    pattern: /flower/sakuras
    controller: Flower\Controller\Sakuras
    
flower:roses:
    pattern: /flower/roses
    controller: Flower\Controller\Roses
```

Use browser open `/flower/sakuras`, Windwalker will find `Flower\Controller\Sakuras` to render page. We can create a controller to match this route:

``` php
<?php
// src/Flower/Controller/Sakuras/GetController.php

namespace Flower\Controller\Sakuras;

use Windwalker\Core\Controller\Controller;

class GetController extends Controller
{
	protected function doExecute()
	{
		return 'Sakuras';
	}
}
```

About how routing and controller working, please see [Routing And Controller](routing-controller.html) section.

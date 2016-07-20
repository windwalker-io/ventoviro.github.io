---

layout: rad.twig
title: Getting Started

---

## Installation

Please install Windwalker Framework first. See [Windwalker Installation](../../documentation/3.x/start/installation.html)

Then add `"windwalker/phoenix": "~1.1"` to require block and run `composer update`.

### Register Phoenix Package

Add Phoenix package object to `etc/app/windwalker.php`.

``` php
// ...

    'packages' => [
        // ...
        'phoenix' => Phoenix\PhoenixPackage::class
    ]

// ...
```

### Install Phoenix Assets

Run this command to install phoenix assets:

``` bash
php windwalker package install phoenix
```

See [Sync Package Assets](../../documentation/3.x/services/asset.html#sync-package-assets)

## Generate A New Package

Please make sure you have correct database configuration, then please type:

``` bash
$ php windwalker muse init Flower sakura.sakuras -sm
```

And you will see this result:

![img](https://cloud.githubusercontent.com/assets/1639206/9724699/858b4cf2-560e-11e5-9137-956532efdb2e.png)

Package `flower` has been auto generated. We must register this package too, add it in `etc/app/windwalker.php`:

``` php
// ...

    'packages' => [
        // ...
        'phoenix' => Phoenix\PhoenixPackage::class,
        'flower'  => Flower\FlowerPackage::class
    ]

// ...
```

Then create symlink to assets of this package:

``` bash
php windwalker asset sync flower [--hard]
```

Direct sub routing to package routing at `etc/routing.yml`:

``` yaml
## etc/routing.yml

##...

## Add this route
flower:
    pattern: /flower
    package: flower
```

OK, now we can open `http://localhost/{your_project}/www/flower/sakuras` in browser, you will see a sample admin UI.

![img](https://cloud.githubusercontent.com/assets/1639206/9724923/231ea408-5611-11e5-8448-ff29aa059306.png)

Use PHP built-in server:

``` bash
cd {your_project}/www
php -S localhost:8000
```

And open `http://localhost:8000/flower/sakuras` to test your application.

## Debug Mode

Add `/dev.php` before your URL, Windwalker will start debug mode and provides a powerful debug console.

> `http://localhost:8000/dev.php/flower/sakuras`

![img](https://cloud.githubusercontent.com/assets/1639206/9725055/0cc4e1fc-5613-11e5-9f0d-c373d7d68c87.png)



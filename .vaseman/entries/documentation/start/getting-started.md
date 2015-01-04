layout: documentation.twig
title: Getting Started

---

# Installation

## Install Starter Application

Windwalker use [Composer](https://getcomposer.org/) as package manager, make sure you [install composer](https://getcomposer.org/download/)
 first.

To start install Windwalker, please open terminal and type the following:

``` bash
$ php composer.phar create-project windwalker/starter project_dir 2.0.* --no-dev
```

> For use of unit test or any development purpose, simply remove `--no-dev` option.

The [Starter](https://github.com/ventoviro/windwalker-starter) package is a default application to start a project (like Symfony Standard).
If you want to use Windwalker as a library, see next section.

## Use Windwalker as Library

Add `windwalker/framework` as your project's dependencies in `composer.json`.

``` json
{
    "require": {
        "windwalker/framework": "~2.0@stable"
    }
}
```

> `~2.0` is equivalent to 2.0 to 2.9

You can also pick any child packages rather than the complete framework set. This is an example to install Session and Form package:

``` json
{
    "require": {
        "windwalker/session": "~2.0@stable",
        "windwalker/form": "~2.0@stable"
    }
}
```

See all available packages on [Packagist](https://packagist.org/packages/windwalker/)

# Overview

## Open Windwalker Public Root

After installed, use browser open `/www` then you will see default landing page.

![img](https://cloud.githubusercontent.com/assets/1639206/5576484/31c9834c-9037-11e4-9f97-8f73d0822043.png)

## File Structure

| Name | Description |
| ---- | ----------- |
| `/bin`  | All executable files, there is a `console` file can run Windwalker Console. |
| `/etc`  | All configuration and routing files. |
| ` -- define.php`  | The system path constants. |
| ` -- config.yml`  | Basic configuration file. |
| ` -- secret.dist.yml`  | Contains some secret information like DB account. <br /> Please rename to `secret.yml` before use. |
| ` -- routing.yml` | The routing configuration file. |
| `/resources` | Some non-PHP or non-classes files. |
| ` -- languages` | Language files, default is `.ini` format |
| ` -- migrations` | Database migration files. See [Migration](../db/migration.html) |
| ` -- seeders` | Database seeder files. See [Seeder](../db/seeder.html) |
| `/src` | All PHP classes in your project. |
| `/templates` | Layout and template files. |
| `/vendor` | All 3rd party libraries. |
| `/www` | Web public root (or /public in general) |
| ` -- media` | Web front-end assets (image, vedio, css, etc..) |
| ` -- .htaccess` | Htaccess config for Apache, you need this file to make mod_rewrite work. |
| ` -- index.php` | Default web application entrance. |
| ` -- dev.php` | Default web application entrance for dev environment. |
| `/phpunit.xml.dist` | PHPUnit configuration file. Rename to `phpunit.xml` before use. |
| `/README.md` | Readme file |
| `/composer.json` | Composer configuration file |

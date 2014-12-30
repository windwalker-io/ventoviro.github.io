layout: documentation.twig
title: Getting Started

---

# Installation

## Install Starter Application

Windwalker use [Composer](https://getcomposer.org/) as package manager, make sure you [install composer](https://getcomposer.org/download/)
 before installing it.

To start install Windwalker, please open terminal and type this command:

``` bash
$ php composer.phar create-project windwalker/starter project_dir 2.0.* --no-dev
```

> If you want to use unit test or develop Windwalker it-self, remove `--no-dev` option.

The [Starter](https://github.com/ventoviro/windwalker-starter) package is a default application to start a project (like Symfony Standard).
If you want to use Windwalker as a library, see next section.

## Use Windwalker as Library

If you have your own application project and want to include Windwalker as library, just add `windwalker/framework` to require block in `composer.json`.

``` json
{
    "require": {
        "windwalker/framework": "~2.0@stable"
    }
}
```

> `~2.0` means 2.0 to 2.9

You can also install specific packages instead install whole framework. This is an example to install Session and Form package:

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

## The File Structure

| Name | Description |
| ---- | ----------- |
| `/bin`  | All executable files, there is a `console` file can run Windwalker Console |
| `/etc`  | All configuration and routing files. |
| ` -- define.php`  | The system path constants. |
| ` -- config.yml`  | Basic configuration file. |
| ` -- secret.dist.yml`  | Contains some secret information like DB account. <br /> Please rename to `secret.yml` to use it |
| ` -- routing.yml` | The routing configuration file |
| `/resources` | Some non-PHP or non-classes files |
| ` -- languages` | Language files, default is INI format |
| ` -- migrations` | Database migration files. See [Migration](../db/migration.html) |
| ` -- seeders` | Database seeder files. See [Seeder](../db/seeder.html) |
| `/src` | All PHP classes files for your project. |
| `/templates` | The layout files. |
| `/vendor` | All 3rd libraries files |
| `/www` | Web public root |
| ` -- media` | Assets files for front-end |
| ` -- .htaccess` | Htaccess config for Apache, you need this file to make mod_rewrite work. |
| ` -- index.php` | System entry. |
| ` -- dev.php` | System entry for Development mode. |
| `/phpunit.xml.dist` | PHPUnit configuration file. Rebane to `phpunit.xml` to use. |
| `/README.md` | Readme file |
| `/composer.json` | Composer configuration file |
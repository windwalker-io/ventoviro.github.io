---
layout: documentation.twig
title: Installation

---

## Installation

### Starter Application

Install via [Composer](https://getcomposer.org/)

``` bash
$ composer create-project windwalker/starter project_dir ~3.0 [--dev]
```

> For development purpose, you can add `--dev` to this command.

After project file downloaded, the installation script will ask you some information to initial system environment.

``` bash
> Windwalker\Composer\StarterInstaller::rootInstall

Salt to generate secret [Windwalker-577f076209eff]:
Auto created secret key.

Do you want to use database? [Y/n]: y

Database driver only support mysql/postgresql now.
Database driver [mysql]:
Database host [localhost]:
Database name [acme]: db_name
Database user [root]: db_user
Database password: ******
Table prefix [wind_]:

Database config setting complete.

Install complete.
```

The [Starter](https://github.com/ventoviro/windwalker-starter) package is a default application help us start a project.

### Use Windwalker as Library

If you just want to use Windwalker as a library, add `windwalker/framework` as your project's dependencies in `composer.json`.

``` json
{
    "require": {
        "windwalker/framework": "~3.0"
    }
}
```

> `~3.0` is equivalent to 3.0 to 3.9

You can also pick any child packages rather than the complete framework set. This is an example to install Session and Form package:

``` json
{
    "require": {
        "windwalker/session": "~3.0",
        "windwalker/form": "~3.0"
    }
}
```

See all available packages on [Packagist](https://packagist.org/packages/windwalker/)

## Overview

### Open Windwalker Public Root

After installed, use browser open `/www` then you will see default landing page.

![img](https://cloud.githubusercontent.com/assets/1639206/5576484/31c9834c-9037-11e4-9f97-8f73d0822043.png)

### File Structure

| Name | Description |
| ---- | ----------- |
| `/bin`  | All executable files, there is a `console` file can run Windwalker Console. |
| `/etc`  | All configuration and routing files. |
| ` -- app/*.php`  | Basic config files for different environments. |
| ` -- package/*.php`  | Config files for every packages. |
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
| ` -- asset` | Web front-end assets (image, vedio, css, etc..) |
| ` -- .htaccess` | Htaccess config for Apache, you need this file to make mod_rewrite work. |
| ` -- index.php` | Default web application entrance. |
| ` -- dev.php` | Default web application entrance for dev environment. |
| `/phpunit.xml.dist` | PHPUnit configuration file. Rename to `phpunit.xml` before use. |
| `/README.md` | Readme file |
| `/composer.json` | Composer configuration file |
| `.mode` | The env mode config file |

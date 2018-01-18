---

layout: rad.twig
title: Generator

---

## The Phoenix Generator Commands

Phoenix includes [PHP Muse](https://github.com/asika32764/muse) as template generator.

Please type `php windwalker muse -h`, and you will see this message:

```  bash
Windwalker Console - version: 2.0
------------------------------------------------------------

[muse Help]

The template generator.

Usage:
  muse <command> [option]


Options:

  --type            Generate type.
  -t | --tmpl       Using template.
  -h | --help       Display this help message.
  -q | --quiet      Do not output any message.
  -v | --verbose    Increase the verbosity of messages.
  --ansi            Set 'off' to suppress ANSI colors on unsupported terminals.

Commands:

  init             Init a new package.
  add-subsystem    Add a singular & plural MVC group.
  add-item         Add a singular MVC template.
  add-list         Add a plural MVC template.
  convert          Convert a package back to template.
```

## Init Package

Use `init` command to generate package template.

Usage

``` bash
init [<namespace>\\]<name> <item>[.<list>] [-s|--seed] [-m|--migrate] [-t|--tmpl=default]
```

> If you only type item name, Windwalker will guess the list name by string inflector.

### Example

Init a first level package in `src/Flower` with two MVC groups: `Sakura` and `Sakuras`.

``` bash
php windwalker muse init Flower sakura.sakuras
```

Init a package in `Asuka\` namespace, the package will located at `src/Asuka/Flower`.

``` bash
php windwalker muse init Asuka\\Flower sakura.sakuras
```

> We support normal slash `Asuka/Flower` that will be more convenience.

Init a package than run migration and seeder.

``` bash
php windwalker muse init Asuka\\Flower sakura.sakuras -sm
```

Init a package with another template named: `simple`.

``` bash
php windwalker muse init Asuka\\Flower sakura.sakuras -t=simple
```

If you forgot to run migration and seeder, you can use this command to run it after generated:

``` bash
php windwalker migration migrate -p=flower --seed
```

### Register This Package

See [Windwalker Package Document](http://windwalker.io/documentation/start/package-system.html), we have to register
this package to Windwalker main application.

For example, if we create a package named `Flower`, we have to register `Flower\FlowerPackage` class to Windwalker.

``` php
// etc/app/windwalker.php

    'packages' => [
        // ...

        // Add this line
        'flower' => Flower\FlowerPackage::class
    ]

// ...
```

Then register Flower routing to `etc/routing.yml`, the URI begin with `/flower/*` will direct to Flower package.

``` yaml
## etc/routing.yml

##...

## Add this route
flower:
    pattern: /flower
    package: flower
```

Run this command to link assets files in Flower package, it will create a symlink `/www/asset/flower <====> /src/Flower/Resources/asset`.

``` bash
php windwalker asset sync flower
```

> If you are in Windows, you must open `cmd.exe` or `powershell.exe` with administrator access to run this command.
> If your system do not support symlink, you have to use hard copy:

> `php windwalker asset sync phoenix  --hard`

> ---
> You can do these steps by simply type `php windwalker package install flower [--hard]`

Make sure you have ran the migration and seeder. Open browser and go to `http://{your_project}/dev.php/flower/sakuras`.

![img](https://cloud.githubusercontent.com/assets/1639206/9725055/0cc4e1fc-5613-11e5-9f0d-c373d7d68c87.png)

> If you are bother to see the star `**` wrapped every string, set config `language.debug` to `false` or `0`.

## Add Subsystem

If you want to add two MVC groups with singular and plural, use `add-subsystem` command. All parameters are same as `init`.

Usage: `add-subsystem [<namespace>\\]<name> <item>.<list> [-s|--seed] [-m|--migrate] [-t|--tmpl=default]`

For example, add `rose.roses`.

``` bash
php windwalker muse add-subsystem Flower rose.roses -sm
```

Then you will see a new submenu item in admin UI.

![Imgur](https://i.imgur.com/mWiDVFA.jpg)

## Add Item & List

Just use `add-item` and `add-list` commands. All parameters are same as `init` and `add-subsystem`.

Add item usage: `add-item [<namespace>\\]<name> <item>.<list> [-t|--tmpl=default]`

Add list usage: `add-list [<namespace>\\]<name> <item>.<list> [-t|--tmpl=default]`

> You still must provides both item and list name for this two commands because phoenix must locale some core classes of your MVC.

## Simple Template

``` bash
php windwalker muse init Flower sakura.sakuras -t=simple
```

Sometimes we don't want a complete admin template, you can just use `simple` template, this template contains basic
Controller/Model/View and load database as a list. you can easily modify this template to a blog or any type of applications.

![p-2016-07-20-012](https://cloud.githubusercontent.com/assets/1639206/16977910/99c79614-4e8b-11e6-9760-4840a37d8526.jpg)

## Empty Template

If you only want a basic empty Controller/Model/View that you can fill your own logic, use `empty` template.

``` bash
php windwalker muse init Vehicle car.cars -t=empty
```

![Imgur](https://i.imgur.com/HqRE5IV.jpg)

## Create Your Own Template

Use `convert` command to convert a existing package to be template then your team will able to create your own templates.

Usage: `convert [<namespace>\\]<name> <item>.<list> [-t|--tmpl=default]`

> #### TODO:

> Currently phoenix will convert template back to phoenix folder in `/vendor`. we will add `--dir` support in the future.

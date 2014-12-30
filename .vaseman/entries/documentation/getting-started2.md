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

}
```

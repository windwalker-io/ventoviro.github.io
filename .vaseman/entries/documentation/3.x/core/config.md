---
layout: documentation.twig
title: Config & Setting
redirect:
    2.1: start/config

---

## Global Config

Windwalker stores global config files in `/etc` folder, you can see `config.yml` and `secret.yml` file. These two config files will be merged
in runtime, so settings in `secret.yml` will override the same key in `config.yml`.

### config.yml

The `config.yml` stores some global system settings includes the language locale, session time, cache storage, timezone etc.
Which is your application need and will be track by VCS, .

### secret.yml

And the `secret.yml` stores some customize or sensitive settings which you don't want to track by VCS, for example, the database account
or the 3rd party service API key, you will not hope to push these information to GitHub or other public VCS service, so we will
write some keys in `secret.dist.yml`.

For example, we can prepare some empty keys in `secret.dist.yml` then push this file to VCS.

```yaml
## secret.dist.yml

## Keep NULL to notice developers fill this data.
amazon:
    key: ~
    secret: ~
```

When Someone clone this project, they must copy `secret.dist.yml` to `secret.yml` and fill the keys.

```yaml
## secret.yml

## Fill real data to use.
amazon:
    key: ************
    secret: *************************
```

### Override config.yml

If you set a config in config.yml

```yaml
## config.yml

foo: bar
```

You can override it in secret.yml

```yaml
## secret.yml

foo: yoo
```

Now the `foo` value will be `yoo` not `bar`.

## Environment Config

Environment configs stored ad `etc/app/`:

- `web.php` => Config file for Web
- `dev.php` => Config file for Web development mode
- `console.php` => Config file for CLI
- `test.php` => Config file for test
- `windwalker.php` => Config file for all

Environment config contains some important system settings, you can add providers, listeners and middlewares to
customize your system process.

## Get & Set Config Data

In Application, you can use `get()` to get config.

```php
// Use Ioc get Application then get config
$value = Ioc::getApplication()->get('foo', [default value]);
```

```php
// Get config in controller
$value = $this->app->get('foo', [default value]);

// Set data
$this->app->set('foo', 'baz');
```

Get config by Config object.

```php
// In everywhere
$config = Ioc::getConfig();

$foo = $config->get('foo');

// You can also get data by array access.

$foo = $config['foo'];
```

Config is a Structure object, please see [Structure Object](../more/structure.html)

### Nested Data

If you have multi-level config:

```yaml
morning:
    break:
        first: egg
```

Please use dot (`.`) as separator:

```php
$config->get('morning.break.first'); // sakura

// OR get data by array access

$config['morning.break.first']; // sakura
```

## Add New Config Files

Open `src/app/windwalker.php` (or `web.php`, `console.php`), add new config file at `configs` element. Windwalker supports `php`, `json` and `yaml` format
to save config.

```php
// ...
    'configs' => [
        500 => WINDWALKER_ETC . '/my-config.yml'
    ],
// ...
```

We use numeric keys to support sorting of config files, the bigger is later loaded, so last config will override previous.

## Package Config

Every packages has their own configs. If you have a `FlowerPackage` and it's alias is `flower`, create a file to `etc/package/flower.php`.
The Package object will auto load this config file.

```php
<?php
// etc/package/flower.php

return [
    'foo' => 'bar'
];
```

You can get package config from package object:

```php
$package = PackageHelper::getPackage('flower');

$package->get('foo'); // bar
```

## Description of `config.yaml`

```yaml
system:
    # Enanle debug mode, will disable cache, and log some errors.
    debug: false

    # The PHP error reporting level, 0 is hide all errors, -1 is the biggest report level.
    error_reporting: 0

    # Default system timezone.
    timezone: 'UTC'

    # Secret code will be a salt to generate hashs when system running,
    # Will be replace when Windwalker installation.
    secret: 'This-token-is-not-safe'

error:
    # The error template & renderer engine
    template: windwalker.error.default
    engine: php
    log: true

session:
    # Session handler, supports `native`, `database`, `apc`, `memcached`
    handler: native
    # By minutes
    expire_time: 15

routing:
    # Enable routing debug, if route key not found when you generate routs,
    # will raise error and stop application.
    # @deprecated
    debug: true

    # Simple routing help us auto find controller by URL: `{package}/{controller}` without routing config,
    # Disable this function will enhance performance.
    simple_route: true

cache:
    # Disabled cache will make all cache as null storage and not stored to storage.
    # But you can use CacheFactory::createCache('mycache') to ignore this settings.
    enabled: false

    # The default sotrage, you can use other storages by use `CacheManager::getCache('name', 'storage')`
    # Support storages: file / raw_file / memcached / null / redis / array / runtime_array
    storage: file

    # Cache serializer decided how to serialize and store data into storage.
    # Support serializers: php / json / string / raw
    serializer: php

    # Cache time (minutes)
    time: 15

crypt:
    # The Crypt cipher method.
    # Support ciphers: blowfish (bf) / aes-256 (aes) / 3des / php_aes / sodium
    cipher: blowfish

    # The hashing algorithm
    # Support algorithms: blowfish (bf) / md5 / sha256 / sha512 / argon2 / scrypt
    hash_algo: blowfish

    # The hashing cost depends on different algorithms. Keep default if you don't know how to use it.
    hash_cost: ~

asset:
    # The asset folder in public root, default is `asset`
    folder: asset

    # The full asset uri, default is NULL. If you set this uri, it will override `asset.folder`.
    # This is useful if you want to put all asset files on cloud storage.
    uri: ~

language:
    # Language debug will mark untranslated string by `??` and stored orphan in Languages object.
    debug: false

    # The current locale
    locale: en-GB

    # The default locale, if translated string in current locale not found, will fallback to default locale.
    default: en-GB

    # Dfault languaghe file format, you can use other foramt in runtime by `Translator::loadFile($file, 'yaml')`
    format: ini

console:
    # Custom scripts, add some commands here to batch execute. Example:
    # scripts:
    #     foo:
    #         - git pull
    #         - composer install
    #         - php windwalker migration migrate
    #
    # Then just run `$ php windwalker run foo`
    scripts: ~
```

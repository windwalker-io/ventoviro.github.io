---
layout: documentation.twig
title: Config & Setting

---

# How to Configure Your Application

Windwalker stores config files in `/etc` folder, you can see `config.yml` and `secret.yml` file. These two config files will be merged
in runtime, so settings in secret.yml can override the same key in config.yml.

## config.yml

The `config.yml` stores some global system settings includes the language locale, session time, cache storage, timezone etc. 
Which is your application need and will be track by VCS, .

## secret.yml

And the `secret.yml` stores some customize settings which you don't want to track by VCS, for example, the database account
or the 3rd party service API key, you will not hope to push these information to GitHub or other public VCS service, so we will
write some keys in `secret.dist.yml`.

For example, we can prepare some empty keys in `secret.dist.yml` then push this file to VCS.

``` yaml
# secret.dist.yml

# Keep NULL to notice developers fill this data. 
amazon:
    key: ~
    secret: ~
```

When Someone clone this project, they must copy `secret.dist.yml` to `secret.yml` and fill the keys.

``` yaml
# secret.yml

# Fill real data to use.
amazon:
    key: ************
    secret: *************************
```

## Override config.yml

If you set a config in config.yml

``` yaml
# config.yml

foo: bar
```

You can override it in secret.yml

``` yaml
# secret.yml

foo: yoo
```

Now the `foo` value will be `yoo` not `bar`.


# Get & Set Config Data

In Application, you can use `get()` to get config.

``` php
// Use Ioc get Application then get config
$value = Ioc::getApplication()->get('foo', [default value]);
```

``` php
// Get config in controller
$value = $this->app->get('foo', [default value]);

// Set data
$this->app->set('foo', 'baz');
```

Get config by Config object.

``` php
// In everywhere
$config = Ioc::getConfig();

$foo = $config->get('foo');

// You can also get data by array access.

$foo = $config['foo'];
```

Config is a Registry object, please see [Registry Object](../more/registry.html)

## Nested Data

If you have multi-level config:

``` yaml
morning:
    break:
        first: egg
```

Please use dot (`.`) as separator:

``` php
$config->get('morning.break.first'); // sakura

// OR

$config['morning.break.first']; // sakura
```

# Add New Config Files

Open `src/Windwalker/Web/Application.php` and modify `loadConfiguration()`, you can load any files as config if you want.

``` php
protected function loadConfiguration(Registry $config)
{
    Windwalker::loadConfiguration($config);
    
    $config->loadFile(WINDWALKER_ETC . '/my-config.yml', 'yaml');
    
    // Or load other formats
    $config->loadFile(WINDWALKER_ETC . '/my-config.ini', 'ini');
    
    $config->loadFile(WINDWALKER_ETC . '/my-config.xml', 'xml');
    
    $config->loadFile(WINDWALKER_ETC . '/my-config.php', 'php'); // Must return array
}
```

# Description of Config

The config.yml looks like above:

``` yaml
system:
    debug: 0
    error_reporting: 0
    timezone: 'UTC'
    secret: 'This-token-is-not-safe'
session:
    handler: native
    expire_time: 15
routing:
    debug: 0
cache:
    enabled: 0
    storage: file
    handler: serialize
    dir: cache
    time: 15
language:
    debug: 0
    locale: en-GB
    default: en-GB
    format: ini
    path: resources/languages
```

| Key | Sub Key | Value |
| -------- | -- | -- |
| `system` | | |
| | `debug` | Enable debug mode or not |
| | `error_reporting` | PHP error_reporting setting, set -1 to enable strict mode, set 0 to close all error messages. |
| | `timezone` | The default system timezone. |
| | `secret` | A random string as secret code to generate some tokens |
| `session` | |  |
| | `handler` | The session handler, `native` is the native php session handler, you can use `database` or `memcache`, please see [Windwalker Session](https://github.com/ventoviro/windwalker/tree/master/src/Session#windwalker-session) |
| | `expire_time` | Session expire time, the unit is minute. |
| `routing` | |  |
| | `debug` | Enable routing debug, see [Routing](./documentation/mvc/uri-route-building.html) |
| `cache` | | |
| | `enabled` | Enable system cache. |
| | `storage` | Cache storage, FileStorage is the default. See [Caching](./documentation/more/caching.html) |
| | `handler` | Hot cache convert array and object to string. |
| | `dir` | Cache storage folder. |
| | `time` | Cache TTL time (minute). |
| `language` | |  |
| | `debug` | Enable Language debug. |
| | `locale` | The current locale. |
| | `default` | The default locale. |
| | `format` | The language translate file format. See [Windwalker Language](https://github.com/ventoviro/windwalker/tree/master/src/Language) |
| | `path` | The language file path. |



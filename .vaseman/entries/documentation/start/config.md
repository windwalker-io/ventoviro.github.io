---
layout: documentation.twig
title: Config & Setting

---

# How to Configure Your Application

Windwalker stores config files in `/etc` folder, you can see `config.yml` and `secret.yml` file, the `config.yml` stores
some global settings which your application need and will be track by VCS. And the `secret.yml` stores some customize
settings which you don't want to track by VCS.

# config.yml

The config.yml looks like above:

``` apache
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



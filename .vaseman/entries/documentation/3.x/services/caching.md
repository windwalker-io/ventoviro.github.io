---
layout: documentation.twig
title: Caching

---

## Configuration

Using cache will be very easy in Windwalker, the cache configuration is located at `/etc/config.yml`.

``` yaml
cache:
    enabled: 0
    storage: file
    serializer: php
    time: 15
```

The `enabled` property controls global cache start or not, can be close if you want to test your application. `storage` controls which cache storage method
 you use, can be `file`, `runtime`, `memcached` or [more](https://github.com/ventoviro/windwalker-cache#available-storage). 
 About storage and handler, please see [Windwalker Cache](https://github.com/ventoviro/windwalker-cache).
 
## Use Global Cache

Windwalker contains a global main cache object that you can configure it in `config.yml`.

``` php
$cache = Ioc::getCache();

// OR

$cache = $container->get('cache');

// Store cache item
$cache->set('key', 'value');

// Get cache
$value = $cache->get('key');

// Check cache item
$cache->exists('key');
```

## Custom Cache Object

We can still create our own custom cache objects:

``` php
$cacheManager = $container->get('cache.manager');

$cache = $cacheManager->getCache('mycache_name', 'file'); // Every name will be singleton object.

// OR use Facade

use Windwalker\Core\Cache\CacheFactory;

CacheFactory::getCache('mycache_name', 'file');
```

If you set `cache.enabled` to `false` or in DEBUG mode, all cache created from `getCache()` will be `NullStorage`, it won't cache any data.

You can set ignore global to make cache manager ignore config settings.

``` php
CacheFactory::ignoreGlobal(true);
```

## Disable Cache or Debug

When debug property in global config se to `true` or `cache.disabled` set to `true`, the cache storage will auto set to `NullStorage`, cache can still be used
 but no work.
 
``` php
// If cache disabled
$cache = \Windwalker\Ioc::getCache();

// Cache object can be get and operate, but the key will always not exists.
if (!$cache->exists('flower'))
{
    $data = $flowerMapper->loadAll();

    $cache->set('flower', $data);
}
else
{
    $data = $cache->get('flower');
}

return $data;
```

## CacheFactory

### Use `CacheFactory::getCache()`

CacheFactory is a cache creator, it will store each cache object as singleton by different name and options.

``` php
use Windwalker\Core\Cache\CacheFactory;

$myFileCache = CacheFactory::getCache('cache_name', 'file');
$myArrayCache = CacheFactory::getCache('cache_name', 'array');
```

The default cache is array cache, it means our data only keep in array but will not save as files.

### Custom Cache Options

``` php
$options = array(
    'cache_dir' => WINDWALKLER_CACHE, // Only for file storage
    'cache_time' => 999 // minutes
);

$cache = CacheFactory::getCache('cache_name', 'file', 'php', $options);
```

## Storage

### ArrayStorage and RuntimeArrayStorage

This is default storage, which will store data in itself and will not depends on any outside storage engine.

The `RuntimeArrayStorage` use static property to storage data, which means all data will live in current runtime
no matter how many times you create it.

``` php
$cache = CacheFactory::getCache('my_cache', 'array');
$cache = CacheFactory::getCache('my_cache', 'runtime_array');
```

### FileStorage

Create a cache with `FileStorage` and set a path to store files.

``` php
$cache = CacheFactory::getCache('my_cache', 'file');

$cache->set('flower', array('sakura'));
```

The file will store at `{ROOT}/cache/my_cache/~5a46b8253d07320a14cace9b4dcbf80f93dcef04.data`, and the data will be serialized string.

```
a:1:{i:0;s:6:"sakura";}
```

#### File Group

Group is a subfolder of your storage path.

``` php
$cache = CacheFactory::getCache('mygroup', 'file');

$cache->set('mygroup', array('sakura'));
```

The file wil store at `{ROOT}/cache/mygroup/~5a46b8253d07320a14cace9b4dcbf80f93dcef04.data` that for organize your cache folder.

#### PHP File Format and Deny Access

If your cache folder are exposure on web environment, we have to make our cache files unable to access. The argument 3
 of `FileStorage` is use to deny access.

``` php
$cache = CacheFactory::getCache('mygroup', 'file', 'php', ['deny_access' => true]);

$cache->set('flower', array('sakura'));
```

The stored file will be a PHP file with code to deny access:

`/your/cache/path/mygroup/~5a46b8253d07320a14cace9b4dcbf80f93dcef04.php`

``` php
<?php die("Access Deny"); ?>a:1:{i:0;s:6:"sakura";}
```

### Available Storages

- ArrayStorage (`array`)
- RuntimeArrayStorage (`runtime_array`)
- FileStorage (`file`)
- MemcachedStorage (`memcached`)
- RedisStorage (`redis`)
- WincacheStorage (`wincache`)
- XcacheStorage (`xcache`)
- NullStorage (`null`)

## Serializer

The default `PhpSerializer` (`php`) will make our data be php serialized string, if you want to use other format,
just change serializer at second argument of Cache object.

``` php
$cache = CacheFactory::getCache('my_cache', 'file', 'json');

$cache->set('flower', array('flower' => 'sakura'));
```

The stored cache file is:

```
{"flower":"sakura"}
```

### Full Page Cache

Sometimes we may need to store whole html as static page cache. `StringSerializer` (`string`) or `RawSerializer` (`raw`) helps us save raw data as string:

``` php
$cache = CacheFactory::getCache('my_cache', 'file', 'string');

$url = 'http://mysite.com/foo/bar/baz';

if ($cache->exists($url))
{
    echo $cache->get($url);

    exit();
}

$html = $view->render('html.layout');

$cache->set($url, $html);

echo $html;
```

### PhpFileSerializer

This serializer can save array data as a php file, will be useful when we need to cache config data.

``` php
$cache = CacheFactory::getCache('my_cache', 'file', 'php_file');

$config = array('foo' => 'bar');

$cache->set('config.name', $config);

$cache->get('config.name'); // Array( [foo] => bar )
```

The cache file will be:

``` php
<?php

return array (
  'foo' => 'bar',
);
```

### Available Serializers

- PhpSerializer (`php`)
- PhpFileSerializer (`php_file`)
- JsonSerializer (`json`)
- StringSerializer (`string`)
- RawSerializer (`raw`)

## PSR6 Cache Interface

Windwalker Cache Storage are all follows [PSR6](http://www.php-fig.org/psr/psr-6/), so you can use other libraries'
CacheItemPool object as storage, you can also directly use Storage object.

``` php
use Windwalker\Cache\Item\CacheItem;
use Windwalker\Cache\Storage\FileStorage;

$cachePool = new FileStorage(__DIR__ . '/cache');

$cachePool->save(new CacheItem('foo', 'Bar', 150));

// OR save differed
$cachePool->saveDeferred(new CacheItem('baz', 'Yoo', 150));
$cachePool->commit();
```
  
See [Windwalker Cache Package](https://github.com/ventoviro/windwalker-cache)

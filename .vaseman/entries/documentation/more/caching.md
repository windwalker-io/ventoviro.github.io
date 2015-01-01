layout: documentation.twig
title: Caching

---

# Configuration

Using cache will be very easy in Windwalker, the cache configuration is located at `/etc/config.yml`.

``` yaml
cache:
    enabled: 0
    storage: file
    handler: serialize
    dir: cache
    time: 15
```

The `enabled` property controls global cache start or not, can be close if you want to test your application. `storage` controls which cache storage method
 you use, can be `file`, `runtime`, `memcached` or [more](https://github.com/ventoviro/windwalker-cache#available-storage). 
 About storage and handler, please see [Windwalker Cache](https://github.com/ventoviro/windwalker-cache).
 
# Use Cache

``` php
$cache = Ioc::getCache();

// OR

$cache = $container->get('system.cache');

// Store cache item
$cache->set('key', 'value');

// Get cache
$value = $cache->get('key');

// Check cache item
$cache->exists('key');
```

# Auto Fetch Data By Closure

Using call method to auto detect is cache exists or not. 

``` php
$data = $cache->call('flower', function()
{
    return array('sakura');
});
```

It is same as this code:

``` php
if (!$cache->exists('flower'))
{
    $cache->set('flower', array('sakura'));
}

$data = $cache->get('flower');
```

# Debug Mode or Cache Disabled

When debug property in global config se to `1` or cache disabled, the cache storage will auto set to `NullStorage`, cache can still be used
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

# Using Custom Cache Object

The previous we mentioned global cache, but we can still create our custom cache to store values, it will not affected by global config.

## Use CacheFactory

CacheFactory is a cache creator, it will store each cache object as singleton by different name. 

``` php
use Windwalker\Core\Cache\CacheFactory;

$myFileCache = CacheFactory::getCache('cache_name', 'file');
$myRuntimeCache = CacheFactory::getCache('cache_name', 'runtime');
```

Te default cache is runtime cache, it means our data only keep in once runtime but will not save as files.

## Custom Cache Options

``` php
$options = array(
    'cache_dir' => WINDWALKLER_ROOT . '/cache', // Only for file storage
    'cache_time' => 999 // minutes
);

$cache = CacheFactory::getCache('cache_name', 'file', 'serialize', $options);
```

# Full Page Cache

Sometimes we want to store whole html as static page cache. `StringHandler`  help us save raw string:
 
``` php
use Windwalker\Cache\Cache;
use Windwalker\Cache\Storage\RawFileStorage;

$cache = CacheFactory::getCache('cache_name', 'file', 'string');

$url = 'http://mysite.com/foo/bar/baz';

if ($cache->has($url))
{
    $html = $cache->get($url);
    
    exit();
}

$view = new View;

$html = $view->render();

$cache->set($url, $html);

echo $html;
```

## Supported Handlers

- SerializeHandler
- JsonHandler
- StringHandler
  
[More about Windwalker Cache](https://github.com/ventoviro/windwalker-cache)

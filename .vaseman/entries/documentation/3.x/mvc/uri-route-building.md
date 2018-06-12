---
layout: documentation.twig
title: URI and Route Building

---

## Base URI

Sometimes you may put your application at sub folder of a domain, Windwalker provides a uri data help you get base uri.

If our application located at `http://domain.com/sites/windwalker/` and we open `/flower/sakura?foo=bar` page, we may use this code to get uri data:

```php
$uri = \Windwalker\Ioc::getUriData();

// Get Base path
echo $uri->current;
echo $uri->full;
echo $uri->path;
echo $uri->root;
echo $uri->host;
echo $uri->route;
```

We will get:

```
current  ---> http://domain.com/sites/windwalker/flower/sakura
full     ---> http://domain.com/sites/windwalker/flower/sakura?foo=bar
path     ---> /sites/windwalker
root     ---> http://domain.com/sites/windwalker
host     ---> http://domain.com
route    ---> flower/sakura
```

These uri data can help us add base path to our uri, so the link will not break if
we use relative url.

Add host and base path.

```php
echo $link = $uri->root . '/romeo/and/juliet';

// OR

echo $link = $uri->root() . '/romeo/and/juliet';
```

The output will be:

```
http://domain.com/sites/windwalker/romeo/and/juliet
```

Or only add base path by `path` variable.

```php
echo $uri->path . '/flower/sakura';

// OR

echo $uri->path() . '/flower/sakura';
```

We'll get a uri start from root, so the relative path will not break.

```html
/sites/windwalker/flower/sakura
```

### Use Method Call

```php
$uri->root('flower/sakura');
$uri->path('flower/sakura');
```

### Use UriData in View

View has already included uri object as a global variable.

```html
<link href="<?php echo $uri->path; ?>/asset/css/bootstrap.min.css">

OR

<link href="<?php echo $uri->path('asset/css/bootstrap.min.css'); ?>">
```

The output will be:

```html
<link href="/sites/windwalker/media/css/bootstrap.min.css">
```

Start with `/` means this url will based on domain host.

## Build Route

Every route in Windwalker has a key, we called it **route name** or **route profile**, this name will help us quickly build route.

For example, this is the routing file:

```yaml
flower_page:
    pattern: /flower/page/(id)-(alias).html
    controller: Flower\Controller\Page
```

Then we can build this route by `CoreRouter::route()`

```php
use Windwalker\Core\CoreRouter;

echo CoreRouter::route('flower_page', ['id' => 25, 'alias' => 'foo-bar-baz']);
```

The output will be:

```html
flower/page/25-foo-bar-baz.html
```

This is a very useful function that you can change roue name but won't worry of link will be broke.

### Build Package Route

If your routes is definded in a package, you must add package alias before route name, and separate by at (`@`):

```php
use Windwalker\Core\CoreRouter;

CoreRouter::route('flower@sakuras', array('page' => $page));
```

Or use PackageRouter, we can ignore package prefix, Package will auto add it:

```php
$package = PackageHelper::getPackage('flower');

// No necessary to add 'flower:'
$package->router->route('sakuras', array('page' => $page));

// If you want to build rote in other package, you can re-add package name
$package->router->route('user@login');
```

### Encode URL in Template

If we print URL in HTML, we must encode some special chars.

```php
// In php engine
// URL: /flower/sakuras?foo=bar&baz=yoo

<?php echo $this->escape(CoreRouter::route('flower@sakuras', ['foo' => 'bar', 'baz' => 'yoo'])); ?>

// OR in Edge / Blade, the {{ ... }} will help us escape URL.

{{ CoreRouter::route('flower@sakuras', ['foo' => 'bar', 'baz' => 'yoo']) }}
```

The printed URL will be:

```
/flower/sakuras?foo=bar&amp;baz=yoo
```

### Relative or Absolute URL

Router has 3 mode, `RAW`, `PATH` or `FULL`:

```php
echo CoreRouter::route('flower@sakuras', array(), Router::TYPE_RAW);
echo CoreRouter::route('flower@sakuras', array(), Router::TYPE_PATH);
echo CoreRouter::route('flower@sakuras', array(), Router::TYPE_FULL);
```

Result:

```html
flower/sakuras
/sites/windwalker/flower/sakuras
http://domain.com/sites/windwalker/flower/sakuras
```

The RAW route used to store in database, the PATH route used to print in HTML, the FULL route used to redirect.

### Build Routes in Controller

If you are in controller, we can use PackageRouter to build route, this way is more safer because we can auto get current package routes.

```php
// Original way, we have to know package name
use Windwalker\Core\CoreRouter;

$route = CoreRouter::route('flower@sakura');

// Simpler way to let package handle it
$route = $this->package->router->route('sakura');

$this->setRedirect($route);
```

### Build Routes in View Template

View has also includes package router, just build route like this:

```html
<a href="<?php echo $router->route('sakura', ['id' => 25]); ?>">Link</a>
```

In Blade or Edge

```php
<a href="{{ $router->route('sakura', ['id' => 25]) }}">Link</a>

OR

@route('sakura', ['id' => 25]))
```

> The benefit to use `@route()` is that this directive will mute the error message, it won't throw exception if route not found,
 instead, it will echo a `javascript:alert('Route ... not found.')` in debug mode. And if in production environment,
 it will only echo a `#` to make sure system won't break.

In Twig

```php
<a href="{{ router.route('sakura', ['id' => 25]) }}">Link</a>
```

### Use Chaining Methods

After Windwalker 3.2, we provides a powerful route builder, just use chaining methods to configure your route string.

In controller or package:

```php
$this->redirect(
    $this->router->to('sakura', ['id' => 25])->path()->__toString()
);
```

In View template:

```php
{{ $router->to('sakuras')->id(25)->page(2)->full() }}
```

Available methods:

- `full()` --> Get full URL.
- `path()` --> Get path from root.
- `raw()`  --> Get relative path.
- `escape([bool])` --> Escape for HTML printing.
- `id([int])` --> Add id to route query.
- `page([int])` --> Add page to route query.
- `addVar($name, $value)` --> Add custom var to route query.
- `getVar($name, $default = null)` --> Get a custom var
- `getQueries()` --> Get all queries.
- `setQueries($queries)` --> Set queries.
- `getRoute()` --> Get route name.
- `setRoute($route)` --> set a new route name.
- `mute([bool])` --> Don't throw exception if route not found. (Useful in dev period) 

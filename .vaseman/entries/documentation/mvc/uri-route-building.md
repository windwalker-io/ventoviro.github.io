layout: documentation.twig
title: URI and Route Building

---

# Base URI

Sometimes you may put your application at sub folder of a domain, Windwalker provides a uri data help you get base uri.
 
If our application located at `http://domain.com/sites/windwalker/` and we open `/flower/sakura` page, we may use this code to get uri data:

``` php
$uri = \Windwalker\Ioc::get('uri');

// Get Base path
echo $uri->get('current');
echo $uri->get('base.path');
echo $uri->get('base.full');
echo $uri->get('base.host');
echo $uri->get('media.path');
echo $uri->get('media.full');
echo $uri->get('route');
```

We will get:

``` html
http://domain.com/sites/windwalker/flower/sakura <--- current
/sites/windwalker/                               <--- base.path
http://domain.com/sites/windwalker/              <--- base.full
http://domain.com                                <--- base.host
/sites/windwalker/media/                         <--- media.path
http://domain.com/sites/windwalker/media/        <--- media.full
flower/sakura                                    <--- route
```

These uri data can help our page links be strong whenever we put this application. Fo example, this code will build a full path of link:

``` php
echo $link = $uri->get('base.full') . 'romeo/and/juliet'; 
```

The output will be:

```
http://domain.com/sites/windwalker/romeo/and/juliet
```

## Use base.path

Use this code:

``` php
// We can also use array access
echo $uri['base.path'] . '/flower/sakura';
```

We'll get a uri start from root, so the relative path will not break.

``` html
/sites/windwalker/flower/sakura
```

## Use URI in View

View has already included uri object as a global variable.

``` html
<link href="<?php echo $data->uri['media.path']; ?>css/bootstrap.min.css">
```

The output will be:

``` html
<link href="/sites/windwalker/media/css/bootstrap.min.css">
```

# Build Route

Every route in Windwalker has a key, we called it **route name** or **route resources**, this name will help us quickly build route.

For example, this is the routing file:

``` yaml
flower_page:
    pattern: /flower/page/(id)-(alias).html
    controller: Flower\Controller\Page
```

Then we can build this route by `Router::build()`

``` php
use Windwalker\Core\Router;

// Note: don't use \Windwalker\Router\Router
echo Router::build('flower', array('id' => 25, 'alias' => 'foo-bar-baz'));
```

The output will be:

``` html
flower/page/25-foo-bar-baz.html
```

This is a very useful function that you can change roue name but won't worry of link will be broke.

## Build Package Route

If your routes is definded in a package, you must add package alias before route name, and separate by colon `:`:

``` php
use Windwalker\Core\Router;

Router::build('flower:sakuras', array('page' => $page));
```

Or use PackageRouter, we can ignore package name that Package will auto add it:

``` php
$package = PackageHelper::getPackage('flower');

// No necessary to add 'flower:'
$package->router->build('sakuras', array('page' => $page));

// If you want to build rote in other package, you may re add package name
$package->router->build('user:login');
```

## Build for Http or Html

Default `build()` method will not encode URL, so we can use this URL to redirect page, the `buildHttp()` is an alias of `build()`.

If we want to build a URL to put on HTML, we must encode it, so we have to use `buildHtml()` method.

``` php
echo Router::buildHttp('flower:sakuras', ['foo' => 'bar', 'baz' => 'yoo']);
echo Router::buildHtml('flower:sakuras', ['foo' => 'bar', 'baz' => 'yoo']);
```

Result:

``` html
flower/sakuras?foo=bar&baz=yoo
flower/sakuras?foo=bar&amp;baz=yoo
```

## Relative or Absolute URL

Router has 3 mode, `RAW`, `PATH` or `FULL`:

``` php
echo Router::build('flower:sakuras', array(), Router::TYPE_RAW);
echo Router::build('flower:sakuras', array(), Router::TYPE_PATH);
echo Router::build('flower:sakuras', array(), Router::TYPE_FULL);
```

Result:

``` html
flower/sakuras
/sites/windwalker/flower/sakuras
http://domain.com/sites/windwalker/flower/sakuras
```

The RAW route used to store in database, the PATH route used to print in HTML, the FULL route used to redirect.

## Build Route in Controller

If you are in controller, we can use PackageRouter to build route, it is more safer because we can auto get current package routes.

``` php
// Original way, we have to know package name
use Windwalker\Core\Router;

$route = Router::buildHttp('flower:sakura');

// Simpler way to let package handle it
$route = $this->package->router->buildHttp('sakura');

$this->setRedirect($route);
```

## Build Route in View Template

View has also includes package router, just build route like this:

``` html
<a href="<?php echo $data->router->buildHtml('sakura'); ?>">Link</a>
```


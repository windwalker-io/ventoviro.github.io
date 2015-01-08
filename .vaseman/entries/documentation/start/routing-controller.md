layout: documentation.twig
title: Routing And Controller

---

# Create Simple Routing

Open `/etc/routing.yml` and add a new route resource.

``` http
flower:
    pattern: /flower/sakura
    controller: Flower\Controller\Sakura
```

If you use browser open `/flower/sakura`, Windwalker will find `Flower\Controller\Sakura\GetController`  and execute it automatically.

The reason we locate `GetController` is because Windwalker routing supports RESTful protocal for that `Get` is commonly used as default http request method.

If you send `Post` request then `SaveController` will be executed instead. This is more efficient and faster to the better routing performance.

## Methods supported:

| Method    | Mapped Controller |
| --------- | --------------- |
| `GET`     | `GetController` |
| `POST`    | `SaveController` |
| `PUT`     | `SaveController` |
| `PATCH`   | `SaveController` |
| `DELETE`  | `DeleteController` |
| `HEAD`    | `HeadController` |
| `OPTIONS` | `OptionsController` |

> NOTE: Windwalker mapped POST, PUT and PATCH to `SaveController`, which includes both create and update. If you want to separate create and update to two controllers, see next section to override actions.

## Use Controller

Now we use a route like this:

``` http
flower:
    pattern: /flower/(id)
    controller: Flower\Controller\Sakura
```

Lets create a controller at path : `src/Flower/Controller/Sakura/GetController.php`:

``` php
<?php
// src/Flower/Controller/Sakura/GetController.php

namespace Flower\Controller\Sakura;

use Windwalker\Core\Controller\Controller;

class GetController extends Controller
{
	protected function doExecute()
	{
		$id = $this->input->get('id');

		return 'Flower id is: ' . $id;
	}
}
```

Windwalker Controller follows single action pattern (similar to Joomla New MVC), every controller has only one action (`execute()`). This way, we keep controller itself as light as possible than other frameworks. You can add more logic to a controller but won't be confused by many actions in one class.

You don't have to include above files because Windwalker use autoloading, which follows [PSR-4](http://www.php-fig.org/psr/psr-4/) standard. Go to `composer.json` you can see the autoloading option is set to `"":"/src"`, so any file under `/src` folder hierarchy will be loaded automatically.

After controller created, go to `/flower/25` at your host. You will see:

``` html
Flower id is: 25
```

Congratulations! Your first page is finished.

## Override Actions

Add the action attribute:

``` http
flower:
    pattern: /flower/sakura
    controller: Flower\Controller\Sakura
    action:
        get: IndexController
```

The GET method will match `Flower\Controller\Sakura\IndexController` because we set a map to find new name. We can set more 
methods to mapping methods with controllers.

``` http
flower:
    pattern: /flower/sakura
    controller: Flower\Controller\Sakura
    action:
        get: IndexController
        post: CreateController
        put: UpdateController
        delete: DeleteController
```

Or use wildcards to map all methods to one controller:

``` http
    action:
        '*': SakuraController
```

## Override Methods

If you want to send `PUT` and `DELETE` method from web form, you may add `_method` params in HTTP query, this param will override 
real HTTP method. For example: `&_method=DELETE` will raise `DeleteController`. 

## Limit By Methods

The HTTP request will be ingnored according if it did not satisfy the fiven conditions. For example this config will only allow GET and POST, while PUT and DELETE will be ignored.

``` http
flower:
    pattern: /flower/sakura
    controller: Flower\Controller\Sakura
    method:
        - GET
        - POST
```

## Limit By schema

``` apache
flower:
    pattern: /flower/sakura
    controller: Flower\Controller\Sakura
    method:
        - GET
        - POST
    # Only http & https
    scheme: https
    post: 80
    sslPort: 443
```

# Route Pattern

## Simple Params

Use parenthesis `()` to wrap param name.

``` http
    pattern: /flower/(id)/(alias)
```

For uri look like : `/flower/25/article-alias-name`, above pattern will be matched and there will be two input params.

``` html
[id] => 25
[alias] => article-alias-name
```

### Custom Input Variables

``` http
    pattern: /flower/(id)/(alias)
    variables:
        foo: bar
```

The attributes in `variables` will auto set to input if this route be matched.

### Limit By Requirement

Use Regular Expression to validate type of input. For example `\d+` indicates that only `Integer` will be accepted as `id` input.

``` http
    pattern: /flower/(id)/(alias)
    requirements:
        id: \d+
```

## Optional Params

### Single Optional Params

Use `(/{anyparam})` to wrap an Optional Param.

``` http
    pattern: flower(/id)
```

Below 2 uris will be matched simultaneously.

```
/flower
/flower/25
```

### Multiple Optional Params

``` http
    pattern: flower(/year,month,day)
```

All uris below will be matched.

```
/flower
/flower/2014
/flower/2014/10
/flower/2014/10/12
```

Matched variables:

```
Array
(
    [year] => 2014
    [month] => 10
    [day] => 12
)
```

## Wildcards

Use Wildcards to match all the successive params in uri.

``` http
    pattern: /king/(*tags)
```

Every param after `/king` will all be matched. For example: `/king/john/troilus/and/cressida`, will get these variables.

```
Array
(
    [tags] => Array
    (
        [0] => john
        [1] => troilus
        [2] => and
        [3] => cressida
    )
)
```

# Build Route

Every route in Windwalker has a key, which allows every single route patter can be access by **route name** or **route resources**, this will be helpful building a route quickly.

``` php
use Windwalker\Core\Router;

echo Router::build('{route name}', array('id' => 25, 'alias' => 'foo-bar-baz'));
```

The output will be:

``` html
{route name}/25/foo-bar-baz
```

This is a very useful function that you can change roue name but don't need to worry about invalid link.

For further information, see: [Route and Redirect](../mvc/route-redirect.html)

# Matchers

Windwalker Router provides some matchers to use different way to match routes.

You can set matcher name in `/etc/config.yml`:

``` http
routing:
    matcher: default
```

The default matcher is Sequential Matcher.

## Sequential Matcher

Sequential Matcher use the [Sequential Search Method](http://en.wikipedia.org/wiki/Linear_search) to find route.
It is the slowest matcher but much more customizable. It is the default matcher of Windwalker Router.

## Binary Matcher

Binary Matcher use the [Binary Search Algorithm](http://en.wikipedia.org/wiki/Binary_search_algorithm) to find route.
This matcher is faster than Sequential Matcher but it will break the ordering of your routes. Binary search will re-sort all routes by pattern characters.

## Trie Matcher

Trie Matcher use the [Trie](http://en.wikipedia.org/wiki/Trie) tree to search route.
This matcher is the fastest method of Windwalker Router, but the limit is that it need to use an simpler route pattern
which is not as flexible as the other two matchers.

## Rules of TrieMatcher

### Simple Params

only match when the uri segments all exists. If you want to use optional segments, you must add two or more patterns.

```  html
/flower
/flower/:id
/flower/:id/:alias
```

### Wildcards

This pattern will convert all segments after `/flower` to an array which named `tags`:

``` html
/flower/*tags
```

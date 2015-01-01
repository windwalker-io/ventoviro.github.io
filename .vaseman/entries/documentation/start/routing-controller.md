layout: documentation.twig
title: Routing And Controller

---

# Create Simple Routing

Open `/etc/routing.yml` and add a new route resource.

``` http
flower:
    pattern: /flower/sakura
    controller: Flower\Controlelr\Sakura
```

If you use browser open `/flower/sakura`, Windwalker will call `Flower\Controller\Sakura\GetController` and execute it.

Why is `GetController`? Windwalker routing follows RESTful pattern to find action, and default method of browser is `GET`,
 so `GetController` will be matched. You can send `POST` request to `/flower/sakura`, the `SaveController` will be executed.
 
This way will be more efficiently because we can reduce routes number to make routing more faster.

## This is supported methods:

| Method    | Mapped Controller |
| --------- | --------------- |
| `GET`     | `GetController` |
| `POST`    | `SaveController` |
| `PUT`     | `SaveController` |
| `PATCH`   | `SaveController` |
| `DELETE`  | `DeleteController` |
| `HEAD`    | `HeadController` |
| `OPTIONS` | `OptionsController` |

> NOTE: Windwalker mapped POST, PUT and PATCH to `SaveController`, this controller will handle both create and update. 
If you want to separate create and update to two controllers, see next section to override actions.

## Use Controller

Now we use a route like this:

``` http
flower:
    pattern: /flower/(id)
    controller: Flower\Controlelr\Sakura
```

Lets create a controller at `src/Flower/Controller/Sakura/GetController.php`:

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

Windwalker Controller follows single action pattern (similar to Joomla New MVC), every controller has only one action (`execute()`).
The benefit is that our controllers will be more lighter then other frameworks. You can add more logic to a controller but won't be
confused by many actions in one class.

Why we don't need to include this file? Windwalker use autoloading to find classes. The `/src` folder use [PSR](http://www.php-fig.org/psr/psr-4/)
rule to match class namespace with folder structure, it because we have already set `"": "src/"` in `composer.json`.

After controller created, type `/flower/25` in browser. You will see:

``` html
Flower id is: 25
```

Congrats, your first page has show.

## Override Actions

Add the action attribute:

``` http
flower:
    pattern: /flower/sakura
    controller: Flower\Controlelr\Sakura
    action:
        get: IndexController
```

The GET method will match `Flower\Controlelr\Sakura\IndexController` because we set a map to find new name. We can set more 
methods to mapping methods with controllers.

``` http
flower:
    pattern: /flower/sakura
    controller: Flower\Controlelr\Sakura
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

## Limit By Methods

We can limit our route by some options, if the HTTP request not match this rule, this route will be ignored,
for example this config will only allow GET and POST, the PUT and DELETE will not matched.

``` http
flower:
    pattern: /flower/sakura
    controller: Flower\Controlelr\Sakura
    method:
        - GET
        - POST
```

## Limit By Other Options

``` apache
flower:
    pattern: /flower/sakura
    controller: Flower\Controlelr\Sakura
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

Use `()` to wrap param name.

``` http
    pattern: /flower/(id)/(alias)
```

If uri is `/flower/25/article-alias-name`, this pattern will be matched, and there will be two variables in input:

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

Use `\d+` to limit that id need to be integer. If uri is `/flower/foo/sakura`, this route will not be matched.

``` http
    pattern: /flower/(id)/(alias)
    requirements:
        id: \d+
```

## Optional Params

### Single Optional Params

Use `(/...)` to wrap param name, this param will be optional param.

``` http
    pattern: flower(/id)
```

These 2 uri will be matched.

```
/flower
/flower/25
```

### Multiple Optional Params

``` http
    pattern: flower(/year,month,day)
```

These uri will be matched.

```
/flower
/flower/2014
/flower/2014/10
/flower/2014/10/12
```

And the matched variables will be

```
Array
(
    [year] => 2014
    [month] => 10
    [day] => 12
)
```

## Wildcards

Use Wildcards to match all params follows our route.

``` http
    pattern: /king/(*tags)
```

The routes start by `/king` will all be matched. For example: `/king/john/troilus/and/cressida`, will get these variables.

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
This matcher is faster than SequentialMatcher but it will break the ordering of your routes. Binary search will re-sort all routes by pattern characters.

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

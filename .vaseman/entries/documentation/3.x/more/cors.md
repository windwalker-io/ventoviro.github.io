---
layout: documentation.twig
title: CORS Headers

---

## Add CORS Headers

If you want to build a remote API server, you must handle [CORS](https://developer.mozilla.org/en-US/docs/Web/HTTP/Access_control_CORS)
request to make sure remote browser can access your server.

Windwalker has a `CorsHandler` to quickly add CORS headers to `Response`. Fo example, you can do this in controller:

```php
// In Controller

use Windwalker\Core\Http\CorsHandler;

// Response object is immutable
// Make sure you return response to replace old one
$this->response = CorsHandler::create($this->response)
    ->allowOrigin('*')
    ->allowCredentials(true)
    ->allowMethods(['GET', 'POST'])
    ->allowHeaders(['X-Foo', 'X-bar'])
    ->getResponse();
```

Now Windwalker will auto genarate these headers:

```http
Access-Control-Allow-Credentials: true
Access-Control-Allow-Headers: X-Foo, X-Bar
Access-Control-Allow-Methods: GET, POST
Access-Control-Allow-Origin: *
```

## Dynamically Return Allow Origins

Use wildcard (`*`) to Allow-Origin is not recommended, you can save domains which you allowed in system and add them
 to header dynamically.

```php
$allowOrigins = $repo->getMyAllowOrigins();

$origin = $this->input->server->getUrl('HTTP_ORIGIN');

if (in_array($origin, $allowOrigins)) {
    $this->response = CorsHandler::create($this->response)
        ->allowOrigin($origin)
        ->getResponse();
}
```

You can also add multiple origins:

```php
$this->response = CorsHandler::create($this->response)
    ->allowOrigin($origin1)
    ->allowOrigin($origin2)
    ->allowOrigin($origin3)
    ->getResponse();
```

The headers will be:

```http
Access-Control-Allow-Origin: http://domain1.com
Access-Control-Allow-Origin: http://domain2.com
Access-Control-Allow-Origin: http://domain3.com
```

To replace all origins, set `true` to second argument.

```php
$this->response = CorsHandler::create($this->response)
    ->allowOrigin($newOrigin, true)
    ->getResponse();
```

## Use CoreTrait

Use CorsTrait on controller to add some helper methods:

```php
class GetController extends AbstractController
{
    ue JsonApiTrait, CorsTrait;

    public function preparExecute()
    {
        $this->>allowOrigin('*')
            ->maxAge(...)
            ->allowHeaders([...]);
    }
}
```

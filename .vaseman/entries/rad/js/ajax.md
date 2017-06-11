---

layout: rad.twig
title: Ajax

---

## Phoenix.Ajax

`Phoenix.Ajax` object is a `jQuery` ajax wrapper, to include it, use:

``` php
PhoenixScript::ajax();
```

The CSRF token will auto injected to this object, just use it as a http client.

```js
Phoenix.Ajax.get('flower/sakura/1')
    .done(function (response, status, jqHXR) {
        // ...
    }).fail(function (jqHXR, status, error) {
        // ...
    }).always(function () {
        // ...
    });
```

Use other methods:

```js
Phoenix.Ajax.get('flower/sakura/1', data, headers).done(...).fail(...);
Phoenix.Ajax.post('flower/sakura', data, headers).done(...).fail(...);
Phoenix.Ajax.put('flower/sakura/1', data, headers).done(...).fail(...);
Phoenix.Ajax.patch('flower/sakura/1', data, headers).done(...).fail(...);
Phoenix.Ajax.delete('flower/sakura/1', data, headers).done(...).fail(...);
Phoenix.Ajax.head('flower/sakura/1', data, headers).done(...).fail(...);
Phoenix.Ajax.options('flower/sakura/1', data, headers).done(...).fail(...);
```

Add jQuery ajax options to forth argument:

```js
Phoenix.Ajax.post('flower/sakura', data, headers, {dataType: 'xml'})
    .done(...)
    .fail(...);
```

Use `request()`:

```js
Phoenix.Ajax.request('POST', 'flower/sakura', data, headers, options)
    .done(...)
    .fail(...);
```

### Override Methods

To override HTTP method with `X-HTTP-Method-Override` or `_method` parameter, use `.customMethod()` chain.

```js
Phoenix.Ajax.customMethod()
    .put('flower/sakura/1')
    .done(...)
    .fail(...);
```

### Set Custom Headers

```js
Phoenix.Ajax.headers.POST['X-Foo'] = 'Bar';
```

This header will always send with every request.

## Integrate JsonApiTrait

Add `JsonApiTrait` to controller:

``` php
class GetController extends AbstractController
{
    use JsonApiTrait;

    // ...

    public function doExecute()
    {
        $this->addMessage('Hello');

        return ['foo' => 'bar'];
    }
}
```

Now your JSON return will format with this:

```json
{success:true,code:200,message:"Hello",data:{foo:"bar"}}
```

If you throw exceptions in controller:

``` php
class GetController extends AbstractController
{
    use JsonApiTrait;

    // ...

    public function doExecute()
    {
        throw new \RuntimeException('Something error', 403);

        return ['foo' => 'bar'];
    }
}
```

The return JSON will be:

```json
{"success":false,"code":403,"message":"Something error","data":{"backtrace":...}}
```

So you can check API status in JS:

```js
Phoenix.Ajax.get('flower/sakura/1')
    .done(function (response, status, jqHXR) {
        if (response.success) {
            console.log(response.data);
        } else {
            throw new Error(response.message);
        }
    });
```

## Use Vue Resource

See [Vue Resource](./vue.html#vue-resource)

## Integrate with Router

See [Phoenix.Router](./core.html#routes)


## Add CSRF Token

If you want to protect your application in ajax call, you can use this method to add form token.

``` php
\Phoenix\Script\CoreScript::csrfToken();
```

This method will add a meta tag to HTML `<nead>`

``` html
<meta name="csrf-token" content="a7d71a2c21743d8865fdfa61b71b29e8" />
```

Now you can fetch this token by JS, for example, we can add a param to jQuery ajaxSetup:

```js
jQuery.ajaxSetup({
    headers: {
        'X-Csrf-Token': jQuery('meta[name="csrf-token"]').attr('content')
    }
});
```

## Auto add ajaxSetup

Use this code to auto add ajaxSetup:

``` php
JQueryScript::csrfToken();
```

---

layout: rad.twig
title: Phoenix Form

---

## Phoenix Form

PhoenixForm is a HTML form operation helper, include it by:

``` php
\Phoenix\Script\PhoenixScript::form('#my-form');
```

And you must have a `<form id="#admin-form"> ...` element in your HTML so Phoenix can operate this form.

## Submit and RESTful CRUD

You can simply use this JS code to submit form:

```js
// Submit with default action defined on <form action="...">
Phoenix.submit();

// Submit with custom URI and query
Phoenix.submit('/flower/sakuras', {page: 3});

// Send POST
Phoenix.submit('/flower/sakura', data, 'POST');

// Send custom RESTful method, will add `_method` to your params
Phoenix.submit('/flower/sakura', data, 'POST', 'PUT');
```

In PHP template, it wil look like:

```html
Phoenix.submit('{{ $router->route('sakura') }}', {page: 3});
``` 

Use simple methods:

```js
// GET
Phoenix.get('/flower/sakura/25', query);

// POST
Phoenix.post('/flower/sakura/25', query);

// POST with custom method
Phoenix.post('/flower/sakura/25', query, 'PUT');

// PUT
Phoenix.put('/flower/sakura/25', query);

// PATCH
Phoenix.patch('/flower/sakura/25', query);

// DELETE
Phoenix.sendDelete('/flower/sakura/25', query);
```

On click event in HTML element:

``` html
<button type="button" onclick="Phoenix.post()"></button>
```

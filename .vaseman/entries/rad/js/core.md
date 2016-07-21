---

layout: rad.twig
title: Phoenix Core Script

---

## Introduction

Phoenix provides a set of useful scripts to help us operate form and input, these scripts based on jQuery so every developers
can easily learn how to use and integrate it with their own scripts.

## Phoenix Core

Phoenix core script is a HTML form operation helper, it will auto included if you generate a phoenix admin template,
but you can also include it manually in everywhere with first argument as css selector.

``` php
// In View class
\Phoenix\Script\PhoenixScript::core();

// Custom selector
\Phoenix\Script\PhoenixScript::core('#my-form');

// Custom selector and use other variable name.
\Phoenix\Script\PhoenixScript::core('#my-form', 'MyPhoenix');

// In Template
<?php \Phoenix\Script\PhoenixScript::core(); ?>

OR

@php(\Phoenix\Script\PhoenixScript::core())
```

The `jquery.js` and `phoenix.js` will be auto included to HTML head.

And you must have a `<form id="#admin-form"> ...` element in your HTML so Phoenix can operate this form.

## Submit and RESTful CRUD

You can simply use this JS code to submit form:

``` js
// Submit with default action defined on <form action="...">
Phoenix.submit();

// Submit with custom URI and query
Phoenix.submit('/flower/sakuras', {page: 3});

// Send POST
Phoenix.submit('/flower/sakura', data, 'POST');

// Send custom RESTful method, will add `_method` to your params
Phoenix.submit('/flower/sakura', data, 'POST', 'PUT');
```

Use simple methods:

``` js
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

## Routes

Use `Router` to print route to HTML.

``` php
<button type="button" onclick="Phoenix.post('{{ $router->route('sakura', ['id' => 25]) }}')"></button>
```

The output

``` html
<button type="button" onclick="Phoenix.post('/flower/sakura/25')"></button>
```

### Use Phoenix JS Router:

Add route settings in PHP

``` php
\Phoenix\Script\PhoenixScript::addRoute('sakura_save', $router->route('sakura', ['id' => 25]));
```

And get this route by `Phoenix.Router`:

``` html
<button type="button" onclick="Phoenix.post(Phoenix.Router.route('sakura_save'))"></button>
```

Get route in anywhere, for example, use it in ajax call:

``` js
$.ajax({
    url: Phoenix.Router.route('sakura_save'),
    data: data,

    // ...
}).done(...)
```

## Translate

If you has a language key:

``` ini
flower.message.sakura="Sakura"
```

Add language key to JS by php:

``` php
\Phoenix\Script\PhoenixScript::translate('flower.message.sakura');
```

Now you can get this language string in JS:

``` js
Phoenix.Translator.translate('flower.message.sakura'); // Sakura

// You can also use sprintf() and plural()
Phoenix.Translator.sprintf('flower.message.sakura', 'arg1', 'arg2');
Phoenix.Translator.plural('flower.message.sakura', 3);
```

See [Translator](../../documentation/3.x/services/languages.html)

## Messages

Use JS code `addMessage()` to render message to template:

``` js
Phoenix.addMessage('Hello World');
```

![p-2016-07-21-001](https://cloud.githubusercontent.com/assets/1639206/17009420/f958386c-4f2c-11e6-85d4-ae41c3bf73c9.jpg)

Use other styles

``` js
Phoenix.addMessage('Hello World', 'info');
Phoenix.addMessage('Hello World', 'success');
Phoenix.addMessage('Hello World', 'warning');
Phoenix.addMessage('Hello World', 'danger');
```

Multiple messages:

``` js
Phoenix.addMessage(['Foo', 'Bar'], 'info');
```

Remove messages:

``` js
Phoenix.removeMessages();
```

### Keep Alive

If you are writing a form with long textarea, you will hope the session do not expired, use `keepAlive()` in php.

``` php
PhoenixScript::keepAlive();
```

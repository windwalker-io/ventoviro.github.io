---

layout: rad.twig
title: Phoenix Core Script

---

## Introduction

Phoenix provides a set of useful scripts to help us operate form and input, these scripts based on jQuery so every developers
can easily learn how to use and integrate it with their own scripts.

## Phoenix Core

Phoenix core script will auto included if you generate a phoenix admin template,
but you can also include it manually in everywhere with first argument as css selector.

```php
// In View class
\Phoenix\Script\PhoenixScript::phoenix();

// In Template
<?php \Phoenix\Script\PhoenixScript::phoenix(); ?>

OR

@php(\Phoenix\Script\PhoenixScript::phoenix())
```

The `jquery.js` and `phoenix.js` will be auto included to HTML head.


## Uri

Use `uri()` to get uri data, same as php `$uri->path` in template.

```js
Phoenix.uri('path');
Phoenix.uri('root');
Phoenix.uri('full');
```

Use `asset()` to get asset path:

```js
Phoenix.asset('path');
Phoenix.asset('root');
```

## Variable Storage

Phoenix can store some variables from PHP and push to frontend. Use this code in PHP:

```php
PhoenixScript::data('item', $data);
PhoenixScript::data('title', 'Hello');
```

In frontend, use this JS to get value:

```php
// Get object
var item = Phoenix.data('item');

var id = item.id;

// Get string
var title = Phoenix.data('title').toUpperCase();
```

It is useful if you are using Vue.js and you want to push a lot of data structure to vue instance:

```js
new Vue({
    el: '#app',
    data: {
        item: Phoenix.data('item')
    }
});
```

## Translate

If you has a language key:

```ini
flower.message.sakura="Sakura"
```

Add language key to JS by php:

``` php
\Phoenix\Script\PhoenixScript::translate('flower.message.sakura');
```

Now you can get this language string in JS:

```js
Phoenix.__('flower.message.sakura'); // Sakura

// You can also use sprintf() and plural()
Phoenix.__('flower.message.sakura', 'arg1', 'arg2');
Phoenix.Translator.plural('flower.message.sakura', 3);
```

See [Translator](../../documentation/3.x/services/languages.html)

## Messages

Use JS code `addMessage()` to render message to template:

```js
Phoenix.addMessage('Hello World');
```

![p-2016-07-21-001](https://cloud.githubusercontent.com/assets/1639206/17009420/f958386c-4f2c-11e6-85d4-ae41c3bf73c9.jpg)

Use other styles

```js
Phoenix.addMessage('Hello World', 'info');
Phoenix.addMessage('Hello World', 'success');
Phoenix.addMessage('Hello World', 'warning');
Phoenix.addMessage('Hello World', 'danger');
```

Multiple messages:

```js
Phoenix.addMessage(['Foo', 'Bar'], 'info');
```

Remove messages:

```js
Phoenix.removeMessages();
```

## Events

Phoenix has it's own event system and very easy to use:

### Listen A Custom Event

```js
Phoenix.on('my.event', function () {
    // ...
});
```

Only listen once:

```js
Phoenix.once('my.event', function () {
    // ...
});
```

### Trigger Event

```js
Phoenix.trigger('my.event');
```

Trigger with params:

```js
Phoenix.trigger('my.event', 'Flower', 'Sakura');
```

Listen with params:

```js
Phoenix.on('my.event', function (arg1, arg2) {
    // ...
});
```

### Stop Listening

```js
Phoenix.off('my.event');
```

### Get Listeners

```js
var listeners = Phoenix.listeners('my.events');
```

## Plugin Creator

Phoenix help us create jQuery plugin quickly.

```js
Phoenix.plugin('flower', class Flower {
  constructor(arg1, arg2) {
    // ...
  }
});

// Use this plugin
var instance = $('.hello').flower('arg1', 'arg2');

// Call again will get same instance.
var instance2 = $('.hello').flower('arg1', 'arg2');

instance === instance2;
```

## Loaded Event

Phoenix provides a `loaded` event to make sure your script can run after page and something initialised.

```js
// By default, loaded is same as dom ready event
Phoenix.on('loaded', function () {
    // ...
});
```

But you can add `wait()` to make phoenix loaded deferred. For example, this code make phoenix wait web components ready.

```js
Phoenix.wait(function (resolve) {
    window.addEventListener('WebComponentsReady', function() {
        // Call resolve() to complete loaded
        resolve();
    });
});
```

All `wait()` callback will push to a queue, if queue all completed, Phoenix will trigger `loaded` event. 

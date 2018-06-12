---

layout: rad.twig
title: Router

---

Use `\Phoenix\Script\PhoenixScript::phoenix();` load phoenix js and also includes router js.

## Use PHP Print Routes

Use `Router` to print route to HTML.

``` php
<button type="button" onclick="Phoenix.post('{{ $router->route('sakura', ['id' => 25]) }}')"></button>
```

The output

``` html
<button type="button" onclick="Phoenix.post('/flower/sakura/25')"></button>
```

## Use Phoenix JS Router

Add route settings in PHP

``` php
\Phoenix\Script\PhoenixScript::addRoute('sakura_save', $router->route('sakura', ['id' => 25]));
```

And get this route by `Phoenix.Router`:

``` html
<button type="button" onclick="Phoenix.post(Phoenix.route('sakura_save'))"></button>
```

Get route in anywhere, for example, use it in ajax call:

```js
$.ajax({
    url: Phoenix.route('sakura_save'),
    data: data,

    // ...
}).done(function () {
    // ...
})
```

## Add Query

Use second argument as URL query.

```js
Phoenix.route('sakura_save', {id: 123}); // /sakura/save?id=1
```

This will be useful if you write an AJAX updater:

```js
function updateItem(id, data) {
  $.post(
    Phoenix.route('sakura', {id: id}), 
    data
  );
}
```

## State Control

Phoenix Router can help you control browser history state.

Push a new history:

```js
Phoenix.Router.push({
  uri: '/article/view',
  title: 'Article Title',
  state: {...}
});
```

Replace a history:

```js
Phoenix.Router.replace({
  uri: '/article/view',
  title: 'Article Title',
  state: {...}
});
```

Get current history state:

```js
var state = Phoenix.Router.state();
```

Other methods as window.history proxies:

```js
Phoenix.Router.back();
Phoenix.Router.forward();
Phoenix.Router.go(-1);
```

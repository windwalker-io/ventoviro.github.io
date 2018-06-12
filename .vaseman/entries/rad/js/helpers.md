---

layout: rad.twig
title: Helpers

---

Use `\Phoenix\Script\PhoenixScript::phoenix();` load phoenix js and also includes helpers.

### Keep Alive

If you are writing a form with long textarea, you will hope the session do not expired, use `keepAlive()` in php.

``` php
PhoenixScript::keepAlive();
```

## Load Script

Load JS dynamically.

```js
Phoenix.loadScript('js/foo.js');
``` 

Load multiple files and do something after all loaded:

```js
Phoenix.loadScript(['js/foo.js', 'js/bar.js'])
  .done(function () { // or then()
      // ...
  });
```

Auto detect min file like `AssetManager`

```js
// Load foo.min.js without DEBUG mode
// Load foo.js in DEBUG mode
Phoenix.loadScript('js/foo.min.js');
```

JS cannot check file exists, if you have only compressed or un-compress file, add second argument to FALSE so Phoenix 
will not auto convert file name.

```js
Phoenix.loadScript('js/foo.min.js', false); // Always load js/foo.min.js
```

## sprintf() and vsprintf()

Phoenix fork sprintf() in it-self to reduce request times.

```js
Phoenix.sprintf('Hello: %s', 'Simon');
Phoenix.vprintf('Hello: %s', ['Simon']);
``` 


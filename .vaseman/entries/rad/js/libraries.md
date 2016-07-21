---

layout: rad.twig
title: Base Libraries

---

## RequireJS

Include `require.js`

``` php
\Phoenix\Script\CoreScript::requireJS();
```

Now yo can load your script by commonJS api:

``` js
var foo = require('foo.js');
```

See [RequireJS](http://requirejs.org/)

## Underscore

Phoenix provides `underscore.js` as a base JS library to help us do more things which native JS cannot do.

``` php
\Phoenix\Script\CoreScript::underscore();
```

Phoenix enabled no conflict mode as default, so you must use `underscore` instead `_`:

``` js
underscore.isNumber(234);
```

### Template

Phoenix override underscore template syntax to same as Blade and Edge, so we can use double braces to render variables:

``` js
underscore.template("hello: {{ name }}").compiled({name: 'World'});
```

To ese template in Blade and Edge, you must add `@` before braces to escape the echo tag:

``` html
<script>
underscore.template("hello: @{{ name }}").compiled({name: 'World'});
</scrip>
```

See [Underscore.js](http://underscorejs.org/)

## Underscore String

Support more string operations.

``` php
\Phoenix\Script\CoreScript::underscoreString();
```

See [Underscore.string.js](http://gabceb.github.io/underscore.string.site/)

## Backbone

Phoenix provides `backbone.js` if you want to build a SPA.

``` php
\Phoenix\Script\CoreScript::backbone();
```

See [Backbone.js](http://backbonejs.org/)

## Simple Uri

Simple Uri is a JS URI object to help us easily handle uri operations in browser environment:

``` php
\Phoenix\Script\CoreScript::simpleUri();
```

See [SimpleUri.js](http://about.asika.tw/simple-uri.js/)

## Add CSRF Token

Add CSRF token to `<head>` as a meta tag

``` php
\Phoenix\Script\CoreScript::csrfToken();
```

``` html
<meta name="csrf-token" content="a7d71a2c21743d8865fdfa61b71b29e8" />
```

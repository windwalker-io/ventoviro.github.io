---

layout: rad.twig
title: CSRF for Ajax

---

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

``` js
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

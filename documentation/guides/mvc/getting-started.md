---
layout: doc
---

# Getting Started

## Start a New MVC

Run

```shell
php windwalker g view Front/Sakura
```

This will create a `src/Front/Sakura/SakuraView` file and a series of files.

Let's write something into the `prepare()` function.

```php 
<?php
// src/Front/Sakura/SakuraView.php

// ...

class SakuraView implements ViewModelInterface
{
    // ...
    
    public function prepare(AppContext $app, View $view): array
    {
        $title = 'Sakura View Title'; // [!code ++]
        
        return []; // [!code --]
        return compact('title'); // [!code ++]
    }
}

```

Then print out the variable in the template file.

```blade
<?php // src/Module/Front/Sakura/views/sakura.blade.php ?>

@extends('global.body')

@section('content')
    <h2>Sakura view</h2> // [!code --]
    <div class="container"> // [!code ++]
        <h2>{{ $title }}</h2> // [!code ++]
    </div> // [!code ++]
@stop

```

Next, run the following command to create routing.

```shell
php windwalker g route front/sakura
```

Edit the file to add the route settings.

```php 
<?php
// routes/front/sakura.route.php

use App\Module\Front\Sakura\SakuraView; // [!code focus]

// ...

$router->group('sakura')
    ->register(function (RouteCreator $router) {
        // Add this // [!code focus]
        $router->any('sakura', '/sakura')  // [!code focus]
            ->view(SakuraView::class);  // [!code focus]
    });
```

Next, open the following URL in the browser to see the view we create.

``` 
http://localhost:8000/sakura
```

![sakura title](https://github.com/lyrasoft/ww4tut/assets/1639206/b6fef366-9f95-4144-a645-bba6881d0e13)

## Modify CSS Styles

Next, let's try modifying the assets/_sakura.scss in sakura. Add:

```scss
// src/Module/Front/Sakura/assets/_sakura.scss

// Limit to this view only
.view-sakura {
  h2 {
    color: red;
  }
}

```

Next, run

``` 
yarn build css
```

After completion, you will see the style take effect.

![red title](https://github.com/lyrasoft/ww4tut/assets/1639206/93d0d2f7-82f9-449c-8b8a-af398761bef9)

In Windwalker 4, CSS is distributed in different MVC folders and will be integrated together during fusion compile.

## Modify JS

Similar to CSS, JS is also distributed in the MVC folders corresponding to the view name. Let's modify `sakura.ts`.

```ts
// src/Module/Front/Sakura/assets/sakura.ts

console.log('Hello Sakura');

```

Then compile with fusion.

``` 
yarn build js
```

Refresh the page and open the browser console to see the result.

![console](https://github.com/lyrasoft/ww4tut/assets/1639206/bd458e36-1560-4e4d-a513-966a7053f0d2)

## Page Title

Simply add this line to add title to browser:

```php
    // ...

    public function prepare(AppContext $app, View $view): array
    {
        $title = 'Sakura View Title';

        $view->setTitle($title); // [!code ++]

        return compact('title');
    }
```

![title](https://github.com/windwalker-io/framework/assets/1639206/92ca07b3-88ce-4c47-97a5-2c34c3858df8)

### Use `#[ViewMetadata]` Attribute

If you want to set more metadata, try use `#[ViewMetadata]` with another method. You amy inject any service or view data into this method through declaring the arguments.

The `HtmlFrame` is a useful object that help you configure HTML header and body. See [HtmlFrame Service](../../framework/html-frame.md)

```php
use Windwalker\Core\Attributes\ViewMetadata; // [!code ++]
use Windwalker\Core\Html\HtmlFrame; // [!code ++]

    // ...

    public function prepare(AppContext $app, View $view): array
    {
        $title = 'Sakura View Title';

        $view->setTitle($title); // [!code --]
        // Inject title to view  // [!code ++]
        $view['title'] = $title; // [!code ++]

        return compact('title');
    }

    #[ViewMetadata] // [!code ++]
    public function prepareMetadata(HtmlFrame $htmlFrame, string $title): void // [!code ++]
    { // [!code ++]
        $htmlFrame->setTitle($title); // [!code ++]
        $htmlFrame->setDescription('Sakura Description'); // [!code ++]
    } // [!code ++]
```

## Debug Mode

If you need to enter debug mode, such as viewing DB Queries, complete error messages, or want to prevent CSS/JS caching, you can enter debug mode.

The simplest way is to add /dev.php at the end of the website URL.

For example: 
- The URL: http://localhost/hello/www/sakura/list 
- Change to: http://localhost/hello/www/dev.php/sakura/list.

In this case, we are using PHP server: `http://localhost:8000`, we can simply add `dev.php` after ths host: `http://localhost:8000/dev.php`

This will enter debug mode.

![debug console](https://github.com/lyrasoft/ww4tut/assets/1639206/13a93c09-eba2-4d65-80c1-944ccdcc6bf7)

When an error occurs, it can also display complete error messages.

![error page](https://github.com/lyrasoft/ww4tut/assets/1639206/3e89efcb-a6b1-4594-85b6-4107632d00ee)

More details please see [Debugging](../start/debugging.html)

---
layout: doc
---

# Getting Started

## Start a New MVC

Run

``` 
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
        // Add these lines
        $title = 'Sakura View';
        
        return compact('title');
    }
}

```

Then print out the variable in the template file.

```blade
<?php // src/Module/Front/Sakura/views/sakura.blade.php ?>

@extends('global.body')

@section('content')
    <div class="container">
        <h2>{{ $title }}</h2>
    </div>
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

use App\Module\Front\Sakura\SakuraView;

// ...

$router->group('sakura')
    ->register(function (RouteCreator $router) {
        // Add this
        $router->any('sakura', '/sakura')
            ->view(SakuraView::class);
            
    });
```

Next, open the following URL in the browser to see the view we create.

``` 
http://{site}/www/sakura
```

{image}

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

{image}

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

{image}

## Debug Mode

If you need to enter debug mode, such as viewing DB Queries, complete error messages, or want to prevent CSS/JS caching, you can enter debug mode.

The simplest way is to add /dev.php at the end of the website URL.

For example: 
- The URL: http://localhost/hello/www/sakura/list 
- Change to: http://localhost/hello/www/dev.php/sakura/list.

This will enter debug mode.

```adf 
{"type":"mediaSingle","attrs":{"layout":"center"},"content":[{"type":"media","attrs":{"width":1497,"id":"a82763dc-4800-48b0-a3f6-0728099aff46","collection":"contentId-1455685784","type":"file","height":768}}]}
```

When an error occurs, it can also display complete error messages.

```adf 
{"type":"mediaSingle","attrs":{"layout":"center"},"content":[{"type":"media","attrs":{"width":1444,"id":"0195949a-bb89-464a-a05e-4335e51479cc","collection":"contentId-1455685784","type":"file","height":739}}]}
```



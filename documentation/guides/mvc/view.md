---
layout: doc
---

# View

Next, we will create a View to fetch data from the database.

## Create View Model for List

Run:

```shell
php windwalker g view Front/Article/ArticleListView
```

This will create the `ArticleListView` object.

> [!info]
> If you enter the short name `Front/Article`, it will generate `Front\Article\ArticleView`.
> 
> And if you enter the full name `Front/Article/ArticleListView`, it will generate `Front\Article\ArticleListView`.
> 
> Since we want multiple Views to be managed in the same folder, we enter the full name here.

We will use the ORM object's query builder to fetch articles. Every view object is a [ViewModel](https://en.wikipedia.org/wiki/Model%E2%80%93view%E2%80%93viewmodel), that you can load or fetch data in it, and return as view data.

```php
<?php // src/Module/Front/Article/ArticleListView.php

#[ViewModel(
    layout: 'article-list',
    js: 'article-list.js'
)]
class ArticleListView implements ViewModelInterface
{
    public function __construct(
        // Inject ORM
        protected ORM $orm
    ) {
        //
    }

    public function prepare(AppContext $app, View $view): array
    {
        $items = $this->orm->from(Article::class)
            ->where('state', 1) // Only fetch state = 1
            ->limit(15)
            ->all(Article::class);

        return compact('items');
    }
}

```

Here we add a class name: `Article::class` to `all()` method as first argument, that well make ORM hydrate every item
to `Article` entity. If no argument provided, a `Collection` list will be returned.

If you don't want to read items from DB instantly, you may consider replace `->all(Article::class)` with `->getIterator(Article::class)`, the ORM will return an iterator, before you loop it, the ORM will not send any request to DB.

In the `views/article-list.blade.php` template file, we use foreach to print the articles, wrapped in
a [bootstrap](https://getbootstrap.com/docs/5.3/components/card/) card.

```blade
<?php // src/Module/Front/Article/views/article-list.blade.php
// ...

use App\Entity\Article;

// ...

/**
 * Annotate the type of $items
 * @var $items Article[]
 * @var $item  Article
 */
?>

@extends('global.body')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-7">

                @foreach ($items as $item)
                    <div class="card mb-4">
                        <div class="card-body">
                            <h2 class="card-title">
                                {{ $item->getTitle() }}
                            </h2>

                            <div class="mb-2 small text-muted">
                                {{-- Convert DB UTC timezone to local timezone --}}
                                {{ $chronos->toLocalFormat($item->getCreated(), 'Y/m/d H:i:s') }}
                            </div>

                            <div>
                                {{-- Truncate the string for summary --}}
                                {!! \Windwalker\str($item->getContent())->stripHtmlTags()->truncate(100, '...') !!}
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
@stop

```

> [!note]
> Although the view file named **blade**, this is not render by Laravel Blade engine. Windwalker implemented a blade compatible engine called [Edge](/documentation/components/edge/), which is not depend on any Illumination package and dose not contains any Laravel built-in functions, and can be fully customize for any PHP projects.

Next, create the routing, run:

```shell
php windwalker g route front/article
```

and edit the generated file to add article-list route:

```blade
<?php // routes/front/article.route.php

// ...
use App\Module\Front\Article\ArticleListView; // [!code focus]

$router->group('article')
    ->register(function (RouteCreator $router) {
        $router->any('article_list', '/article/list') // [!code focus]
            ->view(ArticleListView::class); // [!code focus]
    });

```

Now, open the URL `http://localhost:8000/article/list`

You will see the article list.

![sarticle list](https://github.com/windwalker-io/framework/assets/1639206/05ac054f-fa8c-46e2-bbc8-21defff2dc62)

## Create Pagination

Pagination requires three numbers:

| **Number**             | **Purpose**                     | **Explanation**                                                                                                                   |
|------------------------|---------------------------------|-----------------------------------------------------------------------------------------------------------------------------------|
| **page** or **offset** | Current page or offset          | page is the current page number, usually obtained from the URL. `(page - 1) x limit` is the offset                                |
| **limit**              | Items per page                  | limit is directly set in our program, or can be controlled from the URL if needed                                                 |
| **total**              | Maximum number of current query | total is used to calculate the number of pages. It can be omitted, resulting in infinite next pages, usually calculated by the DB |

We first modify the `ArticleListView` code as follows:

```php
use App\Entity\Article;
use Windwalker\Core\Pagination\PaginationFactory;
use Windwalker\ORM\ORM;

use function Windwalker\filter;

// ...

class ArticleListView implements ViewModelInterface
{
    public function __construct(
        protected ORM $orm,
        protected PaginationFactory $paginationFactory
    ) {
        //
    }

    public function prepare(AppContext $app, View $view): array
    {
        $page = (int) $app->input('page');
        $limit = 5;

        // Restrict the minimum value of page to 1
        $page = filter($page, 'int|range(min=1)');

        $query = $this->orm->from(Article::class)
            ->where('state', 1)
            ->offset(($page - 1) * $limit) // Calculate offset
            ->limit($limit);

        // Create pagination object
        $pagination = $this->paginationFactory->create($page, $limit)
            // Calculate total using ORM::countWith()
            ->total(fn () => $this->orm->countWith($query));

        $items = $query->getIterator(Article::class);

        return compact('items', 'pagination');
    }
}

```

Then print the pagination in the blade template.

```blade
    ...
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="my-4"> // [!code ++]
                    <x-pagination :pagination="$pagination"></x-pagination> // [!code ++]
                </div> // [!code ++]

            </div>
        </div>
    ...
```

The result:

![pagination](https://github.com/windwalker-io/framework/assets/1639206/746b7449-450b-4f60-9902-372c593ede9d)


We use `x-pagination` component to print the pagination HTML, you can modify the HTML by editing `views/layout/pagination/basic-pagination.blade.php`. And you can replace the template file path by editing `etc/packages/renderer.php`

```php
    // ...

        'aliases' => [
            '@pagination' => 'layout.pagination.basic-pagination', // [!code focus]
            '@messages' => 'layout.messages.bs5-messages',
            '@csrf' => 'layout.security.csrf',
        ],

    // ...
```

## Create View Model for Item

Similar to the previous steps, run this command:

```php
php windwalker g view Front/Article/ArticleItemView
```

to create `ArticleItemView`, and then write the code as follows:

```php
// src/Module/Front/Article/ArticleItemView.php

// ...
use App\Entity\Article;
use Windwalker\Core\Router\Exception\RouteNotFoundException;
use Windwalker\ORM\ORM;

// ...

#[ViewModel(
    layout: 'article-item',
    js: 'article-item.js'
)]
class ArticleItemView implements ViewModelInterface
{
    public function __construct(
        protected ORM $orm
    ) {
        //
    }

    public function prepare(AppContext $app, View $view): array
    {
        $id = $app->input('id');

        $item = $this->orm->findOne(Article::class, $id);

        if (!$item) {
            throw new RouteNotFoundException();
        }

        return compact('item');
    }
}

```

Here we get ID from URL params and find record from DB. If there are no any item found, throw a 404 exception. You may simply replace to `mustFinOne()` to done this by one line.  

```php
        $item = $this->orm->mustFindOne(Article::class, $id); // [!code ++]
        $item = $this->orm->findOne(Article::class, $id); // [!code --]

        if (!$item) { // [!code --]
            throw new RouteNotFoundException(); // [!code --]
        } // [!code --]
```

Next, write the blade template.

```blade
<?php
// src/Module/Front/Article/views/article-item.blade.php

/**
 * @var $item Article
 */
?>

@extends('global.body')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <article class="c-article">
                    <header>
                        <h2>{{ $item->getTitle() }}</h2>
                    </header>

                    <div class="my-2 text-muted">
                        {{ $chronos->toLocalFormat($item->getCreated(), 'Y/m/d H:i:s') }}
                    </div>

                    <div class="c-article__content">
                        {!! $item->getContent() !!}
                    </div>
                </article>
            </div>
        </div>
    </div>
@stop

```

Then add this view to the route.

```php
// ...

use App\Module\Front\Article\ArticleItemView; // [!code focus]
use App\Module\Front\Article\ArticleListView;
use Windwalker\Core\Router\RouteCreator;

/** @var RouteCreator $router */

$router->group('article')
    ->register(function (RouteCreator $router) {
        $router->any('article_list', '/article/list')
            ->view(ArticleListView::class);

        $router->any('article_item', '/article/item/{id}') // [!code focus]
            ->view(ArticleItemView::class); // [!code focus]
    });
```

Finally, go back to `article-list.blade.php` and add links to each card to `article_item`.

```blade
@foreach ($items as $item)
    <div class="card mb-4">
        <div class="card-body">
            ...
            
            <div>
                {{-- Truncate the string for summary --}}
                {!! \Windwalker\str($item->getContent())->stripHtmlTags()->truncate(100, '...') !!}
            </div>

            <div class="mt-2"> // [!code ++]
                <a href="{{ $nav->to('article_item', ['id' => $item->getId()]) }}" class="btn btn-primary"> // [!code ++]
                    Read More // [!code ++]
                </a> // [!code ++]
            </div> // [!code ++]
        </div>
    </div>
@endforeach
```

The final result:

![list](https://github.com/windwalker-io/framework/assets/1639206/f1c58ed0-ac00-493d-b7f8-f4f360e523da)

![item](https://github.com/windwalker-io/framework/assets/1639206/ba033563-bc2e-4711-901c-aa359136d8d9)

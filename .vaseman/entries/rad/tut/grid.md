---

layout: rad.twig
title: Prepare Grid View

---

## Articles List Manager

We must add more information to article grid page. Open `Model/ArticlesModel.php`, add some joined table to article.

``` php
// src/Blog/Admin/Model/ArticlesModel.php

// ...

class ArticlesModel extends ListModel
{
	// ...

	protected function configureTables()
	{
		$this->addTable('article', Table::ARTICLES)
        	->addTable('category', Table::CATEGORIES, 'category.id = article.category_id');
	}
```

After table joined, let's add some data in grid template.

``` php
<!-- src/Blog/Admin/Templates/articles/articles.blade.php -->

<!-- ... -->

<div class="grid-table table-responsive">
    <table class="table table-bordered">
        <thead>
        <tr>
            <!-- ... -->

            {{-- CATEGORY --}}
            <th>
                {!! $grid->sortTitle('Category', 'article.category_id') !!}
            </th>

            {{-- TITLE --}}
            <th>
                {!! $grid->sortTitle('admin.article.field.title', 'article.title') !!}
            </th>

            <!-- ... -->
        </tr>
        </thead>

        <tbody>
        @foreach ($items as $i => $item)
            <!-- ... -->

                {{-- CATEGORY --}}
                <td>
                    {{ $item->category_title }}
                </td>

                {{-- TITLE --}}
                <td>
                    <a href="{{{ $router->route('article', array('id' => $item->id)) }}}">
                        {{ $item->title }}
                    </a>
                    <br/>
                    <small class="text-muted">{{ $item->alias }}</small>
                </td>

            <!-- ... -->
```

Now Category title has been added to articles view.

![Imgur](https://i.imgur.com/Yeezqqs.jpg)

## Comment Grid

Also, we do same thing to `comments`.

``` php
// src/Blog/Admin/Model/CommentsModel.php

class CommentsModel extends ListModel
{
	// ...

	protected function configureTables()
	{
		$this->addTable('comment', Table::COMMENTS)
			->addTable('article', Table::ARTICLES, 'article.id = comment.article_id');
	}

	// ...
```

Add `name`, `email` and `article_title`, remove some none-necessary fields.

``` php
<!-- src/Blog/Admin/Templates/comments/comments.blade.php -->

<!-- ... -->

<table class="table table-bordered">
    <thead>
    <tr>
        {{-- CHECKBOX --}}
        <th>
            {!! $grid->checkboxesToggle(array('duration' => 150)) !!}
        </th>

        {{-- STATE --}}
        <th style="min-width: 90px;">
            {!! $grid->sortTitle('admin.comment.field.state', 'comment.state') !!}
        </th>

        {{-- ARTICLE --}}
        <th>
            {!! $grid->sortTitle('Article', 'article.title') !!}
        </th>

        {{-- NAME --}}
        <th>
            {!! $grid->sortTitle('Name', 'comment.name') !!}
        </th>

        {{-- EMAIL --}}
        <th>
            {!! $grid->sortTitle('Email', 'comment.email') !!}
        </th>

        {{-- ORDERING --}}
        <th width="5%" class="nowrap">
            {!! $grid->sortTitle('admin.comment.field,.ordering', 'comment.ordering') !!} {!! $grid->saveorderButton() !!}
        </th>

        {{-- CREATED --}}
        <th>
            {!! $grid->sortTitle('admin.comment.field.created', 'comment.created') !!}
        </th>

        {{-- ID --}}
        <th>
            {!! $grid->sortTitle('admin.comment.field.id', 'comment.id') !!}
        </th>
    </tr>
    </thead>

    <tbody>
    @foreach ($items as $i => $item)
        <?php
        $grid->setItem($item, $i);
        ?>
        <tr>
            <!-- ... -->

            {{-- ARTICLE --}}
            <td>
                {!! $grid->foreignLink($item->article_title, $router->route('article', array('id' => $item->article_id))) !!}
            </td>

            {{-- NAME --}}
            <td>
                <a href="{{ $router->route('comment', array('id' => $item->id)) }}">
                    {{ $item->name }}
                </a>
            </td>

            {{-- EMAIL --}}
            <td>
                <a href="{{ $router->route('comment', array('id' => $item->id)) }}">
                    {{ $item->email }}
                </a>
            </td>

            {{-- ORDERING --}}
            <td>
                {!! $grid->orderButton() !!}
            </td>

            <!-- ... -->
    @endforeach
```

The result will be:

![Imgur](https://i.imgur.com/6E5l53f.jpg)

### Get Joined Fields

The `ListModel` helps us create table join and return object list. All joined fields will add table name alias as prefix.

For example, if you write this join condition in model:

```php
$this->addTable('article', Table::ARTICLES)
    ->addTable('category', Table::CATEGORIES, 'category.id = article.category_id')
    ->addTable('user', Table::USERS, 'user.id = article.created_by');
```

Then you can get property in view by this way:

```php
foreach ($items as $item)
{
    // Get article field
    $item->id;
    $item->title;
    $item->introtext;
    
    // GEt joined fields
    $item->category_id;
    $item->category_title;
    $item->category_description;
    
    $item->user_id;
    $item->user_email;
    $item->user_name;
}
```

Looks good? It is convenience to get every fields in your joined table and you don't need to worry about naming conflict. 

## Configure List Loading

A Grid list must have some important information:
- start (page)
- limit (number per page)
- ordering
- direction (ASC or DESC)

We can configure it in list controller as a default value, so every one who first go to this page will have a default setting.

```php
namespace Flower\Controller\Sakuras;

class GetController extends ListDisplayController
{
	// ...

	protected $defaultOrdering = 'sakura.id';

	protected $defaultDirection = 'DESC';

	protected $limit = 50; // 0 to unlimited

	protected $fuzzingSearching = false;
```

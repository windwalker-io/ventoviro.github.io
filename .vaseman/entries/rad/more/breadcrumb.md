---

layout: rad.twig
title: Breadcrumb

---

## Create Breadcrumb

Create breadcrumb in Phoenix is very easy.

```php
// In Package
\Phoenix\Breadcrumb\Breadcrumb::push('Home', $uri->root);

// In View
\Phoenix\Breadcrumb\Breadcrumb::push('Articles', $router->route('articles'));
\Phoenix\Breadcrumb\Breadcrumb::push('Article Edit', '', true); // Third arg is active
```

Then render it in template:

```html
{!! \Phoenix\Breadcrumb\Breadcrumb::render() !!}
```

The result:

![](https://i.imgur.com/YfNmboh.jpg)

## Other Methods

```php
\Phoenix\Breadcrumb\Breadcrumb::pop();
\Phoenix\Breadcrumb\Breadcrumb::map();
\Phoenix\Breadcrumb\Breadcrumb::render();
\Phoenix\Breadcrumb\Breadcrumb::getItems();
\Phoenix\Breadcrumb\Breadcrumb::setItems($items: Data[]);
\Phoenix\Breadcrumb\Breadcrumb::get($index);
\Phoenix\Breadcrumb\Breadcrumb::set($index, $item: Data);
```

Breadcrumb is a DataSet object and it stores Data object as an iterator.

## Override Template:

Please override `phoenix/bootstrap/ui/breadcrumbs.blade.php`

---
layout: documentation.twig
title: View Model

---

## About MVC Interactions

In most MVC practices, there is no direct communication between View and Model. We called it **Passive View**. But there are many 
variants of MVC like: MVVM, MVP etc. 

Windwalker uses a pattern which is similar to [Supervising Controller](http://goo.gl/p6Rjwl), 
[MVVM](http://goo.gl/LJPG) or [MVP](http://goo.gl/y3VzE)
, View can not communicate to Model or Repository, but Controller can binding Repository to View, then View is able to get data from Repository.

The benefit of this pattern is that View can decide what data is necessary for this page or layout and call Repository to get data.
 And controller's responsibility is just binding repositories to view. There is a `ViewModel` between View and Repository 
 to handle request from View and get data from Repository.

### Schematic Diagram

![mvc](https://i.imgur.com/gGe4wGc.jpg) ![ww-mvc](https://i.imgur.com/TRxEg9j.jpg)

### ViewModel

View is not directly communicate to Repository, there is a `ViewModel` object between them. You can consider ViewModel 
as a Repository manager. 

![](https://cloud.githubusercontent.com/assets/1639206/5587061/82da36ac-911b-11e4-9da8-772dcd40e9b6.jpg)

## Use ViewModel Pattern in Windwalker

This is a traditional MVC usage in controller:

```php
// doExecute()

$repo = new FlowerRepository();
$view = new SakurasHtmlView();

$item = $repo->getItem();

$view['created'] = $item;

return $view->render();
```

Change some code, we can push Repository into View:

```php
// doExecute()

$repo = new FlowerRepository();
$view = new SakurasHtmlView();

$view->setRepository($repo);

// Or set with prepare repo state
$view->setRepository($repository, function (FlowerRepository $repo)
{
    $repo->set('item.id', $this->input->get('id'));
});

return $view->render();
```

Get data in View:

```php
class SakurasHtmlView extends HtmlView
{
	protected function prepareData($data)
	{
		$data->item = $this->repository->getItem();
	}
}
```

The `$view->repository` property is `ViewModel` object, if we call any method, `ViewModel` will pass this method to default Model by magic method.
If Repository does not have this method, it will only return `null`.

We can also get data by `pipe()` or `applyData()` with a callback and class hint:
 
```php
// In View

protected function prepareData($data)
{
    // Use pipe()
    $this->pipe(function (FlowerRepository $repo, $view) use ($data)
    {
        $data->item = $repo->getItem();
    });
    
    // Use applyData
    $this->applyData(function (FlowerRepository $repository, $data)
    {
        $data->item = $repository->getItem();
    });
    
    // ...
```

You must make sure Repository named with `XXXRepository`, the Repository will guess their own name. you can also 
set name property when declaring Repository class. There is some ways to define Repository's name:

```php
// Set by config
$repository->config->set('name', 'foo');

// Use class name
class FooRepository extends Repository {}

// Use property
class SomeRepository extends Repository
{
	protected $name = 'foo';
}
```

### Set Multiple Repositories

We can get many repositories and push them into one view.

```php
// In Controller::doExecute()

$repository = $this->getRepository();
$roseRepo = $this->getRepository('Rose');
$oliveRepo = $this->getRepository('Olive');

$view->setRepository($repository, true); // Second argument TRUE as default
$view->setRepository($roseRepo);
$view->setRepository($oliveRepo [, custom name]);

return $view->render();
```

Set sub models with custom name alias:

```php
$view->setRepository($repository, true); // Second argument TRUE as default
$view->addRepository('rose', $roseRepo);
$view->addRepository('olive', $oliveRepo);
```

If you want to set state when setting repositories, use callback:

```php
$view->addRepository('rose', $this->getRepository('Rose'), function (RoseRepository $repository) {
    $repository->set('list.page', 5);
});
```

Use `configureRepository()` or `pipe()` to configure models after set in View:

```php
$view->configureRepository('rose', function (RoseRepository $repository, $view) {
    $repository->set('list.limit', 15);
});

// pipe() will return value:
$list = $view->pipe('rose', function (RoseRepository $repository, $view) {
    $repository->set('list.limit', 15);
    return $repository->getList();
});
```

### Get Sub Repositories in View

Use array access to get different repositories and call methods in View.

```php
// In View::prepareData()

$data->items = $this->repository->getItems();
$data->roses = $this->repository['rose']->getRoses();
$data->olives = $this->repository['olive']->getOlives();
```

If a model not set, and the method name start with `get*` or `load*`, it will return `null`.

```php
$data->foo = $this->repository['foo']->getFoo();
```

You can also use `$this->pipe()` or `$this->applyData()`, remember send string as first argument to find repository by name alias.

```php
// In view

protected function prepareData($data)
{
    $this->applyData('rose', function (RoseRepository $repository, Data $data) {
        $data->roses = $repository->getList();
    });
    
    // Inject dat to cllback
    $this->pipe('rose', function (RoseRepository $repository, $view) use ($data) {
        $data->roses = $repository->getList();
    });
    
    // Or just return value
    $data->roses = $this->pipe('rose', function (RoseRepository $repository, $view) {
        return $repository->getList();
    });
    
    // ...
```

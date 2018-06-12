---
layout: documentation.twig
title: View Model

---

## About MVC Interactions

In most MVC practices, there is no direct communication between View and Model. We called it **Passive View**. But there are many 
variants of MVC like: MVVM, MVP etc. 

Windwalker uses a pattern which is similar to [Supervising Controller](http://goo.gl/p6Rjwl), [MVVM](http://goo.gl/LJPG) or [MVP](http://goo.gl/y3VzE)
, View can not communicate to Model, but Controller can binding Model to View, then View is able to get data from Model.

The benefit of this pattern is that View can decide which data they are needed and call Model to get these data.
 And controller's responsibility is just decide binding which models to view. There is a `ViewModel` between View and Model 
 to handle request from View and get data from Model.

### Schematic Diagram

![mvc](https://cloud.githubusercontent.com/assets/1639206/5587060/82d753f6-911b-11e4-85b8-3ccd08599c95.jpg) ![ww-mvc](https://cloud.githubusercontent.com/assets/1639206/5591914/9ddd2b42-91d6-11e4-9a6a-81fb427f4a54.jpg)

### ViewModel

View is not directly communicate to Model, there is a `ViewModel` object between them. You can consider ViewModel 
as a Model manager. 

![view-model](https://cloud.githubusercontent.com/assets/1639206/5587061/82da36ac-911b-11e4-9da8-772dcd40e9b6.jpg)

## Use ViewModel Pattern in Windwalker

This is a traditional MVC usage in controller:

``` php
<?php
// doExecute()

$model = new FlowerModel;
$view = new SakurasHtmlView;

$item = $model->getItem();

$view['created'] = $item;

return $view->render();
```

Change some code, we can push Model into View:

``` php
// doExecute()

$model = new FlowerModel;
$view = new SakurasHtmlView;

$view->setModel($model);

// Or set with prepare model state
$view->setModel($model, function (FlowerModel $model)
{
    $model->set('item.id', $this->input->get('id'));
});

return $view->render();
```

Get data in View:

``` php
class SakurasHtmlView extends HtmlView
{
	protected function prepareData($data)
	{
		$data->item = $this->model->getItem();
	}
}
```

The `$view->model` property is `ViewModel` object, if we call any method, `ViewModel` will pass this method to default Model by magic method.
If Model does not have this method, it will only return `null`.

We can also get data by `pipe()` or `applyData()` with a callback and class hint:
 
```php
// In View

protected function prepareData($data)
{
    // Use pipe()
    $this->pipe(function (FlowerModel $model, $view) use ($data)
    {
        $data->item = $model->getItem();
    });
    
    // Use applyData
    $this->applyData(function (FlowerModel $model, $data)
    {
        $data->item = $model->getItem();
    });
    
    // ...
```

You must make sure Model named with `XXXModel`, the model will guess their name. you can also set name property when declaring Model class. 
There is some ways to define Model's name:

``` php
// Set by config
$model->config->set('name', 'foo');

// Use class name
class FooModel extends Model {}

// Use property
class SomeModel extends Model
{
	protected $name = 'foo';
}
```

### Set Multiple Models

We can get many models and push them into one view.

``` php
// In Controller::doExecute()

$model = $this->getModel();
$roseModel = $this->getModel('Rose');
$oliveModel = $this->getModel('Olive');

$view->setModel($model, true); // Second argument TRUE as default
$view->setModel($roseModel);
$view->setModel($oliveModel [, custom name]);

return $view->render();
```

Set sub models with custom name alias:

```php
$view->setModel($model, true); // Second argument TRUE as default
$view->addModel('rose', $roseModel);
$view->addModel('olive', $oliveModel);
```

If you want to set state when setting models, use callback:

```php
$view->addModel('rose', $this->getModel('Rose'), function (RoseModel $model)
{
    $model->set('list.page', 5);
});
```

Use `configureModel()` or `pipe()` to configure models after set in View:

```php
$view->configureModel('rose', function (RoseModel $model, $view)
{
    $model->set('list.limit', 15);
});

// pipe() will return value:
$list = $view->pipe('rose', function (RoseModel $model, $view)
{
    $model->set('list.limit', 15);
    return $model->getList();
});
```

### Get Sub Models in View

Use array access to get different models and call methods in View.

``` php
// In View::prepareData()

$data->items = $this->model->getItems();
$data->roses = $this->model['rose']->getRoses();
$data->olives = $this->model['olive']->getOlives();
```

If a model not set, and the method name start with `get*` or `load*`, it will return `null`.

``` php
$data->foo = $this->model['foo']->getFoo();
```

You can also use `$this->pipe()` or `$this->applyData()`, remember send string as first argument to find model by name alias.

```php
// In view

protected function prepareData($data)
{
    $this->applyData('rose', function (RoseModel $model, Data $data)
    {
        $data->roses = $model->getList();
    });
    
    // Inject dat to cllback
    $this->pipe('rose', function (RoseModel $model, $view) use ($data)
    {
        $data->roses = $model->getList();
    });
    
    // Or just return value
    $data->roses = $this->pipe('rose', function (RoseModel $model, $view)
    {
        return $model->getList();
    });
    
    // ...
```



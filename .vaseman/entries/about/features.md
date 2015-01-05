layout: about.twig
title: Features

---

This page, we list something Windwlker do better than others, or we think it is worth you try it. 

# Easy and Understandable

Windwalker is very simple on classes interface, we called it **programming UX**, most classes in Windwalker 
has single entry point and easy understandable naming.

# Fully Decoupled

Most Windwalker packages has zero dependency, just a few big packages will dependency on 1 or 2 Windwalker package itself. 
We hope developer has more flexible to decide what they want to include in their projects.
 
Many Windwlaker packages provides adapter interface to let developer create adapter to working with other library, for example,
the [DataMapper](https://github.com/ventoviro/windwalker-datamapper) package has a `DatabaseAdapter` that you may create an adapter 
to use other frameworks' database object, and you don't need to install Windwalker Database.

# Package System (Bundle)

Package is the main component of Windwalker's structure. Here is a image that describe the package system:

![](https://cloud.githubusercontent.com/assets/1639206/5579031/b4c50ed8-906e-11e4-8964-a1f2d949fc88.png)

From this image, we will know there can be multiple packages and its' own MVC system in Windwalker. 
That make our application more flexible. For example, we can create a FrontendPackage and an AdminPackage to maintain 
your front-end and back-end. And an ApiPackage to provide RESTful API for mobile APP.

See: [Package Syetem](../documentation/start/package-system.html)

# Single Responsibility Principle

Most Windwalker objects follows [Single Responsibility Principle](http://en.wikipedia.org/wiki/Single_responsibility_principle),
the benefit is that the responsibility of every object are very clear, we don't need to include a big object for using only one method.

The main usage is **Single Action Controller**, every controller has only one action (`execute()`). 
Controllers in Windwalker will be more lighter then other frameworks. You can add more logic to one controller 
but won't be confused by many actions in one class.

``` php
class GetController extends Controller
{
    protected function doExecute()
    {
        // Do some stuff...
    }
}
```

See: [Use Controller](../documentation/mvc/use-controller.html)

## Hook System

Since many objects has single entry point, there is a hook system in Windwalker everywhere.
You can add many logic before or after `doExecute()` if you override the parent classes.

![hook](https://cloud.githubusercontent.com/assets/1639206/5596977/5f11832c-92d5-11e4-8e43-33013d076877.jpg)

# ViewModel Pattern

Windwalker uses a pattern which is similar to [Supervising Controller](http://goo.gl/p6Rjwl), [MVVM](http://goo.gl/LJPG) or [MVP](http://goo.gl/y3VzE)
, View can not communicate to Model, but Controller can binding Model to View, then View is able to get data from Model.

![mvc](https://cloud.githubusercontent.com/assets/1639206/5587060/82d753f6-911b-11e4-85b8-3ccd08599c95.jpg) ![ww-mvc](https://cloud.githubusercontent.com/assets/1639206/5591914/9ddd2b42-91d6-11e4-9a6a-81fb427f4a54.jpg)

See: [ViewModel](../documentation/mvc/view-model.html)

# Stateful Model

Windwalker Model is stateful design, use state pattern can help ue create flexible data provider. For example, we can change this state to get different data.

``` php
$model = new MyModel;

// Let's change model state
$model->set('where.published', 1);
$model->set('list.ordering', 'birth');
$model->set('list.direction', 'DESC');

$users = $model->getUsers();
```

See: [Model State](../documentation/mvc/model-database.html#model-state)

# Powerful Templating

## Extendible PHP Engine

Windwalker PHP Engine is able to extends parent template which similar to Blade or Twig:

``` html
<?php
$this->extend('_global.main');
?>

<?php $this->block('title');?>Article<?php $this->endblock(); ?>

<?php $this->block('body');?>
    <article>
        <h2>Article</h2>
        <p>FOO</p>
    </article>
<?php $this->endblock(); ?>
```

See: [PHP Engine](../documentation/mvc/view-templating.html#php-engine)

## Blade and Twig Engine
 
Windwalker also support powerful Blade and Twig engine.

See: [Blade Engine](../documentation/mvc/view-templating.html#blade-engine) / [Twig Engine](../documentation/mvc/view-templating.html#twig-engine)

# Widget System

Widget object is a convenience tool to help us render HTML blocks, you can consider it as a backend web component.

![widget](https://cloud.githubusercontent.com/assets/1639206/5594250/e28c0d56-927d-11e4-8f32-19005916710c.jpg)

We also contains powerful level-based template override rules.

See: [Widget and Renderer](../documentation/more/widget-renderer.html)

# Fast Routing

## Restful

Windwalker use Restful design, one route will able to handle four controller actions. This can reduce a quarter of times to match routes.
 
## Matchers

Router provides some matchers to use different way to search routes:

- **Sequential Matcher** - Use the [Sequential Search Method](http://en.wikipedia.org/wiki/Linear_search) to find route
- **Binary Matcher** - Use the [Binary Search Algorithm](http://en.wikipedia.org/wiki/Binary_search_algorithm) to find route.
- **Trie Matcher** - Use the [Trie](http://en.wikipedia.org/wiki/Trie) tree to search route.

A simple benchmark when we developing Router:

![Matcher Benchmark](https://cloud.githubusercontent.com/assets/1639206/5596779/7bab7460-92d1-11e4-997b-d69fc58fb520.jpg)

See: [Routing](../documentation/start/routing-controller.html)

# Database

## Powerful Schema Builder

``` php
use Windwalker\Database\Schema\Column;
use Windwalker\Database\Schema\Key;
use Windwalker\Database\Schema\DataType;

$table = $db->getTable('#__articles');

$table->addColumn('id', DataType::INTEGER, Column::UNSIGNED, Column::NOT_NULL, '', 'PK', array('primary' => true))
    ->addColumn('name', DataType::VARCHAR, Column::SIGNED, Column::NOT_NULL, '', 'Name', array('length' => 255))
    ->addColumn('alias', DataType::VARCHAR, Column::SIGNED, Column::NOT_NULL, '', 'Alias')
    ->addIndex(Key::TYPE_INDEX, 'idx_name', 'name', 'Test')
    ->addIndex(Key::TYPE_UNIQUE, 'idx_alias', 'alias', 'Alias Index')
    ->create(true);
    
// OR simpler

$table->addColumn(new Column\Primary('id'))
    ->addColumn(new Column\Varchar('name'))
    ->addColumn(new Column\Char('type'))
    ->addColumn(new Column\Timestamp('created'))
    ->addColumn(new Column\Bit('state'))
    ->addColumn(new Column\Integer('uid'))
    ->addColumn(new Column\Tinyint('status'))
    ->create();
```

See: [Table and Schema](../documentation/db/table-schema.html)

## DataType Mapping

Use `DataType::TYPE_NAME` to define data type of every column, Windwalker will auto map datatype for different databases.

For example, use `DataType::DATETIME` in Mysql, will be `datetime`, but in PostgreSql, will be `timestamp`. You won't worry about 
the difference of data type between databases.

> NOTE: Postgresql, Oracle and MSSQL are still working in process, will be release after 2.1

See: [Table and Schema](../documentation/db/table-schema.html)

## The DataMapper

DataMapper is an easy using tool help us access Database:
 
``` php
use Windwalker\DataMapper\DataMapper;

$mapper = new DataMapper('#_table_name');

$dataset = $mapper->find(array('id' => 5));

$mapper->save($data);

$mapper->delete(12);
```

See: [DataMapper](../documentation/db/datamapper.html)

# Easy Error Handling

See: [Error Handling](../documentation/more/error-handling.html)

# Spl Iterable Filesystem

``` php
$items = Filesystem::items($path);

foreach ($files as $file)
{
    if ($file->isDir())
    {
        continue;
    }
}
```

Check permission bigger than 755.

``` php
$closure = function($current, $key, $iterator)
{
    return Path::getPermissions($current->getPath()) >= 755;
};

$files = Filesystem::find($path, $closure, true);
```

# Form Builder

``` php
$form = new Form;

$form->addField(new TextField('name', 'Label'))
    ->set('id', 'my-name')
    ->set('class', 'col-md-8 form-input')
    ->set('onclick', 'return false;');
    
$form->addField(new TextField('email', 'Label'))
    ->required();
    
$form->addField(new TextField('username', 'Username'));
$form->addField(new PasswordField('password', 'Password'));
$form->addField(new TextareaField('description', 'Description'));

echo $form->renderFields();
```

See: [Form Builder](../documentation/more/form-builder.html)

# HTML Builder

Simple HtmlElement to build a tag.

``` php
use Windwalker\Dom\HtmlElement;

$attrs = array(
    'class' => 'btn btn-mini',
    'onclick' => 'return fasle;'
);

$html = (string) new HtmlElement('button', 'Click', $attrs);

// The result
// <button class="btn btn-mini" onclick="return false;">Click</button>
```

And select list builder.

``` php
use Windwalker\Html\Select\SelectList;
use Windwalker\Html\Option;

$select = new SelectList(
    'form[timezone]',
    array(
        new Option('Asia - Tokyo', 'Asia/Tokyo', array('class' => 'opt')),
        new Option('Asia - Taipei', 'Asia/Taipei'),
        new Option('Europe - Paris', 'Asia/Paris'),
        new Option('UTC', 'UTC'),
    ),
    array('class' => 'input-select'),
    'UTC',
    false
);

echo $select;
```

See: [HTML Builder](../documentation/more/html-builder.html)

# Easy Benchmarking

Benchmark in Windwalker is very easy:

``` php
use Windwalker\Profiler\Banchmark;

$benchmark = new Benchmark;

$benchmark->addTask('task1', function()
{
    md5(uniqid());
});

$benchmark->->addTask('task2', function()
{
    sha1(uniqid());
});

$benchmark->execute(10000);

echo $benchmark->render();

/* Result
task1 => 187.489986 ms
task2 => 207.049847 ms
*/
```

See: [Profiler](https://github.com/ventoviro/windwalker-profiler#windwalker-profiler)

# Utilities

## String Handler

Utf8 string handler

``` php
use Windwalker\String\Utf8String;

echo Utf8String::substr('這是中文字', 0, 3);
```

String Inflector

``` php
use Windwalker\String\StringInflector;

$inflector = new StringInflector;

echo $inflector->toSingular('categories'); // category
```

## Easy Dump Data

``` php
// print_r 3 objects and limit 7 levels
show($data1, $data2, $data3, 7);
```

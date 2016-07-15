---
layout: documentation.twig
title: Widget and Renderer
redirect:
    2.1: more/widget-renderer

---

## What is Widget Object

Widget object is a convenience tool to help us render HTML blocks, you can consider it as a backend web component.  

![widget](https://cloud.githubusercontent.com/assets/1639206/5594250/e28c0d56-927d-11e4-8f32-19005916710c.jpg)

## Create A Widget

First, add a template file in `/templates/sidebar/news.php`, this is a news widget.

``` php
<ul class="nav news">
	<?php foreach ($data->articles as $article): ?>
	<li>
		<a href="<?php echo $article->link; ?>">
			<?php echo $article->title; ?>
		</a>
	</li>
	<?php endforeach; ?>
</ul>
```

Now we can use this widget in anywhere, remember add data when rendering it:

``` php
use Windwalker\Core\Widget\Widget;

$news = new Widget('sidebar.news');

$news->render(array('articles' => $data));

// In PHP 5.4, you can use instantiation object access
$news = (new Widget('sidebar.news'))->render(['articles' => $data]);
```

It will be useful to render widget in View `prepareData()`:

``` php
class SakurasHtmlView extends HtmlView
{
	protected function prepareData($data)
	{
		$data->items      = $this->model->getItems();
		$data->categories = $this->model['Categories']->getItems();

		$data->news       = (new Widget('sidebar.news'))->render(array('articles' => $data->items));
		$data->pagination = (new Widget('_global.pagination.pagination'))->render(array('pages' => $this->model->getPagination()));
		$data->categories = (new Widget('sidebar.categories'))->render(array('categories' => $data->categories));
		$data->banner     = (new Widget('sidebar.banner'))->render(array('banner' => $data->banner));
	}
}
```

## Templating

By default, widget will find templates from these paths:

``` html
- {ROOT}/src/{package}/Templates/{Locale}
- {ROOT}/src/{package}/Templates
- {ROOT}/templates
- {ROOT}/vendor/windwalker/core/src/Core/Resources/Templates
```

You can add your custom path to Widget:

``` php
use Windwalker\Utilities\Queue\PriorityQueue;

$widget->addPath('/my/widget/path', PriorityQueue::HIGH);
```

Widget also use [Windwalker Renderer](https://github.com/ventoviro/windwalker-renderer) to render page,
you need to add priority to set the ordering of this path.

### Set Package

By default, Widget will auto guess current package, but you can set custom package to direct to different template paths.

``` php
// Add package name, Widget will auto resolve package
$news = new Widget('sidebar.news', 'php', 'package_name');

// OR add package object

$news = new Widget('sidebar.news', 'php', $package);
```

### Add Path to Global

Add your path to global RendererHelper that Widget will always contain this path:

``` php
\Windwalker\Core\Renderer\RendererHelper::addGlobalPath('/my/widget/path', PriorityQueue::ABOVE_NORMAL);
```

### Add Shared Variables

``` php
$widget = new Widget('flower.sakura');
$widget->set('foo', 'bar');

echo $widget->render($data1);
echo $widget->render($data2);
```

## Override Built-in Widgets Templates

Windwalker has some built-in widgets, it contains: `messages`, `pagination`, `error pages`.

There is an example to override messages template. The global message template is located at

```
{ROOT}/vendor/windwalker/core/src/Core/Resources/Templates/windwalker/message/default.php
```

So we can add a custom template at:

```
{ROOT}/templates/windwalker/message/default.php
```

Now all messages in Windwalker will select your template since the new file path is priority to origin file.

> Override other widgets please see: [Pagination](pagination.html) and [Error Handling](error-handling.html)

## Use Edge and Twig

Similar to View, Windwalker Widget support Blade and Twig engine, you may just create it by newing it:

``` php
use Windwalker\Core\Widget\WidgetHelper;

// Use constant
WidgetHelper::createWidget('sidebar.news', WidgetHelper::EDGE);

// Use string
WidgetHelper::createWidget('sidebar.news', 'twig');
```

> See also: [Blade Templating](https://laravel.com/docs/master/blade) and [Twig Documentation](http://twig.sensiolabs.org/documentation)

## Render by WidgetHelper

Use WidgetHelper to quickly render page.

``` php
use Windwalker\Core\Widget\WidgetHelper;

$html = WidgetHelper::render('foo.bar', $data, WidgetHelper::EDGE);
```

## Create Custom Widget Class

Create custom Widget class to quickly render specific template.

``` php
use Windwalker\DataMapper\DataMapper;

class NewsWidget extends Widget
{
	/**
	 * Optional property to set engine.
	 */
	protected $renderer = 'edge';

	/**
	 * Optional property to find template.
	 */
	protected $layout = 'sidebar.news';

	/**
	 * Optional property to locate package.
	 */
	protected $package = 'flower';

	protected function prepareData($data)
	{
		$data->items = (new DataMapper('articles'))->limit(10, 20)->find();
	}
}
```

``` php
echo (new NewsWidget)->render();
```

## Render Widget in Template

WidgetHelper instance has been inject to global renderer variables, we can use it to quickly render widget.

In php template

``` php
<?php echo $widget->render('sidebar,.news', $data, 'edge'); ?>
```

In Blade or Edge

``` php
{!! $widget->render('sidebar,.news', $data, 'edge') !!}

OR

@widget('sidebar,.news', $data, 'edge')
```

Twig

``` twig
{{ widget.render('sidebar .news', $data, 'edge') | raw }}
```

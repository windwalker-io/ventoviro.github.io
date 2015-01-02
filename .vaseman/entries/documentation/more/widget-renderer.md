layout: documentation.twig
title: Widget and Renderer

---

# What is Widget Object

Widget object is a convenience tool to help us render HTML blocks, you can consider it as a backend web component.  

![widget](https://cloud.githubusercontent.com/assets/1639206/5594250/e28c0d56-927d-11e4-8f32-19005916710c.jpg)

# Create A Widget

First, add a template file in `/templates/sidebar/news.php`, this is a news widget.

``` html
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

// In PHP 5.3, you can use with() to simulate instantiation object access
$news = with(new Widget('sidebar.news'))->render(array('articles' => $data));
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

# Templating

By default, widget will find templates from these paths:
 
``` html
[0] => /templates
[1] => /vendor/windwalker/core/src/Core/Resources/Templates
```

You may add your custom path to Widget:

``` php
use Windwalker\Utilities\Queue\Priority;

$widget->addPath('/my/widget/path', Priority::HIGH);
```

Widget also use [Windwalker Renderer](https://github.com/ventoviro/windwalker-renderer) to render page, 
you need to add priority to set the ordering of this path.

## Add Path to Global

Add your path to global RendererHelper that Widget will always contain this path:

``` php
\Windwalker\Core\Renderer\RendererHelper::addPath('/my/widget/path', Priority::ABOVE_NORMAL);
```

# Override Built-in Widgets Templates

Windwalker has some built-in widgets, it contains: `messages`, `pagination`, `error pages`.

There is an example to override messages template, the global message template is located at 

``` html
/vendor/windwalker/core/src/Core/Resources/Templates/windwalker/message/default.php
```

So we can add a custom template at: 
``` html
/templates/windwalker/message/default.php
```

Now all messages in Windwalker will use your template to render.

> Override other widgets please see: [Pagination](pagination.html) and [Error Handling](error-handling.html)

# Use Blade and Twig

Similar to View, Windwalker Widget support Blade and Twig engine, you may just create it by newing it:

``` php
use Windwalker\Core\Widget\BladeWidget;
use Windwalker\Core\Widget\TwigWidget;

$news = new BladeWidget('sidebar.news');
$news = new TwigWidget('sidebar.news');
```

> See also: [Blade Templating](http://laravel.com/docs/4.2/templates) and [Twig Documentation](http://twig.sensiolabs.org/documentation)

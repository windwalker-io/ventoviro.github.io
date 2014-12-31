layout: documentation.twig
title: Use Controller

---

# Controller Overview

Windwalker Controller is a main entry of a page, after routing, the request input and IoC container will be injected 
into controller and execute it. Every our code of this page will start at `doExecute()`.

Simple usage of controller:

``` php
use Flower\Controller\Sakura\GetController;

use Flower\View\Sakura\SakuraHtmlView;
use Windwalker\Core\Controller\Controller;

class GetController extends Controller
{
	protected function doExecute()
	{
		$view = new SakuraHtmlView;

		return $view->render();
	}
}
```

## Single Action Pattern

Windwalker Controller follows single responsibility principle, every controller has only one action (`execute()`).
The benefit is that our controllers will be more lighter then other frameworks. You can add more logic to a controller but won't be
confused by many actions in one class.

## Use Multiple Actions

But if you want to use multiple actions like traditional practice, Windwalker also support it.

First, your controller should extends to `MultiActionController`:

``` php
use Flower\Controller\Sakura\SakuraController;

use Flower\View\Sakura\SakuraHtmlView;
use Windwalker\Core\Controller\MultiActionController;

class GetController extends MultiActionController
{
	protected function indexAction($id = null)
	{
		// ...
	}
	
	protected function createAction($id = null)
	{
		// ...
	}
}
```

Then please edit the routing file:

``` http
flower:
    pattern: /flower/sakura(/id)
    controller: Flower\Controlelr\Sakura\SakuraController
    action:
        get: '::indexAction'
        post: '::createAction'
```

The action prefix with double colons will be methods of your controller.

> NOTE: There is a found bug of MultiActionController in 2.0.3 that make it not work, we'll fix it soon after next version.

# Get HTTP Input

See: [Request and Input](../start/request-input.html), we can use Input object to get HTTP queries:

``` php
$id = $this->input->getInput('id');

$layout = $this->input->getString('layout', 'default');

$html = $this->getVar('html');
``` 

# Simple Usage of Model and View

``` php
// In doExecute()

// Get Input
$id = $this->input->getInt('id');
$layout = $this->input->getString('layout', 'default');

// Get Model and View
$model = new SakuraModel;
$view = new SakuraHtmlView;

// Get Data
$item = $model->getItem($id);

// Push data into View
$view->set('item', $item);

// Render
return $view->setLayout($layout)->render();
```

## Use Model and View getter

If your model and view located in your package, and follows Windwalker naming convention, you can get Model and View by getter in controller.

``` php
// In doExecute()

// File: src/Flower/Model/SakuraModel.php
// Class: Flower\Model\SakuraModel
$model = $this->getModel('Sakura');

// File: src/Flower/View/Sakura/SakuraHtmlView
// Class: Flower\View\Sakura\SakuraHtmlView
$view = $this->getView('Sakura', 'html');

// Get other models and views
$roseModel = $this->getModel('Rose'); // RoseModel
$roseView = $this->getView('Rose', 'json'); // RoseJsonView
```

If you didn't send name into getter, will use controller name as default:

``` php
// In doExecute()

// File: src/Flower/Model/SakuraModel.php
// Class: Flower\Model\SakuraModel
$model = $this->getModel();

// File: src/Flower/View/Sakura/SakuraHtmlView
// Class: Flower\View\Sakura\SakuraHtmlView
$view = $this->getView();

$JsonView = $this->getView(null, 'json'); // SakuraJsonView
```

# Use Container

Windwalker controller is a Container aware interface, we can directly use container in controller:

``` php
// In doExecute()

$session = $this->container->get('system.session');
$cache = $this->controller->get('system.cache');

$cache->call('user', function() use ($session)
{
	return $session->get('user');
});
```

# Setting Config

You can set some config in `$this->config` and pass it into View and Model, then they will know everything about this controller.

``` php
// In doExecute()

$this->config->set('package.name', 'other_package_name');

$view->setConfig($this->config);
$model->setConfig($this->config);

// Then View will find template from other package
```

If you use getter to get View and Model, config will auto set into them.

Config is a Registry object, see: [Windwalker Registry](https://github.com/ventoviro/windwalker-registry#windwalker-registry)

# Redirect

Use `setRedirect($url)` to tell controller redirect to other url after executed. 

``` php
$this->setRedirect('pages.html');
```

Set Message when redirect:

``` php
$this->setRedirect('pages.html', 'Save success', 'success');
```

We can override redirect target everywhere when executing.

``` php
// In doExecute()

$model->save($data);

$this->setRedirect('pages.html', 'Save success', 'success');

try
{
	$model->saveRelations($data);
}
catch (Exception $e)
{
	// Override pervious url
	$this->setRedirect('edit.html', 'Save fail', 'error');

	return false;
}

return true;
```

# Add Flash Messages

Flash message is a disposable message in session, if we show it, these messages will be purged.

``` php
$this->addFlash('Message', 'type');

// OR

$this->setRedirect('url.html', 'Message', 'type');
```

## Mute

If we set controller to mute, this controller will not add any messages:

``` php
$this->mute(true);

$this->addFlash('Message'); // This action no use
```

# Check Token

(Not implemented yet)

# HMVC

The Hierarchical-Model-View-Controller (HMVC) pattern is a direct extension to the MVC pattern that manages 
to solve many of the scalability issues already mentioned. HMVC was first described in a blog post entitled 
[HMVC: The layered pattern for developing strong client tiers](http://goo.gl/jecFUK) on the JavaWorld web site in July 2000. 

![mvc-hmvc](https://cloud.githubusercontent.com/assets/1639206/5586880/993f66de-9115-11e4-8551-6f6c3f0f0058.png)

In Windwalker, using HMVC is very easy, look this example:

``` php
class ParentController extends Controller
{
	protected function doExecute()
	{
		// A standard way to push all data of current controller into sub controller
		$child = new ChildController($this->input, $this->app, $this->container, $this->package);
		
		$hmvcResult = $child->execute();
		
		// Use this result to do something
		$this->getView()->set('some_widget', $hmvcResult);
	}
}
```

Actually, all params of constructor can be ignored because the global IoC container will handle this dependency.
 
``` php
// Every dependency will be provided by IoC
$child = new ChildController;
```

But you can force push some params you needed:

``` php
$newInput = new Input(array('foo' => 'bar'));

$child = new ChildController($newInput);

// OR
$subContainer = Ioc::factory('sub.container');

$child = new ChildController(null, null, $subContainer, $subContainer->get('package'));
```

## Use hmvc() Method

Windwalker provides a `hmvc()` method to make this step more quickly:

``` php
$result = $this->hmvc('Flower\Controller\Rose\SaveController', array('data' => $data));

// OR

$result = $this->hmvc(new ChildController, $this->input);
```


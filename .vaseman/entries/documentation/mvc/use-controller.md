layout: documentation.twig
title: Use Controller

---

layout: documentation.twig
title: Use Controller

---

# Controller Overview

Windwalker Controller is a main entry of a page, after routing, the request input and IoC container will be injected into controller and execute it. Every our code of this page will start at `doExecute()`.

## Single Action Pattern

Windwalker Controller follows single responsibility principle, every controller only have one action and a main entry: `execute()`.

## Use Multiple Actions


# Get HTTP Input

See: [Request and Input](../start/request-input.html), we can use Input object to get HTTP queries:

``` php
$id = $this->input->getInput('id');

$layout = $this->input->getString('layout', 'default');

$html = $this->getVar('html');
``` 

# Simple Usage of Model and View

``` php
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
$session = $this->container->get('system.session');
$cache = $this->controller->get('system.cache');

$cache->call('user', function() use ($session)
{
	return $session->get('user');
});
```

# Setting Config

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

# Check Token

(Not implemented yet)

# HMVC



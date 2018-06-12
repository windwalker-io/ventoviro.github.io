---
layout: documentation.twig
title: Use Controller

---

## Controller Overview

Windwalker Controller is a main entry of a page, after routing, the request input and IoC container will be injected 
into controller and execute it. Any code mentioned in this section will be executed in `doExecute()`, the entrance of controller.

Simple usage of controller:

``` php
use Flower\Controller\Sakura\GetController;

use Flower\View\Sakura\SakuraHtmlView;
use Windwalker\Core\Controller\AbstractController;

class GetController extends AbstractController
{
	protected function doExecute()
	{
		$view = $this->getView();

		return $view->render();
	}
}
```

### Single Action Pattern

Windwalker Controller follows single responsibility principle, every controller has only one action (`execute()`).
The benefit is that our controllers will be much more lighter if an action has complex logic.

### Hooks

Controller provides `prepareExecute()` and `postExecute()` to allow you inject before & after process to controller.

![hooks](https://cloud.githubusercontent.com/assets/1639206/5596977/5f11832c-92d5-11e4-8e43-33013d076877.jpg)

This is an example:

``` php
class SaveController extends AbstractController
{
    // We prepare data in pre-process
	protected function prepareExecute()
	{
		$this->user   = $this->input->getArray('user');
		$this->format = $this->input->get('format', 'json');
		$this->repository  = $this->getRepository('User');
	}

    // Do real save logic
	protected function doExecute()
	{
		$this->repository
		    ->validate($this->user)
		    ->save($this->user);

		return true;
	}

    // Do post-process to return response.
	protected function postExecute($result = null)
	{
		if ($this->format == 'json')
		{
			return new JsonResponse($this->data, 200);
		}

		return new RedirectResponse($this->router->route('users'));
	}
}
```

Follows this way to write your logic so we can easily override the before and after process in child controller if we need
 extends this controller.

## Get HTTP Request Input

See: [Request and Input](../start/request-input.html), we can use Input object to get HTTP queries:

``` php
$id = $this->input->getInput('id');

$layout = $this->input->getString('layout', 'default');

$html = $this->getVar('html');

// Get other request data
$this->input->get->get('foo');
$this->input->post->get('foo');
$this->input->put->get('foo');
$this->input->patch->get('foo');
$this->input->delete->get('foo');
$this->input->files->get('foo');
$this->input->header->get('foo');
$this->input->server->get('foo');
$this->input->env->get('foo');
$this->input->cookie->get('foo');
```

## Use Repository and View Object

If your repository and view object located in your package, and follows Windwalker naming convention, you can get Repository and View by getter in controller.

``` php
// In doExecute()

// File: src/Flower/Repository/SakuraRepository.php
// Class: Flower\Repository\SakuraRepository
$this->getRepository('Sakura');

// File: src/Flower/View/Sakura/SakuraHtmlView
// Class: Flower\View\Sakura\SakuraHtmlView
 $this->getView('Sakura', 'html');

// Get other repositorys and views
$this->getRepository('Rose'); // RoseRepository
$this->getView('Rose', 'json'); // RoseJsonView
```

If you didn't tell getter the view or repository name, it will use controller name as default:

``` php
// In doExecute()

// SakuraRepository
$this->getRepository();

// SakuraHtmlView
$this->getView();

// SakuraJsonView
$this->getView(null, 'json');
```

See [View](view-templating.html) and [Repository](repository-database.html)

## Delegating Tasks

Controller `delegate()` method supports us handle multiple tasks in one controller if we need dispatch different handlers.

``` php
protected function doExecute()
{
    return $this->delegate($this->input->get('task', 'task1'), ...$this->input->post->toArray());
}

protected function task1($arg1, $arg2, $arg3)
{
    // ...
}

protected function task2($arg1, $arg2)
{
    // ...
}
```

## Use Container, Package & Application

Windwalker controller is a Container aware interface, we can directly use container in controller:

``` php
// In doExecute()

$session = $this->container->get('session');
$cache = $this->controller->get('cache');

$cache->call('user', function() use ($session)
{
	return $session->get('user');
});
```

Controller maintains package and application object, so we can get some important global services from them.

``` php
// Get services from Application
$this->app->container->get('...'); // Global container
$this->app->cache->getCache();
$this->app->session->get('user');
$this->app->browser->isMobile();
$this->app->platform->isLinux();
$this->app->database->getQuery(true);
$this->app->logger->debug('category', '...');
$this->app->mailer->createMessage('Subject');
$this->app->get('foo'); // Get global config

// Get services from package
$this->package->container->get('...'); // Package child container
$this->package->router->route('user'); // Package child router
$this->package->get('foo'); // Package config
```

More services please see [Service Provider](../core/service-provider.html)

## Success and Failure Processor

Controller also provides an interface to help us process success and failure of executed, for example, if we throw an Exception,
controller will catch it and auto run `processFailure()`, so we can write our failure handler buy override this method,
otherwise controller will call `processSuccess()` to handle success logic.

This is an sample code to show how to implement a DB transaction in controller.

``` php
class SaveController extends AbstractController
{
	protected function doExecute()
	{
		if (!$data = $this->input->getArray('data'))
		{
			throw new \InvalidArgumentException('No data');
		}

		/** @var DatabaseRepositoryRepository $this->repository */
		$this->repository->transactionStart(true);

		if (!$this->repository->save($data))
		{
			throw new \RuntimeException('Save fail');
		}

		return true;
	}

	public function processSuccess()
	{
		$this->repository->transactionCommit(true);

		return true;
	}

	public function processFailure(\Exception $e = null)
	{
		$this->repository->transactionRollback(true);

		$this->addMessage($e->getMessage(), 'warning');
		$this->setRedirect($this->router->route('user'));

		return false;
	}
}
```

## Json Response

Use JsonResponse to return json `content-type` in `doExecute()`

``` php
use Windwalker\Http\Response\JsonResponse;

// in doExecute()

$this->response = new JsonResponse;

// The returned data will auto convert to json
return ['foo' => 'bar'];
```

Use trait to auto prepare json response.

``` php
use Windwalker\Core\Controller\Traits\JsonResponseTrait;

class GetController extends AbstractController
{
    use JsonResponseTrait;
```

Use `JsonApiTrait` to return a standard api format:

``` php
use Windwalker\Core\Controller\Traits\JsonApiTrait;

class GetController extends AbstractController
{
    use JsonApiTrait;

    public function doExecute()
    {
        $this->addMessage('Hello');

        return return ['foo' => 'bar'];
    }
```

Output data:

``` php
{
    "success": true,
    "code": 200,
    "message": "Hello"
    "data": {
        "foo": "bar"
    }
}
```

## Validate Middleware

Add `ValidateErrorHandlingMiddleware` so controller can catch `ValidateFailException` to handle multiple invalid data messages.

``` php
use Windwalker\Core\Controller\Middleware\ValidateErrorHandlingMiddleware;
use Windwalker\Core\Repository\Exception\ValidateFailException;

// ...

protected $middlewares = [
    ValidateErrorHandlingMiddleware::class
];

protected function doExecute()
{
    $data = $this->input->getArray('data');

    $this->validate($data);

    return $this->repository->save($data);
}

protected function validate($data)
{
    // Set one message
    if (empty($data['name']))
    {
        throw new ValidateFailException('Please enter user name.');
    }

    // Set multiple messages
    if (...)
    {
        throw new ValidateFailException([
            'Message1',
            'Message2',
            'Message3',
        ]);
    }
}
```

## Controller Config

You can set some config in `$this->config` and pass it into View and Repository, then they will know everything about this controller.

``` php
// In doExecute()

$this->config->set('package.name', 'other_package_name');

$view->setConfig($this->config);
$repository->setConfig($this->config);

// Then View will find template from other package
```

If you use getter to get View and Repository, config will auto set into them.

Config is a Structure object, see: [Windwalker Structure](../more/structure.html)

## CSRF Token

Windwalker provides a simple CSRF token generator, please add this line to your HTML form:

``` html
<form action="..." method="post">
    
    <!-- ... -->
    
    <?php echo \Windwalker\Core\Security\CsrfProtection::input(); ?>
</form>
```

In Edge of Blade

``` php
@formToken()
```

This will generate a token input to your form. You can also add token to an URL.

``` html
<a href="flower/sakura?<?php echo \Windwalker\Core\Security\CsrfProtection::getFormToken(); ?>"></a>
```

Then just check token in your Controller.
 
``` php
use Windwalker\Core\Security\CsrfProtection;

// Validate or throw exception
CsrfProtection::validate();

// Validate or die
CsrfProtection::validate(true);

// Validate with message
CsrfProtection::validate([bool], 'Sorry your token is invalid');

// Only check and return boolean
if (! CsrfProtection::checkToken())
{
    throw new \RuntimeException('Invalid Token');
}
```

Use trait to auto check CSRF token:

``` php
use Windwalker\Core\Controller\Traits\CsrfProtectionTrait;

class SaveController extends AbstractController
{
	use CsrfProtectionTrait;

	// ...
```

## Utilities Methods

### Redirect

Use `setRedirect($url)` to tell controller redirect to other url after executed.

``` php
$this->setRedirect('pages.html');

// Then after executed, we can call `redirect()` to do redirect action.
$this->redirect();
```

Set Message when redirect:

``` php
$this->setRedirect('pages.html', 'Save success', 'info');
```

We can override redirect target anywhere when executing.

``` php
// In doExecute()

$repository->save($data);

$this->setRedirect('pages.html', 'Save success', 'success');

try
{
	$repository->saveRelations($data);
}
catch (Exception $e)
{
	// Override pervious url
	$this->setRedirect('edit.html', 'Save fail', 'error');

	return false;
}

return true;
```

We can also redirect instantly by set URL as first argument to `redirect()`

``` php
$this->redirect('http://foo.com/page.html');
```

### Add Flash Messages

Flash message is a disposable message in session, if we show it, these messages will be purged.

``` php
$this->addMessage('Message', 'type');

// Set multiple messages by array
$this->addMessage(['Message', 'Message2'], 'type');

// OR

$this->setRedirect('url.html', 'Message', 'type');
```

### Mute

If we set controller to mute, this controller will not add any messages:

``` php
$this->mute(true);

$this->addMessage('Message'); // This action no use
```

## HMVC

The Hierarchical-Repository-View-Controller (HMVC) pattern is a direct extension to the MVC pattern that manages 
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
		$child = new ChildController($this->input, $this->package, $this->container);
		
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

$child = new ChildController(null, $subContainer->get('package'), $subContainer);
```

### Use hmvc() Method

Windwalker provides a `hmvc()` method to make this step more quickly:

``` php
$result = $this->hmvc('Flower\Controller\Rose\SaveController', array('data' => $data));

// OR

$result = $this->hmvc(new ChildController, $this->input);
```

## Middlewares

Windwalker Controller supports middleware pattern, you can add middlewares in `middleware` property when class declaring:

``` php
class GetController extends AbstractController
{
	protected $middlewares = [
	    // The key: 800 is priority number
		800 => \Windwalker\Core\Controller\Middleware\JsonResponseMiddleware::class
	];

	// ...
```

Or add it in `init()` or `boot()` method before executed:

``` php
// ...

protected function init()
{
    parent::init();

    $this->addMiddleware(ValidateErrorHandlingMiddleware::class, PriorityQueue::HIGH + 10);
}

// ...
```

> Controller interface is different from application and package, it is not Psr7 invokable,
> so we must use another interface to execute middlewares.

### Create Custom Middleware

Use callback as middleware:

``` php
protected $middlewares = [
    500 => function ($data, $next)
    {
        // pre-process

        $result = $next->execute($data);

        // post-process
        // Controller will be binded as $this
        $this->response->getBody()->write('Hello');

        return $result;
    }
];
```

Or use class as middleware:

``` php
use Windwalker\Debugger\Helper\DebuggerHelper;
use Windwalker\Http\Response\JsonResponse;

class MyMiddleware extends AbstractControllerMiddleware
{
	public function execute($data = null)
	{

		$result = $this->next->execute($data);

		$this->controller->response->getBody()->write('Hello');

		return $result;
	}
}
```

Then register it to controller:

``` php
protected $middlewares = [
    800 => MyMiddleware::class
];
```

## Traits

Controller is an instance of `BootableTrait`, which can auto boot used traits.

Create a trait with a method named `boot{TraitName}()`:

``` php
trait MyTestTrait
{
    public function bootMyTestTrait()
    {
        $this->adMiddleware(...);
    }
}
```

Now use it in controller and it will be auto booted.

``` php
class GetController extends AbstractController
{
    use MyTestTrait;
}
```

### Built-in Traits

- `Windwalker\Core\Controller\Traits\JsonResponseTrait`
- `Windwalker\Core\Controller\Traits\JsonApiTrait`
- `Windwalker\Core\Controller\Traits\HtmlResponseTrait`
- `Windwalker\Core\Controller\Traits\CsrfProtectionTrait`
- `Windwalker\Core\Controller\Traits\CorsTrait`

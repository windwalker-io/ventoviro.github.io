---
layout: documentation.twig
title: Error Handling
redirect:
    2.1: more/error-handling

---

## Introduction

Windwalker's error handling system overrided the native PHP error handler to provide a flexible interface to fetch error message.

## Exception Handling

Most of time, we throw exception object to change the program process if error occurred:

```php
try
{
    if (/* Something error */) {
        throw new Exception('Error message');
    }
}
catch (Exception $e)
{
    // Handle error or redirect
}
```

If you didn't catch exception and throw it to the top level:

```php
// no catch() to fetch this exception
throw new Exception('Error message');
```

The exception you thrown will not be caught and shows a `Uncaught Exception error` in native PHP.

```php
Fatal error: Uncaught Exception: Error message in Command line code:xxx
```

Nothing happened, and there is no any information and stack trace for debugging.

Windwalker includes [Whoops](http://filp.github.io/whoops/) as default error handler, the uncaught exception will show this page:

![160331-0002](https://cloud.githubusercontent.com/assets/1639206/14169526/e84189b8-f75a-11e5-9fcc-85507bf3fe48.jpg)

To enable Pretty error page, you must open DEBUG mode, please change config `system.debug` to `1` or use `dev.php` to access your site.

See [Debugging and Logging](debugging.html)

### Built-in Error Page

Without DEBUG mode, Windwalker contains a default error handler to handle error page. Let's throw an Exception to show error page.

```php
throw new \Exception('Something wrong~~~');
```

![p-2016-07-09-003](https://cloud.githubusercontent.com/assets/1639206/16706741/2ca3ff68-45ea-11e6-8dac-1bc0328447cc.jpg)

Override error template to `simple` or `empty` in `config.yml`, so you can hide information in production site.

```yaml
# etc/config.yml

error:
    # `simple` template will only show plain text message.
    template: windwalker.error.simple

    # `empty` template will show blank page without anything
    template: windwalker.error.empty

    # The template engine, you can use `php`, `twig`, `blade`, `edge`
    engine: php
```

The Simple error page.

![p-2016-07-09-005](https://cloud.githubusercontent.com/assets/1639206/16706742/2ca4d9ec-45ea-11e6-8ca1-61790a8d608d.jpg)

## Custom Error Template

You are allow to create a custom pretty error page, for instance, most site will have a designed 404 page.
Override the `error.template` to your one:

```yaml
error:
    template: flower.error.default
    engine: edge
```

You are also allow to rewrite default error handler to render your own page:

```php
use Windwalker\Core\Error\ErrorHandler;
use Windwalker\Core\Widget\WidgetHelper;

/**
 * @param \Exception|\Throwable $e
 */
ErrorHandler::addHandler(function ($e)
{
    echo WidgetHelper::render('flower.error.default', ['e' => $e], 'edge');
}, 'default');
```

Use Package controller to render error page, so you are able to organize error page as a package MVC.

```php
ErrorHandler::addHandler(function ($e)
{
    echo PackageHelper::getPackage()->executeTask('Error/GetController', ['e' => $e])->getBody();
}, 'default');

// In src/Flower/Controller/Error/GetController.php
class GetController extends AbstractController
{
	protected function doExecute()
	{
		$data = [
			'e' => $this->input->getRaw('e')
		];

		return $this->renderView('error', 'flower.error.default', 'edge', $data);
	}
}
```

## ErrorHandler Class

Windwalker use `ErrorHandler` class to handle all custom error process.

### Register Error And Exception Handler

In Application, Windwalker use this code to register PHP error handler to it own.

```php
\Windwalker\Core\Error\ErrorHandler::register();

// Register shot-down function so you can catch fatal error
\Windwalker\Core\Error\ErrorHandler::register(true, null, true);
```

### Set Error Level

```php
ErrorHandler::register(true, E_ALL);
```

### Restore Error Handler

If you want to use other error handler, you can restore all error handler have set.

```php
\Windwalker\Core\Error\ErrorHandler::restore();
```

### Add More Handlers

You can log errors when every time error occurred. Add this line in `etc/web.php`:

```php
// ...

    'error' => [
		'handlers' => [
			'log' => \Windwalker\Core\Error\Handler\ErrorLogHandler::class
		]
	]
```

Now Windwalker will log error in `logs/error.log`

```log
[2016-07-09 08:52:18] error.ERROR: Something wrong~~~ {"code":0} []
```

You can write your own Log Handler by add callback as handler.

```php
/**
 * @param \Exception|\Throwable $e
 */
ErrorHandler::addHandler('log', function ($e)
{
    Logger::error('my-errorlog', $e->getMessage());
});
```

See [Logging](/debugging.html)

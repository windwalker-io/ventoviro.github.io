layout: documentation.twig
title: Error Handling

---

# Introduction

Windwalker has a matured error handling system, it override the native PHP error handler to provide a flexible interface to fetch error message.
 
# Exception Handling

Most of time, we throw exception object to change the program process if error occurred:

``` php
try
{
    if (/* Something error */)
    {
        throw new Exception('Error message');
    }
}
catch (Exception $e)
{
    // Handle error or redirect
}
```

If you didn't catch exception and throw it to the top level:

``` php
// no catch() to fetch this exception
throw new Exception('Error message');
```

In native PHP, it will show this error message. Nothing happened, and there is no information for debugging.

``` php
Error message.
```

Windwalker includes [Whoops](http://filp.github.io/whoops/) as default error handler, the uncaught exception will show this page:
 
![p-2015-01-02-3](https://cloud.githubusercontent.com/assets/1639206/5594830/cad4448c-9299-11e4-940c-06e5d00ef637.jpg)

## Built-in Error Page

If you don't want to use Whoops, Widwalker still have it own error handler. If we remove WhoopsProvider from Application:
 
``` php
// src/Windwalker/Web/Application

// ...

public function loadProviders()
{
    $providers = parent::loadProviders();

    // $providers['debug']    = new WhoopsProvider;
    $providers['event']    = new EventProvider;
    $providers['database'] = new DatabaseProvider;

    // ...
}
```

Now error page will be a simpler one.

![p-2015-01-02-4](https://cloud.githubusercontent.com/assets/1639206/5594840/88661bce-929a-11e4-93fd-5b60cda22f46.jpg)

# Close Error Handler

These two error handlers will only work if debug mode opened, if we close debug mode:
 
``` yaml
# etc/config.yml

system:
    debug: 0
    timezone: 'UTC'
```

The error handler will fallback to PHP native.

# Custom Error Template

Windwalker error handler also use [Renderer](widget-renderer.html) to render page. If you want to show a clean error page only
contains a title which tell user: **Something error, please contact administrator**, you can override the error template.

Add file to `/templates/windwalker/error/default.php`:

``` php
<?php
// templates/windwalker/error/default.php`

$exception = $data->exception;
?>
<div style="text-align: center">
	<h1>Error: <?php echo $exception->getCode(); ?></h1>
	<h2>Please contact administrator</h2>
</div>
```

Or change template name:

``` php
\Windwalker\Core\Error\ErrorHandler::setErrorTemplate('my.error.tmpl.file');
```

# ErrorHandler Class

Windwalker use `ErrorHandler` class to handle all custom error process.

## Register Error And Exception Handler

In Application, Windwalker use this code to register PHP error handler to it own.

``` php
\Windwalker\Core\Error\ErrorHandler::register();
```

## Restore Error Handler

If you want to use other error handler, you can restore all error handler have set.

``` php
\Windwalker\Core\Error\ErrorHandler::restore();
```

## Override It

Override Built-in ErrorHandler with our own logic.

``` php
use Windwalker\Core\Error\ErrorHandler;

class MyErrorHandler extends ErrorHandler
{
	/**
	 * The error handler.
	 *
	 * @param   integer  $code     The level of the error raised, as an integer.
	 * @param   string   $message  The error message, as a string.
	 * @param   string   $file     The filename that the error was raised in, as a string.
	 * @param   integer  $line     The line number the error was raised at, as an integer.
	 * @param   mixed    $context  An array that contains variables in the scope which this error occurred.
	 *
	 * @throws  \ErrorException
	 * @return  void
	 *
	 * @see  http://php.net/manual/en/function.set-error-handler.php
	 */
	public static function error($code ,$message ,$file, $line, $context)
	{
		$content = sprintf('%s. File: %s (line: %s)', $message, $file, $line);

		throw new \ErrorException($content, $code, 1, $file, $line);
	}
}
```

Then register it:

``` php
MyErrorHandler::register();
```

Now all PHP error will throws ErrorException.
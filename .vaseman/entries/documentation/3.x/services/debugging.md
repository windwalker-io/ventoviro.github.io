---
layout: documentation.twig
title: Debugging and Logging
redirect:
    2.1: more/debugging

---

## DEBUG Mode

Windawlker contains a development mode, you can enable it by setting config `system.debug` to `1`, or use `dev.php` to access your site.

You will see a debug console at the bottom of page.

![160331-0003](https://cloud.githubusercontent.com/assets/1639206/14169792/6aabee92-f75c-11e5-9614-1512c6021d77.jpg)

### Remote Access

By default, `dev.php` only support `localhost`, you can add allow IPs to `secret.yml` so that you can open dev mode from remote.

``` yaml
dev:
    allow_ips:
        - 127.0.0.1
        - 'fe80::1'
        - '::1'
        - 123.456.654.321 ## Add your ip
```

### Close Debug Console

Debug console will auto push to page when system close, but sometimes we won't want it to show if in ajax or api call.
Add this line to close it.

``` php
use Windwalker\Debugger\Helper\DebuggerHelper;

class AjaxController extends Controller
{
	protected function doExecute()
	{
	    // Close debug console
		DebuggerHelper::disableConsole();

		$this->app->response->setMimeType('application/json');

		return json_encode(['foo' => 'bar']);
	}
}
```

## Debugger Package

Click buttons on debug console, you will enter the Debugger page.

![160331-0004](https://cloud.githubusercontent.com/assets/1639206/14169994/a78ec270-f75d-11e5-81b9-b14317b32fbe.jpg)

Windwlker will log last 100 page requests information to help us track system process.

![160331-0005](https://cloud.githubusercontent.com/assets/1639206/14170049/f8b897d4-f75d-11e5-9cd1-3380e88854fb.jpg)

### Log your Custom Content

If you need to log some important information to debug, you can use `DebugHelper` to add custom data.

``` php
DebuggerHelper::addCustomData('My data', "It's not who I am underneath, but it's what I do that defines me.");
```

Go to system page.

![system](https://cloud.githubusercontent.com/assets/1639206/14170215/ff20a048-f75e-11e5-992e-9fc8eaa4105c.jpg)

You can add multiple data with same key, it will be wrapped with array.

``` php
foreach ($list as $item)
{
    DebuggerHelper::addCustomData('test-data', $item);
}
```

### Debug Flash Messages

If you queued flash messages to session, you can see them in debug console before they showed in page:

``` php
// In controller

$this->addMessage(['Hello', 'World'], 'warning');
$this->addMessage('Foo Bar', 'danger');

// Do not show them in page, we stop application.
exit();
```

Now you can see queued messages in debug console.

![p-2016-10-12-002](https://cloud.githubusercontent.com/assets/1639206/19293598/5ec61130-9058-11e6-807f-107aa3cc3acf.jpg)

### Custom Debug Messages

Sometimes you must log some debug information to help you developing, you can use debug messages:

``` php
// In controller

DebuggerHelper::addMessage('Test message1');
DebuggerHelper::addMessage('Test message2', 'warning');
DebuggerHelper::addMessage('Test message3', 'danger');
```

These messages will also show in debug console:

![p-2016-10-12-001](https://cloud.githubusercontent.com/assets/1639206/19293650/a9a124ec-9058-11e6-81d3-003d047147de.jpg)

## Mail Tester

Go to `dev.php/_debugger/mail` and you will see a mail tester.

To test a mail layout, you must [pre-define](./mailer.html) a mail message class and put mail template in global `/templates` folder.

Then add `?class=Flower\Mail\CheckoutMessage` to URL and you can test your mail layout:

![p-2016-10-11-001](https://cloud.githubusercontent.com/assets/1639206/19275116/33e2a6d4-9005-11e6-988f-1014566562f6.jpg)

## Logging

Windwalker includes [Monolog](https://github.com/Seldaek/monolog) to help us log debugging information.

``` php
use Windwalker\Core\Logger\Logger;

Logger::log('flower', Logger::INFO, 'log text');
```

The first argument is channel & log file name, this will create a file at `logs/flower-xxxx-xx-xx.log` with one line:

> By default, Windwalker use monolog RotatingStreamHandler so all file will only save logs for one day.
  And the max files number is 7, older log files will de deleted.

```
## logs/flower.log

[2016-04-01 10:17:07] flower.INFO: log text [] []
```

Add some data at line last.

``` php
Logger::log('flower', Logger::INFO, 'log text', array('foo' => 'bar'));
```

```
## logs/flower.log

[2016-04-01 10:18:42] flower.INFO: log text {"foo":"bar"} []
```

We can use proxy methods to log data:

``` php
Logger::debug('channel', 'log text');
Logger::info('channel', 'log text');
Logger::notice('channel', 'log text');
Logger::warning('channel', 'log text');
Logger::error('channel', 'log text');
Logger::critical('channel', 'log text');
Logger::alert('channel', 'log text');
Logger::emergency('channel', 'log text');
```

Log multiple channels:

```php
Logger::info(['error', 'message'], 'Something wrong');
```

Log multiple strings:

```php
Logger::info('error', [
    'error 1',
    'error 2',
    'error 3'
]);
```

## Log Levels

The log levels are described by [RFC5424](http://tools.ietf.org/html/rfc5424)

| Name | Code | Description |
| ---- | ---- | ----------- |
| DEBUG | 100 | Detailed debug information. |
| INFO | 200 | Interesting events. Examples: User logs in, SQL logs. |
| NOTICE | 250 | Normal but significant events. |
| WARNING | 300 | Exceptional occurrences that are not errors. Examples: Use of deprecated APIs, poor use of an API, undesirable things that are not necessarily wrong. |
| ERROR | 400 | Runtime errors that do not require immediate action but should typically be logged and monitored. |
| CRITICAL | 500 | Critical conditions. Example: Application component unavailable, unexpected exception. |
| ALERT | 550 | Action must be taken immediately. Example: Entire website down, database unavailable, etc. This should trigger the SMS alerts and wake you up. |
| EMERGENCY | 600 | Emergency: system is unusable. |

You can create a channel with level code, all levels below this level will not logged.

``` php
Logger::createChannel('sakura', Logger::ERROR);

Logger::debug('sakura', 'log text'); // This won't log

Logger::alert('sakura', 'log text'); // This will log
```

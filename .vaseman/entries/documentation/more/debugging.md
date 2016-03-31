---
layout: documentation.twig
title: Debugging
---

# DEV Mode

Windawlker contains a development mode, you can enable it by setting config `system.debug` to `1`, or use `dev.php` to access your site.

You will see a debug console at the bottom of page.

![160331-0003](https://cloud.githubusercontent.com/assets/1639206/14169792/6aabee92-f75c-11e5-9614-1512c6021d77.jpg)

## Remote Access

By default, `dev.php` only support `localhost`, you can add allow IPs to `secret.yml` so that you can open dev mode from remote.

``` yaml
dev:
    allow_ips:
        - 127.0.0.1
        - 'fe80::1'
        - '::1'
        - 123.456.654.321 # Add your ip
```

## Close Debug Console

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

# Debugger Package

Click buttons on debug console, you will enter the Debugger page.

![160331-0004](https://cloud.githubusercontent.com/assets/1639206/14169994/a78ec270-f75d-11e5-81b9-b14317b32fbe.jpg)

Windwlker will log last 100 page requests information to help us track system process.

![160331-0005](https://cloud.githubusercontent.com/assets/1639206/14170049/f8b897d4-f75d-11e5-9cd1-3380e88854fb.jpg)

## Log your Custom Content

If you need to log some important information to debug, you can use `DebugHelper` to add custom data.

``` php
DebuggerHelper::addCustomData('My data', "It's not who I am underneath, but it's what I do that defines me.");
```

Go to system page.

![system](https://cloud.githubusercontent.com/assets/1639206/14170215/ff20a048-f75e-11e5-992e-9fc8eaa4105c.jpg)

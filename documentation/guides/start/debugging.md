---
layout: doc
---

# Debugging

## Enter Debug Mode

If you need to enter debug mode, such as viewing DB Queries, complete error messages, or want to prevent CSS/JS caching, you can enter debug mode.

The simplest way is to add /dev.php at the end of the website URL.

For example:
- The URL: http://localhost/hello/www/sakura/list
- Change to: http://localhost/hello/www/dev.php/sakura/list.

In this case, we are using PHP server: `http://localhost:8000`, we can simply add `dev.php` after ths host: `http://localhost:8000/dev.php`

This will enter debug mode.

![debug console](https://github.com/lyrasoft/ww4tut/assets/1639206/13a93c09-eba2-4d65-80c1-944ccdcc6bf7)

When an error occurs, it can also display complete error messages.

![error page](https://github.com/lyrasoft/ww4tut/assets/1639206/3e89efcb-a6b1-4594-85b6-4107632d00ee)

### Force Debug Mode

Add `APP_DEBUG=1` to `.env` file to force enable debug mode without accessing `dev.php`.

## Web Profiler

Click the debug console at the bottom of page. You will enter the Windwalker Debugger interface.

![system](https://github.com/lyrasoft/ww4tut/assets/1639206/d3d03b2b-17f6-4053-95a2-eb1531692ea3)

You can see many profiler data which is collected from Windwalker process, to help you debug or inspect system issues. If you want to see the data from previous actions, go to `Dashboard` and choose the page data you want.

![dashboard](https://github.com/lyrasoft/ww4tut/assets/1639206/d18f00cc-da6f-4a5d-b1b3-01800bf3dfac)

## Debugger Configuration

### THe Core Debugger

Open `etc/packages/debugger.php` to configure debug system, this debug system is provided by `windwalker/core` package.

#### `profiler_disabled`

Set to `true` to force disable debug profiler.

#### `cache.max_files`

To configure how many collected profiler data caches at this project, default is `100`.

### The Whoops

Windwalker use [flip/whoops](https://github.com/filp/whoops) package to render error page. You can configure whoops through `etc/conf/whoops.php`:

#### `editor`

To configure the editor/IDE software that can open file when you click the file path in error page, you can simply add `WHOOPS_EDITOR` to `.env` to override this config.

![editor](https://github.com/lyrasoft/ww4tut/assets/1639206/b3a91c40-2d4e-4874-b169-1ccdcf3c477a)

Default is `phpstorm`, you may change it to `vscode` or any editor which supports open by `{editor}://` URL scheme.

#### `hidden_list`

Whoops will display some sensitive data from your `$_SERVER` super global variables. This config provides a list to hide these sensitive data.

```php
    // ...

    'hidden_list' => [
        '_ENV' => array_keys($_ENV ?? []),
        '_SERVER' => array_merge(
            [
                'PATH',
                'SERVER_SOFTWARE',
                'DOCUMENT_ROOT',
                'CONTEXT_DOCUMENT_ROOT',
                'SERVER_ADMIN',
                'SCRIPT_FILENAME',
                'REMOTE_PORT',
            ],
            array_keys($_ENV ?? [])
        )
    ],
```


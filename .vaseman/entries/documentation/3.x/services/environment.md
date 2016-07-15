---
layout: documentation.twig
title: Environment
redirect:
    2.1: more/environment

---

Environment object help us get browser and server information.

## Get Environment Object

``` php
// Get from Application
$env = $app->environment;

// Get from Ioc
$env = \Windwalker\Ioc::getEnvironment();

// Get from container
$env = $this->container->get('environment');
```

Environment contains two objects, `Browser` to get browser information, and `Platform` to get server information.

## The Platform Object

Get platform object:

``` php
$platform = $env->platform;

// OR

$platform = $app->platform;
```

### Detect Running Environment

``` php
$platform->isWeb();
$platform->isCli();
```

Same as:

``` php
use Windwalker\Environment\PhpHelper;

PhpHelper:isWeb();
PhpHelper::isCli();
```

### Detect System OS

Get server OS information. The `getOS()` return first 3 letters from `getUname()`. The Uname is same as `PHP_OS`.
([List of `PHP_OS`](https://gist.github.com/asika32764/90e49a82c124858c9e1a))

``` php
$platform->getOS();  // WIN, UNI, LIN, DAR ... etc.
$platform->getUname(); // PHP_OS

$platform->isWin();
$platform->isLinux();
$platform->isUnix();
```

### Get System Path

``` php
$platform->getWorkingDirectory();

$platform->getRoot();

$platform->getEntry();

$platform->getServerPublicRoot();

$platform->getRequestUri();
```

#### getWorkingDirectory()

If in Web environment, this method return running script directory.

If in CLI environment, this method return current terminal working dir(same as `pwd` command).

#### getRoot(`[$full = true]`)

Get running script root directory.

Set first argument to `false`, will return relative path from DocumentRoot in Web,
or return relative path from working dir in CLI.

#### getEntry(`[$full = true]`)

Get running script file name.

Set first argument to `false`, will return relative path from DocumentRoot in Web,
or return relative path from working dir in CLI.

#### getServerPublicRoot()

Return the Http server DocumentRoot, same as `$_SERVER['DOCUMENT_ROOT']`.

#### getRequestUri(`[$withParams = true]`)

Call this method will return the URI path as `$_SERVER['REQUEST_URI']` with Http queries.

```
/path/foo/?bar=baz
```

Set first argument to `false` will return request path without params, same as `$_SERVER['PHP_SELF']`.

```
/path/foo/
```

### Get Request Information

``` php
$platform->getHost();
$platform->getScheme();
$platform->getPort();
```

## The PhpHelper

PhpHelper provides some useful methods to know about our PHP status.

### Check PHP Running Environment

``` php
PhpHelper::isWeb();
PhpHelper::isCli();
PhpHelper::isHHVM();
PhpHelper::isPHP();
PhpHelper::isEmbed();
```

### Get PHP Version

If is PHP, return `PHP_VERSION`. If is HHVM, return `HHVM_VERSION`.

``` php
PhpHelper::getVersion()
```

### Set Debug Mode

`setStrict()` will set `error_reporting()` to `-1`.

`setMuted()` will set `error_reporting()` to `0`.

``` php
PhpHelper::setStrict();
PhpHelper::setMuted();
```

### Check Extensions

``` php
PhpHelper::hasXdebug();
PhpHelper::hasPcntl();
PhpHelper::hasCurl();
PhpHelper::hasMcrypt();
```

## The Browser Object

Get browser object:

``` php
$browser = $env->browser;

// OR

$browser = $app->browser;
```

### Detect Browser

``` php
// Check is IE
$browser->getBrowser() == Browser::IE;
```

Available Browser Detection

- IE
- EDGE
- FIREFOX
- CHROME
- SAFARI
- OPERA
- ANDROID_TABLET
- VIVALDI

### Detect Browser Version

``` php
$version = $browser->getBrowserVersion();

// Check version
$version >= 11;
```

### Detect Browser Engine

``` php
$engine = $browser->getEngine();

// Check engine
$engine == Browser::ENGINE_WEBKIT
```

Available Engines

- ENGINE_TRIDENT
- ENGINE_EDGE_HTML
- ENGINE_WEBKIT
- ENGINE_GECKO
- ENGINE_PRESTO
- ENGINE_KHTML
- ENGINE_AMAYA

### Detect User's OS or Device

``` php
$device = $browser->getDevice();

// Check platform
$device == Browser::DEVICE_ANDROID
```

Available Platforms

- DEVICE_WINDOWS
- DEVICE_WINDOWS_PHONE
- DEVICE_WINDOWS_CE
- DEVICE_IPHONE
- DEVICE_IPAD
- DEVICE_IPOD
- DEVICE_MAC
- DEVICE_BLACKBERRY
- DEVICE_ANDROID
- DEVICE_LINUX

### Other Detection

``` php
$browser->isRobot();
$browser->isMobile();
$browser->getLanguages();
$browser->getEncodings();
$browser->isSSLConnection();
$browser->getUserAgent();
```

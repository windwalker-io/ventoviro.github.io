---

layout: rad.twig
title: Asset Minify

---

## Auto Minify Css & JS

Windwalker use `AssetManager` object to control all CSS & JS includes, then render all `<link>` and `<script>`
tags in HTML.

We can minify all asset files and combine them to one file that can speed up page downloading.
  
Use `CssMinifyListener` and `JsMinifyListener` in `etc/app/web.php`

``` php
// etc/app/web.php

// ...

	'listeners' => [
		//...
		
		700 => \Phoenix\Listener\CssMinifyListener::class,
		800 => \Phoenix\Listener\JsMinifyListener::class
	],
	
```

Now every page will auto compress included css & js files to one file.

``` html
<!DOCTYPE html>
<html lang="en-GB">
<head>

    <title>Sakura Edit</title>

    <!-- ... -->
    
    <link rel="stylesheet" href="/path/site/www/asset/min/15d2969be82c4ac02c53fe8ead5600fd.css?f2d3c5f7211348644ed0a74575011f6c" />
    
    <script src="/path/site/www/asset/min/e2f3ce2ebbd95d65021ce52872f5e355.js?f2d3c5f7211348644ed0a74575011f6c"></script>
    
```

You must modify your `.gitignore` file to ignore `www/asset/min` folder, and add a cron to clear cache files.



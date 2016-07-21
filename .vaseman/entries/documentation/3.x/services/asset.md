---
layout: documentation.twig
title: Asset Manager

---

## Get Assets Path

Windwalker stores asset files in `/www/asset`, we can get the path or full URL of this folder:

``` php
use Windwalker\Core\Asset\Asset;

echo Asset::path(); // /path/to/windwalker/asset
echo Asset::root(); // http://domain.com/path/to/windwlaker/asset
```

You can simply use `$asset->path` in view template:

``` php
<script src="<?php echo $asset->path; ?>/js/bootstrap.js"></script>
```

Or use method call:

``` php
<script src="<?php echo $asset->path('js/bootstrap.js'); ?>"></script>
```

### Set Full Asset Uri

Add `asset.uri` to config if you store assets in cloud storage:

``` yaml
asset:
    uri: https://foo.s3.amazonaws.com/assets
```

Now `$asset->path` and `$asset->root` will be `https://foo.s3.amazonaws.com/assets`.

## Use Asset to Include CSS & JS

``` php
use Windwalker\Core\Asset\Asset;

Asset::addCSS('css/bootstrap.css');
Asset::addJS('js/bootstrap.js');

// In template
$asset->addCSS('css/bootstrap.css');
$asset->addJS('js/bootstrap.js');
```

Windwalker will auto includes these files in `<head>`:

``` php
<link rel="stylesheet" href="/windwalker/www/asset/css/bootstrap.css?ee8f77834fabe4188265a599a77f2c21" />

<script src="/windwalker/www/asset/js/bootstrap.js?ee8f77834fabe4188265a599a77f2c21"></script>
```

If you are not use default template, you must confirm your template has rendered styles and scripts:

``` php
<?php echo $asset->renderStyles(true); ?>

<?php echo $asset->renderScripts(true); ?>
```

### Include Compressed Files

You can add `.min` to file name, Windwalker will auto check compressed file exists or not, and will fallback to normal file if
there has no min file.

And in DEBUG mode, Windwalker will force include uncompressed file if exists, otherwise fallback to min file.

``` php
// If `bootstrap.css` exists but `bootstrap.min.css` not
Asset::addCSS('css/bootstrap.min.css'); // bootstrap.css
Asset::addCSS('css/bootstrap.css'); // bootstrap.css

// If `bootstrap.min.css` exists but `bootstrap.css` not
Asset::addCSS('css/bootstrap.min.css'); // bootstrap.min.css
Asset::addCSS('css/bootstrap.css'); // bootstrap.min.css

// If both exists in DEBUG mode
Asset::addCSS('css/bootstrap.min.css'); // bootstrap.css
Asset::addCSS('css/bootstrap.css'); // bootstrap.css

// If both exists and not in DEBUG mode
Asset::addCSS('css/bootstrap.min.css'); // bootstrap.min.css
Asset::addCSS('css/bootstrap.css'); // bootstrap.min.css
```

## Alias

If you want to override an asset's URL, for instance, to modify a 3rd party scripts, you can use asset alias:

``` php
Asset::alias('js/my-grid.js', 'phoenix/js/grid.js');

Asset::addJS('phoenix/js/grid.min.js');
```

Now Windwlaker will include `js/my-grid.js` to HTML, don't worry about `.min`, AssetManager will normalize the path.

## Asset Versions

Windwalker auto add version hash string to every asset URL. In DEBUG mode, this version string will always refresh
after page reload. And in normal mode, AssetManager will hash version by the modified time of every asset files to make sure
version not changed, but can be refresh if any file has been modified.

You can add your own version to specific asset file:

``` php
Asset::addCSS('css/bootstrap.css', md5('1.0.5'));
```

Or add an md5 sum in cache folder, so all assets will use this cached version that make better performance.

``` bash
$ php windwlaker asset makesum
Create SUM: d9edf273fc270b99e1c5a0f5d9a96b3d at {ROOT}/cache/asset/MD5SUM
```

> You must run this command after every new version of you system deployed, otherwise the end users' browser may cache
the old assets files and will break your front-end application.

## Asset Template

Sometimes your Widget need push a JS template to the bottom of `<body>`, we can wrap this template by `AssetTemplate`:

``` php
<script>
    alert(jQuery('#hello').html());
</script>

<?php $asset->getTemplate()->startTemplate('asset.name'); ?>
<script id="hello" type="text/template">
    <h1>Hello <?php echo $content; ?></h1>
</script>
<?php $asset->getTemplate()->endTemplate(); ?>
```

Make sure your template has render asset template as bottom of body:

``` php
...

<?php echo $asset->getTemplate()->renderTemplates(); ?>
</body>
```

Simple tag in Edge and Blade:

``` php
@assetTemplate('asset.name')
<script id="hello" type="text/template">
    <h1>Hello {{ $content }}</h1>
</script>
@endTemplate()
```

## Asset Dependency

`Windwalker\Core\Asset\AbstractScript` is a dependency manager, we can create methods as dependency handler and call it anywhere.

This is an example to load a `sakura.js` and init it for different HTML selectors, and it is dependent on `flower.js`.

``` php
namespace Flower\Script;

use Windwalker\Core\Asset\AbstractScript;

class FlowerScript extends AbstractScript
{
    public static function jquery()
    {
        if (!static::inited(__METHOD__))
        {
            static::addJS('js/jquery/jquery.min.js');
        }
    }

	public static function flower()
	{
		if (!static::inited(__METHOD__))
		{
			// flower.js needs jquery.js first
			static::jquery();

			// If we need other scripts, call them first.
			OtherScript::core();

			static::addJS('js/flower.min.js');
			static::addCSS('js/flower.min.css');
		}
	}

	public static function sakura($selector = '.hasSakura', $options = array())
	{
	    $args = get_defined_vars();

		// Include asset file first.
		if (!static::inited(__METHOD__))
		{
			// sakura.js needs flower.js first
			static::flower();

			// If not in debug mode, Windwalker will auto get min file instead.
			static::addJS('sakura.js');
		}

		// Call only once with same arguments
		if (!static::inited(__METHOD__, $args))
		{
			$defaultOptions = array(
				'foo' => 'bar',
				'callback' => '\\function () {}' // Start with \\ will not be quoted
			);

			// Recursive merge options to defaultOptions and print a JS option string.
			$options = $asset::getJSObject($defaultOptions, $options);

			$js = <<<JS
jQuery(document).ready(function($) {
    $('$selector').sakuraInit($options);
});
JS;

			// Add inline JS in <head>
			static::internalJS($js);
		}
	}
}
```

Now use this code to include `sakura.js` every where:

``` php
\Flower\Script\FlowerScript::sakura('.mySakura', array('foo' => 'baz'));
```

## Sync Package Assets

Put your package asset files to `{package}/Resources/asset`, then you can run this command to sync asset files to
web public root.

``` bash
$ php windwalker asset sync
```

Windwalker will make a symbol link to your package asset folder, if you are using Windows, you must do this with Administrator access.
You can also add `--hard` to hard copy all asset files to public folder.

Change public asset folder by modifying `etc/config.yml`:

``` yaml
asset:
    folder: asset
```

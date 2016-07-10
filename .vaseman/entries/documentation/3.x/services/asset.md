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

``` html
<script src="<?php echo $asset->path; ?>/js/bootstrap.js"></script>
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

``` html
<link rel="stylesheet" href="/windwalker/www/asset/css/bootstrap.css?ee8f77834fabe4188265a599a77f2c21" />

<script src="/windwalker/www/asset/js/bootstrap.js?ee8f77834fabe4188265a599a77f2c21"></script>
```

If you are not use default template, you must confirm your template has rendered styles and scripts:

``` html
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

``` html
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

``` html
...

<?php echo $asset->getTemplate()->renderTemplates(); ?>
</body>
```

Simple tag in Edge and Blade:

``` blade
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
	public static function core()
	{
		if (!static::inited(__METHOD__))
		{
			// flower.js need jquery.js first
			JQueryScript::core();

			static::addJS('flower.min.js');
			static::addCSS('flower.min.css');
		}
	}

	public static function sakura($selector = '.hasSakura', $options = array())
	{
		// Include asset file first.
		if (!static::inited(__METHOD__))
		{
			// We need flower.js first
			static::core();

			// If not in debug mode, Windwalker will auto get min file instead.
			static::addJS('sakura.js');
		}

		// Call only once if arguments are same
		if (!static::inited(__METHOD__, func_get_args()))
		{
			$defaultOptions = array(
				'foo' => 'bar',
				'callback' => '\\function () {}' // Start with \\ will not be quote
			);

			// Recursive merge options to defaultOptions.
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

Now use this code to include Bootstrap Calendar every where:

``` php
\Flower\Script\FlowerScript::sakura('.mySakura', array('foo' => 'baz'));
```



layout: documentation.twig
title: Languages

---

# Introduction

Windwalker use Language package to handle i18n localise. There is a `language` configuration in `/etc/config.yml`:
 
``` yaml
language:
    debug: 0
    locale: zh-TW
    default: en-GB
    format: ini
    path: resources/languages
```

`locale` means current language, and `default` will tell language object that if current translate not exists, fallback to which language as default.

Format is the default file format, but we can still load other format in the runtime.

# Using Language to Translate String

Windwalker language object has a Facade as proxy, just call this static class to use.

``` php
use Windwalker\Core\Language\Language;

// Load en-GB/main.ini file first
Language::load('main');

// Translate
echo Language::translate('WINDWALKER_HELLO_MESSAGE');
```

Add language:

``` ini
; resources/languages/en-GB/main.ini

WINDWALKER_HELLO_MESSAGE="Hello World"
```

The result will be:

``` html
Hello World
```

There is a short alias of `translate()`:

``` php
echo Language::_('WINDWALKER_HELLO_MESSAGE');
```

# Locale and Default Languages

We set locale to `zh-TW` and default to `en-GB`, then create ini language files:

``` ini
; resources/languages/en-GB/flower.ini

WINDWALKER_LANGUAGE_TEST_FLOWER="Flower"
WINDWALKER_LANGUAGE_TEST_SAKURA="Sakura"
```

``` ini
; resources/languages/zh-TW/flower.ini

WINDWALKER_LANGUAGE_TEST_FLOWER="花"
```

And load them.

``` php
Language::load('flower');
```

Translate string:

``` php
// zh-TW has this language key, so it will be translated
Language::translate('WINDWALKER_LANGUAGE_TEST_FLOWER'); // 花

// This key not exists in zh-TW, use en-GB as default
Language::translate('WINDWALKER_LANGUAGE_TEST_SAKURA'); // Sakura
```

## Key Format

All language key will be normalised to lowercase and separated by dot (`.`).

These cases all get same result:

``` php
Language::translate('WINDWALKER_LANGUAGE_TEST_FLOWER'); // 花
Language::translate('WINDWALKER_language_TEST FLOWER'); // 花
Language::translate('windwalker.language.test.flower'); // 花
Language::translate('Windwalker Language Test Flower'); // 花
Language::translate('Windwalker Language, Test Flower~~~!'); // 花

// All keys will be normalise to 'windwalker.language.test.flower'
```

## Replace String

Use `sprintf()` method.

``` ini
WINDWALKER_LANGUAGE_TEST_BEAUTIFUL_FLOWER="The %s is beautiful~~~!!!"
```

``` php
Language::sprintf('WINDWALKER_LANGUAGE_TEST_BEAUTIFUL_FLOWER', 'Sunflower');

// Result: The Sunflower is beautiful~~~!!!
```

## Plural String

Create a Localise class:

``` php
// An example of EnGB Localise
namespace Windwalker\Language\Localise;

class EnGBLocalise implements LocaliseInterface
{
	public function getPluralSuffix($count = 1)
	{
		if ($count == 0)
		{
			return '0';
		}
		elseif ($count == 1)
		{
			return '';
		}

		return 'more';
	}
}
```

And prepare this language keys.

``` ini
WINDWALKER_LANGUAGE_TEST_SUNFLOWER="Sunflower"
WINDWALKER_LANGUAGE_TEST_SUNFLOWER_0="No Sunflower"
WINDWALKER_LANGUAGE_TEST_SUNFLOWER_MORE="Sunflowers"
```

Now we can translate plural string by `plural()`.

``` php
Language::plural('Windwalker Language Test Sunflower', 0); // No Sunflower
Language::plural('Windwalker Language Test Sunflower', 1); // Sunflower
Language::plural('Windwalker Language Test Sunflower', 2); // Sunflowers
```

# If Language Key Not Exists

Language object will return raw string which we send into it.

``` php
echo Language::translate('A Not Translated String');
echo "\n";
echo Language::translate('A_NOT_TRANSLATED_STRING');
```

Result:

```
A Not Translated String
A_NOT_TRANSLATED_STRING
```

# Using Other Formats

Change the `format` property from config.

## Yaml

Yaml language file can write as nested structure.

``` yaml
windwalker:
    language.test:
        sakura: Sakura
        olive: Olive
```

``` php
Language::translate('windwalker.language.test.sakura'); // Sakura
Language::translate('WINDWALKER_LANGUAGE_TEST_OLIVE'); // Olive
```

## Json

``` json
{
	"windwalker" : {
		"language-test" : {
			"sakura" : "Sakura",
			"olive" : "Olive"
		}
	}
}
```

The usage same as yaml.

``` php
Language::translate('windwalker.language.test.sakura'); // Sakura
Language::translate('WINDWALKER_LANGUAGE_TEST_OLIVE'); // Olive
```

## PHP

``` php
<?php

return array(
	'WINDWALKER_LANGUAGE_TEST_FLOWER' => "Flower",
	'WINDWALKER_LANGUAGE' => array(
			'TEST' => array(
				'SAKURA' => "Sakura"
			)
		)
);
```

The usage same as yaml.


``` php
Language::translate('windwalker.language.test.sakura'); // Sakura
Language::translate('WINDWALKER_LANGUAGE_TEST_OLIVE'); // Olive
```

> NOTE: We'll support to load different formats in the runtime soon.

# Load Package Languages

Package language file located at `YourPackage/Languages/xx-XX/language-name.ini`.

So we can load package languages by second argument:

``` php
// Load en-GB/sakura.ini and zh-TW/sakura.ini from Flower package
Language::load('sakura', 'flower');
```

# Used Keys

``` php
// Get keys which have been used.
Language::getUsed();
```

# Debugging

## Debug Mode
 
``` php
Language::setDebug(true);
```

We can get non-translated keys in debug mode.

``` php
echo Language::translate('A Translation Exists String');
echo "\n";
echo Language::translate('A Not Translated String');


```

And the output will be:

``` html
**A Translation Exists String**
??A Not Translated String??
```

## Get Orphans

Orphans is the language string you used but haven't translated.

``` php
Language::setDebug(true);

// Run your applications

$orphans = Language::getOrphan(); // Array([0] => A Not Translated String);
```

## Check Loaded Languages

``` php
$config = \Windwalker\Ioc::getConfig();

$config->get('language.loaded');
```

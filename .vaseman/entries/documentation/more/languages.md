---
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
use Windwalker\Core\Language\Translator;

// Load en-GB/main.ini file first
Translator::loadFile('main');

// Translate
echo Translator::translate('windwalker.hello.message');
```

Add language:

``` ini
; resources/languages/en-GB/main.ini

windwalker.hello.message="Hello World"
```

The result will be:

``` html
Hello World
```

There is a short alias of `translate()`:

``` php
echo Translator::translate('windwalker.hello.message');
```

# Locale and Default Languages

We set locale to `zh-TW` and default to `en-GB`, then create ini language files:

``` ini
; resources/languages/en-GB/flower.ini

windwalker.language.test.flower="Flower"
windwalker.language.test.sakura="Sakura"
```

``` ini
; resources/languages/zh-TW/flower.ini

windwalker.language.test.flower="花"
```

And load them.

``` php
Translator::loadFile('flower');
```

Translate string:

``` php
// zh-TW has this language key, so it will be translated
Translator::translate('windwalker.language.test.flower'); // 花

// This key not exists in zh-TW, use en-GB as default
Translator::translate('windwalker.language.test.sakura'); // Sakura
```

## Key Format

All language key will be normalised to lowercase and separated by dot (`.`).

These cases all get same result:

``` php
Translator::translate('WINDWALKER_LANGUAGE_TEST_FLOWER'); // 花
Translator::translate('WINDWALKER_language_TEST FLOWER'); // 花
Translator::translate('windwalker.language.test.flower'); // 花
Translator::translate('Windwalker Language Test Flower'); // 花
Translator::translate('Windwalker Language, Test Flower~~~!'); // 花

// All keys will be normalise to 'windwalker.language.test.flower'
```

## Replace String

Use `sprintf()` method.

``` ini
windwalker.language.test.beautiful.flower="The %s is beautiful~~~!!!"
```

``` php
Translator::sprintf('windwalker.language.test.beautiful.flower', 'Sunflower');

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
windwalker.language.test.sunflower="Sunflower"
windwalker.language.test.sunflower="No Sunflower"
windwalker.language.test.sunflower="Sunflowers"
```

Now we can translate plural string by `plural()`.

``` php
Translator::plural('windwalker.language.test.sunflower', 0); // No Sunflower
Translator::plural('windwalker.language.test.sunflower', 1); // Sunflower
Translator::plural('windwalker.language.test.sunflower', 2); // Sunflowers
```

# If Language Key Not Exists

Language object will return raw string which we send into it.

``` php
echo Translator::translate('A Not Translated String');
echo "\n";
echo Translator::translate('A_NOT_TRANSLATED_STRING');
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
Translator::translate('windwalker.language.test.sakura'); // Sakura
Translator::translate('WINDWALKER_LANGUAGE_TEST_OLIVE'); // Olive
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
Translator::translate('windwalker.language.test.sakura'); // Sakura
Translator::translate('WINDWALKER_LANGUAGE_TEST_OLIVE'); // Olive
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
Translator::translate('windwalker.language.test.sakura'); // Sakura
Translator::translate('WINDWALKER_LANGUAGE_TEST_OLIVE'); // Olive
```

> NOTE: We'll support to load different formats in the runtime soon.

# Load Package Languages

Package language file located at `YourPackage/Languages/xx-XX/language-name.ini`.

So we can load package languages by second argument:

``` php
// Load en-GB/sakura.ini and zh-TW/sakura.ini from Flower package
Translator::loadFile('sakura', 'ini', 'flower');
```

# Used Keys

``` php
// Get keys which have been used.
Translator::getUsed();
```

# Debugging

## Debug Mode
 
``` php
Translator::setDebug(true);
```

We can get non-translated keys in debug mode.

``` php
echo Translator::translate('A Translation Exists String');
echo "\n";
echo Translator::translate('A Not Translated String');
```

And the output will be:

``` html
**A Translation Exists String**
??A Not Translated String??
```

## Get Orphans

Orphans is the language string you used but haven't translated.

``` php
Translator::setDebug(true);

// Run your applications

$orphans = Translator::getOrphans(); // Array([0] => A Not Translated String);
```

## Check Loaded Languages

``` php
$config = \Windwalker\Ioc::getConfig();

$config->get('language.loaded');
```

# Translate in View Template

Blade

``` php
@translate('windwalker.language.test.flower')
@sprintf('windwalker.language.test.flower', 'foo', 'bar')
@plural('windwalker.language.test.flower', 5)
```

Twig

``` php
{{ 'windwalker.language.test.flower' | lang }}
{{ 'windwalker.language.test.flower' | sprintf('foo', 'bar') }}
{{ 'windwalker.language.test.flower' | plural(5) }}
```

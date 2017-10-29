---

layout: rad.twig
title: Batch

---

## Add Category Batch

Open `Form/Articles/GridDefinition.php` and add new configuration.

``` php
// src/Blog/Admin/Form/Articles/GridDefinition.php

// ...

class GridDefinition extends AbstractFieldDefinition
{
	public function doDefine(Form $form)
	{
	    // ...

		$this->group('batch', function (Form $form)
		{
			// Add Category field
			$form->add('category_id', new CategoryListField)
				->label('Category')
				->option('-- Select Category --', '');

			// Language
			$this->list('language')
                ->label('Language')
                ->set('class', 'col-md-12')
                ->option('-- Select Language --', '')
                ->option('English', 'en-GB')
                ->option('Chinese Traditional', 'zh-TW');

				// Add FR-fr language
				->option('French', 'FR-fr');

				// ...
		});
	}
}
```

Then we can batch move articles items to other category or language.

![Imgur](https://i.imgur.com/azcMe62.jpg)

![Imgur](https://i.imgur.com/mZARv5f.jpg)

Or copy it.

![Imgur](https://i.imgur.com/Rfag9OT.jpg)

The title will auto increment.

![Imgur](https://i.imgur.com/2fzbOY8.jpg)

## Empty String

By default, empty string will be ignored and will not save in database. But if you really want to update an empty string into DB
to clear a field, use `__EMPTY__` as the value.

```php
// Add Category field
$form->add('category_id', new CategoryListField)
    ->label('Category')
    ->option('-- Select Category --', '')
    ->option('-- No category --', '__EMPTY__');
```

You can chane this mark in your update controller

```php
// src/Flower/Sakuras/Controller/Batch/UpdateContrtoller.php

class UpdateController extends AbstractUpdateController
{
    protected $emptyMark = '__MY_EMPTY_MARK__';
}
```

If you really want to use `__EMPTY__` as a string value to store into database, prefix a back slash `\`.

```php
// ...
    ->option('-- No category --', '\__EMPTY__');
```

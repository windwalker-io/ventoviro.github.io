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
				->option('-- Select Category --');

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

![Imgur](http://i.imgur.com/azcMe62.jpg)

![Imgur](http://i.imgur.com/mZARv5f.jpg)

Or copy it.

![Imgur](http://i.imgur.com/Rfag9OT.jpg)

The title will auto increment.

![Imgur](http://i.imgur.com/2fzbOY8.jpg)

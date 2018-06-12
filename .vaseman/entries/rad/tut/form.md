---

layout: rad.twig
title: Form & Edit

---

## Category Edit Page

Click Any category item, you will see edit page with a form and inputs.

We must modify this form to fit `categories` table.

Please open `src/Blog/Admin/Form/Category/EditDefinition.php` and change code to below.

``` php
// src/Blog/Admin/Form/Category/EditDefinition.php

namespace Blog\Admin\Form\Category;

use Phoenix\Form\PhoenixFieldTrait;
use Windwalker\Core\Form\AbstractFieldDefinition;
use Windwalker\Core\Language\Translator;
use Windwalker\Form\Form;

class EditDefinition extends AbstractFieldDefinition
{
    use PhoenixFieldTrait;

	public function doDefine(Form $form)
	{
		// Basic fieldset
		$this->fieldset('basic', function(Form $form)
		{
			// ID
			$this->hidden('id');

			// Title
			$this->text('title')
				->label(Translator::translate('admin.category.field.title'))
				->required(true);

			// Alias
			$this->text('alias')
				->label(Translator::translate('admin.category.field.alias'));
		});

		// Delete the Text Group...

		// Created fieldset
		$this->fieldset('created', function(Form $form)
		{
			// No change...
		});
	}
}
```

## Article Edit Form

Then modify the article form.

Note we use `CategoryListField` for `category_id`.

``` php
// src/Blog/Admin/Form/Article/EditDefinition.php

namespace Blog\Admin\Form\Article;

use Blog\Admin\Field\Category\CategoryListField;
use Phoenix\Form\PhoenixFieldTrait;
use Windwalker\Core\Form\AbstractFieldDefinition;
use Windwalker\Core\Language\Translator;
use Windwalker\Form\Form;
use Windwalker\Html\Option;

class EditDefinition extends AbstractFieldDefinition
{
	use PhoenixFieldTrait;

	public function doDefine(Form $form)
	{
		// Basic fieldset
		$this->fieldset('basic', function(Form $form)
		{
			// ID
			$this->hidden('id');

			// Title
			$this->text('title')
				->label(Translator::translate('admin.article.field.title'))
				->required(true);

			// Alias
			$this->text('alias')
				->label(Translator::translate('admin.article.field.alias'));

			// Category
			$this->add('category_id', new CategoryListField)
				->label('Category')
				->option(new Option('Uncategorised', ''));
		});

		// Text Fieldset
		$this->fieldset('text', function(Form $form)
		{
			// No change...
		});

		// Created fieldset
		$this->fieldset('created', function(Form $form)
		{
			// No change...
		});
	}
}
```

Then we can choose category in article edit page.

![Imgur](https://i.imgur.com/eCT61tS.jpg)

## Comment Form

Same as article and category, but we use `ArticleModalField` to select article.

``` php
// src/Blog/Admin/Form/Comment/EditDefinition.php

namespace Blog\Admin\Form\Comment;

use Blog\Admin\Field\Article\ArticleModalField;
use Phoenix\Form\PhoenixFieldTrait;
use Windwalker\Core\Form\AbstractFieldDefinition;
use Windwalker\Core\Language\Translator;
use Windwalker\Form\Form;

class EditDefinition extends AbstractFieldDefinition
{
	use PhoenixFieldTrait;

	public function doDefine(Form $form)
	{
		// Basic fieldset
		$this->fieldset('basic',function(Form $form)
		{
			// ID
			$this->hidden('id');

			// Name
			$this->text('name')
				->label('Name')
				->required(true);

			$this->email('email')
				->label('Email')
				->required(true);

			// Article
			$this->add('article_id', new ArticleModalField)
				->label('Article');
		});

		// Text Fieldset
		$this->fieldset('text',function(Form $form)
		{
			// Introtext
			$this->textarea('text')
				->label('Text')
				->rows(10);
		});

		// Created fieldset
		$this->fieldset('created',function(Form $form)
		{
			// State
			$this->radio('state')
				->label(Translator::translate('admin.comment.field.state'))
				->class('btn-group')
				->defaultValue(1)
				->option(Translator::translate('phoenix.grid.state.published'), '1')
				->option(Translator::translate('phoenix.grid.state.unpublished'), '0');

			// Created
			$this->calendar('created')
				->label(Translator::translate('admin.comment.field.created'));
		});
	}
}
```

You will see a Repository select field, if you click the `Select an Item` button, will open a modal for select article.

![Imgur](https://i.imgur.com/AvKB3xf.jpg)

![Imgur](https://i.imgur.com/yOsuRgx.jpg)

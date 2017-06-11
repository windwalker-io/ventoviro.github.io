---

layout: rad.twig
title: Start A Blog

---

## Introduction

In this chapter, we'll step by step to create a simple blog system, that helps you understand how to use phoenix.

## Create Admin Package

First, we have to create a basic blog admin package. please type this command:

``` bash
php windwalker muse init Blog/Admin category.categories
php windwalker muse add-subsystem Blog/Admin article.articles
php windwalker muse add-subsystem Blog/Admin comment.comments
```

After package created, register package and add assets link.

``` php
// etc/app/windwlaker.php

// ...

    'packages' => [
        // ...
        'phoenix' => \Phoenix\PhoenixPackage::class,
        'admin' => \Blog\Admin\AdminPackage::class
    ]

// ...
```

``` bash
$ php windwalker asset sync admin
```

And add routing to `/etc/routing.yml`.

``` yaml
## /etc/routing.yml

## ...

admin:
    pattern: /admin
    package: admin
```

## Migration

Open `src/Blog/Admin/*.php` files, change migration like below:

``` php
// src/Blog/Admin/xxxxxxxxxxxxxx_CategoryInit.php

// ...

class CategoryInit extends AbstractMigration
{
	public function up()
	{
		$this->createTable(Table::CATEGORIES, function (Schema $schema)
		{
		    $schema->primary('id')->unsigned()->notNull()->comment('Primary Key');
            $schema->varchar('title')->comment('Title');
            $schema->varchar('alias')->comment('Alias');
            $schema->integer('ordering');
            $schema->tinyint('state');
            $schema->datetime('created')->comment('Created Date');
            $schema->integer('created_by')->comment('Author');
            $schema->datetime('modified')->comment('Modified Date');
            $schema->integer('modified_by')->comment('Modified User');
            $schema->char('language')->length(7)->comment('Language');
            $schema->text('params')->comment('Params');

            $schema->addIndex('alias');
            $schema->addIndex('language');
            $schema->addIndex('created_by');
		});
	}

	public function down()
	{
		$this->drop(Table::CATEGORIES, true);
	}
}
```

``` php
// src/Blog/Admin/xxxxxxxxxxxxxx_ArticleInit.php

class ArticleInit extends AbstractMigration
{
	public function up()
    {
        $this->createTable(Table::ARTICLES, function(Schema $schema)
        {
            $schema->primary('id')->comment('Primary Key');
            $schema->integer('category_id');
            $schema->varchar('title')->comment('Title');
            $schema->varchar('alias')->comment('Alias');
            $schema->varchar('url')->comment('URL');
            $schema->text('introtext')->comment('Intro Text');
            $schema->text('fulltext')->comment('Full Text');
            $schema->varchar('image')->comment('Main Image');
            $schema->tinyint('state')->signed(true)->comment('0: unpublished, 1:published');
            $schema->integer('ordering')->comment('Ordering');
            $schema->datetime('created')->comment('Created Date');
            $schema->integer('created_by')->comment('Author');
            $schema->datetime('modified')->comment('Modified Date');
            $schema->integer('modified_by')->comment('Modified User');
            $schema->char('language')->length(7)->comment('Language');
            $schema->text('params')->comment('Params');

            $schema->addIndex('alias');
            $schema->addIndex('language');
            $schema->addIndex('created_by');
        });
    }

    public function down()
    {
        $this->drop(Table::ARTICLES);
    }
}
```

``` php
// src/Blog/Admin/xxxxxxxxxxxxxx_CommentInit.php

class CommentInit extends AbstractMigration
{
	public function up()
	{
		$this->createTable(Table::COMMENTS, function(Schema $schema)
		{
			$schema->primary('id')->comment('Primary Key');
			$schema->integer('article_id');
			$schema->varchar('name')->comment('Name');
			$schema->varchar('alias')->comment('Alias');
			$schema->varchar('email')->comment('Email');
			$schema->text('introtext')->comment('Intro Text');
			$schema->text('fulltext')->comment('Full Text');
			$schema->varchar('image')->comment('Main Image');
			$schema->tinyint('state')->signed(true)->comment('0: unpublished, 1:published');
			$schema->integer('ordering')->comment('Ordering');
			$schema->datetime('created')->comment('Created Date');
			$schema->integer('created_by')->comment('Author');
			$schema->datetime('modified')->comment('Modified Date');
			$schema->integer('modified_by')->comment('Modified User');
			$schema->char('language')->length(7)->comment('Language');
			$schema->text('params')->comment('Params');

			$schema->addIndex('alias');
			$schema->addIndex('language');
			$schema->addIndex('created_by');
		});
	}

	public function down()
	{
		$this->drop(Table::COMMENTS);
	}
}
```

Run migration:

``` bash
php windwalker migration migrate -p=admin
```

Now you can open `http://{your_site}/admin/categories` to see your admin page.

## Fake Data

We must add some fake data to help us developing. Use seeder to do this work, please modify `src/Blog/Admin/Seed/*.php` files.

First check that the seeder ordering is `CategorySeeder` -> `ArticleSeeder` -> `CommentSeeder` in `MainSeeder`.

``` php
// src/Blog/Admin/MainSeeder.php

// ...

class MainSeeder extends AbstractSeeder
{
	/**
	 * doExecute
	 *
	 * @return  void
	 */
	public function doExecute()
	{
		$this->execute(CategorySeeder::class);

		$this->execute(ArticleSeeder::class);

		$this->execute(CommentSeeder::class);

		// @muse-placeholder  seeder-execute  Do not remove this.
	}

	// ...
```

Then add modify all other seeder class to:

``` php
// src/Blog/Admin/Seed/CategorySeeder.php

// ...

class CategorySeeder extends AbstractSeeder
{
	public function doExecute()
	{
		$faker = Factory::create();

		foreach (range(1, 7) as $i)
		{
			$data = new Data;

			$data['title']       = $faker->word;
			$data['alias']       = OutputFilter::stringURLSafe($data['title']);
			$data['version']     = rand(1, 50);
			$data['created']     = $faker->dateTime->format($this->getSqlFormat());
			$data['created_by']  = rand(20, 100);
			$data['modified']    = $faker->dateTime->format($this->getSqlFormat());
			$data['modified_by'] = rand(20, 100);
			$data['ordering']    = $i;
			$data['state']       = $faker->randomElement(array(1, 1, 1, 1, 0, 0));
			$data['language']    = 'en-GB';
			$data['params']      = '';

			CategoryMapper::createOne($data);

			$this->outCounting();
		}
	}

	public function doClean()
	{
		$this->truncate(Table::CATEGORIES);
	}
}
```

``` php
// src/Blog/Admin/Seed/ArticleSeeder.php

// ...

class ArticleSeeder extends AbstractSeeder
{
	public function doExecute()
	{
		$faker = Factory::create();

		$categories = CategoryMapper::findAll();

		foreach ($categories as $category)
		{
			foreach (range(1, rand(3, 5)) as $i)
			{
				$data = new Data;

				$data['title']       = $faker->sentence(rand(3, 5));
				$data['alias']       = OutputFilter::stringURLSafe($data['title']);
				$data['category_id'] = $category->id;
				$data['introtext']   = $faker->paragraph(5);
				$data['fulltext']    = $faker->paragraph(5);
				$data['version']     = rand(1, 50);
				$data['created']     = $faker->dateTime->format($this->getSqlFormat());
				$data['created_by']  = rand(20, 100);
				$data['modified']    = $faker->dateTime->format($this->getSqlFormat());
				$data['modified_by'] = rand(20, 100);
				$data['ordering']    = $i;
				$data['state']       = $faker->randomElement(array(1, 1, 1, 1, 0, 0));
				$data['language']    = 'en-GB';
				$data['params']      = '';

				ArticleMapper::createOne($data);

				$this->outCounting();
			}
		}
	}

	public function doClean()
	{
		$this->truncate(Table::ARTICLES);
	}
}
```

``` php
// src/Blog/Admin/Seed/CommentSeeder.php

// ...

class CommentSeeder extends AbstractSeeder
{
	public function doExecute()
	{
		$faker = Factory::create();

		$articles = ArticleMapper::findAll();

		foreach ($articles as $article)
		{
			foreach (range(1, rand(1, 7)) as $i)
			{
				$data = new Data;

				$data['article_id'] = $article->id;
				$data['name']       = $faker->name;
				$data['email']      = $faker->email;
				$data['text']       = $faker->paragraph(5);
				$data['created']    = $faker->dateTime->format($this->getSqlFormat());
				$data['ordering']   = $i;
				$data['state']      = $faker->randomElement(array(1, 1, 1, 1, 0, 0));
				$data['params']     = '';

				CommentMapper::createOne($data);

				$this->outCounting();
			}
		}
	}

	public function doClean()
	{
		$this->db->getTable(Table::COMMENTS)->truncate();
	}
}
```

Run seeder import

``` bash
php windwalker seed import -p=admin

# OR

php windwalker migration reset --seed -p=admin
```

If migration and seeder works fine, open the admin page, you will see sample data to test.

![Imgur](http://i.imgur.com/qhyCxmp.jpg)

The first `Categories` menu item is a placeholder, you can delete it in `src/Blog/Admin/Templates/_global/admin/widget/submenu.blade.php`.


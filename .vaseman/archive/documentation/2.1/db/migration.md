---
layout: documentation.twig
title: Migration and Seeding

---

## Migration

Migration is a database version control system, it help our team to manage database schema and stay up to date on newest schema state.
Every member are allow to create a new schema version, and migration system will help us sync local schema to newest version.

### The Migration Flow

![migration](https://cloud.githubusercontent.com/assets/1639206/5591937/ae3fba0e-91db-11e4-88f7-d280f5b0577f.jpg)

Maybe you are *Develop B*, and your version is `20150112_AddSakuraTable.`
 
If migration system found your version is not up to date, you may run `migration migrate`, then migration system will update schema 
to the newest version: `20150421_AddTableIndex`

> Migrations are typically paired with the [Schema Builder](table-schema.html) to easily manage the database schema.

### Create A New Version

To create a migration version, you may use `migration create` command in console:

``` bash
php windwlaker migration create InitFlowerTable
```

You'll see this info:

``` html
Migration version: 20150101091434_InitFlowerTable.php created.
File path: /your/project/path/migrations/20150101091434_InitFlowerTable.php
```

##### Create to Custom Position

You may also create migration to other position by `--dir` or `--package`

``` bash
## Create to custom directory
php windwlaker migration create InitFlowerTable --dir=resources/migrations

## Create to a package's Migration folder
php windwlaker migration create InitFlowerTable --package=flower
```

### Writing Migration

This is a new migration file. The `up()` method will run if your version is lower than latest version. The `down()` method
will run if you migrate to older version.

``` php
<?php
// src/resources/migrations/20150101091434_InitFlowerTable.php

use Windwalker\Core\Migration\AbstractMigration;

/**
 * Migration class, version: 20150101091434
 */
class InitFlowerTable extends AbstractMigration
{
	/**
	 * Migrate Up.
	 */
	public function up()
	{

	}

	/**
	 * Migrate Down.
	 */
	public function down()
	{

	}
}
```

Here is an example to create a table:

``` php
use Windwalker\Core\Migration\AbstractMigration;
use Windwalker\Core\Migration\Schema;
use Windwalker\Database\Schema\Column;
use Windwalker\Database\Schema\DataType;
use Windwalker\Database\Schema\Key;

class InitFlowerTable extends AbstractMigration
{
	/**
	 * Migrate Up.
	 */
	public function up()
	{
        $this->createTable('flowers', function(Schema $schema)
        {
            $sc->addColumn('id',          new Column\Primary)->comment('Primary Key');
            $sc->addColumn('category_id', new Column\Integer)->comment('Category ID');
            $sc->addColumn('title',       new Column\Varchar)->allowNull(false)->comment('Title');
            $sc->addColumn('alias',       new Column\Varchar)->comment('Alias');
            $sc->addColumn('image',       new Column\Varchar)->comment('Main Image');
            $sc->addColumn('introtext',   new Column\Longtext)->comment('Intro Text');
            $sc->addColumn('fulltext',    new Column\Longtext)->comment('Full Text');
            $sc->addColumn('state',       new Column\Tinyint)->signed(true)->comment('0: unpublished, 1:published');
            $sc->addColumn('ordering',    new Column\Integer)->comment('Ordering');
            $sc->addColumn('created',     new Column\Datetime)->comment('Created Date');
            $sc->addColumn('created_by',  new Column\Integer)->comment('Author');
            $sc->addColumn('modified',    new Column\Datetime)->comment('Modified Date');
            $sc->addColumn('modified_by', new Column\Integer)->comment('Modified User');
            $sc->addColumn('language',    new Column\Char)->length(7)->comment('Language');
            $sc->addColumn('params',      new Column\Text)->comment('Params');

            $sc->addIndex(Key::TYPE_INDEX, 'idx_articles_category_id', 'category_id');
            $sc->addIndex(Key::TYPE_INDEX, 'idx_articles_alias', 'alias');
            $sc->addIndex(Key::TYPE_INDEX, 'idx_articles_language', 'language');
            $sc->addIndex(Key::TYPE_INDEX, 'idx_articles_created_by', 'created_by');
        });
	}

	/**
	 * Migrate Down.
	 */
	public function down()
	{
        $this->drop('flowers');
	}
}
```

See other schema operations: [Table and Schema](table-schema.html)

### Check Status

Use this command to show migration status.

``` bash
php windwlaker migration status
```

``` bash
 Status  Version         Migration Name
-----------------------------------------
  down   20141105131929  AcmeInit
  down   20150101091434  InitFlowerTable
```

### Start Migrating

Use `migrate` command to start migrating:

``` bash
php windwlaker migration migrate
```

Terminal will show migrating process.

``` html
Migration UP the version: 20141105131929_AcmeInit
------------------------------------------------------------
Success


Migration UP the version: 20150101091434_InitFlowerTable
------------------------------------------------------------
Success
```

##### Run Package Migration

``` bash
php windwlaker migration migrate --package=flower
```

##### Migrate to Specific Version
 
``` bash
php windwlaker migration migrate 20141105131929
```

If you use a lower version, this action will be downgrade.

## Seeding

Windwalker also provides a simple way to help you create fixtures for easy testing. The default seeder class will store in 
`/resources/seeders` , the package seeder will store in `/src/YourPackage/Seeder`.

### Default Seeder

Every time after you installed Windwalker, there will be a `DatabaseSeeder.php` in `/resources/seeders`:
 
``` php
<?php
// resources/seeders/DatabaseSeeder.php

use Windwalker\Core\Seeder\AbstractSeeder;

class DatabaseSeeder extends AbstractSeeder
{
	public function doExecute()
	{
	}

	public function doClean()
	{
	}
}
```

If you didn't add `--class` option, this file will be seeder default entry, we can follow these steps to create our seeders:

Add new Seeder file (Use IDE or code editor)

``` php
// resources/seeders/FlowerSeeder.php

/**
 * This class dose not has Namespace
 */
class FlowerSeeder extends \Windwalker\Core\Seeder\AbstractSeeder
{
	public function doExecute()
	{
		$data = array();

		// Create your fake data...

		foreach ($data as $item)
		{
			$this->db->getWriter()->insertOne('flowers', $item);

			// Or use DataMapper

			FlowerMapper::createOne($item);
		}
	}
}
```

Then call this seeder in default DatabaseSeeder:

``` php
class DatabaseSeeder extends AbstractSeeder
{
	public function doExecute()
	{
		// Execute sub seeder 
		$this->execute(new FlowerSeeder);

		$this->command->out('Seeder executed.')->out();
	}

	public function doClean()
	{
		// Truncate table
		$this->truncate('flowers');

		$this->command->out('Database clean.')->out();
	}
}
```

Now, use `seed import` command:

``` bash
php windwlaker seed import
```

You will see this output, it means seeder execute success:

``` html
Import seeder DatabaseSeeder
Import seeder FlowerSeeder
Seeder executed.
```

### Package Seeder

Package Seeder is allow to use namespace as class name. You can just add `--class` option after command to direct a 
particular seeder class, or add `--package` to use package default DatabaseSeeder.

``` php
// src/FlowerPackage/Seeder/FlowerSeeder.php

namespace Flower\Seeder;

use Windwalker\Core\Seeder\AbstractSeeder;

class FlowerSeeder extends AbstractSeeder
{
	public function doExecute()
	{
	}
}
```

Run this command to execute package seeder:

``` bash
## Choose class
php windwlaker seed import --class=Flower\Seeder\MySeeder
 
## Use default DatabaseSeeder
php windwlaker seed import --package=flower
```

## Fake Data Generator

Windwalker includes [PHP Faker](https://github.com/fzaninotto/Faker) to help you generate random fake data.

``` php
class ArticleSeeder extends AbstractSeeder
{
	public function doExecute()
	{
		// Get Faker
		$faker = \Faker\Factory::create();
		
		// Get Users in database
		$users = (new DataMapper('users'))->findAll();
		
		// Article DataMapper
		$articleMapper = new DataMapper('articles');

		// Create articles for every user in database
		foreach ($users as $user)
		{
			// Every use 10 articles
			foreach (range(1, 10) as $i)
			{
				// Use Faker to generate fake data
				$article = array(
					'title'     => $faker->sentence(),
					'author'    => $user->name,
					'author_id' => $user->id,
					'text'      => $faker->paragraphs(3),
					'created'   => $faker->date()
				);

				$articleMapper->createOne($article);
			}
		}
	}
}
```

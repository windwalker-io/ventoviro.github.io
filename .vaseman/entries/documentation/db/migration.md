layout: documentation.twig
title: Migration and Seeding

---

# Migration

Migration is a database version control system, it help our team to manage database schema and stay up to date on newest schema state.
Every member are allow to create a new schema version, and migration system will help us sync local schema to newest version.

## The Migration Flow

![migration](https://cloud.githubusercontent.com/assets/1639206/5591937/ae3fba0e-91db-11e4-88f7-d280f5b0577f.jpg)

Maybe you are *Develop B*, and your version is `20150112_AddSakuraTable.`
 
If migration system found your version is not up to date, you may run `migration migrate`, then migration system will update schema 
to the newest version: `20150421_AddTableIndex`

> Migrations are typically paired with the [Schema Builder](table-schema.html) to easily manage the database schema.

## Create A New Version

To create a migration version, you may use `migration create` command in console:

``` bash
php bin/console migration create InitFlowerTable
```

You'll see this info:

``` html
Migration version: 20150101091434_InitFlowerTable.php created.
File path: /your/project/path/migrations/20150101091434_InitFlowerTable.php
```

### Create to Custom Position

You may also create migration to other position by `--dir` or `--package`

``` bash
# Create to custom directory 
php bin/console migration create InitFlowerTable --dir=resources/migrations

# Create to a package's Migration folder
php bin/console migration create InitFlowerTable --package=flower
```

## Writing Migration

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
        $this->db->getTable('flowers')
            ->addColumn('id', DataType::INTEGER, Column::UNSIGNED, Column::NOT_NULL, '', 'PK', array('primary' => true))
			->addColumn('name', DataType::VARCHAR, Column::SIGNED, Column::NOT_NULL, '', 'Name', array('length' => 255))
			->addColumn('alias', DataType::VARCHAR, Column::SIGNED, Column::NOT_NULL, '', 'Alias')
			->addIndex(Key::TYPE_INDEX, 'idx_name', 'name', 'Test')
			->addIndex(Key::TYPE_UNIQUE, 'idx_alias', 'alias', 'Alias Index')
			->create(true);
	}

	/**
	 * Migrate Down.
	 */
	public function down()
	{
        $this->db->getTable('flowers')->drop();
	}
}
```

We can use more simpler syntax to define table, there is some pre-defined column type object:

``` php
$this->db->getTable('flowers')
	->addColumn(new Column\Primary('id'))
    ->addColumn(new Column\Varchar('name'))
    ->addColumn(new Column\Char('type'))
    ->addColumn(new Column\Timestamp('created'))
    ->addColumn(new Column\Bit('state'))
    ->addColumn(new Column\Integer('uid'))
    ->addColumn(new Column\Tinyint('status'))
    ->create();
```

See other schema operations: [Table and Schema](table-schema.html)

## Check Status

Use this command to show migration status.

``` bash
php bin/console migration status
```

``` bash
 Status  Version         Migration Name
-----------------------------------------
  down   20141105131929  AcmeInit
  down   20150101091434  InitFlowerTable
```

## Start Migrating

Use `migrate` command to start migrating:

``` bash
php bin/console migration migrate
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

### Run Package Migration

``` bash
php bin/console migration migrate --package=flower
```

### Migrate to Specific Version
 
``` bash
php bin/console migration migrate 20141105131929
```

If you use a lower version, this action will be downgrade.

# Seeding

Windwalker also provides a simple way to help you create fixtures for easy testing. The default seeder class will store in 
`/resources/seeders` , the package seeder will store in `/src/YourPackage/Seeder`.

## Default Seeder

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
		$this->execute('FlowerSeeder');

		$this->command->out('Seeder executed.')->out();
	}

	public function doClean()
	{
		// Truncate table
		$this->db->getTable('flowers')->truncate();

		$this->command->out('Database clean.')->out();
	}
}
```

Now, use `seed import` command:

``` bash
php bin/console seed import
```

You will see this output, it means seeder execute success:

``` html
Import seeder DatabaseSeeder
Import seeder FlowerSeeder
Seeder executed.
```

## Package Seeder

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
# Choose class
php bin/console seed import --class=Flower\Seeder\MySeeder
 
# Use default DatabaseSeeder
php bin/console seed import --package=flower
```

# Fake Data Generator

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

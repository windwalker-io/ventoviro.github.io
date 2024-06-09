---
layout: doc
---

# Modeling

For the actual tutorial process, we will use a blog system as practice.

To have test data first, we start by following the steps below to create the database structure.

## Create Entity

We will first create a basic Article table, starting from Entity. Every table must have a corresponding entity.

Run:

```shell
php windwalker g entity Article
```

This will create `src/Entity/Article.php`, with content similar to this:

```php
<?php

declare(strict_types=1);

namespace App\Entity;

// ...

#[Table('articles', 'article')]
#[\AllowDynamicProperties]
class Article implements EntityInterface
{
    use EntityTrait;

    #[EntitySetup]
    public static function setup(EntityMetadata $metadata): void
    {
        //
    }
}

```

The `#[Table]` attribute above the class declares the table name.

Currently, this entity has no any properties. We will add them later and then create the migration.

## Create Migration

Input:

```shell
php windwalker mig:create ArticleInit
```

This will generate a `xxxxxxxxxxxxxx_ArticleInit.php` file under `resources/migrations`. The `xxxxxxxxxxxxxx` is migration ID, which is formatted by `YmdHi` and suffix by a 4 digit serial number. 

Open it and input the table column creation information for the article.

```php
<?php

declare(strict_types=1);

namespace App\Migration;

use App\Entity\Article;
use Windwalker\Core\Console\ConsoleApplication;
use Windwalker\Core\Migration\Migration;
use Windwalker\Database\Schema\Schema;

/**
 * Migration UP: 2024060909320001_ArticleInit.
 *
 * @var Migration          $mig
 * @var ConsoleApplication $app
 */
$mig->up(
    static function () use ($mig) {
        $mig->createTable(
            Article::class,
            function (Schema $schema) {
                $schema->primary('id');
                $schema->integer('category_id');
                $schema->varchar('title');
                $schema->varchar('image');
                $schema->longtext('content');
                $schema->datetime('created');
                $schema->integer('created_by');
                $schema->json('params');

                $schema->addIndex('category_id');
                $schema->addIndex('created_by');
            }
        );
    }
);

/**
 * Migration DOWN.
 */
$mig->down(
    static function () use ($mig) {
        $mig->dropTable(Article::class);
    }
);


```

We use the `App\Entity\Article::class` as the table name and create some common columns for article. The `down()` function below sets up the downgrade logic to remove this table.

Next, run the following command to upgrade the migration version and create the table.

```shell
php windwalker mig:go -f
```

If success, will show the following message:

```shell
Backing up SQL...
SQL backup to: ...


Migration start...
==================

2024060909320001 ArticleInit UP... Success

```

We can run the following command to check the migration status:

```shell
$ php windwalker mig:status

+--------+---- Migration Status -------------+
| Status | Version          | Migration Name |
+--------+------------------+----------------+
| up     | 2021061915530001 | AcmeInit       |
| up     | 2024060909320001 | ArticleInit    |
+--------+------------------+----------------+

```

## Create Entity Properties

Since all future database auto-mapping will be handled by Entity, we need to set up the Entity first.

While the table is already created, we can run the following command to generate Entity properties:

```php
php windwalker build:entity Article
```

> [!note]
> If you have set up the auto-completion of console, you may type first some letters and press tab to auto-complete entity name.  

Result:

```shell
Handling: App\Entity\Article

  Added columns:
    - id (id)
    - categoryId (category_id)
    - title (title)
    - image (image)
    - state (state)
    - content (content)
    - created (created)
    - createdBy (created_by)
    - params (params)

```

The Windwalker will generate the corresponding properties based on the table. Let's open the file and take a look, by default, the auto-generated code may be stuck together and not fit the `PSR-12` or any common code styles, you can re-format it by your IDE before next action.

```php
<?php

// ...

#[Table('articles', 'article')]
#[\AllowDynamicProperties]
class Article implements EntityInterface
{
    use EntityTrait;
    #[Column('id'), PK, AutoIncrement]
    protected ?int $id = null;
    #[Column('category_id')]
    protected int $categoryId = 0;
    #[Column('title')]
    protected string $title = '';
    #[Column('image')]
    protected string $image = '';
    #[Column('state')]
    protected int $state = 0;
    #[Column('content')]
    protected string $content = '';
    #[Column('created')]
    #[CastNullable(ServerTimeCast::class)]
    protected ?Chronos $created = null;
    #[Column('created_by')]
    protected int $createdBy = 0;
    #[Column('params')]
    #[Cast(JsonCast::class)]
    protected array $params = [];

    #[EntitySetup]
    public static function setup(EntityMetadata $metadata): void
    {
        //
    }
}

```

Each column has a corresponding property with default values, and some columns have Cast to help convert types.

Here, we need to pre-set all possible type adjustments. For example, the params column stores JSON. We can set it to automatically convert to an array when retrieved and back to JSON when saved.

```php
    #[Column('params')]
    #[Cast(JsonCast::class)]
    protected array $params = [];

```

## Introduction to Entity Casting

`#[Cast]` is used to convert types when fetching data from the database into objects and when saving data from objects back into the database.

The interface is as follows:

```php
Cast([hydrate], [extract = null], [options = 0])
```

If only the first parameter (hydrate) is provided, it will convert data according to this parameter when fetched from the DB. `#[Cast]` can use the following formats:

- Predefined `CastInterface` objects, such as `JsonCast`, `DateTimeCast`, `TimestampCast`, etc.
- Direct input types like `int`, `string`, etc.
- Input filter chains, such as `int|range(min=1, max=5)|length(max=1)`
- Any object class name
- It will convert using two modes, defaulting to the object's constructor. The other mode uses hydrator, controlled by the third parameter: `Cast::USE_HYDRATOR` or `Cast::USE_CONSTRUCTOR`.

The second parameter (extract) is used when fetching data back into the DB. If not provided, it will automatically guess the method needed to convert back. You can also provide custom cast parameters for reverse conversion.

Multiple Cast settings can be stacked, as shown below:

```php
    #[Column('state')]
    #[Cast('int')]
    #[Cast(BasicState::class)]
    protected BasicState $state;

```

When data is put into the entity, the order is from top to bottom. When extracting, the order is from bottom to top, restoring it to the initial value.

### `CastNullable`

By default, `#[Cast]` converts all `null` value to empty string. If you are using a DB column which is accepts `NULL` value, there is also a `#[CastNullable]` that can accept `NULL` value. This is same as `#[Cast('...', options: Cast::NULLABLE)]`

## Create Getters/Setters

Once all Casts are set up, we can add accessors to the entity. Run the following command.

Same command as before, but with `--methods` or `-m` added:

```shell
Handling: App\Entity\Article

  Added methods:
    - getCategoryId
    - setCategoryId
    - getTitle
    - setTitle
    - getImage
    - setImage
    - getState
    - setState
    - getContent
    - setContent
    - getCreated
    - setCreated
    - getCreatedBy
    - setCreatedBy
    - getParams
    - setParams

```

The Entity now has methods to access its properties. After creating properties and methods, they will be stuck together. You can reformat them by your IDE.

## Create Seeder

With Entity and its methods, it is more convenient to create seeders to populate test data.

First, input:

```shell
php windwalker seed:create article
```

This will create:

```shell
[CREATE] resources/seeders/article-seeder.php
```

Due to the order of seeders, we need to manually register it in `resources/seeders/main.php`. Open and edit:

```php
<?php

return [
    __DIR__ . '/acme-seeder.php',
    __DIR__ . '/article-seeder.php', // [!code ++]
];
```

Register `article-seeder.php` in the list. In the future, as seeders increase, this list will control the execution order.

Next, we can open `article-seeder.php` and edit the content to populate fake data.

```php
<?php

declare(strict_types=1);

namespace App\Seeder;

use App\Entity\Article;
use Windwalker\Core\Seed\Seeder;
use Windwalker\Database\DatabaseAdapter;
use Windwalker\ORM\EntityMapper;
use Windwalker\ORM\ORM;

/**
 * Article Seeder
 *
 * @var Seeder          $seeder
 * @var ORM             $orm
 * @var DatabaseAdapter $db
 */
$seeder->import(
    static function () use ($seeder, $orm, $db) {
        $faker = $seeder->faker('en_US');
        
        /** @var EntityMapper<Article> $mapper */
        $mapper = $orm->mapper(Article::class);
        
        foreach (range(1, 50) as $i) {
            $item = $mapper->createEntity();
            $item->setTitle($faker->sentence(2));
            $item->setImage($faker->imageUrl(800, 600));
            $item->setState(random_int(0, 1));
            $item->setContent($faker->paragraph(40));
            $item->setState(random_int(0, 1));
            $item->setCreatedBy(1); // Currently no User, set to 1
            $item->setCategoryId(1); // Currently no User, set to 1
            $item->setCreated($faker->dateTimeThisYear());
            $item->setImage($faker->imageUrl());
            $item->setParams(
                [
                    'show_date' => true,
                    'show_author' => false,
                ]
            );
            
            $article = $mapper->createOne($item);
            
            $seeder->outCounting();
        }
    }
);

$seeder->clear(
    static function () use ($seeder, $orm, $db) {
        $seeder->truncate(Article::class);
    }
);

```

With the help of Entity Methods, we can be reminded of what content needs to be input and can check the input types.

Among them, `setCreated()` was automatically generated earlier because the setter can accept multiple types.

```php
public function setCreated(\DateTimeInterface|string|null $created) : static
{
    $this->created = Chronos::tryWrap($created);
    return $this;
}
```

So it can convert various contents into `Chronos` objects. If needed, you can modify any property or method at any time to perform various type checks or conversions. The ultimate goal is to make database access more convenient and strictly check type issues.

Now, run again:

```shell
php windwalker mig:reset -fs

```

to refresh all migrations/seeders.

You will see:

```shell
Backing up SQL...
SQL backup to: ...


Rollback to 0 version...
========================

2024060909320001 ArticleInit DOWN... Success
2021061915530001 AcmeInit DOWN... Success


Migrating to latest version...
==============================

2021061915530001 AcmeInit UP... Success
2024060909320001 ArticleInit UP... Success


Seeding...
==========

Import seeder: Acme Seeder (/acme-seeder.php)
  (15) ◒ 
  Import completed...
Import seeder: Article Seeder (/article-seeder.php)
  (50) ◑ 
  Import completed...

Completed.
```

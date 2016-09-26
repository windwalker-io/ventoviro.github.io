---

layout: rad.twig
title: Field Generation

---

## Generate Field From Database

Phoenix provides a command to help us generate field definition code from SQL table, we can easily change our form
field definition after migration.

To use this function, please prepare a migration looks like below:

``` php
use Windwalker\Core\Migration\AbstractMigration;
use Windwalker\Database\Schema\Schema;

/**
 * Migration class of ScoreInit.
 */
class ScoreInit extends AbstractMigration
{
	/**
	 * Migrate Up.
	 */
	public function up()
	{
		$this->createTable('scores', function(Schema $schema)
		{
			$schema->primary('id');
			$schema->integer('student_id');
			$schema->datetime('date');
			$schema->varchar('title');
			$schema->char('type')->length(5)->comment('quiz, exam');
			$schema->decimal('grade');
			$schema->text('comment');
		});
	}

	/**
	 * Migrate Down.
	 */
	public function down()
	{
		$this->drop('scores');
	}
}
```

Then run this command:

``` bash
php windwalker phoenix form gen-field scores
```

Phoenix will generate field definitions to match data types of your table columns in terminal:

``` bash
// Id
$this->text('id')
        ->label('Id')
        ->set('class', '')
        ->set('labelClass', '')
        ->set('readonly', true);

// Student_id
$this->text('student_id')
        ->label('Student_id')
        ->set('class', '')
        ->set('labelClass', '')
        ->set('default', null);

// Date
$this->calendar('date')
        ->label('Date')
        ->set('class', '')
        ->set('labelClass', '')
        ->set('default', null);

// Title
$this->text('title')
        ->label('Title')
        ->set('class', '')
        ->set('labelClass', '')
        ->set('default', null);

// Type
$this->list('type')
        ->label('Type')
        ->option('Quiz', 'quiz')
        ->option('Exam', 'exam')
        ->set('class', '')
        ->set('labelClass', '')
        ->set('default', 1);

// Subject
$this->text('subject')
        ->label('Subject')
        ->set('class', '')
        ->set('labelClass', '')
        ->set('default', null);

// Grade
$this->text('grade')
        ->label('Grade')
        ->set('class', '')
        ->set('labelClass', '')
        ->set('default', null);

// Comment
$this->textarea('comment')
        ->label('Comment')
        ->set('class', '')
        ->set('labelClass', '')
        ->set('rows', 7)
        ->set('default', null);

```

Now you can copy this code to your project.

## Output to File

Add `-o` to output these code to `/tmp` folder.

``` bash
php windwalker phoenix form gen-field scores
```

## Add Options to `CHAR` Type

By default, `char` type will create a select list, we can set options in comment:

``` php
$schema->char('foo')->length(3)->comment('bar, baz');
```

And the field generated will be:

``` php
$this->list('foo')
    ->label('Foo')
    ->option('Bar', 'bar')
    ->option('Baz', 'Baz')
    ->set('class', '')
    ->set('labelClass', '')
    ->set('default', 1);
```

## Supported Types

| Data Type | Field Type |
| --------- | ---------- |
| Primary | TextField |
| Varchar | TextField |
| Varchar (named `password`) | PasswordField |
| Tinyint | ListField (Boolean) |
| Char | ListField (With options) |
| Text | TextareaField |
| Longtext | TextareaField |
| Mediumtext | TextareaField |
| Datetime | CalendarField |

All un-defined types will fallback to text field.

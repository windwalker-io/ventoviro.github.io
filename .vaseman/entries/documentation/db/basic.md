layout: documentation.twig
title: Basic Database Usage

---

# Configure Database

In a new project, you should rename `/etc/secret.dist.yml` to `/etc/secret.yml`, and fill your database account information:

``` yaml
database:
    driver: mysql
    host: localhost
    user: account
    password: your_password
    name: your_db_name
    prefix: wind_
```

# Get Database

In DatabaseModel, you can get internal DB object.

``` php
$this->db;
```

Or in other context, use IoC container to get Database:

``` php
$db = \Windwalker\Ioc::getDatabase();

$db = $container->get('system.database');
```

# Execute A Query

This is an example of insert data.

``` php
$sql = 'INSERT INTO foo_table (title, state) VALUES ("Flower", 1)';

$db->setQuery($sql);

$db->execute();
```

# Fetch records

## Fetch multiple rows

This will fetch multiple rows from table, and every record will be an object.

``` php
$sql = 'SELECT * FROM foo_table WHERE state = 1';

$db->setQuery($sql);

$items = $db->loadAll();
```

Customize:

``` php
// Custom object class
$items = $db->loadAll(null, 'MyObject');

// Record as array with number as indexes
$items = $db->loadAll(null, 'array');

// Record as array with column name as indexes
$items = $db->loadAll(null, 'assoc');

// Use id column as $items index
$items = $db->loadAll('id', 'assoc');
```

## Fetch one row

``` php
$sql = 'SELECT * FROM foo_table WHERE id = 3';

$db->setQuery($sql);

$item = $db->loadOne();

// Custom object class
$items = $db->loadAll('MyObject');

// Record as array with number as indexes
$items = $db->loadAll('array');

// Record as array with column name as indexes
$items = $db->loadAll('assoc');
```

# Table Prefix

Add `prefix` in `secret.yml` config file, then DB object will auto replace all `#__` with prefix in every query:

``` php
$items = $db->setQuery('SELECT * FROM #__articles')->loadAll();

// The query will be `SELECT * FROM foo_articles`
```

# Iterating Over Results

``` php
$iterator = $db->setQuery('SELECT * FROM #__articles WHERE state = 1')->getIterator();

foreach ($iterator as $row)
{
    // Deal with $row
}
```

It allows also to count the results.

``` php
$count = count($iterator);
```

# Logging

`Database\DatabaseDriver` implements the `Psr\Log\LoggerAwareInterface` so is ready for intergrating with a logging package that supports that standard.

Drivers log all errors with a log level of `LogLevel::ERROR`.

If debugging is enabled (using `setDebug(true)`), all queries are logged with a log level of `LogLevel::DEBUG`. The context of the log include:

* **sql** : The query that was executed.
* **category** : A value of "databasequery" is used.

## An example to log error by Monolog

Add this to `composer.json` require block.

``` json
"monolog/monolog" : "1.*"
```

Then we push Monolog into Database instance.

``` php
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Processor\PsrLogMessageProcessor;

// Create logger object
$logger = new Logger('sql');

// Push logger handler, use DEBUG level that we can log all information
$logger->pushHandler(new StreamHandler('path/to/log/sql.log', Logger::DEBUG));

// Use PSR-3 logger processor that we can replace {sql} with context like array('sql' => 'XXX')
$logger->pushProcessor(new PsrLogMessageProcessor);

// Push into DB
$db->setLogger($logger);
$db->setDebug(true);

// Do something
$db->setQuery('A WRONG QUERY')->execute();
```

This is the log file:

```
[2014-07-29 07:25:22] sql.DEBUG: A WRONG QUERY {"sql":"A WRONG QUERY","category":"databasequery","trace":[...]} []
[2014-07-29 07:36:01] sql.ERROR: Database query failed (error #42000): SQL: 42000, 1064, You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'A WRONG QUERY' at line 1 {"code":42000,"message":"SQL: 42000, 1064, You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'A WRONG QUERY' at line 1"} []
```



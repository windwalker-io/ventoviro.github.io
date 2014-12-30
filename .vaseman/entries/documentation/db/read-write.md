layout: documentation.twig
title: Read and Write Database

---

# Reader Command

Reader is a command object that help us read records from database, this is a simple example to use Reader.

``` php
$reader = $db->getReader();

$items = $reader->setQuery($sql)->loadObjectList();

// OR

$items = $db->getReader($sql)->loadObjectList();
```

## loadObjectList()

Return an array, every element is a record and wrap with an object. This method is same as `$db->loadAll()`:

``` php
$sql = 'SELECT * FROM foo_table WHERE state = 1';

$reader = $db->getReader($sql);

$items = $reader->loadObjectList();
```

The return value is an array contains all records we found, every record will be an `stdClass` object.

We can set object class to store records:

``` php
$items = $reader->loadObjectList(null, 'Windwalker\\Data\\Data');

// bool(true)
var_dump($items[0] instanceof \Windwalker\Data\Data);
```

Use column values as array index. For example, we can use id as array indexes:

``` php
$items = $reader->loadObjectList('id');

// True
($items[11]->id == 11);
```

## loadObject()

Return only one record and wrap with an object.  This method is same as `$db->loadOne()`:

``` php
$sql = 'SELECT * FROM foo_table WHERE id = 1';

$reader = $db->getReader($sql);

$item = $reader->loadObject();
```

The `$item` will be a `stdClass`  object or `false` (If no any records found). we can set object class:

``` php
$item = $reader->loadObject('MyObject');
```

Then the object will be an instance of `MyObject`.

## loadArrayList()

Same as `$db->loadAll('array')`, returns an array and every element is an array indexed by column number as
returned in your result set, starting at column 0, we can set a column as index:

``` php
$reader = $db->getReader($sql);

$items = $reader->loadArrayList();
$items = $reader->loadArrayList('id'); // Use id as index
```

## loadArray()

Returns an array indexed by column number as returned in your result set, starting at column 0:

``` php
$reader = $db->getReader($sql);

$item = $reader->loadArray();
```

## loadAssocList()

Returns an array and every element is an associative array indexed by column name.

``` php
$reader = $db->getReader($sql);

$items = $reader->loadAssocList();
$items = $reader->loadAssocList('id'); // Use id as index
```

## loadAssoc()

Returns an associative array indexed by column name.

``` php
$reader = $db->getReader($sql);

$item = $reader->loadAssoc();
```

## loadColumn()

Fetch values of a column field, please select only one column in this query:

``` php
$titles = $db->getReader('SELECT title FROM article_table')->loadColumn();
```

## loadResult()

Fetch only one cell as a value:

``` php
// Get article id = 3 title
$title = $db->getReader('SELECT title FROM article_table WHERE id = 3')->loadResult();

// Get total hits
$sum = $db->getReader('SELECT SUM(hits) FROM article_table')->loadResult();

// Get a value
$id = $db->getReader('SELECT LAST_INSERT_ID()')->loadResult();
```

## About PDO Fetch

See: [PHP.net / PDOStatement::fetch](http://php.net/manual/en/pdostatement.fetch.php)

## Quick Fetch From Driver

### DatabaseDriver::loadAll()

Using a query we set into DB object to fetch records.

``` php
$sql = 'SELECT * FROM foo_table WHERE state = 1';

$db->setQuery($sql);

$items = $db->loadAll();

// Same as $reader->loadObjectList('id')
$items = $db->loadAll('id');

// Same as $reader->loadObjectList(null, 'MyObject')
$items = $db->loadAll(null, 'MyObject');

// Same as $reader->loadArrayList()
$items = $db->loadAll(null, 'array');

// Same as $reader->loadAssocList()
$items = $db->loadAll(null, 'assoc');
```

The return value is an array contains all records we found.

### DatabaseDriver::loadOne()

We can only get first record and ignore others:

``` php
$db->setQuery($sql);

$item = $db->loadOne();

// Same as $reader->loadObject(null, 'MyObject')
$item = $db->loadOne('MyObject');

// Same as $reader->loadArray()
$item = $db->loadOne('array');

// Same as $reader->loadAssoc()
$item = $db->loadOne('assoc');
```

The `$item` will be a record or `false` (If no any records found).

### DatabaseDriver::loadColumn()

Same as `$db->getReader($sql)->loadColumn()`.

### DatabaseDriver::loadResult()

Same as `$db->getReader($sql)->loadResult()`.

## count()

Count the total found rows of last query.

## countAffected()

Count the affected rows of last query.

## insertId()

Get the last inserted id.

# Writer Command

Writer object provide us an interface to write data into database.

## insert()

Using an object or array to store data into a table, argument 3 is the name of primary key that will be added
to data object if insert success:

``` php
$data = array(
    'title' => 'Sakura',
    'created' => '2014-03-02'
);

$db->getWriter()->insert('#__articles', $data, 'id');

// $data['id'] will be the last inserted id
echo $data['id'];

// OR using object

$data = new stdClass;

$data->title = 'Sakura';
$data->created = '2014-03-02';

$db->getWriter()->insert('#__articles', $data, 'id');

// $data->id will be the last inserted id
echo $data->id;
```

## insertMultiple()

Insert many records:

``` php
$dataSet = array(
    array(
        'title' => 'Sakura',
        'created' => '2014-03-02'
    ),
    array(
        'title' => 'Sunflower',
        'created' => '2014-05-02'
    );
);

$db->getWriter()->insertMultiple('#__articles', $dataSet, 'id');

// $dataSet[0]['id'] will be the last inserted id
echo $dataSet[0]['id'];
```

## update()

Using an object or array to update a record into a table, argument 3 is the where key value that we added to query:

``` php
$data = new stdClass;

$data->id = 1;
$data->title = 'Sakura2';

$db->getWriter()->update('#__articles', $data, 'id');

// Same as `UPDATE #__articles SET title = "Sakura2" WHERE id = 1;`
```

Also we can use array instead of object as data.

## updateMultiple()

Same as `update()` but update every record in an array.

``` php
$dataSet = array(
    $data1,
    $data2
);

$db->getWriter()->updateMultiple('#__articles', $dataSet, 'id');
```

## updateBatch()

Using where conditions to update some values to every records which matched this condition.

``` php
$data = new stdClass;

$data->state = 0;

// Update all author=13 records to state 0, same as `UPDATE #__articles SET state = 0 WHERE author = 15;`
$db->getWriter()->updateBatch('#__articles', $data, array('author' => 15));
```

Using other conditions:

``` php
$conditions = array(
    'author' => 15,
    'updated < "2014-03-02"',
    'catid' => array(1, 2, 3)
);

$db->getWriter()->updateBatch('#__articles', $data, $conditions);

// Same as `UPDATE #__articles SET state = 0 WHERE author = 15 AND updated < "2014-03-02" AND catid IN(1, 2, 3);`
```

## Save & SaveMultiple

`save()` and `saveMultiple()` will auto check the primary exists or not. If primary key exists, it will use `update`,
if not exists, it will use `insert` to store data.

## insertId()

Get the last inserted id.

## countAffected()

Count the affected rows of last query.

# Transaction Command

## Start Transaction

``` php
$tran = $this->db->getTransaction()->start();

try
{
    $this->db->setQuery($sql1)->execute();

    $this->db->setQuery($sql2)->execute();

    $this->db->setQuery($sql3)->execute();
}
catch (\Exception)
{
    $tran->rollback();
}

$tran->commit();
```

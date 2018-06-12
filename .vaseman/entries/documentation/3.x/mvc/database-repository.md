---
layout: documentation.twig
title: Database and Repository

---

## What is Repository

Repository is a class which implemented the repository pattern in Windwalker, it contains some low-layer DB handler like DataMapper
or ActiveRecord and help us do more database operation logic.

### Repository

`Repository` is a very simple class without any DB access. This is an example of using it:

```php
use Windwalker\Core\Repository\Repository;

class MyRepository extends Repository
{
	public function getItem()
	{
		return file_get_contents(__DIR__ . '/data.md');
	}

	public function save($content)
	{
		file_put_contents(__DIR__ . '/data.md', $content);
	}
}
```

```php
// In controller
$repo = new MyRepository();

$item = $repo->getItem();

// Do something...

$repo->save($item);
```

Set source to Repository:

```php
// FileFinder is just an example
$repo = new MyRepository(null, null, new FileFinder());

// In repository
$this->source->find(...);
```

## Database Repository

We can use `DatabaseRepository` to operate Database, here is a CRUD example, `db` is preset in Repository so we can get it quickly:

```php
use Windwalker\Core\Repository\DatabaseRepository;

class FlowerRepository extends DatabaseRepository
{
	public function getItem($id)
	{
		$query = $this->db->getQuery(true);

		$query->select('*')
			->from('flowers')
			->where('id = ' . $id);

		return $this->db->setQuery($query)->loadOne();
	}

	public function save($data)
	{
		if ($data->id) {
			$this->db->getWriter()->updateOne('flowers', $data, 'id');
		} else {
			$this->db->getWriter()->insertOne('flowers', $data);
		}
	}

	public function delete($id)
	{
		$query = $this->db->getQuery(true);

		$query->delete('flowers')
			->where('id = ' . $id);

		return $this->db->setQuery($query)->execute();
	}
}
```

## Use DataMapper

DataMapper is a easy way to help us operate database:

```php
use Windwalker\Core\Repository\DatabaseRepository;

class FlowerRepository extends DatabaseRepository
{
    // Add table to make sure DataMapper use corrent DB table.
    protected $table = 'flowers';

    // Keys is optional since Repository will set ['id'] as default
    // But you can override id if you use `uuid` or multiple primary key.
    protected $keys = 'id';

	public function getItem($id)
	{
		return $this->getDataMapper()->findOne($id);
	}

	public function save($data)
	{
		return $this->getDataMapper()->saveOne($data);
	}

	public function delete($id)
	{
		return $this->getDataMapper()->delete($id);
	}
}
```

See [DataMapper](../db/datamapper.html)

## Use Record

You can also use ActiveRecord to handle data saving.

```php
use Windwalker\Core\Repository\DatabaseRepository;

class FlowerRepository extends DatabaseRepository
{
    // Add table to make sure Record use corrent DB table.
    protected $table = 'flowers';

    // Keys is optional since Repository will set ['id'] as default
    // But you can override id if you use `uuid` or multiple primary key.
    protected $keys = 'id';

	public function getItem($id)
	{
		$record = $this->getRecord();
		$record->load($id);  // @throws NoResultException;

		return $record->dump();
	}

	public function save($data)
	{
	    $record = $this->getRecord();
	    $record->bind($data)
	        ->validate(); // @throws ValidateFailException;
            ->store();

        return true;
	}

	public function delete($id)
	{
		$this->getRecord()->delete($id);

		return true;
	}
}
```

See [ActiveRecord](../db/active-record.html)

## Magic Method

Repository support a usage similar to NullObject pattern, if we call some method start with `get*()` or `load*()`, and this method not exists,
Repository will not raise error but only return `null`.

```php
use Windwalker\Core\Repository\Repository;

// This is default repository, does not have any custom methods
$repo = new Repository();

// These 2 methods will only return null
$data = $repo->getData();

$list = $repo->loadList();
```

So, we can use default Repository to provide empty data for some object but won't breaking our program.
 
## Repository State

Windwalker Repository is stateful design, use state pattern can help ue create flexible data provider. 
For example, we can change this state to get different data.

```php
class MyRepository extends DatabaseRepository
{
    // ...

    public function getUsers()
    {
        $published = $this->state->get('where.published', 1);

        $ordering  = $this->state->get('list.ordering', 'id');
        $direction = $this->state->get('list.direction', 'ASC');

        $sql = "SELECT * FROM users " .
            " WHERE published = " . $published .
            " ORDER BY " . $ordering . " " . $direction;

        try
        {
            return $this->db->setQuery($sql)->loadAll();
        }
        catch (\Exception $e)
        {
            $this->state->set('error', $e->getMessage());

            return false;
        }
    }
}

$repo = new MyRepository();

$state = $repo->getState();

// Let's change repository state
$state->set('where.published', 1);
$state->set('list.ordering', 'birth');
$state->set('list.direction', 'DESC');

$users = $repo->getUsers();

if (!$users) {
    $error = $state->get('error');
}
```

##### Simple Way to Access State

Using `get()` and `set()`

```php
// Same as getState()->get();
$repo->get('where.author', 5);

// Same as getState()->set();
$repo->set('list.ordering', 'RAND()');
```

##### State ArrayAccess

```php
// Same as getState()->get();
$data = $repo['list.ordering'];

// Same as getState()->set();
$repo['list.ordering'] = 'created_time';
```

## Repository Caching

Windwalker Repository provides runtime cache interface help us cache data in Repository itself (This runtime cache only life in once
page load, will not exists in next page loading, and won't affected by global configuration).

This is an example to use cache in Repository:

```php
use Windwalker\Core\Repository\Repository;

class MyRepository extends Repository
{
	public function getData()
	{
		if ($this->cache->exists('item.data')) {
			return $this->cache->get('item.data');
		}

		$data = file_get_contents(__DIR__ . '/data.md');

		$this->cache->set('item.data', $data);

		return $data;
	}
}
```

### Generate Cache id When State Changed

Repository state is dynamic, so if we change state, the cache key should be refresh that we can make sure we get same data when state is same,
but get new data if state is changed.
 
```php
public function getData()
{
	// Will generate a id look like: d967f4557f17dd542ece0f8a7b57b4f697c9b189
	$id = $this->getCacheId('item.data');

	if ($this->cache->exists($id)) {
		return $this->cache->get($id);
	}

	$data = file_get_contents(__DIR__ . '/' . $this->state->get('file.name'));

	$this->cache->set($id, $data);

	return $data;
}
```

### Custom Cache id Rule

If you trace `getCacheId()` at the parent, you will see:

```php
public function getCacheId($id = null)
{
	$id = $id . json_encode($this->state->toArray());

	return sha1($id);
}
```

So override cache id rule is very easy, we can add some custom elements to hash:

```php
public function getCacheId($id = null)
{
	$id .= json_encode($this->get('query.filter'));
	$id .= json_encode($this->get('query.search'));
	$id .= json_encode($this->get('query.where'));
	$id .= json_encode($this->get('query.having'));
	$id .= json_encode($this->get('query.ordering'));
	$id .= json_encode($this->get('query.direction'));
	$id .= json_encode($this->get('query.limit'));
	$id .= json_encode($this->get('query.start'));

	return sha1($id);
}
```

### Use Callback
 
There is a simple way to quickly use cache, `fetch()` will auto check the cache exists or not and execute the callback to get data:

```php
public function getData()
{
	$callback = function()
	{
		return file_get_contents(__DIR__ . '/' . $this->state->get('file.name'));
	};
	
	return $this->fetch('item.data', $callback);
}
```


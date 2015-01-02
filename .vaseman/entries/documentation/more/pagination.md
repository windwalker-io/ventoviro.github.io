layout: documentation.twig
title: Pagination

---

# Start Using Pagination

Using Pagination in Windwalker is very easy, but you must know how to count pages in your SQL.

A pagination always need 3 integers, **Total rows**, **Items per page**, and **Current page**.
 
For SQL and programming, this 3 integers will be  **Total**, **Limit**, and **Offset**.
   
## Count Total Rows

Some times we will fetch items from database by SQL which looks like this:

``` sql
SELECT * 
FROM sakuras
WHERE state = 1
    AND author = 25
ORDER BY created
LIMIT 0, 20
```

This is a common way to count total rows by remove `LIMIT` element and select `COUNT(*)`.

``` sql
SELECT COUNT(*) 
FROM sakuras
WHERE state = 1
    AND author = 25
ORDER BY created
```

Use the second query we'll fetch total rows of first query without limit.

### Another Way to Count Total

Use `SQL_CALC_FOUND_ROWS` in only MySQL.

``` php
SELECT SQL_CALC_FOUND_ROWS *
FROM sakuras
WHERE state = 1
    AND author = 25
ORDER BY created
LIMIT 0, 20
```

And use `SELECT FOUND_ROWS()` to fetch total rows, this way will a little faster than first way if you set index correctly
, but only MySQL works.

### Count Total if SQL has GROUP

If you add `group` in your SQL, the `COUNT(*)` will be incorrect, we must use an inefficient way to count all results.

This is the main query of yours.

``` sql
SELECT *
FROM sakuras AS a
    LEFT JOIN flowers AS b ON a.flower_id = b.id
WHERE state = 1
    AND author = 25
ORDER BY created
LIMIT 0, 20
GROUP a.id
```

Remove `LIMIT` then execute this SQL:

``` sql
SELECT *
FROM sakuras AS a
    LEFT JOIN flowers AS b ON a.flower_id = b.id
WHERE state = 1
    AND author = 25
ORDER BY created
GROUP a.id
```

And use `ReaderCommand::count()`:

``` php
$total = $db->getReader($sql)->count();
```

## Create Pagination

This is an example to fetch rows and total: 

``` php
$limit = 20;
$page  = $input->get('page', 1);
$start = ($page - 1) * $limit;

$query = $db->getQuery(true);

// Basic query
$query->select('*')
    ->from('sakuras')
    ->where('state = 1')
    ->where('author = 25')
    ->order('created')
    ->limit($limit, $start);

// Load rows
$items = $db->getReader($query)->loadObjectList();

// Count total, clear non-necessary sql elements
$query->clear(array('select', 'limit', 'offset', 'order'))
    ->select('COUNT(*)');

$total = $db->getReader($query)->loadResult();

// Create pagination object
$pagination = new Pagination($total, $page, $limit);

// Get PaginationResult object
$paginData = $pagination->getResult();
```

# Pagination Result

`PaginationResult` to an object contains pagination information, you can get pages data from this object:

``` php
$paginData = $pagination->getResult();

$paginData->getFirst();    // page number
$paginData->getLess();     // page number
$paginData->getPrevious(); // page number
$paginData->getPages();    // An array or pages
$paginData->getNext();     // page number
$paginData->getMore();     // page number
$paginData->getLast();     // page number

// Print all
print_r($paginData->getAll());
```

The output is:

``` html
Array
(
    [1] => first
    [7] => less
    [8] => lower
    [9] => lower
    [10] => lower
    [11] => lower
    [12] => current
    [13] => higher
    [14] => higher
    [15] => higher
    [16] => higher
    [17] => more
    [25] => last
)
```

You can use this array to build your pagination HTML.

![pagination](https://cloud.githubusercontent.com/assets/1639206/5594615/131c1546-928e-11e4-8103-f90e73f4428d.jpg)

# Use Built-in Pagination Template

`render()` method will auto render pagination HTML, the default template is `windwalker.pagination.default`. 
The first argument (route resources name) is required because pagination use `Router` to get page url.

``` php
echo $pagination->render('flower:sakuras');
```

## Use Your Own Template

Add your template file in `/templates/windwalker/pagination/default.php`, Windwalker renderer will auto find it to replace built-in template.
  
Or use second argument to point to other template:

``` php
echo $pagination->render('flower:sakuras', 'mywidget.pagination');
```

See also: [Widget and Renderer](widget-renderer.html)


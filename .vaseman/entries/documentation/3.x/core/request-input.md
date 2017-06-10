---
layout: documentation.twig
title: Request and Input
redirect:
    2.1: start/request-input 

---

## Get Input Object in Controller

Last section we have learned how to make controller work, now it's time to see how the application receives HTTP request queries and respond.
In Windwalker you may access input queries using `Input` object. Below is how it works:

``` php
// src/Flower/Controller/Sakuras/GetController.php

class GetController extends AbstractController
{
	protected function doExecute()
	{
		$id = $this->input->get('id');
		$token = $this->input->get('token', 'default value');

		// Do some stuff
	}
}
```

### Get Input at Anywhere

``` php
// Use global Ioc class
$input = Ioc::getInput();

// OR use container object
$input = $container->get('input');

// In Controller
$this->input;
```

## Web Input

Input object is a container of web request data and superglobals.

``` php
use Windwalker\IO\Input;

$input = new Input;

// To get $_REQUEST['flower']
$input->get('flower', 'default');

// Set value
$input->set('flower', 'sakura');

// Get value from $_POST
$input->post->get('form_data');
```

The second argument is default value if request params not exists

### Filter

Input object use [Windwalker Filter](https://github.com/ventoviro/windwalker-filter) package to clean request string,
get request data by Input object keeps us away from unsafe string injection.

The default filter type is `CMD`. We can use other filter type:

``` php
// mysite.com/?flower=<p>to be, or not to be.</p>;

$input->get('flower'); // tobeornottobe (Default cmd filter)

$input->get('flower', 'default_value', InputFilter::STRING); // to be, or not to be

$input->getString('flower'); // to be, or not to be (Same as above, using magic method)

$input->getRaw('flower') // <p>to be, or not to be.</p>
```

More filter usage please see: [Windwalker Filter](https://github.com/ventoviro/windwalker-filter)

### Compact and Get Array

Get data as an array.

``` php
// mysite.com/?flower[1]=sakura&flower[2]=olive;

$input->getArray('flower'); // Array( [1] => sakura [2] => olive)

// Get array and filter every values
$input->getArray('flower', null, '.', 'int');
```

Use `compact()` method

``` php
// mysite.com/?flower=sakura&foo=bar&king=Richard

// Get all request
$input->compact();

// To retrieve values you want
$array(
    'flower' => '',
    'king' => '',
);

$input->compact($array); // Array( [flower] => sakura [king] => Richard)

// Specify different filters for each of the inputs:
$array(
    'flower' => InputFilter::CMD,
    'king' => InputFilter::STRING,
);

// Use nested array to get more complicated hierarchies of values

$input->compact(array(
    'windwalker' => array(
        'title' => InputFilter::STRING,
        'quantity' => InputFilter::INTEGER,
        'state' => 'integer' // Same as above
    )
));
```

### Get And Set Multi-Level

If we want to get value of `foo[bar][baz]`, just use `get('foo.bar.baz')`:

``` php
$value = $input->get('foo.bar.baz', 'default', InputFilter::STRING);

$input->set('foo.bar.baz', $data);

// Use custom separator
$input->get('foo/bar/baz', 'default', 'string', '/');
$input->set('foo/bar/baz', $data, '/');
```

### Get Value From Other Methods

We can get other methods as a new input object.

``` php
$post = $input->post;

$value = $post->get('foo', 'bar');

// Other inputs
$get    = $input->get;
$put    = $input->put;
$delete = $input->delete;
```

## Get SUPER GLOBALS

``` php
$env     = $input->env;
$session = $input->session;
$cookie  = $input->cookie;
$server  = $input->server;

$server->get('REMOTE_ADDR'); // Same as $_SERVER['REMOTE_ADDR'];
```

See: [SUPER GLOBALS](http://php.net/manual/en/language.variables.superglobals.php)

### Get method of current request:

``` php
$method = $input->getMethod();
```

## Json Input

If you send a request with json body or `content-type: application/json`, you can use `$input->json` to
get `JsonInput` and parse json values.

## Files and Uploaded

Windwalker Http package convert nested `$_FILES` variables to a set of `UploadedFileInterface` that we can easily move the uploaded files.

Suppose we have this HTML form.

``` html
<form action="..." enctype="multipart/form-data" method="post">
    <input type="file" name="flower[sakura]" />
    <input type="file" name="flower[rose]" />
    <input type="submit" value="submit" />
</form>
```

Then we get files in controller:

``` php
// controller::doExecute()

use Windwalker\Http\Helper\UploadedFileHelper;

/** @var  $file  UploadedFileInterface */
$file = $this->input->files->get('flower.sakura');

if ($file->getError())
{
    throw new \RuntimeException('Upload fail: ' . UploadedFileHelper::getUploadMessage($file->getError()), 500);
}

$dest = WINDWALKER_TEMP . '/uploaded/' . $file->getClientFilename();

try
{
    $file->moveTo($temp);
}
catch (\RuntimeException $e)
{
    // Handle move error.
}
```

See [Windwalker Http](https://github.com/ventoviro/windwalker-http)

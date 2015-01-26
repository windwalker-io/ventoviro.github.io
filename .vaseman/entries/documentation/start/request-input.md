layout: documentation.twig
title: Request and Input

---

# Get Input Object in Controller

Last section we have learned how to make controller work, now it's time to see how the application receives HTTP request queries and respond. In Windwalker you may access input queries using `Input` object. Below is how it works:

``` php
// src/Flower/Controller/Sakuras/GetController.php

class GetController extends Controller
{
	protected function doExecute()
	{
		$id = $this->input->get('id');
		$token = $this->input->get('token', 'default value');

		// Do some stuff
	}
}
```

## Get Input at Anywhere

The `Input` property only exists in class `Application` and `Controller`. Under any other situation to access `Input`, you may use Ioc (DI) Container. This is NOT recommended for it might break software robustness of your application.

``` php
// Use global Ioc class
$input = Ioc::getInput();

// OR use container object
$input = $container->get('system.input');
```

# How Input Work

Mostly, we need to get data from http request which `$_GET`, `$_POST` or `$_REQUEST` provides.

But it is very unsafe if we only use super global variables, the Input object can help us get values from these variables and sanitize string.
  
``` php
$input->get('flower'); // Same as $_REQUEST['flower']

$input->set('flower', 'sakura');
```

The second argument is default value if request params does not exist.

``` php
$input->get('flower', 'default');
```

## Filter

Input use [Windwalker Filter](https://github.com/ventoviro/windwalker-filter) package to sanitize request string, the default filter type is `CMD`.
We can use other filter type:

``` php
// mysite.com/?flower=<p>to be, or not to be.</p>;

$input->get('flower'); // tobeornottobe (Default cmd filter)

$input->get('flower', 'default_value', InputFilter::STRING); // to be, or not to be

$input->get('flower', 'default_value', 'string'); // Same as above

$input->getString('flower'); // to be, or not to be (Same as above, using magic method)

$input->getRaw('flower') // <p>to be, or not to be.</p>
```

More filter usage please see: [Windwalker Filter](https://github.com/ventoviro/windwalker-filter)

## Get Array

Input provides option to get data as array. 

``` php
// mysite.com/?flower[1]=sakura&flower[2]=olive;

$input->get('flower', InputFilter::ARRAY); // Array( [1] => sakura [2] => olive)
```

Use `getArray()` method

``` php
// mysite.com/?flower=sakura&foo=bar&king=Richard

// Get all request
$input->getArray();

// To retrieve values you want
$array(
    'flower' => '',
    'king' => '',
);

$input->getArray($array); // Array( [flower] => sakura [king] => Richard)

// Specify different filters for each of the inputs:
$array(
    'flower' => InputFilter::CMD,
    'king' => InputFilter::STRING,
);

// Use nested array to get more complicated hierarchies of values

$input->getArray(array(
    'windwalker' => array(
        'title' => InputFilter::STRING,
        'quantity' => InputFilter::INTEGER,
        'state' => 'integer' // Same as above
    )
));
```

## Get And Set Multi-Level

If we want to get value of `foo[bar][baz]`, just use `setByPath()`:

``` php
$value = $input->getByPath('foo.bar.baz', 'default', InputFilter::STRING);

$input->setByPath('foo.bar.baz', $data);
```

## Get Value From RESTful methods

We can get other methods as a new input object.

``` php
$post = $input->post;

$value = $post->get('foo', 'bar');

// Other inputs
$get    = $input->get;
$put    = $input->put;
$delete = $input->delete;
```

# Get SUPER GLOBALS

Every Super Global will be an independent object.

``` php
// These are all Input object
$env     = $input->env;
$session = $input->session;
$cookie  = $input->cookie;
$server  = $input->server;

$server->get('REMOTE_ADDR'); // Same as $_SERVER['REMOTE_ADDR'];
$env->set('FOO_BAR', 1);
```

See: [SUPER GLOBALS](http://php.net/manual/en/language.variables.superglobals.php)

## Get method according to current request type:

``` php
$method = $input->getMethod();
```

# Files Input

The format that PHP returns file data in for arrays can at times be awkward, especially when dealing with arrays of files. 
Files Input provides a convenient interface for making life a little easier, grouping the data by file.

Suppose you have a form like:

``` html
<form action="..." enctype="multipart/form-data" method="post">
    <input type="file" name="flower[test][]" />
    <input type="file" name="flower[test][]" />
    <input type="submit" value="submit" />
</form>
```

Normally, PHP put these in array `$_FILES` looks like:

```
Array
(
    [flower] => Array
        (
            [name] => Array
                (
                    [test] => Array
                        (
                            [0] => flower.png
                            [1] => sakura.jpg
                        )

                )

            [type] => Array
                (
                    [test] => Array
                        (
                            [0] => image/png
                            [1] => image/jpeg
                        )

                )

            [tmp_name] => Array
                (
                    [test] => Array
                        (
                            [0] => /tmp/phpUF9Hj
                            [1] => /tmp/phpO4Fug
                        )

                )

            [error] => Array
                (
                    [test] => Array
                        (
                            [0] => 0
                            [1] => 0
                        )

                )

            [size] => Array
                (
                    [test] => Array
                        (
                            [0] => 90445
                            [1] => 31187
                        )

                )

        )

)
```

FilesInput rearrange the result with cleaner form to work with:

``` php
$files = $input->files->get('flower');
```

`$files` then becomes:

```
Array
(
    [test] => Array
        (
            [0] => Array
                (
                    [name] => youtube_icon.png
                    [type] => image/png
                    [tmp_name] => /tmp/phpXoIpSD
                    [error] => 0
                    [size] => 34409
                )

            [1] => Array
                (
                    [name] => Younger_Son_2.jpg
                    [type] => image/jpeg
                    [tmp_name] => /tmp/phpWDE7ye
                    [error] => 0
                    [size] => 99529
                )

        )
)
```

Windwalker Input object is profoundly influenced by [Joomla Input](https://github.com/joomla-framework/input)
 
More about Input, see [Windwalker IO Package](https://github.com/ventoviro/windwalker-io)

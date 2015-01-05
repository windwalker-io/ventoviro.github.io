layout: documentation.twig
title: HTML Builder

---

## Html & Dom Builder

This is a convenience class to create XML and HTML element in an OO way.

### DomElement

DomElement and DomElements is use to create XML elements.

``` php
use Windwalker\Dom\DomElement;

$attrs = array('id' => 'foo', 'class' => 'bar');

echo $dom = (string) new DomElement('field', 'Content', $attrs);
```

Output:

``` xml
<field id="foo" class="bar">Content</field>
```

Add Children

``` php
use Windwalker\Dom\DomElement;

$attrs = array('id' => 'foo', 'class' => 'bar');

$content = array(
    new DomElement('option', 'Yes', array('value' => 1)),
    new DomElement('option', 'No', array('value' => 0))
)

echo $dom = (string) new DomElement('field', $content, $attrs);
```

The output wil be:

``` xml
<field id="foo" class="bar">
    <option value="1">Yes</option>
    <option value="0">No</option>
</field>
```

### HtmlElement

HtmlElement is use to create HTML elements, some specific tags will force to unpaired.

``` php
use Windwalker\Dom\HtmlElement;

$attrs = array(
    'class' => 'btn btn-mini',
    'onclick' => 'return fasle;'
);

$html = (string) new HtmlElement('button', 'Click', $attrs);
```

Then we will get this HTML:

``` html
<button class="btn btn-mini" onclick="return false;">Click</button>
```

#### Get Attributes by Array Access

``` php
$class = $html['class'];
```

### DomElements & HtmlElements

It is a collection of HtmlElement set.

``` php
$html = new HtmlElements(
    array(
        new HtmlElement('p', $content, $attrs),
        new HtmlElement('div', $content, $attrs),
        new HtmlElement('a', $content, $attrs)
    )
);

echo $html;
```

OR we can iterate it:

``` php
foreach ($html as $element)
{
    echo $element;
}
```

## XmlHelper

`XmlHelper` using on get attributes of `SimpleXmlElement`.

### Get Attributes

``` php
use Windwalker\Dom\SimpleXml\XmlHelper;

$xml = <<<XML
<root>
    <field name="foo" type="bar" readonly="true">
        <option></option>
    </field>
</root>
XML;

$xml = simple_xml_load_string($xml);

$element = $xml->xpath('field');

$name = XmlHelper::getAttribute($element, 'name'); // result: foo

// Same as get()
$name = XmlHelper::get($element, 'name'); // result: foo
```

### Get Boolean

`getBool()` can help us convert some string link `true`, `1`, `yes` to boolean `TRUE` and `no`, `false`, `disabled`, `null`, `none`, `0` string to booleand `FALSE`.

``` php
$bool = XmlHelper::getBool($element, 'readonly'); // result: (boolean) TRUE
```

### Get False

Just an alias of `getBool()` but FALSE will return `TRUE`.

### Set Default

If this attribute not exists, use this value as default, or we use original value from xml.

``` php
XmlHelper::def($element, 'class', 'input');
```

## Select List

``` php
use Windwalker\Html\Select\SelectList;
use Windwalker\Html\Option;

$select = new SelectList(
    'form[timezone]',
    array(
        new Option('Asia - Tokyo', 'Asia/Tokyo', array('class' => 'opt')),
        new Option('Asia - Taipei', 'Asia/Taipei'),
        new Option('Europe - Paris', 'Asia/Paris'),
        new Option('UTC', 'UTC'),
    ),
    array('class' => 'input-select'),
    'UTC',
    false
);

echo $select;
```

The result:

``` html
<select class="input-select" name="form[timezone]">
	<option class="opt" value="Asia/Tokyo">Asia - Tokyo</option>
	<option value="Asia/Taipei">Asia - Taipei</option>
	<option value="Asia/Paris">Europe - Paris</option>
	<option value="UTC" selected="selected">UTC</option>
</select>
```

### Group Select

Use two level array to make options grouped.

``` php
use Windwalker\Html\Select\CheckboxList;

$select = new SelectList(
    'form[timezone]',
    array(
        'Asia' => array(
            new Option('Tokyo', 'Asia/Tokyo', array('class' => 'opt')),
            new Option('Taipei', 'Asia/Taipei')
        ),
        'Europe' => array(
            new Option('Europe - Paris', 'Asia/Paris')
        )
        ,
        new Option('UTC', 'UTC'),
    ),
    array('class' => 'input-select'),
    'UTC',
    false
);

echo $select;
```

The result

``` html
<select class="input-select" name="form[timezone]">
	<optgroup label="Asia">
		<option class="opt" value="Asia/Tokyo">Tokyo</option>
		<option value="Asia/Taipei">Taipei</option>
	</optgroup>

	<optgroup label="Europe">
		<option value="Asia/Paris">Europe - Paris</option>
	</optgroup>

	<option value="UTC" selected="selected">UTC</option>
</select>
```

## CheckboxList

``` php
$select = new CheckboxList(
    'form[timezone]',
    array(
        new Option('Asia - Tokyo', 'Asia/Tokyo', array('class' => 'opt')),
        new Option('Asia - Taipei', 'Asia/Taipei'),
        new Option('Europe - Paris', 'Asia/Paris'),
        new Option('UTC', 'UTC'),
    ),
    array('class' => 'input-select'),
    'UTC',
    false
);

echo $select;
```

The result

``` html
<span class="checkbox-inputs input-select">
	<input class="opt" value="Asia/Tokyo" type="checkbox" name="form[timezone][]" id="form-timezone-asia-tokyo" />
	<label class="opt" id="form-timezone-asia-tokyo-label" for="form-timezone-asia-tokyo">Asia - Tokyo</label>

	<input value="Asia/Taipei" type="checkbox" name="form[timezone][]" id="form-timezone-asia-taipei" />
	<label id="form-timezone-asia-taipei-label" for="form-timezone-asia-taipei">Asia - Taipei</label>

	<input value="Asia/Paris" type="checkbox" name="form[timezone][]" id="form-timezone-asia-paris" />
	<label id="form-timezone-asia-paris-label" for="form-timezone-asia-paris">Europe - Paris</label>

	<input value="UTC" checked="checked" type="checkbox" name="form[timezone][]" id="form-timezone-utc" />
	<label id="form-timezone-utc-label" for="form-timezone-utc">UTC</label>
</span>
```

If you want to use `div` to wrap all inputs instead `span`, set tag name to object.

``` php
$select->setName('div');
```

## RadioList

Same as Checkboxes, but the input type will be `type="radio"`

## HtmlHelper

### Repair Tags

We can using `repair()` method to repair unpaired tags by `php tidy`, if tidy extension not exists, will using simple tag close function to fix it.

``` php
$html = '<p>foo</i>';

$html = \Windwalker\Html\Helper\HtmlHelper::repair($html);

echo $html; // <p>foo</p>
```

### Get JS Object

This method convert a nested array or object to JSON format that you can inject it to JS code.

``` php
$option = array(
    'url' => 'http://foo.com',
    'foo' => array('bar', 'yoo')
);

echo $option = \Windwalker\Html\Helper\HtmlHelper::getJSOBject($option);
```

Result

```
{
    "url" : "http://foo.com",
    "foo" : ["bar", "yoo"]
}
```

## More Builder

We'll add more builder object after version 2.1.

---

layout: rad.twig
title: Bootstrap

---

## Include Bootstrap

Include bootstrap css and js to HTML:

``` php
\Phoenix\Script\BootstrapScript::css();
\Phoenix\Script\BootstrapScript::script();
```

## Tooltips

Init tooltips:

``` php
\Phoenix\Script\BootstrapScript::tooltip();
```

Then use `hasTooltip` in element class to enable tooltip.

``` html
<a class="hasTooltip" title"Tooltip Text">...</a>
```

Custom tooltip class:

``` php
\Phoenix\Script\BootstrapScript::tooltip('.my-tooltips');
```

## Checkbox

Use Awesome Bootstrap Checkboxes:

``` php
\Phoenix\Script\BootstrapScript::checkbox();

// Or use font-awesome icons

\Phoenix\Script\BootstrapScript::checkbox(BootstrapScript::FONTAWESOME);
```

![p-2016-07-21-005](https://cloud.githubusercontent.com/assets/1639206/17014764/e761e8ca-4f58-11e6-8897-e5e7862baa0a.jpg)

See [Awesome Bootstrap Checkbox](http://flatlogic.github.io/awesome-bootstrap-checkbox/demo/)

## Iframe Modal

Add iframe modal to links:

``` php
\Phoenix\Script\BootstrapScript::modal();
```

Now add `hasModal` to `<a>` as class:

``` html
<a class="hasModal" href="http://windwalker.io">Open Link in Modal</a>
```

Then this link will open a modal with target page in iframe after clicked.

![p-2016-07-21-003](https://cloud.githubusercontent.com/assets/1639206/17014636/0e63ce94-4f58-11e6-8879-63d06c6ec168.jpg)

## Calendar

Convert an input to bootstrap calendar:

``` php
\Phoenix\Script\BootstrapScript::calendar();
```

HTML

``` html
<input class="hasCalendar" name="date">
```

![p-2016-07-20-011](https://cloud.githubusercontent.com/assets/1639206/16977839/2ac6f0b6-4e8b-11e6-8b0f-bec4f71fd295.jpg)

You can add some options to configure your calendar:

``` php
\Phoenix\Script\BootstrapScript::calendar('.selector', 'YYYY-MM-DD', [
    'sideBySide' => true,
    'calendarWeeks' => true
]);
```

See [Bootstrap Datepicker](https://eonasdan.github.io/bootstrap-datetimepicker/)

## Tab State

Use `tab-state.js` to remember last used tab panel when you back to same page:

``` php
\Phoenix\Script\BootstrapScript::tabState();

\Phoenix\Script\BootstrapScript::tabState('#selector');
```

## Button Radio

Convert HTML radio to Bootstrap radio:

``` php
\Phoenix\Script\BootstrapScript::buttonRadio();
```

Add `btn-group` to radio field to enable this feature:

``` php
class EditDefinition extends AbstractFieldDefinition
{
    public function doDefine()
    {
        $this->radio('state')
            ->setClass('btn-group')
            ->option('Published', 1)
            ->option('Unpublished', 0);

        // ...
    }
}
```

![p-2016-07-21-004](https://cloud.githubusercontent.com/assets/1639206/17014674/468fbcf6-4f58-11e6-8285-b300914b7460.jpg)

You can change the color class and selector options to fit different templates:

```php
\Phoenix\Script\BootstrapScript::buttonRadio([
    'selector' => '.btn-group .radio',
    'buttonClass' => 'btn',
    'activeClass' => 'actice',
    'color' => [
        'default' => 'btn-default',
        'green'   => 'btn-success',
        'red'     => 'btn-danger',
        'blue'    => 'btn-primary',
    ]
]);
```

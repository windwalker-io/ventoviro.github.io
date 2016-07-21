---

layout: rad.twig
title: Grid Script

---

## Introduction

`PhoenixGrid` is a helper to operate table list includes `sort`, `batch`, `select` and `ordering`.

It will auto included in Phoenix admin template, or use this PHP code to init it:

``` php
\Phoenix\Script\PhoenixScript::grid();

// OR add custom selector of your <form >

\Phoenix\Script\PhoenixScript::grid('#my-form');
```

## Sort

Sort this table by a column name:

``` js
// JS
Phoenix.Grid.sort('created', 'DESC');
```

After this method called, the form will auto submit and refresh page.

This method will send `list_ordering` and `list_direction` two variables to controller then you can use them to rebuild your SQL.

## Check Row

Tick a checkbox of specify row.

``` js
// JS

// Start row is 0, so we tick the second row.
Phoenix.Grid.checkRow(1);
```

![p-2016-07-21-002](https://cloud.githubusercontent.com/assets/1639206/17011424/9e8f52f8-4f40-11e6-8fe5-7e8e95d78f06.jpg)

You must print a checkbox with row number in HTML:

``` php
<input type="checkbox" class="grid-checkbox" data-row-number="1" name="id[1]" value="2">
```

Or use `GridHelper` to generate this checkbox in template:

``` php
{{-- In Edge --}}

@foreach ($items as $i => $item)
    @php($grid->setItem($item, $i))
    <tr>
        {{-- CHECKBOX --}}
        <td>
            {!! $grid->checkbox() !!}
        </td>

    {{-- ... --}}

@endforeach
```

## Update Row

This method can update a row with specify data by sending request to `BatchController`:

``` js
// JS

Phoenix.Grid.updateRow(3, null, {task: 'publish'});
```

This method will update row 3 `state => 0`.

> You must prepare checkboxes in HTML first to support this method

## Send Batch Task

Send a batch task to update rows:

``` js
Phoenix.Grid.batch('unpublish');
```

## Copy Row

Send a request to duplicate a row:

``` js
Phoenix.Grid.copyRow(3);
```

## Delete List

Delete checked rows.

``` js
Phoenix.Grid.deleteList();

// Add custom confirm message
Phoenix.Grid.deleteList('Are you sure?');
```

## Delete Row

``` js
Phoenix.Grid.deleteRTow(3);
```

## Toggle All Checkboxes

``` js
Phoenix.Grid.toggleAll();
```

## More Methods

``` js
// Count checked checkboxes
Phoenix.Grid.countChecked();

// Get Checked boxes
Phoenix.Grid.getChecked();

// Validate there has one or more checked boxes.
// The method will throw Error instantly if no checked
try
{
    Phoenix.Grid.hasChecked().batch(...);
}
catch (Error)
{
    // ...
}

// Reorder
Phoenix.Grid.reorderAll();
Phoenix.Grid.reorder();
```

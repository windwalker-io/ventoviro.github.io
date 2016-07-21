---

layout: rad.twig
title: jQuery

---

## Include jQuery

Phoenix provides jQuery as core JS environment, you can use PHP to auto add jQuery to HTML output.

``` php
\Phoenix\Script\JQueryScript::core();
```

The jQuery will auto included in `<hrad>`.

### No Conflict

You can also add first argument as no-conflict mode:

``` php
\Phoenix\Script\JQueryScript::core(true);
```

Then you must use `jQuery` instead `$` in global scope:

``` js
jQuery(document).ready(function ($) {
    $('body').attr('class');
})
```

## jQuery UI

Add jQuery UI to HTML, the jQuery core will auto included when you call this method:

``` php
\Phoenix\Script\JQueryScript::ui();
```

Add UI components, every component will only include once:

``` php
\Phoenix\Script\JQueryScript::ui(['droppable', 'effect']);
```

Available components:

- draggable
- droppable
- resizable
- selectable
- sortable
- effect

## Color Picker

A jQuery based color picker:

``` php
// Use default settings
\Phoenix\Script\JQueryScript::colorPicker();

// Custom settings
\Phoenix\Script\JQueryScript::colorPicker('.selector', ['control' => 'hue', 'position' => 'top']);
```

See [Minicolors](http://labs.abeautifulsite.net/jquery-minicolors/)

## Highlight Text

Highlight plugin is useful when you want to mark a text in search result:

``` php
// Find text in whole page
\Phoenix\Script\JQueryScript::highlight(null, 'Text');

// Find in an element
\Phoenix\Script\JQueryScript::highlight('.hasHighlight', 'Text');

// Set options
\Phoenix\Script\JQueryScript::highlight('.hasHighlight', 'Text', ['element' => 'em', 'className' => 'my-highlight']);
```

See [jQuery Highlight](http://bartaz.github.io/sandbox.js/jquery.highlight.html)

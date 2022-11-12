---
layout: global.documentation-layout
title: Introduction
part: Components
chapter: Renderer
menu: components/renderer
---

# Introduction

Windwalker Renderer is a template engine adapters to easily render template files for different engines 
with same interface.

## Support Engines

- PHP
- Twig
- Blade
- Edge (A Blade compitable engine without dependencies)
- Mustache
- Plates

## Installation

Install via composer

```bash
composer require windwalker/renderer ^4.0
```

## Use in Windwalker

By default, `windwalker-starter` will install `renderer` as dependency.

You can render a template by `RendererService`:

```php
use Windwalker\Core\Renderer\RendererService;

$rendererService = $app->service(RendererService::class);

$rendererService->render('foo.bar'); // Will auto detect template file type
```

## Use as Standalone Component

### Getting Started

The basic PHP renderer is `PlatesRenderer`, which is an adapter of [league/plates](https://platesphp.com/):

```php
use Windwalker\Renderer\PlatesRenderer;

$renderer = new PlatesRenderer(
    [
        // Required
        'base_dir' => '/path/to/tmpl'
    ]
);

// Make a template callback
$template = $renderer->make('flower/sakura'); // Will load `/path/to/tmpl/flower/sakura.phtml`

// Render it
echo $template(['foo' => 'bar']);

// You can render it again with different data
echo $template(['foo' => 'goo']);
```

Directly render:

```php
echo $renderer->render('flower/sakura', ['foo' => 'bar']);
```

### Extends Template Engine

In Windwalker 4, Renderer on-longer keep the paths and leave engine to handle it.

Use extends to configure template engine before rendering:

```php
$renderer->extend(
    function (\League\Plates\Engine $engine) {
         $engine->setDirectory('/another/tmpl/path');

        return $engine;
    }
);
```

### Custom Engine Builder

If you want to create your own engine, use `setEngineBuilder()`:

```php
$renderer->setEngineBuilder(
    function () {
        return new \League\Plates\Engine('/path/to/templates');
    }
);
```

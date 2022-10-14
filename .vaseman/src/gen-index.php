<?php

/**
 * Part of site project.
 *
 * @copyright  Copyright (C) 2022 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

$components = include __DIR__ . '/../resources/data/components.php';

$docRoot = __DIR__ . '/../entries/documentation/components';

foreach ($components as $component) {
    $cName = ucfirst($component);
    $content = <<<MD
---
layout: global.documentation-layout
title: Introduction
part: Components
chapter: $cName
menu: components/$component
---

# Introduction

This document is work in process.

## Installation

Install by composer

```bash
composer require windwalker/$component ^4.0
```

## Use in Windwalker

...

## Use as Standalone Component

...

## Getting Started

...

MD;
    $file = $docRoot . '/' . $component . '/index.md';

    if (!is_file($file)) {
        mkdir(dirname($file));

        file_put_contents($file, $content);
    }

}
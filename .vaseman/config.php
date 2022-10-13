<?php

/**
 * Part of vaseman4 project.
 *
 * @copyright  Copyright (C) 2022 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

return [
    'project' => [
        'name' => 'Windwalker - PHP Rapid Development Framework'
    ],

    'og' => [
        'image' => 'https://i.imgur.com/1HHxwsI.jpg'
    ],

    'doc' => [
        'components_count' => 31
    ],

    // Which folders you want to generate (Array)
    'folders' => [
        'entries' => '',
        'assets' => 'assets'
    ],

    'links' => [
        //
    ],

    // Plugin classes with namespace (Array)
    'plugins' => [
        \App\Plugin\DataPlugin::class
    ],

    'components' => [
        'hero-banner' => 'components.hero-banner',
        'doc-banner' => 'components.doc-banner',
        'breadcrumb' => 'components.breadcrumb',
        'pagination' => 'components.pagination',
    ],

    'system' => [
        'debug' => 0,
        'timezone' => 'UTC',
        'error_reporting' => -1,
    ],
    
    'assets' => [
        'append_version' => true
    ]
];

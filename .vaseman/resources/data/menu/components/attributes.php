<?php

/**
 * Part of site project.
 *
 * @copyright  Copyright (C) 2022 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

use App\Menu\MenuItem;

return [
    'index' => new MenuItem(
        title: 'Introduction',
        extra: []
    ),
    'available-type-actions' => new MenuItem(
        title: 'Available Types & Actions',
        extra: []
    ),
    'write-attributes-handlers' => new MenuItem(
        title: 'Write Your Own Attribute Handlers',
        extra: []
    ),
    'misc' => new MenuItem(
        title: 'Miscellaneous',
        extra: []
    ),
    'Use in Framework' => [
        'use-in-framework' => new MenuItem(
            title: 'Use In Framework',
            extra: []
        ),
    ]
];
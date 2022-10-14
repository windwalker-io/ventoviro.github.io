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
    'name' => 'Attributes',
    'part' => 'Components',
    'items' => [
        'index' => new MenuItem(
            title: 'Introduction',
            extra: []
        ),
        'available-type-actions' => new MenuItem(
            title: 'Available Types & Actions',
            extra: []
        ),
        'write-handlers' => new MenuItem(
            title: 'Writing Handlers',
            extra: []
        ),
        'misc' => new MenuItem(
            title: 'Miscellaneous',
            extra: []
        )
    ]
];

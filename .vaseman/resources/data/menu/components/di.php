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
    'name' => 'DI',
    'part' => 'Components',
    'items' => [
        'index' => new MenuItem(
            title: 'Introduction',
            extra: []
        ),
    ]
];

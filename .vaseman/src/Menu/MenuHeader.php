<?php

/**
 * Part of site project.
 *
 * @copyright  Copyright (C) 2022 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace App\Menu;

/**
 * The MenuHeader class.
 */
class MenuHeader
{
    public string $title = '';
    public array $extra = [];

    /**
     * @param  string  $title
     * @param  array   $extra
     */
    public function __construct(string $title, array $extra = [])
    {
        $this->title = $title;
        $this->extra = $extra;
    }
}

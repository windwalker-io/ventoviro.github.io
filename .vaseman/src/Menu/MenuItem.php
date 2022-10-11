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
 * The MenuItem class.
 */
class MenuItem
{
    public string $title = '';
    public string $description = '';
    public array $extra = [];

    /**
     * @param  string  $title
     * @param  string  $description
     * @param  array   $extra
     */
    public function __construct(string $title, string $description = '', array $extra = [])
    {
        $this->title = $title;
        $this->description = $description;
        $this->extra = $extra;
    }
}

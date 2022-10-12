<?php

namespace App\View;

use App\Menu\MenuItem;

if ($config['chapter'] ?? '') {
    $config['title'] .= ' | ' . $config['chapter'];
}

/**
 * @var $menu MenuItem[]
 * @var $menuItem MenuItem
 */

// Menu
$menuName = $config['menu'];
$menu = include PROJECT_DATA_ROOT . '/resources/data/menu/' . $menuName . '.php';

?>

@extends('global.body')

@push('script')
    <script src="{{ $asset->path('js/doc.js') }}"></script>
@endpush

@section('body')
    <x-doc-banner :part="$config['part'] ?? ''">
        {{ $config['chapter'] ?? '' }}
    </x-doc-banner>

    <div class="container my-5">
        <div class="row">
            <div class="col-lg-3">
                <h5 class="fw-bold">
                    Menu: {{ $config['chapter'] }}
                </h5>

                <ul class="nav flex-column">
                    @foreach ($menu as $alias => $menuItem)
                        @if ($menuItem instanceof MenuItem)
                            @include('global.doc.menu-item', compact('menuItem', 'alias'))
                        @elseif (is_array($menuItem))
                            <li class="nav-item">
                                <div class="nav-link text-dark fw-bold ps-0">
                                    {{ $alias }}
                                </div>

                                <ul class="nav flex-column nav--submenu ps-3">
                                    @foreach ($menuItem as $alias => $menuItem)
                                        @include('global.doc.menu-item', compact('menuItem', 'alias'))
                                    @endforeach
                                </ul>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>

            <div class="col-lg-9">
                <article class="article-content" data-content>
                    @yield('content', $content ?? '')
                </article>
            </div>
        </div>
    </div>
@stop

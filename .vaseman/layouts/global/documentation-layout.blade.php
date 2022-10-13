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
        {{ $menu['name'] ?? '' }}

        <x-slot name="bottom">
            @include('components.doc.breadcrumb')
        </x-slot>
    </x-doc-banner>

    <div class="l-documentation container my-5">
        <div class="row">
            <div class="l-documentation__menu col-lg-3">
                <h5 class="fw-bold">
                    Menu: {{ $menu['name'] }}
                </h5>

                <ul class="nav flex-column">
                    @foreach ($menu['items'] ?? [] as $alias => $menuItem)
                        @if ($menuItem instanceof MenuItem)
                            @include('components.doc.menu-item', compact('menuItem', 'alias'))
                        @elseif (is_array($menuItem))
                            <li class="nav-item">
                                <div class="nav-link text-dark fw-bold ps-0">
                                    {{ $alias }}
                                </div>

                                <ul class="nav flex-column nav--submenu ps-3">
                                    @foreach ($menuItem as $alias => $menuItem)
                                        @include('components.doc.menu-item', compact('menuItem', 'alias'))
                                    @endforeach
                                </ul>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>

            <div class="col-lg-7 l-documentation__body">
                <article class="article-content" data-content
                    data-bs-spy="scroll"
                    data-bs-target="#toc"
                    data-bs-root-margin="0px 0px -100px"
                    data-bs-smooth-scroll="true"
                >
                    @yield('content', $content ?? '')
                </article>
            </div>

            <div class="col-lg-2 l-documentation__toc">
                <div class="sticky-top" style="top: 65px">
                    <h5>Table of Contents</h5>

                    <div data-toc>

                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

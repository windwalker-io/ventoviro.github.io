<?php

$menuRoute = $config['menu'];
$segments = explode('/', $menuRoute);
array_pop($segments);

$part = implode('/', $segments);
?>

<div class="breadcrumb m-0">
    <a class="breadcrumb-item link-primary small"
        href="{{ $uri->path('documentation/') }}"
        style="text-decoration: none">
        Documentation
    </a>

    @if ($menu['part'] ?? null)
        <a class="breadcrumb-item link-primary small"
            href="{{ $part ? $uri->path('documentation/' . $part . '.html') : '#' }}"
            style="text-decoration: none">
            {{ $menu['part'] }}
        </a>
    @endif

    @if ($menu['name'] ?? '')
        <a class="breadcrumb-item link-primary small"
            style="text-decoration: none">
            {{ $menu['name'] ?? '' }}
        </a>
    @endif
</div>

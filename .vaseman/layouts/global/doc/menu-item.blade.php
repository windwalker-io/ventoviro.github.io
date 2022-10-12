<?php
$segments = $uri->routeArray;
array_shift($segments);
$currentRoute = implode('/', $segments);

$menuRoute = $config['menu'] . '/' . $alias;

$active = $menuRoute === $currentRoute;
?>
<li class="nav-item">
    <a href="{{ $uri->path('documentation/' . $config['menu'] . '/' . $alias . '.html') }}"
        class="nav-link ps-0 {{ $active ? 'active text-success fw-bold' : '' }}">
        {{ $menuItem->title }}
    </a>
</li>

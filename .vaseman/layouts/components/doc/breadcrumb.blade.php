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

    @if ($config['part'])
        <a class="breadcrumb-item link-primary small"
            href="{{ $part ? $uri->path('documentation/' . $part . '.html') : '#' }}"
            style="text-decoration: none">
            {{ $config['part'] }}
        </a>
    @endif
</div>

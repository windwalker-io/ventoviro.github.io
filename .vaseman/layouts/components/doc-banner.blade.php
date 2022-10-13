<?php

$attributes = $attributes->class('text-bg-success py-4');
?>

<div {!! $attributes !!}>
    <div class="container">
        <div class="gap-2">
            <div class="mb-1  fw-bold"
                style="margin-top: -0.5rem;">
                <a
                    class="link-light"
                    style="text-decoration: none; opacity: .85; "
                >
                    {{ $part ?? '' }}
                </a>
            </div>
            <h2 class="m-0 text-uppercase fw-bold">
                {{ $slot ?? '' }}
            </h2>
            @if ($bottom ?? null)
                {!! $bottom() !!}
            @endif
        </div>
    </div>
</div>

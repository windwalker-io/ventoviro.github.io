<?php

$attributes = $attributes->class('text-bg-success py-4');
?>

<div {!! $attributes !!}>
    <div class="container">
        <div class="gap-2">
            <div class="mb-1 text-light  fw-bold" style="--bs-text-opacity: .75; margin-top: -0.5rem;">
                {{ $part ?? '' }}
            </div>
            <h2 class="m-0 text-uppercase fw-bold">
                {{ $slot ?? '' }}
            </h2>
        </div>
    </div>
</div>

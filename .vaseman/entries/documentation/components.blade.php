---
title: Components
part: Components
---
<?php

namespace App\View;

use App\Menu\MenuItem;
use Windwalker\Utilities\StrNormalize;

$sections = $docTree['pages']['components'] ?? [];

/**
 * @var $component MenuItem
 */
?>

@extends('global.default-layout')

{{--@section('breadcrumb')--}}
{{--    @include('components.doc.breadcrumb')--}}
{{--@stop--}}

@section('content')
    <div>
        <div>
            @foreach ($sections as $section => $components)
                    <?php $className = StrNormalize::toKebabCase($section); ?>
                <section class="l-section l-section--{{ $className }} mb-5">
                    <h3>{{ $section }}</h3>

                    <div class="row row-cols-lg-3 row-cols-2 mt-4">
                        @foreach ($components as $package => $component)
                            <?php
                            $wip = $component->extra['wip'] ?? false;
                            ?>
                            <div class="col mb-4">
                                <div class="card h-100 shadow-sm">
                                    <div class="card-body">
                                        <div class="mb-1 d-flex align-items-center">
                                            <span class="font-monospace" style="color: var(--bs-success)">
                                                windwalker/{{ $package }}
                                            </span>
                                            @if ($wip)
                                                <span class="ms-auto badge border border-secondary text-secondary">WIP</span>
                                            @endif
                                        </div>
                                        <h5 class="card-title">
                                            <a class="link-primary stretched-link"
                                                style="text-decoration: none; {{ $wip ? 'pointer-events: none;' : '' }}"
                                                href="{{ $uri->path('documentation/components/' . $package . '/') }}">
                                                {{ $component->title }}
                                            </a>
                                        </h5>
                                        <div class="text-muted">
                                            {{ $component->description }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endforeach
        </div>

    </div>
@stop

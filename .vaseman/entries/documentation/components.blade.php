---
title: Components
---
<?php

namespace App\View;

use Windwalker\Utilities\StrNormalize;

$sections = include PROJECT_DATA_ROOT . '/resources/data/page/components.php';

?>

@extends('global.default-layout')

@section('content')
    <div>
        <div>
            @foreach ($sections as $section => $components)
                <?php $className = StrNormalize::toKebabCase($section); ?>
                <section class="l-section l-section--{{ $className }} mb-5">
                    <h3>{{ $section }}</h3>

                    <div class="row row-cols-3 mt-4">
                        @foreach ($components as $package => $component)
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <div class="mb-1">
                                        <code style="color: var(--bs-success)">
                                            windwalker/{{ $package }}
                                        </code>
                                    </div>
                                    <h5 class="card-title">
                                        <a class="link-primary stretched-link"
                                            style="text-decoration: none"
                                            href="{{ $uri->path('documentation/components/' . $package . '/') }}">
                                            {{ $component['title'] }}
                                        </a>
                                    </h5>
                                    <div class="text-muted">
                                        {{ $component['description'] }}
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

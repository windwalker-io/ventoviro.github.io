---
title: Documentation
---

@extends('global.default-layout')

@section('content')
    <div class="">
        <div class="row row-cols-lg-4 row-cols-md-3 row-cols-2">
            <div class="col">
                {{-- Components --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-center gap-2 w-100">
                            <h5 class="card-title m-0">
                                Components
                            </h5>
                            <span class="badge bg-secondary rounded-pill">
                                {{ $config['doc']['components_count'] ?? 31 }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body border-top text-muted text-center">
                        A set of standalone PHP components.
                    </div>
                    <div class="card-body pt-0">
                        <div class="text-center">
                            <a href="{{ $uri->path('documentation/components.html') }}"
                                class="btn btn-primary w-100 stretched-link"
                                style="max-width: 150px">
                                See
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                {{-- Cook Book --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-body text-center">
                        <h5 class="card-title m-0">
                            Cookbook
                        </h5>
                    </div>
                    <div class="card-body border-top text-muted text-center">
                        Step by step tutorial to learn Windwalker.
                    </div>
                    <div class="card-body pt-0">
                        <div class="text-center">
                            <a href="{{ $uri->path('documentation/cookbook.html') }}"
                                class="btn btn-primary w-100 stretched-link disabled"
                                style="max-width: 150px">
                                Work in process
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                {{-- Framework --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-body text-center">
                        <h5 class="card-title m-0">
                            Framework
                        </h5>
                    </div>
                    <div class="card-body border-top text-muted text-center">
                        The Framework MVC tutorial and documentation.
                    </div>
                    <div class="card-body pt-0">
                        <div class="text-center">
                            <a href="{{ $uri->path('documentation/framework.html') }}"
                                class="btn btn-primary w-100 stretched-link disabled"
                                style="max-width: 150px">
                                Work in process
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                {{-- RAD --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-body text-center">
                        <h5 class="card-title m-0">
                            Unicorn RAD
                        </h5>
                    </div>
                    <div class="card-body border-top text-muted text-center">
                        The RAD package to build web app rapidly.
                    </div>
                    <div class="card-body pt-0">
                        <div class="text-center">
                            <a href="{{ $uri->path('documentation/rad.html') }}"
                                class="btn btn-primary w-100 stretched-link disabled"
                                style="max-width: 150px">
                                Work in process
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                {{-- RAD --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-body text-center">
                        <h5 class="card-title m-0">
                            Legacy
                        </h5>
                    </div>
                    <div class="card-body border-top text-muted text-center">
                        The Windwalker 2.x / 3.x documentation.
                    </div>
                    <div class="card-body pt-0">
                        <div class="text-center">
                            <a href="https://windwalker-io.github.io/site-legacy"
                                target="_blank"
                                class="btn btn-primary w-100 stretched-link"
                                style="max-width: 150px">
                                See
                                <span class="fa fa-external-link small"></span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

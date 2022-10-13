@extends('global.body')

@section('body')
    <div class="text-bg-success py-4">
        <div class="container">
            <h2 class="m-0 text-uppercase">
                {{ $config['title'] ?? '' }}
            </h2>
            @yield('breadcrumb')
        </div>
    </div>

    <div class="l-main-body bg-light">
        <div class="container py-5">
            @yield('content', $content ?? '')
        </div>
    </div>
@stop

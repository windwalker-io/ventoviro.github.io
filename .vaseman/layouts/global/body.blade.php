@extends('global.html')

@push('meta')
{{-- GA --}}
@endpush

@section('superbody')
@section('header')

    <header class="l-main-header">
        <nav class="l-main-nav navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
            <div class="container">
                <a class="navbar-brand" href="{{ $uri->path() }}">
                    <img src="{{ $asset->path('images/logo-cw-h.svg') }}"
                        alt="LOGO"
                        style="height: 25px;"
                    />
                </a>
                <button class="navbar-toggler" type="button"
                    data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                    </ul>

                    <ul class="navbar-nav mb-2 mb-lg-0">
                        {{--<li class="nav-item">--}}
                        {{--    <a class="nav-link active" aria-current="page"--}}
                        {{--        href="{{ $uri->path() }}">--}}
                        {{--        About--}}
                        {{--    </a>--}}
                        {{--</li>--}}
                        <li class="nav-item">
                            <a class="nav-link active"
                                href="{{ $uri->path('documentation') }}">
                                <i class="fa-solid fa-file-lines"></i>
                                Documentation
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active"
                                target="_blank"
                                href="https://github.com/windwalker-io/framework">
                                <i class="fa-brands fa-github"></i>
                                Github
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
@show

@section('body')
@yield('content', $content ?? 'Content')
@show

@section('copyright')
    <div id="copyright">
        <div class="">
            <hr class="mt-0" />

            <footer class="container d-flex flex-column flex-lg-row align-items-center justify-content-between">
                <div>
                    &copy; Windwalker {{ date('Y') }} - Made by
                    <a target="_blank" href="https://lyrasoft.net"
                        style="text-decoration: none"
                    >
                        LYRASOFT
                    </a>
                </div>

                <div>
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link" href="https://windwalker-io.github.io/site-legacy"
                                target="_blank">
                                3.x Docs
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="https://github.com/windwalker-io"
                                target="_blank">
                                GitHub
                            </a>
                        </li>
                    </ul>
                </div>
            </footer>
        </div>
    </div>
@show
@stop

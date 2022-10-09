<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=2.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <link rel="shortcut icon" href="{{ $asset->path('images/logo-icon.png') }}" />

    <title>@yield('title', $helper->page->title($config['title'] ?? ''))</title>

    <meta property="og:image" content="{{ $config['og']['image'] ?? '' }}">

    @stack('meta')
    @yield('meta')

    {{--<link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism.min.css" rel="stylesheet" />--}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-tomorrow.min.css" rel="stylesheet" />
    <link href="{{ $asset->path('css/bootstrap.css') }}" rel="stylesheet" />
    @stack('style')
    <link href="{{ $asset->path('css/main.css') }}" rel="stylesheet">

    @stack('head')
</head>
<body class="{{ $helper->page->bodyClass() }}" style="margin-top: 50px">
@yield('superbody')

<script src="{{ $asset->path('vendor/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ $asset->path('vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
<script src="https://kit.fontawesome.com/cefe7e8fb3.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-core.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/autoloader/prism-autoloader.min.js"></script>

@stack('script')
</body>
</html>

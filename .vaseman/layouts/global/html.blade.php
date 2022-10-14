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

    {{--<link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-tomorrow.min.css" rel="stylesheet" />--}}
    <link href="https://unpkg.com/prism-themes@1.9.0/themes/prism-dracula.css" rel="stylesheet" />
    <link href="{{ $asset->path('css/bootstrap.css') }}" rel="stylesheet" />
    @stack('style')
    <link href="{{ $asset->path('css/main.css') }}" rel="stylesheet">

    @stack('head')

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-MYMEG7N4RV"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-MYMEG7N4RV');
    </script>
</head>
<body class="{{ $helper->page->bodyClass() }}" style="margin-top: 56px">
@yield('superbody')

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://kit.fontawesome.com/cefe7e8fb3.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-core.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/autoloader/prism-autoloader.min.js"></script>

@stack('script')
</body>
</html>

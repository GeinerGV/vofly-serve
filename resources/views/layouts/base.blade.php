<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Laravel'))</title>

    <!-- Scripts -->
    @auth
        <script>
            window.AUTH_TOKEN = "Bearer {{Auth::user()->api_token}}";
        </script>
    @endauth
    <script src="{{ mix('js/app.js') }}" defer></script>
    <script src="https://unpkg.com/jquery@3.5.1/dist/jquery.js"></script>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ mix('css/vendor.css') }}" rel="stylesheet">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    @yield('head')
</head>
<body>
    <div class="wrapper-bubbles">
        <ul class="colorlib-bubbles">
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
        </ul>
        <div class="ocean">
            <div class="wave"></div>
            <div class="wave"></div>
        </div>
    </div>
    <div id="app">
		<div id="alert-root"></div>
        @yield('navbar')

        <main>
            @yield('content')
        </main>
		<div id="modal-root"></div>
    </div>
</body>
</html>

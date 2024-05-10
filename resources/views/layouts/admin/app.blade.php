<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Dashboard') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    
    <!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="{{ asset("assets/login/images/icons/favicon.ico")}}"/>
    <!--===============================================================================================-->
        <link rel="stylesheet" type="text/css" href="{{ asset("assets/login/vendor/bootstrap/css/bootstrap.min.css") }}">
    <!--===============================================================================================-->
        <link rel="stylesheet" type="text/css" href="{{ asset("assets/login/fonts/font-awesome-4.7.0/css/font-awesome.min.css") }}">
    <!--===============================================================================================-->
        <link rel="stylesheet" type="text/css" href="{{ asset("assets/login/fonts/Linearicons-Free-v1.0.0/icon-font.min.css") }}">
    <!--===============================================================================================-->
        <link rel="stylesheet" type="text/css" href="{{ asset("assets/login/vendor/animate/animate.css") }}">
    <!--===============================================================================================-->	
        <link rel="stylesheet" type="text/css" href="{{ asset("assets/login/vendor/css-hamburgers/hamburgers.min.css") }}">
    <!--===============================================================================================-->
        <link rel="stylesheet" type="text/css" href="{{ asset("assets/login/vendor/animsition/css/animsition.min.css") }}">
    <!--===============================================================================================-->
        <link rel="stylesheet" type="text/css" href="{{ asset("assets/login/css/util.css") }}">
        <link rel="stylesheet" type="text/css" href="{{ asset("assets/login/css/main.css") }}">
    <!--===============================================================================================-->
    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @stack('style_src')
</head>
<body>
    <div id="app">
        <main class="">
            @yield('content')
        </main>
    </div>
    <!--===============================================================================================-->
	    <script src="{{ asset("assets/login/vendor/jquery/jquery-3.2.1.min.js")}}"></script>
    <!--===============================================================================================-->
        <script src="{{ asset("assets/login/vendor/animsition/js/animsition.min.js")}}"></script>
    <!--===============================================================================================-->
        <script src="{{ asset("assets/login/vendor/daterangepicker/moment.min.js")}}"></script>
        <script src="{{ asset("assets/login/vendor/daterangepicker/daterangepicker.js")}}"></script>
    <!--===============================================================================================-->
</body>
</html>

<!doctype html>
<html lang="en" class="no-js">
<head>
    <style>
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="/assets/images/SVG/Logo.svg" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css?family=Prompt" rel="stylesheet">

    @include('layouts._style')

    @yield('style')

    @include('layouts._headscript')

    <title>CryptovationX | @yield('title')</title>
</head>
<body style="background: #f2f4f6;">
<div id="asset">

    @include('layouts._navbar')

    <main class="cd-main-content">

        @include('layouts._sidenav')

        <div class="content-wrapper">

            {{--Loader--}}
            @yield('loader', View::make('layouts._loader'))
            <loader></loader>
            {{--Loader--}}

            @include('layouts._header')

            @yield('content')

            <notify ref="notification" notitype="success" content="test"></notify>

        </div> <!-- .content-wrapper -->
    </main>
</div>
{{--    @include('layouts._footer')--}}
@include('layouts._script')
@include('layouts._javascript')

@yield('script')


</body>
</html>
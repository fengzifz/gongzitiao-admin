<!DOCTYPE html>
<html lang="en">
<head>
    <base href="./../">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description" content="CoreUI - Open Source Bootstrap Admin Template">
    <meta name="author" content="Łukasz Holeczek">
    <meta name="keyword" content="Bootstrap,Admin,Template,Open,Source,jQuery,CSS,HTML,RWD,Dashboard">
    <title>YESHM 工资条</title>
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="assets/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <!-- Main styles for this application-->
    <link href="{{ asset('plugins/coreui/css/coreui.css') }}" rel="stylesheet">
</head>
<body class="c-app">

@include('layouts.sidebar')

<div class="c-wrapper c-fixed-components">

    @include('layouts.header')

    <div class="c-body">
        <main class="c-main">
            <div class="container-fluid">
                <div class="fade-in">
                    @yield('content')
                </div>
            </div>
        </main>

        @include('layouts.footer')

    </div>
</div>
<!-- CoreUI and necessary plugins-->
<script src="{{ asset('plugins/coreui/js/coreui.bundle.js') }}"></script>
</body>
</html>

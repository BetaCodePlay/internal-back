<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{ $title }}</title>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="">
    <!--===============================================================================================-->
    <link rel="shortcut icon" href="{{ $favicon }}">
    <link rel="apple-touch-icon" sizes="57x57" href="{{ $favicon }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ $favicon }}">
    <!--===============================================================================================-->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ mix('css/vendor.min.css', 'auth') }} ">
    <link rel="stylesheet" href="{{ mix('css/custom.min.css', 'auth') }} ">
    <link rel="stylesheet" href="{{ asset("themes/$theme") }}?v=1.130">
    {{--<link rel="stylesheet" href="{{ asset("themes/unicornio/theme.min.css") }}?v=1.113">--}}
    <!--===============================================================================================-->
</head>
<body class="body-auth">
<div class="limiter">
    @yield('content')
</div>


@yield('modals')

<script src="{{ mix('js/manifest.js', 'auth') }}"></script>
<script src="{{ mix('js/vendor.js', 'auth') }}"></script>
<script src="{{ mix('js/custom.min.js', 'auth') }}"></script>
@yield('scripts')
</body>
</html>

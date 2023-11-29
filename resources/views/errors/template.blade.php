<!DOCTYPE html>
<html lang="en">
<head>
    <title>@yield('code')</title>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="">
    <!--===============================================================================================-->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ mix('css/vendor.min.css', 'auth') }} ">
    <link rel="stylesheet" href="{{ mix('css/custom.min.css', 'auth') }} ">
    <!--===============================================================================================-->
</head>
<body class="body-auth">
<div class="limiter">
    <div class="container-auth">
        <div class="container-auth-bg" style="background-image: url('https://bestcasinos-llc.s3.us-east-2.amazonaws.com/templates/bg-login-v2.jpg')"></div>
        <div class="bg-opacity"></div>
        <div class="content">
            <div class="content-ex">
                <div class="error-figure"><img src="https://bestcasinos-llc.s3.us-east-2.amazonaws.com/templates/error-page.png"></div>
                <div class="error-title">@yield('code')</div>
                <div class="error-subtitle">@yield('message')</div>
            </div>
        </div>
    </div>
</div>


@yield('modals')

<script src="{{ mix('js/manifest.js', 'auth') }}"></script>
<script src="{{ mix('js/vendor.js', 'auth') }}"></script>
<script src="{{ mix('js/custom.min.js', 'auth') }}"></script>
@yield('scripts')
</body>
</html>

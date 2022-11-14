<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="">
    <link rel="shortcut icon" href="{{ $favicon }}">
    <link rel="apple-touch-icon" sizes="57x57" href="{{ $favicon }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ $favicon }}">
    <link rel="stylesheet" href="{{ mix('css/vendor.min.css', 'auth') }} ">
    <link rel="stylesheet" href="{{ mix('css/custom.min.css', 'auth') }} ">
</head>
<body class="double-diagonal dark auth-page">
<div class="preloader" id="preloader">
    <div class="logopreloader">
        <img src="{{ $logo->img_dark }}" alt="{{ $whitelabel_description }}" width="350">
    </div>
    <div class="loader" id="loader"></div>
</div>
<div class="wrapper">
    <div class="languages-menu" id="languages-menu">
        <a class="languages-menu-selected">
            <img class="lang-flag" src="{{ $selected_language['flag'] }}"
                 alt="{{ $selected_language['name'] }}">
            <span class="title-lang">
            {{ $selected_language['name'] }}
            <i class="fa fa-caret-down" aria-hidden="true"></i>
        </span>
        </a>
        <ul class="languages-submenu">
            @foreach ($languages as $language)
                <li>
                    <a href="{{ route('core.change-language', [$language['iso']]) }}"
                       class="change-language" data-locale="{{ $language['iso'] }}">
                        <img class="lang-flag" src="{{ $language['flag'] }}" alt="{{ $language['name'] }}">
                        <span>{{ $language['name'] }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
    <div class="container-fluid user-auth">
        <div class="row">
            <div class="d-none d-lg-block col-lg-4">
            </div>
            <div class="col-xs-12 col-lg-4">
                <div class="form-container">
                    <div>
                        <div class="text-center d-md-block my-5">
                            <img src="{{  $logo->img_dark }}" alt="{{ $whitelabel_description }}" width="350">
                        </div>
                        <form class="custom-form" action="{{ route('auth.authenticate') }}" id="login-form">
                            <div class="form-group">
                                <input class="form-control" type="text" placeholder="{{ _i('Username')}}"
                                       name="username" id="username" autocomplete="off" required>
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="password" placeholder="{{ _i('Password') }}"
                                       name="password" id="password" autocomplete="off" required>
                            </div>
                            <div class="form-group">
                                <button class="custom-button login mb-5" id="login" type="button"
                                        data-loading-text="{{ _i('Please wait...') }}">
                                    {{ _i('Login') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <p class="text-center copyright-text">
                    {{ $whitelabel_info->copyright ? _i('Developed by Dotworkers. Operated by') : '' }} {{ $whitelabel_description }}
                    © {{ _i('Copyright') }} - {{ date('Y') }}. {{ _i('All rights reserved') }}
                </p>
            </div>
        </div>
    </div>
</div>
<script src="{{ mix('js/manifest.js', 'auth') }}"></script>
<script src="{{ mix('js/vendor.js', 'auth') }}"></script>
<script src="{{ mix('js/custom.min.js', 'auth') }}"></script>
<script>
    $(function () {
        Auth.login();
    });
</script>
</body>
</html>

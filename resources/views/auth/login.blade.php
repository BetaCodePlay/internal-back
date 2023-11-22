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
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ mix('css/vendor.min.css', 'auth') }} ">
    <link rel="stylesheet" href="{{ mix('css/custom.min.css', 'auth') }} ">
    <!--===============================================================================================-->
</head>
<body class="body-login">

<div class="limiter">
    <div class="container-login">
        <div class="wrap-login">
            <div class="login-preview-bg" style="background-image: url('https://bestcasinos-llc.s3.us-east-2.amazonaws.com/templates/bg-login-v1.jpg')">
                <img class="login-logo" src="{{  $logo->img_dark }}" alt="{{ $whitelabel_description }}" width="350">
            </div>

            <form class="login-form validate-form" action="{{ route('auth.authenticate') }}" id="login-form">
                <div class="login-form-ex">
                    <div class="login-nav">
                        <button type="button" class="btn active">Por email</button>
                        <button type="button" class="btn">Por usuario</button>
                    </div>
                    <div class="wrap-input-title">{{ _i('Welcome')}}</div>
                    <div class="wrap-input-subtitle">
                        {{ _i("Today is a new day. It's your day. You shape it.")}}<br>
                        {{ _i('Sign in to start managing your project.')}}
                    </div>
                    <div class="login-form-line">
                        <div class="wrap-input-login validate-input">
                            <input class="input-login" type="text" name="username" id="username" autocomplete="off" placeholder="{{ _i('Username')}}" required>
                        </div>

                        <div class="wrap-input-login validate-input" data-validate="{{ _i('Enter password')}}">
						<span class="btn-show-pass">
							<i class="fa fa-eye"></i>
						</span>
                            <input class="input-login" type="password" name="password" id="password" autocomplete="off" placeholder="{{ _i('Password') }}" required>
                        </div>

                        <div class="wrap-input-login">
                            <a href="#" class="a-login">have you forgotten your password?</a>
                        </div>

                        <div class="container-login-form-btn">
                            <button class="btn-login" id="login" type="button" data-loading-text="{{ _i('Please wait...') }}">
                                {{ _i('Login') }}
                            </button>
                        </div>

                        <div class="wrap-input-divider">
                            O
                        </div>

                        <div class="container-login-form-btn">
                            <button class="btn-login-google" type="button" data-loading-text="{{ _i('Please wait...') }}">
                                <img src="https://bestcasinos-llc.s3.us-east-2.amazonaws.com/templates/google.png"> {{ _i('Sign in with Google') }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{--<footer>

    <div class="dropdown text-center p-b-20">
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
        <div id="dropDownSelect1"></div>

    </div>
    <p class="text-center copyright-text">
        {{ $whitelabel_info->copyright ? _i('Developed by Betsweet. Operated by') : '' }} {{ $whitelabel_description }}
        © {{ _i('Copyright') }} - {{ date('Y') }}. {{ _i('All rights reserved') }}
    </p>


</footer>--}}
@include('auth.modals.change-password')

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

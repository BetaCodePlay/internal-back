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
    <link rel="stylesheet" href="{{ mix('css/vendor.min.css', 'auth') }} ">
    <link rel="stylesheet" href="{{ mix('css/custom.min.css', 'auth') }} ">
    <!--===============================================================================================-->
</head>
<body>

<div class="limiter">
    <div class="container-login100">
        <div class="wrap-login100">
            <form class="login100-form validate-form" action="{{ route('auth.authenticate') }}" id="login-form">
					<span class="login100-form-title p-b-48">
						<img class="LogoPrincipal" src="{{  $logo->img_dark }}" alt="{{ $whitelabel_description }}" width="350">
					</span>

                <div class="wrap-input100 validate-input">
                    <input class="input100" type="text" name="username" id="username" autocomplete="off" required>
                    <span class="focus-input100" data-placeholder="{{ _i('Username')}}"></span>
                </div>

                <div class="wrap-input100 validate-input" data-validate="{{ _i('Enter password')}}">
						<span class="btn-show-pass">
							<i class="icons zmdi zmdi-eye"></i>
						</span>
                    <input class="input100" type="password" name="password" id="password" autocomplete="off" required>
                    <span class="focus-input100" data-placeholder="{{ _i('Password') }}"></span>
                </div>

                <div class="container-login100-form-btn">
                    <div class="wrap-login100-form-btn">
                        <div class="login100-form-bgbtn"></div>
                        <button class="login100-form-btn" id="login" type="button"
                                data-loading-text="{{ _i('Please wait...') }}">
                            {{ _i('Login') }}
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<footer>

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


</footer>

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

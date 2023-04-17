<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('back/css/vendor.min.css') }}?v=2">
    <link rel="stylesheet" href="{{ asset('back/css/custom.min.css') }}?v=12">
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans%3A400%2C300%2C500%2C600%2C700%7CPlayfair+Display%7CRoboto%7CRaleway%7CSpectral%7CRubik">
    @if (\Dotworkers\Configurations\Configurations::getWhitelabel() == 109)
        <link rel="shortcut icon" href="{{ asset('commons/img/bloko-favicon.png') }}">
    @else
        <link rel="shortcut icon" href="{{ $favicon }}">
    @endif
    <link rel="apple-touch-icon" sizes="57x57" href="{{ $favicon }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ $favicon }}">
    <title>{{ $title ?? _i('BackOffice') }}</title>
    <link rel="stylesheet" href="{{ asset('commons/css/template.min.css') }}?v=0.19">
    @yield('styles')
    <style>
        li.has-active .u-side-nav-opened {
            background-color: #f4f4f41f !important;
        }
    </style>
</head>
<body class=" currency-theme-{{ session('currency') }}">
@include('back.layout.header')
<main class="container-fluid px-0 g-pt-65">
    <div class="row no-gutters g-pos-rel g-overflow-x-hidden">
        @include('back.layout.sidebar')
        <div class="col g-ml-45 g-ml-0--lg g-pb-65--md">
            @include('back.layout.warning')
            @if(isset($iphone))
                @if($iphone)
                    @include('back.layout.search')
                @endif
            @endif
{{--            @if(!in_array(\Dotworkers\Security\Enums\Roles::$admin_Beet_sweet, session('roles')))--}}
                <div class="g-pt-20 g-pr-15 g-pl-15">
                    <div class="row">
                        <div class="offset-md-8 offset-lg-9 offset-xl-9 col-xs-12 col-sm-12 col-md-4 col-lg-3 col-xl-3">
                            <div class="form-group">
                                <select name="timezone" class="form-control change-timezone" data-route="{{ route('core.change-timezone') }}">
                                    @foreach ($global_timezones as $global_timezone)
                                        <option value="{{ $global_timezone['timezone'] }}" {{ $global_timezone['timezone'] == session()->get('timezone') ? 'selected' : '' }}>
                                            {{ $global_timezone['text'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
{{--            @endif--}}

            <div class="g-pa-20">
                @yield('content')
            </div>
            @include('back.layout.footer')
        </div>
    </div>
</main>
<script src="{{ mix('js/manifest.js', 'back') }}"></script>
<script src="{{ mix('js/vendor.js', 'back') }}"></script>
<script src="{{ mix('js/custom.min.js', 'back') }}"></script>
<script src="{{ asset('back/js/scripts.min.js') }}?v=21"></script>
@yield('scripts')
@can('access', [\Dotworkers\Security\Enums\Permissions::$tawk_chat])
    @include('back.layout.tawk')
@endif
@include('back.layout.chat')
<script>
    @if (env('APP_ENV') == 'testing')
    $(function () {
        //let socket = new Socket();
        //socket.initChannel('{{ session()->get('betpay_client_id') }}', '{{ $favicon }}', '{{ route('push-notifications.store') }}');
    });
    @endif
</script>
</body>
</html>


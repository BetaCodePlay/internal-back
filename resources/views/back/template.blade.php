<!doctype html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('back/css/vendor.min.css') }}?v=2">
    <link rel="stylesheet" href="{{ asset('back/css/custom.min.css') }}?v=13">
    <link rel="stylesheet"
        href="//fonts.googleapis.com/css?family=Open+Sans%3A400%2C300%2C500%2C600%2C700%7CPlayfair+Display%7CRoboto%7CRaleway%7CSpectral%7CRubik">
    @if (\Dotworkers\Configurations\Configurations::getWhitelabel() == 109)
    <link rel="shortcut icon" href="{{ asset('commons/img/bloko-favicon.png') }}">
    @else
    <link rel="shortcut icon" href="{{ $favicon }}">
    @endif
    <link rel="apple-touch-icon" sizes="57x57" href="{{ $favicon }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ $favicon }}">
    <title>{{ $title ?? _i('BackOffice') }}</title>
    <link rel="stylesheet" href="{{ asset('commons/css/template.min.css') }}?v=0.43">
    @yield('styles')
    <style>
    li.has-active .u-side-nav-opened {
        background-color: #f4f4f41f !important;
    }
    </style>
</head>

<body class="currency-theme-{{ session('currency') }}">
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

                <div class="g-pa-20">
                    @if($mailgun_notifications->active == true)
                    @include('back.layout.email-verify')
                    @endif
                    @yield('content')
                    {{--@if(!empty($action) && $action == \App\Users\Enums\ActionUser::$update_email)
                        @if($iagent == 0)
                            @include('back.users.modals.reset-email')
                        @endif
                    @endif--}}
                </div>
                @include('back.layout.footer')
            </div>
        </div>
    </main>
    <script src="{{ mix('js/manifest.js', 'back') }}"></script>
    <script src="{{ mix('js/vendor.js', 'back') }}"></script>
    <script src="{{ mix('js/custom.min.js', 'back') }}"></script>
    <script src="{{ asset('back/js/scripts.min.js') }}?v=22"></script>

    {{--TODO AGREGAR CDN PARA EXPORTAR PDF--}}
    {{--<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>--}}
    {{--<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>--}}

    @yield('scripts')
    @can('access', [\Dotworkers\Security\Enums\Permissions::$tawk_chat])
    @include('back.layout.tawk')
    @endif
    @include('back.layout.chat')
    <script>
    @if(env('APP_ENV') == 'testing')
    $(function() {
        //let socket = new Socket();
        //socket.initChannel('{{ session()->get('betpay_client_id') }}', '{{ $favicon }}', '{{ route('push-notifications.store') }}');
    });
    @endif

    $(function() {
        let dashboard = new Dashboard();
        dashboard.resetEmail();
        dashboard.showEmail();
    });
    </script>
</body>

</html>
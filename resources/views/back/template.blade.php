<!doctype html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if (\Dotworkers\Configurations\Configurations::getWhitelabel() == 109)
        <link rel="shortcut icon" href="{{ asset('commons/img/bloko-favicon.png') }}">
    @else
        <link rel="shortcut icon" href="{{ $favicon }}">
    @endif
    <link rel="apple-touch-icon" sizes="57x57" href="{{ $favicon }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ $favicon }}">
    <title>{{ $title ?? _i('BackOffice') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('back/css/vendor.min.css') }}?v=6.25">
    <link rel="stylesheet" href="{{ asset('back/css/custom.min.css') }}?v=12.29">
    <link rel="stylesheet" href="{{ mix('css/template.min.css', 'back') }} ">
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
            <div class="g-pa-20">
                @yield('content')
                @if(!empty($action))
                    @if($iagent == 1)
                        @include('back.users.modals.reset-email')
                    @endif
                @endif
            </div>
            @include('back.layout.footer')
        </div>
    </div>
</main>
<script src="{{ mix('js/manifest.js', 'back') }}"></script>
<script src="{{ mix('js/vendor.js', 'back') }}"></script>
<script src="{{ mix('js/custom.min.js', 'back') }}"></script>
<script src="{{ asset('back/js/scripts.min.js') }}?v=24"></script>

{{--TODO AGREGAR CDN PARA EXPORTAR PDF--}}
{{--<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>--}}
{{--<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>--}}

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


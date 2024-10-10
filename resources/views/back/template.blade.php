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
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('back/css/vendor.min.css') }}?v=6.33">
    <link rel="stylesheet" href="{{ asset('back/css/custom.min.css') }}?v=12.43">
    <link href="https://unpkg.com/primeicons/primeicons.css " rel="stylesheet">
    {{--<link rel="stylesheet" href="{{ asset("themes/$theme") }}?v=1.021">--}}
    <link rel="stylesheet" href="{{ asset("themes/mrduck/theme.min.css") }}?v=1.123">

    <!--<link href="https://cdn.datatables.net/2.0.0/css/dataTables.dataTables.css" rel="stylesheet">-->

    <script>
        window.authUserId = parseInt('{{ auth()->id() }}')
        window.timezone = "{{ session('timezone') }}"
        window.userBalance = "{{ getAuthenticatedUserBalance(true) }}"
        String.prototype.formatMoney = function (decimalPlaces = 2, currency = null) {
            return new Intl.NumberFormat("es-ES", {
                style: "currency",
                currency: "{{session('currency')}}",
                minimumFractionDigits: decimalPlaces,
            }).format(this);
        };
        Number.prototype.formatMoney = function (decimalPlaces = 2, currency = null) {
            return new Intl.NumberFormat("es-ES", {
                style: "currency",
                currency: "{{session('currency')}}",
                minimumFractionDigits: decimalPlaces,
            }).format(this);
        };

    </script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.13.1/xlsx.full.min.js"></script>
    @yield('styles')
    <style>
        li.has-active .u-side-nav-opened {
            background-color: #f4f4f41f !important;
        }
    </style>
</head>

<body class="currency-theme-{{ session('currency') }}">
<div id="app">
    @include('back.layout.header')
    <main class="container-fluid px-0 g-pt-65">
        <div class="row no-gutters g-pos-rel">
            @include('back.layout.sidebar')
            <div class="col col-general">
                <div class="g-pa-20 g-pt-30 g-pb-30">
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
</div>

@yield('modals')
<!--
<div class="modal modal-style fade" id="modal-alert-main">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                Mantenimiento programado
            </div>
            <div class="modal-body">
                <p>ATENCION!! EL DIA 04-09-2024 desde 05:00 a 05:15 hs madrugada Argentina vamos realizar tareas de mantenimiento en los juegos para que tengas una mejor experiencia en la plataforma.</p>
                <p>Te recomendamos por favor no realizar apuestas en el lapso de esos horarios.</p>
                <p>Muchas gracias.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-theme close-modal-alert-main" data-dismiss="modal">{{ __('Cerrar') }}</button>
            </div>
        </div>
    </div>
</div>
-->
<script src="{{ mix('js/manifest.js', 'back') }}"></script>
<script src="{{ mix('js/vendor.js', 'back') }}"></script>
<script src="{{ mix('js/custom.min.js', 'back') }}"></script>
<script src="{{ asset('back/js/scripts.min.js') }}?v=24"></script>

<script src=" https://cdn.jsdelivr.net/npm/js-cookie@3.0.5/dist/js.cookie.min.js "></script>
<script>
    $(function () {
        if (Cookies.get('closeModalAlert') === undefined) {
            var inFifteenMinutes = new Date(new Date().getTime() + 60 * 60 * 1000);
            Cookies.set('closeModalAlert', true, {
                expires: inFifteenMinutes
            });
        }

        if (Cookies.get('closeModalAlert') === 'true') {
            $('#modal-alert-main').modal('show');
            $('.close-modal-alert-main').on('click', function () {
                var inFifteenMinutes = new Date(new Date().getTime() + 60 * 60 * 1000);
                Cookies.set('closeModalAlert', false, {
                    expires: inFifteenMinutes
                });
            });
        }
    });
</script>

{{--TODO AGREGAR CDN PARA EXPORTAR PDF--}}
{{--<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>--}}
{{--<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>--}}

@yield('scripts')
{{--@can('access', [\Dotworkers\Security\Enums\Permissions::$tawk_chat])
    @include('back.layout.tawk')
@endif--}}
{{--@include('back.layout.chat')--}}
<script>
    /*  @if(env('APP_ENV') == 'testing')
    $(function() {
        let socket = new Socket();
        socket.initChannel('{{ session()->get('betpay_client_id') }}', '{{ $favicon }}', '{{ route('push - notifications.store ') }}');
        });
        @endif */
    Global.sidebar();
</script>
</body>
</html>

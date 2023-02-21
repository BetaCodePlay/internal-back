<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('back/css/vendor.min.css') }}?v=2">
    <link rel="stylesheet" href="{{ asset('back/css/custom.min.css') }}?v=9">
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans%3A400%2C300%2C500%2C600%2C700%7CPlayfair+Display%7CRoboto%7CRaleway%7CSpectral%7CRubik">
    @if (\Dotworkers\Configurations\Configurations::getWhitelabel() == 109)
        <link rel="shortcut icon" href="{{ asset('commons/img/bloko-favicon.png') }}">
    @else
        <link rel="shortcut icon" href="{{ $favicon }}">
    @endif
    <link rel="apple-touch-icon" sizes="57x57" href="{{ $favicon }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ $favicon }}">
    <title>{{ $title ?? _i('Dotpanel') }}</title>
    @yield('styles')
        </head>
        <body class=" currency-theme-{{ session('currency') }}">
    @include('back.layout.header')
    <main class="container-fluid px-0 g-pt-65">
        <div class="row no-gutters g-pos-rel g-overflow-x-hidden">
            @include('back.layout.sidebar')
            <div class="col g-ml-45 g-ml-0--lg g-pb-65--md">
                <div class="g-pt-20 g-pr-20">
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
    <script>
        @if (env('APP_ENV') == 'testing')
        $(function () {
            //let socket = new Socket();
            //socket.initChannel('{{ session()->get('betpay_client_id') }}', '{{ $favicon }}', '{{ route('push-notifications.store') }}');
        });
        @endif
    </script>
    <script>
        window.__lc = window.__lc || {};
        window.__lc.license = 14932659;
        ;(function (n, t, c) {
            function i(n) {
                return e.h ? e._h.apply(null, n) : e._q.push(n)
            }

            var e = {
                _q: [], _h: null, _v: "2.0", on: function () {
                    i(["on", c.call(arguments)])
                }, once: function () {
                    i(["once", c.call(arguments)])
                }, off: function () {
                    i(["off", c.call(arguments)])
                }, get: function () {
                    if (!e._h) throw new Error("[LiveChatWidget] You can't use getters before load.");
                    return i(["get", c.call(arguments)])
                }, call: function () {
                    i(["call", c.call(arguments)])
                }, init: function () {
                    var n = t.createElement("script");
                    n.async = !0, n.type = "text/javascript", n.src = "https://cdn.livechatinc.com/tracking.js", t.head.appendChild(n)
                }
            };
            !n.__lc.asyncInit && e.init(), n.LiveChatWidget = n.LiveChatWidget || e
        }(window, document, [].slice))
    </script>
    <noscript><a href="https://www.livechatinc.com/chat-with/14932659/" rel="nofollow">Chat with us</a>, powered by <a
            href="https://www.livechatinc.com/?welcome" rel="noopener nofollow" target="_blank">LiveChat</a>
    </noscript>
    </body>
</html>


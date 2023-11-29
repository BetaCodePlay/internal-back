@extends('auth.template')

@section('content')
    <div class="container-auth">
        <div class="container-auth-bg" style="background-image: url('https://bestcasinos-llc.s3.us-east-2.amazonaws.com/templates/bg-login-v2.jpg')"></div>
        <div class="bg-opacity"></div>
        <div class="content">
            <div class="content-ex">
                <div class="auth-figure"><img class="login-logo" src="{{  $logo->img_dark }}" alt="{{ $whitelabel_description }}"></div>
                <div class="auth-title">@yield('code')</div>
                <div class="auth-subtitle">@yield('message')</div>
            </div>
        </div>
    </div>
@endsection

@extends('auth.template')

@section('content')
    <div class="container-auth">
        <div class="container-auth-bg" style="background-image: url('https://bestcasinos-llc.s3.us-east-2.amazonaws.com/templates/bg-login-v2.jpg')"></div>
        <div class="bg-opacity"></div>
        <div class="content">
            <div class="content-ex">
                <div class="auth-title">{{ _i('Reset password') }}</div>
                <div class="auth-subtitle">{{ _i('Enter the new password, remember that it has to be 8 characters and (,) or (.) as special.') }}</div>
                <div class="auth-body">
                    <label>{{ _i('Password')}}</label>
                    <div class="wrap-input-login validate-input" data-validate="{{ _i('Enter password')}}">
						<span class="btn-show-pass">
							<i class="fa fa-eye-slash"></i>
						</span>
                        <input class="input-login" type="password" name="password" id="reset-password" autocomplete="off" placeholder="{{ _i('At least 8 characters') }}" required>
                    </div>
                    <button type="button" class="btn-send" data-route="{{ route('auth.change-password') }}" data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Please wait...') }}">
                        {{ _i('Reset') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

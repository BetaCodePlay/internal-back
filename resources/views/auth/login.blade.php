@extends('auth.template')

@section('content')
    <div class="container-login" data-multi="{{ $envType ? 'true' : 'false' }}">
        <div class="wrap-login">
            @if (\Dotworkers\Configurations\Configurations::getWhitelabel() == 2)
                <div class="login-preview-bg" style="background-image: url('https://bestcasinoswhitelabel.s3.amazonaws.com/planeta/section-images/sin-logo1715699957.png')">
                    <div class="bg-opacity"></div>
                    <img class="login-logo" src="{{  $logo->img_dark }}" alt="{{ $whitelabel_description }}" width="350">
                </div>
            @elseif (\Dotworkers\Configurations\Configurations::getWhitelabel() == 3)
                <div class="login-preview-bg" style="background-image: url('https://bestcasinoswhitelabel.s3.amazonaws.com/mrduck/section-images/portada-login-v11723204529.png')">
                    <div class="bg-opacity"></div>
                    <img class="login-logo" src="{{  $logo->img_dark }}" alt="{{ $whitelabel_description }}" width="350">
                </div>
            @elseif (\Dotworkers\Configurations\Configurations::getWhitelabel() == 4)
                <div class="login-preview-bg" style="background-image: url('https://bestcasinoswhitelabel.s3.us-east-2.amazonaws.com/unicornio/section-images/portada-login-v11723148405.png')">
                    <div class="bg-opacity"></div>
                    <img class="login-logo" src="{{  $logo->img_dark }}" alt="{{ $whitelabel_description }}" width="350">
                </div>
            @elseif (\Dotworkers\Configurations\Configurations::getWhitelabel() == 5)
                <div class="login-preview-bg" style="background-image: url('https://bestcasinoswhitelabel.s3.amazonaws.com/trebolbet/section-images/portada-login-v1.png')">
                    <div class="bg-opacity"></div>
                    <img class="login-logo" src="{{  $logo->img_dark }}" alt="{{ $whitelabel_description }}" width="350">
                </div>
            @else
                <div class="login-preview-bg" style="background-image: url('https://bestcasinoswhitelabel.s3.amazonaws.com/bestcasinos/section-images/portada-login-v11723578339.jpg')">
                    <div class="bg-opacity"></div>
                    <img class="login-logo" src="{{  $logo->img_dark }}" alt="{{ $whitelabel_description }}" width="350">
                </div>
            @endif


            <form class="login-form validate-form" action="{{ route('auth.authenticate') }}" id="login-form">
                <div class="loader-login"></div>
                <div class="login-form-ex">
                    <div class="wrap-input-title">{{ _i('Welcome')}}</div>
                    <div class="wrap-input-subtitle">
                        {{ _i("Choose which method you prefer to log in.")}}
                    </div>

                    @if($envType)
                        <div class="login-nav">
                            <button type="button" class="btn btn-tab-login" data-tag="show-input-email">{{ _i('By email')}}</button>
                            <button type="button" class="btn btn-tab-login" data-tag="show-input-user">{{ _i('By user')}}</button>
                        </div>
                    @endif

                    <div class="login-form-line">
                        @if($envType)
                            <div class="login-tag show-tag show-input-email">
                                <label>{{ _i('E-mail')}}</label>
                                <div class="wrap-input-login validate-input">
                                    <input class="input-login" type="text" name="email" id="email" autocomplete="off" placeholder="{{ _i('example@email.com')}}" required>
                                </div>
                            </div>
                        @endif
                        <div class="login-tag show-input-user">
                            <label>{{ _i('Username')}}</label>
                            <div class="wrap-input-login validate-input">
                                <input class="input-login" type="text" name="username" id="username" autocomplete="off" placeholder="{{ _i('Enter name')}}" required>
                            </div>
                        </div>
                        <label>{{ _i('Password')}}</label>
                        <div class="wrap-input-login validate-input" data-validate="{{ _i('Enter password')}}">
						<span class="btn-show-pass">
							<i class="fa fa-eye-slash"></i>
						</span>
                            <input class="input-login" type="password" name="password" id="password" autocomplete="off" placeholder="{{ _i('At least 8 characters') }}" required>
                        </div>
                        <!--
                        <div class="wrap-input-login">
                            <a href="#" class="a-login">{{ _i('have you forgotten your password?')}}</a>
                        </div>
                            -->
                        <div class="container-login-form-btn">
                            <button class="btn-login disabled" id="login" type="button" data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Please wait...') }}">
                                {{ _i('Login') }}
                            </button>
                        </div>
                        @if($envType)
                            <div class="login-tag login-tag-invisible show-tag show-input-email">
                                <div class="wrap-input-divider">
                                    O
                                </div>

                                <div class="container-login-form-btn">
                                    <button class="btn-login-google" type="button" data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Please wait') }}...">
                                        <img src="https://bestcasinos-llc.s3.us-east-2.amazonaws.com/templates/google.png"> {{ _i('Sign in with Google') }}
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('modals')
    @include('auth.modals.change-password')
@endsection

@section('scripts')
    <script>
        $(function () {
            Auth.login();
        });

        $(window).on('load', function () {
            $('.loader-login').hide();
            $('.login-form-ex').addClass('load');
        });
    </script>
@endsection

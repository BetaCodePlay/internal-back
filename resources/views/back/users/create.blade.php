@extends('back.template')

@section('content')
    <div class="row">
        <div class="col-md-5">
            <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                <header class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                    <div class="media">
                        <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                            {{ $title }}
                        </h3>
                    </div>
                </header>
                <div class="card-block g-pa-15">
                    <form action="{{ route('users.store') }}" id="users-form" method="post">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="username">{{ _i('Username') }}</label>
                                    <input type="text" class="form-control" name="username" id="username">
                                    <small class="form-text text-muted">{{ _i('Only letters and numbers without spaces (4-12 characters)') }}</small>
                                    <small class="form-text text-muted">{{ _i('The username cannot be changed later') }}</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">{{ _i('Password') }}</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="password" id="password">
                                        <div class="input-group-append">
                                            <button class="btn u-input-btn--v1 g-width-40 u-btn-primary g-rounded-right-4 u-btn-3d refresh-password" type="button">
                                                <i class="hs-admin-reload g-absolute-centered g-font-size-16 g-color-white"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">{{ _i('Minimum 8 characters, 1 letter and 1 number') }}</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">{{ _i('Email') }}</label>
                                    <input type="email" class="form-control" name="email" id="email">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="country">{{ _i('Country') }}</label>
                                    <select name="country" id="country" class="form-control">
                                        <option value="">{{ _i('Select...') }}</option>
                                        @foreach ($countries as $country)
                                            <option value="{{ $country->iso }}">
                                                {{ $country->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="timezone">{{ _i('Timezone') }}</label>
                                    <select name="timezone" id="timezone" class="form-control">
                                        <option value="">{{ _i('Select...') }}</option>
                                        @foreach ($timezones as $timezone)
                                            <option value="{{ $timezone }}" {{ $timezone == session('timezone') ? 'selected' : '' }}>
                                                {{ $timezone }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="currency">{{ _i('Currency') }}</label>
                                    <select name="currency" id="currency" class="form-control">
                                        <option value="">{{ _i('Select...') }}</option>
                                        @foreach ($whitelabel_currencies as $currency)
                                            <option value="{{ $currency->iso }}" {{ $currency->iso == session('currency') ? 'selected' : '' }}>
                                                {{ $currency->iso == 'VEF' ? $free_currency->currency_name : $currency->iso . " ({$currency->name})" }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="button" class="btn u-btn-3d u-btn-primary" id="store"
                                            data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Creating...') }}">
                                        <i class="hs-admin-save"></i>
                                        {{ _i('Create') }}
                                    </button>
                                    <button type="button" class="btn u-btn-3d u-btn-bluegray" id="clear">
                                        <i class="hs-admin-close"></i>
                                        {{ _i('Clear') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="noty_bar noty_type__warning noty_theme__unify--v1--dark g-mb-25">
                <div class="noty_body">
                    <div class="g-mr-20">
                        <div class="noty_body__icon">
                            <i class="hs-admin-info"></i>
                        </div>
                    </div>
                    <div>
                        <p>
                            {{ _i('The country and time zone must be configured correctly so that the dates shown to the user adjust correctly to their geographical location. These values ​​can be modified later.') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function () {
            let users = new Users();
            users.store();
        });
    </script>
@endsection

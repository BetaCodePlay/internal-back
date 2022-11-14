@extends('back.template')

@section('content')
    <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
        <header
            class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
            <div class="media">
                <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                    {{ $title }}
                </h3>
                <div class="media-body d-flex justify-content-end">
                    <a href="{{ route('betpay.clients.accounts.create') }}" class="btn u-btn-3d u-btn-primary float-right">
                        <i class="hs-admin-layout-list-thumb"></i>
                        {{ _i('Go to list') }}
                    </a>
                </div>
            </div>
        </header>
        <div class="card-block g-pa-15">
            <form id="client-account-form" method="post" action="{{ route('betpay.clients.accounts.store') }}">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="client">{{ _i('Whitelabel') }}</label>
                            <select name="client" id="client" class="form-control">
                                <option value="">{{ _i('Select...') }}</option>
                                @foreach ($whitelabels as $whitelabel)
                                    <option value="{{ $whitelabel->id }}">
                                        {{ $whitelabel->description }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="currency">{{ _i('Currency') }}</label>
                            <select name="currency" id="currency" class="form-control">
                                <option value="">{{ _i('Select...') }}</option>
                                @foreach ($currency_client as $currency)
                                    <option value="{{ $currency->iso }}">
                                        {{ $currency->iso == 'VEF' ? $free_currency->currency_name : $currency->iso . " ({$currency->name})" }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="payment">{{ _i('Payment methods') }}</label>
                            <select name="payments" id="payments" class="form-control">
                                <option value="">{{ _i('Select...') }}</option>
                                @foreach ($payment_methods as $payment)
                                    <option value="{{ $payment->id }}" data-account-required="{{$payment->account_required}}">
                                        {{ $payment->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="payments">{{ _i('Transaction type') }}</label>
                            <div class="form-group">
                                <select name="transaction_type" id="transaction_type" class="form-control">
                                    <option value="">{{ _i('Credit and debit') }}</option>
                                    <option value="{{ \Dotworkers\Configurations\Enums\TransactionTypes::$credit }}">{{ _i('Credit') }}</option>
                                    <option value="{{ \Dotworkers\Configurations\Enums\TransactionTypes::$debit }}">{{ _i('Debit') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="d-none email-account col-md-4">
                        <div class="form-group">
                            <label for="email">{{ _i('Email') }}</label>
                            <input type="email" name="account_email" id="account_email" class="form-control" autocomplete="off">
                        </div>
                    </div>
                    <div class="d-none full-name col-md-4">
                        <div class="form-group">
                            <label for="first_name">{{ _i('First name') }}</label>
                            <input type="text" name="first_name" id="first_name" class="form-control" autocomplete="off">
                        </div>
                    </div>
                    <div class="d-none full-name col-md-4">
                        <div class="form-group">
                            <label for="last_name">{{ _i('Last name') }}</label>
                            <input type="text" name="last_name" id="last_name" class="form-control" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-4 d-none wire-transfers">
                        <div class="form-group">
                            <label for="country">{{ _i('Country') }}</label>
                            <select name="country" class="form-control country"  data-route="{{ route('betpay.banks.data') }}">
                                <option value="">{{ _i('Select...') }}</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->iso }}">
                                        {{ $country->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 d-none wire-transfers">
                        <div class="form-group">
                            <label for="back">{{ _i('Bank ') }}</label>
                            <select name="bank" class="form-control select2 bank">
                                <option value="">{{ _i('Select ...') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 d-none wire-transfers">
                        <div class="form-group">
                            <label for="account_number">{{ _i('Account number') }}</label>
                            <input type="text" name="account_number" class="form-control" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-4 d-none wire-transfers">
                        <div class="form-group">
                            <label for="account_type">{{ _i('Account type') }}</label>
                            <select name="account_type" class="form-control">
                                <option value="">{{ _i('Select...') }}</option>
                                <option value="C">{{ _i('Current') }}</option>
                                <option value="S">{{ _i('Saving') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 d-none wire-transfers">
                        <div class="form-group">
                            <label for="account_type">{{ _i('Social reasons') }}</label>
                            <input type="text" name="social_reason" class="form-control" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-4 d-none wire-transfers">
                        <div class="form-group">
                            <label for="dni">{{ _i('DNI') }}</label>
                            <input name="account_dni" class="form-control" type="text" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-4 d-none wire-transfers">
                        <div class="form-group">
                            <label for="title">{{ _i('Title') }}</label>
                            <input type="text" name="title" class="form-control" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-4 d-none criptocurrency">
                        <div class="form-group">
                            <label for="crypto_wallet">{{ _i('Wallet ') }}</label>
                            <input id="crypto_wallet" name="crypto_wallet" class="form-control" type="text" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-4 d-none criptocurrency">
                        <div class="form-group">
                            <label for="crypto_currencies">{{ _i('Criptocurrency') }}</label>
                            <select name="crypto_currencies" class="form-control">
                                <option value="">{{ _i('Select...') }}</option>
                                <option value="BTC">{{ _i('BTC') }}</option>
                                <option value="USDT">{{ _i('USDT') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 d-none alps">
                        <div class="form-group">
                            <label for="public_key">{{ _i('Public key ') }}</label>
                            <input id="public_key" name="public_key" class="form-control" type="text" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-4 d-none alps">
                        <div class="form-group">
                            <label for="secret_key">{{ _i('Secret key') }}</label>
                            <input id="secret_key" name="secret_key" class="form-control" type="text" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-4 d-none alps">
                        <div class="form-group">
                            <label for="username">{{ _i('Username') }}</label>
                            <input id="username" name="username" class="form-control" type="text" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-4 d-none alps">
                        <div class="form-group">
                            <label for="password">{{ _i('Password') }}</label>
                            <input id="password" name="password" class="form-control" type="password" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-4 d-none vcreditos_api">
                        <div class="form-group">
                            <label for="vcreditos_user">{{ _i('Vcreditos user ') }}</label>
                            <input id="vcreditos_user" name="vcreditos_user" class="form-control" type="text" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-4 d-none vcreditos_api">
                        <div class="form-group">
                            <label for="vcreditos_secure_id">{{ _i('Vcreditos secure') }}</label>
                            <input id="vcreditos_secure_id" name="vcreditos_secure_id" class="form-control" type="text" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="button" class="btn u-btn-3d u-btn-primary" id="save"
                                    data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Saving...') }}">
                                <i class="hs-admin-save"></i>
                                {{ _i('Save') }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function () {
            let betpay = new BetPay();
            betpay.accountRequired();
            betpay.storeAccountClient();
        });
    </script>
@endsection

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
                    <a href="{{ route('betpay.clients.accounts') }}" class="btn u-btn-3d u-btn-primary float-right">
                        <i class="hs-admin-layout-list-thumb"></i>
                        {{ _i('Go to list') }}
                    </a>
                </div>
            </div>
        </header>
        <div class="card-block g-pa-15">
            <form id="client-account-form" method="post" action="{{route('betpay.clients.accounts.update-client-account')}}" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="currency">{{ _i('Currency') }}</label>
                            <select name="currency" id="currency" class="form-control">
                                <option value="">{{ _i('Select...') }}</option>
                                @foreach ($whitelabel_currencies as $currency)
                                    <option value="{{ $currency->iso }}" {{ $currency->iso == $client->currency_iso ? 'selected' : '' }}>
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
                                    <option value="{{ $payment->id }}" {{ $payment->id == $client->payment_method_id ? 'selected' : '' }} data-account-required="{{$payment->account_required}}">
                                        {{ $payment->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="status">{{ _i('Status') }}</label>
                            <select name="status" id="status" class="form-control">
                                <option value="true" {{ 'true' == $client->status ?? 'selected'}}>{{ _i('Active') }}</option>
                                <option value="false" {{ 'false' == $client->status ?? 'selected'}}>{{ _i('Inactive') }}</option>
                            </select>
                        </div>
                    </div>
                    @if($client->payment_method_id == \Dotworkers\Configurations\Enums\PaymentMethods::$zelle
                        || $client->payment_method_id == \Dotworkers\Configurations\Enums\PaymentMethods::$skrill || $client->payment_method_id == \Dotworkers\Configurations\Enums\PaymentMethods::$neteller
                        || $client->payment_method_id == \Dotworkers\Configurations\Enums\PaymentMethods::$airtm
                        || $client->payment_method_id == \Dotworkers\Configurations\Enums\PaymentMethods::$uphold)
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="email">{{ _i('Email') }}</label>
                            <input type="email" name="account_email" id="account_email" class="form-control" autocomplete="off" value="{{$client->data->email}}">
                        </div>
                    </div>
                        @if($client->payment_method_id == \Dotworkers\Configurations\Enums\PaymentMethods::$zelle)
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="first_name">{{ _i('First name') }}</label>
                                    <input type="text" name="first_name" id="first_name" class="form-control" autocomplete="off" value="{{$client->data->first_name}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="last_name">{{ _i('Last name') }}</label>
                                    <input type="text" name="last_name" id="last_name" class="form-control" autocomplete="off" value="{{$client->data->last_name}}">
                                </div>
                            </div>
                        @endif
                    @endif
                    @if($client->payment_method_id == \Dotworkers\Configurations\Enums\PaymentMethods::$cryptocurrencies)
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="crypto_wallet">{{ _i('Wallet ') }}</label>
                                <input id="crypto_wallet" name="crypto_wallet" class="form-control" type="text" autocomplete="off" value="{{$client->data->wallet}}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="crypto_currencies">{{ _i('Criptocurrency') }}</label>
                                <select name="crypto_currencies" class="form-control">
                                    <option value="">{{ _i('Select...') }}</option>
                                    <option value="BTC" {{ 'BTC' == $client->data->cryptocurrency ? 'selected' : '' }}>{{ _i('BTC') }}</option>
                                    <option value="USDT" {{ 'USDT' == $client->data->cryptocurrency ? 'selected' : '' }}>{{ _i('USDT') }}</option>
                                    <option value="USDC" {{ 'USDC' == $client->data->cryptocurrency ? 'selected' : '' }}>{{ _i('USDC') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="network_cripto">{{ _i('Network') }}</label>
                                    <input type="text" name="network_cripto" id="network_cripto" class="form-control" autocomplete="off" value="{{$client->data->network_cripto}}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="qr_cripto">{{ _i('QR') }}</label>
                                    <input type="file" name="qr_cripto" id="qr_cripto" class="form-control" autocomplete="off">
                            </div>
                        </div>
                        <input type="hidden" name="file" value="{{$client->data->qr}}">
                    @endif
                    @if($client->payment_method_id == \Dotworkers\Configurations\Enums\PaymentMethods::$binance)
                    <div class="col-md-4 ">
                        <div class="form-group">
                            <label for="cryptocurrency_binance">{{ _i('Cryptocurrency') }}</label>
                            <select name="cryptocurrency_binance" class="form-control cryptocurrency">
                                <option value="">{{ _i('Select...') }}</option>
                                <option value="USDT" {{ 'USDT' == $client->data->cryptocurrency ? 'selected' : '' }}>{{ _i('USDT') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 ">
                        <div class="form-group">
                            <label for="email_binance">{{ _i('Email') }}</label>
                                <input type="email" name="email_binance" class="form-control" autocomplete="off" value="{{$client->data->email}}">
                        </div>
                    </div>
                    <div class="col-md-4 ">
                        <div class="form-group">
                            <label for="phone_binance">{{ _i('Phone') }}</label>
                                <input type="number" name="phone_binance" id="phone_binance" class="form-control" autocomplete="off" value="{{$client->data->phone}}">
                        </div>
                    </div>
                    <div class="col-md-4 ">
                        <div class="form-group">
                            <label for="pay_id_binance">{{ _i('Pay Id') }}</label>
                                <input type="number" name="pay_id_binance" id="pay_id_binance" class="form-control" autocomplete="off" value="{{$client->data->pay_id}}">
                        </div>
                    </div>
                    <div class="col-md-4 ">
                        <div class="form-group">
                            <label for="binance_id">{{ _i('Binance Id') }}</label>
                                <input type="number" name="binance_id" id="binance_id" class="form-control" autocomplete="off" value="{{$client->data->binance_id}}">
                        </div>
                    </div>
                    <div class="col-md-4 ">
                        <div class="form-group">
                            <label for="qr_binance">{{ _i('QR') }}</label>
                                <input type="file" name="qr_binance" id="qr_binance" class="form-control" autocomplete="off">
                        </div>
                    </div>
                    <input type="hidden" name="file" value="{{$client->data->qr}}">
                    @endif
                    @if($client->payment_method_id == \Dotworkers\Configurations\Enums\PaymentMethods::$mercado_pago)
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="access_token_mercado_pago">{{ _i('Access Token') }}</label>
                                <input type="text" name="access_token_mercado_pago" class="form-control" autocomplete="off" value="{{$client->data->access_token}}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="public_key_mercado_pago">{{ _i('Public Key') }}</label>
                                <input type="text" name="public_key_mercado_pago" class="form-control" autocomplete="off" value="{{$client->data->public_key}}">
                        </div>
                    </div>
                    @endif
                    @if($client->payment_method_id == \Dotworkers\Configurations\Enums\PaymentMethods::$paypal)
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="client_id_paypal">{{ _i('Client ID') }}</label>
                                <input type="text" name="client_id_paypal" class="form-control" autocomplete="off" value="{{$client->data->client_id}}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="client_secret_paypal">{{ _i('Secret Key') }}</label>
                                <input type="text" name="client_secret_paypal" class="form-control" autocomplete="off" value="{{$client->data->client_secret}}">
                        </div>
                    </div>
                    @endif
                    @if($client->payment_method_id == \Dotworkers\Configurations\Enums\PaymentMethods::$pix)
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="client_id_pix">{{ _i('Client ID') }}</label>
                                <input type="text" name="client_id_pix" class="form-control" autocomplete="off" value="{{$client->data->client_id}}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="client_secret_pix">{{ _i('Secret Key') }}</label>
                                <input type="text" name="client_secret_pix" class="form-control" autocomplete="off" value="{{$client->data->client_secret}}">
                        </div>
                    </div>
                    @endif
                    <div class="col-md-12">
                        <input type="hidden" name="client_account" id="client_account" value="{{$client->id}}">
                        <input type="hidden" name="payments" id="payments" value="{{$client->payment_method_id}}">
                        <div class="form-group">
                            <button type="submit" class="btn u-btn-3d u-btn-primary" id="update"
                                    data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Updating...') }}">
                                <i class="hs-admin-reload"></i>
                                {{ _i('Update') }}
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
            betpay.updateClientAccount("{!! $client->data->qr !!}");
        });
    </script>
@endsection

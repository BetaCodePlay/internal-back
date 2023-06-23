@extends('back.template')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                <header
                    class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                    <div class="media">
                        <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                            {{ _i('Filter client account') }}
                        </h3>
                        <div class="media-body d-flex justify-content-end">
                            <a href="{{ route('betpay.clients.accounts.create') }}"
                               class="btn u-btn-3d u-btn-primary float-right">
                                <i class="hs-admin-upload"></i>
                                {{ _i('Create') }}
                            </a>
                        </div>
                    </div>
                </header>
                <div class="card-block g-pa-15">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="currency">{{ _i('Currency') }}</label>
                                <select name="currency" id="currency" class="form-control">
                                    <option value="">{{ _i('All currencies') }}</option>
                                    @foreach ($whitelabel_currencies as $currency)
                                    <option value="{{ $currency->iso }}">
                                        {{ $currency->iso == 'VEF' ? $free_currency->currency_name : $currency->iso . " ({$currency->name})" }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="payments">{{ _i('Payment methods') }}</label>
                                <select name="payments" id="payments" class="form-control">
                                    <option value="">{{ _i('All payment methods') }}</option>
                                    @foreach ($payment_methods as $payment)
                                        <option value="{{ $payment->id }}">
                                            {{ $payment->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <button type="button" class="btn u-btn-3d u-btn-primary" id="search"
                                        data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Consulting...') }}">
                                    <i class="hs-admin-search"></i>
                                    {{ _i('Search') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                <header
                    class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                    <div class="media">
                        <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                            {{ $title }}
                        </h3>
                    </div>
                </header>
                <div class="card-block g-pa-15">
                    <div class="media">
                        <div class="media-body d-flex justify-content-end g-mb-10" id="table-buttons">

                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered w-100" id="client-account-list-table"
                               data-route="{{ route('betpay.clients.accounts.data') }}">
                            <thead>
                            <tr>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Name') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Currency') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Payment method') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Status') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Details') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Action') }}
                                </th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('back.betpay.clients.modals.watch-binance-qr')
    @include('back.betpay.clients.modals.watch-crypto-qr')
@endsection

@section('scripts')
    <script>
        $(function () {
            let betpay = new BetPay();
            betpay.clientAccount();
            betpay.binanceQrModal();
            betpay.cryptoQrModal();
            $(document).on('click', '.status_checkbox', function () {
                console.log('hola');
                if (!$(this).hasClass('active')) {
                    $.post('{{route('betpay.clients.accounts.status')}}', {
                        client_id: $(this).data('id'),
                        name: 'status',
                        value: true
                    }, function () {
                    });
                } else {
                    $.post('{{route('betpay.clients.accounts.status')}}', {
                        client_id: $(this).data('id'),
                        name: 'status',
                        value: false
                    }, function () {
                    });
                }
                $(this).toggleClass('active');
            });
        });
    </script>
@endsection

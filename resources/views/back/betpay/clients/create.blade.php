@extends('back.template')

@section('content')
        <div class="row">
            <div class="col-md-12">
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                    <header
                        class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                {{ $title }}
                            </h3>
                            <div class="media-body d-flex justify-content-end">
                                <a href="{{ route('betpay.clients.index') }}" class="btn u-btn-3d u-btn-primary float-right">
                                    <i class="hs-admin-layout-list-thumb"></i>
                                    {{ _i('Go to list') }}
                                </a>
                            </div>
                        </div>
                    </header>
                    <div class="card-block g-pa-15">
                        <form action="{{ route('betpay.clients.store') }}" id="clients-form" method="post">
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
                                        <label for="payments">{{ _i('Payment methods') }}</label>
                                        <select name="payments[]" class="form-control" id="payments" multiple>
                                            <option value="">{{ _i('Select...') }}</option>
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
            </div>
        </div>
@endsection

@section('scripts')
    <script>
        $(function () {
            let betpay = new BetPay();
            betpay.storeClient();
        });
    </script>
@endsection

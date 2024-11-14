@extends('back.template')

@section('content')
    <form action="{{ route('financial-report.update') }}" id="update-form" method="post" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-12">
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
                        <form action="{{ route('financial-report.store') }}" id="store-form" method="post">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="change_provider">{{ _i('Provider') }}</label>
                                        <select name="change_provider" id="change_provider"
                                                data-route="{{ route('financial-report.maker') }}" class="form-control">
                                            <option value="">{{ _i('Select...') }}</option>
                                            @foreach ($providers as $provider)
                                                <option value="{{ $provider->provider_id }}">
                                                    {{ $provider->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="maker">{{ _i('Maker') }}</label>
                                        <select name="maker" id="maker" class="form-control"
                                                data-loading-text="<i class='fa fa-spin fa-spinner'></i>  {{ _i('Loading...') }}">
                                            <option value="">{{ _i('Select...') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="currency">{{ _i('Currency') }}</label>
                                        <select name="currency" id="currency" class="form-control">
                                            <option value="">{{ _i('Select...') }}</option>
                                            @foreach ($whitelabel_currencies as $currency)
                                                <option
                                                    value="{{ $currency->iso }}" {{ $currency->iso == session('currency') ? 'selected' : '' }}>
                                                    {{ $currency->iso == 'VEF' ? $free_currency->currency_name : $currency->iso . " ({$currency->name})" }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="amount">{{ _i('Amount') }}</label>
                                        <input type="number" name="amount" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="load_amount">{{ _i('Load Amount') }}</label>
                                        <input type="number" name="load_amount" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="load_date">{{ _i('Load date') }}</label>
                                        <input type="text" name="load_date" id="load_date" class="form-control datetimepicker input_placeholder" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="limit">{{ _i('Limit') }}</label>
                                        <input type="number" name="limit" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="hidden" name="user" value="{{ $user }}">
                                        <input type="hidden" name="id" value="{{ $id }}">
                                        <button type="button" class="btn u-btn-3d u-btn-primary" id="update"
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
    </form>
@endsection

@section('scripts')
    <script>
        $(function () {
            let financialReport = new FinancialReport()
            financialReport.maker();
            financialReport.store();
            financialReport.update();
        });
    </script>
@endsection

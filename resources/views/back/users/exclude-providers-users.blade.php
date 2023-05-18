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
                    </div>
                </header>
                <div class="card-block g-pa-15">
                    <form action="{{ route('users.exclude-providers-users-data') }}" method="post" id="exclude-provider-user-form">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="agent">{{ _i('Provider') }}</label>
                                    <select name="provider" id="provider" class="form-control provider" data-route="{{ route('core.maker') }}">>
                                        <option value="">{{ _i('Select...') }}</option>
                                        @foreach ($providers as $provider)
                                            <option value="{{ $provider['id'] }}">
                                                {{ $provider['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="maker">{{ _i('Maker') }}</label>
                                    <select name="maker" id="maker" class="form-control maker">
                                        <option value="">{{ _i('Select...') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="username">{{ _i('User') }}</label>
                                    <input type="text" name="username" id="username" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="currency">{{ _i('Currency') }}</label>
                                    <select name="currency" id="currency" class="form-control">
                                        <option value="">{{ _i('Select...') }}</option>
                                        @foreach ($currency_client as $currency)
                                            <option value="{{ $currency }}">
                                                {{ $currency == 'VEF' ? $free_currency->currency_name : $currency }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="button" class="btn u-btn-3d u-btn-primary" id="save"
                                            data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Saving...') }}">
                                        <i class="hs-admin-reload"></i>
                                        {{ _i('Exclude user') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                <header class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                    <div class="media">
                        <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0 title">
                            {{ _i('Excluded users') }}
                        </h3>
                        <div class="media-body d-flex justify-content-end" id="table-buttons">

                        </div>
                    </div>
                </header>
                <div class="card-block g-pa-15">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="date_range">{{ _i('Date range') }}</label>
                                <input type="text" id="daterange" class="form-control daterange" autocomplete="off" placeholder="{{ _i('Date range') }}">
                                <input type="hidden" id="start_date" name="start_date">
                                <input type="hidden" id="end_date" name="end_date">
                            </div>
                        </div>
                       <div class="col-md-3">
                            <div class="form-group">
                                <label for="agent">{{ _i('Provider') }}</label>
                                <select name="provider_filter" id="provider_filter" class="form-control provider" data-route="{{ route('core.maker') }}">>
                                    <option value="">{{ _i('Select...') }}</option>
                                    @foreach ($providers as $provider)
                                        <option value="{{ $provider['id'] }}">
                                            {{ $provider['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="maker_filter">{{ _i('Maker') }}</label>
                                <select name="maker_filter" id="maker_filter" class="form-control maker">
                                    <option value="">{{ _i('Select...') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="currency_filter">{{ _i('Currency') }}</label>
                                <select name="currency_filter" id="currency_filter" class="form-control">
                                    <option value="">{{ _i('Select...') }}</option>
                                    @foreach ($currency_client as $currency)
                                        <option value="{{ $currency }}">
                                            {{ $currency == 'VEF' ? $free_currency->currency_name : $currency }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <button type="button" class="btn u-btn-3d u-btn-primary" id="update"
                                        data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Consulting...') }}">
                                    <i class="hs-admin-search"></i>
                                    {{ _i('Consult data') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-block g-pa-15">
                    <table class="table table-bordered table-responsive-sm w-100" id="exclude-providers-users-table" data-route="{{ route('users.exclude-providers-users.list') }}">
                        <thead>
                        <tr>
                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                {{ _i('User') }}
                            </th>
                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                {{ _i('Username') }}
                            </th>
                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                {{ _i('Provider') }}
                            </th>
                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                {{ _i('Makers') }}
                            </th>
                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                {{ _i('Currency') }}
                            </th>
                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                {{ _i('Date') }}
                            </th>
                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                {{ _i('Actions') }}
                            </th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function () {
            let users = new Users();
            users.excludeProviderUserList();
            users.selectProvidersMaker();
        });
    </script>
@endsection

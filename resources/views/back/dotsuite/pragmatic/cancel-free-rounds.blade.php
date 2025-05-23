@extends('back.template')

@section('content')
        <div class="row">
            <div class="col-md-12">
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                    <header
                        class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                {{ _i('Search by filter')}}
                            </h3>
                        </div>
                    </header>
                    <div class="card-block g-pa-15">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="currency">{{ _i('Currency') }}</label>
                                    <select name="currency"  id="currency" class="form-control" data-route="{{ route('dot-suite.currency-users') }}" data-provider="{{ \Dotworkers\Configurations\Enums\Providers::$pragmatic_play}}">
                                        <option value="">{{ _i('Select...') }}</option>
                                        @foreach ($whitelabel_currencies as $currency)
                                            <option value="{{ $currency->iso }}">
                                                {{ $currency->iso == 'VEF' ? $free_currency->currency_name : $currency->iso . " ({$currency->name})" }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="submit" class="btn u-btn-3d u-btn-primary" id="search"
                                            data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Searching...') }}">
                                        <i class="hs-admin-search"></i>
                                        {{ _i('Search')}}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                    <header
                        class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                {{ $title }}
                            </h3>
                            <div class="media-body d-flex justify-content-end">
                                <a href="{{ route('dot-suite.free-spins') }}" class="btn u-btn-3d u-btn-primary float-right">
                                    <i class="hs-admin-plus"></i>
                                    {{ _i('New') }}
                                </a>
                            </div>
                        </div>
                    </header>
                    <div class="card-block g-pa-15">
                        <div class="media">
                            <div class="media-body d-flex justify-content-end g-mb-10" id="table-buttons">

                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered w-100" id="free-spins-list-table" data-route="{{ route('dot-suite.cancel-free-list') }}">
                                <thead>
                                <tr>
                                    <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                        {{ _i('Code bonus') }}
                                    </th>
                                    <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                        {{ _i('Currency') }}
                                    </th>
                                    <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                        {{ _i('Quantity of turns') }}
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
        </div>
@endsection
@section('scripts')
    <script>
        $(function () {
            let dotSuite = new DotSuite();
            dotSuite.cancelFreeRoundsPragmatic();
            dotSuite.freeSpinsPragmaticList();
        });
    </script>
@endsection


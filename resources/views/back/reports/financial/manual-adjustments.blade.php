@extends('back.template')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                <header
                    class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                    <div class="media">
                        <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                            {{_i('Filters')}}
                        </h3>
                    </div>
                </header>
                <div class="card-block g-pa-15">
                    <div class="row">
                        @can('access', [\Dotworkers\Security\Enums\Permissions::$whitelabels_totals])
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="currency">{{ _i('whitelabel') }}</label>
                                    <select name="whitelabel" id="whitelabel" class="form-control"
                                            data-route="{{ route('whitelabels.currency-by-whitelabel') }}">
                                        <option value="">{{ _i('All whitelabels') }}</option>
                                        @foreach ($whitelabels as $whitelabel)
                                            <option value="{{ $whitelabel->id }}">
                                                {{ $whitelabel->description }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endcan
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="currency">{{ _i('Currency') }}</label>
                                <select name="currency" id="currency" class="form-control select2">
                                    <option value="">{{ _i('All currencies') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="currency">{{ _i('Transaction type') }}</label>
                                <select name="transaction_type" id="transaction_type" class="form-control">
                                    <option value="">{{ _i('All types') }}</option>
                                    <option
                                        value="{{ \Dotworkers\Configurations\Enums\TransactionTypes::$credit }}">{{ _i('Credit') }}</option>
                                    <option
                                        value="{{ \Dotworkers\Configurations\Enums\TransactionTypes::$debit }}">{{ _i('Debit') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="date_ranger">{{ _i('Date range') }}</label>
                                <input type="text" id="daterange" class="form-control daterange g-pr-80 g-pl-15 g-py-9"
                                       autocomplete="off">
                                <input type="hidden" id="start_date" name="start_date">
                                <input type="hidden" id="end_date" name="end_date">
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
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                <header class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                    <div class="media">
                        <h3 class="d-flex align-self-center text-uppercase g-font-size-12 g-font-size-default--md g-color-black mb-0">
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
                        <table class="table table-bordered w-100" id="manual-adjustments-table"
                               data-route="{{ route('reports.financial.manual-adjustments-data') }}">
                            <thead>
                            <tr>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('ID') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Username') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Operator') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Whitelabel') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Transaction type') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Amount') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Currency') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Description') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Method') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Date') }}
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
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
            let reports = new Reports();
            let whitelabels = new Whitelabels();
            reports.manualAdjustments();
            whitelabels.currencies();
        });
    </script>
@endsection

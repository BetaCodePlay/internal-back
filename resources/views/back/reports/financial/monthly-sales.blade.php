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
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="month">{{ _i('Month') }}</label>
                                <select name="month" id="month" class="form-control" multiple>
                                    <option value="">{{ _i('Select...') }}</option>
                                    <option value="01"> {{ _i('January')}} </option>
                                    <option value="02"> {{ _i('February')}} </option>
                                    <option value="03"> {{ _i('March')}} </option>
                                    <option value="04"> {{ _i('April')}} </option>
                                    <option value="05"> {{ _i('May')}} </option>
                                    <option value="06"> {{ _i('June')}} </option>
                                    <option value="07"> {{ _i('July')}} </option>
                                    <option value="08"> {{ _i('August')}} </option>
                                    <option value="09"> {{ _i('September')}} </option>
                                    <option value="10"> {{ _i('October')}} </option>
                                    <option value="11"> {{ _i('November')}} </option>
                                    <option value="12"> {{ _i('December')}} </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="years">{{ _i('Year') }}</label>
                                <select name="years" id="years" class="form-control">
                                    <option value="">{{ _i('Select...') }}</option>
                                    @for ($i = $end_year; $i >= $start_year; $i--)
                                        <option value="{{ $i }}">
                                            {{ $i }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="currency">{{ _i('Currency') }}</label>
                                <select name="currency" id="currency" class="form-control">
                                    <option value="">{{ _i('All currencies') }}</option>
                                    @foreach ($whitelabel_currencies as $currency)
                                        <option value="{{ $currency->iso }}" {{ $currency->iso == session('currency') ? 'selected' : '' }}>
                                            {{ $currency->iso == 'VEF' ? $free_currency->currency_name : $currency->iso . " ({$currency->name})" }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="convert">{{ _i('Convert to') }}</label>
                                <select name="convert" id="convert" class="form-control">
                                    <option value="">{{ _i('No conversion') }}</option>
                                    @foreach ($all_currencies as $currency)
                                        <option value="{{ $currency->iso }}">
                                            {{ $currency->iso == 'VEF' ? $free_currency->currency_name : $currency->iso }}
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
                        <table class="table table-bordered w-100" id="monthly-sales-table"
                               data-route="{{route('reports.financial.monthly-sales-data')}}">
                            <thead>
                            <tr>
                                <th rowspan="2" class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Month')}}
                                </th>
                                <th rowspan="2" class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('New registers')}}
                                </th>
                                <th rowspan="2" class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Unique depositors')}}
                                </th>
                                <th rowspan="2" class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Active users')}}
                                </th>
                                <th rowspan="2" class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('FTD')}}
                                </th>
                                <th rowspan="2" class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Bonuses')}}
                                </th>
                                <th colspan="2" class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none text-center">
                                    {{ _i('Deposits')}}
                                </th>
                                <th colspan="2" class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none text-center">
                                    {{ _i('Withdrawals')}}
                                </th>
                                <th colspan="2" class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none text-center">
                                    {{ _i('Manual transactions')}}
                                </th>
                                <th rowspan="2" class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Played')}}
                                </th>
                                <th rowspan="2" class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Profit')}}
                                </th>
                            </tr>
                            <tr>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Approved')}}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Rejected')}}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Approved')}}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Rejected')}}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Credit')}}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Debit')}}
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th class="text-right">{{ _i('Percentage')}}</th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                            <tr>
                                <th class="text-right">{{ _i('Totals')}}</th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                            </tfoot>
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
            reports.monthlySales();
        });
    </script>
@endsection

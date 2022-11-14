@extends('back.template')

@section('content')
    <div class="row">
        <div class="col-sm-6 col-lg-6 col-xl g-mb-30">
            <div class="card h-100 g-brd-gray-light-v7 g-rounded-3">
                <div class="card-block g-font-weight-300 g-pa-20">
                    <div class="media">
                        <div class="d-flex g-mr-15">
                            <div
                                    class="u-header-dropdown-icon-v1 g-pos-rel g-width-60 g-height-60 g-bg-teal g-font-size-18 g-font-size-24--md g-color-white rounded-circle">
                                <i class="hs-admin-money g-absolute-centered"></i>
                            </div>
                        </div>
                        <div class="media-body align-self-center">
                            <div class="d-flex align-items-center g-mb-5">
                                <span class="g-font-size-24 g-line-height-1 g-color-black" id="total_balances">0.00</span>
                            </div>
                            <h6 class="g-font-size-16 g-font-weight-300 g-color-gray-dark-v6 mb-0">
                                {{ _i('Total balances') }}
                            </h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
{{--        <div class="col-sm-6 col-lg-6 col-xl g-mb-30">--}}
{{--            <div class="card h-100 g-brd-gray-light-v7 g-rounded-3">--}}
{{--                <div class="card-block g-font-weight-300 g-pa-20">--}}
{{--                    <div class="media">--}}
{{--                        <div class="d-flex g-mr-15">--}}
{{--                            <div--}}
{{--                                class="u-header-dropdown-icon-v1 g-pos-rel g-width-60 g-height-60 g-bg-primary g-font-size-18 g-font-size-24--md g-color-white rounded-circle">--}}
{{--                                <i class="hs-admin-gift g-absolute-centered"></i>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="media-body align-self-center">--}}
{{--                            <div class="d-flex align-items-center g-mb-5">--}}
{{--                                <span class="g-font-size-24 g-line-height-1 g-color-black" id="total_bonus_balances">0.00</span>--}}
{{--                            </div>--}}
{{--                            <h6 class="g-font-size-16 g-font-weight-300 g-color-gray-dark-v6 mb-0">--}}
{{--                                {{ _i('Total bonus balance') }}--}}
{{--                            </h6>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
        <div class="col-sm-6 col-lg-6 col-xl g-mb-30">
            <div class="card h-100 g-brd-gray-light-v7 g-rounded-3">
                <div class="card-block g-font-weight-300 g-pa-20">
                    <div class="media">
                        <div class="d-flex g-mr-15">
                            <div
                                class="u-header-dropdown-icon-v1 g-pos-rel g-width-60 g-height-60 g-bg-primary g-font-size-18 g-font-size-24--md g-color-white rounded-circle">
                                <i class="hs-admin-lock g-absolute-centered"></i>
                            </div>
                        </div>
                        <div class="media-body align-self-center">
                            <div class="d-flex align-items-center g-mb-5">
                                <span class="g-font-size-24 g-line-height-1 g-color-black" id="total_locked_balances">0.00</span>
                            </div>
                            <h6 class="g-font-size-16 g-font-weight-300 g-color-gray-dark-v6 mb-0">
                                {{ _i('Total balances locked') }}
                            </h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="currency">{{ _i('Currency') }}</label>
                                <select name="currency" id="currency" class="form-control">
                                    @foreach ($whitelabel_currencies as $currency)
                                        <option value="{{ $currency->iso }}" {{ $currency->iso == session('currency') ? 'selected' : '' }}>
                                            {{ $currency->iso == 'VEF' ? $free_currency->currency_name : $currency->iso . " ({$currency->name})" }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        {{--<div class="col-md-3">--}}
                        {{--    <div class="form-group">--}}
                        {{--        <label for="level">{{ _i('Level') }}</label>--}}
                        {{--       <select name="level" id="level" class="form-control">--}}
                        {{--           <option value="1"> {{ _i('Level 1')}} </option>--}}
                        {{--           <option value="2"> {{ _i('Level 2')}} </option>--}}
                        {{--           <option value="3"> {{ _i('Level 3')}} </option>--}}
                        {{--           <option value="4"> {{ _i('Level 4')}} </option>--}}
                        {{--           <option value="5"> {{ _i('Level 5')}} </option>--}}
                        {{--       </select>--}}
                        {{--   </div>--}}
                        {{--</div>--}}
                    </div>
                    <div class="row">
                        <div class="col">
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
                <div class="card-block g-pa-15">
                    <div class="media">
                        <div class="media-body d-flex justify-content-end g-mb-10" id="table-buttons">

                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered w-100" id="users-balances-table"
                               data-route="{{ route('reports.users.balances-data') }}">
                            <thead>
                            <tr>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('ID') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Username') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none text-right">
                                    {{ _i('Balance') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none text-right">
                                    {{ _i('Bonus balance') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none text-right">
                                    {{ _i('Locked') }}
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
            reports.usersBalances();
        });
    </script>
@endsection

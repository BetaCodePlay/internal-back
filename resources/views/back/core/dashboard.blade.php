@extends('back.template')

@section('content')
    @can('access', [\Dotworkers\Security\Enums\Permissions::$dashboard_widgets])
        <div class="row">
            <div class="col-sm-6 col-lg-6 col-xl g-mb-30">
                <div class="card h-100 g-brd-gray-light-v7 g-rounded-3">
                    <div class="card-block g-font-weight-300 g-pa-20">
                        <div class="media">
                            <div class="d-flex g-mr-15">
                                <a href="{{ route('reports.users.registered-users') }}">
                                    <div
                                        class="u-header-dropdown-icon-v1 g-pos-rel g-width-60 g-height-60 g-bg-indigo g-font-size-18 g-font-size-24--md g-color-white rounded-circle">
                                        <i class="hs-admin-plus g-absolute-centered"></i>
                                    </div>
                                </a>
                            </div>
                            <div class="media-body align-self-center">
                                <div class="d-flex align-items-center g-mb-5">
                                <span class="g-font-size-24 g-line-height-1 g-color-black" id="new-users" data-route="{{ route('users.new') }}">
                                    0
                                </span>
                                </div>
                                <h6 class="g-font-size-16 g-font-weight-300 g-color-gray-dark-v6 mb-0">
                                    {{ _i('New users in the day')}}
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-6 col-xl g-mb-30">
                <div class="card h-100 g-brd-gray-light-v7 g-rounded-3">
                    <div class="card-block g-font-weight-300 g-pa-20">
                        <div class="media">
                            <div class="d-flex g-mr-15">
                                <a href="{{ route('reports.users.registered-users') }}">
                                    <div
                                        class="u-header-dropdown-icon-v1 g-pos-rel g-width-60 g-height-60 g-bg-blue g-font-size-18 g-font-size-24--md g-color-white rounded-circle">
                                        <i class="hs-admin-user g-absolute-centered"></i>
                                    </div>
                                </a>
                            </div>
                            <div class="media-body align-self-center">
                                <div class="d-flex align-items-center g-mb-5">
                                <span class="g-font-size-24 g-line-height-1 g-color-black" id="total-users" data-route="{{ route('users.total') }}">
                                    0
                                </span>
                                </div>
                                <h6 class="g-font-size-16 g-font-weight-300 g-color-gray-dark-v6 mb-0">
                                    {{ _i('Registered users')}}
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-6 col-xl g-mb-30">
                <div class="card h-100 g-brd-gray-light-v7 g-rounded-3">
                    <div class="card-block g-font-weight-300 g-pa-20">
                        <div class="media">
                            <div class="d-flex g-mr-15">
                                <a href="{{ route('reports.users.users-conversion') }}">
                                    <div
                                        class="u-header-dropdown-icon-v1 g-pos-rel g-width-60 g-height-60 g-bg-teal g-font-size-18 g-font-size-24--md g-color-white rounded-circle">
                                        <i class="hs-admin-check-box g-absolute-centered"></i>
                                    </div>
                                </a>
                            </div>
                            <div class="media-body align-self-center">
                                <div class="d-flex align-items-center g-mb-5">
                                    <span class="g-font-size-24 g-line-height-1 g-color-black" id="completed-profiles" data-route="{{ route('users.completed-profiles') }}">
                                        0
                                    </span>
                                </div>
                                <h6 class="g-font-size-16 g-font-weight-300 g-color-gray-dark-v6 mb-0">
                                    {{ _i('Completed profiles')}}
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-6 col-xl g-mb-30">
                <div class="card h-100 g-brd-gray-light-v7 g-rounded-3">
                    <div class="card-block g-font-weight-300 g-pa-20">
                        <div class="media">
                            <div class="d-flex g-mr-15">
                                <a href="{{ route('reports.users.users-conversion') }}">
                                    <div
                                        class="u-header-dropdown-icon-v1 g-pos-rel g-width-60 g-height-60 g-bg-primary g-font-size-18 g-font-size-24--md g-color-white rounded-circle">
                                        <i class="hs-admin-close g-absolute-centered"></i>
                                    </div>
                                </a>
                            </div>
                            <div class="media-body align-self-center">
                                <span class="g-font-size-24 g-line-height-1 g-color-black" id="incomplete-profiles" data-route="{{ route('users.incomplete-profiles') }}">
                                    0
                                </span>
                                <h6 class="g-font-size-16 g-font-weight-300 g-color-gray-dark-v6 mb-0">
                                    {{ _i('Incomplete profiles')}}
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-6 col-xl g-mb-30">
                <div class="card h-100 g-brd-gray-light-v7 g-rounded-3">
                    <div class="card-block g-font-weight-300 g-pa-20">
                        <div class="media">
                            <div class="d-flex g-mr-15">
                                <a href="{{ route('reports.financial.deposits') }}">
                                    <div
                                        class="u-header-dropdown-icon-v1 g-pos-rel g-width-60 g-height-60 g-bg-teal g-font-size-18 g-font-size-24--md g-color-white rounded-circle">
                                        <i class="hs-admin-stats-up g-absolute-centered"></i>
                                    </div>
                                </a>
                            </div>
                            <div class="media-body align-self-center">
                                <div class="d-flex align-items-center g-mb-5">
                                    <span class="g-font-size-24 g-line-height-1 g-color-black" id="today-deposits" data-route="{{ route('transactions.totals-by-type', [\Dotworkers\Store\Enums\TransactionTypes::$credit, $start_date, $end_date]) }}">
                                        0.00
                                    </span>
                                </div>
                                <h6 class="g-font-size-16 g-font-weight-300 g-color-gray-dark-v6 mb-0">
                                    {{ _i('Amount deposited on the day')}}
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6 col-lg-6 col-xl g-mb-30">
                <div class="card h-100 g-brd-gray-light-v7 g-rounded-3">
                    <div class="card-block g-font-weight-300 g-pa-20">
                        <div class="media">
                            <div class="d-flex g-mr-15">
                                <a href="{{ route('reports.financial.withdrawals') }}">
                                    <div
                                        class="u-header-dropdown-icon-v1 g-pos-rel g-width-60 g-height-60 g-bg-primary g-font-size-18 g-font-size-24--md g-color-white rounded-circle">
                                        <i class="hs-admin-stats-down g-absolute-centered"></i>
                                    </div>
                                </a>
                            </div>
                            <div class="media-body align-self-center">
                                <div class="d-flex align-items-center g-mb-5">
                                    <span class="g-font-size-24 g-line-height-1 g-color-black" id="today-withdrawals" data-route="{{ route('transactions.totals-by-type', [\Dotworkers\Store\Enums\TransactionTypes::$debit, $start_date, $end_date]) }}">
                                        0.00
                                    </span>
                                </div>
                                <h6 class="g-font-size-16 g-font-weight-300 g-color-gray-dark-v6 mb-0">
                                    {{ _i('Amount withdrawn on the day')}}
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-6 col-xl g-mb-30">
                <div class="card h-100 g-brd-gray-light-v7 g-rounded-3">
                    <div class="card-block g-font-weight-300 g-pa-20">
                        <div class="media">
                            <div class="d-flex g-mr-15">
                                <div
                                    class="u-header-dropdown-icon-v1 g-pos-rel g-width-60 g-height-60 g-bg-orange g-font-size-18 g-font-size-24--md g-color-white rounded-circle">
                                    <i class="hs-admin-shift-left g-absolute-centered"></i>
                                </div>
                            </div>
                            <div class="media-body align-self-center">
                                <div class="d-flex align-items-center g-mb-5">
                                    <span class="g-font-size-24 g-line-height-1 g-color-black" id="pending-deposits"
                                          data-route="{{ route('transactions.count-by-type', [\Dotworkers\Store\Enums\TransactionTypes::$credit, \Dotworkers\Configurations\Enums\TransactionStatus::$pending, $start_date, $end_date]) }}">
                                        0
                                    </span>
                                </div>
                                <h6 class="g-font-size-16 g-font-weight-300 g-color-gray-dark-v6 mb-0">
                                    {{ _i('Pending deposits')}}
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-6 col-xl g-mb-30">
                <div class="card h-100 g-brd-gray-light-v7 g-rounded-3">
                    <div class="card-block g-font-weight-300 g-pa-20">
                        <div class="media">
                            <div class="d-flex g-mr-15">
                                <div
                                    class="u-header-dropdown-icon-v1 g-pos-rel g-width-60 g-height-60 g-bg-deeporange g-font-size-18 g-font-size-24--md g-color-white rounded-circle">
                                    <i class="hs-admin-shift-right g-absolute-centered"></i>
                                </div>
                            </div>
                            <div class="media-body align-self-center">
                                <div class="d-flex align-items-center g-mb-5">
                                    <span class="g-font-size-24 g-line-height-1 g-color-black" id="pending-withdrawals"
                                          data-route="{{ route('transactions.count-by-type', [\Dotworkers\Store\Enums\TransactionTypes::$debit, \Dotworkers\Configurations\Enums\TransactionStatus::$pending, $start_date, $end_date]) }}">
                                        0
                                    </span>
                                </div>
                                <h6 class="g-font-size-16 g-font-weight-300 g-color-gray-dark-v6 mb-0">
                                    {{ _i('Pending withdrawals')}}
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-6 col-xl g-mb-30">
                <div class="card h-100 g-brd-gray-light-v7 g-rounded-3">
                    <div class="card-block g-font-weight-300 g-pa-20">
                        <div class="media">
                            <div class="d-flex g-mr-15">
                                <div
                                    class="u-header-dropdown-icon-v1 g-pos-rel g-width-60 g-height-60 g-bg-orange g-font-size-18 g-font-size-24--md g-color-white rounded-circle">
                                    <i class="hs-admin-desktop g-absolute-centered"></i>
                                </div>
                            </div>
                            <div class="media-body align-self-center">
                                <div class="d-flex align-items-center g-mb-5">
                                    <span class="g-font-size-24 g-line-height-1 g-color-black" id="connect-by-desktop"
                                          data-route="{{ route('core.number-users-connected-by-device', [$start_date, $end_date]) }}">
                                        0
                                    </span>
                                </div>
                                <h6 class="g-font-size-16 g-font-weight-300 g-color-gray-dark-v6 mb-0">
                                    {{ _i('Users connected from desktop')}}
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-6 col-xl g-mb-30">
                <div class="card h-100 g-brd-gray-light-v7 g-rounded-3">
                    <div class="card-block g-font-weight-300 g-pa-20">
                        <div class="media">
                            <div class="d-flex g-mr-15">
                                <div
                                    class="u-header-dropdown-icon-v1 g-pos-rel g-width-60 g-height-60 g-bg-deeporange g-font-size-18 g-font-size-24--md g-color-white rounded-circle">
                                    <i class="hs-admin-tablet g-absolute-centered"></i>
                                </div>
                            </div>
                            <div class="media-body align-self-center">
                                <div class="d-flex align-items-center g-mb-5">
                                    <span class="g-font-size-24 g-line-height-1 g-color-black" id="connect-by-mobile"
                                          data-route="{{ route('core.number-users-connected-by-device', [$start_date, $end_date]) }}">
                                        0
                                    </span>
                                </div>
                                <h6 class="g-font-size-16 g-font-weight-300 g-color-gray-dark-v6 mb-0">
                                    {{ _i('Users connected from mobile')}}
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('back.users.modals.reset-email')
    @endcan
    @can('access', [\Dotworkers\Security\Enums\Permissions::$dashboard_report])
        @include('back.reports.products.layout.products')
    @endcan
@endsection

@section('scripts')
    @can('access', [\Dotworkers\Security\Enums\Permissions::$dashboard_report])
        <script>
            $(function () {
                let reports = new Reports();
                reports.productsTotals();
            });
        </script>
    @endcan
    @can('access', [\Dotworkers\Security\Enums\Permissions::$dashboard_widgets])
        <script>
            $(function () {
                new Dashboard();
            });
        </script>
        
    @endcan
@endsection

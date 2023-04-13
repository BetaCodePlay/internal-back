@extends('back.template')
@section('styles')
{{-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap.min.css"> --}}
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-6 col-lg-6 col-xl-6 g-mb-30">
            <div class="card h-100 g-brd-gray-light-v7 g-rounded-3">
                <div class="card-block g-font-weight-300 g-pa-20">
                    <div class="media">
                        <div class="d-flex g-mr-15">
                            <div
                                class="u-header-dropdown-icon-v1 g-pos-rel g-width-60 g-height-60 g-bg-lightred-v2 g-font-size-18 g-font-size-24--md g-color-white rounded-circle">
                                <i class="hs-admin-arrow-down g-absolute-centered"></i>
                            </div>
                        </div>
                        <div class="media-body align-self-center">
                            <div class="d-flex align-items-center g-mb-5">
                                <span class="g-font-size-24 g-line-height-1 g-color-black" id="credit">0.00</span>
                            </div>
                            <h6 class="g-font-size-16 g-font-weight-300 g-color-gray-dark-v6 mb-0">
                                {{ _i('Total recharges') }}
                            </h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-6 col-xl-6 g-mb-30">
            <div class="card h-100 g-brd-gray-light-v7 g-rounded-3">
                <div class="card-block g-font-weight-300 g-pa-20">
                    <div class="media">
                        <div class="d-flex g-mr-15">
                            <div
                                class="u-header-dropdown-icon-v1 g-pos-rel g-width-60 g-height-60 g-bg-darkblue-v2 g-font-size-18 g-font-size-24--md g-color-white rounded-circle">
                                <i class="hs-admin-arrow-up g-absolute-centered"></i>
                            </div>
                        </div>
                        <div class="media-body align-self-center">
                            <div class="d-flex align-items-center g-mb-5">
                                <span class="g-font-size-24 g-line-height-1 g-color-black" id="debit">0.00</span>
                            </div>
                            <h6 class="g-font-size-16 g-font-weight-300 g-color-gray-dark-v6 mb-0">
                                {{ _i('Total discharges') }}
                            </h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('back.layout.litepicker')
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
                    <table class="table table-bordered display nowrap" style="width:100%" id="cash-flow-transactions-table"
                           data-route="{{ route('agents.reports.cash-flow-data') }}">
                        <thead>
                        <tr>
                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                {{ _i('ID') }}
                            </th>
                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                {{ _i('Username') }}
                            </th>
                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                {{ _i('Initial balance') }}
                            </th>
                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                {{ _i('Recharge') }}
                            </th>
                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                {{ _i('Discharge') }}
                            </th>
                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                {{ _i('Final balance') }}
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
@endsection

@section('scripts')
    <script>
        $(function () {
            let agents = new Agents();
            agents.cashFlowByDates();
        });
    </script>
@endsection

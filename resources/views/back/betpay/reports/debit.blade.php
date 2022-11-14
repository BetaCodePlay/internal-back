@extends('back.template')

@section('content')
    <div class="row">
    <div class="offset-sm-6 offset-lg-9 offset-xl-9 col-sm-6 col-lg-3 col-xl-3 g-mb-30">
        <div class="card h-100 g-brd-gray-light-v7 g-rounded-3">
            <div class="card-block g-font-weight-300 g-pa-20">
                <div class="media">
                    <div class="d-flex g-mr-15">
                        <div
                                class="u-header-dropdown-icon-v1 g-pos-rel g-width-60 g-height-60 g-bg-primary g-font-size-18 g-font-size-24--md g-color-white rounded-circle">
                            <i class="hs-admin-stats-down g-absolute-centered"></i>
                        </div>
                    </div>
                    <div class="media-body align-self-center">
                        <div class="d-flex align-items-center g-mb-5">
                            <span class="g-font-size-24 g-line-height-1 g-color-black" id="total">0.00</span>
                        </div>
                        <h6 class="g-font-size-16 g-font-weight-300 g-color-gray-dark-v6 mb-0">
                            {{ _i('Total') }}
                        </h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <input type="hidden" name="paymentMethod" id="paymentMethod" value="{{ $payment_method }}">
    @include('back.layout.litepicker-with-processing-status')
    <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
        <header class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
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
                <table class="table table-bordered w-100" id="debit-table" data-route="{{ route('betpay.reports.debit-data') }}">
                    <thead>
                    <tr>
                        <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                            {{ _i('ID') }}
                        </th>
                        <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                            {{ _i('Username') }}
                        </th>
                        <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                            {{ _i('Amount') }}
                        </th>
                        <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                            {{ _i('Currency') }}
                        </th>
                        <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                            {{ _i('Data for withdrawal') }}
                        </th>
                        <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                            {{ _i('Details') }}
                        </th>
                        <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                            {{ _i('Requested') }}
                        </th>
                        <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                            {{ _i('Status') }}
                        </th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function () {
            let betPay = new BetPay();
            betPay.debitTransactionsReport();
        });
    </script>
@endsection

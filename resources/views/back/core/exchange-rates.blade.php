@extends('back.template')

@section('content')
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
                        <table class="table table-bordered w-100" id="exchange-rates-table" data-route="{{ route('core.update-exchange-rates') }}">
                            <thead>
                            <tr>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Currency') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none text-right">
                                    {{ _i('Last update') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none text-right">
                                    {{ _i('Rate') }}
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($exchange_rates as $rate)
                                <tr>
                                    <td>{{ $rate->currency_iso }}</td>
                                    <td class="text-right">
                                        <span id="updated">
                                            {{ $rate->updated }}
                                        </span>
                                    </td>
                                    <td class="d-flex justify-content-end form-inline">
                                        <div class="input-group">
                                            <input type="text" id="rate-{{ $rate->id }}" class="form-control" value="{{ $rate->amount }}">
                                            <div class="input-group-append">
                                                <button class="btn u-btn-primary update-exchange" type="button" data-rate="{{ $rate->id }}" data-loading-text="<i class='fa fa-spin fa-spinner'></i>">
                                                    <i class="hs-admin-reload"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
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
            let core = new Core();
            core.exchangeRates();
        });
    </script>
@endsection

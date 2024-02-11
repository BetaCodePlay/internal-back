@extends('back.template')

@section('styles')

@endsection

@section('content')
    <div class="wrapper-title g-pb-30">
        {{ _i('Dashboard') }}
    </div>

    <div class="page-dashboard">
        <div class="row">
            <div class="col-12 col-xl-4">
                <div class="dashboard-content">
                    <div class="dashboard-content-title">{{ _i('Total balance') }}</div>
                    <div class="dash-balance">
                        <div class="dash-balance-total">
                            <span class="minus">$</span>80,000.<span class="minus">00</span>
                        </div>
                        <div class="dash-balance-amount">
                            <div class="dash-balance-amount-item">
                                <div class="dash-balance-amount-name">
                                    <div class="dash-balance-amount-icon">
                                        <i class="fa-solid fa-arrow-trend-up"></i>
                                    </div>
                                    <div class="dash-balance-amount-name-text">
                                        <div class="span">{{ _i('Amount deposited') }}</div>
                                        <div class="span">{{ _i('on day') }}</div>
                                    </div>
                                </div>

                                <div class="dash-balance-amount-balance">
                                    <span class="minus">$</span>80,000.<span class="minus">00</span>
                                </div>
                            </div>

                            <div class="dash-balance-amount-item">
                                <div class="dash-balance-amount-name">
                                    <div class="dash-balance-amount-icon">
                                        <i class="fa-solid fa-arrow-trend-up"></i>
                                    </div>
                                    <div class="dash-balance-amount-name-text">
                                        <div class="span">{{ _i('Total amount') }}</div>
                                        <div class="span">{{ _i('prizewinning') }}</div>
                                    </div>
                                </div>

                                <div class="dash-balance-amount-balance">
                                    <span class="minus">$</span>2,000.<span class="minus">00</span>
                                </div>
                            </div>

                            <div class="dash-balance-amount-item">
                                <div class="dash-balance-amount-name">
                                    <div class="dash-balance-amount-icon">
                                        <i class="fa-solid fa-arrow-trend-up"></i>
                                    </div>
                                    <div class="dash-balance-amount-name-text">
                                        <div class="span">{{ _i('Total amount') }}</div>
                                        <div class="span">{{ _i('played') }}</div>
                                    </div>
                                </div>

                                <div class="dash-balance-amount-balance">
                                    <span class="minus">$</span>17,550.<span class="minus">00</span>
                                </div>
                            </div>

                            <div class="dash-balance-amount-item">
                                <div class="dash-balance-amount-name">
                                    <div class="dash-balance-amount-icon">
                                        <i class="fa-solid fa-arrow-trend-up"></i>
                                    </div>
                                    <div class="dash-balance-amount-name-text">
                                        <div class="span">{{ _i('Total amount') }}</div>
                                        <div class="span">{{ _i('to turn off') }}</div>
                                    </div>
                                </div>

                                <div class="dash-balance-amount-balance">
                                    <span class="minus">$</span>5,000.<span class="minus">00</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6 col-xl-4">
                <div class="dashboard-content">
                    <div class="dashboard-content-title">{{ _i('Transactions') }}</div>
                    <div class="dash-transactions">
                        <div class="dash-transactions-ex">
                            <div class="dash-transactions-item">
                                <div class="dash-transactions-item-text">
                                    <div class="dash-transactions-item-text-top">
                                        <i class="fa-solid fa-circle"></i> orlando_99
                                    </div>
                                    <div class="dash-transactions-item-text-bottom">
                                        01-01-2024 10:32
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

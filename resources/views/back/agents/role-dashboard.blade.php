@extends('back.template')

@section('styles')

@endsection

@section('content')
    <div class="wrapper-title g-pb-30">
        {{ _i('Dashboard') }}
    </div>

    <div class="page-dashboard">
        <div class="row">
            <div class="col-12 col-xl-4 mb-4">
                <div class="dashboard-content dashboard-content-mobile">
                    <div class="dashboard-content-title">{{ _i('Total balance') }}</div>
                    <div class="dash-balance">
                        <div class="dash-balance-total">
                            <!-- <span class="minus">$</span> 80,000.<span class="minus">00</span> -->
                            <span class="minus"></span>{{  $dashboard['amounts']['totalBalance'] }}</span>
                        </div>
                        <div class="dash-balance-amount">
                            <div class="dash-balance-amount-ex">
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
                                        <!--  <span class="minus">$</span>80,000.<span class="minus">00</span> -->
                                        {{--<span class="minus">$</span>--}}{{ $dashboard['amounts']['totalDeposited'] }}
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
                                        {{--<span
                                            class="minus">$</span>--}}{{ $dashboard['amounts']['totalPrizeWinningAmount'] }}
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
                                        {{--<span
                                            class="minus">$</span>--}}{{ $dashboard['amounts']['totalPlayedAmount'] }}
                                    </div>
                                </div>

                                {{--<div class="dash-balance-amount-item">
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
                                </div>--}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6 col-xl-4 mb-4">
                <div class="dashboard-content">
                    <div class="dashboard-content-title">{{ _i('Transactions') }}</div>
                    <div class="dash-transactions">
                        <div class="dash-transactions-ex">
                            @foreach ($dashboard['transactions'] as $transactions)
                                <div class="dash-transactions-item">
                                    <div class="dash-transactions-item-text">
                                        <div class="dash-transactions-item-text-top">
                                            <span class="icon">
                                                @if( $transactions->transactionType == 1)
                                                    <i class="fa-solid fa-arrow-down-long"></i>
                                                @else
                                                    <i class="fa-solid fa-arrow-up-long"></i>
                                                @endif
                                            </span>

                                            @if( $transactions->transactionType == 1)
                                                {{ _i('You receive') }}
                                            @else
                                                {{ _i('You sent') }}
                                            @endif
                                        </div>
                                        <div
                                            class="dash-transactions-item-text-middle">{{ $transactions->transactionType == 1 ?_i('Payment with debit for') : _i('Transfer to') }} {{ $transactions->username }}</div>
                                        <div class="dash-transactions-item-text-bottom">
                                            {{ $transactions->date }}
                                        </div>
                                    </div>

                                    <div
                                        class="dash-transactions-amount {{ $transactions->transactionType == 1 ? '' : 'transactions-send' }}">
                                        <span
                                            class="minus">{{ $transactions->transactionType == 1 ? '+' : '-' }}$</span> {{ $transactions->amount }}
                                    </div>
                                </div>
                            @endforeach
                            <!--
                            <div class="dash-transactions-item">
                                <div class="dash-transactions-item-text">
                                    <div class="dash-transactions-item-text-top">
                                        <span class="icon green"><i class="fa-solid fa-circle"></i></span>
                                        orlando_99
                                    </div>
                                    <div class="dash-transactions-item-text-bottom">
                                        01-01-2024 10:32
                                    </div>
                                </div>

                                <div class="dash-transactions-amount">
                                    <span class="minus">$</span>2,034.<span class="minus">00</span>
                                </div>
                            </div>

                            <div class="dash-transactions-item">
                                <div class="dash-transactions-item-text">
                                    <div class="dash-transactions-item-text-top">
                                        <span class="icon green"><i class="fa-solid fa-circle"></i></span>
                                        username123
                                    </div>
                                    <div class="dash-transactions-item-text-bottom">
                                        01-01-2024 14:11
                                    </div>
                                </div>

                                <div class="dash-transactions-amount">
                                    <span class="minus">$</span>542.<span class="minus">57</span>
                                </div>
                            </div>

                            <div class="dash-transactions-item">
                                <div class="dash-transactions-item-text">
                                    <div class="dash-transactions-item-text-top">
                                        <span class="icon"><i class="fa-solid fa-circle"></i></span>
                                        gustavoperez
                                    </div>
                                    <div class="dash-transactions-item-text-bottom">
                                        01-01-2024 01:01
                                    </div>
                                </div>

                                <div class="dash-transactions-amount">
                                    <span class="minus">$</span>1,000.<span class="minus">00</span>
                                </div>
                            </div>

                            <div class="dash-transactions-item">
                                <div class="dash-transactions-item-text">
                                    <div class="dash-transactions-item-text-top">
                                        <span class="icon green"><i class="fa-solid fa-circle"></i></span>
                                        username123
                                    </div>
                                    <div class="dash-transactions-item-text-bottom">
                                        01-01-2024 14:11
                                    </div>
                                </div>

                                <div class="dash-transactions-amount">
                                    <span class="minus">$</span>542.<span class="minus">57</span>
                                </div>
                            </div>

                            <div class="dash-transactions-item">
                                <div class="dash-transactions-item-text">
                                    <div class="dash-transactions-item-text-top">
                                        <span class="icon green"><i class="fa-solid fa-circle"></i></span>
                                        username123
                                    </div>
                                    <div class="dash-transactions-item-text-bottom">
                                        01-01-2024 14:11
                                    </div>
                                </div>

                                <div class="dash-transactions-amount">
                                    <span class="minus">$</span>542.<span class="minus">57</span>
                                </div>
                            </div>

                            <div class="dash-transactions-item">
                                <div class="dash-transactions-item-text">
                                    <div class="dash-transactions-item-text-top">
                                        <span class="icon green"><i class="fa-solid fa-circle"></i></span>
                                        username123
                                    </div>
                                    <div class="dash-transactions-item-text-bottom">
                                        01-01-2024 14:11
                                    </div>
                                </div>

                                <div class="dash-transactions-amount">
                                    <span class="minus">$</span>542.<span class="minus">57</span>
                                </div>
                            </div>

                            <div class="dash-transactions-item">
                                <div class="dash-transactions-item-text">
                                    <div class="dash-transactions-item-text-top">
                                        <span class="icon green"><i class="fa-solid fa-circle"></i></span>
                                        username123
                                    </div>
                                    <div class="dash-transactions-item-text-bottom">
                                        01-01-2024 14:11
                                    </div>
                                </div>

                                <div class="dash-transactions-amount">
                                    <span class="minus">$</span>542.<span class="minus">57</span>
                                </div>
                            </div>

                            <div class="dash-transactions-item">
                                <div class="dash-transactions-item-text">
                                    <div class="dash-transactions-item-text-top">
                                        <span class="icon green"><i class="fa-solid fa-circle"></i></span>
                                        username123
                                    </div>
                                    <div class="dash-transactions-item-text-bottom">
                                        01-01-2024 14:11
                                    </div>
                                </div>

                                <div class="dash-transactions-amount">
                                    <span class="minus">$</span>542.<span class="minus">57</span>
                                </div>
                            </div>

                            <div class="dash-transactions-item">
                                <div class="dash-transactions-item-text">
                                    <div class="dash-transactions-item-text-top">
                                        <span class="icon green"><i class="fa-solid fa-circle"></i></span>
                                        username123
                                    </div>
                                    <div class="dash-transactions-item-text-bottom">
                                        01-01-2024 14:11
                                    </div>
                                </div>

                                <div class="dash-transactions-amount">
                                    <span class="minus">$</span>542.<span class="minus">57</span>
                                </div>
                            </div>

                            <div class="dash-transactions-item">
                                <div class="dash-transactions-item-text">
                                    <div class="dash-transactions-item-text-top">
                                        <span class="icon green"><i class="fa-solid fa-circle"></i></span>
                                        username123
                                    </div>
                                    <div class="dash-transactions-item-text-bottom">
                                        01-01-2024 14:11
                                    </div>
                                </div>

                                <div class="dash-transactions-amount">
                                    <span class="minus">$</span>542.<span class="minus">57</span>
                                </div>
                            </div>

                            <div class="dash-transactions-item">
                                <div class="dash-transactions-item-text">
                                    <div class="dash-transactions-item-text-top">
                                        <span class="icon green"><i class="fa-solid fa-circle"></i></span>
                                        username123
                                    </div>
                                    <div class="dash-transactions-item-text-bottom">
                                        01-01-2024 14:11
                                    </div>
                                </div>

                                <div class="dash-transactions-amount">
                                    <span class="minus">$</span>542.<span class="minus">57</span>
                                </div>
                            </div>
                             -->
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6 col-xl-4 mb-4">
                <div class="dashboard-content">
                    <div class="dashboard-content-title">
                        {{ _i('Recent activity') }}
                        {{--<a href="#">{{ _i('See more') }} <i class="fa-solid fa-angle-right"></i></a>--}}
                    </div>

                    <div class="dash-recent-activity">
                        <div class="dash-recent-activity-ex">
                            @foreach ($dashboard['audits'] as $audits)
                                <div class="dash-recent-activity-item">
                                    <div class="dash-recent-activity-item-text">
                                        <div class="dash-recent-activity-item-text-top">
                                            <span class="icon green"><i class="fa-solid fa-circle"></i></span>
                                            {{ $audits->name }}
                                        </div>
                                        <div class="dash-recent-activity-item-text-bottom">
                                            {{ $audits->formatted_date }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <!--
                            <div class="dash-recent-activity-item">
                                <div class="dash-recent-activity-item-text">
                                    <div class="dash-recent-activity-item-text-top">
                                        <span class="icon green"><i class="fa-solid fa-circle"></i></span>
                                        Update Server log
                                    </div>
                                    <div class="dash-recent-activity-item-text-bottom">
                                        01-01-2024 10:32
                                    </div>
                                </div>
                            </div>

                            <div class="dash-recent-activity-item">
                                <div class="dash-recent-activity-item-text">
                                    <div class="dash-recent-activity-item-text-top">
                                        <span class="icon green"><i class="fa-solid fa-circle"></i></span>
                                        Send Mail to HR and Admin
                                    </div>
                                    <div class="dash-recent-activity-item-text-bottom">
                                        01-01-2024 10:32
                                    </div>
                                </div>
                            </div>

                            <div class="dash-recent-activity-item">
                                <div class="dash-recent-activity-item-text">
                                    <div class="dash-recent-activity-item-text-top">
                                        <span class="icon green"><i class="fa-solid fa-circle"></i></span>
                                        Update Server log
                                    </div>
                                    <div class="dash-recent-activity-item-text-bottom">
                                        01-01-2024 10:32
                                    </div>
                                </div>
                            </div>

                            <div class="dash-recent-activity-item">
                                <div class="dash-recent-activity-item-text">
                                    <div class="dash-recent-activity-item-text-top">
                                        <span class="icon green"><i class="fa-solid fa-circle"></i></span>
                                        Send Mail to HR and Admin
                                    </div>
                                    <div class="dash-recent-activity-item-text-bottom">
                                        01-01-2024 10:32
                                    </div>
                                </div>
                            </div>

                            <div class="dash-recent-activity-item">
                                <div class="dash-recent-activity-item-text">
                                    <div class="dash-recent-activity-item-text-top">
                                        <span class="icon green"><i class="fa-solid fa-circle"></i></span>
                                        Update Server log
                                    </div>
                                    <div class="dash-recent-activity-item-text-bottom">
                                        01-01-2024 10:32
                                    </div>
                                </div>
                            </div>

                            <div class="dash-recent-activity-item">
                                <div class="dash-recent-activity-item-text">
                                    <div class="dash-recent-activity-item-text-top">
                                        <span class="icon green"><i class="fa-solid fa-circle"></i></span>
                                        Send Mail to HR and Admin
                                    </div>
                                    <div class="dash-recent-activity-item-text-bottom">
                                        01-01-2024 10:32
                                    </div>
                                </div>
                            </div>

                            <div class="dash-recent-activity-item">
                                <div class="dash-recent-activity-item-text">
                                    <div class="dash-recent-activity-item-text-top">
                                        <span class="icon green"><i class="fa-solid fa-circle"></i></span>
                                        Update Server log
                                    </div>
                                    <div class="dash-recent-activity-item-text-bottom">
                                        01-01-2024 10:32
                                    </div>
                                </div>
                            </div>

                            <div class="dash-recent-activity-item">
                                <div class="dash-recent-activity-item-text">
                                    <div class="dash-recent-activity-item-text-top">
                                        <span class="icon green"><i class="fa-solid fa-circle"></i></span>
                                        Send Mail to HR and Admin
                                    </div>
                                    <div class="dash-recent-activity-item-text-bottom">
                                        01-01-2024 10:32
                                    </div>
                                </div>
                            </div>

                            <div class="dash-recent-activity-item">
                                <div class="dash-recent-activity-item-text">
                                    <div class="dash-recent-activity-item-text-top">
                                        <span class="icon green"><i class="fa-solid fa-circle"></i></span>
                                        Update Server log
                                    </div>
                                    <div class="dash-recent-activity-item-text-bottom">
                                        01-01-2024 10:32
                                    </div>
                                </div>
                            </div>

                            <div class="dash-recent-activity-item">
                                <div class="dash-recent-activity-item-text">
                                    <div class="dash-recent-activity-item-text-top">
                                        <span class="icon green"><i class="fa-solid fa-circle"></i></span>
                                        Send Mail to HR and Admin
                                    </div>
                                    <div class="dash-recent-activity-item-text-bottom">
                                        01-01-2024 10:32
                                    </div>
                                </div>
                            </div>

                            <div class="dash-recent-activity-item">
                                <div class="dash-recent-activity-item-text">
                                    <div class="dash-recent-activity-item-text-top">
                                        <span class="icon green"><i class="fa-solid fa-circle"></i></span>
                                        Update Server log
                                    </div>
                                    <div class="dash-recent-activity-item-text-bottom">
                                        01-01-2024 10:32
                                    </div>
                                </div>
                            </div>

                            <div class="dash-recent-activity-item">
                                <div class="dash-recent-activity-item-text">
                                    <div class="dash-recent-activity-item-text-top">
                                        <span class="icon green"><i class="fa-solid fa-circle"></i></span>
                                        Send Mail to HR and Admin
                                    </div>
                                    <div class="dash-recent-activity-item-text-bottom">
                                        01-01-2024 10:32
                                    </div>
                                </div>
                            </div>
                            -->
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6 col-xl-6 mb-4">
                <div class="dashboard-content">
                    <div class="dashboard-content-title">{{ _i('Top 10 games') }}</div>
                    <div class="top-ten-games">
                        <div class="top-ten-games-ex">
                            <div class="top-ten-games-head">
                                <div class="top-ten-games-head-tr">
                                    {{ _i('Game') }}
                                </div>
                                <div class="top-ten-games-head-tr">
                                    {{ _i('Provider') }}
                                </div>
                                <div class="top-ten-games-head-tr">
                                    {{ _i('Players') }}
                                </div>
                            </div>

                            <div class="top-ten-games-body">
                                <div class="top-ten-games-body-ex">
                                    @foreach($dashboard['games'] as $game)
                                        <div class="top-ten-games-body-tr">
                                            <div class="top-ten-games-body-th">
                                                <figure style="background-image: url('{{ imageUrlFormat($game, $game?->maker) }}')"></figure>
                                                {{ $game?->name }}
                                            </div>

                                            <div class="top-ten-games-body-th">
                                                {{ $game?->maker }}
                                            </div>
                                            <div class="top-ten-games-body-th">
                                                <span class="deco-text">{{ $game?->total_users }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6 col-xl-6 mb-4">
                <div class="dashboard-content">
                    <div class="dashboard-content-title">{{ _i('Top 10 providers') }}</div>
                    <div class="top-ten-games">
                        <div class="top-ten-games-ex">
                            <div class="top-ten-games-head">
                                <div class="top-ten-games-head-tr">
                                    {{ _i('Provider') }}
                                </div>
                                <div class="top-ten-games-head-tr">
                                    {{ _i('Games') }}
                                </div>
                                <div class="top-ten-games-head-tr">
                                    {{ _i('Players') }}
                                </div>
                            </div>

                            <div class="top-ten-games-body">
                                <div class="top-ten-games-body-ex">
                                    @foreach ($dashboard['makers'] as $maker)
                                        <div class="top-ten-games-body-tr">
                                            <div class="top-ten-games-body-th">
                                                {{--<figure
                                                    style="background-image: url('https://www.gammastack.com/wp-content/uploads/2021/12/PragmaticPlay-300x173.png')"></figure>--}}
                                                {{ $maker?->maker }}
                                            </div>
                                            <div class="top-ten-games-body-th">
                                                <span class="deco-text">{{ $maker?->total_games }}</span>
                                            </div>
                                            <div class="top-ten-games-body-th">
                                                <span class="deco-text">{{ $maker?->total_users }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

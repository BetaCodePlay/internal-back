@extends('back.template')

@section('styles')

@endsection

@section('content')
    <div class="wrapper-title g-pb-30">
        {{ _i('Dashboard') }}
    </div>

    <div class="page-dashboard">
        <div class="row">
            <div class="col-12 col-lg-4">
                <div class="dashboard-content">
                    <div class="dashboard-content-title">{{ _i('Total balance') }}</div>
                    <div class="dash-balance">
                        <div class="dash-balance-total">
                            <span class="minus">S</span>80,000.<span class="minus">00</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

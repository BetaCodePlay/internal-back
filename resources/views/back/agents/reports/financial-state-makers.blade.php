@extends('back.template')
@section('styles')
    <style>
        .init_agent {
            color: #3398dc !important;
            font-weight: bold !important;
        }

        .init_user {
            color: #e62154 !important;
            font-weight: bold !important;
        }
        .select2-container .select2-selection--single {
            height: 2.4rem;
        }
        .p-lr-out {
            padding-left: 0;
            padding-right: 0;
        }
        .w-th-17-5{
            width: 17.5%;
        }
        .w-th-20{
            width: 20%;
        }
        .w-th-23{
            width: 23%;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="offset-md-7"></div>
        <div class="col-md-2" style="    padding: 0%!important;">
            <div class="input-group">
                <select name="currency_id" id="currency_id">
{{--                    <option value="" selected="selected" hidden>Selecciona un Proveedor</option>--}}
                    @foreach($currencies as $val)
                        <option value="{{$val}}">{{$val}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-2" style="padding: 0%!important;">
            <div class="input-group">
                <input type="text" id="date_range" class="form-control" autocomplete="off"
                       placeholder="{{ _i('Date range') }}">
            </div>
        </div>
        <div class="col-md-1">
            <div class="input-group">
                <div class="input-group-append">
                    <button class="btn g-bg-primary" type="button" id="update"
                            data-route="{{ route('agents.reports.financial-state-data-makers') }}"
                            data-routetotals="{{ route('agents.reports.financial-state-data-makers-totals') }}"
                            data-loading-text="<i class='hs-admin-reload fa-spin g-color-white'></i>">
                        <i class="hs-admin-search g-color-white"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
        <header
            class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
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
            <div class="table-responsive" id="financial-state-table-makers"
                 data-route="{{ route('agents.reports.financial-state-data-makers') }}"
                 data-routetotals="{{ route('agents.reports.financial-state-data-makers-totals') }}">

            </div>
            <div class="col-md-12 p-lr-out">
                <br>
                <div class="table-responsive">
                    <div class="financialStateDataMakersTotals"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function () {
            let agents = new Agents();
            agents.financialStateMakers();
            setTimeout(function (){
                $('#update').click()
            },1000)
        });
    </script>
@endsection

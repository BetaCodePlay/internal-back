@extends('back.template')
@section('styles')
    <style>
        .init_agent{
            color: #3398dc !important;
            font-weight: bold!important;
        }
        .init_user{
            color: #e62154 !important;
            font-weight: bold!important;
        }
    </style>
@endsection
@section('content')
{{--    {{dd($color = substr(md5('estarly'), 0, 6))}}--}}
<div class="row">
    <div class="offset-md-5"></div>
    <div class="col-md-2" style="    padding: 0%!important;">
        <div class="input-group">
            <select name="provider_id" id="provider_id">
                <option value="" selected="selected" hidden>Selecciona un Proveedor</option>
                <option value="171">Bet Connections</option>
                <option value="166">LV SLots</option>
                <option value="115">Dot Suite</option>
            </select>
        </div>
    </div>
    <div class="col-md-2">
        <div class="input-group">
            <input type="text" id="username_like" name="username_like" class="form-control" autocomplete="off" placeholder="{{ _i('Username') }}">
        </div>
    </div>
    <div class="col-md-2" style="padding: 0%!important;">
        <div class="input-group">
            <input type="text" id="date_range" class="form-control" autocomplete="off" placeholder="{{ _i('Date range') }}">
        </div>
    </div>
    <div class="col-md-1">
        <div class="input-group">
            <div class="input-group-append">
                <button class="btn g-bg-primary" type="button" id="update"
                        data-loading-text="<i class='hs-admin-reload fa-spin g-color-white'></i>">
                    <i class="hs-admin-search g-color-white"></i>
                </button>
            </div>
        </div>
    </div>
</div>
<br>
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
            <div class="table-responsive" id="financial-state-table" data-route="{{ route('agents.reports.details.financial-state') }}">

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function () {
            let agents = new Agents();
            agents.financialState({{ $user }});
        });
    </script>
@endsection

@extends('back.template')
@section('styles')
    <style>
        .name_1{
            color: #3398dc !important
        }
        .name_2{
            color: #e62154 !important
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-4"></div>
            <div class="col-md-4">
                <div class="input-group">
                    <input type="text" id="username_like" name="username_like" class="form-control" autocomplete="off" placeholder="{{ _i('Username') }}">
                </div>
            </div>
            <div class="col-md-4">
                <div class="input-group">
                    <input type="text" id="date_range" class="form-control" autocomplete="off" placeholder="{{ _i('Date range') }}">
                    <div class="input-group-append">
                        <button class="btn g-bg-primary" type="button" id="update"
                                data-loading-text="<i class='fa fa-spin fa-refresh g-color-white'></i>">
                            <i class="hs-admin-reload g-color-white"></i>
                        </button>
                    </div>
                </div>
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
            <div class="table-responsive" id="financial-state-table" data-route="{{ route('agents.reports.financial-state-data.username') }}">

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function () {
            let agents = new Agents();
            agents.financialState({{ $user }});
            $('#update').trigger('click')
        });
    </script>
@endsection

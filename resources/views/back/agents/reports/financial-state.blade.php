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
    @include('back.layout.litepicker')
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
            <div class="table-responsive" id="financial-state-table" data-route="{{ route('agents.reports.financial-state-data') }}">

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function () {
            let agents = new Agents();
            agents.financialState({{ $user }});
            setTimeout(function (){
                $('#update').click()
            },1000)
        });
    </script>
@endsection

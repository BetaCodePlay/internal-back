@extends('back.template')

@section('content')
{{--    <div class="noty_bar noty_type__warning noty_theme__unify--v1 g-mb-25">--}}
{{--        <div class="noty_body">--}}
{{--            <div class="g-mr-20">--}}
{{--                <div class="noty_body__icon">--}}
{{--                    <i class="hs-admin-alert"></i>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div>--}}
{{--                {{ _i('This report makes closings and calculations every hour') }}--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
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
            <div class="table-responsive" id="financial-state-table" data-route="{{ route('agents.reports.financial-state-data.provider') }}">

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function () {
            let agents = new Agents();
            agents.financialState({{ $user }});

            // financialStateDetails(user = null) {
            //     let picker = initLitepickerEndToday();
            //     let $table = $('#financial-state-table');
            //     let $button = $('#update');
            //     let api;
            //     if (user == null) {
            //         $('#financial-state-tab').on('show.bs.tab', function () {
            //             $table.children().remove();
            //             user = $('.user').val();
            //         });
            //     }
            //
            //     $button.click(function () {
            //         $button.button('loading');
            //         let startDate = moment(picker.getStartDate()).format('YYYY-MM-DD');
            //         let endDate = moment(picker.getEndDate()).format('YYYY-MM-DD');
            //
            //         $.ajax({
            //             url: `${$table.data('route')}/${user}/${startDate}/${endDate}`,
            //             type: 'get',
            //             dataType: 'json'
            //
            //         }).done(function (json) {
            //             $table.html(json.data.table);
            //
            //         }).fail(function (json) {
            //             swalError(json);
            //
            //         }).always(function () {
            //             $button.button('reset');
            //         });
            //     });
            // }

        });
    </script>
@endsection

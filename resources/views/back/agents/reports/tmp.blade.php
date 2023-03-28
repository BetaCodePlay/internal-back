@extends('back.template')
@section('styles')
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
            <div class="table-responsive">

            <table id="exampleTable" class="table table-bordered table-hover dt-responsive"  width="100%">
                <thead>
                <tr>
                    <th> {{ _i('Maker') }}</th>
                    <th> {{ _i('Username') }}</th>
                    <th> {{ _i('Payed') }}</th>
                </tr>
                </thead>
{{--                <tfoot>--}}
{{--                <tr>--}}
{{--                    <th>First name</th>--}}
{{--                    <th>Last name</th>--}}
{{--                </tr>--}}
{{--                </tfoot>--}}
            </table>
        </div>
{{--            <div class="table-responsive" id="financial-state-table" data-route="{{ route('agents.reports.financial-state-data') }}">--}}

{{--            </div>--}}
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function () {
            $('#exampleTable')
                .DataTable({
                    processing: true,
                    serverSide: true,
                    lengthMenu:[10,20,30],
                    ajax: {
                        url: '{{route('reports.data.tmp')}}',
                        dataType: 'json',
                        type: 'get',
                    },
                    columns: [
                        { data: 'name_maker' },
                        { data: 'username' },
                        { data: 'total_played' },
                    ],
                });
            {{--let agents = new Agents();--}}
            {{--agents.financialState({{ $user }});--}}
        });
    </script>
@endsection

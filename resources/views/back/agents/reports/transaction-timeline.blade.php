@extends('back.template')
@section('styles')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap.min.css">

<style>
    table.display {
        table-layout: fixed; /* Establecer el diseño de la tabla en fijo */
    }

    th, td {
        word-wrap: break-word; /* Permitir que las palabras se ajusten en las celdas */
        overflow: auto; /* Ocultar el contenido que se desborda */
        text-overflow: ellipsis; /* Mostrar puntos suspensivos para el contenido que se desborda */
    }
</style>
@endsection
@section('content')

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
            <div class="table-reponsive">

            <table id="exampleTable" class="table table-bordered display nowrap"  style="width:100%">
                @include('back.layout.litepicker')
                <thead>
                <tr>
                    <th> {{ _i('Date') }}</th>
                    <th> {{ _i('Description') }}</th>
{{--                    <th> Debit </th>--}}
{{--                    <th> Credit</th>--}}
                    <th> {{ _i('Debit') }}</th>
                    <th> {{ _i('Credit') }}</th>
{{--                    @if(in_array(\Dotworkers\Security\Enums\Roles::$admin_Beet_sweet, session('roles')))--}}
{{--                        <th> {{ _i('Balance').' ('.\Illuminate\Support\Facades\Auth::user()->username.')' }}</th>--}}
{{--                    @else--}}
{{--                        <th> {{ _i('Balance')}}</th>   --}}
{{--                    @endif--}}
                    <th> {{ _i('Balance')}}</th>
                </tr>
                </thead>
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
            let agents = new Agents();
            agents.transactionTimeline('{{route('reports.data.transaction.timeline')}}','#exampleTable',[10,20,50,100]);
        });

    </script>
@endsection

@extends('back.template')
@section('styles')
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

            <table id="exampleTable" class="table table-bordered table-hover dt-responsive"  width="100%">
                @include('back.layout.litepicker')
                <thead>
                <tr>
                    <th> {{ _i('Date') }}</th>
                    <th> {{ _i('Description') }}</th>
{{--                    <th> {{ _i('Debit') }} 2: debit=cargar</th>--}}
{{--                    <th> {{ _i('Credit') }} 1:credit=abonar o descargar</th>--}}
                    <th> {{ _i('Debit') }}</th>
                    <th> {{ _i('Credit') }}</th>
                    <th> {{ _i('Balance').' ('.\Illuminate\Support\Facades\Auth::user()->username.')' }}</th>
{{--                    <th> {{ _i('Balance') }}</th>--}}
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
            agents.returnDate('{{route('reports.data.transaction.timeline')}}','#exampleTable');

           {{--setTimeout(function(){--}}
           {{--    $.ajax({--}}
           {{--        url: '{{route('reports.data.transaction.timeline')}}',--}}
           {{--        dataType: 'json',--}}
           {{--        type: 'get',--}}
           {{--        success : function(data) {--}}
           {{--            //var o = JSON.parse(data);//A la variable le asigno el json decodificado--}}
           {{--            var o =data;//A la variable le asigno el json decodificado--}}
           {{--            console.log(o);--}}

           {{--        }--}}
           {{--    })--}}
           {{--},2000);--}}
           {{-- $('#exampleTable')--}}
           {{--     .DataTable({--}}
           {{--         processing: true,--}}
           {{--         serverSide: true,--}}
           {{--         lengthMenu:[10,20,30],--}}
           {{--         ajax: {--}}
           {{--             url: '{{route('reports.data.transaction.timeline')}}',--}}
           {{--             dataType: 'json',--}}
           {{--             type: 'get',--}}
           {{--             data:{--}}
           {{--                 'date':document.getElementById('date_range')--}}
           {{--             }--}}
           {{--         },--}}
           {{--         columns: [--}}
           {{--             { data: 'date' },--}}
           {{--             { data: 'names' },--}}
           {{--             // { data: 'from' },--}}
           {{--             // { data: 'to' },--}}
           {{--             { data: 'debit' },--}}
           {{--             { data: 'credit' },--}}
           {{--             { data: 'balance' },--}}
           {{--             // {--}}
           {{--             //     render: function(data, type, full, meta) {--}}
           {{--             //         console.log(data, type, full, meta);--}}
           {{--             //         return full.balance;--}}
           {{--             //     }--}}
           {{--             // },--}}
           {{--         ],--}}
           {{--         // initComplete: function () {--}}
           {{--         //     // Apply the search--}}
           {{--         //     this.api().columns().every( function () {--}}
           {{--         //         var that = this;--}}
           {{--         //--}}
           {{--         //         $( 'input', this.header() ).on( 'keyup change clear', function () {--}}
           {{--         //             if ( that.search() !== this.value ) {--}}
           {{--         //                 that--}}
           {{--         //                     .search( this.value )--}}
           {{--         //                     .draw();--}}
           {{--         //             }--}}
           {{--         //         } );--}}
           {{--         //     } );--}}
           {{--         //--}}
           {{--         // }--}}
           {{--     });--}}

        });
    </script>
@endsection

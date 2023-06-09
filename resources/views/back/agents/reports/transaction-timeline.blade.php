@extends('back.template')

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
                <div class="media-body d-flex justify-content-start g-mb-10" id="table-buttons">

                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover dt-responsive" id="tableTimeline" data-route="{{ route('reports.data.transaction.timeline') }}" width="100%">
                    <thead>
                        <tr>
                            <th> {{ _i('Date') }}</th>
                            <th> {{ _i('Description') }}</th>
                            <th> {{ _i('Debit') }}</th>
                            <th> {{ _i('Credit') }}</th>
                            <th> {{ _i('Balance')}} <strong>{{'('._i('from').')'}}</strong></th>
                            <th> {{ _i('Balance')}}</th>
            {{--                    <th> {{ _i('Balance'). ' ('._i('to').')'}}</th>--}}
                        </tr>
                    </thead>
                </table>
        </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function () {
            let agents = new Agents();
            agents.transactionTimeline([50,100,500,1000,2000]);
        });

    </script>
@endsection

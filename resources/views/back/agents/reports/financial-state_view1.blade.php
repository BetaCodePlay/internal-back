@extends('back.template')

@section('content')
    <div class="noty_bar noty_type__warning noty_theme__unify--v1 g-mb-25">
        <div class="noty_body">
            <div class="g-mr-20">
                <div class="noty_body__icon">
                    <i class="hs-admin-alert"></i>
                </div>
            </div>
            <div>
                {{ _i('This report makes closings and calculations every hour') }}
            </div>
        </div>
    </div>
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
            <div class="table-responsive" id="financial-state-table" data-route="{{ route('agents.reports.financial-state-data.view1') }}">

            </div>
            <table class="table table-hover">
                <thead>
                <tr>
                    <th scope="col">Categoria</th>
                    <th scope="col">Apuestas</th>
                    <th scope="col">Apostado</th>
                    <th scope="col">Ganado</th>
                    <th scope="col">NetWin</th>
                    <th scope="col">Comision</th>
                </tr>
                </thead>
                <tbody>
                <tr class="table-primary">
                    <th><strong>Casino *</strong></th>
                    <td>4.000</td>
                    <td>3.500</td>
                    <td>100</td>
                    <td>200.000</td>
                    <td>4.000</td>
                </tr>
                <tr class="table-secondary">
                    <th>&nbsp;&nbsp;&nbsp;SG -</th>
                    <td>4.000</td>
                    <td>3.500</td>
                    <td>100</td>
                    <td>200.000</td>
                    <td>4.000</td>
                </tr>
                <tr class="table-success">
                    <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Evo Play /</th>
                    <td>4.000</td>
                    <td>3.500</td>
                    <td>100</td>
                    <td>200.000</td>
                    <td>4.000</td>
                </tr>
                <tr class="table-success">
                    <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Habanero /</th>
                    <td>4.000</td>
                    <td>3.500</td>
                    <td>100</td>
                    <td>200.000</td>
                    <td>4.000</td>
                </tr>
                <tr class="table-secondary">
                    <th>&nbsp;&nbsp;&nbsp;VG -</th>
                    <td>4.000</td>
                    <td>3.500</td>
                    <td>100</td>
                    <td>200.000</td>
                    <td>4.000</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function () {
            //let agents = new Agents();
            //agents.financialStateDetails({{ $user }});
        });
    </script>
@endsection

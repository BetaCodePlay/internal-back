@extends('back.template')

@section('content')
    @include('back.reports.products.layout.products')
@endsection

@section('scripts')
    <script>
        $(function () {
            let reports = new Reports();
            reports.productsTotals();
        });
    </script>
@endsection

@extends('back.template')

@section('content')
    <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
        <header
            class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
            <div class="media">
                <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                    {{ $title }}
                </h3>
                <div class="media-body d-flex justify-content-end">
                    <a href="{{ route('betpay.clients.create') }}" class="btn u-btn-3d u-btn-primary float-right">
                        <i class="hs-admin-upload"></i>
                        {{ _i('Create') }}
                    </a>
                </div>
            </div>
        </header>
        <div class="card-block g-pa-15">
            <div class="media">
                <div class="media-body d-flex justify-content-end g-mb-10" id="table-buttons">

                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered w-100" id="clients-table" data-route="{{ route('betpay.clients.all') }}">
                    <thead>
                    <tr>
                        <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                            {{ _i('ID') }}
                        </th>
                        <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                            {{ _i('Name') }}
                        </th>
                        <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                            {{ _i('Secret') }}
                        </th>
                        <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                            {{ _i('Endpoint') }}
                        </th>
                        <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                            {{ _i('Status') }}
                        </th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function () {
            let  betpay= new BetPay();
            betpay.all();
            $(document).on('click', '.revoked_checkbox', function () {
                if (!$(this).hasClass('active')) {
                    $.post('{{route('betpay.clients.status')}}', {client_id: $(this).data('id'),  name: 'revoked', value: true}, function () {});
                } else {
                    $.post('{{route('betpay.clients.status')}}', {client_id: $(this).data('id'),  name: 'revoked', value: false}, function () {});
                }
                $(this).toggleClass('active');
            });
        });
    </script>
@endsection

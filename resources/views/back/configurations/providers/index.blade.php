@extends('back.template')

@section('content')
    <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
        <header class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
            <div class="media">
                <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
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
                <table class="table table-bordered w-100" id="provider-table" data-route="{{ route('configurations.providers.data') }}">
                    <thead>
                    <tr>
                        <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                            {{  _i('Name') }}
                        </th>
                        <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                            {{  _i('Status') }}
                        </th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function () {
            let configurations = new Configurations();
            configurations.providers();
        });
        $(document).on('click', '.update_checkbox', function () {
            console.log('listo');
            if (!$(this).hasClass('active')) {
                $.post('{{route('configurations.providers.status')}}', {provider_id: $(this).data('id'), name: 'status', value: true}, function () {});
            } else {
                $.post('{{route('configurations.providers.status')}}', {provider_id: $(this).data('id'), name: 'status', value: false}, function () {});
            }

            $(this).toggleClass('active');
        });
    </script>
@endsection

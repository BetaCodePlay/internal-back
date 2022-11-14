@extends('back.template')

@section('content')
    <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
        <header
            class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
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
                <table class="table table-bordered w-100" id="pages-table" data-route="{{ route('pages.all') }}">
                    <thead>
                    <tr>
                        <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                            {{ _i('Suggested title') }}
                        </th>
                        <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                            {{ _i('Configured title') }}
                        </th>
                        <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                            {{ _i('Language') }}
                        </th>
                        <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                            {{ _i('Last modification') }}
                        </th>
                        <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                            {{ _i('Status') }}
                        </th>
                        <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                            {{ _i('Actions') }}
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
            let pages = new Pages();
            pages.all();
        });
    </script>
@endsection

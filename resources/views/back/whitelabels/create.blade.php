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
            <form action="{{ route('whitelabels.store') }}" id="whitelabels-form" method="post">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="name">{{ _i('Name (Uppercase only without spaces)') }}</label>
                            <input type="text" class="form-control text-uppercase" name="name">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="description">{{ _i('Description (It will be shown to the client and the user)') }}</label>
                            <input type="text" class="form-control" name="description">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="domain">{{ _i('Domain (example.com)') }}</label>
                            <input type="text" class="form-control" name="domain">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="button" class="btn u-btn-3d u-btn-primary" id="store"
                                    data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Creating...') }}">
                                <i class="hs-admin-save"></i>
                                {{ _i('Create') }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function () {
            let whitelabels = new Whitelabels();
            whitelabels.store();
        });
    </script>
@endsection

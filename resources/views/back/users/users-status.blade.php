@extends('back.template')

@section('content')
    <div class="row">
        <div class="col-md-12">
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
                    <div class="row">
                        <div class="col-md-6">
                            @if(in_array(19, session('roles')))
                                <div class="d-none">
                                    <input type="text" name="whitelabel" id="whitelabel"
                                           value="{{\Dotworkers\Configurations\Configurations::getWhitelabel()}}">
                                </div>
                            @else

                                <div class="form-group">
                                    <label for="client">{{ _i('Whitelabel') }}</label>
                                    <select name="whitelabel" id="whitelabel" class="form-control">
                                        <option value="">{{ _i('Select...') }}</option>
                                        @foreach ($whitelabels as $whitelabel)
                                            <option value="{{ $whitelabel->id }}">
                                                {{ $whitelabel->description }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                            @endif
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status">{{ _i('Status') }}</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="">{{ _i('Select...') }}</option>
                                    <option value="true">{{ _i('Active') }}</option>
                                    <option value="false">{{ _i('Blocked') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12" style="text-align: end;">
                            <div class="form-group">
                                <button type="button" class="btn u-btn-3d u-btn-primary" id="search"
                                        data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Searching...') }}">
                                    <i class="hs-admin-save"></i>
                                    {{ _i('Search') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                <header
                    class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                    <div class="media">
                        <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0 title">
                            {{ _i('Users') }}
                        </h3>
                        <div class="media-body d-flex justify-content-end" id="table-buttons">

                        </div>
                    </div>
                </header>
                <div class="card-block g-pa-15">
                    <table class="table table-bordered table-responsive-sm w-100" id="status-user-table"
                           data-route="{{ route('users.users-status.data') }}">
                        <thead>
                        <tr>
                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                {{ _i('User') }}
                            </th>
                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                {{ _i('Username') }}
                            </th>
                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                {{ _i('Email') }}
                            </th>
                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                {{ _i('Whitelabel') }}
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
    </div>
@endsection

@section('scripts')
    <script>
        $(function () {
            let users = new Users();
            users.statusUser();
        });
    </script>
@endsection

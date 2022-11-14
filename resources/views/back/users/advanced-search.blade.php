@extends('back.template')

@section('content')
    <div class="row">
        <div class="col-md-4">
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
                    <form action="{{ route('users.advanced-search-data') }}" id="advanced-search-form" method="post">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="id">{{ _i('ID') }}</label>
                                    <input type="text" class="form-control" name="id" id="id">
                                </div>
                            </div>
                            @can('access', [\Dotworkers\Security\Enums\Permissions::$show_wallet_id])
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="wallet_id">{{ _i('Wallet ID') }}</label>
                                        <input type="text" class="form-control" name="wallet" id="wallet">
                                    </div>
                                </div>
                            @endcan
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="username">{{ _i('Username') }}</label>
                                    <input type="text" class="form-control" name="username" id="username">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="dni">{{ _i('DNI') }}</label>
                                    <input type="text" class="form-control" name="dni" id="dni">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">{{ _i('Email') }}</label>
                                    <input type="email" class="form-control" name="email" id="email">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="first_name">{{ _i('Name') }}</label>
                                    <input type="text" class="form-control" name="first_name" id="first_name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="last_name">{{ _i('Last Name') }}</label>
                                    <input type="text" class="form-control" name="last_name" id="last_name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">{{ _i('Phone') }}</label>
                                    <input id="phone" name="phone" class="form-control" type="text">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="last_name">{{ _i('Referral code') }}</label>
                                    <input type="text" class="form-control" name="code" id="code">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="gender">{{ _i('Gender') }}</label>
                                    <select name="gender" id="gender" class="form-control">
                                        <option value="">{{ _i('Select...') }}</option>
                                        <option value="*">{{ _i('All genders') }}</option>
                                        <option value="F">{{ _i('Female') }}</option>
                                        <option value="M">{{ _i('Male') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="level">{{ _i('Level') }}</label>
                                    <select name="level" id="level" class="form-control">
                                        <option value="">{{ _i('Select...') }}</option>
                                        <option value="*">{{ _i('All levels') }}</option>
                                        @foreach ($levels as $level)
                                            <option value="{{ $level->id }}">
                                                {{ $level->{$selected_language['iso']} }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="button" class="btn u-btn-3d u-btn-primary" id="search"
                                            data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Searching...') }}">
                                        <i class="hs-admin-search"></i>
                                        {{ _i('Search') }}
                                    </button>
                                    <button type="button" class="btn u-btn-3d u-btn-bluegray" id="clear">
                                        <i class="hs-admin-close"></i>
                                        {{ _i('Clear') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                <header
                    class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                    <div class="media">
                        <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0 title">
                            {{ _i('Search results') }}
                        </h3>
                        <div class="media-body d-flex justify-content-end" id="table-buttons">

                        </div>
                    </div>
                </header>
                <div class="card-block g-pa-15">
                    <div class="table-responsive">
                        <table class="table table-bordered w-100" id="users-table">
                            <thead>
                            <tr>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('ID') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Username') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Email') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{  _i('Name') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{  _i('Last Name') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{  _i('Gender') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{  _i('Status') }}
                                </th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function () {
            let users = new Users();
            users.advancedSearch();
        });
    </script>
@endsection

@extends('back.template')

@section('content')
    <form action="{{ route('segments.users-data') }}" id="segmentation-form" method="post">
        <div class="row">
            <div class="col-md-4">
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-10">
                    <header
                        class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                {{ _i('Country filter') }}
                            </h3>
                        </div>
                    </header>
                    <div class="card-block g-pa-15 g-pb-5">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="country">{{ _i('Country') }}</label>
                                    <select name="country[]" id="country" class="form-control" multiple="multiple">
                                        <option value="">{{ _i('All countries') }}</option>
                                        @foreach ($countries as $country)
                                            <option value="{{ $country->iso }}">
                                                {{ $country->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exclude_country">{{ _i('Exclude country') }}</label>
                                    <select name="exclude_country[]" id="exclude_country" class="form-control" multiple="multiple">
                                        @foreach ($countries as $country)
                                            <option value="{{ $country->iso }}">
                                                {{ $country->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-10">
                    <header
                        class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                {{ _i('Balance filter') }}
                            </h3>
                        </div>
                    </header>
                    <div class="card-block g-pa-15 g-pb-5">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="balance_options">{{ _i('Options') }}</label>
                                    <select name="balance_options" id="balance_options" class="form-control">
                                        <option value="<=">{{ _i('Less than or equal to') }}</option>
                                        <option value=">=">{{ _i('Greater than or equal to') }}</option>
                                        <option value="==">{{ _i('Same to') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <div class="form-group">
                                        <label for="balance">{{ _i('Balance') }}</label>
                                        <input type="number" name="balance" id="balance" class="form-control" min="0">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-10">
                    <header
                        class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                {{ _i('Deposits filter') }}
                            </h3>
                        </div>
                    </header>
                    <div class="card-block g-pa-15 g-pb-5">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="deposits_options">{{ _i('Options') }}</label>
                                    <select name="deposits_options" id="deposits_options" class="form-control">
                                        <option value="<=">{{ _i('Less than or equal to') }}</option>
                                        <option value=">=">{{ _i('Greater than or equal to') }}</option>
                                        <option value="==">{{ _i('Same to') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="deposits">{{ _i('Deposits') }}</label>
                                    <input type="number" class="form-control" name="deposits" id="deposits">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-10">
                    <header
                        class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                {{ _i('Last login filter') }}
                            </h3>
                        </div>
                    </header>
                    <div class="card-block g-pa-15 g-pb-5">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="last_login_options">{{ _i('Options') }}</label>
                                    <select name="last_login_options" id="last_login_options" class="form-control">
                                        <option value="<=">{{ _i('Less than or equal to') }}</option>
                                        <option value=">=">{{ _i('Greater than or equal to') }}</option>
                                        <option value="==">{{ _i('Same to') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <div class="form-group">
                                        <label for="last_login">{{ _i('Last login') }}</label>
                                        <input type="text" name="last_login" id="last_login"
                                               class="form-control datepicker" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-10">
                    <header
                        class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                {{ _i('Last deposit filter') }}
                            </h3>
                        </div>
                    </header>
                    <div class="card-block g-pa-15 g-pb-5">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="last_deposit_options">{{ _i('Options') }}</label>
                                    <select name="last_deposit_options" id="last_deposit_options" class="form-control">
                                        <option value="<=">{{ _i('Less than or equal to') }}</option>
                                        <option value=">=">{{ _i('Greater than or equal to') }}</option>
                                        <option value="==">{{ _i('Same to') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <div class="form-group">
                                        <label for="last_deposit">{{ _i('Last deposit') }}</label>
                                        <input type="text" name="last_deposit" id="last_deposit"
                                               class="form-control datepicker" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-10">
                    <header
                        class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                {{ _i('Last withdrawal filter') }}
                            </h3>
                        </div>
                    </header>
                    <div class="card-block g-pa-15 g-pb-5">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="last_withdrawal_options">{{ _i('Options') }}</label>
                                    <select name="last_withdrawal_options" id="last_withdrawal_options" class="form-control">
                                        <option value="<=">{{ _i('Less than or equal to') }}</option>
                                        <option value=">=">{{ _i('Greater than or equal to') }}</option>
                                        <option value="==">{{ _i('Same to') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <div class="form-group">
                                        <label for="last_withdrawal">{{ _i('Last withdrawal') }}</label>
                                        <input type="text" name="last_withdrawal" id="last_withdrawal" class="form-control datepicker" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-10">
                    <header
                        class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                {{ _i('Registration filter') }}
                            </h3>
                        </div>
                    </header>
                    <div class="card-block g-pa-15 g-pb-5">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="registration_options">{{ _i('Options') }}</label>
                                    <select name="registration_options" id="registration_options" class="form-control">
                                        <option value="<=">{{ _i('Less than or equal to') }}</option>
                                        <option value=">=">{{ _i('Greater than or equal to') }}</option>
                                        <option value="==">{{ _i('Same to') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <div class="form-group">
                                        <label for="registration_date">{{ _i('Date registration') }}</label>
                                        <input type="text" name="registration_date" id="registration_date" class="form-control datepicker" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-10">
                    <header
                        class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                {{ _i('Sales filter') }}
                            </h3>
                        </div>
                    </header>
                    <div class="card-block g-pa-15 g-pb-5">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="played_options">{{ _i('Options') }}</label>
                                    <select name="played_options" id="played_options" class="form-control">
                                        <option value="<=">{{ _i('Less than or equal to') }}</option>
                                        <option value=">=">{{ _i('Greater than or equal to') }}</option>
                                        <option value="==">{{ _i('Same to') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <div class="form-group">
                                        <label for="played">{{ _i('Played') }}</label>
                                        <input type="text" name="played" id="played" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-10">
                    <header
                        class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                {{ _i('Complete profile filter') }}
                            </h3>
                        </div>
                    </header>
                    <div class="card-block g-pa-15 g-pb-5">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="registration_options">{{ _i('Full profile') }}</label>
                                    <select name="full_profile" id="full_profile" class="form-control">
                                        <option value="">{{ _i('Select') }}</option>
                                        <option value="1">{{ _i('Completed') }}</option>
                                        <option value="0">{{ _i('Incomplete') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-10">
                    <header
                        class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                {{ _i('Other filters') }}
                            </h3>
                        </div>
                    </header>
                    <div class="card-block g-pa-15 g-pb-5">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="currency">{{ _i('Currency') }}</label>
                                    <select name="currency[]" id="currency" class="form-control" multiple="multiple">
                                        <option value="">{{ _i('All currencies') }}</option>
                                        @foreach ($currency_client as $currency)
                                            <option value="{{ $currency }}">
                                                {{ $currency == 'VEF' ? $free_currency->currency_name : $currency}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="status">{{ _i('Player status') }}</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="1">{{ _i('Active') }}</option>
                                        <option value="0">{{ _i('Blocked') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="language">{{ _i('Language') }}</label>
                                    <select name="language[]" id="language" class="form-control" multiple="multiple">
                                        <option value="">{{ _i('All languages') }}</option>
                                        @foreach ($languages as $language)
                                            <option value="{{ $language['iso'] }}">
                                                {{ $language['name'] }}
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
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="row">
        <div class="col-md-12">
            <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                <header
                    class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                    <div class="media">
                        <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0 title">
                            {{ _i('Search results') }}
                        </h3>
                        <div class="media-body d-flex justify-content-end" id="table-buttons">

                        </div>
                        <div class="justify-content-end g-ml-10 d-none g-pl-10" id="segments">
                            <a href="#store-segments-modal" class="btn u-btn-3d u-btn-primary" data-toggle="modal">
                                <i class="hs-admin-save"></i>
                                {{ _i('Save segment') }}
                            </a>
                        </div>
                        <div class="justify-content-end g-ml-10 g-pl-10" id="create-segment">
                            <a href="#store-segments-modal" class="btn u-btn-3d u-btn-primary" data-toggle="modal">
                                <i class="hs-admin-save"></i>
                                {{ _i('Create empty segment') }}
                            </a>
                        </div>
                    </div>
                </header>
                <div class="card-block g-pa-15">
                    <div class="table-responsive">
                        <table class="table table-bordered w-100" id="segmentation-table">
                            <thead>
                            <tr>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('ID') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Username') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{  _i('Full Name') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Email') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Phone') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{  _i('Country') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{  _i('Currency') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{  _i('Last deposit') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{  _i('Last withdrawal') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{  _i('Last login') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{  _i('Profile') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{  _i('Registered') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{  _i('Language') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Deposits') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Played') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{  _i('Balance') }}
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
    @include('back.crm.segments.modals.store')
@endsection

@section('scripts')
    <script>
        $(function () {
            let segments = new Segments();
            segments.usersData();
            segments.update();
        });
    </script>
@endsection

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
                                            <option value="{{ $country->iso }}" @isset($segment->filter->country)
                                                {{ in_array($country->iso, $segment->filter->country) ? 'selected': ''}}
                                                @endisset>
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
                                            <option value="{{ $country->iso }}" @isset($segment->filter->exclude_country)
                                                {{ in_array($country->iso, $segment->filter->exclude_country) ? 'selected': ''}}
                                                @endisset>
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
                                        <option value="<=" @isset($segment->filter->balance_options)
                                            {{ $segment->filter->balance_options == '<=' ? 'selected': ''}}
                                            @endisset>
                                            {{ _i('Less than or equal to') }}
                                        </option>
                                        <option value=">=" @isset($segment->filter->balance_options)
                                            {{ $segment->filter->balance_options == '>=' ? 'selected': ''}}
                                            @endisset>
                                            {{ _i('Greater than or equal to') }}
                                        </option>
                                        <option value="==" @isset($segment->filter->balance_options)
                                            {{ $segment->filter->balance_options == '==' ? 'selected': ''}}
                                            @endisset>
                                            {{ _i('Same to') }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <div class="form-group">
                                        <label for="balance">{{ _i('Balance') }}</label>
                                        <input type="number" name="balance" id="balance" class="form-control" min="0" value="@isset($segment->filter->balance){{ $segment->filter->balance }}@endisset">
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
                                        <option value="<=" @isset($segment->filter->deposits_options)
                                            {{ $segment->filter->deposits_options == '<=' ? 'selected': ''}}
                                            @endisset>
                                            {{ _i('Less than or equal to') }}
                                        </option>
                                        <option value=">=" @isset($segment->filter->deposits_options)
                                            {{ $segment->filter->deposits_options == '>=' ? 'selected': ''}}
                                            @endisset>
                                            {{ _i('Greater than or equal to') }}
                                        </option>
                                        <option value="==" @isset($segment->filter->deposits_options)
                                            {{ $segment->filter->deposits_options == '==' ? 'selected': ''}}
                                            @endisset>
                                            {{ _i('Same to') }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="deposits">{{ _i('Deposits') }}</label>
                                    <input type="number" class="form-control" name="deposits" id="deposits" value="@isset($segment->filter->deposits){{ $segment->filter->deposits }}@endisset">
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
                                        <option value="<=" @isset($segment->filter->last_login_options)
                                            {{ $segment->filter->last_login_options == '<=' ? 'selected': ''}}
                                            @endisset>
                                            {{ _i('Less than or equal to') }}
                                        </option>
                                        <option value=">=" @isset($segment->filter->last_login_options)
                                            {{ $segment->filter->last_login_options == '>=' ? 'selected': ''}}
                                            @endisset>
                                            {{ _i('Greater than or equal to') }}
                                        </option>
                                        <option value="==" @isset($segment->filter->last_login_options)
                                            {{ $segment->filter->last_login_options == '==' ? 'selected': ''}}
                                            @endisset>
                                            {{ _i('Same to') }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <div class="form-group">
                                        <label for="last_login">{{ _i('Last login') }}</label>
                                        <input type="text" name="last_login" id="last_login"
                                               class="form-control datepicker" autocomplete="off" value="@isset($segment->last_login){{ $segment->last_login }}@endisset">
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
                                        <option value="<=" @isset($segment->filter->last_deposit_options)
                                            {{ $segment->filter->last_deposit_options == '<=' ? 'selected': ''}}
                                            @endisset>
                                            {{ _i('Less than or equal to') }}
                                        </option>
                                        <option value=">=" @isset($segment->filter->last_deposit_options)
                                            {{ $segment->filter->last_deposit_options == '>=' ? 'selected': ''}}
                                            @endisset>
                                            {{ _i('Greater than or equal to') }}
                                        </option>
                                        <option value="==" @isset($segment->filter->last_deposit_options)
                                            {{ $segment->filter->last_deposit_options == '==' ? 'selected': ''}}
                                            @endisset>
                                            {{ _i('Same to') }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <div class="form-group">
                                        <label for="last_deposit">{{ _i('Last deposit') }}</label>
                                        <input type="text" name="last_deposit" id="last_deposit"
                                               class="form-control datepicker" autocomplete="off" value="@isset($segment->last_deposit){{ $segment->last_deposit }}@endisset">
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
                                        <option value="<=" @isset($segment->filter->last_withdrawal_options)
                                            {{ $segment->filter->last_withdrawal_options == '<=' ? 'selected': ''}}
                                            @endisset>
                                            {{ _i('Less than or equal to') }}
                                        </option>
                                        <option value=">=" @isset($segment->filter->last_withdrawal_options)
                                            {{ $segment->filter->last_withdrawal_options == '>=' ? 'selected': ''}}
                                            @endisset>
                                            {{ _i('Greater than or equal to') }}
                                        </option>
                                        <option value="==" @isset($segment->filter->last_withdrawal_options)
                                            {{ $segment->filter->last_withdrawal_options == '==' ? 'selected': ''}}
                                            @endisset>
                                            {{ _i('Same to') }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <div class="form-group">
                                        <label for="last_withdrawal">{{ _i('Last withdrawal') }}</label>
                                        <input type="text" name="last_withdrawal" id="last_withdrawal"
                                               class="form-control datepicker" autocomplete="off" value="@isset($segment->last_deposit){{ $segment->last_deposit }}@endisset">
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
                                        <option value="<=" @isset($segment->filter->registration_options)
                                            {{ $segment->filter->registration_options == '<=' ? 'selected': ''}}
                                            @endisset>
                                            {{ _i('Less than or equal to') }}
                                        </option>
                                        <option value=">=" @isset($segment->filter->registration_options)
                                            {{ $segment->filter->registration_options == '>=' ? 'selected': ''}}
                                            @endisset>
                                            {{ _i('Greater than or equal to') }}
                                        </option>
                                        <option value="==" @isset($segment->filter->registration_options)
                                            {{ $segment->filter->registration_options == '==' ? 'selected': ''}}
                                            @endisset>
                                            {{ _i('Same to') }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <div class="form-group">
                                        <label for="registration_date">{{ _i('Date registration') }}</label>
                                        <input type="text" name="registration_date" id="registration_date"
                                               class="form-control datepicker" autocomplete="off" value="@isset($segment->registration_date){{ $segment->registration_date }}@endisset">
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
                                        <option value="<=" @isset($segment->filter->played_options)
                                            {{ $segment->filter->played_options == '<=' ? 'selected': ''}}
                                            @endisset>
                                            {{ _i('Less than or equal to') }}
                                        </option>
                                        <option value=">=" @isset($segment->filter->played_options)
                                            {{ $segment->filter->played_options == '>=' ? 'selected': ''}}
                                            @endisset>
                                            {{ _i('Greater than or equal to') }}
                                        </option>
                                        <option value="==" @isset($segment->filter->played_options)
                                            {{ $segment->filter->played_options == '==' ? 'selected': ''}}
                                            @endisset>
                                            {{ _i('Same to') }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <div class="form-group">
                                        <label for="played">{{ _i('Played') }}</label>
                                        <input type="text" name="played" id="played" class="form-control" value="@isset($segment->filter->played){{ $segment->filter->played }}@endisset">
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
                                    <label for="full_profile">{{ _i('Full profile') }}</label>
                                    <select name="full_profile" id="full_profile" class="form-control">
                                        <option value="1" @isset($segment->filter->full_profile)
                                            {{ $segment->filter->full_profile == '1' ? 'selected': ''}}
                                            @endisset>
                                            {{ _i('Completed') }}
                                        </option>
                                        <option value="0" @isset($segment->filter->full_profile)
                                            {{ $segment->filter->full_profile == '0' ? 'selected': ''}}
                                            @endisset>
                                            {{ _i('Incomplete') }}
                                        </option>
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
                                            <option value="{{ $currency }}" @isset($segment->filter->currency)
                                                {{ in_array($currency, $segment->filter->currency) ? 'selected': ''}}
                                                @endisset>
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
                                        <option value="true" {{ $segment->filter->status == '1' ? 'selected': ''}}>
                                            {{ _i('Active') }}
                                        </option>
                                        <option value="false" {{ $segment->filter->status == '0' ? 'selected': ''}}>
                                            {{ _i('Blocked') }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="language">{{ _i('Language') }}</label>
                                    <select name="language[]" id="language" class="form-control" multiple="multiple">
                                        <option value="">{{ _i('All languages') }}</option>
                                        @foreach ($languages as $language)
                                            <option value="{{ $language['iso'] }}" @isset($segment->filter->language)
                                                {{ in_array($language['iso'], $segment->filter->language) ? 'selected': ''}}
                                                @endisset>
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
                        <div class="justify-content-end g-ml-10 g-pl-10">
                            <input type="hidden" name="id" value="{{ $segment->id }}">
                            <input type="hidden" name="filter" id="filter" value="{{ json_encode($segment->filter) }}">
                            <button id="update" class="btn u-btn-3d u-btn-primary" data-loading-text="{{ _i('Please wait...') }}">
                                <i class="hs-admin-reload"></i>
                                {{ _i('Update segment') }}
                            </button>
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
            segments.update();
        });
    </script>
@endsection

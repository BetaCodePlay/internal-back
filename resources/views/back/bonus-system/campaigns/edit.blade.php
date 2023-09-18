@extends('back.template')

@section('content')
    <form action="{{ route('bonus-system.campaigns.update') }}" id="campaigns-form" method="post" enctype="multipart/form-data"
        novalidate>
        <div class="row">
            <div class="col-md-9">
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-10">
                    <header
                        class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3
                                class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                {{ _i('Campaign details') }}
                            </h3>
                            <div class="media-body d-flex justify-content-end">
                                <a href="{{ route('bonus-system.campaigns.index') }}"
                                    class="btn u-btn-3d u-btn-primary float-right">
                                    <i class="hs-admin-layout-list-thumb"></i>
                                    {{ _i('Go to list') }}
                                </a>
                            </div>
                        </div>
                    </header>
                    <div class="card-block g-pa-15">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="internal_name">{{ _i('Internal name') }}</label>
                                    <input type="text" name="internal_name" id="internal_name"
                                        placeholder="Ej. Bono por deposito" class="form-control"
                                        value="{{ $campaign->name }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="start_date">{{ _i('Start date') }}</label>
                                    <input type="text" name="start_date" id="start_date"
                                        class="form-control datetimepicker" autocomplete="off"
                                        value="{{ $campaign->start }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="end_date">{{ _i('Finish date') }}</label>
                                    <input type="text" name="end_date" id="end_date" class="form-control datetimepicker"
                                        autocomplete="off" value="{{ $campaign->end }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="currencies">{{ _i('Currencies') }}</label>
                                    <select name="currency" id="currency" class="form-control input_placeholder"
                                        data-route="{{ route('bonus-system.campaigns.provider-types') }}"
                                        data-payments-route="{{ route('bonus-system.campaigns.payment-methods') }}">
                                        @foreach ($whitelabel_currencies as $currency)
                                            <option value="{{ $currency->iso }}"
                                                {{ $currency->iso == $campaign->currency_iso ? 'selected' : '' }}>
                                                {{ $currency->iso == 'VEF' ? $free_currency->currency_name : $currency->iso . " ({$currency->name})" }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="status">{{ _i('Status') }}</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="true" {{ $campaign->status ? 'selected' : '' }}>
                                            {{ _i('Active') }}
                                        </option>
                                        <option value="false" {{ !$campaign->status ? 'selected' : '' }}>
                                            {{ _i('Inactive') }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="bonus_type">{{ _i('Bonus type') }}</label>
                                    <select name="bonus_type" id="bonus_type" class="form-control">
                                        <option value="1">{{ _i('Instant') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-10 promo_codes">
                    <header
                        class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3
                                class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                {{ _i('Promo code') }}
                            </h3>
                        </div>
                    </header>
                    <div class="card-block g-pa-15">
                        <div class="repeater">
                            <div data-repeater-list="promo_codes">
                                <div data-repeater-item>
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label for="promo_code">{{ _i('Promo code') }}</label>
                                                <input type="text" name="promo_code" id="promo_code"
                                                    class="form-control text-uppercase">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label for="btag">{{ _i('BTag') }}</label>
                                                <input type="text" name="btag" id="btag" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-2" style="margin-top: 25px">
                                            <div class="form-group">
                                                <label for=""></label>
                                                <button data-repeater-delete class="btn u-btn-3d u-btn-primary"
                                                    type="button">
                                                    <i class="hs-admin-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <button data-repeater-create class="btn u-btn-3d u-btn-primary" type="button">
                                    <i class="hs-admin-plus"></i>
                                    {{ _i('Add') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-10">
                    <header
                        class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3
                                class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                {{ _i('Allocation criteria') }}
                            </h3>
                        </div>
                    </header>
                    <div class="card-block g-pt-15 g-pb-0">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="bonus">{{ _i('Bonus') }}</label>
                                    <select name="allocation_criteria[]" id="allocation_criteria" class="form-control"
                                        data-route="{{ route('bonus-system.campaigns.allocation-criteria-all') }}">
                                        <option value="">{{ _i('Select...') }}</option>
                                        @foreach ($criterias as $criteria)
                                            <option value="{{ $criteria->id }}"
                                                {{ $campaign->data->allocation_criteria[0] ? 'selected' : '' }}>
                                                {{ _i($criteria->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">{{ _i('Commission real balance(%)') }}
                                        <i class="fa fa-info-circle" aria-hidden="true" data-container="body"
                                            data-toggle="popover" data-placement="top" data-trigger="hover"
                                            data-content="{{ _i('Assign the percentage of your actual balance that you want to spend along with the bonus') }}">
                                        </i>
                                    </label>
                                    <input type="text" name="commission_real" id="commission_real"
                                        onkeyup="BonusSystem.updateCommissionBonus()" class="form-control"
                                        value="{{ $campaign->data->commission_real }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">{{ _i('Commission bonus balance(%)') }}
                                        <i class="fa fa-info-circle" aria-hidden="true" data-container="body"
                                            data-toggle="popover" data-placement="top" data-trigger="hover"
                                            data-content="{{ _i('Assign the percentage of bonus balance you want to spend along with the actual balance') }}">
                                        </i>
                                    </label>
                                    <input type="text" name="commission_bonus_v" id="commission_bonus_v"
                                        class="form-control" value="{{ $campaign->data->commission_bonus }}" disabled>
                                    <input type="hidden" name="commission_bonus" id="commission_bonus"
                                        class="form-control" value="{{ $campaign->data->commission_bonus }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-10">
                    <header
                        class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3
                                class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                {{ _i('Bonus data') }}
                            </h3>
                        </div>
                    </header>
                    <div class="card-block g-pt-15 g-pb-0">
                        <table class="table align-middle g-mb-0">
                            <tr>
                                <td width="20%">
                                    <div class="form-group g-mb-10">
                                        <label class="u-check g-pl-25">
                                            <input class="g-hidden-xs-up g-pos-abs g-top-0 g-left-0"
                                                name="bonus_type_awarded" id="fixed-bonus" type="radio"
                                                value="{{ \Dotworkers\Bonus\Enums\BonusTypeAwarded::$fixed }}"
                                                {{ $campaign->bonus_type_id == \Dotworkers\Bonus\Enums\BonusTypeAwarded::$fixed ? 'checked' : '' }}>
                                            <div class="u-check-icon-font g-absolute-centered--y g-left-0">
                                                <i class="fa" data-check-icon="" data-uncheck-icon=""></i>
                                            </div>
                                            {{ _i('Fixed bonus') }}
                                        </label>
                                    </div>
                                    <div class="form-group g-mb-10">
                                        <label
                                            class="u-check g-pl-25 {{ !in_array(\Dotworkers\Bonus\Enums\AllocationCriteria::$deposit, $campaign->data->allocation_criteria) ? 'disabled' : '' }}">
                                            <input class="g-hidden-xs-up g-pos-abs g-top-0 g-left-0"
                                                name="bonus_type_awarded" id="deposit-percentage" type="radio"
                                                value="{{ \Dotworkers\Bonus\Enums\BonusTypeAwarded::$percentage }}"
                                                {{ $campaign->data->bonus_type_awarded == \Dotworkers\Bonus\Enums\BonusTypeAwarded::$percentage ? 'checked' : '' }}>
                                            <div class="u-check-icon-font g-absolute-centered--y g-left-0">
                                                <i class="fa" data-check-icon="" data-uncheck-icon=""></i>
                                            </div>
                                            {{ _i('Deposit percentage') }}
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <div class="row">
                                        <div
                                            class="col-md-4 fixed-bonus {{ $campaign->data->bonus_type_awarded != \Dotworkers\Bonus\Enums\BonusTypeAwarded::$fixed ? 'd-none' : '' }}">
                                            <div class="form-group">
                                                <label for="bonus">{{ _i('Bonus to be awarded') }}</label>
                                                <input type="number" min="0" name="bonus" class="form-control"
                                                    value="{{ $campaign->data->bonus ?? '' }}">
                                            </div>
                                        </div>
                                        <div
                                            class="col-md-4 deposit-percentage {{ $campaign->data->bonus_type_awarded != \Dotworkers\Bonus\Enums\BonusTypeAwarded::$percentage ? 'd-none' : '' }}">
                                            <div class="form-group">
                                                <label for="percentage">{{ _i('Percentage') }}</label>
                                                <input type="number" min="0" name="percentage"
                                                    class="form-control"
                                                    value="{{ isset($campaign->data->percentage) ? $campaign->data->percentage * 100 : '' }}">
                                            </div>
                                        </div>
                                        <div
                                            class="col-md-4 deposit-percentage {{ $campaign->data->bonus_type_awarded != \Dotworkers\Bonus\Enums\BonusTypeAwarded::$percentage ? 'd-none' : '' }}">
                                            <div class="form-group">
                                                <label for="limit">{{ _i('Limit to be awarded') }}</label>
                                                <input type="number" min="0" name="limit" class="form-control"
                                                    value="{{ $campaign->data->limit ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label
                                                    for="max_balance_convert">{{ _i('Maximum to convert into real balance') }}</label>
                                                <input type="number" min="0" name="max_balance_convert"
                                                    class="form-control" placeholder="{{ _i('Optional') }}"
                                                    value="{{ $campaign->data->max_balance_convert }}">
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-10">
                    <header
                        class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3
                                class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                {{ _i('Rollovers data') }}
                            </h3>
                        </div>
                    </header>
                    <div class="card-block g-pa-15">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="complete_rollovers">{{ _i('Complete rollovers') }}</label>
                                    <select name="complete_rollovers" id="complete_rollovers" class="form-control">
                                        {{-- <option value="yes" {{ $campaign->data->rollovers ? 'selected' : '' }}>
                                            {{ _i('Yes') }}
                                        </option> --}}
                                        <option value="no" {{ !$campaign->data->rollovers ? 'selected' : '' }}>
                                            {{ _i('No') }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            {{-- <div class="col-md-4 rollovers-data {{ is_null($rollovers) ? 'd-none' : '' }}">
                                <div class="form-group">
                                    <label for="include_deposit">{{ _i('Rollover type') }}</label>
                                    <select name="include_deposit" id="include_deposit" class="form-control">
                                        <option value="deposit" {{ !is_null($rollovers) && $rollovers->include_deposit ? 'selected' : '' }}>
                                            {{ _i('Deposit') }}
                                        </option>
                                        <option value="bonus" {{ !is_null($rollovers) && !$rollovers->include_deposit ? 'selected' : '' }}>
                                            {{ _i('Bonus') }}
                                        </option>
                                        <option value="both" {{ !is_null($rollovers) && is_null($rollovers->include_deposit) ? 'selected' : '' }}>
                                            {{ _i('Bonus + deposit') }}
                                        </option>
                                    </select>
                                </div>
                            </div> --}}
                            {{-- <div class="col-md-4 rollovers-data {{ is_null($rollovers) ? 'd-none' : '' }}">
                            <div class="form-group">
                                <label for="provider_type">{{ _i('Provider type') }}</label>
                                <select name="provider_type" id="provider_type" data-route="{{ route('bonus-system.campaigns.exclude-providers') }}" class="form-control">
                                    <option value="">{{ _i('Select...') }}</option>
                                    @foreach ($provider_types as $providerType)
                                        <option value="{{ $providerType->id }}" {{ !is_null($rollovers) && $rollovers->provider_type_id == $providerType->id ? 'selected' : '' }}>
                                            {{ $providerType->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div> --}}
                        </div>
                        {{-- <div class="col-md-4 rollovers-data {{ is_null($rollovers) ? 'd-none' : '' }}">
                            <div class="form-group">
                                <label for="exclude_providers">{{ _i('Exclude providers') }}</label>
                                <select name="exclude_providers[]" id="exclude_providers" class="form-control" multiple>
                                    <option value="">{{ _i('Select...') }}</option>
                                </select>
                            </div>
                        </div> --}}
                        {{-- <div class="col-md-4 rollovers-data {{ is_null($rollovers) ? 'd-none' : '' }}">
                            <div class="form-group">
                                <label for="multiplier">{{ _i('Rollover multiplier') }}</label>
                                <input type="number" name="multiplier" id="multiplier" class="form-control" value="{{ !is_null($rollovers) ? $rollovers->multiplier : '' }}">
                            </div>
                        </div> --}}
                        {{-- <div class="col-md-4 rollovers-data {{ is_null($rollovers) ? 'd-none' : '' }}">
                            <div class="form-group">
                                <label for="days">{{ _i('Days to complete rollover') }}</label>
                                <input type="text" name="days" id="days" class="form-control" value="{{ !is_null($rollovers) ? $rollovers->days : '' }}">
                            </div>
                        </div> --}}
                        {{-- <div class="col-md-4 sports {{ !is_null($rollovers) ? $rollovers->provider_type_id != \Dotworkers\Configurations\Enums\ProviderTypes::$sportbook ? 'd-none' : '' : 'd-none' }}">
                            <div class="form-group">
                                <label for="bet_type">{{ _i('Bet type') }}</label>
                                <select name="bet_type" id="bet_type" class="form-control">
                                    <option value="both" {{ isset($campaign->data->simple) && $campaign->data->simple == null ? 'selected' : '' }}>
                                        {{ _i('Simple and combined') }}
                                    </option>
                                    <option value="simple" {{ isset($campaign->data->simple) && $campaign->data->simple ? 'selected' : '' }}>
                                        {{ _i('Simple') }}
                                    </option>
                                    <option value="combined" {{ isset($campaign->data->simple) && !$campaign->data->simple ? 'selected' : '' }}>
                                        {{ _i('Combined') }}
                                    </option>
                                </select>
                            </div>
                        </div> --}}
                        {{-- <div class="col-md-4 sports {{ !is_null($rollovers) ? $rollovers->provider_type_id != \Dotworkers\Configurations\Enums\ProviderTypes::$sportbook ? 'd-none' : '' : 'd-none' }}">
                            <div class="form-group">
                                <label for="odd">{{ _i('Quota') }}</label>
                                <input type="text" name="odd" id="odd" class="form-control" value="{{ $campaign->data->odd ?? '' }}">
                            </div>
                        </div> --}}
                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="hidden" name="id" value="{{ $campaign->id }}">
                                {{-- <input type="hidden" name="rollover_id" value="{{ !is_null($rollovers) ? $rollovers->id : '' }}"> --}}
                                <input type="hidden" name="parent_campaign" value="{{ $campaign->parent_campaign }}">
                                {{-- <input type="hidden" name="version" id="version" value="{{ $campaign->version }}"> --}}
                                {{-- <input type="hidden" name="original_campaign" id="original_campaign" value="{{ $campaign->original_campaign }}"> --}}
                                <button type="submit" class="btn u-btn-3d u-btn-primary" id="update"
                                    data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Updating...') }}">
                                    <i class="hs-admin-upload"></i>
                                    {{ _i('Update campaign') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                {{-- <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-10 {{ !is_null($campaign->original_campaign) ? '' : 'd-none' }}">
                    <header
                        class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                {{ _i('Versions') }}
                            </h3>
                        </div>
                    </header>
                    <div class="card-block g-pa-15">
                        <label for="versions">{{ _i('Versions') }}</label>
                        <select name="versions" id="versions" data-route="{{ route('bonus-system.campaigns.edit', [$campaign->id]) }}" class="form-control">
                            <option value="">{{ _i('Select...') }}</option>
                            @foreach ($versions as $version)
                                <option value="{{ $version->id_campaign }}">
                                    {{ $version->version }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div> --}}
                {{-- <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-10">
                    <header
                        class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                {{ _i('Users restriction') }}
                            </h3>
                        </div>
                    </header>
                    <div class="card-block g-pa-15">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="users_restriction_type">{{ _i('Users restriction type') }}</label>
                                <select name="users_restriction_type" id="users_restriction_type" class="form-control">
                                    <option value="" {{ !isset($campaign->data->users_restriction_type) ? 'selected' : '' }}>
                                        {{ _i('None') }}
                                    </option>
                                    <option value="users" {{ isset($campaign->data->users_restriction_type) && $campaign->data->users_restriction_type == 'users' ? 'selected' : '' }}>
                                        {{ _i('Users') }}
                                    </option>
                                    <option value="segments" {{ isset($campaign->data->users_restriction_type) && $campaign->data->users_restriction_type == 'segments' ? 'selected' : '' }}>
                                        {{ _i('Segments') }}
                                    </option>
                                    <option value="excel" {{ isset($campaign->data->users_restriction_type) && $campaign->data->users_restriction_type == 'excel' ? 'selected' : '' }}>
                                        {{ _i('Excel') }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="search-users {{ isset($campaign->data->users_restriction_type) && $campaign->data->users_restriction_type == 'users' ? '' : 'd-none' }}">
                                <div class="form-group">
                                    <label for="include_users">{{ _i('Include users') }}</label>
                                    <select name="include_users[]" id="include_users" class="form-control select2" data-route="{{ route('users.search-username') }}" multiple>
                                        <option></option>
                                    </select>
                                </div>
                            </div>
                            <div class="search-users {{ isset($campaign->data->users_restriction_type) && $campaign->data->users_restriction_type == 'users' ? '' : 'd-none' }}">
                                <div class="form-group">
                                    <label for="exclude_users">{{ _i('Exclude users') }}</label>
                                    <select name="exclude_users[]" id="exclude_users" class="form-control select2" data-route="{{ route('users.search-username') }}" multiple>
                                        <option></option>
                                    </select>
                                </div>
                            </div>
                            <div class="search-segments {{ isset($campaign->data->users_restriction_type) && $campaign->data->users_restriction_type == 'segments' ? '' : 'd-none' }}">
                                <div class="form-group">
                                    <label for="include_segments">{{ _i('Include segments') }}</label>
                                    <select name="include_segments[]" id="include_segments" class="form-control" multiple>
                                        <option value="">{{ _i('Select...') }}</option>
                                        @foreach ($segments as $segment)
                                            <option value="{{ $segment->id }}">
                                                {{ $segment->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="search-segments {{ isset($campaign->data->users_restriction_type) && $campaign->data->users_restriction_type == 'segments' ? '' : 'd-none' }}">
                                <div class="form-group">
                                    <label for="exclude_segments">{{ _i('Exclude segments') }}</label>
                                    <select name="exclude_segments[]" id="exclude_segments" class="form-control" multiple>
                                        <option value="">{{ _i('Select...') }}</option>
                                        @foreach ($segments as $segment)
                                            <option value="{{ $segment->id }}">
                                                {{ $segment->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="search-excel {{ isset($campaign->data->users_restriction_type) && $campaign->data->users_restriction_type == 'excel' ? '' : 'd-none' }}">
                                <div class="form-group">
                                    <label for="include_excel">{{ _i('Include excel') }}</label>
                                    <input type="file" name="include_excel" id="include_excel" class="opacity-0">
                                </div>
                            </div>
                            <div class="search-excel  {{ isset($campaign->data->users_restriction_type) && $campaign->data->users_restriction_type == 'excel' ? '' : 'd-none' }}">
                                <div class="form-group">
                                    <label for="exclude_excel">{{ _i('Exclude excel') }}</label>
                                    <input type="file" name="exclude_excel" id="exclude_excel" class="opacity-0">
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-10">
                    <header
                        class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3
                                class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                {{ _i('Translations') }}
                            </h3>
                        </div>
                    </header>
                    <div class="card-block g-pa-15">
                        <table class="table table-bordered w-100">
                            @foreach ($languages as $language)
                                <tr>
                                    <td>{{ $language['name'] }}</td>
                                    <td class="text-right">
                                        <a href="#add-translations-modal" data-toggle="modal"
                                            class="btn u-btn-3d u-btn-primary btn-sm add-translation"
                                            data-language="{{ $language['name'] }}"
                                            data-language-iso="{{ $language['iso'] }}">
                                            <i class="hs-admin-plus"></i>
                                            {{ _i('Add') }}
                                        </a>
                                        <a href="#add-translations-modal" data-toggle="modal"
                                            class="btn u-btn-3d u-btn-primary btn-sm edit-translation d-none"
                                            data-language="{{ $language['name'] }}"
                                            data-language-iso="{{ $language['iso'] }}">
                                            <i class="hs-admin-pencil"></i>
                                            {{ _i('Edit') }}
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>

        </div>
    </form>
    @include('back.bonus-system.campaigns.modals.add-translations-modal')
@endsection

@section('scripts')
    <script>
        $(function() {
            let bonusSystem = new BonusSystem();
            bonusSystem.addTranslations(@json($languages));
            bonusSystem.setTranslations(@json($languages), @json($campaign->translations));
            // bonusSystem.versions();

            // @if (isset($campaign->include_users))
            //     bonusSystem.fillSelects('#include_users', @json($campaign->include_users))
            // @endif

            // @if (isset($campaign->exclude_users))
            //     bonusSystem.fillSelects('#exclude_users', @json($campaign->exclude_users))
            // @endif

            // @if (isset($campaign->include_segments))
            //     bonusSystem.fillSelects('#include_segments', @json($campaign->include_segments))
            // @endif

            // @if (isset($campaign->exclude_segments))
            //     bonusSystem.fillSelects('#exclude_segments', @json($campaign->exclude_segments))
            // @endif

            // @if (isset($campaign->include_payment_methods))
            //     bonusSystem.fillSelects('#include_payment_methods', @json($campaign->include_payment_methods))
            // @endif

            // @if (isset($campaign->exclude_payment_methods))
            //     bonusSystem.fillSelects('#exclude_payment_methods', @json($campaign->exclude_payment_methods))
            // @endif

            // @if (isset($campaign->exclude_provider_bet))
            //     bonusSystem.fillSelects('#exclude_providers_bet', @json($campaign->exclude_provider_bet))
            // @endif

            // bonusSystem.update(@json($languages), @json($campaign->promo_codes));

        });
    </script>
@endsection

@extends('back.template')

@section('content')
    <form action="{{ route('bonus-system.campaigns.store') }}" id="campaigns-form" method="post" enctype="multipart/form-data" novalidate>
        <div class="row">
            <div class="col-md-9">
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-10">
                    <header
                        class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                {{ _i('Campaign details') }}
                            </h3>
                            <div class="media-body d-flex justify-content-end">
                                <a href="{{ route('bonus-system.campaigns.index') }}" class="btn u-btn-3d u-btn-primary float-right">
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
                                    <input type="text" name="internal_name" id="internal_name" placeholder="Ej. Bono por deposito" class="form-control input_placeholder">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="start_date">{{ _i('Start date') }}</label>
                                    <input type="text" name="start_date" id="start_date" class="form-control datetimepicker input_placeholder" placeholder="Ej. 07-07-2023 01:15 pm" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="end_date">{{ _i('Finish date') }}</label>
                                    <input type="text" name="end_date" id="end_date" class="form-control datetimepicker input_placeholder" autocomplete="off" placeholder="{{ _i('Optional') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="currencies">{{ _i('Currencies') }}</label>
                                    <select name="currencies[]" id="currencies" class="form-control input_placeholder" data-route="{{ route('bonus-system.campaigns.provider-types') }}" data-payments-route="{{ route('bonus-system.campaigns.payment-methods') }}">
                                        <option value="" disabled selected >{{ _i('Select...') }}</option>
                                        @foreach ($whitelabel_currencies as $currency)
                                            <option value="{{ $currency->iso }}">
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
                                        <option value="true">{{ _i('Active') }}</option>
                                        <option value="false">{{ _i('Inactive') }}</option>
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
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
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
                                                <input type="text" name="promo_code" id="promo_code" class="form-control text-uppercase input_placeholder" placeholder="Ej. 12345">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label for="btag">{{ _i('BTag') }}</label>
                                                <input type="text" name="btag" id="btag" class="form-control input_placeholder" placeholder="Ej. Bono del mes">
                                            </div>
                                        </div>
                                        <div class="col-md-2" style="margin-top: 25px">
                                            <div class="form-group">
                                                <label for=""></label>
                                                <button data-repeater-delete class="btn u-btn-3d u-btn-primary" type="button">
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
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                {{ _i('Allocation criteria') }}
                            </h3>
                        </div>
                    </header>
                    <div class="card-block g-pt-15 g-pb-0">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="bonus">{{ _i('Bonus') }}</label>
                                    <select name="allocation_criteria[]" id="allocation_criteria" class="form-control" data-route="{{ route('bonus-system.campaigns.allocation-criteria-all') }}">
                                        <option value="">{{ _i('Select...') }}</option>
                                        @foreach ($criterias as $criteria)
                                            <option value="{{ $criteria->id }}">
                                                {{ _i($criteria->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">{{ _i('Commission real balance(%)') }}
                                        <i class="fa fa-info-circle" aria-hidden="true" data-container="body" data-toggle="popover" data-placement="top" data-trigger="hover" data-content="{{ _i('Assign the percentage of your actual balance that you want to spend along with the bonus') }}">
                                        </i>
                                    </label>
                                    <input type="text" name="commission_real" id="commission_real" onkeyup="BonusSystem.updateCommissionBonus()" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">{{ _i('Commission bonus balance(%)') }}
                                        <i class="fa fa-info-circle" aria-hidden="true" data-container="body" data-toggle="popover" data-placement="top" data-trigger="hover" data-content="{{ _i('Assign the percentage of bonus balance you want to spend along with the actual balance') }}">
                                        </i>
                                    </label>
                                    <input type="text" name="commission_bonus_v" id="commission_bonus_v" class="form-control" disabled>
                                    <input type="hidden" name="commission_bonus" id="commission_bonus" class="form-control" >
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-10">
                    <header
                        class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
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
                                            <input class="g-hidden-xs-up g-pos-abs g-top-0 g-left-0" name="bonus_type_awarded" id="fixed-bonus" type="radio" value="{{ \Dotworkers\Bonus\Enums\BonusTypeAwarded::$fixed }}">
                                            <div class="u-check-icon-font g-absolute-centered--y g-left-0">
                                                <i class="fa" data-check-icon="" data-uncheck-icon=""></i>
                                            </div>
                                            {{ _i('Fixed bonus') }}
                                        </label>
                                    </div>
                                    <div class="form-group g-mb-10">
                                        <label class="u-check g-pl-25 ">
                                            <input class="g-hidden-xs-up g-pos-abs g-top-0 g-left-0" name="bonus_type_awarded" id="deposit-percentage" type="radio"  value="{{ \Dotworkers\Bonus\Enums\BonusTypeAwarded::$percentage }}">
                                            <div class="u-check-icon-font g-absolute-centered--y g-left-0">
                                                <i class="fa" data-check-icon="" data-uncheck-icon=""></i>
                                            </div>
                                            {{ _i('Deposit percentage') }}
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <div class="bonus-table d-none">
                                        <table class="table table-bordered">
                                            @foreach ($whitelabel_currencies as $currency)
                                                <tr class="bonus-row bonus-row-{{ $currency->iso }} d-none">
                                                    <td class="align-middle text-center">
                                                        {{ $currency->iso }}
                                                    </td>
                                                    <td class="align-middle">
                                                        <div class="row">
                                                            <div class="col-md-4 fixed-bonus d-none">
                                                                <div class="form-group">
                                                                    <label for="bonus">{{ _i('Bonus to be awarded') }}</label>
                                                                    <input type="number" min="0" name="bonus[{{ $currency->iso }}]" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 deposit-percentage d-none">
                                                                <div class="form-group">
                                                                    <label for="percentage">{{ _i('Percentage bonus ') }}</label>
                                                                    <input type="number" min="0" name="percentages[{{ $currency->iso }}]" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 deposit-percentage d-none">
                                                                <div class="form-group">
                                                                    <label for="limit">{{ _i('Limit to be awarded') }}</label>
                                                                    <input type="number" min="0" name="limits[{{ $currency->iso }}]" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 max-convert d-none">
                                                                <div class="form-group">
                                                                    <label for="max_balance_convert">{{ _i('Maximum real balance') }}</label>
                                                                    <input type="number" min="0" name="max_balances_convert[{{ $currency->iso }}]" class="form-control" placeholder="{{ _i('Optional') }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
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
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
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
                                        <option value="no">{{ _i('No') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 rollovers-data d-none">
                                <div class="form-group">
                                    <label for="include_deposit">{{ _i('Rollover type') }}</label>
                                    <select name="include_deposit" id="include_deposit" class="form-control">
                                        <option value="">{{ _i('Select...') }}</option>
                                        <option value="deposit">{{ _i('Deposit') }}</option>
                                        <option value="bonus">{{ _i('Bonus') }}</option>
                                        <option value="both">{{ _i('Bonus + deposit') }}</option>
                                    </select>
                                </div>
                            </div>
                            {{-- <div class="col-md-4 rollovers-data ">
                                <div class="form-group">
                                    <label for="provider_type">{{ _i('Provider type') }}</label>
                                    <select name="provider_type" id="provider_type" multiple data-route="{{ route('bonus-system.campaigns.exclude-providers') }}" class="form-control">
                                        <option value="">{{ _i('Select...') }}</option>
                                        @foreach ($providers as $provider)
                                        <option value="{{ $provider->id }}">
                                            {{ _i($provider->name) }}
                                        </option>
                                    @endforeach
                                    </select>
                                </div>
                            </div> --}}
                            <div class="col-md-4 rollovers-data d-none">
                                <div class="form-group">
                                    <label for="exclude_providers">{{ _i('Exclude providers') }}</label>
                                    <select name="exclude_providers[]" id="exclude_providers" class="form-control" multiple>
                                        <option value="">{{ _i('Select...') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 rollovers-data d-none">
                                <div class="form-group">
                                    <label for="multiplier">{{ _i('Rollover multiplier') }}</label>
                                    <input type="number" min="0" name="multiplier" id="multiplier" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4 rollovers-data d-none">
                                <div class="form-group">
                                    <label for="days">{{ _i('Days to complete rollover') }}</label>
                                    <input type="number" min="0" name="days" id="days" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4 sports d-none">
                                <div class="form-group">
                                    <label for="bet_type">{{ _i('Bet type') }}</label>
                                    <select name="bet_type" id="bet_type" class="form-control">
                                        <option value="both">{{ _i('Simple and combined') }}</option>
                                        <option value="simple">{{ _i('Simple') }}</option>
                                        <option value="combined">{{ _i('Combined') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 sports d-none">
                                <div class="form-group">
                                    <label for="odd">{{ _i('Quota or Achievement') }}</label>
                                    <input type="text" min="0" name="odd" id="odd" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="submit" class="btn u-btn-3d u-btn-primary" id="store"
                                            data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Publishing...') }}">
                                        <i class="hs-admin-upload"></i>
                                        {{ _i('Publish campaign') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
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
                                    <option value="">{{ _i('No restriction') }}</option>
                                    <option value="users">{{ _i('Users') }}</option>
                                    <option value="segments">{{ _i('Segments') }}</option>
                                    <option value="excel">{{ _i('Excel') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="search-users d-none">
                                <div class="form-group">
                                    <label for="include_users">{{ _i('Include users') }}</label>
                                    <select name="include_users[]" id="include_users" class="form-control select2" data-route="{{ route('users.search-username') }}" multiple>
                                        <option></option>
                                    </select>
                                </div>
                            </div>
                            <div class="search-users d-none">
                                <div class="form-group">
                                    <label for="exclude_users">{{ _i('Exclude users') }}</label>
                                    <select name="exclude_users[]" id="exclude_users" class="form-control select2" data-route="{{ route('users.search-username') }}" multiple>
                                        <option></option>
                                    </select>
                                </div>
                            </div>
                            <div class="search-segments d-none">
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
                            <div class="search-segments d-none">
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
                            <div class="search-excel d-none">
                                <div class="form-group">
                                    <label for="include_excel">{{ _i('Include excel') }}</label>
                                    <input type="file" name="include_excel" id="include_excel" class="opacity-0">
                                </div>
                            </div>
                            <div class="search-excel d-none">
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
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
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
                                        <a href="#add-translations-modal" data-toggle="modal" class="btn u-btn-3d u-btn-primary btn-sm add-translation" data-language="{{ $language['name'] }}" data-language-iso="{{ $language['iso'] }}">
                                            <i class="hs-admin-plus"></i>
                                            {{ _i('Add') }}
                                        </a>
                                        <a href="#add-translations-modal" data-toggle="modal" class="btn u-btn-3d u-btn-primary btn-sm edit-translation d-none" data-language="{{ $language['name'] }}" data-language-iso="{{ $language['iso'] }}">
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
    </form>
    @include('back.bonus-system.campaigns.modals.add-translations-modal')
@endsection

@section('scripts')
    <script>
        $(function () {
            let bonusSystem = new BonusSystem();
            bonusSystem.store(@json($languages));
            bonusSystem.addTranslations(@json($languages));
            $('[data-toggle="popover"]').popover();
        });
    </script>
@endsection

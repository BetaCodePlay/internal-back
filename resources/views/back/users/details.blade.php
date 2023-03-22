@extends('back.template')

@section('content')
    <div class="row">
        <div class="col-md-3">
            <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-15">
                <section class="text-center g-mb-30 g-mb-30--md">
                    <div class="d-inline-block g-pos-rel g-mb-20">
                        @if (is_null($user->avatar))
                            <img class="img-fluid rounded-circle" src="{{ asset('back/img/avatar-default.jpg') }}"
                                alt="{{ $title }}">
                        @else
                            <img class="img-fluid rounded-circle" src="{{ $user->avatar }}"
                                alt="{{ $title }}">
                        @endif
                    </div>
                    <h3 class="g-font-weight-300 g-font-size-20 g-color-black g-mb-10">
                        {{ $title }}
                    </h3>
                    <div class="media-body d-flex justify-content-center">
                        <button type="button" data-route="{{ route('users.change-status', [$user->id, 1, 1]) }}"
                                class="btn u-btn-3d u-btn-teal g-mr-10 change-status {{ !$user->status ? 'd-none' : '' }}"
                                data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Updating') }}" id="active-status">
                            <i class="hs-admin-check"></i>
                            {{ _i('Active') }}
                        </button>
                        <button type="button" data-route="{{ route('users.change-status', [$user->id, 0, 1]) }}"
                                class="btn u-btn-3d u-btn-primary g-mr-10 change-status {{ $user->status ? 'd-none' : '' }}"
                                data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Updating') }}" id="inactive-status">
                            <i class="hs-admin-close"></i>
                            {{ _i('Inactive') }}
                        </button>
                        @if(!in_array(\Dotworkers\Security\Enums\Roles::$admin_Beet_sweet, session('roles')))
                            @can('access', [\Dotworkers\Security\Enums\Permissions::$user_login])
                                <a type="button" class="btn u-btn-3d u-btn-blue g-mr-10" href="{{ $login_user }}" data-route="{{ route('users.audit-users') }}" target="_blank" id="login_user">
                                    <i class="hs-admin-user"></i>
                                    {{ _i('See how') }}
                                </a>
                            @endcan
                        @endif
                    </div>
                    <br>
                </section>
                <section>
                    <ul class="list-unstyled g-mb-0">
                        @can('access', [\Dotworkers\Security\Enums\Permissions::$reset_users_password])
                            <li class="g-brd-top g-brd-gray-light-v7 g-mb-0 g-pl-10">
                                <a href="#reset-password-modal" class="d-flex align-items-center u-link-v5 g-parent g-py-5"
                                   data-toggle="modal">
                                <span class="g-font-size-18 g-color-gray-light-v6 g-color-primary--parent-hover g-color-primary--parent-active g-mr-15">
						            <i class="hs-admin-lock"></i>
					            </span>
                                    <span class="g-color-gray-dark-v6 g-color-primary--parent-hover g-color-primary--parent-active">
                                    {{ _i('Reset password') }}
                                </span>
                                </a>
                            </li>
                        @endcan
                        {{--<li class="g-brd-top g-brd-gray-light-v7 g-mb-0 g-pl-10">
                            <a href="#send-message-modal" class="d-flex align-items-center u-link-v5 g-parent g-py-5"
                               data-toggle="modal">
                                <span class="g-font-size-18 g-color-gray-light-v6 g-color-primary--parent-hover g-color-primary--parent-active g-mr-15">
						            <i class="hs-admin-comment-alt"></i>
					            </span>
                                <span class="g-color-gray-dark-v6 g-color-primary--parent-hover g-color-primary--parent-active">
                                    {{ _i('Send private message') }}
                                </span>
                            </a>
                        </li>
                        <li class="g-brd-top g-brd-gray-light-v7 g-mb-0 g-pl-10">
                            <a href="#send-email-modal" class="d-flex align-items-center u-link-v5 g-parent g-py-5"
                               data-toggle="modal">
                                <span class="g-font-size-18 g-color-gray-light-v6 g-color-primary--parent-hover g-color-primary--parent-active g-mr-15">
						            <i class="hs-admin-email"></i>
					            </span>
                                <span class="g-color-gray-dark-v6 g-color-primary--parent-hover g-color-primary--parent-active">
                                    {{ _i('Send email') }}
                                </span>
                            </a>
                        </li>--}}
                        @can('access', [\Dotworkers\Security\Enums\Permissions::$manual_adjustments])
                            <li class="g-brd-top g-brd-gray-light-v7 g-mb-0 g-pl-10">
                                <a href="#manual-adjustments-modal"
                                   class="d-flex align-items-center u-link-v5 g-parent g-py-5" data-toggle="modal">
                                    <span class="g-font-size-18 g-color-gray-light-v6 g-color-primary--parent-hover g-color-primary--parent-active g-mr-15">
                                        <i class="hs-admin-pencil"></i>
                                    </span>
                                    <span class="g-color-gray-dark-v6 g-color-primary--parent-hover g-color-primary--parent-active">
                                        {{ _i('Manual adjustment (Balance corrections)') }}
                                    </span>
                                </a>
                            </li>
                        @endcan
                        @can('access', [\Dotworkers\Security\Enums\Permissions::$manual_transactions])
                            <li class="g-brd-top g-brd-gray-light-v7 g-mb-0 g-pl-10">
                                <a href="#manual-transaction-modal"
                                   class="d-flex align-items-center u-link-v5 g-parent g-py-5" data-toggle="modal" data-transaction-type="{{ \Dotworkers\Configurations\Enums\TransactionTypes::$credit }}" data-transaction-name="{{ _i('credit') }}">
                                    <span class="g-font-size-18 g-color-gray-light-v6 g-color-primary--parent-hover g-color-primary--parent-active g-mr-15">
                                        <i class="hs-admin-plus"></i>
                                    </span>
                                    <span class="g-color-gray-dark-v6 g-color-primary--parent-hover g-color-primary--parent-active">
                                        {{ _i('Manual credit transaction') }}
                                    </span>
                                </a>
                            </li>
                            <li class="g-brd-top g-brd-gray-light-v7 g-mb-0 g-pl-10">
                                <a href="#manual-transaction-modal"
                                   class="d-flex align-items-center u-link-v5 g-parent g-py-5" data-toggle="modal" data-transaction-type="{{ \Dotworkers\Configurations\Enums\TransactionTypes::$debit }}" data-transaction-name="{{ _i('debit') }}">
                                    <span class="g-font-size-18 g-color-gray-light-v6 g-color-primary--parent-hover g-color-primary--parent-active g-mr-15">
                                        <i class="hs-admin-minus"></i>
                                    </span>
                                    <span class="g-color-gray-dark-v6 g-color-primary--parent-hover g-color-primary--parent-active">
                                        {{ _i('Manual debit transaction') }}
                                    </span>
                                </a>
                            </li>
                        @endcan
                        @can('access', [\Dotworkers\Security\Enums\Permissions::$bonus_transactions])
                            <li class="g-brd-top g-brd-gray-light-v7 g-mb-0 g-pl-10">
                                <a href="#bonus-transaction-modal"
                                   class="d-flex align-items-center u-link-v5 g-parent g-py-5"
                                   data-toggle="modal">
                                        <span
                                            class="g-font-size-18 g-color-gray-light-v6 g-color-primary--parent-hover g-color-primary--parent-active g-mr-15">
                                            <i class="hs-admin-gift"></i>
                                        </span>
                                    <span
                                        class="g-color-gray-dark-v6 g-color-primary--parent-hover g-color-primary--parent-active">
                                            {{ _i('Assign bonus') }} ({{ _i('Real money') }})
                                        </span>
                                </a>
                            </li>
                        @endcan
                        @if ($store)
                            @can('access', [\Dotworkers\Security\Enums\Permissions::$points_transactions])
                                <li class="g-brd-top g-brd-gray-light-v7 g-mb-0 g-pl-10">
                                    <a href="#points-transactions-modal"
                                       class="d-flex align-items-center u-link-v5 g-parent g-py-5" data-toggle="modal">
                                        <span class="g-font-size-18 g-color-gray-light-v6 g-color-primary--parent-hover g-color-primary--parent-active g-mr-15">
                                            <i class="hs-admin-package"></i>
                                        </span>
                                        <span class="g-color-gray-dark-v6 g-color-primary--parent-hover g-color-primary--parent-active">
                                            {{ _i('Points transactions') }}
                                        </span>
                                    </a>
                                </li>
                            @endcan
                        @endif
{{--                        <li class="g-brd-top g-brd-gray-light-v7 g-mb-0">--}}
{{--                            <a href="#add-segmentations-modal"--}}
{{--                               class="d-flex align-items-center u-link-v5 g-parent g-py-5" data-toggle="modal">--}}
{{--                                    <span class="g-font-size-18 g-color-gray-light-v6 g-color-primary--parent-hover g-color-primary--parent-active g-mr-15">--}}
{{--                                        <i class="hs-admin-plus"></i>--}}
{{--                                    </span>--}}
{{--                                <span class="g-color-gray-dark-v6 g-color-primary--parent-hover g-color-primary--parent-active">--}}
{{--                                        {{ _i('Add to segment') }}--}}
{{--                                    </span>--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                        <li class="g-brd-top g-brd-gray-light-v7 g-mb-0">--}}
{{--                            <a href="#remove-segmentations-modal"--}}
{{--                               class="d-flex align-items-center u-link-v5 g-parent g-py-5" data-toggle="modal">--}}
{{--                                    <span class="g-font-size-18 g-color-gray-light-v6 g-color-primary--parent-hover g-color-primary--parent-active g-mr-15">--}}
{{--                                        <i class="hs-admin-layout-line-solid"></i>--}}
{{--                                    </span>--}}
{{--                                <span class="g-color-gray-dark-v6 g-color-primary--parent-hover g-color-primary--parent-active">--}}
{{--                                        {{ _i('Remove from segment') }}--}}
{{--                                    </span>--}}
{{--                            </a>--}}
{{--                        </li>--}}
                        @if($bonus)
{{--                            <li class="g-brd-top g-brd-gray-light-v7 g-mb-0">--}}
{{--                                <a href="#add-bonus-modal"--}}
{{--                                   class="d-flex align-items-center u-link-v5 g-parent g-py-5" data-toggle="modal">--}}
{{--                                    <span class="g-font-size-18 g-color-gray-light-v6 g-color-primary--parent-hover g-color-primary--parent-active g-mr-15">--}}
{{--                                        <i class="hs-admin-plus"></i>--}}
{{--                                    </span>--}}
{{--                                    <span class="g-color-gray-dark-v6 g-color-primary--parent-hover g-color-primary--parent-active">--}}
{{--                                        {{ _i('Activate bonus') }}--}}
{{--                                    </span>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li class="g-brd-top g-brd-gray-light-v7 g-mb-0">--}}
{{--                                <a href="#remover-bonus-modal"--}}
{{--                                   class="d-flex align-items-center u-link-v5 g-parent g-py-5" data-toggle="modal">--}}
{{--                                    <span class="g-font-size-18 g-color-gray-light-v6 g-color-primary--parent-hover g-color-primary--parent-active g-mr-15">--}}
{{--                                        <i class="hs-admin-layout-line-solid"></i>--}}
{{--                                    </span>--}}
{{--                                    <span class="g-color-gray-dark-v6 g-color-primary--parent-hover g-color-primary--parent-active">--}}
{{--                                        {{ _i('Remove bonus') }}--}}
{{--                                    </span>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                                @can('access', [\Dotworkers\Security\Enums\Permissions::$manual_adjustments_bonus])--}}
{{--                                    <li class="g-brd-top g-brd-gray-light-v7 g-mb-0 g-pl-10">--}}
{{--                                        <a href="#manual-adjustments-bonus-modal"--}}
{{--                                           class="d-flex align-items-center u-link-v5 g-parent g-py-5" data-toggle="modal">--}}
{{--                                        <span class="g-font-size-18 g-color-gray-light-v6 g-color-primary--parent-hover g-color-primary--parent-active g-mr-15">--}}
{{--                                            <i class="hs-admin-pencil"></i>--}}
{{--                                        </span>--}}
{{--                                            <span class="g-color-gray-dark-v6 g-color-primary--parent-hover g-color-primary--parent-active">--}}
{{--                                            {{ _i('Manual adjustment bonus') }}--}}
{{--                                        </span>--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
{{--                                @endcan--}}
                        @endif

                    </ul>
                </section>
            </div>
            <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-15">
                <header class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-py-10">
                    <div class="media">
                        <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 g-mb-0">
                            {{ _i('Balance') }} ({{ $wallet->currency_iso}})
                        </h3>
                    </div>
                </header>
                <div class="card-block g-px-15 g-py-5">
                    <div class="media u-header-dropdown-bordered-v2 g-brd-gray-light-v7 g-py-10">
                        <div class="d-flex align-self-center g-mr-12">
                            <span class="u-badge-v2--sm g-pos-stc g-transform-origin--top-left g-bg-darkblue-v2"></span>
                        </div>
                        <div class="media-body align-self-center">
                            {{ _i('Main') }}
                        </div>
                        <div class="d-flex text-right g-font-size-18 g-font-weight-900" id="main-balance">
                            {{ $wallet->balance }}
                        </div>
                    </div>
                    @if( $wallet->balance_locked > 0 )
                        <div class="media u-header-dropdown-bordered-v2 g-brd-gray-light-v7 g-py-10">
                            <div class="d-flex align-self-center g-mr-12">
                                <span class="u-badge-v2--sm g-pos-stc g-transform-origin--top-left g-bg-orange"></span>
                            </div>
                            <div class="media-body align-self-center">
                                {{ _i('Locked') }}
                            </div>
                            @can('access', [\Dotworkers\Security\Enums\Permissions::$unlock_balance])
                                <div class="d-flex text-right g-font-size-18 g-font-weight-900">
                                    <a href="#unlock-balance-modal" class="btn u-btn-3d u-btn-bluegray u-btn-3d" data-toggle="modal">
                                        {{ $wallet->balance_locked }}
                                    </a>
                                </div>
                            @else
                                <div class="d-flex text-right g-font-size-18 g-font-weight-900">
                                    {{ $wallet->balance_locked }}
                                </div>
                            @endcan
                        </div>
                    @endif
                </div>
            </div>
            @foreach($wallets as $walletData)
                @if ($walletData->id != $wallet->id)
                    <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-15">
                        <header class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-py-10">
                            <div class="media">
                                <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 g-mb-0">
                                    {{ _i('Balance') }} ({{ $walletData->currency_iso}})
                                </h3>
                            </div>
                        </header>
                        <div class="card-block g-px-15 g-py-5">
                            <div class="media u-header-dropdown-bordered-v2 g-brd-gray-light-v7 g-py-10">
                                <div class="d-flex align-self-center g-mr-12">
                                    <span class="u-badge-v2--sm g-pos-stc g-transform-origin--top-left g-bg-darkblue-v2"></span>
                                </div>
                                <div class="media-body align-self-center">
                                    {{ _i('Main') }}
                                </div>
                                <div class="d-flex text-right g-font-size-18 g-font-weight-900">
                                    {{ $walletData->balance }}
                                </div>
                            </div>
                            @if( $walletData->balance_locked > 0 )
                                <div class="media u-header-dropdown-bordered-v2 g-brd-gray-light-v7 g-py-10">
                                    <div class="d-flex align-self-center g-mr-12">
                                        <span class="u-badge-v2--sm g-pos-stc g-transform-origin--top-left g-bg-orange"></span>
                                    </div>
                                    <div class="media-body align-self-center">
                                        {{ _i('Locked') }}
                                    </div>
                                    <div class="d-flex text-right g-font-size-18 g-font-weight-900">
                                        {{ $walletData->balance_locked }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            @endforeach

            {{--<div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-15">
                <header
                    class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                    <div class="media">
                        <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 g-mb-0">
                            {{ _i('Balance') }}
                        </h3>
                    </div>
                </header>
                <div class="card-block g-pa-15">
                    <div class="media u-header-dropdown-bordered-v2 g-brd-gray-light-v7 g-py-10">
                        <div class="d-flex align-self-center g-mr-12">
                            <span class="u-badge-v2--sm g-pos-stc g-transform-origin--top-left g-bg-blue"></span>
                        </div>
                        <div class="media-body align-self-center">
                            {{ $wallet->currency_iso }}
                        </div>
                        <div class="d-flex text-right g-font-size-18 g-font-weight-900" id="main-balance">
                            {{ $wallet->balance }}
                        </div>
                        @if( $wallet->balance_locked > 0 )
                            <div class="d-flex align-self-center g-mr-12  g-pa-5">
                                <span class="u-badge-v2--sm g-pos-stc g-transform-origin--top-left g-bg-red"></span>
                            </div>
                            <div class="media-body align-self-center">
                                {{ _i('Locked') }}
                            </div>
                            <div class="d-flex text-right g-font-size-18 g-font-weight-900">
                                {{ $wallet->balance_locked }}
                            </div>
                        @endif
                    </div>
                    @foreach($wallets as $walletData)
                        @if ($walletData->id != $wallet->id)
                        <div class="media u-header-dropdown-bordered-v2 g-brd-gray-light-v7 g-py-10">
                            <div class="d-flex align-self-center g-mr-12">
                                <span class="u-badge-v2--sm g-pos-stc g-transform-origin--top-left g-bg-blue"></span>
                            </div>
                            <div class="media-body align-self-center">
                                {{ $walletData->currency_iso }}
                            </div>
                            <div class="g-font-size-18 g-font-weight-900">
                                {{ $walletData->balance }}
                            </div>
                            @if( $walletData->balance_locked > 0 )
                                <div class="d-flex align-self-center g-mr-12 g-pa-5">
                                    <span
                                        class="u-badge-v2--sm g-pos-stc g-transform-origin--top-left g-bg-red"></span>
                                </div>
                                <div class="media-body align-self-center">
                                    {{ _i('Locked') }}
                                </div>
                                <div class="d-flex g-font-size-18 g-font-weight-900">
                                    {{ $walletData->balance_locked }}
                                </div>
                            @endif
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>--}}
        </div>

        <div class="col-md-6">
            <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                <form action="{{ route('users.profiles.update') }}" id="profile-form" method="post">
                    <header class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 g-mb-0">
                                {{ _i('Personal information') }}
                            </h3>
                            @can('access', [\Dotworkers\Security\Enums\Permissions::$update_users_data])
                                <div class="media-body d-flex justify-content-end">
                                    <input type="hidden" name="user" value="{{ $user->id }}">
                                    <button type="button" class="btn u-btn-3d u-btn-primary float-right" id="update-profile"
                                            data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Updating...') }}">
                                        <i class="hs-admin-reload"></i>
                                        {{ _i('Update data') }}
                                    </button>
                                </div>
                            @endcan
                        </div>
                    </header>
                    <div class="card-block g-pa-15">
                        <div class="row g-mb-15">
                            <div class="col-md-2 align-self-center g-mb-5 g-mb-0--md">
                                <label class="g-mb-0" for="id">
                                    {{ _i('ID') }}
                                </label>
                            </div>
                            <div class="col-md-10 align-self-center">
                                <div class="form-group g-pos-rel g-mb-0">
                                    {{ $user->id }}
                                </div>
                            </div>
                        </div>
                        <div class="row g-mb-15">
                            <div class="col-md-2 align-self-center g-mb-5 g-mb-0--md">
                                <label class="g-mb-0" for="id">
                                    {{ _i('Username') }}
                                </label>
                            </div>
                            <div class="col-md-10 align-self-center">
                                <div class="form-group g-pos-rel g-mb-0">
                                    {{ $user->username }}
                                </div>
                            </div>
                        </div>
                        @if(!in_array(\Dotworkers\Security\Enums\Roles::$admin_Beet_sweet, session('roles')))
                            <div class="row g-mb-15">
                                <div class="col-md-2 align-self-center g-mb-5 g-mb-0--md">
                                    <label class="g-mb-0" for="id">
                                        {{ _i('Referral code') }}
                                    </label>
                                </div>
                                <div class="col-md-10 align-self-center">
                                    <div class="form-group g-pos-rel g-mb-0">
                                        {{ $user->referral_code }}
                                    </div>
                                </div>
                            </div>
                            @if(isset($agent))
                                <div class="row g-mb-15">
                                    <div class="col-md-2 align-self-center g-mb-5 g-mb-0--md">
                                        <label class="g-mb-0" for="id">
                                            {{ _i('Parent agent') }}
                                        </label>
                                    </div>
                                    <div class="col-md-10 align-self-center">
                                        <div class="form-group g-pos-rel g-mb-0">
                                            {!! $agent !!}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                        @can('access', [\Dotworkers\Security\Enums\Permissions::$show_wallet_id])
                            <div class="row g-mb-15">
                                <div class="col-md-2 align-self-center g-mb-5 g-mb-0--md">
                                    <label class="g-mb-0" for="id">
                                        {{ _i('Wallet ID') }}
                                    </label>
                                </div>
                                <div class="col-md-10 align-self-center">
                                    <div class="form-group g-pos-rel g-mb-0">
                                        {{ $wallet->id }}
                                    </div>
                                </div>
                            </div>
                        @endcan
                        <div class="row g-mb-10">
                            <div class="col-md-2 align-self-center g-mb-5 g-mb-0--md">
                                <label class="g-mb-0" for="email">
                                    {{ _i('Email') }}
                                </label>
                            </div>
                            <div class="col-md-10 align-self-center">
                                <div class="form-group g-pos-rel g-mb-0">
                                    <input id="email" name="email" class="form-control" type="email"
                                           value="{{ $user->email }}">
                                </div>
                            </div>
                        </div>
                        <div class="row g-mb-10">
                            <div class="col-md-2 align-self-center g-mb-5 g-mb-0--md">
                                <label class="g-mb-0" for="dni">
                                    {{ _i('DNI') }}
                                </label>
                            </div>

                            <div class="col-md-10 align-self-center">
                                <div class="form-group g-pos-rel g-mb-0">
                                    <input id="dni" name="dni" class="form-control" type="text"
                                           value="{{ $user->dni }}">
                                </div>
                            </div>
                        </div>
                        <div class="row g-mb-10">
                            <div class="col-md-2 align-self-center g-mb-5 g-mb-0--md">
                                <label class="g-mb-0" for="first_name">
                                    {{ _i('Name') }}
                                </label>
                            </div>
                            <div class="col-md-10 align-self-center">
                                <div class="form-group g-pos-rel g-mb-0">
                                    <input id="first_name" name="first_name" class="form-control" type="text"
                                           value="{{ $user->first_name }}">
                                </div>
                            </div>
                        </div>
                        <div class="row g-mb-10">
                            <div class="col-md-2 align-self-center g-mb-5 g-mb-0--md">
                                <label class="g-mb-0" for="last_name">
                                    {{ _i('Last Name') }}
                                </label>
                            </div>
                            <div class="col-md-10 align-self-center">
                                <div class="form-group g-pos-rel g-mb-0">
                                    <input id="last_name" name="last_name" class="form-control" type="text"
                                           value="{{ $user->last_name }}">
                                </div>
                            </div>
                        </div>
                        <div class="row g-mb-10">
                            <div class="col-md-2 align-self-center g-mb-5 g-mb-0--md">
                                <label class="g-mb-0" for="gender">
                                    {{ _i('Gender') }}
                                </label>
                            </div>
                            <div class="col-md-10 align-self-center">
                                <div class="form-group g-pos-rel g-mb-0">
                                    <select name="gender" id="gender" class="form-control">
                                        @if (is_null($user->gender))
                                            <option value="" selected>{{ _i('Select...') }}</option>
                                        @endif
                                        <option value="F" {{ $user->gender == 'F' ? 'selected' : '' }}>
                                            {{ _i('Female') }}
                                        </option>
                                        <option value="M" {{ $user->gender == 'M' ? 'selected' : '' }}>
                                            {{ _i('Male') }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row g-mb-10">
                            <div class="col-md-2 align-self-center g-mb-5 g-mb-0--md">
                                <label class="g-mb-0" for="level">
                                    {{ _i('User level') }}
                                </label>
                            </div>
                            <div class="col-md-10 align-self-center">
                                <div class="form-group g-pos-rel g-mb-0">
                                    <select name="level" id="level" class="form-control">
                                        @foreach ($levels as $level)
                                            <option value="{{ $level->id }}" {{ $level->id == $user->level ? 'selected' : '' }}>
                                                {{ $level->{$selected_language['iso']} }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row g-mb-10">
                            <div class="col-md-2 align-self-center g-mb-5 g-mb-0--md">
                                <label class="g-mb-0" for="country">
                                    {{ _i('Country') }}
                                </label>
                            </div>
                            <div class="col-md-10 align-self-center">
                                <div class="form-group g-pos-rel g-mb-0">
                                    <select name="country" id="country" class="form-control" data-route="{{ route('core.states') }}">
                                        @foreach ($countries as $country)
                                            <option value="{{ $country->iso }}" {{ $country->iso == $user->country_iso ? 'selected' : '' }}>
                                                {{ $country->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row g-mb-10">
                            <div class="col-md-2 align-self-center g-mb-5 g-mb-0--md">
                                <label class="g-mb-0" for="state">
                                    {{ _i('State') }}
                                </label>
                            </div>
                            <div class="col-md-10 align-self-center">
                                <div class="form-group g-pos-rel g-mb-0">
                                    <select name="state" id="state" class="form-control" data-route="{{ route('core.city') }}">
                                        <option value="">{{ _i('Select...') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row g-mb-10">
                            <div class="col-md-2 align-self-center g-mb-5 g-mb-0--md">
                                <label class="g-mb-0" for="city">
                                    {{ _i('City') }}
                                </label>
                            </div>
                            <div class="col-md-10 align-self-center">
                                <div class="form-group g-pos-rel g-mb-0">
                                    <select name="city" id="city" class="form-control" >
                                        <option value="">{{ _i('Select...') }}</option>
                                            @if($user->city)
                                                <option value="{{$user->city}}" selected>{{$user->city}}</option>
                                            @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row g-mb-10">
                            <div class="col-md-2 align-self-center g-mb-5 g-mb-0--md">
                                <label class="g-mb-0" for="timezone">
                                    {{ _i('Timezone') }}
                                </label>
                            </div>
                            <div class="col-md-10 align-self-center">
                                <div class="form-group g-pos-rel g-mb-0">
                                    <select name="timezone" id="timezone" class="form-control">
                                        @foreach ($timezones as $timezone)
                                            <option value="{{ $timezone }}" {{ $timezone == $user->timezone ? 'selected' : '' }}>
                                                {{ $timezone }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row g-mb-10">
                            <div class="col-md-2 align-self-center g-mb-5 g-mb-0--md">
                                <label class="g-mb-0" for="phone">
                                    {{ _i('Phone') }}
                                </label>
                            </div>
                            <div class="col-md-10 align-self-center">
                                <div class="form-group g-pos-rel g-mb-0">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <select name="calling_code" class="form-control">
                                                <option value="">{{ _i('Select...') }}</option>
                                                @foreach ($countries as $country)
                                                    <option value="{{ $country->calling_code }}" {{ $country->calling_code == $user->calling_code ? 'selected' : '' }}>
                                                        {{ $country->name }} (+{{ $country->calling_code }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-8">
                                            <input id="phone" name="phone" class="form-control" type="text"
                                                   value="{{ $user->phone }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-mb-10">
                            <div class="col-md-2 align-self-center g-mb-5 g-mb-0--md">
                                <label class="g-mb-0" for="birth_date">
                                    {{ _i('Date of birth') }}
                                </label>
                            </div>
                            <div class="col-md-10 align-self-center">
                                <div class="form-group g-pos-rel g-mb-0">
                                    <input id="birth_date" name="birth_date" class="form-control datepicker" type="text"
                                           value="{{ !is_null($user->birth_date) ? $user->birth_date : '' }}">
                                </div>
                            </div>
                        </div>
                        <div class="row g-mb-10">
                            <div class="col-md-2 align-self-center g-mb-5 g-mb-0--md">
                                <label class="g-mb-0" for="registration_date">
                                    {{ _i('Registration date') }}
                                </label>
                            </div>
                            <div class="col-md-10 align-self-center">
                                <div class="form-group g-pos-rel g-mb-0">
                                    {{ $user->created }}
                                </div>
                            </div>
                        </div>
                        <div class="row g-mb-10">
                            <div class="col-md-2 align-self-center g-mb-5 g-mb-0--md">
                                <label class="g-mb-0" for="last_access">
                                    {{ _i('Last access') }}
                                </label>
                            </div>
                            <div class="col-md-10 align-self-center">
                                <div class="form-group g-pos-rel g-mb-0">
                                    {{ $user->login }}
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-15">
                <header class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                    <div class="media">
                        <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 g-mb-0">
                            {{ _i('Financial summary') }}
                        </h3>
                    </div>
                </header>
                <div class="card-block g-pa-15">
                    <div class="media u-header-dropdown-bordered-v2 g-brd-gray-light-v7 g-py-10">
                        <div class="d-flex align-self-center g-mr-12">
                            <span class="u-badge-v2--sm g-pos-stc g-transform-origin--top-left g-bg-teal"></span>
                        </div>
                        <div class="media-body align-self-center">
                            {{ _i('Total deposited') }}
                        </div>
                        <div class="d-flex text-right g-font-weight-900" id="main-balance">
                            {{ $user->deposits }}
                        </div>
                    </div>
                    <div class="media u-header-dropdown-bordered-v2 g-brd-gray-light-v7 g-py-10">
                        <div class="d-flex align-self-center g-mr-12">
                            <span class="u-badge-v2--sm g-pos-stc g-transform-origin--top-left g-bg-indigo"></span>
                        </div>
                        <div class="media-body align-self-center">
                            {{ _i('Manual deposits') }}
                        </div>
                        <div class="d-flex text-right g-font-weight-900" id="main-balance">
                            {{ $user->manual_deposits }}
                        </div>
                    </div>

                    <div class="media u-header-dropdown-bordered-v2 g-brd-gray-light-v7 g-py-10">
                        <div class="d-flex align-self-center g-mr-12">
                            <span class="u-badge-v2--sm g-pos-stc g-transform-origin--top-left g-bg-primary"></span>
                        </div>
                        <div class="media-body align-self-center">
                            {{ _i('Total withdrawn') }}
                        </div>
                        <div class="d-flex text-right g-font-weight-900">
                            {{ $user->withdrawals }}
                        </div>
                    </div>

                    <div class="media u-header-dropdown-bordered-v2 g-brd-gray-light-v7 g-py-10">
                        <div class="d-flex align-self-center g-mr-12">
                            <span class="u-badge-v2--sm g-pos-stc g-transform-origin--top-left g-bg-darkred"></span>
                        </div>
                        <div class="media-body align-self-center">
                            {{ _i('Manual withdrawals') }}
                        </div>
                        <div class="d-flex text-right g-font-weight-900">
                            {{ $user->manual_withdrawals }}
                        </div>
                    </div>
                    <div class="media u-header-dropdown-bordered-v2 g-brd-gray-light-v7 g-py-10">
                        <div class="d-flex align-self-center g-mr-12">
                            <span class="u-badge-v2--sm g-pos-stc g-transform-origin--top-left g-bg-blue"></span>
                        </div>
                        <div class="media-body align-self-center">
                            {{ _i('Total profit') }}
                        </div>
                        <div class="d-flex text-right g-font-weight-900">
                            {{ $user->profit }}
                        </div>
                    </div>
                    <div class="media u-header-dropdown-bordered-v2 g-brd-gray-light-v7 g-py-10">
                        <div class="d-flex align-self-center g-mr-12">
                            <span class="u-badge-v2--sm g-pos-stc g-transform-origin--top-left g-bg-orange"></span>
                        </div>
                        <div class="media-body align-self-center">
                            {{ _i('Total bonus') }}
                        </div>
                        <div class="d-flex text-right g-font-weight-900">
                            {{ $user->bonus }}
                        </div>
                    </div>
                </div>
            </div>
            @if ($store)
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-15">
                    <header class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-py-10">
                        <div class="media">
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 g-mb-0">
                                {{ _i('Store') }}
                            </h3>
                        </div>
                    </header>
                    <div class="card-block g-px-15 g-py-5">

                        <div class="media u-header-dropdown-bordered-v2 g-brd-gray-light-v7 g-py-10">
                            <div class="d-flex align-self-center g-mr-12">
                                <span class="u-badge-v2--sm g-pos-stc g-transform-origin--top-left g-bg-purple"></span>
                            </div>
                            <div class="media-body align-self-center">
                                {{ _i('Points') }}
                            </div>
                            <div class="d-flex text-right g-font-size-24 g-font-weight-900" id="points-balance">
                                {{ $points }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-15">
                <header class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">

                    <div class="media">
                        <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 g-mb-0">
                            {{ _i('Payment methods') }}
                        </h3>
                    </div>
                </header>
                <div class="card-block g-pa-15">
                    @if ($user_accounts)
                        @foreach ($user_accounts as $account)
                            <div class="media-md align-items-center g-parent g-brd-around g-brd-gray-light-v2 g-rounded-4 g-px-20 g-py-5 g-mb-3">
                                <div class="d-flex-md text-center g-mb-20 g-mb-0--md">
                                    <div class="d-inline-block u-icon-v3 u-icon-size--lg g-bg-gray-light-v3 g-font-size-24 g-color-secondary rounded-circle">
                                        <i class="g-font-size-0">
                                            {!! $account->logo !!}
                                        </i>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center g-font-size-12 g-font-size-default--md g-mb-10 g-mb-0--md g-mx-10--md">
                                    <div>
                                        {!! $account->info !!}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <label class="js-check g-pos-rel d-block g-mb-20">
                            <div class="media-md align-items-center g-parent g-bg-gray-light-v8--sibling-checked g-brd-around g-brd-gray-light-v7 g-rounded-4 g-px-10 g-py-15">
                                <div class="d-flex align-items-center g-font-size-12 g-font-size-default--md g-mb-10 g-mb-0--md g-mx-10--md">
                                    <div>
                                        {{ _i('This user does not have payment methods') }}
                                    </div>
                                </div>
                            </div>
                        </label>
                    @endif
                </div>
            </div>
            @if(isset($wallets_bonuses))
                @if (count($wallets_bonuses) > 0)
                    <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-15">
                        <header class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-py-10">
                            <div class="media">
                                <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 g-mb-0">
                                    {{ _i('Bonus') }} ({{ session('currency') }})
                                </h3>
                            </div>
                        </header>
                        @foreach ($wallets_bonuses as $bonusWallet)
                            <div class="card-block g-px-15 g-py-5">
                                <div class="media u-header-dropdown-bordered-v2 g-brd-gray-light-v7 g-py-10">
                                    <div class="d-flex align-self-center g-mr-12">
                                        <span class="u-badge-v2--sm g-pos-stc g-transform-origin--top-left g-bg-darkblue-v2"></span>
                                    </div>
                                    <div class="media-body align-self-center">
                                        {{ $bonusWallet->provider_type }}
                                    </div>
                                    <div class="d-flex text-right g-font-size-18 g-font-weight-900">
                                        {{ $bonusWallet->balance }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            @endif
        </div>
        <div class="col-md-12">
            <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                <header class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                    <div class="media">
                        <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 g-mb-0">
                            {{ _i('Wallet transactions') }}
                        </h3>
                        <div class="media-body d-flex justify-content-end g-mb-10" id="table-buttons-wallet">

                        </div>

                        <div class="justify-content-end g-ml-10" style="padding-left: 10px">
                            <button class="btn u-btn-3d u-btn-primary" type="button" id="update-wallet"
                                    data-loading-text="<i class='fa fa-spin fa-refresh g-color-white'></i>">
                                <i class="hs-admin-reload g-color-white"></i>
                            </button>
                        </div>
                    </div>
                </header>
                <div class="card-block g-pa-15">
                    <div class="table-responsive">
                        <input type="hidden" name="wallet" id="wallet" value="{{ $wallet->id }}">
                        <table class="table table-bordered w-100" id="wallet-table"
                               data-route="{{ route('wallets.transactions') }}">
                            <thead>
                            <tr>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Date') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('ID') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Platform') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Description') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Debit') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Credit') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Balance') }}
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        {{--<div class="col-md-12">
            <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                <header class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                    <div class="media">
                        <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 g-mb-0">
                            {{ _i('Wallet transactions - Historic') }}
                        </h3>
                        <div class="media-body d-flex justify-content-end g-mb-10" id="table-buttons-wallet-historic">

                        </div>

                        <div class="justify-content-end g-ml-10" style="padding-left: 10px">
                            <button class="btn u-btn-3d u-btn-primary" type="button" id="update-wallet-historic"
                                    data-loading-text="<i class='fa fa-spin fa-refresh g-color-white'></i>">
                                <i class="hs-admin-reload g-color-white"></i>
                            </button>
                        </div>
                    </div>
                </header>
                <div class="card-block g-pa-15">
                    <div class="table-responsive">
                        <input type="hidden" name="wallet" id="wallet-historic" value="{{ $wallet->id }}">
                        <table class="table table-bordered w-100" id="wallet-table-historic"
                               data-route="{{ route('wallets.transactions-historic') }}">
                            <thead>
                            <tr>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Date') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Platform') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Description') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Debit') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Credit') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Balance') }}
                                </th>
                                --}}{{--                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">--}}{{--
                                --}}{{--                                    {{ _i('Wallet') }}--}}{{--
                                --}}{{--                                </th>--}}{{--
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>--}}
        <div class="col-md-12">
            <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                <header class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                    <div class="media">
                        <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 g-mb-0">
                            {{ _i('Deposits and withdrawals') }}
                        </h3>
                        <div class="media-body d-flex justify-content-end g-mb-10" id="deposit-withdrawals-table-buttons">

                        </div>
                        <div class="justify-content-end g-ml-10" style="padding-left: 10px">
                            <button class="btn u-btn-3d u-btn-primary" type="button" id="update-payments"
                                    data-loading-text="<i class='fa fa-spin fa-refresh g-color-white'></i>">
                                <i class="hs-admin-reload g-color-white"></i>
                            </button>
                        </div>
                    </div>
                </header>
                <div class="card-block g-pa-15">
                    <div class="table-responsive">
                        <input type="hidden" name="currency" id="currency" value="{{ $wallet->currency_iso }}">
                        <input type="hidden" name="user_id" id="user_id" value="{{ $user->id }}">
                        <table class="table table-bordered w-100" id="payments-transactions-table"
                               data-route="{{ route('transactions.user')}}">
                            <thead>
                            <tr>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Date') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('ID') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Platform') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Description') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Debit') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Credit') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Status') }}
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                <header class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                    <div class="media">
                        <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 g-mb-0">
                            {{ _i('Profit') }}
                        </h3>
                        <div class="media-body d-flex justify-content-end g-mb-10" id="products-total-date-table-buttons">

                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="text" id="daterange" class="form-control daterange g-pr-80 g-pl-15 g-py-9" autocomplete="off">
                                <input type="hidden" id="start_date" name="start_date">
                                <input type="hidden" id="end_date" name="end_date">
                            </div>
                        </div>
                        <div class="justify-content-end g-ml-10" style="padding-left: 10px">
                            <button class="btn u-btn-3d u-btn-primary" type="button" id="update-products-total-date"
                                    data-loading-text="<i class='fa fa-spin fa-refresh g-color-white'></i>">
                                <i class="hs-admin-reload g-color-white"></i>
                            </button>
                        </div>
                    </div>
                </header>
                <div class="card-block g-pa-15">
                    <div class="table-responsive">
                        <table class="table table-bordered table-responsive-sm w-100" id="products-users-totals-date-table"
                               data-route="{{ route('users.products-users-totals-data', [$user->id]) }}">
                            <thead>
                            <tr>
                                <th colspan="2" class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none text-center">
                                    {{ _i('Provider') }}
                                </th>
                                <th rowspan="2" class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Currency') }}
                                </th>
                                <th rowspan="2" class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none text-center">
                                    {{ _i('Bets') }}
                                </th>
                                <th rowspan="2" class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Played') }}
                                </th>
                                <th rowspan="2" class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Won') }}
                                </th>
                                <th rowspan="2" class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Profit') }}
                                </th>
                                <th rowspan="2" class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('RTP') }}
                                </th>
                                <th rowspan="2" class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Hold') }}
                                </th>
                            </tr>
                            <tr>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Name') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Type') }}
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                <header class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                    <div class="media">
                        <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 g-mb-0">
                            {{ _i('Connections') }}
                        </h3>

                        <div class="media-body d-flex justify-content-end g-mb-10" id="ip-table-buttons">

                        </div>
                        <div class="justify-content-end g-ml-10" style="padding-left: 10px">
                            <input type="hidden" name="user_id" id="user_id" value="{{ $user->id }}">
                            <button class="btn u-btn-3d u-btn-primary" type="button" id="update-ip"
                                    data-loading-text="<i class='fa fa-spin fa-refresh g-color-white'></i>">
                                <i class="hs-admin-reload g-color-white"></i>
                            </button>
                        </div>
                    </div>
                </header>
                <div class="card-block g-pa-15">
                    <div class="table-responsive">
                        <input type="hidden" name="user_id" id="user_id" value="{{ $user->id }}">
                        <table class="table table-bordered w-100" id="ip-table"
                               data-route="{{ route('users.users-ips-data') }}">
                            <thead>
                            <tr>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('IP') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Quantity') }}
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @can('access', [\Dotworkers\Security\Enums\Permissions::$users_audits])
            <div class="col-md-12">
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                    <header class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 g-mb-0">
                                {{ _i('Audits') }}
                            </h3>

                            <div class="media-body d-flex justify-content-end g-mb-10" id="audit-table-buttons">

                            </div>
                            <div class="justify-content-end g-ml-10" style="padding-left: 10px">
                                <input type="hidden" name="user_id" id="user_id" value="{{ $user->id }}">
                                <button class="btn u-btn-3d u-btn-primary" type="button" id="update-audit"
                                        data-loading-text="<i class='fa fa-spin fa-refresh g-color-white'></i>">
                                    <i class="hs-admin-reload g-color-white"></i>
                                </button>
                            </div>
                        </div>
                    </header>
                    <div class="card-block g-pa-15">
                        <div class="table-responsive">
                            <input type="hidden" name="user_id" id="user_id" value="{{ $user->id }}">
                            <table class="table table-bordered w-100" id="audit-table"
                                   data-route="{{ route('users.users-audit-data') }}">
                                <thead>
                                <tr>
                                    <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                        {{ _i('Details') }}
                                    </th>
                                    <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                        {{ _i('Type') }}
                                    </th>
                                    <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                        {{ _i('Date') }}
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endcan

        @if ($store)
            <div class="col-md-12">
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                    <header class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 g-mb-0">
                                {{ _i('Store transactions history') }}
                            </h3>
                            <div class="media-body d-flex justify-content-end g-mb-10" id="store-transactions-table-buttons">

                            </div>
                            <div class="justify-content-end g-ml-10" style="padding-left: 10px">
                                <button class="btn u-btn-3d u-btn-primary" type="button" id="store-transactions-update"
                                        data-loading-text="<i class='fa fa-spin fa-refresh g-color-white'></i>">
                                    <i class="hs-admin-reload g-color-white"></i>
                                </button>
                            </div>
                        </div>
                    </header>
                    <div class="card-block g-pa-15">
                        <div class="table-responsive">
                            <input type="hidden" name="user_id" id="user_id" value="{{ $user->id }}">
                            <table class="table table-bordered w-100" id="store-transactions-table"
                                   data-route="{{ route('store.transactions') }}">
                                <thead>
                                <tr>
                                    <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                        {{ _i('Date') }}
                                    </th>
                                    <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                        {{ _i('Platform') }}
                                    </th>
                                    <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                        {{ _i('Debit') }}
                                    </th>
                                    <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                        {{ _i('Credit') }}
                                    </th>
                                    <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                        {{ _i('Balance') }}
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                    <header class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 g-mb-0">
                                {{ _i('Store claims history') }}
                            </h3>
                            <div class="media-body d-flex justify-content-end g-mb-10" id="store-claims-table-buttons">

                            </div>
                            <div class="justify-content-end g-ml-10" style="padding-left: 10px">
                                <button class="btn u-btn-3d u-btn-primary" type="button" id="store-claims-update"
                                        data-loading-text="<i class='fa fa-spin fa-refresh g-color-white'></i>">
                                    <i class="hs-admin-reload g-color-white"></i>
                                </button>
                            </div>
                        </div>
                    </header>
                    <div class="card-block g-pa-15">
                        <div class="table-responsive">
                            <input type="hidden" name="user_id" id="user_id" value="{{ $user->id }}">
                            <table class="table table-bordered w-100" id="store-claims-table"
                                   data-route="{{ route('store.rewards.claims') }}">
                                <thead>
                                <tr>
                                    <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                        {{ _i('Date') }}
                                    </th>
                                    <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                        {{ _i('Reward') }}
                                    </th>
                                    <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                        {{ _i('Prize') }}
                                    </th>
                                    <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                        {{ _i('Points') }}
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if($document_verification)
            <div class="col-md-12">
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                    <header class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 g-mb-0">
                                {{ _i('Verification of documents') }}
                            </h3>
                            <div class="media-body d-flex justify-content-end g-mb-10" id="verification-document-table-buttons">

                            </div>
                            <div class="justify-content-end g-ml-10" style="padding-left: 10px">
                                <button class="btn u-btn-3d u-btn-primary" type="button" id="verification-document-update"
                                        data-loading-text="<i class='fa fa-spin fa-refresh g-color-white'></i>">
                                    <i class="hs-admin-reload g-color-white"></i>
                                </button>
                            </div>
                        </div>
                    </header>
                    <div class="card-block g-pa-15">
                        <div class="table-responsive">
                            <input type="hidden" name="user_id" id="user_id" value="{{ $user->id }}">
                            <table class="table table-bordered w-100" id="verification-document-table"
                                   data-route="{{ route('store.documents-user') }}">
                                <thead>
                                <tr>
                                    <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                        {{ _i('Date') }}
                                    </th>
                                    <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                        {{ _i('Document type') }}
                                    </th>
                                    <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                        {{ _i('Status') }}
                                    </th>
                                    <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none text-right">
                                        {{ _i('Actions') }}
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
    @include('back.users.modals.reset-password')
    @include('back.users.modals.send-message')
    @include('back.users.modals.send-email')
    @include('back.users.modals.reset-password')
{{--    @include('back.users.modals.add-segmentations')--}}
{{--    @include('back.users.modals.remove-segmentations')--}}
    @include('back.users.modals.edit-account')
    @can('access', [\Dotworkers\Security\Enums\Permissions::$unlock_balance])
        @include('back.users.modals.unlock-balance')
    @endcan
    @can('access', [\Dotworkers\Security\Enums\Permissions::$manual_adjustments])
        @include('back.users.modals.manual-adjustment')
    @endcan
    @can('access', [\Dotworkers\Security\Enums\Permissions::$manual_transactions])
        @include('back.users.modals.manual-transaction')
    @endcan
    @can('access', [\Dotworkers\Security\Enums\Permissions::$bonus_transactions])
        @include('back.users.modals.bonus-transaction')
    @endcan
    @if ($store)
        @can('access', [\Dotworkers\Security\Enums\Permissions::$points_transactions])
            @include('back.users.modals.points-transactions')
        @endcan
    @endif
    @if($document_verification)
        @include('back.users.modals.watch-document')
        @include('back.users.modals.document-rejected')
    @endif
    @if($bonus)
{{--        @include('back.users.modals.add-bonus')--}}
{{--        @include('back.users.modals.remove-bonus')--}}
{{--        @can('access', [\Dotworkers\Security\Enums\Permissions::$manual_adjustments_bonus])--}}
{{--            @include('back.users.modals.manual-adjustment-bonus')--}}
{{--        @endcan--}}
    @endif
@endsection

@section('scripts')
    <script>
        $(function () {
            let users = new Users();
            let segments = new Segments();
            let bonussystems = new BonusSystem();
            let core = new Core();
            // let security = new Security();
            users.details();
            users.detailsModals();
            users.walletTransactions();
            users.walletTransactionsHistoric();
            users.paymentsTransactions();
            users.productsUsersTotalsDate();
            users.usersIps();
            users.usersAudit();
            users.storeClaims();
            users.storeTransactions();
            users.updateUserAccounts();
            segments.addUser();
            segments.allUser();
            core.states('{{ $user->state }}');
            core.city('{{ $user->city }}');
            @can('access', [\Dotworkers\Security\Enums\Permissions::$unlock_balance])
            users.unlockBalance();
            @endcan
            @can('access', [\Dotworkers\Security\Enums\Permissions::$update_users_status])
            users.changeStatus();
            @endcan
            @can('access', [\Dotworkers\Security\Enums\Permissions::$update_users_data])
            users.updateProfile();
            @endcan
            @can('access', [\Dotworkers\Security\Enums\Permissions::$reset_users_password])
            users.resetPassword();
            @endcan
            @can('access', [\Dotworkers\Security\Enums\Permissions::$manual_transactions])
            users.manualTransactions();
            @endcan
            @can('access', [\Dotworkers\Security\Enums\Permissions::$manual_adjustments])
            users.manualAdjustments();
            @endcan
            @can('access', [\Dotworkers\Security\Enums\Permissions::$bonus_transactions])
            users.bonusTransactions();
            @endcan
            @can('access', [\Dotworkers\Security\Enums\Permissions::$user_login])
            users.loginUser();
            @endcan
            @if ($store)
            @can('access', [\Dotworkers\Security\Enums\Permissions::$points_transactions])
            users.pointsTransactions();
            @endcan
            @endif
            @if($document_verification)
            users.documentsByUser();
            @endif
            @if($bonus)
            // bonussystems.addUser();
            // bonussystems.removeUser();
{{--            @can('access', [\Dotworkers\Security\Enums\Permissions::$manual_adjustments_bonus])--}}
{{--            bonussystems.manualAdjustments();--}}
{{--            @endcan--}}
            @endif
            users.disableAccount();
        });
    </script>
@endsection

@extends('back.template')

@section('styles')
    {{-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap.min.css"> --}}
    <style>
        #financial-state-table .bg-warning {
            background-color: rgba(255, 193, 7, 0.4) !important;
        }

        #financial-state-table .bg-primary {
            background-color: rgba(0, 123, 255, .4) !important;
        }

        #financial-state-table .bg-success {
            background-color: rgba(40, 167, 69, .4) !important
        }

        .init_tree {
            color: rgb(77 77 77) !important
        }

        .init_agent {
            color: #3398dc !important;
            font-weight: bold !important;
        }

        .init_user {
            color: #e62154 !important;
            font-weight: bold !important;
        }

        .nav_link_blue {
            color: white !important;
            background-color: #38a7ef !important;
        }

        .flex-items {
            display: flex;
        }

        /*#dashboard {*/
        /*    border-color: #38a7ef;*/
        /*    border-top-style: solid;*/
        /*    border-right-style: solid;*/
        /*    border-bottom-style: solid;*/
        /*    border-left-style: solid;*/
        /*}*/
        .nav_link_red {
            color: white !important;
            background-color: #e62154 !important
        }

        .nav_link_green {
            color: white !important;
            background-color: green !important
        }

        .nav_link_orange {
            color: white !important;
            background-color: darkorange !important
        }

        .select2-container {
            width: 100% !important;
            text-align: left !important;
        }

        .jstree-default .jstree-anchor:hover, .jstree-default .jstree-anchor:focus {
            background: #e7f4f9;
            border-radius: 2px;
            box-shadow: inset 0 0 1px #ccc;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-3 col-xl-4">
            <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                <header
                    class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                    <div class="media">
                        <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 g-mb-0">
                            {{ $title}}
                        </h3>
                        <div class="media-body d-flex justify-content-end g-mb-10" id="table-buttons-wallet">

                        </div>
                        <div class="d-none d-sm-none d-md-block">
                            <div class="justify-content-end g-ml-10" style="padding-left: 10px">
                            </div>
                        </div>
                    </div>
                </header>
                <div class="card-block g-pa-15">
                    <div class="d-block d-sm-block d-md-none g-pa-10">
                        <div class="row">
                            <div class="col-12">
                                @if(!in_array(\Dotworkers\Security\Enums\Roles::$admin_Beet_sweet, session('roles')))
                                    <select name="agent_id_search" id="username_search"
                                            class="form-control select2 username_search agent_id_search"
                                            data-route="{{ route('agents.search-username')}}"
                                            data-select="{{ route('agents.find-user') }}">
                                        <option></option>
                                    </select>
                                @endif

                            </div>
                        </div>
                    </div>
                    <div class="">
                        <div id="tree" data-route="{{ route('agents.find') }}" data-json="{{ $tree }}"></div>
                    </div>
                    <div id="tree-pro" class="jstree" data-route="{{ route('agents.find') }}">
                        <div class="jstree-default">
                            <ul class="jstree-container-ul jstree-children">
                                <li class="jstree-node init_tree jstree-last jstree-open" id="tree-pro-init">
                                    <i class="jstree-icon jstree-ocl" id="tree-pro-master" data-idtreepro="{{ auth()->user()->id }}" data-typetreepro="agent"></i><a href="javascript:void(0)" class="jstree-anchor"><i class="jstree-icon jstree-themeicon fa fa-diamond jstree-themeicon-custom"
                                                                                                                                                                                                              role="presentation"></i>{{ isset(auth()->user()->username) ? auth()->user()->username : '' }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-9 col-xl-8">
            <div class="d-none d-sm-none d-md-block">
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30 g-pa-15">
                    <div class="d-block d-sm-block d-md-none">
                        <header
                            class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                            <div class="media">
                                <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                    {{ $title }}
                                </h3>
                            </div>
                        </header>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-8 g-py-5 g-pa-5">
                            {{--                            @if(!in_array(\Dotworkers\Security\Enums\Roles::$admin_Beet_sweet, session('roles')))--}}
                            <select name="agent_id_search" id="agent_id_search"
                                    class="form-control select2 agent_id_search"
                                    data-route="{{ route('agents.search-username')}}"
                                    data-select="{{ route('agents.find-user') }}">
                                <option></option>
                            </select>
                            {{--                            @endif--}}
                        </div>
                        {{--                        <div class="col-6 col-md-2 g-py-5">--}}
                        {{--                            <a href="#add-users-modal" data-toggle="modal" class="btn u-btn-3d u-btn-primary btn-block"--}}
                        {{--                               id="new-user">--}}
                        {{--                                <i class="hs-admin-plus"></i>--}}
                        {{--                                {{ _i(' Player') }}--}}
                        {{--                            </a>--}}
                        {{--                        </div>--}}
                        {{--                        @if ($agent->master)--}}
                        {{--                            <div class="col-6 col-md-2 g-py-5">--}}
                        {{--                                <a href="#add-agents-modal" data-toggle="modal"--}}
                        {{--                                   class="btn u-btn-3d u-btn-blue btn-block" id="new-agent">--}}
                        {{--                                    <i class="hs-admin-plus"></i>--}}
                        {{--                                    {{ _i(' Agent ') }}--}}
                        {{--                                </a>--}}
                        {{--                            </div>--}}
                        {{--                        @endif--}}
                    </div>
                </div>
            </div>
            <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                <div class="card-block g-pa-15">
                    <div class="d-block d-sm-block d-md-none">
                        <div class="d-flex align-self-center justify-content-end">
                            <div class="g-pos-rel g-top-3 d-inline-block">
                                <div class="dropdown">
                                    <a class="d-block g-text-underline--none--hover text-dark dropdown-toggle"
                                       type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                       aria-expanded="false">
                                        <i class="hs-admin-angle-down g-pos-rel g-top-2 g-ml-10"></i> {{ _i('Options') }}
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" href="#dashboard" id="dashboard-mobile"
                                           data-target="#dashboard" aria-controls="dashboard" aria-selected="true">
                                            <i class="hs-admin-dashboard"></i>
                                            {{ _i('Dashboard') }}
                                        </a>
                                        <a class="dropdown-item" href="#agents-transactions"
                                           id="agents-transactions-mobile" data-target="#agents-transactions"
                                           aria-controls="agents-transactions" aria-selected="false">
                                            <i class="hs-admin-layout-list-thumb"></i>
                                            {{ _i('Transactions') }}
                                        </a>
                                        <a class="dropdown-item d-none" data-target="#users-transactions"
                                           href="#users-transactions" id="users-transactions-mobile"
                                           aria-controls="users-transactions" aria-selected="false">
                                            <i class="hs-admin-layout-list-thumb"></i>
                                            {{ _i('Transactions') }}
                                        </a>
                                        <a class="dropdown-item" data-target="#users" href="#users" id="users-mobile"
                                           aria-controls="users" aria-selected="false">
                                            <i class="hs-admin-user"></i>
                                            {{ _i('Players') }}
                                        </a>
                                        @if (!empty($agent) && $agent->master)
                                            <a class="dropdown-item" data-target="#agents" href="#agents"
                                               id="agents-mobile" aria-controls="agents" aria-selected="false">
                                                <i class="hs-admin-briefcase"></i>
                                                {{ _i('Agents') }}
                                            </a>
                                        @endif
                                        <a class="dropdown-item" data-target="#financial-state" href="#financial-state"
                                           id="financial-state-mobile" aria-controls="agents" aria-selected="false">
                                            <i class="hs-admin-pie-chart"></i>
                                            {{ _i('Financial state') }}
                                        </a>
                                        @if (!empty($agent) && $agent->master)
                                            <a class="dropdown-item d-none" data-target="#locks" href="#locks"
                                               id="locks-mobile" aria-controls="agents" aria-selected="false">
                                                <i class="hs-admin-lock"></i>
                                                {{ _i('Locks') }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-none d-sm-none d-md-block">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active nav_link_blue" id="dashboard-tab" data-toggle="tab"
                                   href="#dashboard" role="tab" aria-controls="dashboard" aria-selected="true">
                                    <i class="hs-admin-dashboard"></i>
                                    {{ _i('Dashboard') }}
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link nav_link_red" id="agents-transactions-tab" data-toggle="tab"
                                   href="#agents-transactions" role="tab" aria-controls="agents-transactions"
                                   aria-selected="false">
                                    <i class="hs-admin-layout-list-thumb"></i>
                                    {{ _i('Transactions') }}
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link d-none nav_link_red" id="users-transactions-tab" data-toggle="tab"
                                   href="#users-transactions" role="tab" aria-controls="users-transactions"
                                   aria-selected="false">
                                    <i class="hs-admin-layout-list-thumb"></i>
                                    {{ _i('Transactions') }}
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link nav_link_blue" id="users-tab" data-toggle="tab" href="#users"
                                   role="tab" aria-controls="users" aria-selected="false">
                                    <i class="hs-admin-user"></i>
                                    {{ _i('Players') }}
                                </a>
                            </li>
                            @if (!empty($agent) && $agent->master)
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link nav_link_red" id="agents-tab" data-toggle="tab" href="#agents"
                                       role="tab" aria-controls="agents" aria-selected="false">
                                        <i class="hs-admin-briefcase"></i>
                                        {{ _i('Agents') }}
                                    </a>
                                </li>
                            @endif
                            <li class="nav-item" role="presentation">
                                <a class="nav-link nav_link_green" id="financial-state-tab" data-toggle="tab"
                                   href="#financial-state" role="tab" aria-controls="agents" aria-selected="false">
                                    <i class="hs-admin-pie-chart"></i>
                                    {{ _i('Financial state') }}
                                </a>
                            </li>
                            @if (!empty($agent) && $agent->master)
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link d-none nav_link_orange" id="locks-tab" data-toggle="tab"
                                       href="#locks" role="tab" aria-controls="agents" aria-selected="false">
                                        <i class="hs-admin-lock"></i>
                                        {{ _i('Locks') }}
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active mobile g-py-20 g-px-5" id="dashboard" role="tabpanel"
                             aria-labelledby="dashboard-tab">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row g-mb-15">
                                        <div
                                            class="col-4 col-sm-4 col-md-3 g-mb-5 g-mb-0--md g-mb-10 align-self-center">
                                            <label class="g-mb-0">
                                                <strong>{{ _i('Username') }}</strong>
                                            </label>
                                        </div>
                                        <div class="col-4 col-sm-7 col-md-7 align-self-center">
                                            <div class="form-group g-pos-rel g-mb-0">
                                                <span id="username"></span>
                                            </div>
                                        </div>
                                        <div class="col-4 col-sm-2 col-md-2 align-self-center" id="modals-transaction">
                                            <div class="d-block d-sm-block d-md-none">
                                                <div class="row">
                                                    <div class="form-group mb-0">
                                                        @can('access', [\Dotworkers\Security\Enums\Permissions::$agents_credit_transactions])
                                                            <label href="#transaction-modal"
                                                                   class="btn u-btn-3d u-btn-blue" data-toggle="modal"
                                                                   data-transaction-type="{{ \Dotworkers\Configurations\Enums\TransactionTypes::$credit }}"
                                                                   data-transaction-name="{{ _i('credit') }}">
                                                                <i class="hs-admin-plus"></i>
                                                            </label>
                                                        @endcan
                                                        @can('access', [\Dotworkers\Security\Enums\Permissions::$agents_debit_transactions])
                                                            <label href="#transaction-modal"
                                                                   class="btn u-btn-3d u-btn-primary"
                                                                   data-toggle="modal"
                                                                   data-transaction-type="{{ \Dotworkers\Configurations\Enums\TransactionTypes::$debit }}"
                                                                   data-transaction-name="{{ _i('debit') }}">
                                                                <i class="hs-admin-layout-line-solid"></i>
                                                            </label>
                                                        @endcan
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- TODO ROL 19 NUEVO ROL DE AGENTE--}}
                                    @if(!in_array(\Dotworkers\Security\Enums\Roles::$admin_Beet_sweet, session('roles')))
                                        <div class="row g-mb-15">
                                            <div
                                                class="col-4 col-sm-4 col-md-3 g-mb-5 g-mb-0--md g-mb-10 align-self-center">
                                                <label class="g-mb-0">
                                                    <strong> {{ _i('Code') }}</strong>
                                                </label>
                                            </div>
                                            <div class="col-4 col-sm-5 col-md-4 align-self-center">
                                                <div class="form-group g-pos-rel g-mb-0">
                                                    <span id="referral_code"></span>
                                                </div>
                                            </div>
                                            <div class="col-4 col-sm-3 col-md-5 align-self-center">
                                                <div class="form-group g-pos-rel g-mb-0">
                                                    <button
                                                        class="btn g-width-40 g-height-40 u-btn-primary g-rounded-4 u-btn-3d btn-sm clipboard"
                                                        type="button" type="button" id="clipboard"
                                                        data-title="{{ _i('Copied') }}">
                                                        <i class="hs-admin-clipboard g-absolute-centered g-font-size-16 g-color-white"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-mb-15">
                                            <div
                                                class="col-4 col-sm-4 col-md-3 g-mb-5 g-mb-0--md g-mb-10 align-self-center">
                                                <label class="g-mb-0">
                                                    <strong>{{ _i('Timezone') }}</strong>
                                                </label>
                                            </div>
                                            <div class="col-8 col-sm-8 col-md-9 align-self-center">
                                                <div class="form-group g-pos-rel g-mb-0">
                                                    <span id="agent_timezone"></span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="row g-mb-15">
                                        <div
                                            class="col-4 col-sm-4 col-md-3 g-mb-5 g-mb-0--md g-mb-10 align-self-center">
                                            <label class="g-mb-0">
                                                <strong>{{ _i('Balance') }}</strong>
                                            </label>
                                        </div>
                                        <div class="col-8 col-sm-8 col-md-9 align-self-center">
                                            <div class="form-group g-pos-rel g-mb-0">
                                                <span class="balance"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-mb-15">
                                        <div
                                            class="col-4 col-sm-4 col-md-3 g-mb-5 g-mb-0--md g-mb-10 align-self-center">
                                            <label class="g-mb-0">
                                                <strong> {{ _i('Type') }}</strong>
                                            </label>
                                        </div>
                                        <div class="col-8 col-sm-8 col-md-9 align-self-center">
                                            <div class="form-group g-pos-rel g-mb-0">
                                                <span id="user_type"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-mb-15">
                                        <div
                                            class="col-4 col-sm-4 col-md-3 g-mb-5 g-mb-0--md g-mb-10 align-self-center">
                                            <label class="g-mb-0">
                                                <strong>{{ _i('Status') }}</strong>
                                            </label>
                                        </div>
                                        <div class="col-8 col-sm-8 col-md-9 align-self-center">
                                            <div class="form-group g-pos-rel g-mb-0">
                                                <span id="status"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-mb-15">
                                        <div
                                            class="col-4 col-sm-4 col-md-3 g-mb-5 g-mb-0--md g-mb-10 align-self-center">
                                            <label class="g-mb-0">
                                                <strong>{{ _i('Password') }}</strong>
                                            </label>
                                        </div>
                                        <div class="col-8 col-sm-8 col-md-9 align-self-center">
                                            <div class="form-group g-pos-rel g-mb-0">
                                                <a href="#reset-password-modal"
                                                   class="btn u-btn-3d u-btn-primary btn-sm" data-toggle="modal">
                                                    {{ _i('Reset') }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    @if (!empty($agent) && $agent->master)
                                        <div class="row g-mb-15 d-none" id="move-agents-user">
                                            <div
                                                class="col-4 col-sm-4 col-md-3 g-mb-5 g-mb-0--md g-mb-10 align-self-center">
                                                <label class="g-mb-0">
                                                    <strong>{{ _i('Move user') }}</strong>
                                                </label>
                                            </div>
                                            <div class="col-8 col-sm-8 col-md-9 align-self-center">
                                                <div class="form-group g-pos-rel g-mb-0">
                                                    <a href="#move-agents-users-modal" id="move-agents-users"
                                                       class="btn u-btn-3d u-btn-blue btn-sm" data-toggle="modal">
                                                        {{ _i('Move') }}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-mb-15 d-none" id="move-agents">
                                            <div
                                                class="col-4 col-sm-4 col-md-3 g-mb-5 g-mb-0--md g-mb-10 align-self-center">
                                                <label class="g-mb-0">
                                                    <strong>{{ _i('Move agent') }}</strong>
                                                </label>
                                            </div>
                                            <div class="col-8 col-sm-8 col-md-9 align-self-center">
                                                <div class="form-group g-pos-rel g-mb-0">
                                                    <a href="#move-agents-modal" id="move-agents"
                                                       class="btn u-btn-3d u-btn-blue btn-sm" data-toggle="modal">
                                                        {{ _i('Move') }}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="row g-mb-15" id="details-user">
                                        <div class="col-12 col-sm-8 col-md-9 align-self-center">
                                            <div class="form-group g-pos-rel g-mb-0">
                                                <a href="#details-user-modal" id="details-user-get"
                                                   data-route="{{route('agents.get.father.cant')}}"
                                                   class="btn u-btn-3d u-btn-blue btn-sm" data-toggle="modal">
                                                    <i class="hs-admin-info g-font-size-16 g-color-white"
                                                       style="font-weight: 700!important;"></i><strong> {{ _i('More information') }}</strong>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @can('access', [\Dotworkers\Security\Enums\Permissions::$agents_credit_transactions,  \Dotworkers\Security\Enums\Permissions::$agents_debit_transactions])
                                    <div class="col-md-6">
                                        <div class="d-none d-sm-none d-md-block">
                                            <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30 d-none"
                                                 id="transactions-form-container">
                                                <header
                                                    class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                                                    <div class="media">
                                                        <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                                            {{ _i('Balance adjustments') }}
                                                        </h3>
                                                    </div>
                                                </header>
                                                <div class="card-block g-pa-15">
                                                    <form action="{{ route('agents.perform-transactions') }}"
                                                          id="transactions-form" method="post">
                                                        <input type="hidden" name="wallet" id="wallet">
                                                        <input type="hidden" name="user" class="user">
                                                        <input type="hidden" name="type" id="type">
                                                        <div class="form-group">
                                                            <label for="amount">{{ _i('Amount') }}</label>
                                                            <input type="number" name="amount" id="amount"
                                                                   class="form-control" min="0">
                                                        </div>
                                                        <div class="row">
                                                            @can('access', [\Dotworkers\Security\Enums\Permissions::$agents_credit_transactions])
                                                                <div class="col-6">
                                                                    <button type="button"
                                                                            class="btn u-btn-3d u-btn-blue btn-block"
                                                                            id="credit"
                                                                            data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Please wait...') }}">
                                                                        {{ _i('Credit') }}
                                                                    </button>
                                                                </div>
                                                            @endcan
                                                            @can('access', [\Dotworkers\Security\Enums\Permissions::$agents_debit_transactions])
                                                                <div class="col-6">
                                                                    <button type="button"
                                                                            class="btn u-btn-3d u-btn-primary btn-block"
                                                                            id="debit"
                                                                            data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Please wait...') }}">
                                                                        {{ _i('Debit') }}
                                                                    </button>
                                                                </div>
                                                            @endcan
                                                        </div>
                                                        <br>
                                                        <div class="row">
                                                            <div class="col-6" id="ticket">
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endcan
                            </div>
                        </div>
                        <div class="tab-pane fade mobile g-py-20 g-px-5" id="agents-transactions" role="tabpanel"
                             aria-labelledby="agents-transactions-tab">
                            <div class="row">
                                <div class="offset-md-2"></div>
                                {{-- <div class="form-group">
                                    <label for="date_range">{{ _i('Date range') }}</label>
                                    <input type="text" id="daterange" class="form-control daterange" autocomplete="off" placeholder="{{ _i('Date range') }}">
                                    <input type="hidden" id="start_date" name="start_date">
                                    <input type="hidden" id="end_date" name="end_date">
                                </div> --}}
                                <div class="col-md-3 ">
                                    <div class="form-group">
                                        <label for="transaction_select">{{ _i('Type Transaction') }}</label>
                                        <select name="transaction_select" id="transaction_select" class="form-control">
                                            <option value="all" selected="selected" hidden>{{_i('All')}}</option>
                                            <option value="credit">{{_i('Charge')}}</option>
                                            <option value="debit">{{_i('Discharge')}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 ">
                                    <div class="form-group">
                                        <label for="type_select">{{ _i('Type User') }}</label>
                                        <select name="type_select" id="type_select" class="form-control">
                                            <option value="all" selected="selected" hidden>{{_i('All')}}</option>
                                            <option value="agent">{{_i('Agent')}}</option>
                                            <option value="user">{{_i('User')}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 col-xs-12 col-sm-12">
                                    <div class="form-group">
                                        <label for="date_range">{{ _i('Date range') }}</label>
                                        <div class="flex-items">
                                            <input type="text" id="date_range_new" class="form-control"
                                                   autocomplete="off"
                                                   placeholder="{{ _i('Date range') }}">
                                            <button class="btn g-bg-primary" type="button" id="updateNew"
                                                    data-route="{{ route('agents.transactions.paginate') }}"
                                                    data-routetotals="{{ route('agents.transactions.totals') }}"
                                                    data-loading-text="<i class='fa fa-spin fa-refresh g-color-white'></i>">
                                                <i class="hs-admin-reload g-color-white"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="media">
                                <div class="media-body d-flex justify-content-start g-mb-10" id="table-buttons">

                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover dt-responsive"
                                       id="agents-transactions-table"
                                       data-route="{{ route('agents.transactions.paginate') }}"
                                       data-routetotals="{{ route('agents.transactions.totals') }}">
                                    <thead>
                                    <tr>
                                        <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                            {{ _i('Date') }}
                                        </th>
                                        <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                            {{--                                            {{ _i('From') }}--}}
                                            Agente
                                            {{--                                            {{ _i('User') }}--}}
                                        </th>
                                        <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                            {{--                                            {{ _i('Toward') }}--}}
                                            Cuenta destino
                                        </th>
                                        {{--                                        <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">--}}
                                        {{--                                            {{ _i('Debit') }}--}}
                                        {{--                                            Descarga--}}
                                        {{--                                        </th>--}}
                                        {{--                                        <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">--}}
                                        {{--                                            {{ _i('Credit') }}--}}
                                        {{--                                            Carga--}}
                                        {{--                                        </th>--}}
                                        <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                            {{--                                            {{ _i('Credit') }}--}}
                                            {{_i('Amount')}}
                                        </th>
                                        {{--                                        @if(in_array(\Dotworkers\Security\Enums\Roles::$admin_Beet_sweet, session('roles')))--}}
                                        {{--                                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none" title="Entrada">--}}
                                        {{--                                                {{ _i('Charged him') }}--}}
                                        {{--                                                Carga--}}
                                        {{--                                            </th>--}}
                                        {{--                                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none" title="Salida">--}}
                                        {{--                                                {{ _i('Withdrew') }}--}}
                                        {{--                                                Retiro--}}
                                        {{--                                            </th>--}}
                                        {{--                                        @else--}}
                                        {{--                                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">--}}
                                        {{--                                                {{ _i('Debit') }}--}}
                                        {{--                                            </th>--}}
                                        {{--                                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">--}}
                                        {{--                                                {{ _i('Credit') }}--}}
                                        {{--                                            </th>--}}
                                        {{--                                        @endif--}}
                                        {{--                                        <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">--}}
                                        {{--                                            {{ _i('Toward') }}--}}{{--...--}}
                                        {{--                                        </th>--}}
                                        <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                            {{ _i('Balance') }}
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                                <div class="col-md-12">
                                    <br>
                                    <div class="table-responsive">
                                        <div class="totalsTransactionsPaginate"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade mobile g-py-20 g-px-5" id="users-transactions" role="tabpanel"
                             aria-labelledby="users-transactions-tab">
                            <div class="table-responsive">
                                <table class="table table-bordered display nowrap" style="width:100%"
                                       id="users-transactions-table"
                                       data-route="{{ route('wallets.transactions') }}">
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
                                        @if(in_array(\Dotworkers\Security\Enums\Roles::$admin_Beet_sweet, session('roles')))
                                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                                {{ _i('Charged him') }}
                                            </th>
                                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                                {{ _i('Withdrew') }}
                                            </th>
                                        @else
                                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                                {{ _i('Debit') }}
                                            </th>
                                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                                {{ _i('Credit') }}
                                            </th>
                                        @endif
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
                        <div class="tab-pane fade mobile g-py-20 g-px-5" id="users" role="tabpanel"
                             aria-labelledby="users-tab">
                            <div class="media">
                                <div class="media-body d-flex justify-content-end g-mb-10" id="table-buttons-users">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered display nowrap" style="width:100%"
                                               id="users-table"
                                               data-route="{{ route('agents.users') }}">
                                            <thead>
                                            <tr>
                                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                                    {{ _i('Username') }}
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
                        @if (!empty($agent) && $agent->master)
                            <div class="tab-pane fade mobile g-py-20 g-px-5" id="agents" role="tabpanel"
                                 aria-labelledby="agents-tab">
                                <div class="media">
                                    <div class="media-body d-flex justify-content-end g-mb-10"
                                         id="table-buttons-agents">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered display nowrap" style="width:100%"
                                                   id="agents-table"
                                                   data-route="{{ route('agents.agents') }}">
                                                <thead>
                                                <tr>
                                                    <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                                        {{ _i('Username') }}
                                                    </th>
                                                    <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                                        {{ _i('Type') }}
                                                    </th>
                                                    <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                                        {{ _i('Percentage') }}
                                                    </th>
                                                    <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                                        {{ _i('Balance') }}
                                                    </th>
                                                    <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                                        {{ _i('Options') }}
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
                        <div class="tab-pane fade mobile g-py-20 g-px-5" id="financial-state" role="tabpanel"
                             aria-labelledby="financial-state-tab">
                            <div class="offset-md-8 col-xs-12 col-sm-12 col-md-4">
                                <div class="input-group">
                                    <input type="hidden" id="_hour" name="_hour" value="_hour">
                                    <input type="hidden" id="username_like" name="username_like">
                                    <input type="text" id="date_range" class="form-control" autocomplete="off"
                                           placeholder="{{ _i('Date range') }}">
                                    <div class="input-group-append">
                                        <button class="btn g-bg-primary" type="button" id="update"
                                                data-loading-text="<i class='fa fa-spin fa-refresh g-color-white'></i>">
                                            <i class="hs-admin-reload g-color-white"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            {{--                            @include('back.layout.litepicker')--}}
                            {{--                            <div class="col-md-12">--}}
                            {{--                                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">--}}
                            {{--                                    <header class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">--}}
                            {{--                                        <div class="media">--}}
                            {{--                                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 g-mb-0">--}}
                            {{--                                                {{ _i('Connections') }}--}}
                            {{--                                            </h3>--}}

                            {{--                                            <div class="media-body d-flex justify-content-end g-mb-10" id="ip-table-buttons">--}}

                            {{--                                            </div>--}}
                            {{--                                            <div class="justify-content-end g-ml-10" style="padding-left: 10px">--}}
                            {{--                                                <input type="hidden" name="user_id" id="user_id"  class="user_id_ips" value="{{ \Illuminate\Support\Facades\Auth::user()->id }}">--}}
                            {{--                                                <button class="btn u-btn-3d u-btn-primary" type="button" id="update-ip"--}}
                            {{--                                                        data-loading-text="<i class='fa fa-spin fa-refresh g-color-white'></i>">--}}
                            {{--                                                    <i class="hs-admin-reload g-color-white"></i>--}}
                            {{--                                                </button>--}}
                            {{--                                            </div>--}}
                            {{--                                        </div>--}}
                            {{--                                    </header>--}}
                            {{--                                    <div class="card-block g-pa-15">--}}
                            {{--                                        <div class="table-responsive">--}}
                            {{--                                            <input type="hidden" name="user_id" id="user_id" value="{{ \Illuminate\Support\Facades\Auth::user()->id }}">--}}
                            {{--                                            <table class="table table-bordered w-100" id="ip-table"--}}
                            {{--                                                   data-route="{{ route('users.users-ips-data') }}">--}}
                            {{--                                                <thead>--}}
                            {{--                                                <tr>--}}
                            {{--                                                    <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">--}}
                            {{--                                                        {{ _i('IP') }}--}}
                            {{--                                                    </th>--}}
                            {{--                                                    <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">--}}
                            {{--                                                        {{ _i('Quantity') }}--}}
                            {{--                                                    </th>--}}
                            {{--                                                </tr>--}}
                            {{--                                                </thead>--}}
                            {{--                                                <tbody>--}}
                            {{--                                                </tbody>--}}
                            {{--                                            </table>--}}
                            {{--                                        </div>--}}
                            {{--                                    </div>--}}
                            {{--                                </div>--}}
                            {{--                            </div>--}}
                            @if(!in_array(\Dotworkers\Security\Enums\Roles::$admin_Beet_sweet, session('roles')))
                                <div class="table-responsive if-admin" id="financial-state-table"
                                     data-route="{{ route('agents.reports.financial-state-data') }}"></div>
                            @else
                                <div class="table-responsive" id="financial-state-table"
                                     data-route="{{ route('agents.reports.financial-state-summary-data-new') }}"></div>
                            @endif
                            {{--                            @if(!in_array(\Dotworkers\Security\Enums\Roles::$admin_Beet_sweet, session('roles')))--}}
                            {{--                                <div class="table-responsive" id="financial-state-table" data-route="{{ route('agents.reports.financial-state-data') }}"></div>--}}
                            {{--                            @else--}}
                            {{--                                <div class="table-responsive" id="financial-state-table" data-route="{{ route('agents.reports.financial-state-summary-data') }}">--}}
                            {{--                                --}}{{----}}{{--TODO NUEVA CONSULTA--}}
                            {{--                                --}}{{----}}{{--<div class="table-responsive" id="financial-state-table" data-route="{{ route('agents.reports.financial-state-data.view1') }}">--}}
                            {{--                                </div>--}}
                            {{--                            @endif--}}

                        </div>
                        @if (!empty($agent) && $agent->master)
                            <div class="tab-pane fade mobile g-py-20 g-px-5" id="locks" role="tabpanel"
                                 aria-labelledby="locks-tab">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                                            <header
                                                class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                                                <div class="media">
                                                    <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                                        {{ _i('Providers locking') }}
                                                    </h3>
                                                </div>
                                            </header>
                                            <div class="card-block g-pa-15">
                                                <div
                                                    class="noty_bar noty_type__warning noty_theme__unify--v1--dark g-mb-25">
                                                    <div class="noty_body">
                                                        <div class="g-mr-20">
                                                            <div class="noty_body__icon">
                                                                <i class="hs-admin-info"></i>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <p>
                                                                {{ _i('The provider lock locks the agent and its entire tree') }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <form action="{{ route('agents.block-agent-data') }}"
                                                      id="lock-agent-form" method="post">
                                                    <div class="row">
                                                        <input type="hidden" name="user" class="user">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="maker">{{ _i('Maker') }}</label>
                                                                <select name="maker" id="maker"
                                                                        class="form-control"
                                                                        data-route="{{ route('core.categories-by-maker') }}">
                                                                    <option value="">{{ _i('Select...') }}</option>
{{--                                                                    @foreach ($makers as $maker)--}}
{{--                                                                        <option value="{{ $maker->maker }}">--}}
{{--                                                                            {{ $maker->maker }}--}}
{{--                                                                        </option>--}}
{{--                                                                    @endforeach--}}
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="category">{{ _i('Categories') }}</label>
                                                                <select name="category" id="category"
                                                                        class="form-control">
                                                                    <option value="">{{ _i('Select...') }}</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <button type="button"
                                                                    class="btn u-btn-3d u-btn-primary btn-block"
                                                                    id="lock-agent"
                                                                    data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Please wait...') }}">
                                                                {{ _i('Lock') }}
                                                            </button>
                                                        </div>
                                                        <div class="col-6">
                                                            <button type="button"
                                                                    class="btn u-btn-3d u-btn-blue btn-block"
                                                                    id="unlock-agent"
                                                                    data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Please wait...') }}">
                                                                {{ _i('Unlock') }}
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                                            <header
                                                class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                                                <div class="media">
                                                    <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                                        {{ _i('Users locking') }}
                                                    </h3>
                                                </div>
                                            </header>
                                            <div class="card-block g-pa-15">
                                                <div
                                                    class="noty_bar noty_type__warning noty_theme__unify--v1--dark g-mb-25">
                                                    <div class="noty_body">
                                                        <div class="g-mr-20">
                                                            <div class="noty_body__icon">
                                                                <i class="hs-admin-info"></i>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <p>
                                                                {{ _i('The user locking locks the agent and his entire tree') }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <form action="{{ route('agents.block-agent-data') }}"
                                                      id="lock-user-form" method="post">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label
                                                                    for="description">{{ _i('Description of the lock') }}</label>
                                                                <textarea name="description" id="description" cols="30"
                                                                          rows="5" class="form-control"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <input type="hidden" name="user" class="user">
                                                        <div class="col-6">
                                                            <button type="button"
                                                                    class="btn u-btn-3d u-btn-primary btn-block"
                                                                    id="lock-users"
                                                                    data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Please wait...') }}">
                                                                {{ _i('Lock') }}
                                                            </button>
                                                        </div>
                                                        <div class="col-6">
                                                            <button type="button"
                                                                    class="btn u-btn-3d u-btn-blue btn-block"
                                                                    id="unlock-users"
                                                                    data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Please wait...') }}">
                                                                {{ _i('Unlock') }}
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="tab-pane fade mobile g-py-20 g-px-5" id="connect" role="tabpanel"
                             aria-labelledby="connect-tab">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row g-mb-15">
                                        <div
                                            class="col-4 col-sm-4 col-md-3 g-mb-5 g-mb-0--md g-mb-10 align-self-center">
                                            <label class="g-mb-0">
                                                <strong>{{ _i('Username') }}</strong>
                                            </label>
                                        </div>
                                        <div class="col-4 col-sm-7 col-md-7 align-self-center">
                                            <div class="form-group g-pos-rel g-mb-0">
                                                <span id="username"></span>
                                            </div>
                                        </div>
                                        <div class="col-4 col-sm-2 col-md-2 align-self-center" id="modals-transaction">
                                            <div class="d-block d-sm-block d-md-none">
                                                <div class="row">
                                                    <div class="form-group mb-0">
                                                        @can('access', [\Dotworkers\Security\Enums\Permissions::$agents_credit_transactions])
                                                            <label href="#transaction-modal"
                                                                   class="btn u-btn-3d u-btn-blue" data-toggle="modal"
                                                                   data-transaction-type="{{ \Dotworkers\Configurations\Enums\TransactionTypes::$credit }}"
                                                                   data-transaction-name="{{ _i('credit') }}">
                                                                <i class="hs-admin-plus"></i>
                                                            </label>
                                                        @endcan
                                                        @can('access', [\Dotworkers\Security\Enums\Permissions::$agents_debit_transactions])
                                                            <label href="#transaction-modal"
                                                                   class="btn u-btn-3d u-btn-primary"
                                                                   data-toggle="modal"
                                                                   data-transaction-type="{{ \Dotworkers\Configurations\Enums\TransactionTypes::$debit }}"
                                                                   data-transaction-name="{{ _i('debit') }}">
                                                                <i class="hs-admin-layout-line-solid"></i>
                                                            </label>
                                                        @endcan
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                                @can('access', [\Dotworkers\Security\Enums\Permissions::$agents_credit_transactions,  \Dotworkers\Security\Enums\Permissions::$agents_debit_transactions])
                                    <div class="col-md-6">
                                        <div class="d-none d-sm-none d-md-block">
                                            <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30 d-none"
                                                 id="transactions-form-container">
                                                <header
                                                    class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                                                    <div class="media">
                                                        <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                                            {{ _i('Balance adjustments') }}
                                                        </h3>
                                                    </div>
                                                </header>
                                                <div class="card-block g-pa-15">
                                                    <form action="{{ route('agents.perform-transactions') }}"
                                                          id="transactions-form" method="post">
                                                        <input type="hidden" name="wallet" id="wallet">
                                                        <input type="hidden" name="user" class="user">
                                                        <input type="hidden" name="type" id="type">
                                                        <div class="form-group">
                                                            <label for="amount">{{ _i('Amount') }}</label>
                                                            <input type="number" name="amount" id="amount"
                                                                   class="form-control" min="0">
                                                        </div>
                                                        <div class="row">
                                                            @can('access', [\Dotworkers\Security\Enums\Permissions::$agents_credit_transactions])
                                                                <div class="col-6">
                                                                    <button type="button"
                                                                            class="btn u-btn-3d u-btn-blue btn-block"
                                                                            id="credit"
                                                                            data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Please wait...') }}">
                                                                        {{ _i('Credit') }}
                                                                    </button>
                                                                </div>
                                                            @endcan
                                                            @can('access', [\Dotworkers\Security\Enums\Permissions::$agents_debit_transactions])
                                                                <div class="col-6">
                                                                    <button type="button"
                                                                            class="btn u-btn-3d u-btn-primary btn-block"
                                                                            id="debit"
                                                                            data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Please wait...') }}">
                                                                        {{ _i('Debit') }}
                                                                    </button>
                                                                </div>
                                                            @endcan
                                                        </div>
                                                        <br>
                                                        <div class="row">
                                                            <div class="col-6" id="ticket">
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if (!empty($agent) && $agent->master)
        {{--        TODO THE OPTION TO CREATE AGENT IS DISABLED WITHIN THE DASHBOARD--}}
        {{--        @include('back.agents.modals.add-agents')--}}
        @include('back.agents.modals.update-percentage')
    @endif
    @include('back.agents.modals.manual-transaction')
    @include('back.agents.modals.move-agents')
    @include('back.agents.modals.move-agents-users')
    @include('back.agents.modals.details-user')
    {{--        TODO THE OPTION TO CREATE USER IS DISABLED WITHIN THE DASHBOARD--}}
    {{--    @include('back.agents.modals.add-users')--}}
    @include('back.users.modals.reset-password')
@endsection

@section('scripts')
    <script>
        $(function () {
            let agents = new Agents();
            let users = new Users();
            users.usersIps();
            //TODO TABLA PARA IPS EN EL MODAL
            //users.userIpsDetails();

            agents.dashboard();
            agents.searchAgentDashboard();
            agents.performTransactions();
            agents.manualTransactionsModal();
            //agents.agentsTransactions();
            agents.agentsTransactionsPaginate([50, 100, 500, 1000, 2000]);
            agents.usersTransactions([50, 100, 500, 1000, 2000]);
            agents.users();
            agents.agents();
            //TODO THE OPTION TO CREATE IS DISABLED WITHIN THE DASHBOARD
            // agents.storeAgents();
            // agents.storeUsers();
            agents.changeUserStatus();
            agents.changeEmailAgent();
            users.resetPassword();
            agents.financialState();
            agents.lockProvider();
            agents.moveAgentUser();
            agents.moveAgent();
            agents.optionsFormUser();
            agents.optionsFormAgent();
            agents.menuMobile();
            agents.selectAgentOrUser('{{ _i('Agents search...') }}');
            agents.selectUsernameSearch('{{ _i('Agents search...') }}');
            agents.selectCategoryMaker();
            agents.statusFilter();
            @if(!empty($agent) && $agent->master)
            agents.changeAgentType();
            @endif
            agents.relocationAgents();
            //agents.detailsUserModal();
        });
    </script>
    <script>
        function treePro() {
            let listUsers;
            let listMakers;
            let idCurrentUser = $('#tree-pro-master').data('idtreepro');

            $.ajax({
                url: '{{ route('agents.get.tree.users') }}',
                type: 'get',
                dataType: 'json',
            }).done(function (data) {
                if (data.status === 'OK') {
                    listUsers = data.data.tree;
                    listMakers = data.data.makers;
                    scanSearch(idCurrentUser);
                    drawMakers();
                    $('#tree-pro-master').find('a.jstree-anchor').click();
                } else {
                    console.log('Error al consultar usuarios',data)
                }
            }).fail(function () {
                Swal.fire(
                    'Ha ocurrido un error inesperado',
                    'Recarga o intenta de nuevo mas tarde.',
                    'error'
                )
            }).always(function () {

            });

            function scanSearch(id) {
                let users = listUsers.filter(user => user.owner_id === id);
                let usersTemp;
                let userHtmlTempMini = '';
                let usersHtmlTemp;
                let last = '';
                let atm = false;
                let type_user;
                let atmIcon;
                let atmType;


                $.each(users, function (index, value) {
                    usersTemp = listUsers.filter(user => user.owner_id === value.id);

                    if (index + 1 === users.length) {
                        last = 'jstree-last';
                    }

                    switch(value.type_user) {
                        case 1:
                            type_user = 'agent';
                            atmIcon = 'fa-star';
                            atmType = 'agent';
                            break;
                        case 2:
                            type_user = 'agent';
                            atmIcon = 'fa-users';
                            atmType = 'agent';
                            break;
                        case 5:
                            type_user = 'user';
                            atmIcon = 'fa-user';
                            atmType = 'user';
                            break;
                        default:
                            type_user = 'user-null';
                            atmIcon = 'icon-null';
                            atmType = 'null'
                    }

                    if (usersTemp.length > 0) {
                        userHtmlTempMini = userHtmlTempMini + '<li class="jstree-node init_agent jstree-closed ' + last + '"><i class="jstree-icon jstree-ocl jstree-more" data-idtreepro="' + value.id + '" data-typetreepro="' + type_user + '" role="' + value.owner_id + '"></i><a class="jstree-anchor" href="javascript:void(0)"><i class="jstree-icon jstree-themeicon fa '+ atmIcon +' jstree-themeicon-custom" role="presentation"></i>' + value.username + '</a></li>';
                    } else {
                        userHtmlTempMini = userHtmlTempMini + '<li class="jstree-node init_'+ atmType +' jstree-leaf ' + last + '"><i class="jstree-icon jstree-ocl" data-idtreepro="' + value.id + '" data-typetreepro="' + type_user + '" role="' + value.owner_id + '"></i><a class="jstree-anchor" href="javascript:void(0)"><i class="jstree-icon jstree-themeicon fa '+ atmIcon +' jstree-themeicon-custom" role="presentation"></i>' + value.username + '</a></li>';
                    }
                })

                usersHtmlTemp = '<ul role="group" class="jstree-children">' + userHtmlTempMini + '</ul>';


                $('[data-idtreepro="' + id + '"]').parent().append(usersHtmlTemp);
            }

            function drawMakers() {
                let makers = listMakers;

                $('#maker option[value!=""]').remove();
                if(makers.length > 0){
                    $.each(makers, function (index, val) {
                        $('#maker').append("<option value=" + val.maker + ">" + val.maker + "</option>");

                    });
                }
            }

            $(document).on('click', '.jstree-more', function () {
                let $this = $(this);
                let $obj = $this.parent();

                if ($obj.hasClass('jstree-open')) {
                    $obj.removeClass('jstree-open');
                    $obj.addClass('jstree-closed');
                    $obj.find('.jstree-children').remove();
                } else {
                    $obj.removeClass('jstree-closed');
                    $obj.addClass('jstree-open');
                    scanSearch($this.data('idtreepro'));
                }
            });

            $(document).on('click', 'a.jstree-anchor', function (){
                let $this = $(this);
                let type = $this.parent().find('.jstree-icon.jstree-ocl').eq(0).data('typetreepro');
                let id = $this.parent().find('.jstree-icon.jstree-ocl').eq(0).data('idtreepro');
                let $container = $('#tree-pro');

                $('a.jstree-anchor').removeClass('jstree-clicked');
                $this.addClass('jstree-clicked');

                $.ajax({
                    url: $container.data('route'),
                    type: 'get',
                    dataType: 'json',
                    data: {
                        id, type
                    }

                }).done(function (json) {
                    //TODO Init Set Modal
                    $('.userSet').text(json.data.user.username);
                    $('.emailSet').text(json.data.user.email);
                    $('.fatherSet').text(json.data.father);
                    $('.typeSet').text(json.data.user.typeSet);
                    $('.createdSet').text(json.data.user.created);
                    $('.cantA_P').show();
                    $('.cantA_P').show();
                    if (json.data.type != "agent") {
                        $('.cantA_P').hide();
                        $('.cantA_P').hide();
                    }
                    // $('.agentsSet').text(json.data.cant_agents);
                    // $('.playersSet').text(json.data.cant_players);
                    // let initUl = '';
                    // let finishUl = '';
                    // $.each(json.data.fathers,function(index,val) {
                    //     initUl = initUl + '<ul style="margin-left: -13%!important;"><li><strong>'+val.username+'</strong>'
                    //     finishUl = finishUl + '</li></ul>'
                    // });
                    // $('.appendTreeFather').append(initUl+finishUl);

                    setTimeout(function () {
                        Agents.getFatherRecursive($('#details-user-get').data('route'), id, type);
                    }, 500)
                    //TODO Finish Set Modal

                    $('#username').text(json.data.user.username);
                    $('#agent_timezone').text(json.data.user.timezone);
                    $('.balance').text(json.data.balance);
                    $('.balanceAuth_' + json.data.user.id).text('');
                    $('.balanceAuth_' + json.data.user.id).text(json.data.balance);
                    $('#user_type').html(json.data.user.type);
                    $('#status').html(json.data.user.status);
                    $('#wallet').val(json.data.wallet);
                    $('.wallet').val(json.data.wallet);
                    $('.user').val(id);
                    $('#name').val(json.data.user.username);
                    $('#type').val(json.data.type);
                    $('.type').val(json.data.type);
                    $('#referral_code').text(json.data.user.referral_code);
                    $('.clipboard').attr('data-clipboard-text', json.data.user.url);

                    if (json.data.master) {
                        $('#agents-tab').removeClass('d-none');
                        $('#agents-mobile').removeClass('d-none');
                        $('#move-agents').removeClass('d-none');
                    } else {
                        $('#agents-tab').addClass('d-none');
                        $('#agents-mobile').addClass('d-none');
                        $('#move-agents').addClass('d-none');
                    }

                    if (json.data.agent) {
                        $('#users-tab').removeClass('d-none');
                        $('#agents-transactions-tab').removeClass('d-none');
                        $('#financial-state-tab').removeClass('d-none');
                        $('#users-transactions-tab').addClass('d-none');
                        $('#users-mobile').removeClass('d-none');
                        $('#agents-transactions-mobile').removeClass('d-none');
                        $('#financial-state-mobile').removeClass('d-none');
                        $('#users-transactions-mobile').addClass('d-none');
                        $('#move-agents-user').addClass('d-none');
                        $('#move-agents').removeClass('d-none');
                    } else {
                        $('#users-tab').addClass('d-none');
                        $('#agents-transactions-tab').addClass('d-none');
                        $('#financial-state-tab').addClass('d-none');
                        $('#users-transactions-tab').removeClass('d-none');
                        $('#users-mobile').addClass('d-none');
                        $('#agents-transactions-mobile').addClass('d-none');
                        $('#financial-state-mobile').addClass('d-none');
                        $('#users-transactions-mobile').removeClass('d-none');
                        $('#move-agents-user').removeClass('d-none');
                        $('#move-agents').addClass('d-none');
                    }

                    if (json.data.myself) {
                        if (!json.data.agent_player) {
                            $('#new-user, #new-agent').addClass('d-none');
                        } else {
                            $('#new-user, #new-agent').removeClass('d-none');
                        }
                        $('#locks, #locks-tab').addClass('d-none');
                        $('#locks, #locks-mobile').addClass('d-none');
                        $('#transactions-form-container').addClass('d-none');
                        $('#modals-transaction').addClass('d-none');
                        $('#move-agents-user').addClass('d-none');
                        $('#move-agents').addClass('d-none');
                    } else {
                        $('#new-user, #new-agent').addClass('d-none');
                        $('#locks, #locks-tab').removeClass('d-none');
                        $('#locks, #locks-mobile').removeClass('d-none');
                        $('#transactions-form-container').removeClass('d-none');
                        $('#modals-transaction').removeClass('d-none');
                    }

                }).fail(function (json) {
                    swalError(json);
                });
            })

            $('#tree-pro-init').find('.jstree-anchor').eq(0).click();
        }

        treePro();
    </script>
@endsection

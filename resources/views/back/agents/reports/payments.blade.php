@extends('back.template')

@section('styles')
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

    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 col-xl-12">
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
                                {{--<button type="button" data-route="{{ route('agents.tree-filter', [1]) }}" data-status="1"--}}
                                {{--       class="btn u-btn-3d u-btn-teal g-mr-5 status_filter g-rounded-50"--}}
                                {{--       data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Updating') }}" id="active-status">--}}
                                {{--   <i class="hs-admin-check"></i>--}}
                                {{--</button>--}}
                                {{--<button type="button" data-route="{{ route('agents.tree-filter', [0]) }}" data-status="0"--}}
                                {{--        class="btn u-btn-3d u-btn-primary g-mr-5 status_filter g-rounded-50"--}}
                                {{--       data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Updating') }}" id="inactive-status">--}}
                                {{--   <i class="hs-admin-close"></i>--}}
                                {{--</button>--}}
                            </div>
                        </div>
                    </div>
                </header>
                <div class="card-block g-pa-15">
                    <div class="d-block d-sm-block d-md-none g-pa-10">
                        <div class="row">
                            <div class="col-12 g-py-5 g-pa-5">
                                @if(!in_array(\Dotworkers\Security\Enums\Roles::$admin_Beet_sweet, session('roles')))
                                    <select name="agent_id_search" id="username_search"
                                            class="form-control select2 username_search agent_id_search"
                                            data-route="{{ route('agents.search-username')}}"
                                            data-select="{{ route('agents.find-user') }}">
                                        <option></option>
                                    </select>
                                @endif

                            </div>
                            <div class="col-6 g-py-5">
                                <a href="#add-users-modal" data-toggle="modal"
                                   class="btn u-btn-3d u-btn-primary btn-block" id="new-user">
                                    <i class="hs-admin-plus"></i>
                                    {{ _i(' Player') }}
                                </a>
                            </div>
                            @if ($agent->master)
                                <div class="col-6 g-py-5">
                                    <a href="#add-agents-modal" data-toggle="modal"
                                       class="btn u-btn-3d u-btn-blue btn-block" id="new-agent">
                                        <i class="hs-admin-plus"></i>
                                        {{ _i(' Agent') }}
                                    </a>
                                </div>
                            @endif
                            {{--<div class="col-6 g-py-5">--}}
                            {{--   <button type="button" data-route="{{ route('agents.tree-filter', [1]) }}" data-status="1"--}}
                            {{--           class="btn u-btn-3d u-btn-teal g-mr-10 btn-block status_filter"--}}
                            {{--           data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Updating') }}" id="active-status">--}}
                            {{--        <i class="hs-admin-check"></i>--}}
                            {{--       {{ _i('Active') }}--}}
                            {{--    </button>--}}
                            {{--</div>--}}
                            {{--<div class="col-6 g-py-5">--}}
                            {{--    <button type="button" data-route="{{ route('agents.tree-filter', [0]) }}" data-status="0"--}}
                            {{--            class="btn u-btn-3d u-btn-primary g-mr-10 btn-block status_filter"--}}
                            {{--            data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Updating') }}" id="inactive-status">--}}
                            {{--       <i class="hs-admin-close"></i>--}}
                            {{--       {{ _i('Inactive') }}--}}
                            {{--    </button>--}}
                            {{--</div>--}}
                        </div>
                    </div>
                    <div class="">
                        <div id="tree" data-route="{{ route('agents.find') }}" data-json="{{ $tree }}"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    @include('back.layout.litepicker')
    <div class="row">
        <div class="col-xl-12">
            <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                <header class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                    <div class="media">
                        <h3 class="d-flex align-self-center text-uppercase g-font-size-12 g-font-size-default--md g-color-black mb-0">
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
                        <table class="table table-bordered w-100" id="bonus-transactions-table"
                               data-route="{{ route('reports.financial.bonus-transactions-data') }}">
                            <thead>
                            <tr>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('ID') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Username') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Operator') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Description') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Amount') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Registered') }}
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
    </div>

@endsection

@section('scripts')
    <script>
        $(function () {
            let agents = new Agents();
            let users = new Users();
            users.usersIps();
            agents.dashboard();
            agents.searchAgentDashboard();
            agents.performTransactions();
            agents.manualTransactionsModal();
            //agents.agentsTransactions();
            agents.agentsTransactionsPaginate([20, 50, 100, 500, 1000]);
            agents.usersTransactions();
            agents.users();
            agents.agents();
            agents.storeAgents();
            agents.storeUsers();
            agents.changeUserStatus();
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
            agents.statusFilter();
            @if($agent->master)
            agents.changeAgentType();
            @endif
            agents.relocationAgents();
        });
    </script>
@endsection

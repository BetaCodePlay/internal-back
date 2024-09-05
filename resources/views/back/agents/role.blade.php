@extends('back.template')

@section('styles')

@endsection

@section('content')
    <div class="wrapper-title g-pb-30">
        {{ _i('Role and permission management') }}
    </div>

    <div class="page-role" data-id="{{ $authUser->id }}">
        <div class="page-top">
            <form class="search-input" autocomplete="destroy">
                <i class="fa-solid fa-magnifying-glass"></i>
                <select class="form-control roleUsernameSearch" placeholder="{{ _i('Search') }}"
                        data-route="{{ route('agents.search-username')}}"
                        data-redirect="{{ route('agents.find-user') }}">
                    <option></option>
                </select>
            </form>
            <button type="button" class="btn btn-theme" data-toggle="modal" data-target="#role-create"
                    data-value="true"><i class="fa-solid fa-plus"></i> {{ _i('Create role') }}</button>
        </div>
        <div class="nav-roles">
            <ul class="nav nav-tabs" id="roleTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link tab-role" data-toggle="tab" data-target="#roleTabProfileManager"
                            type="button" role="tab" aria-controls="roleTabProfileManager" aria-selected="false">
                        {{ _i('My data') }}
                    </button>
                </li>
                @if($authUser->agentType !== 5)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link tab-role" data-toggle="tab" data-target="#roleTabMoreInformation"
                                type="button" role="tab" aria-controls="roleTabMoreInformation" aria-selected="false">
                            {{ _i('Roles') }}
                        </button>
                    </li>
                @endif
                <li class="nav-item" role="presentation">
                    <button class="nav-link tab-role" data-toggle="tab" data-target="#roleTabTransactions" type="button"
                            role="tab" aria-controls="roleTabTransactions" aria-selected="false">
                        {{ _i('Transactions') }}
                    </button>
                </li>
                @if($authUser->agentType === 5)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link tab-role" data-toggle="tab" data-target="#roleTabBets" type="button"
                                role="tab" aria-controls="roleTabBets" aria-selected="false">
                            {{ _i('Bets') }}
                        </button>
                    </li>
                @endif
                <!--                <li class="nav-item" role="presentation">
                    <button class="nav-link tab-role" data-toggle="tab" data-target="#roleTabLocks" type="button" role="tab" aria-controls="roleTabLocks" aria-selected="false">
                        {{ _i('Providers') }}
                </button>
            </li>-->
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade" id="roleTabProfileManager" role="tabpanel" aria-labelledby="profile-tab">
                    <div class="tab-content-body">
                        <div class="tab-content-title">{{ _i('Activity') }}</div>
                        <div class="tab-manager">
                            <div class="tab-manager-top">
                                <div class="tab-manager-data">
                                    <div class="data-title">{{ _i('Balance') }}</div>
                                    <div
                                        class="data-text text-id"><span id="role-balance-refresh">{{ $authUser->balanceUser }}</span> {{ session('currency') == 'VEF' ? $free_currency->currency_name : session('currency') }}
                                        @if($authUser->status)
                                            <span class="separator"></span>
                                            @if(auth()->user()->id !== $authUser->id)
                                                <button class="btn btn-theme btn-xs currentDataRole" data-toggle="modal"
                                                        data-target="#role-balance"
                                                        data-userid="{{ $authUser->id}}"
                                                        data-username="{{ $authUser->username }}"
                                                        data-rol="{{ $authUser->agentType }}">{{ _i('Adjustment') }}
                                                </button>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                <div class="tab-manager-data">
                                    <div class="data-title">{{ _i('Profit') }}</div>
                                    <div class="data-text text-id">
                                        {{ $profit }} <span class="number">{{ $authUser->percentage }}%</span>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-manager-bottom">
                                <div class="tab-manager-data">
                                    <div class="data-title">{{ _i('Number of dependent agents') }}</div>

                                    @if(auth()->user()->id === 4)
                                        <div class="data-text-inline" style="display: none">
                                            <span class="name">{{ _i('Master') }}</span>
                                            <span class="number">{{ $agent?->masterQuantity ?? '0.00' }}</span>
                                        </div>
                                        <div class="data-text-inline" style="display: none">
                                            <span class="name">{{ _i('Support') }}</span>
                                            <span class="number">{{ $agent?->cashierQuantity ?? '0.00' }}</span>
                                        </div>
                                        <div class="data-text-inline" style="display: none">
                                            <span class="name">{{ _i('Players') }}</span>
                                            <span class="number">{{ $agent?->playerQuantity ?? '0.00' }}</span>
                                        </div>

                                        <div class="data-text-inline">
                                            <button class="btn btn-theme btn-xs" id="btn-show-dependent" data-route="">{{ _i('Show') }}</button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-content-body">
                        <div class="tab-content-title">{{ _i('Account info') }}</div>
                        <div class="tab-manager">
                            <div class="tab-manager-top">
                                <div class="tab-manager-data">
                                    <div class="data-title">{{ _i('Name') }}</div>
                                    <div class="data-text">{{ $authUser->username }} <span
                                            class="separator"></span><span
                                            class="deco-role">{{ $authUser->type_user }}</span></div>
                                </div>
                                <div class="tab-manager-data">
                                    <div class="data-title">{{ _i('ID User') }}</div>
                                    <div class="data-text text-id">{{ $authUser->id }} <span class="separator"></span>
                                        <button class="btn btn-theme btn-xs clipboard" data-title="{{ _i('Copied') }}"
                                                data-clipboard-text="{{ $authUser->id }}">{{ _i('Copy') }}</button>
                                    </div>
                                </div>
                                <div class="tab-manager-data">
                                    <div class="data-title">{{ _i('Created the') }}</div>
                                    <div class="data-text">{{ ($authUser->created_at)->format('d-m-Y ') }}</div>
                                </div>
                            </div>

                            <div class="tab-manager-bottom">
                                @if($authUser->status)
                                    <div class="tab-manager-data text-center">
                                        <div class="data-title">{{ _i('Password') }}</div>
                                        <div class="data-text">
                                            <button class="btn btn-theme btn-xs currentDataRole" data-toggle="modal"
                                                    data-target="#role-password-reset"
                                                    data-userid="{{ $authUser->id}}"
                                                    data-username="{{ $authUser->username }}"
                                                    data-rol="{{ $authUser->agentType }}">{{ _i('Reset') }}
                                            </button>
                                        </div>
                                    </div>
                                    @if(auth()->user()->id !== $authUser->id)
                                        <div class="tab-manager-data text-center">
                                            <div class="data-title">{{ _i('Account') }}</div>
                                            <div class="data-text">
                                                @if ($agent?->master)
                                                    <button class="btn btn-theme btn-xs currentDataRole" data-toggle="modal"
                                                            data-target="#role-modify"
                                                            data-userid="{{ $authUser->id}}"
                                                            data-username="{{ $authUser->username }}"
                                                            data-rol="{{ $authUser->agentType }}"
                                                            data-route="{{ route('agents.role.user-find') }}">{{ _i('Modify') }}
                                                    </button>
                                                @else
                                                    {{ _i('Cashier') }}
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                @endif

                                @if(auth()->user()->id !== $authUser->id)
                                    <div class="tab-manager-data">
                                        <div class="data-title">{{ _i('Father') }}</div>
                                        <div class="data-text">{{ $authUser->owner }}</div>
                                    </div>
                                @endif
                            </div>

                            <div class="tab-manager-bottom">
                                <div class="tab-manager-data">
                                    <div class="data-title">{{ _i('Status') }}</div>
                                    <div
                                        class="data-text text-status {{ !$authUser->status ? 'force-text-finish' : '' }}">
                                        @if(auth()->user()->id !== $authUser->id)
                                            <i class="fa-solid i-status fa-circle {{ $authUser->status ? 'green' : 'red' }}"></i> {{ $authUser->statusText }}
                                            <span class="separator"></span>
                                            <button class="btn btn-theme btn-xs currentDataRole" data-toggle="modal"
                                                    data-target="#role-lock"
                                                    data-lock="{{ _i('Lock profile') }}"
                                                    data-unlock="{{ _i('Unlock profile') }}"
                                                    data-value="{{ $authUser->status ? 'true' : 'false' }}"
                                                    data-type="{{ $authUser->action }}"
                                                    data-userid="{{ $authUser->id }}"
                                                    data-username="{{ $authUser->username }}"
                                                    data-rol="{{ $authUser->agentType }}">{{ $authUser->status ? _i('Lock') : _i('Unlock') }}
                                            </button>

                                        @else
                                            <span class="separator"> &nbsp;</span>
                                            <i class="fa-solid i-status fa-circle {{ $authUser->status ? 'green' : 'red' }}"></i> {{ $authUser->statusText }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-content-body-simple">
                        <div class="tab-content-title">{{ _i('Connection activity') }}</div>
                    </div>

                    <div class="tab-body">
                        <form autocomplete="destroy" class="table-load">
                            <table id="table-information" class="display nowrap"
                                   data-route="{{ route('users.user-ip-data') }}?userId={{ $authUser->id}}">
                                <thead>
                                <tr>
                                    <th data-priority="1">{{ _i('IP') }}</th>
                                    <th data-priority="2">{{ _i('Quantity') }}</th>
                                </tr>
                                </thead>
                            </table>
                        </form>
                        <div class="loading-style"></div>
                    </div>
                </div>
                @if($authUser->agentType !== 5)
                    <div class="tab-pane fade" id="roleTabMoreInformation" role="tabpanel"
                         aria-labelledby="information-tab">
                        <div class="tab-content-body-simple">
                            <div class="tab-content-title">
                                @if(auth()->user()->id !== $authUser->id)
                                    {{ _i('roles in charge of') }} <span class="text">{{ $authUser->username }}</span>
                                @else
                                    {{ _i('Roles in') }} <span class="text">{{ _i('my charge') }}</span>
                                @endif
                            </div>
                        </div>

                        @if($authUser->agentType !== 5)
                            <div class="page-body">
                                <form autocomplete="destroy" class="table-load">
                                    <table id="table-roles" class="display nowrap"
                                           data-route="{{ route('agents.get.direct.children') }}?draw=2&start=0&username={{ $username }}">
                                        <thead>
                                        <tr>
                                            <th data-priority="1">{{ _i('Name') }}</th>
                                            <th>{{ _i('Rol') }}</th>
                                            <th>{{ _i('ID User') }}</th>
                                            <th>{{ _i('Status') }}</th>
                                            <th data-priority="3">{{ _i('Balance') }}</th>
                                            <th data-priority="2"></th>
                                        </tr>
                                        </thead>
                                    </table>
                                </form>
                                <div class="loading-style"></div>
                            </div>
                        @endif
                    </div>
                @endif
                <div class="tab-pane fade" id="roleTabTransactions" role="tabpanel" aria-labelledby="transactions-tab">
                    <div class="tab-content-body">
                        <div class="tab-content-title">{{ _i('Daily movements') }}</div>
                        <form autocomplete="destroy" class="tab-form">
                            <div class="row">
                                <div class="col-12 col-form {{ $authUser->agentType === 5 ? 'col-lg-4' : 'col-lg-3' }}">
                                    <label>{{ _i('Action') }}</label>
                                    <select class="form-control" id="roleTabTransactionsAction">
                                        <option value="all">{{ _i('All') }}</option>
                                        <option value="credit">{{ _i('Credit') }}</option>
                                        <option value="debit">{{ _i('Debit') }}</option>
                                    </select>
                                </div>
                                <div class="col-12 col-lg-3 col-form {{ $authUser->agentType === 5 ? 'd-none' : '' }}">
                                    <label>{{ _i('User type') }}</label>
                                    <select class="form-control" id="roleTabTransactionsType">
                                        <option value="all">{{ _i('All') }}</option>
                                        <option value="agent">{{ _i('Agents') }}</option>
                                        <option value="user">{{ _i('Players') }}</option>
                                    </select>
                                </div>
                                <div class="col-12 col-form {{ $authUser->agentType === 5 ? 'col-lg-4' : 'col-lg-3' }}">
                                    <div class="form-group">
                                        <label>{{ _i('Date') }}</label>
                                        <input type="text" class="form-control" id="date_range_new" placeholder="">
                                    </div>
                                </div>
                                <div class="col-12 col-form {{ $authUser->agentType === 5 ? 'col-lg-4' : 'col-lg-3' }}">
                                    <div class="form-group">
                                        <label class="d-none d-lg-block">&nbsp;</label>
                                        <button type="button"
                                                class="btn btn-theme btn-block currentDataRole searchTransactionsRole"
                                                data-loading-text="<i class='fa fa-spin fa-spinner'></i> Searching..."
                                                data-userid="{{ $authUser->id}}"
                                                data-username="{{ $authUser->username }}"
                                                data-rol="{{ $authUser->agentType }}">
                                            {{ _i('Search') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="tab-body">
                        <form autocomplete="destroy" class="table-load">
                            <table id="table-transactions" class="display nowrap"
                                   data-route="{{ $authUser->agentType === 5 ? route('transactions.players') : route('transactions.agents') }}">
                                <thead>
                                <tr>
                                    <th data-priority="1">{{ _i('Date') }}</th>
                                    <th>{{ _i('Origin') }}</th>
                                    <th data-priority="3">{{ _i('Destination') }}</th>
                                    <th data-priority="2">{{ _i('Amount') }}</th>
                                    <th>{{ _i('Balance') }}</th>
                                </tr>
                                </thead>
                            </table>
                        </form>
                        <div class="loading-style"></div>
                    </div>
                </div>
                <!--                <div class="tab-pane fade" id="roleTabLocks" role="tabpanel" aria-labelledby="locks-tab">
                    <br>
                    <div class="text-center"><b>{{ _i('Coming soon') }}...</b></div>
                    <br>
                </div>-->
                @if($authUser->agentType === 5)
                    <div class="tab-pane fade" id="roleTabBets" role="tabpanel" aria-labelledby="bet-tab">
                        <div class="tab-body">
                            <form autocomplete="destroy" class="table-load">
                                <table id="table-bets" class="display nowrap" data-route="{{ route('wallets.transactions.assiria') }}?userId={{ $authUser->id}}">
                                    <thead>
                                    <tr>
                                        <th data-priority="3">{{ _i('N') }}</th>
                                        <th data-priority="1">{{ _i('Fecha') }}</th>
                                        <th>{{ _i('ID') }}</th>
                                        <th>{{ _i('Concept') }}</th>
                                        <th data-priority="2">{{ _i('Amount') }}</th>
                                        <th>{{ _i('Description') }}</th>
                                    </tr>
                                    </thead>
                                </table>
                            </form>
                            <div class="loading-style"></div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="d-none" id="user-buttons">
            <div class="d-inline-block dropdown">
                <button class="btn dropdown-toggle" type="button" id="dropdownRoleProfile" data-toggle="dropdown"
                        aria-expanded="false">
                    <i class="fa-solid fa-ellipsis-vertical"></i>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownRoleProfile">
                    <li><a class="dropdown-item currentDataRole" href="javascript:void(0)" data-toggle="modal"
                           data-target="#role-create">{{ _i('Add role') }}</a></li>
                    <li><a class="dropdown-item currentDataRole" href="javascript:void(0)" data-toggle="modal"
                           data-target="#role-password-reset">{{ _i('Reset password') }}</a></li>
                    <li><a class="dropdown-item currentDataRole" href="javascript:void(0)" data-toggle="modal"
                           data-target="#role-lock"
                           data-lock="{{ _i('Lock profile') }}"
                           data-unlock="{{ _i('Unlock profile') }}"
                           data-rol=""
                           data-value=""
                           data-type="">
                        </a>
                    </li>
                    <li><a class="dropdown-item currentDataRole" href="javascript:void(0)" data-toggle="modal"
                           data-target="#role-balance">{{ _i('Balance adjustment') }}</a></li>
                    <li><a class="dropdown-item currentDataRole" href="javascript:void(0)" data-toggle="modal"
                           data-target="#role-modify"
                           data-route="{{ route('agents.role.user-find') }}">{{ _i('Modify') }}</a></li>
                </ul>
            </div>

            <a href="" class="btn btn-href" target="_blank"><i class="fa-solid fa-chevron-right"></i></a>
        </div>
    </div>
    <div class="d-none" id="globalActionID" data-userid="" data-username=""></div>
@endsection

@section('modals')
    @include('back.agents.modals.role-password-reset')
    @include('back.agents.modals.role-balance')
    @include('back.agents.modals.role-create')
    @include('back.agents.modals.role-modify')
    @include('back.agents.modals.role-lock')
@endsection

@section('scripts')
    <script>
        $(function () {
            let roles = new Roles();
            roles.initTableRoles();
            roles.userSearch("{{ _i('Search user') }}...", "{{ _i('Write more than 3 characters') }}...", 3);
            roles.userResetPassword();
            roles.userBalance();
            roles.userCreate();
            roles.userModify();
            roles.userLock();
            roles.userDependent();
            roles.tabsTablesSection();
        });
    </script>
@endsection

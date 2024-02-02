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
                <select class="form-control roleUsernameSearch" placeholder="{{ _i('Search') }}" data-route="{{ route('agents.search-username')}}" data-redirect="{{ route('agents.find-user') }}">
                    <option></option>
                </select>
            </form>
            <button type="button" class="btn btn-theme" data-toggle="modal" data-target="#role-create" data-value="true"><i class="fa-solid fa-plus"></i> {{ _i('Create role') }}</button>
        </div>
        <div class="nav-roles">
            <ul class="nav nav-tabs" id="roleTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active tab-role" data-toggle="tab" data-target="#roleTabProfileManager" type="button" role="tab" aria-controls="roleTabProfileManager" aria-selected="true">
                        {{ _i('Profile management') }}
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link tab-role" data-toggle="tab" data-target="#roleTabTransactions" type="button" role="tab" aria-controls="roleTabTransactions" aria-selected="false">
                        {{ _i('Transactions') }}
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link tab-role" data-toggle="tab" data-target="#roleTabMoreInformation" type="button" role="tab" aria-controls="roleTabMoreInformation" aria-selected="false">
                        {{ _i('More information') }}
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link tab-role" data-toggle="tab" data-target="#roleTabLocks" type="button" role="tab" aria-controls="roleTabLocks" aria-selected="false">
                        {{ _i('Locks') }}
                    </button>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="roleTabProfileManager" role="tabpanel" aria-labelledby="profile-tab">
                    <div class="tab-manager">
                        <div class="tab-manager-top">
                            <div class="tab-manager-data">
                                <div class="data-title">{{ _i('Name') }}</div>
                                <div class="data-text">{{ $authUser->username }} <span class="separator"></span><span class="deco-role">{{ $authUser->type_user }}</span></div>
                            </div>
                            <div class="tab-manager-data">
                                <div class="data-title">{{ _i('ID User') }}</div>
                                <div class="data-text text-id">{{ $authUser->id }} <span class="separator"></span>
                                    <button class="btn btn-theme btn-xs clipboard" data-title="{{ _i('Copied') }}" data-clipboard-text="{{ $authUser->id }}">{{ _i('Copy') }}</button>
                                </div>
                            </div>
                            <div class="tab-manager-data">
                                <div class="data-title">{{ _i('Status') }}</div>
                                <div class="data-text text-status {{ !$authUser->status ? 'force-text-finish' : '' }}">
                                    @if(auth()->user()->id !== $authUser->id)
                                        <i class="fa-solid i-status fa-circle {{ $authUser->status ? 'green' : 'red' }}"></i> {{ $authUser->statusText }}
                                        <span class="separator"></span>
                                        <button class="btn btn-theme btn-xs currentDataRole" data-toggle="modal" data-target="#role-lock"
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

                        <div class="tab-manager-bottom">
                            <div class="tab-manager-data">
                                <div class="data-title">{{ _i('Balance') }}</div>
                                <div class="data-text text-id">{{ $authUser->balanceUser }} {{ session('currency') == 'VEF' ? $free_currency->currency_name : session('currency') }}
                                    @if($authUser->status)
                                        <span class="separator"></span>
                                        @if(auth()->user()->id !== $authUser->id)
                                            <button class="btn btn-theme btn-xs currentDataRole" data-toggle="modal" data-target="#role-balance"
                                                    data-userid="{{ $authUser->id}}"
                                                    data-username="{{ $authUser->username }}"
                                                    data-rol="{{ $authUser->agentType }}">{{ _i('Adjustment') }}
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </div>

                            @if($authUser->status)
                                <div class="tab-manager-data text-center">
                                    <div class="data-title">{{ _i('Password') }}</div>
                                    <div class="data-text">
                                        <span class="separator">
                                            @if(auth()->user()->id !== $authUser->id)
                                                &nbsp;
                                            @endif
                                        </span>
                                        <button class="btn btn-theme btn-xs currentDataRole" data-toggle="modal" data-target="#role-password-reset"
                                                data-userid="{{ $authUser->id}}"
                                                data-username="{{ $authUser->username }}"
                                                data-rol="{{ $authUser->agentType }}">{{ _i('Reset') }}
                                        </button>
                                    </div>
                                </div>

                                <div class="tab-manager-data text-center">
                                    <div class="data-title">{{ _i('Account') }}</div>
                                    <div class="data-text">
                                        <span class="separator">
                                             @if(auth()->user()->id !== $authUser->id)
                                                &nbsp;
                                            @endif
                                        </span>
                                        <button class="btn btn-theme btn-xs currentDataRole" data-toggle="modal" data-target="#role-modify"
                                                data-userid="{{ $authUser->id}}"
                                                data-username="{{ $authUser->username }}"
                                                data-rol="{{ $authUser->agentType }}"
                                                data-route="{{ route('agents.role.user-find') }}">{{ _i('Modify') }}
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
                <div class="tab-pane fade" id="roleTabTransactions" role="tabpanel" aria-labelledby="transactions-tab">
                    <form autocomplete="destroy" class="tab-form">
                        <div class="row">
                            <div class="col-12 col-lg-3 col-form">
                                <label>{{ _i('Action') }}</label>
                                <select class="form-control">
                                    <option value="">{{ _i('All') }}</option>
                                    <option value="">{{ _i('Accredited') }}</option>
                                    <option value="">{{ _i('Discredited') }}</option>
                                </select>
                            </div>
                            <div class="col-12 col-lg-3 col-form">
                                <label>{{ _i('User type') }}</label>
                                <select class="form-control">
                                    <option value="">{{ _i('All') }}</option>
                                    <option value="">{{ _i('Agents') }}</option>
                                    <option value="">{{ _i('Players') }}</option>
                                </select>
                            </div>
                            <div class="col-12 col-lg-3 col-form">
                                <div class="form-group">
                                    <label>{{ _i('Date') }}</label>
                                    <input type="text" class="form-control" id="date_range_new" placeholder="">
                                </div>
                            </div>
                            <div class="col-12 col-lg-3 col-form">
                                <div class="form-group">
                                    <label class="d-none d-lg-block">&nbsp;</label>
                                    <button type="button" class="btn btn-theme btn-block" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Searching...">
                                        {{ _i('Search') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="tab-body">
                        <form autocomplete="destroy" class="col table-load">
                            <table id="table-transactions" class="display nowrap" data-route="{{ route('transactions.agents') }}">
                                <thead>
                                <tr>
                                    <th>{{ _i('Date') }}</th>
                                    <th data-priority="3">{{ _i('Origin') }}</th>
                                    <th data-priority="1">{{ _i('Destination') }}</th>
                                    <th data-priority="2">{{ _i('Amount') }}</th>
                                    <th>{{ _i('Balance') }}</th>
                                </tr>
                                </thead>
                            </table>
                        </form>
                        <div class="loading-style"></div>
                    </div>
                </div>
                <div class="tab-pane fade" id="roleTabMoreInformation" role="tabpanel" aria-labelledby="information-tab">
                    <div class="tab-manager">
                        <div class="tab-manager-top">
                            <div class="tab-manager-data">
                                <div class="data-title">{{ _i('Created the') }}</div>
                                <div class="data-text">{{ ($authUser->created_at)->format('d-m-Y ') }}</div>
                            </div>
                            @if(auth()->user()->id !== $authUser->id)
                                <div class="tab-manager-data">
                                    <div class="data-title">{{ _i('Father') }}</div>
                                    <div class="data-text">{{ $authUser->owner }}</div>
                                </div>
                            @endif
                            <div class="tab-manager-data">
                                <div class="data-title">{{ _i('Percentage') }}</div>
                                <div class="data-text text-finish">{{ $authUser->percentage }}%</div>
                            </div>
                        </div>

                        <div class="tab-manager-data">
                            <div class="data-title">{{ _i('Number of dependent agents') }}</div>
                            <div class="data-text-inline"><span class="name">{{ _i('Master') }}</span> <span class="number">{{ $agent?->masterQuantity ?? '0.00' }}</span></div>
                            <div class="data-text-inline"><span class="name">{{ _i('Support') }}</span> <span class="number">{{ $agent?->cashierQuantity ?? '0.00' }}</span></div>
                            <div class="data-text-inline"><span class="name">{{ _i('Players') }}</span> <span class="number">{{ $agent?->playerQuantity ?? '0.00' }}</span></div>
                        </div>
                    </div>

                    <div class="tab-body">
                        <form autocomplete="destroy" class="col table-load">
                            <table id="table-information" class="display nowrap" data-route="{{ route('users.user-ip-data') }}?userId={{ $authUser->id}}">
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
                <div class="tab-pane fade" id="roleTabLocks" role="tabpanel" aria-labelledby="locks-tab">
                    <br>
                    <div class="text-center"><b>{{ _i('Coming soon') }}...</b></div>
                    <br>
                </div>
            </div>
        </div>
        <div class="page-body">
            <form autocomplete="destroy" class="col table-load">
                <table id="table-roles" class="display nowrap" data-route="{{ route('agents.get.direct.children') }}?draw=2&start=0&username={{ $username }}">
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

        <div class="d-none" id="user-buttons">
            <div class="d-inline-block dropdown">
                <button class="btn dropdown-toggle" type="button" id="dropdownRoleProfile" data-toggle="dropdown" aria-expanded="false">
                    <i class="fa-solid fa-ellipsis-vertical"></i>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownRoleProfile">
                    <li><a class="dropdown-item currentDataRole" href="javascript:void(0)" data-toggle="modal" data-target="#role-create">{{ _i('Add role') }}</a></li>
                    <li><a class="dropdown-item currentDataRole" href="javascript:void(0)" data-toggle="modal" data-target="#role-password-reset">{{ _i('Reset password') }}</a></li>
                    <li><a class="dropdown-item currentDataRole" href="javascript:void(0)" data-toggle="modal" data-target="#role-lock"
                           data-lock="{{ _i('Lock profile') }}"
                           data-unlock="{{ _i('Unlock profile') }}"
                           data-rol=""
                           data-value=""
                           data-type="">
                        </a>
                    </li>
                    <li><a class="dropdown-item currentDataRole" href="javascript:void(0)" data-toggle="modal" data-target="#role-balance">{{ _i('Balance adjustment') }}</a></li>
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
            roles.tabsTablesSection();
        });
    </script>
@endsection

@extends('back.template')

@section('styles')

@endsection

@section('content')
    <div class="wrapper-title g-pb-30">
        {{ _i('Role and permission management') }}
    </div>

    <div class="page-role">
        <div class="page-top">
            <form class="search-input" autocomplete="destroy">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" class="form-control" placeholder="{{ _i('Search') }}">
            </form>
            <button type="button" class="btn btn-theme" data-toggle="modal" data-target="#role-create" data-value="true"><i class="fa-solid fa-plus"></i> {{ _i('Create role') }}</button>
        </div>
        <div class="nav-roles">
            <ul class="nav nav-tabs" id="roleTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" data-toggle="tab" data-target="#roleTabProfileManager" type="button" role="tab" aria-controls="roleTabProfileManager" aria-selected="true">
                        Profile management
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-toggle="tab" data-target="#roleTabTransactions" type="button" role="tab" aria-controls="roleTabTransactions" aria-selected="false">
                        Transactions
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-toggle="tab" data-target="#roleTabMoreInformation" type="button" role="tab" aria-controls="roleTabMoreInformation" aria-selected="false">
                        More information
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-toggle="tab" data-target="#roleTabLocks" type="button" role="tab" aria-controls="roleTabLocks" aria-selected="false">
                        Locks
                    </button>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="roleTabProfileManager" role="tabpanel" aria-labelledby="profile-tab">
                    <div class="tab-manager">

                        <div class="tab-manager-top">
                            <div class="tab-manager-data">
                                <div class="data-title">{{ _i('Name') }}</div>
                                <div class="data-text">{{ $authUser->username }} <span class="deco-role">{{ $authUser->typeUser }}</span></div>
                            </div>
                            <div class="tab-manager-data">
                                <div class="data-title">{{ _i('ID User') }}</div>
                                <div class="data-text text-id">{{ $authUser->id}}</div>
                            </div>
                            <div class="tab-manager-data">
                                <div class="data-title">{{ _i('Password') }}</div>
                                <div class="data-text">
                                    <button class="btn btn-theme btn-reset-password-head" data-toggle="modal" data-target="#role-password-reset" data-id="{{ $authUser->id}}">{{ _i('Reset') }}</button>
                                </div>
                            </div>
                        </div>


                        <div class="tab-manager-data">
                            <div class="data-title">{{ _i('Number of dependent agents') }}</div>
                            <div class="data-text-inline"><span class="name">{{ _i('Master') }}</span> <span class="number">{{ $agent?->masterQuantity ?? '0.00' }}</span></div>
                            <div class="data-text-inline"><span class="name">{{ _i('Support') }}</span> <span class="number">{{ $agent?->cashierQuantity ?? '0.00' }}</span></div>
                            <div class="data-text-inline"><span class="name">{{ _i('Players') }}</span> <span class="number">{{ $agent?->playerQuantity ?? '0.00' }}</span></div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="roleTabTransactions" role="tabpanel" aria-labelledby="transactions-tab">2</div>
                <div class="tab-pane fade" id="roleTabMoreInformation" role="tabpanel" aria-labelledby="information-tab">3</div>
                <div class="tab-pane fade" id="roleTabLocks" role="tabpanel" aria-labelledby="locks-tab">4</div>
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
    @include('back.agents.modals.role-lock')
@endsection

@section('scripts')
    <script>
        $(function () {
            let roles = new Roles();
            roles.initTableRoles();
            roles.userResetPassword();
            roles.userBalance();
            roles.userCreate();
            roles.userLock();
        });
    </script>
@endsection

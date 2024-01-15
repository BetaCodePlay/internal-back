@extends('back.template')

@section('styles')

@endsection

@section('content')
    <div class="wrapper-title g-pb-30">
        {{ _i('Role and permission management') }}
    </div>

    <div class="page-role">
        <div class="page-top">
            <div class="search-input">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" class="form-control" placeholder="{{ _i('Search') }}">
            </div>
            <button type="button" class="btn btn-theme" data-toggle="modal" data-target="#role-create"><i class="fa-solid fa-plus"></i> {{ _i('Create role') }}</button>
        </div>
        <div class="page-header">
            <div class="page-header-top">
                {{ _i('My profile') }}

                <div class="d-inline-block dropdown">
                    <button class="btn dropdown-toggle" type="button" id="dropdownRoleProfile" data-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-ellipsis-vertical"></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownRoleProfile">
                        <li><a class="dropdown-item" href="#">{{ _i('Add role') }}</a></li>
                        <li><a class="dropdown-item currentDataRole" data-toggle="modal" data-target="#role-password-reset" data-userid="{{ auth()->user()->id }}" data-username="{{ auth()->user()->username }}">{{ _i('Reset password') }}</a></li>
                        <li><a class="dropdown-item" href="#">{{ _i('Block') }}</a></li>
                    </ul>
                </div>
            </div>
            <div class="page-header-body">

                <div class="page-data-top">
                    <div class="page-data">
                        <div class="data-title">{{ _i('Name') }}</div>
                        <div class="data-text">{{ $authUser->username }} <span class="deco-role">{{ $authUser->typeUser }}</span></div>
                    </div>
                    <div class="page-data">
                        <div class="data-title">{{ _i('ID User') }}</div>
                        <div class="data-text text-id">{{ $authUser->id}}</div>
                    </div>
                </div>


                <div class="page-data">
                    <div class="data-title">{{ _i('Number of dependent agents') }}</div>
                    <div class="data-text-inline"><span class="name">{{ _i('Master') }}</span> <span class="number">{{ $agent->masterQuantity }}</span></div>
                    <div class="data-text-inline"><span class="name">{{ _i('Support') }}</span> <span class="number">{{ $agent->cashierQuantity }}</span></div>
                    <div class="data-text-inline"><span class="name">{{ _i('Players') }}</span> <span class="number">{{ $agent->playerQuantity }}</span></div>
                </div>
            </div>
        </div>
        <div class="page-body">
            <div class="col table-load">
                <table id="table-roles" class="display nowrap" data-route="{{ route('agents.get.direct.children') }}?draw=2&start=0">
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
                    {{--<tbody>
                    <tr>
                        <td><span class="btn-tr-details"><i class="fa-regular fa-eye"></i></span>  Antonella93</td>
                        <td><span class="deco-rol">Administrador</span></td>
                        <td>BE8523</td>
                        <td><i class="fa-solid i-status fa-circle green"></i> Activo</td>
                        <td>Normal</td>
                        <td>$12.000,00</td>
                        <td><span class="deco-number">00</span></td>
                        <td class="text-right">
                            <div class="d-inline-block dropdown">
                                <button class="btn dropdown-toggle" type="button" id="dropdownRoleProfile" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownRoleProfile">
                                    <li><a class="dropdown-item" href="#">{{ _i('View profile') }}</a></li>
                                    <li><a class="dropdown-item" href="javascript:void(0)" data-toggle="modal" data-target="#role-create-simple">{{ _i('Add role') }}</a></li>
                                    <li><a class="dropdown-item" href="javascript:void(0)" data-toggle="modal" data-target="#role-password-reset">{{ _i('Reset password') }}</a></li>
                                    <li><a class="dropdown-item" href="javascript:void(0)" data-toggle="modal" data-target="#role-lock">{{ _i('Lock profile') }}</a></li>
                                    <li><a class="dropdown-item" href="javascript:void(0)" data-toggle="modal" data-target="#role-balance">{{ _i('Balance adjustment') }}</a></li>
                                </ul>
                            </div>

                            <a href="#" class="btn btn-href"><i class="fa-solid fa-chevron-right"></i></a>
                        </td>
                    </tr>
                    </tbody>--}}
                </table>
            </div>
            <div class="loading-style"></div>
        </div>

        <div class="d-none" id="user-buttons">
            <div class="d-inline-block dropdown">
                <button class="btn dropdown-toggle" type="button" id="dropdownRoleProfile" data-toggle="dropdown" aria-expanded="false">
                    <i class="fa-solid fa-ellipsis-vertical"></i>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownRoleProfile">
                    <li><a class="dropdown-item" href="#">{{ _i('View profile') }}</a></ li>
                    <li><a class="dropdown-item currentDataRole" href="javascript:void(0)" data-toggle="modal" data-target="#role-create-simple">{{ _i('Add role') }}</a></li>
                    <li><a class="dropdown-item currentDataRole" href="javascript:void(0)" data-toggle="modal" data-target="#role-password-reset">{{ _i('Reset password') }}</a></li>
                    <li><a class="dropdown-item currentDataRole" href="javascript:void(0)" data-toggle="modal" data-target="#role-lock">{{ _i('Lock profile') }}</a></li>
                    <li><a class="dropdown-item currentDataRole" href="javascript:void(0)" data-toggle="modal" data-target="#role-balance">{{ _i('Balance adjustment') }}</a></li>
                </ul>
            </div>

            <a href="#" class="btn btn-href"><i class="fa-solid fa-chevron-right"></i></a>
        </div>
    </div>
    <div class="d-none" id="globalActionID" data-userid="" data-username=""></div>
@endsection

@section('modals')
    @include('back.agents.modals.role-password-reset')
    @include('back.agents.modals.role-create-simple')
    @include('back.agents.modals.role-balance')
    @include('back.agents.modals.role-create')
    @include('back.agents.modals.role-lock')
@endsection

@section('scripts')
    <script>
        $(function () {
            let roles = new Roles();
            roles.initTableRoles();
        });
    </script>
@endsection

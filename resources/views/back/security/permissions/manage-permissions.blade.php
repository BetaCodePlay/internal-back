@extends('back.template')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                <header class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                    <div class="media">
                        <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                            {{ $title }}
                        </h3>
                    </div>
                </header>
                <div class="card-block g-pa-15">
                    <form action="{{ route('security.manage-store-permissions-users')}}" id="save-form" method="post">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                <label for="user">{{ _i('User') }}</label>
                                    <select class="form-control select2 permissionsselect user" id="user" name="users[]"
                                            data-route="{{ route('users.search-username') }}" multiple>
                                        <option></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="permissions">{{ _i('Permissions') }}</label>
                                    <select name="permissions[]" id="permissionselect" class="form-control select2 permissionselect user " multiple>
                                        @foreach ($permissions as $permission)
                                            <option value="{{ $permission->id }}">
                                                {{ $permission->description }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="button" class="btn u-btn-3d u-btn-primary" id="save"
                                            data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Saving...') }}">
                                        
                                        {{ _i('Save') }}
                                    </button>
                                    <button type="button" class="btn u-btn-3d u-btn-bluegray" id="clear">
                                        <i class="hs-admin-close"></i>
                                        {{ _i('Clear') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
                <div class="col-md-12">
                    <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                        <header class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                            <div class="media">
                                <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 g-mb-0">
                                    {{ _i('Users and Permissions') }}
                                </h3>
                            </div>
                        </header>
                        <div class="card-block g-pa-15">
                            <div class="table-responsive">
                                <input type="hidden" name="permissions" id="permissions" >
                                <table class="table table-bordered w-100" id="permissions-users-table" data-route="{{ route('security.manage-permissions-users-data') }}">
                                    <thead>
                                    <tr>
                                        <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                            {{ _i('ID') }}
                                        </th>
                                        <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                            {{ _i('Users') }}
                                        </th>
                                        <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                            {{ _i('permissions') }}
                                        </th>
                                        <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                            {{ _i('Actions') }}
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
        </div>

@include('back.security.permissions.modals.edit-permissions')
@endsection

@section('scripts')
    <script>
        $(function () {
            let security = new Security();
            let users = new Users();
            security.assignPermissionToUser();
            security.permissionsUsers();
            security.editPermissionUsers();
            security.select2permission('{{ _i('Select permission') }}')
            security.select2Users('{{ _i('Select user') }}');
        });
    </script>
@endsection
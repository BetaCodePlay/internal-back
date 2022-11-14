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
                    <form action="{{ route('security.manage-store-role-users')}}" id="save-form" method="post">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                <label for="user">{{ _i('User') }}</label>
                                    <select class="form-control select2 roleselect user" id="user" name="users[]"
                                            data-route="{{ route('users.search-username') }}" multiple>
                                        <option></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="roles">{{ _i('Roles') }}</label>
                                    <select name="roles[]" id="roles" class="form-control" multiple data-placeholder="{{ _i('Select ...') }}">
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}">
                                                {{ $role->description }}
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
                                    {{ _i('Users and Roles') }}
                                </h3>
                            </div>
                        </header>
                        <div class="card-block g-pa-15">
                            <div class="table-responsive">
                                <input type="hidden" name="roles" id="roles" >
                                <table class="table table-bordered w-100" id="roles-users-table" data-route="{{ route('security.role-users-data') }}">
                                    <thead>
                                    <tr>
                                        <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                            {{ _i('ID') }}
                                        </th>
                                        <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                            {{ _i('Users') }}
                                        </th>
                                        <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                            {{ _i('Roles') }}
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
@endsection

@section('scripts')
    <script>
        $(function () {
            let security = new Security();
            let users = new Users();
            security.assignRoleToUser();
            security.rolesUsers();
            users.select2Users('{{ _i('Select user') }}');
        });
    </script>
@endsection
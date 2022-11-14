@extends('back.template')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                <header
                    class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                    <div class="media">
                        <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                            {{ $title }}
                        </h3>
                    </div>
                </header>
                <div class="card-block g-pa-15">
                    <form action="{{ route('security.exclude-role-permissions-data') }}" method="post" id="exclude-role-permissions-form">
                        <div class="row">
                            <div class="col-md-4">
                            <div class="form-group">
                                    <label for="role">{{ _i('Role') }}</label>
                                    <select name="role" id="role" class="form-control">
                                        <option value="">{{ _i('Select...') }}</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}">
                                                {{ $role->description }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                            <div class="form-group">
                                <label for="permissions">{{ _i('Permissions') }}</label>
                                <select name="permissions[]" id="permissions" class="form-control select2 permissions" multiple data-route="{{ route('security.permissions-by-role') }}"></select>
                            </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="button" class="btn u-btn-3d u-btn-primary" id="save"
                                            data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Saving...') }}">
                                        <i class="hs-admin-reload"></i>
                                        {{ _i('Exclude role permissions') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function () {
            let security = new Security();
            security.permissionsByRole('{{ _i('Select permission') }}');
            security.excludeRolePermissions();
        });
    </script>
@endsection

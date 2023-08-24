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
                    <form action="{{route('core.add.rol.admin')}}" id="change-rol-form"  method="post">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="username_search">{{ _i('Search Admin') }}</label>
                                    <select name="user_id" id="username_search"
                                            class="form-control select2 username_search agent_id_search"
                                            required="required"
                                            data-route="{{ route('agents.search-username')}}?type=1"
                                            data-select="{{ route('agents.find-user') }}">
                                        <option></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="rol_id">{{ _i('Rol') }}</label>
                                    <select name="rol_id" id="rol_id" class="form-control"
                                            required="required">
                                        <option value="">{{ _i('Select...') }}</option>
                                        @foreach($roles as $value)
                                            <option value="{{$value->id}}">{{$value->description}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12" style="text-align: end;">
                                <div class="form-group">
                                    <button type="button" class="btn u-btn-3d u-btn-primary" id="changeRolAdmin"
                                            data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Please wait...') }}">
                                        <i class="hs-admin-save"></i>
                                        {{ _i('Add') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-6" id="listRoles" data-route_delete="{{ route('core.delete.rol.admin')}}">

                        </div>
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
            agents.selectUserSearch('{{ _i('User search...') }}');
            users.changeRolAdmin();
        });
    </script>
@endsection

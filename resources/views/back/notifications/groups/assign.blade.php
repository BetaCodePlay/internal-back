@extends('back.template')

@section('content')
    <div class="row">
        <div class="col-md-3">
            <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                <header
                    class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                    <div class="media">
                        <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                            {{ $title }}
                        </h3>
                    </div>
                </header>
                <form action="{{ route('notifications.groups.assign.data') }}" id="assign-user-form" method="post" enctype="multipart/form-data">
                    <div class="card-block g-pa-15">
                        <div class="form-group">
                            <label for="user">{{ _i('User') }}</label>
                            <select class="form-control select2" id="user" name="user"
                                    data-route="{{ route('users.search-username') }}">
                                <option></option>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="group" id="group" value="{{ $group }}">
                            <button type="submit" class="btn u-btn-3d u-btn-primary" id="store"
                                    data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Publishing...') }}">
                                <i class="hs-admin-upload"></i>
                                {{ _i('Assign') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                <header
                    class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                    <div class="media">
                        <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                            {{ _i('Users of group') }}
                        </h3>
                        <div class="media-body d-flex justify-content-end">
                            <a href="{{ route('notifications.groups.index') }}" class="btn u-btn-3d u-btn-primary float-right">
                                <i class="hs-admin-layout-list-thumb"></i>
                                {{ _i('Go to list') }}
                            </a>
                        </div>
                    </div>
                </header>
                <div class="card-block g-pa-15">
                    <div class="media">
                        <div class="media-body d-flex justify-content-end g-mb-10" id="table-buttons">

                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered w-100" id="group-user-table" data-route="{{ route('notifications.groups.users', [ $group ]) }}">
                            <thead>
                            <tr>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('ID') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('User') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Email') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Name') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Last name') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Actions') }}
                                </th>
                            </tr>
                            </thead>
                            <tbody></tbody>
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
            let notifications = new Notifications();
            let users = new Users();
            notifications.assignUserGroup();
            notifications.groupUsers();
            users.select2Users('{{ _i('Select user') }}');
        });
    </script>
@endsection

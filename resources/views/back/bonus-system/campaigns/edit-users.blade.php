@extends('back.template')

@section('content')
    <form action="{{ route('bonus-system.campaigns.update') }}" id="campaigns-form" method="post" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-12">
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                    <header
                        class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                {{ _i('Users details') }}
                            </h3>
                            <div class="media-body d-flex justify-content-end">
                                <a href="{{ route('bonus-system.campaigns.index') }}" class="btn u-btn-3d u-btn-primary float-right">
                                    <i class="hs-admin-layout-list-thumb"></i>
                                    {{ _i('Go to list') }}
                                </a>
                            </div>
                        </div>
                    </header>
                    <div class="card-block g-pa-15">
                        <div class="row">
                            <div class="card-block g-pa-15">
                                <div class="noty_bar noty_type__warning noty_theme__unify--v1--dark g-mb-25">
                                    <div class="noty_body">
                                        <div class="g-mr-20">
                                            <div class="noty_body__icon">
                                                <i class="hs-admin-info"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <p>
                                                {{ _i('For the assignment of users, the excluded will be taken into account as the first option') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if(isset($campaign->data->user_search_type))
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="user_search_type">{{ _i('User search type') }}</label>
                                        <select name="user_search_type" id="user_search_type" class="form-control">
                                            @if($campaign->data->user_search_type == \App\BonusSystem\Enums\UsersSearchTypes::$users)
                                                <option value="">{{ _i('Select...') }}</option>
                                                <option value="users" selected>{{ _i('Users') }}</option>
                                                <option value="segments">{{ _i('Segments') }}</option>
                                                <option value="excel">{{ _i('Excel') }}</option>
                                                <option value="all">{{ _i('All') }}</option>
                                            @elseif($campaign->data->user_search_type == \App\BonusSystem\Enums\UsersSearchTypes::$segments)
                                                <option value="users">{{ _i('Users') }}</option>
                                                <option value="segments" selected>{{ _i('Segments') }}</option>
                                                <option value="excel">{{ _i('Excel') }}</option>
                                                <option value="all">{{ _i('All') }}</option>
                                            @elseif($campaign->data->user_search_type == \App\BonusSystem\Enums\UsersSearchTypes::$excel)
                                                <option value="users">{{ _i('Users') }}</option>
                                                <option value="segments">{{ _i('Segments') }}</option>
                                                <option value="excel" selected>{{ _i('Excel') }}</option>
                                                <option value="all">{{ _i('All') }}</option>
                                            @else
                                                <option value="users">{{ _i('Users') }}</option>
                                                <option value="segments">{{ _i('Segments') }}</option>
                                                <option value="excel">{{ _i('Excel') }}</option>
                                                <option value="all" selected>{{ _i('All') }}</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                @if($campaign->data->user_search_type == \App\BonusSystem\Enums\UsersSearchTypes::$users)
                                    <div class="col-md-12">
                                        <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                                            <header class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                                                <div class="media">
                                                    <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 g-mb-0">
                                                        {{ _i('Include users') }}
                                                    </h3>
                                                    <div class="media-body d-flex justify-content-end g-mb-10" id="users-table-buttons">

                                                    </div>
                                                </div>
                                            </header>
                                            <div class="card-block g-pa-15">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered w-100" id="users-table"
                                                           data-route="{{ route('bonus-system.campaigns.include-users', [$campaign->id])}}">
                                                        <thead>
                                                        <tr>
                                                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                                                {{ _i('ID') }}
                                                            </th>
                                                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                                                {{ _i('Username') }}
                                                            </th>
                                                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                                                {{ _i('Actions') }}
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
                                @endif
                                @if($campaign->data->user_search_type == \App\BonusSystem\Enums\UsersSearchTypes::$segments)
                                    <div class="col-md-12">
                                        <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                                            <header class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                                                <div class="media">
                                                    <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 g-mb-0">
                                                        {{ _i('Segments') }}
                                                    </h3>
                                                    <div class="media-body d-flex justify-content-end g-mb-10" id="segments-table-buttons">

                                                    </div>
                                                </div>
                                            </header>
                                            <div class="card-block g-pa-15">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered w-100" id="segments-table"
                                                           data-route="{{ route('bonus-system.campaigns.include-segments', [$campaign->id])}}">
                                                        <thead>
                                                        <tr>
                                                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                                                {{ _i('Segment') }}
                                                            </th>
                                                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                                                {{ _i('Actions') }}
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
                                @endif
                                @if($campaign->data->user_search_type == \App\BonusSystem\Enums\UsersSearchTypes::$all)
                                    <div class="col-md-12">
                                        <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                                            <div>
                                                <p>
                                                    {{ _i('Campaign for all users') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endif
                            @if(!isset($campaign->data->user_search_type))
                                <div class="col-md-12 search-type d-none">
                                    <div class="row m--margin-bottom-20">
                                        <div class="col-md-6 search-users d-none">
                                            <div class="form-group">
                                                <label for="include_user">{{ _i('Include users') }}</label>
                                                <select name="include_user[]" id="include_user" class="form-control select2" data-route="{{ route('users.search-username') }}" multiple>
                                                    <option></option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 search-users d-none">
                                            <div class="form-group">
                                                <label for="exclude_user">{{ _i('Exclude users') }}</label>
                                                <select name="exclude_user[]" id="exclude_user" class="form-control select2" data-route="{{ route('users.search-username') }}" multiple>
                                                    <option></option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 search-segments d-none">
                                            <div class="form-group">
                                                <label for="include_segments">{{ _i('Include segments') }}</label>
                                                <select name="include_segments[]" id="include_segments" class="form-control" multiple>
                                                    <option value="">{{ _i('Select...') }}</option>
                                                    @foreach ($segments as $segment)
                                                        <option value="{{ $segment->id }}">
                                                            {{ $segment->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 search-segments d-none">
                                            <div class="form-group">
                                                <label for="exclude_segments">{{ _i('Exclude segments') }}</label>
                                                <select name="exclude_segments[]" id="exclude_segments" class="form-control" multiple>
                                                    <option value="">{{ _i('Select...') }}</option>
                                                    @foreach ($segments as $segment)
                                                        <option value="{{ $segment->id }}">
                                                            {{ $segment->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 search-excel d-none">
                                            <div class="form-group">
                                                <label for="include_excel">{{ _i('Include excel') }}</label>
                                                <input type="file" name="include_excel" id="include_excel">
                                            </div>
                                        </div>
                                        <div class="col-md-6 search-excel d-none">
                                            <div class="form-group">
                                                <label for="exclude_excel">{{ _i('Exclude excel') }}</label>
                                                <input type="file" name="exclude_excel" id="exclude_excel">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="submit" class="btn u-btn-3d u-btn-primary" id="update"
                                            data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Updating...') }}">
                                        <i class="hs-admin-reload"></i>
                                        {{ _i('Update campaign') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    @include('back.bonus-system.campaigns.modals.add-translations-modal')
@endsection

@section('scripts')
    <script>
        $(function () {
            let bonusSystem = new BonusSystem();
            bonusSystem.usersSearchType();
            bonusSystem.select2ExcludeUsers('{{ _i('Select user') }}');
            bonusSystem.select2IncludeUsers('{{ _i('Select user') }}');
        });
    </script>
@endsection

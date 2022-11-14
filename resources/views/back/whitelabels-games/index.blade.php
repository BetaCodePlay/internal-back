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
                    <form action="{{ route('whitelabels-games.store') }}" id="store-form" method="post">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="devices">{{ _i('Devices') }}</label>
                                    <select name="devices" id="devices" class="form-control">
                                        <option value="">{{ _i('All') }}</option>
                                        <option value="true">{{ _i('Mobile') }}</option>
                                        <option value="false">{{ _i('Desktop') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="change_provider">{{ _i('Provider') }}</label>
                                    <select name="change_provider" id="change_provider" data-route="{{ route('whitelabels-games.game') }}" class="form-control">
                                        <option value="">{{ _i('Select...') }}</option>
                                        @foreach ($providers as $provider)
                                            <option value="{{ $provider->provider_id }}">
                                                {{ $provider->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="games">{{ _i('Games') }}</label>
                                    <select name="games[]" id="games" class="form-control" data-loading-text="<i class='fa fa-spin fa-spinner'></i>  {{ _i('Loading...') }}" multiple>
                                        <option value="">{{ _i('Select...') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="game_category">{{ _i('Categories') }}</label>
                                    <select name="game_category" id="game_category" class="form-control">
                                        <option value="">{{ _i('Select...') }}</option>
                                        @foreach ($games_categories as $games_category)
                                            <option value="{{ $games_category->id }}">
                                                {{ $games_category->category }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="button" class="btn u-btn-3d u-btn-primary" id="store"
                                            data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Saving...') }}">
                                        <i class="hs-admin-save"></i>
                                        {{ _i('Save') }}
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
                <header
                    class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                    <div class="media">
                        <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                            {{ _i('Filters') }}
                        </h3>
                    </div>
                </header>
                <div class="card-block g-pa-15 g-pb-5">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="options_balance">{{ _i('Provider') }}</label>
                                <select name="provider" id="provider" data-route="{{ route('whitelabels-games.game') }}" class="form-control">
                                    <option value="">{{ _i('All') }}</option>
                                    @foreach ($providers as $provider)
                                        <option value="{{ $provider->provider_id }}">
                                            {{ $provider->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="categories_games">{{ _i('Category') }}</label>
                                <select name="category" id="category" class="form-control">
                                    <option value="">{{ _i('All') }}</option>
                                    @foreach ($games_categories as $games_category)
                                        <option value="{{ $games_category->id }}">
                                            {{ $games_category->category }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <button type="button" class="btn u-btn-3d u-btn-primary" id="search"
                                        data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Searching...') }}">
                                    <i class="hs-admin-search"></i>
                                    {{ _i('Search') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                <header class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                    <div class="media">
                        <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0 title">
                            {{ _i('Games') }}
                        </h3>
                        <div class="media-body d-flex justify-content-end" id="table-buttons">

                        </div>
                        <div class="justify-content-end g-ml-10" style="padding-left: 10px">
                        </div>
                    </div>
                </header>
                <div class="card-block g-pa-15">
                    <table class="table table-bordered table-responsive-sm w-100" id="games-table"  data-route="{{ route('whitelabels-games.all') }}">
                        <thead>
                        <tr>
                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                {{ _i('Provider') }}
                            </th>
                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                {{ _i('Game') }}
                            </th>
                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                {{ _i('Category') }}
                            </th>
                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                {{ _i('Device') }}
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
@endsection

@section('scripts')

    <script>
        $(function () {
            let whitelabelsGames = new WhitelabelsGames()
            whitelabelsGames.all();
            whitelabelsGames.game();
            whitelabelsGames.store();
        });
    </script>
@endsection

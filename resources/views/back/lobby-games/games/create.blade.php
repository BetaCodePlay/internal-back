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
                    <form action="{{ route('games.store') }}" id="store-form" method="post">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="change_provider">{{ _i('Provider') }}</label>
                                    <select name="change_provider" id="change_provider" data-route="{{ route('core.makers-by-provider') }}" class="form-control">
                                        <option value="">{{ _i('Select...') }}</option>
                                        @foreach ($providers as $provider)
                                            <option value="{{ $provider->provider_id }}">
                                                {{ $provider->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label style="padding-top: 33px;">
                                    <input type="checkbox" class="checkshow" name="personalize" autocomplete="off">
                                    <span class="glyphicon glyphicon-ok">{{ _i('Games Personalize: ') }}</span>
                                </label>
                            </div>
                            <div class="div_a_show col-md-6">
                                <div class="form-group">
                                    <label for="maker">{{ _i('Maker') }}</label>
                                    <select name="maker" id="maker" class="form-control"
                                    data-route="{{ route('core.categories-by-maker') }}">
                                        <option value="">{{ _i('Select...') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="div_a_show col-md-6">
                                <div class="form-group">
                                    <label for="category">{{ _i('Category') }}</label>
                                    <select name="category" id="category" class="form-control"
                                    data-route="{{ route('games.game-by-categories') }}">
                                        <option value="">{{ _i('Select...') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="div_a_product_id col-md-6">
                                <div class="form-group">
                                    <label for="product_id">{{ _i('Product ID') }}</label>
                                    <select name="product_id" id="product_id" class="form-control">
                                        <option value="">{{ _i('Select...') }}</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->product_id }}">
                                                {{ $product->product_id }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="div_a_show col-md-6">
                                <div>
                                    <label for="games">{{ _i('Games') }}</label>
                                    <select name="games[]" id="games" class="form-control"
                                            data-loading-text="<i class='fa fa-spin fa-spinner'></i>  {{ _i('Loading...') }}"
                                            multiple>
                                        <option value="">{{ _i('Select') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="div_a_show col-md-6">
                                <div class="form-group">
                                    <label for="order">{{ _i('Order (optional)') }}</label>
                                    <input type="number" name="order" id="order" value="0" class="form-control" min="0">
                                </div>
                            </div>
                            @isset($route)
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="route">{{ _i('Menu where it will be shown') }}</label>
                                    <select select name="route" id="route" class="form-control">
                                        <option value="">{{ _i('Select...') }}</option>
                                        @foreach ($route as $item)
                                            <option value="{{ $item->route }}">
                                                {{ $item->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                           @endisset
                            <div class="div_a_show card-block g-pa-15">
                                <div class="noty_bar noty_type__warning noty_theme__unify--v1--dark g-mb-25">
                                    <div class="noty_body">
                                        <div class="g-mr-20">
                                            <div class="noty_body__icon">
                                                <i class="hs-admin-info"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <p>
                                                {{ _i(' The maximum file size is 5mb') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="image">{{ _i('Image') }}</label>
                                    <input type="file" name="image" id="image" class="opacity-0">
                                </div>
                            </div>
                            <br>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="submit" class="btn u-btn-3d u-btn-primary" id="store"
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
        {{--<div class="col-md-12">
            <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                <form action="{{ route('games.store') }}" id="filter-form" method="post">
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
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="provider">{{ _i('Provider') }}</label>
                                    <select name="provider" id="provider"
                                            data-route="{{ route('games.game') }}" class="form-control">
                                        <option value="">{{ _i('All') }}</option>
                                        @foreach ($providers as $provider)
                                            <option value="{{ $provider->provider_id}}">
                                                {{ $provider->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="menu">{{ _i('Menu') }}</label>
                                    <select name="menu" id="menu" class="form-control">
                                        <option value="">{{ _i('Select') }}</option>
                                        @foreach ($route as $item)
                                            <option value="{{ $item->route }}">
                                                {{ $item->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="filter">{{ _i('Games') }}</label>
                                    <select name="filter" id="filter" class="form-control"
                                            data-loading-text="<i class='fa fa-spin fa-spinner'></i>  {{ _i('Loading...') }}">
                                        <option value="">{{ _i('Select') }}</option>
                                        @foreach ($games as $game)
                                            <option value="{{ $game->name}}">
                                                {{ $game->name }}
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
                                    <button type="button" class="btn u-btn-3d u-btn-bluegray" id="clear">
                                        <i class="hs-admin-close"></i>
                                        {{ _i('Clear') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>--}}
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
                    <table class="table table-bordered table-responsive-sm w-100" id="games-table" data-route="{{ route('games.all') }}">
                        <thead>
                        <tr>
                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                {{ _i('Image') }}
                            </th>
                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                {{ _i('Provider') }}
                            </th>
                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                {{ _i('Game') }}
                            </th>
                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                {{ _i('Menu') }}
                            </th>
                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                {{ _i('Order') }}
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
            let lobbyGames = new LobbyGames()
            lobbyGames.all();
            lobbyGames.store();
            lobbyGames.selectProviderMaker();
            lobbyGames.selectCategoryMaker();
            lobbyGames.selectGame();
        });
    </script>
    <script>
        $(function() {

            // obtener campos ocultar div
            var checkbox = $(".checkshow");
            var hidden = $(".div_a_show");
            //

            hidden.hide();
            checkbox.change(function() {
                if (checkbox.is(':checked')) {
                    //hidden.show();
                    $(".div_a_show").fadeIn("200")
                } else {
                    //hidden.hide();
                    $(".div_a_show").fadeOut("200")
                    $('input[type=checkbox]').prop('checked',false);// limpia los valores de checkbox al ser ocultado

                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#limpiar').click(function() {
                $('select[type="text"]').val('');
            });
        });
    </script>
@endsection

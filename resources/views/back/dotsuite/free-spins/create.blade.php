@extends('back.template')

@section('content')
    <form action="{{ route('dot-suite.free-spins.store') }}" id="slot-store-form" method="post">
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
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type_user">{{ _i('Type of user load') }}</label>
                                    <select name="type_user" id="type_user" class="form-control">
                                        <option value="">{{ _i('Select...') }}</option>
                                        <option value="1">{{ _i('Search') }}</option>
                                        <option value="2">{{ _i('Segments') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 d-none" id="user">
                                <div class="form-group">
                                    <label for="users">{{ _i('User') }}</label>
                                    <select name="users[]" class="form-control" multiple>
                                        <option value="">{{ _i('Select...') }}</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">
                                                {{ $user->username }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 d-none" id="segments">
                                <div class="form-group">
                                    <label for="segment">{{ _i('Segments') }}</label>
                                    <select name="segment" id="segment" class="form-control">
                                        <option value="">{{ _i('Select...') }}</option>
                                        @foreach ($segments as $segment)
                                            <option value="{{ $segment->id }}">
                                                {{ $segment->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="amount">{{ _i('Amount') }}</label>
                                    <input type="number" name="amount" id="amount" class="form-control" min="0">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="quantity">{{ _i('Quantity of turns') }}</label>
                                    <input type="number" name="quantity" id="quantity" class="form-control" min="0">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="games">{{ _i('Games') }}</label>
                                    <select name="game" id="game" class="form-control">
                                        <option value="">{{ _i('Select...') }}</option>
                                        @foreach ($games as $game)
                                            <option value="{{ $game->game_id }}">
                                                {{ $game->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="form-group">
                                        <label for="expiration_date">{{ _i('Expiration date') }}</label>
                                        <input type="text" name="expiration_date" id="expiration_date"
                                               class="form-control datepicker" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="hidden" id="provider" name="provider" value="{{ $provider }}">
                                    <button type="submit" class="btn u-btn-3d u-btn-primary" id="store"
                                            data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Creating...') }}">
                                        <i class="hs-admin-save"></i>
                                        {{ _i('Create') }}
                                    </button>
                                    <button type="button" class="btn u-btn-3d u-btn-bluegray" id="clear">
                                        <i class="hs-admin-close"></i>
                                        {{ _i('Clear') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@section('scripts')
    <script>
        $(function () {
            let dotSuite = new DotSuite();
            dotSuite.storeSlot();
            dotSuite.typeFormUsers();
        });
    </script>
@endsection


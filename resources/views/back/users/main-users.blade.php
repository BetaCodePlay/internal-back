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
                    <form action="{{ route('users.store-main-users') }}" id="users-form" method="post">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="client">{{ _i('Whitelabel') }}</label>
                                    <select name="whitelabel" id="whitelabel" class="form-control">
                                        <option value="">{{ _i('Select...') }}</option>
                                        @foreach ($whitelabels as $whitelabel)
                                            <option value="{{ $whitelabel->id }}">
                                                {{ $whitelabel->description }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="country">{{ _i('Country') }}</label>
                                    <select name="country" id="country" class="form-control">
                                        <option value="">{{ _i('Select...') }}</option>
                                        @foreach ($countries as $country)
                                            <option value="{{ $country->iso }}">
                                                {{ $country->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="timezone">{{ _i('Timezone') }}</label>
                                    <select name="timezone" id="timezone" class="form-control">
                                        <option value="">{{ _i('Select...') }}</option>
                                        @foreach ($timezones as $timezone)
                                            <option value="{{ $timezone }}">
                                                {{ $timezone }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="password">{{ _i('User password admin') }}</label>
                                <div class="form-group g-pos-rel">
                                    <button class="btn u-input-btn--v1 g-width-40 u-btn-primary g-rounded-right-4 u-btn-3d clipboard"
                                            type="button" id="clipboard" data-clipboard-text="{{ $password }}"
                                            data-title="{{ _i('Copied') }}">
                                        <i class="hs-admin-clipboard g-absolute-centered g-font-size-16 g-color-white"></i>
                                    </button>
                                    <input type="text" class="form-control" name="password" id="password" value="{{ $password }}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="button" class="btn u-btn-3d u-btn-primary" id="store"
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
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function () {
            let users = new Users();
            users.storeMain();
        });
    </script>
@endsection

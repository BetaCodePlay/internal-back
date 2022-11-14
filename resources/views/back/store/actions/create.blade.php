@extends('back.template')

@section('content')
    <form action="{{ route('store.actions.store') }}" id="actions-configurations-form" method="post" enctype="multipart/form-data">
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
                        <div class="media-body d-flex justify-content-end">
                            <a href="{{ route('store.actions.index') }}" class="btn u-btn-3d u-btn-primary float-right">
                                <i class="hs-admin-layout-list-thumb"></i>
                                {{ _i('Go to list') }}
                            </a>
                        </div>
                    </header>
                    <div class="card-block g-pa-15">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="action">{{ _i('Action') }}</label>
                                    <select name="action" id="action" class="form-control">
                                        <option value="">{{ _i('Select...') }}</option>
                                        @foreach ($actions as $action)
                                            <option value="{{ $action->id }}">
                                                {{ $action->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="currency">{{ _i('Currency') }}</label>
                                    <select name="currency" id="currency" class="form-control" data-route="{{ route('store.actions.type-providers') }}">
                                        <option value="">{{ _i('Select...') }}</option>
                                        @foreach ($whitelabel_currencies as $currency)
                                            <option value="{{ $currency->iso }}" {{ $currency->iso == session('currency') ? 'selected' : '' }}>
                                                {{ $currency->iso == 'VEF' ? $free_currency->currency_name : $currency->iso . " ({$currency->name})" }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="points_desk">{{ _i('Points desktop') }}</label>
                                    <input type="text" name="points_desk" id="points_desk" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6" id="desktop">
                                <div class="form-group">
                                    <label for="amount_desk">{{ _i('Amount desktop') }}</label>
                                    <input type="text" name="amount_desk" id="amount_desk" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="points_mobile">{{ _i('Points mobile') }}</label>
                                    <input type="text" name="points_mobile" id="points_mobile" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6" id="mobile">
                                <div class="form-group">
                                    <label for="amount_mobile">{{ _i('Amount mobile') }}</label>
                                    <input type="text" name="amount_mobile" id="amount_mobile" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="start_date">{{ _i('Start date') }}</label>
                                    <input type="text" name="start_date" id="start_date" class="form-control datetimepicker" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end_date">{{ _i('End date') }}</label>
                                    <input type="text" name="end_date" id="end_date" class="form-control datetimepicker" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="provider_type">{{ _i('Provider type') }}</label>
                                    <select name="provider_type" id="provider_type" data-route="{{ route('store.actions.exclude-providers') }}" class="form-control">
                                        <option value="">{{ _i('Select...') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exclude_provider">{{ _i('Exclude provider') }}</label>
                                    <select name="exclude_provider[]" id="exclude_provider" class="form-control" multiple>
                                        <option value="">{{ _i('Select...') }}</option>
                                    </select>
                                </div>
                            </div>
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
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('scripts')
    <script>
        $(function () {
            let store = new Store();
            store.storeActionsConfigurations()
            store.typeProviders()
            store.excludeProvider()
            store.actionsFormType()
        });
    </script>
@endsection

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
                    <form action="{{ route('dot-suite.credentials.store-credentials') }}" id="credentials-form" method="post">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="client">{{ _i('Whitelabel') }}</label>
                                    <select name="client" id="client" class="form-control">
                                        <option value="">{{ _i('Select...') }}</option>
                                        @foreach ($whitelabels as $whitelabel)
                                            <option value="{{ $whitelabel->id }}">
                                                {{ $whitelabel->description }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="currencies">{{ _i('Currency') }}</label>
                                    <select name="currencies[]" id="currencies" class="form-control" data-placeholder="{{ _i('Select...') }}" multiple>
                                        @foreach ($currency_client as $currency)
                                            <option value="{{ $currency->iso }}">
                                                {{ $currency->iso == 'VEF' ? $free_currency->currency_name : $currency->iso . " ({$currency->name})" }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="providers_type">{{ _i('Provider type') }}</label>
                                    <select name="providers_type"  id="providers_type" class="form-control" data-route="{{ route('dot-suite.credentials.providers') }}">
                                        <option value="">{{ _i('Select...') }}</option>
                                        @foreach ($providers_types as $type)
                                            <option value="{{ $type->id }}">
                                                {{ $type->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="providers">{{ _i('Providers') }}</label>
                                    <select name="providers"  id="providers" class="form-control">
                                        <option value="">{{ _i('Select...') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="percentage">{{ _i('Percentage') }}</label>
                                        <input type="text" name="percentage" id="percentage" class="form-control">
                                    </div>
                            </div>
                            <div class="col-md-3">
                                <div class="grant-secret d-none">
                                    <div class="form-group">
                                        <label for="grant_secret">{{ _i('Grant secret') }}</label>
                                        <input type="text" name="grant_secret" id="grant_secret" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="button" class="btn u-btn-3d u-btn-primary" id="save"
                                            data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Saving...') }}">
                                        <i class="hs-admin-save"></i>
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
    </div>
@endsection

@section('scripts')
    <script>
        $(function () {
            let dotSuite = new DotSuite();
            dotSuite.storeCredentials();
            dotSuite.providerTypes();
            dotSuite.credentialsData();
        });
    </script>
@endsection

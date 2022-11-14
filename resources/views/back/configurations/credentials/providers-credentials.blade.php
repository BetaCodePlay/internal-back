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
                    <form action="{{ route('configurations.credentials.store.credentials') }}" id="save-form" method="post">
                        <div class="row">
                            <div class="col-6">
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
                            <div class="col-6">
                            <div class="form-group">
                                <label for="currency">{{ _i('Currency') }}</label>
                                <select name="currency" id='currency' class="form-control" data-route="{{ route('configurations.credentials.type-providers') }}">
                                    <option value="">{{ _i('Select...') }}</option>
                                        @foreach ($whitelabel_currencies as $currency)
                                            <option value="{{ $currency->iso }}">
                                                {{ $currency->iso == 'VEF' ? $free_currency->currency_name : $currency->iso . " ({$currency->name})" }}
                                            </option>
                                        @endforeach
                                </select>
                            </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="provider_type">{{ _i('Provider type') }}</label>
                                    <select name="provider_type" id="provider_type" data-route="{{ route('configurations.credentials.exclude-providers') }}" class="form-control">
                                        <option value="">{{ _i('Select...') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="exclude_providers">{{ _i('Exclude providers') }}</label>
                                    <select name="exclude_providers" id="exclude_providers" class="form-control">
                                        <option value="">{{ _i('Select...') }}</option>
                                    </select>
                                </div>
                            </div> 
                            <div class="col-6">
                                    <div class="form-group">
                                        <label for="percentage">{{ _i('Percentage') }}</label>
                                        <input type="text" name="percentage" id="percentage" class="form-control">
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
            let configurations = new Configurations();
            configurations.providerTypes();
            configurations.providers();
            configurations.save();
            $(document).on('click', '.update_checkbox', function () {
                if (!$(this).hasClass('active')) {
                    $.post('{{route('configurations.credentials.status')}}', {client_id: $(this).data('id'),  name: 'status', value: true}, function () {});
                } else {
                    $.post('{{route('configurations.credentials.status')}}', {client_id: $(this).data('id'),  name: 'status', value: false}, function () {});
                }

                $(this).toggleClass('active');
            });
        });
    </script>
@endsection

@extends('back.template')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                <header
                    class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                    <div class="media">
                        <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                            {{_i('Filters')}}
                        </h3>
                    </div>
                </header>
                <div class="card-block g-pa-15">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="device">{{ _i('Devices') }}</label>
                                <select name="device[]" id="device" class="form-control" multiple>
                                    <option value="">{{ _i('All') }}</option>
                                    <option value="*">{{ _i('All devices') }}</option>
                                    <option value="false">{{ _i('Desktop') }}</option>
                                    <option value="true">{{ _i('Mobile') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="language">{{ _i('Language') }}</label>
                                <select name="language[]" id="language" class="form-control" multiple>
                                    <option value="">{{ _i('All') }}</option>
                                    <option value="*">{{ _i('All languages') }}</option>
                                    @foreach ($languages as $language)
                                        <option value="{{ $language['iso'] }}">
                                            {{ $language['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="currency">{{ _i('Currency') }}</label>
                                <select name="currency[]" id="currency" class="form-control" multiple>
                                    <option value="">{{ _i('All') }}</option>
                                    <option value="*">{{ _i('All currencies') }}</option>
                                    @foreach ($whitelabel_currencies as $currency)
                                        <option
                                            value="{{ $currency->iso }}" {{ $currency->iso == session('currency') ? 'selected' : '' }}>
                                            {{ $currency->iso == 'VEF' ? $free_currency->currency_name : $currency->iso . " ({$currency->name})" }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="status">{{ _i('Status') }}</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="true">{{ _i('Published') }}</option>
                                    <option value="false">{{ _i('Unpublished') }}</option>
                                </select>
                                <input type="hidden" id="template_element_type" name="template_element_type"
                                       value="{{$template_element_type}}">
                                <input type="hidden" id="section" name="section" value="{{$section}}">
                            </div>
                        </div>
                        @isset($menu)
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="route">{{ _i('Menu where it will be shown') }}</label>
                                    <select name="routes[]" id="routes" class="form-control" multiple>
                                        <option value="">{{ _i('All') }}</option>
                                        <option value="core.index">
                                            {{ _i('Home') }}
                                        </option>

                                        @foreach ($menu as $item)
                                            <option value="{{ $item->route }}">
                                                {{ $item->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endisset
                        <div class="col-md-12">
                            <div class="form-group">
                                <button type="button" class="btn u-btn-3d u-btn-primary" id="update"
                                        data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Consulting...') }}">
                                    <i class="hs-admin-search"></i>
                                    {{ _i('Consult data') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                <header
                    class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                    <div class="media">
                        <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                            {{ $title }}
                        </h3>
                        <div class="media-body d-flex justify-content-end">
                            <a href="{{ route('sliders.create', [$template_element_type, $section]) }}"
                               class="btn u-btn-3d u-btn-primary float-right">
                                <i class="hs-admin-upload"></i>
                                {{ _i('Upload') }}
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
                        <table class="table table-bordered w-100" id="sliders-table"
                               data-route="{{ route('sliders.all', [$template_element_type, $section]) }}">
                            <thead>
                            <tr>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Image') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Front') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Menu') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Start / End') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Language') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Currency') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Device') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Order') }}
                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    {{ _i('Status') }}
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
            let sliders = new Sliders();
            sliders.all();
        });
    </script>
@endsection

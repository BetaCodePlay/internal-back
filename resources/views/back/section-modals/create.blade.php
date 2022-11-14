@extends('back.template')

@section('content')
    <form action="{{ route('section-modals.store') }}" id="modals-form" method="post" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-6">
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
                        <div class="form-group">
                            <label for="image">{{ _i('Image') }}</label>
                            <input type="file" name="image" id="image" class="opacity-0">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                    <header
                        class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                {{ _i('Popup details') }}
                            </h3>
                            <div class="media-body d-flex justify-content-end">
                                <a href="{{ route('section-modals.index') }}" class="btn u-btn-3d u-btn-primary float-right">
                                    <i class="hs-admin-layout-list-thumb"></i>
                                    {{ _i('Go to list') }}
                                </a>
                            </div>
                        </div>
                    </header>
                    <div class="card-block g-pa-15">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="route">{{ _i('Menu where it will be shown') }}</label>
                                    <select name="route" id="route" class="form-control">
                                        <option value="">{{ _i('Select...') }}</option>
                                        <option value="core.index">{{ _i('Home') }}</option>
                                        @foreach ($menu as $item)
                                            <option value="{{ $item->route }}">
                                                {{ $item->name }}
                                            </option>
                                        @endforeach
                                        <option value="users.panel">
                                            {{ _i('User panel') }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="one_time">{{ _i('Show one time only') }}</label>
                                    <select name="one_time" id="one_time" class="form-control">
                                        <option value="">{{ _i('Select...') }}</option>
                                        <option value="true">{{ _i('Yes') }}</option>
                                        <option value="false">{{ _i('No') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="scroll">{{ _i('Show when scrolling') }}</label>
                                    <select name="scroll" id="scroll" class="form-control">
                                        <option value="">{{ _i('Select...') }}</option>
                                        <option value="true">{{ _i('Yes') }}</option>
                                        <option value="false">{{ _i('No') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="language">{{ _i('Language') }}</label>
                                    <select name="language" id="language" class="form-control">
                                        <option value="">{{ _i('Select...') }}</option>
                                        <option value="*">{{ _i('All') }}</option>
                                        @foreach ($languages as $language)
                                            <option value="{{ $language['iso'] }}">
                                                {{ $language['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="currency">{{ _i('Currency') }}</label>
                                    <select name="currency" id="currency" class="form-control">
                                        <option value="">{{ _i('Select...') }}</option>
                                        <option value="*">{{ _i('All') }}</option>
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
                                    <label for="status">{{ _i('Status') }}</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="true">{{ _i('Published') }}</option>
                                        <option value="false">{{ _i('Unpublished') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="url">{{ _i('Url') }}</label>
                                    <input type="text" class="form-control" name="url" id="url">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="submit" class="btn u-btn-3d u-btn-primary" id="store"
                                            data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Uploading...') }}">
                                        <i class="hs-admin-upload"></i>
                                        {{ _i('Upload popup') }}
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
            let modals = new SectionModals();
            modals.store();
        });
    </script>
@endsection

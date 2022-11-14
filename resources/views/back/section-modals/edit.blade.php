@extends('back.template')

@section('content')
    <form action="{{ route('section-modals.update') }}" id="modals-form" method="post" enctype="multipart/form-data">
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
                                        <option value="core.index" {{ $modal->route == 'core.index' ? 'selected' : '' }}>
                                            {{ _i('Home') }}
                                        </option>
                                        <option value="users.panel" {{ $modal->route == 'users.panel' ? 'selected' : '' }}>
                                            {{ _i('User panel') }}
                                        </option>
                                        @foreach ($menu as $item)
                                            <option value="{{ $item->route }}" {{ $item->route == $modal->route ? 'selected' : '' }}>
                                                {{ $item->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="one_time">{{ _i('Show one time only') }}</label>
                                    <select name="one_time" id="one_time" class="form-control">
                                        <option value="true" {{ $modal->one_time ? 'selected' : '' }}>
                                            {{ _i('Yes') }}
                                        </option>
                                        <option value="false" {{ !$modal->one_time ? 'selected' : '' }}>
                                            {{ _i('No') }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="scroll">{{ _i('Show when scrolling') }}</label>
                                    <select name="scroll" id="scroll" class="form-control">
                                        <option value="true" {{ $modal->scroll ? 'selected' : '' }}>
                                            {{ _i('Yes') }}
                                        </option>
                                        <option value="false" {{ !$modal->scroll ? 'selected' : '' }}>
                                            {{ _i('No') }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="language">{{ _i('Language') }}</label>
                                    <select name="language" id="language" class="form-control">
                                        <option value="*" {{ $modal->language == '*' ? 'selected' : '' }}>
                                            {{ _i('All') }}
                                        </option>
                                        @foreach ($languages as $language)
                                            <option value="{{ $language['iso'] }}" {{ $modal->language == $language['iso'] ? 'selected' : '' }}>
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
                                        <option value="*" {{ $modal->currency == '*' ? 'selected' : '' }}>
                                            {{ _i('All') }}
                                        </option>
                                        @foreach ($whitelabel_currencies as $currency)
                                            <option value="{{ $currency->iso }}" {{ $currency->iso == $modal->currency ? 'selected' : '' }}>
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
                                        <option value="true" {{ $modal->status ? 'selected' : '' }}>
                                            {{ _i('Published') }}
                                        </option>
                                        <option value="false" {{ !$modal->status ? 'selected' : '' }}>
                                            {{ _i('Unpublished') }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="url">{{ _i('Url') }}</label>
                                    <input type="text" class="form-control" name="url" id="url" value="{{ $modal->url }}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="hidden" name="id" value="{{ $modal->id }}">
                                    <input type="hidden" name="file" id="file" value="{{ $modal->file }}">
                                    <input type="hidden" name="image" id="image" value="{{ $modal->file }}">
                                    <button type="submit" class="btn u-btn-3d u-btn-primary" id="update"
                                            data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Updating...') }}">
                                        <i class="hs-admin-reload"></i>
                                        {{ _i('Update popup') }}
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
            modals.update("{!! $modal->image !!}");
        });
    </script>
@endsection

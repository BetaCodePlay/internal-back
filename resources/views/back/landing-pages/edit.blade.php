@extends('back.template')

@section('content')
    <form action="{{ route('landing-pages.update') }}" id="landing-form" method="post" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-6">
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                    <header
                        class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                {{ _i('Landing Pages details') }}
                            </h3>
                            <div class="media-body d-flex justify-content-end">
                                <a href="{{ route('landing-pages.index') }}"
                                   class="btn u-btn-3d u-btn-primary float-right">
                                    <i class="hs-admin-layout-list-thumb"></i>
                                    {{ _i('Go to list') }}
                                </a>
                            </div>
                        </div>
                    </header>

                    <div class="card-block g-pa-15">
                        <div class="row">

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="name">{{ _i('Name') }}</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                           value="{{ $landing->name }}">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="subtitle">{{ _i('Subtitle') }}</label>
                                    <input type="text" name="subtitle" id="subtitle" class="form-control"
                                           value="{{  $landing->data->props->subtitle }}">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="text">{{ _i('Text button') }}</label>
                                    <input type="text" name="text" id="text" class="form-control"
                                           value="{{  $landing->data->props->button->text }}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="url">{{ _i('URL') }}</label>
                                    <input type="text" name="url" id="url" class="form-control"
                                           value="{{  $landing->data->props->button->url }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="start_date">{{ _i('Start date') }}</label>
                                    <input type="text" name="start_date" id="start_date"
                                           class="form-control datepicker" value="{{ $landing->start }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end_date">{{ _i('Finish date') }}</label>
                                    <input type="text" name="end_date" id="end_date" class="form-control datepicker"
                                           value="{{ $landing->end }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="language">{{ _i('Language') }}</label>
                                    <select name="language" id="language" class="form-control">
                                        <option value="*" {{ $landing->language == '*' ? 'selected' : '' }}>
                                            {{ _i('All') }}
                                        </option>
                                        @foreach ($languages as $language)
                                            <option
                                                value="{{ $language['iso'] }}" {{ $landing->language == $language['iso'] ? 'selected' : '' }}>
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
                                        <option value="*" {{ $landing->currency == '*' ? 'selected' : '' }}>
                                            {{ _i('All') }}
                                        </option>
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
                                        @if (is_null($landing->status))
                                            <option value="true" selected>
                                                {{ _i('Published') }}
                                            </option>
                                            <option value="false">
                                                {{ _i('Unpublished') }}
                                            </option>
                                        @else
                                            <option value="true" {{ $landing->status ? 'selected' : '' }}>
                                                {{ _i('Published') }}
                                            </option>
                                            <option value="false" {{ !$landing->status ? 'selected' : '' }}>
                                                {{ _i('Unpublished') }}
                                            </option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="hidden" name="id" id="id" value="{{ $landing->id }}">
                                    <input type="hidden" name="file" id="file" value="{{ $landing->file }}">
                                    <input type="hidden" name="image" id="image" value="{{ $landing->file }}">
                                    <input type="hidden" name="file_1" id="file_1" value="{{ $landing->file_1 }}">
                                    <input type="hidden" name="background_1" id="background_1"
                                           value="{{ $landing->file_1 }}">
                                    <input type="hidden" name="file_2" id="file" value="{{ $landing->file_2 }}">
                                    <input type="hidden" name="background_2" id="background_2"
                                           value="{{ $landing->file_2 }}">
                                    <input type="hidden" name="file_3" id="file_3" value="{{ $landing->file_3 }}">
                                    <input type="hidden" name="logo" id="logo" value="{{ $landing->file_3 }}">
                                    <button type="submit" class="btn u-btn-3d u-btn-primary" id="store"
                                            data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Updating...') }}">
                                        <i class="hs-admin-reload"></i>
                                        {{ _i('Update landing') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                        <header
                            class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                            <div class="media">
                                <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                    {{ $title }} {{ _i('Background 1') }}
                                </h3>
                            </div>
                        </header>
                        <div class="card-block g-pa-15">
                            <div class="form-group">
                                <label for="background_1">{{ _i('Background 1') }}</label>
                                <input type="file" name="background_1" id="background_1" class="opacity-0">
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
                                    {{ $title }} {{ _i('Background 2') }}
                                </h3>
                            </div>
                        </header>
                        <div class="card-block g-pa-15">
                            <div class="form-group">
                                <label for="background_2">{{ _i('Background 2') }}</label>
                                <input type="file" name="background_2" id="background_2" class="opacity-0">
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
                                    {{ $title }} {{ _i('Image left') }}
                                </h3>
                            </div>
                        </header>
                        <div class="card-block g-pa-15">
                            <div class="form-group">
                                <label for="image">{{ _i('Image left') }}</label>
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
                                    {{ $title }} {{ _i('Logo') }}
                                </h3>
                            </div>
                        </header>
                        <div class="card-block g-pa-15">
                            <div class="form-group">
                                <label for="logo">{{ _i('Logo') }}</label>
                                <input type="file" name="logo" id="logo" class="opacity-0">
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
                                    {{ $title }} {{ _i('Steps') }}
                                </h3>
                            </div>
                        </header>
                        <div class="card-block g-pa-15">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="steps_title">{{ _i('Steps Title') }}</label>
                                        <input type="text" name="steps_title" id="steps_title" class="form-control"
                                               value="{{ $landing->data->props->steps->title }}">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="steps_content">{{ _i('Steps Content') }}</label>
                                        <textarea name="steps_content" id="steps_content" cols="30" rows="10"
                                                  class="form-control"> {!! $landing->data->props->steps->content !!}</textarea>

                                    </div>
                                </div>
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
                                    {{ $title }} {{ _i('Terms') }}
                                </h3>
                            </div>
                        </header>
                        <div class="card-block g-pa-15">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="terms_title">{{ _i('Terms Title') }}</label>
                                        <input type="text" name="terms_title" id="terms_title" class="form-control"
                                               value="{{ $landing->data->props->terms->title }}">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="terms_content">{{ _i('Terms Content') }}</label>
                                        <textarea name="terms_content" id="terms_content" cols="30" rows="10"
                                                  class="form-control">{!! $landing->data->props->terms->content !!}</textarea>

                                    </div>
                                </div>
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
                                    {{ $title }} {{ _i('Additional') }}
                                </h3>
                            </div>
                        </header>
                        <div class="card-block g-pa-15">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="additional_title">{{ _i('Additional Title') }}</label>
                                        <input type="text" name="additional_title" id="additional_title"
                                               class="form-control"
                                               value="{{ $landing->data->props->additional_info->title }}">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="additional_content">{{ _i('Additional Content') }}</label>
                                        <textarea name="additional_content" id="additional_content" cols="30" rows="10"
                                                  class="form-control"> {!! $landing->data->props->additional_info->content !!}</textarea>

                                    </div>
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
            let landingPages = new LandingPages();
            landingPages.update();
        });
    </script>
@endsection

@extends('back.template')

@section('content')
    <form action="{{ route('sliders.store') }}" id="sliders-form" method="post" enctype="multipart/form-data">
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
                        <div class="noty_bar noty_type__warning noty_theme__unify--v1--dark g-mb-25">
                            <div class="noty_body">
                                <div class="g-mr-20">
                                    <div class="noty_body__icon">
                                        <i class="hs-admin-info"></i>
                                    </div>
                                </div>
                                <div>
                                    <p>
                                        {{ _i('The maximum file size is 5mb and the maximum width is 3440px') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="image">{{ _i('Image') }}</label>
                            <input type="file" name="image" id="image" class="opacity-0">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label class="">
                            <input type="checkbox" class="checkshow" name="personalize" autocomplete="off">
                            <span class="glyphicon glyphicon-ok">{{ _i('Enable only for moving sliders: ') }}</span>
                        </label>
                        <div class="div_a_show">
                            <div class="noty_bar noty_type__warning noty_theme__unify--v1--dark g-mb-25">
                                <div class="noty_body">
                                    <div class="g-mr-20">
                                        <div class="noty_body__icon">
                                            <i class="hs-admin-info"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <p>
                                            {{ _i('This image is only if you want to activate sliders with movement.The maximum file size is 5mb and the maximum width is 3440px') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="front">{{ _i('Image') }}</label>
                                <input type="file" name="front" id="front" class="opacity-0">
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
                                {{ _i('Slider details') }}
                            </h3>
                            <div class="media-body d-flex justify-content-end">
                                <a href="{{ route('sliders.index', [$template_element_type, $section]) }}"
                                   class="btn u-btn-3d u-btn-primary float-right">
                                    <i class="hs-admin-layout-list-thumb"></i>
                                    {{ _i('Go to list') }}
                                </a>
                            </div>
                        </div>
                    </header>

                    <div class="card-block g-pa-15">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="url">{{ _i('URL') }}</label>
                                    <input type="text" name="url" id="url" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="start_date">{{ _i('Start date') }}</label>
                                    <input type="text" name="start_date" id="start_date"
                                           class="form-control datetimepicker" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end_date">{{ _i('Finish date') }}</label>
                                    <input type="text" name="end_date" id="end_date"
                                           class="form-control datetimepicker" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="device">{{ _i('Devices') }}</label>
                                    <select name="device" id="device" class="form-control">
                                        <option value="">{{ _i('Select...') }}</option>
                                        <option value="*">{{ _i('All') }}</option>
                                        <option value="false">{{ _i('Desktop') }}</option>
                                        <option value="true">{{ _i('Mobile') }}</option>
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
                                            <option
                                                value="{{ $currency->iso }}" {{ $currency->iso == session('currency') ? 'selected' : '' }}>
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
                            @isset($menu)
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="route">{{ _i('Menu where it will be shown') }}</label>
                                        <select name="route[]" id="route" class="form-control" multiple>
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
                                    <label for="order">{{ _i('Order (optional)') }}</label>
                                    <input type="number" name="order" id="order" value="0" class="form-control"
                                           min="0">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="hidden" name="template_element_type"
                                           value="{{ $template_element_type }}">
                                    <input type="hidden" name="section" value="{{ $section }}">
                                    <button type="submit" class="btn u-btn-3d u-btn-primary" id="store"
                                            data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Uploading...') }}">
                                        <i class="hs-admin-upload"></i>
                                        {{ _i('Upload slider') }}
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
            let sliders = new Sliders();
            sliders.store();
        });
    </script>
    <script>
        $(function () {

            // obtener campos ocultar div
            var checkbox = $(".checkshow");
            var hidden = $(".div_a_show");
            //

            hidden.hide();
            checkbox.change(function () {
                if (checkbox.is(':checked')) {
                    //hidden.show();
                    $(".div_a_show").fadeIn("200")
                } else {
                    //hidden.hide();
                    $(".div_a_show").fadeOut("200")
                    $('input[type=checkbox]').prop('checked', false);// limpia los valores de checkbox al ser ocultado

                }
            });
        });
    </script>
@endsection

@extends('back.template')

@section('content')
    <form action="{{ route('section-images.store') }}" id="images-form" method="post" enctype="multipart/form-data">
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
                                        {{ _i('The recommended size for the image is %s pixels. Using a different size can cause misalignments on the page.', [$image->size]) }}
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
                            <span class="glyphicon glyphicon-ok">{{ _i('Enable only for moving images: ') }}</span>
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
                                            {{ _i('This image is only if you want to activate images with movement.The maximum file size is 5mb and the maximum width is 3440px') }}
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
                                {{ _i('Image details') }}
                            </h3>
                            <div class="media-body d-flex justify-content-end">
                                <a href="{{ route('section-images.index', [$template_element_type, $section]) }}"
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
                                    <label for="title">{{ _i('Title') }}</label>
                                    <input type="text" name="title" id="title" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="url">{{ _i('URL') }}</label>
                                    <input type="text" name="url" id="url" class="form-control">
                                </div>
                            </div>
                            @if($section == 'section-7')
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="category">{{ _i('Category') }}</label>
                                        <select name="category" id="category" class="form-control">
                                            <option value="">{{ _i('Select...') }}</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category }}">
                                                    {{ $category }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
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
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="status">{{ _i('Status') }}</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="true">{{ _i('Published') }}</option>
                                        <option value="false">{{ _i('Unpublished') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="hidden" name="template_element_type"
                                           value="{{ $template_element_type }}">
                                    <input type="hidden" name="section" value="{{ $section }}">
                                    <button type="submit" class="btn u-btn-3d u-btn-primary" id="store"
                                            data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Uploading...') }}">
                                        <i class="hs-admin-reload"></i>
                                        {{ _i('Upload image') }}
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
            let sectionImages = new SectionImages();
            sectionImages.store();
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

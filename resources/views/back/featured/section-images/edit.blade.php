@extends('back.template')

@section('content')
    <form action="{{ route('featured-images.update') }}" id="images-form" method="post" enctype="multipart/form-data">
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
                                <a href="{{ route('featured-images.index', [$template_element_type]) }}" class="btn u-btn-3d u-btn-primary float-right">
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
                                    <input type="text" name="title" id="title" class="form-control" value="{{ $image->title }}">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="button">{{ _i('Button text') }}</label>
                                    <input type="text" name="button" id="button" class="form-control" value="{{ $image->button }}">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description">{{ _i('Description') }}</label>
                                    <textarea name="description" id="description" cols="30" rows="5" class="form-control">{{ $image->description }}</textarea>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="url">{{ _i('URL') }}</label>
                                    <input type="text" name="url" id="url" class="form-control" value="{{ $image->url }}">
                                </div>
                            </div>
                            @if ($props->button === true)
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="button">{{ _i('Button text') }}</label>
                                        <input type="text" name="button" id="button" class="form-control" value="{{ $image->button }}">
                                    </div>
                                </div>
                            @endif
                            @if ($props->description === true)
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="description">{{ _i('Description') }}</label>
                                        <textarea name="description" id="description" cols="30" rows="5" class="form-control">{{ $image->description }}</textarea>
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="status">{{ _i('Status') }}</label>
                                    <select name="status" id="status" class="form-control">
                                        @if (is_null($image->status))
                                            <option value="true" selected>
                                                {{ _i('Published') }}
                                            </option>
                                            <option value="false">
                                                {{ _i('Unpublished') }}
                                            </option>
                                        @else
                                            <option value="true" {{ $image->status ? 'selected' : '' }}>
                                                {{ _i('Published') }}
                                            </option>
                                            <option value="false" {{ !$image->status ? 'selected' : '' }}>
                                                {{ _i('Unpublished') }}
                                            </option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="hidden" name="image_id" value="{{ $image->id }}">
                                    <input type="hidden" name="file" value="{{ $image->file }}">
                                    <input type="hidden" name="image" value="{{ $image->file }}">
                                    <input type="hidden" name="template_element_type" value="{{ $template_element_type }}">
                                    <button type="submit" class="btn u-btn-3d u-btn-primary" id="update"
                                            data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Updating...') }}">
                                        <i class="hs-admin-reload"></i>
                                        {{ _i('Update image') }}
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
            sectionImages.update("{!! $image->image !!}");
        });
    </script>
@endsection

@extends('back.template')

@section('content')
    <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
        <header
            class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
            <div class="media">
                <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                    {{ $title }}
                </h3>
                <div class="media-body d-flex justify-content-end">
                    <a href="{{ route('pages.index') }}" class="btn u-btn-3d u-btn-primary float-right">
                        <i class="hs-admin-layout-list-thumb"></i>
                        {{ _i('Go to list') }}
                    </a>
                </div>
            </div>
        </header>
        <div class="card-block g-pa-15">
            <form action="{{ route('pages.update') }}" method="post" id="posts-form">
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="title">{{ _i('Title') }}</label>
                            <input type="text" name="title" id="title" class="form-control" value="{{ $page->title }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="status">{{ _i('Status') }}</label>
                            <select name="status" id="status" class="form-control">
                                <option value="true" {{ $page->status ? 'selected' : '' }}>
                                    {{ _i('Published') }}
                                </option>
                                <option value="false" {{ !$page->status ? 'selected' : '' }}>
                                    {{ _i('Unpublished') }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="content">{{ _i('Content') }}</label>
                            <textarea name="content" id="content" cols="30" rows="10"
                                      class="form-control">{!! $page->content !!}</textarea>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <input type="hidden" name="id" id="id" value="{{ $page->id }}">
                            <button type="button" class="btn u-btn-3d u-btn-primary" id="update"
                                    data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Updating...') }}">
                                <i class="hs-admin-reload"></i>
                                {{ _i('Update page') }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function () {
            let pages = new Pages();
            pages.update();
        });
    </script>
@endsection

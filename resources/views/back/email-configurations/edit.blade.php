@extends('back.template')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
            <header
                class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                <div class="media">
                    <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                        {{ $title }}
                    </h3>
                    <div class="media-body d-flex justify-content-end">
                        <a href="{{ route('email-configurations.index') }}" class="btn u-btn-3d u-btn-primary float-right">
                            <i class="hs-admin-layout-list-thumb"></i>
                            {{ _i('Go to list') }}
                        </a>
                    </div>
                </div>
            </header>
            <form action="{{ route('email-configurations.updateEmail') }}" id="email-form" method="post" enctype="multipart/form-data">
                <div class="card-block g-pa-15">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title">{{ _i('Title') }}</label>
                                <input type="text" name="title" id="title" class="form-control" autocomplete="off" value="{{$email->title}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="subtitle">{{ _i('Subtitle') }}</label>
                                <input type="text" class="form-control" name="subtitle" id="subtitle" autocomplete="off" value="{{$email->subtitle}}">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="content">{{ _i('Content') }}</label>
                                <textarea type="text" class="form-control" name="content" id="content" autocomplete="off">{!! $email->content !!}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="button">{{ _i('Button') }}</label>
                                <input type="text" class="form-control" name="button" id="button" autocomplete="off" value="{{$email->button}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="footer">{{ _i('Footer') }}</label>
                                <input type="text" class="form-control" name="footer" id="footer" autocomplete="off" value="{{$email->footer}}">
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

                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="hidden" name="id" name="id" value="{{$email->email_type_id }}">
                                <button type="submit" class="btn u-btn-3d u-btn-primary" id="update-email"
                                        data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Updating...') }}">
                                    <i class="hs-admin-reload"></i>
                                    {{ _i('Update') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('scripts')
    <script>
        $(function () {
            let emailType = new EmailConfigurations();
            emailType.updateEmail();
        });
    </script>
@endsection


@extends('back.template')

@section('content')
    <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
        <header class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
            <div class="media">
                <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                    {{ $title }}
                </h3>
                <div class="media-body d-flex justify-content-end">
                    <a href="{{ route('email-templates.index') }}" class="btn u-btn-3d u-btn-primary float-right">
                        <i class="hs-admin-layout-list-thumb"></i>
                        {{ _i('Go to list') }}
                    </a>
                </div>
            </div>
        </header>
        <form action="{{ route('email-templates.store') }}" id="email-templates-form" method="post">
            <div class="card-block g-pa-15">
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="title">{{ _i('Title') }}</label>
                            <input type="text" name="title" id="title" class="form-control" autocomplete="off">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="subject">{{ _i('Subject') }}</label>
                            <input type="text" name="subject" id="subject" class="form-control" autocomplete="off">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group"><label for="language">{{ _i('Language') }}</label>
                            <select name="language" id="language" class="form-control">
                                <option value="">{{ _i('Select...') }}</option>
                                <option value="*">{{ _i('All languages') }}</option>
                                @foreach ($languages as $language)
                                    <option value="{{ $language['iso'] }}">
                                        {{ $language['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="currency">{{ _i('Currency') }}</label>
                            <select name="currency" id="currency" class="form-control">
                                <option value="">{{ _i('Select...') }}</option>
                                <option value="*">{{ _i('All currencies') }}</option>
                                @foreach ($whitelabel_currencies as $currency)
                                    <option value="{{ $currency->iso }}" {{ $currency->iso == session('currency') ? 'selected' : '' }}>
                                        {{ $currency->iso == 'VEF' ? $free_currency->currency_name : $currency->iso . " ({$currency->name})" }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group"><label for="status">{{ _i('Status') }}</label>
                            <select name="status" id="status" class="form-control">
                                <option value="true">{{ _i('Active') }}</option>
                                <option value="false">{{ _i('Inactive') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <hr>
                        <iframe id="mosaico" src="{{ env('MOSAICO_SERVER') }}/editor.html?route={{ route('email-templates.upload-images') }}" frameborder="0" width="100%" height="900px"></iframe>
                        <hr>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <input type="hidden" name="email_templates_type_id" id="email_templates_type_id" value="{{$email_templates_type_id}}">
                            <button type="button" class="btn u-btn-3d u-btn-primary" id="store"
                                    data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Creating...') }}">
                                <i class="hs-admin-save"></i>
                                {{ _i('Create') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        $(function() {
            let emailTemplates = new EmailTemplates();
            emailTemplates.store();
        });
    </script>
@endsection

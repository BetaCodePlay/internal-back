@extends('back.template')

@section('content')
    <form action="{{ route('marketing-campaigns.update') }}" id="marketing-campaigns-form" method="post" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-12">
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                    <header
                        class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                {{ _i('Campaign details') }}
                            </h3>
                            <div class="media-body d-flex justify-content-end">
                                <a href="{{ route('marketing-campaigns.index') }}" class="btn u-btn-3d u-btn-primary float-right">
                                    <i class="hs-admin-layout-list-thumb"></i>
                                    {{ _i('Go to list') }}
                                </a>
                            </div>
                        </div>
                    </header>
                    <div class="card-block g-pa-15">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="title">{{ _i('Title') }}</label>
                                    <input type="text" name="title" id="title" class="form-control" value="{{ $campaign->title }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="language">{{ _i('Language') }}</label>
                                    <select name="language" id="language" class="form-control">
                                        <option value="*" {{ $campaign->language == '*' ? 'selected' : '' }}>
                                            {{ _i('All languages') }}
                                        </option>
                                        @foreach ($languages as $language)
                                            <option value="{{ $language['iso'] }}" {{ $campaign->language == $language['iso'] ? 'selected' : '' }}>
                                                {{ $language['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="currency">{{ _i('Currency') }}</label>
                                    <select name="currency" id="currency" class="form-control">
                                        <option value="*" {{ $campaign->currency_iso == '*' ? 'selected' : '' }}>
                                            {{ _i('All currencies') }}
                                        </option>
                                        @foreach ($whitelabel_currencies as $currency)
                                            <option value="{{ $currency->iso }}" {{ $currency->iso == session('currency') ? 'selected' : '' }}>
                                                {{ $currency->iso == 'VEF' ? $free_currency->currency_name : $currency->iso . " ({$currency->name})" }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="segment">{{ _i('Segment') }}</label>
                                    <select name="segment" id="segment" class="form-control">
                                        @foreach ($segments as $segment)
                                            <option value="{{ $segment->id }}" {{ $campaign->segment_id == $segment->id ? 'selected' : '' }}>
                                                {{ $segment->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="email_template">{{ _i('Email template') }}</label>
                                    <select name="email_template" id="email_template" class="form-control">
                                        @foreach ($email_templates as $email_template)
                                            <option value="{{ $email_template->id }}" {{ $email_template->email_template_id == $email_template->id ? 'selected' : '' }}>
                                                {{ $email_template->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="scheduled_date">{{ _i('Scheduled date') }}</label>
                                    <input type="text" name="scheduled_date" id="scheduled_date"
                                           class="form-control datetimepicker" value="{{ $campaign->date }}" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input name="id" id="id" value="{{ $campaign->id }}" type="hidden">
                                    <input name="status" id="status" value="{{ $campaign->status }}" type="hidden">
                                    <button type="submit" class="btn u-btn-3d u-btn-primary" id="update"
                                            data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Updating...') }}">
                                        <i class="hs-admin-reload"></i>
                                        {{ _i('Update campaign') }}
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
            let marketingCampaign = new MarketingCampaigns();
            marketingCampaign.update();
        });
    </script>
@endsection

@extends('back.template')

@section('content')
    <form action="{{ route('notifications.store') }}" id="notifications-form" method="post" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-3">
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
            <div class="col-md-9">
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                    <header
                        class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                {{ _i('Notification details') }}
                            </h3>
                            <div class="media-body d-flex justify-content-end">
                                <a href="{{ route('notifications.index') }}" class="btn u-btn-3d u-btn-primary float-right">
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
                                    <label for="title">{{ _i('Title') }}</label>
                                    <input type="text" name="title" id="title" class="form-control">
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
                                        <option value="true">{{ _i('Active') }}</option>
                                        <option value="false">{{ _i('Inactive') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type">{{ _i('Notification type') }}</label>
                                    <select name="type" id="type" class="form-control">
                                        <option value="">{{ _i('Select...') }}</option>
                                        @foreach ($types as $type)
                                            @if ( \Dotworkers\Configurations\Configurations::getWhitelabel() == 68 )
                                                @if($type->id != \App\Notifications\Enums\NotificationTypes::$group && $type->id != \App\Notifications\Enums\NotificationTypes::$segment && $type->id != \App\Notifications\Enums\NotificationTypes::$excel)
                                                    <option value="{{ $type->id }}">
                                                        {{ $type->name }}
                                                    </option>
                                                @endif
                                            @else
                                                @if($type->id != \App\Notifications\Enums\NotificationTypes::$group && $type->id != \App\Notifications\Enums\NotificationTypes::$segment)
                                                    <option value="{{ $type->id }}">
                                                        {{ $type->name }}
                                                    </option>
                                                @endif
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group d-none search-user">
                                    <label for="user">{{ _i('User') }}</label>
                                    <select class="form-control select2" id="user" name="users[]"
                                            data-route="{{ route('users.search-username') }}" multiple>
                                        <option></option>
                                    </select>
                                </div>
                                {{--<div class="form-group d-none segment">--}}
                                {{--    <label for="segments">{{ _i('Segments') }}</label>--}}
                                {{--    <select name="segments" id="segments" class="form-control select2" style="width: 100%">--}}
                                {{--        <option value="">{{ _i('Select...') }}</option>--}}
                                {{--        @foreach ($segments as $segment)--}}
                                {{--            <option value="{{ $segment->id }}">--}}
                                {{--                {{ $segment->name }}--}}
                                {{--            </option>--}}
                                {{--        @endforeach--}}
                                {{--    </select>--}}
                                {{--</div>--}}
                                <div class="form-group d-none excel">
                                    <label for="excel">{{ _i('Excel') }}</label>
                                    <input type="file" name="excel_file" id="excel_file" class="opacity-0">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="content">{{ _i('Content') }}</label>
                                    <textarea name="content" id="content" cols="30" rows="5" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="submit" class="btn u-btn-3d u-btn-primary" id="store"
                                            data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Publishing...') }}">
                                        <i class="hs-admin-upload"></i>
                                        {{ _i('Publish notification') }}
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
            let notifications = new Notifications();
            let users = new Users();
            notifications.store();
            notifications.typeNotification();
            users.select2Users('{{ _i('Select user') }}');
        });
    </script>
@endsection

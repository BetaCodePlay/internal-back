@extends('back.template')

@section('content')
    <form action="{{ route('notifications.update') }}" id="notifications-form" method="post"
          enctype="multipart/form-data">
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
                                <a href="{{ route('notifications.index') }}"
                                   class="btn u-btn-3d u-btn-primary float-right">
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
                                    <input type="text" name="title" id="title" class="form-control"
                                           value="{{ $notification->title }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="language">{{ _i('Language') }}</label>
                                    <select name="language" id="language" class="form-control">
                                        <option value="*" {{ $notification->language == '*' ? 'selected' : '' }}>
                                            {{ _i('All') }}
                                        </option>
                                        @foreach ($languages as $language)
                                            <option
                                                value="{{ $language['iso'] }}" {{ $notification->language == $language['iso'] ? 'selected' : '' }}>
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
                                        <option value="*" {{ $notification->currency_iso == '*' ? 'selected' : '' }}>
                                            {{ _i('All') }}
                                        </option>
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
                                        <option value="true" {{ $notification->status ? 'selected' : '' }}>
                                            {{ _i('Active') }}
                                        </option>
                                        <option value="false" {{ !$notification->status ? 'selected' : '' }}>
                                            {{ _i('Inactive') }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            @if($notification->notification_type_id == \App\Notifications\Enums\NotificationTypes::$user
                                //|| $notification->notification_type_id == \App\Notifications\Enums\NotificationTypes::$segment
                                || $notification->notification_type_id == \App\Notifications\Enums\NotificationTypes::$excel)
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <br>
                                        <a href="#users-notificacion" class="btn u-btn-3d u-btn-primary"
                                           data-toggle="modal" id="create-segment">
                                            <i class="hs-admin-save"></i>
                                            {{ _i('Show users') }}
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <br>
                                        <h5 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                            {{ _i('All users') }}
                                        </h5>
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="content">{{ _i('Content') }}</label>
                                    <textarea name="content" id="content"
                                              class="form-control">{!! $notification->content !!}</textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="hidden" name="id" value="{{ $notification->id }}">
                                    <input type="hidden" name="file" id="file" value="{{ $notification->file }}">
                                    <input type="hidden" name="image" id="image" value="{{ $notification->file }}">
                                    <input type="hidden" name="type_notification" id="type_notification"
                                           value="{{ $notification->notification_type_id }}">
                                    <button type="submit" class="btn u-btn-3d u-btn-primary" id="update"
                                            data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Updating...') }}">
                                        <i class="hs-admin-reload"></i>
                                        {{ _i('Update notification') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    @include('back.notifications.modals.users')
@endsection

@section('scripts')
    <script>
        $(function () {
            let notifications = new Notifications;
            let users = new Users();
            notifications.update("{!! $notification->image !!}");
            notifications.usersNotificacion();
            notifications.typeNotificationEdit({{$notification->notification_type_id}});
            users.select2Users('{{ _i('Select user') }}');
        });
    </script>
@endsection

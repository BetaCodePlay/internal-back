@extends('back.template')

@section('content')
    <form action="{{ route('store.rewards.update') }}" id="reward-form" method="post" enctype="multipart/form-data">
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
                        <div class="noty_bar noty_type__warning noty_theme__unify--v1--dark g-mb-25">
                            <div class="noty_body">
                                <div class="g-mr-20">
                                    <div class="noty_body__icon">
                                        <i class="hs-admin-info"></i>
                                    </div>
                                </div>
                                <div>
                                    <p>
                                        {{ _i('The recommended size for the image is 280x280 pixels. Using a different size can cause misalignments on the page.') }}
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
            <div class="col-md-9">
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                    <header
                        class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                {{ _i('Rewards details') }}
                            </h3>
                            <div class="media-body d-flex justify-content-end">
                                <a href="{{ route('store.rewards.index') }}" class="btn u-btn-3d u-btn-primary float-right">
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
                                    <label for="name">{{ _i('Name') }}</label>
                                    <input type="text" name="name" id="name" class="form-control" value="{{ $reward->name }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="description">{{ _i('Description to help the user understand the reward') }}</label>
                                    <input type="text" name="description" id="description" class="form-control" value="{{ $reward->description }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="quantity">{{ _i('Available quantity (optional)') }}</label>
                                    <input type="text" name="quantity" id="quantity" class="form-control" value="{{ $reward->quantity }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="currency">{{ _i('Currency') }}</label>
                                    <select name="currency" id="currency" class="form-control">
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
                                    <label for="points">{{ _i('Points needed to get the reward') }}</label>
                                    <input type="text" name="points" id="points" class="form-control" value="{{ $reward->points }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="amount">{{ _i('Amount that the reward will deliver as a prize') }}</label>
                                    <input type="number" name="amount" id="amount" class="form-control" value="{{ $reward->data->amount }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="start_date">{{ _i('Start date (optional)') }}</label>
                                    <input type="text" name="start_date" id="start_date"
                                           class="form-control datepicker" value="{{ $reward->start }}" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end_date">{{ _i('Finish date (optional)') }}</label>
                                    <input type="text" name="end_date" id="end_date" class="form-control datepicker" value="{{ $reward->end }}" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="language">{{ _i('Language') }}</label>
                                    <select name="language" id="language" class="form-control">
                                        <option value="*" {{ $reward->language == '*' ? 'selected' : '' }}>
                                            {{ _i('All') }}
                                        </option>
                                        @foreach ($languages as $language)
                                            <option value="{{ $language['iso'] }}" {{ $reward->language == $language['iso'] ? 'selected' : '' }}>
                                                {{ $language['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">{{ _i('Status') }}</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="true" {{ $reward->status ? 'selected' : '' }}>
                                            {{ _i('Published') }}
                                        </option>
                                        <option value="false" {{ !$reward->status ? 'selected' : '' }}>
                                            {{ _i('Unpublished') }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="currency">{{ _i('Category (optional)')  }}</label>
                                    <select name="category" id="category" class="form-control">
                                        <option value="">{{ _i('Select...') }}</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" {{ $reward->category_id == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="hidden" name="id" value="{{ $reward->id }}">
                                    <input type="hidden" name="file" id="file" value="{{ $reward->file }}">
                                    <input type="hidden" name="image" id="image" value="{{ $reward->file }}">
                                    <button type="submit" class="btn u-btn-3d u-btn-primary" id="update"
                                            data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Updating...') }}">
                                        <i class="hs-admin-reload"></i>
                                        {{ _i('Update reward') }}
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
            let store = new Store();
            store.update("{!! $reward->image !!}");
        });
    </script>
@endsection

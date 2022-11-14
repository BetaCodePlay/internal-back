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
                    </div>
                </header>
                <div class="card-block g-pa-15">
                    <form action="{{ route('referrals.referral-user-data') }}" method="post" id="add-referral-user-form">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="user">{{ _i('User') }}</label>
                                    <select class="form-control select2" id="user" name="user" data-route="{{ route('users.search-username') }}">
                                        <option></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="user_refer">{{ _i('User to refer') }}</label>
                                    <select class="form-control select2" id="user_refer" name="user_refer" data-route="{{ route('users.search-username') }}">
                                        <option></option>
                                    </select>
                                    {{--<input type="text" name="username" id="username" class="form-control" autocomplete="off">--}}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="currency">{{ _i('Currency') }}</label>
                                    <select name="currency" id="currency" class="form-control">
                                        <option value="">{{ _i('Select...') }}</option>
                                        @foreach ($whitelabel_currencies as $currency)
                                            <option value="{{ $currency->iso }}" {{ $currency->iso == session('currency') ? 'selected' : '' }}>
                                                {{ $currency->iso == 'VEF' ? $free_currency->currency_name : $currency->iso . " ({$currency->name})" }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="button" class="btn u-btn-3d u-btn-primary" id="create"
                                            data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Adding...') }}">
                                        <i class="hs-admin-reload"></i>
                                        {{ _i('Add user') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function () {
            let referrals = new Referrals();
            referrals.addReferral();
            referrals.select2Users('{{ _i('Select user') }}');
            referrals.select2UserRefer('{{ _i('Select user') }}');
        });
    </script>
@endsection

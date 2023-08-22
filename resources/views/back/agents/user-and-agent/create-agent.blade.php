@extends('back.template')

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                <header
                    class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                    <div class="media">
                        <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                            {{ $title}}
                        </h3>
                    </div>
                </header>
                <div class="card-block g-pa-15">
                    <form action="{{ route('agents.store') }}" method="post" id="create-agents-form">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-12 col-sm-6">
                                    <div class="form-group">
                                        <label for="username">{{ _i('Username') }}</label>
                                        <input type="text" name="username" class="form-control" autocomplete="off">
                                        <small
                                            class="form-text text-muted">{{ _i('Only letters and numbers without spaces (4-12 characters)') }}</small>
                                        <small
                                            class="form-text text-muted">{{ _i('The username cannot be changed later') }}</small>
                                    </div>
                                </div>
                                {{--                                <div class="col-12 col-sm-4">--}}
                                {{--                                    <div class="form-group">--}}
                                {{--                                        <label for="username">{{ _i('Email') }}</label>--}}
                                {{--                                        <input type="email" name="email" class="form-control" autocomplete="off" required>--}}
                                {{--                                        <small class="form-text text-muted">{{ _i('Email can be changed later') }}</small>--}}
                                {{--                                    </div>--}}
                                {{--                                </div>--}}
                                <div class="col-12 col-sm-6">
                                    <label for="password">{{ _i('Password') }}</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="password">
                                        <div class="input-group-append">
                                            <button
                                                class="btn u-input-btn--v1 g-width-40 u-btn-primary g-rounded-right-4 u-btn-3d refresh-password"
                                                type="button">
                                                <i class="hs-admin-reload g-absolute-centered g-font-size-16 g-color-white"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <small
                                        class="form-text text-muted">{{ _i('Minimum 8 characters, 1 letter and 1 number') }}</small>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="form-group">
                                        <label
                                            for="balance">{{ _i('Operational balance (It will be credited in %s)', [session('currency')]) }}</label>
                                        <input type="number" name="balance" class="form-control">
                                        <small class="form-text text-danger">
                                            {{ _i('Available') }}: <span class="balance"></span>
                                        </small>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 option_data_agent">
                                    <div class="form-group">
                                        <label for="percentage">{{ _i('Percentage') }}</label>
                                        <input type="number" name="percentage" class="form-control"
                                               placeholder="{{ _i('Rango disponible de 1 - 99') }}" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="form-group">
                                        <label for="master">{{ _i('Agent type') }}</label><br>
                                        <select name="master" id="master" class="form-control agent_type"
                                                style="width: 100%">
                                            <option value="true">
                                                {{ _i('Master agent') }}
                                            </option>
                                            <option value="false">
                                                {{ _i('Cashier') }}
                                            </option>
                                        </select>
                                        <small class="form-text text-muted">
                                            {{ _i('Master agents can have subagents and players dependent on them') }}
                                        </small>
                                        <small class="form-text text-muted">
                                            {{ _i('Cashiers can only have players dependent on them.') }}
                                        </small>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 option_data_agent">
                                    <div class="form-group">
                                        <label for="timezone">{{ _i('Timezone') }}</label>
                                        <select name="timezone" class="form-control" style="width: 100%">
                                            <option value="">{{ _i('Select...') }}</option>
                                            @foreach ($timezones as $timezone)
                                                <option
                                                    value="{{ $timezone }}" {{ $timezone == session()->get('timezone') ? 'selected' : '' }}>
                                                    {{ $timezone }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                {{-- //TODO CAMBIOS SOLO PRA EL ROL: Admin beet sweet--}}
                                @if(!in_array(\Dotworkers\Security\Enums\Roles::$admin_Beet_sweet, session('roles')))
                                    <div class="col-12 col-sm-6 option_data_agent">
                                        <div class="form-group">
                                            <label for="currencies">{{ _i('Currencies') }}</label>
                                            <select name="currencies[]" class="form-control" multiple
                                                    style="width: 100%">
                                                <option value="">{{ _i('Select...') }}</option>
                                                @foreach ($whitelabel_currencies as $currency)
                                                    <option
                                                        value="{{ $currency->iso }}" {{ $currency->iso == session('currency') ? 'selected' : '' }}>
                                                        {{ $currency->iso == 'VEF' ? $free_currency->currency_name : $currency->iso . " ({$currency->name})" }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @else
                                    {{--                            <input name="timezone" value="{{session('timezone')}}" type="hidden">--}}
                                    <input name="currencies[]" value="{{session('currency')}}" type="hidden">
                                @endif
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn u-btn-primary u-btn-3d" id="create-agent"
                                    data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Please wait...') }}">
                                {{ _i('Create agent') }}
                            </button>
                            <button type="button" class="btn u-btn-3d u-btn-bluegray u-btn-3d" data-dismiss="modal">
                                {{ _i('Close') }}
                            </button>
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
            let agents = new Agents();
            agents.dashboard();
            agents.optionsFormUser();
            agents.storeAgents();
            agents.storeUsers();
            agents.balanceAgentCurrent('{{route('agents.consult.balance.by.type')}}');
        });
    </script>
@endsection

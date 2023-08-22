@extends('back.template')

@section('content')
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
                    <form action="{{ route('agents.add-users-data') }}" method="post" id="add-users-form">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="agent">{{ _i('Agent') }}</label>
                                    <select name="agent" id="agent" class="form-control">
                                        <option value="">{{ _i('Select...') }}</option>
                                        @foreach ($agents as $agent)
                                            <option value="{{ $agent['id'] }}">
                                                {{ $agent['username'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="username">{{ _i('User') }}</label>
                                    <input type="text" name="username" id="username" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="button" class="btn u-btn-3d u-btn-primary" id="update"
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
        <div class="col-md-6">
            <div class="noty_bar noty_type__warning noty_theme__unify--v1--dark g-mb-25">
                <div class="noty_body">
                    <div class="g-mr-20">
                        <div class="noty_body__icon">
                            <i class="hs-admin-info"></i>
                        </div>
                    </div>
                    <div>
                        <p>
                            {{ _i('The agent selector is loaded by the session currency, verify that the user has the session currency.') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function () {
            let agents = new Agents();
            agents.addUsers();
        });
    </script>
@endsection

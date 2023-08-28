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
                    <form action="{{ route('agents.store-main-agents') }}" id="main-agents-form" method="post">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="client">{{ _i('Whitelabel') }}</label>
                                    <select name="whitelabel" id="whitelabel" class="form-control">
                                        <option value="">{{ _i('Select...') }}</option>
                                        @foreach ($whitelabels as $whitelabel)
                                            <option value="{{ $whitelabel->id }}">
                                                {{ $whitelabel->description }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3" hidden>
                                <div class="form-group">
                                    <label for="defaultCurrency">{{ _i('Update Currency') }}</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="true" name="update_currency" id="defaultCurrency">
                                        <label class="form-check-label" for="defaultCurrency">
                                            {{ _i('Update Currency all users') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="button" class="btn u-btn-3d u-btn-primary" id="save"
                                            data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Creating...') }}">
                                        <i class="hs-admin-save"></i>
                                        {{ _i('Create') }}
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
            let agents = new Agents();
            agents.mainAgents();
        });
    </script>
@endsection


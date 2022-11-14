@extends('back.template')

@section('content')
    <form action="{{ route('configurations.levels.update') }}" method="post" id="levels-form" data-levels-route="{{ route('configurations.levels.data') }}">
        <div class="row">
            <div class="col-md-3">
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                    <header class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                {{ $title }}
                            </h3>
                        </div>
                    </header>
                    <div class="card-block g-pa-15">
                        <div class="form-group">
                            <label for="whitelabel">{{ _i('Whitelabel') }}</label>
                            <select name="whitelabel" id="whitelabel" class="form-control">
                                <option value="">{{ _i('Select...') }}</option>
                                @foreach ($whitelabels as $whitelabel)
                                    <option value="{{ $whitelabel->id }}">
                                        {{ $whitelabel->description }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <button type="button" class="btn u-btn-3d u-btn-primary" id="load-button"
                                    data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Loading...') }}">
                                {{ _i('Upload data') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                    <header class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0 title">
                                {{ _i('Levels name') }}
                            </h3>
                        </div>
                    </header>
                    <div class="card-block g-pa-15">
                        <div class="row">
                            <div class="col-md-6">
                                <ol>
                                    <li>
                                        <div class="form-group">
                                            <input type="text" name="levels[0]" class="form-control"
                                                   placeholder="{{ _i('Level 1') }}">
                                        </div>
                                    </li>
                                    <li>
                                        <div class="form-group">
                                            <input type="text" name="levels[1]" class="form-control"
                                                   placeholder="{{ _i('Level 2') }}">
                                        </div>
                                    </li>
                                    <li>
                                        <div class="form-group">
                                            <input type="text" name="levels[2]" class="form-control"
                                                   placeholder="{{ _i('Level 3') }}">
                                        </div>
                                    </li>
                                    <li>
                                        <div class="form-group">
                                            <input type="text" name="levels[3]" class="form-control"
                                                   placeholder="{{ _i('Level 4') }}">
                                        </div>
                                    </li>
                                    <li>
                                        <div class="form-group">
                                            <input type="text" name="levels[4]" class="form-control"
                                                   placeholder="{{ _i('Level 5') }}">
                                        </div>
                                    </li>
                                </ol>
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
                                                {{ _i('The names of the levels are used to show the user and the DotPanel operator the level that they have on the platform.') }}
                                            </p>
                                            <p>
                                                {{ _i('The name of the level is visible in the user profile.') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="button" class="btn u-btn-3d u-btn-primary" id="update-button"
                                            data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Updating...') }}"
                                            disabled>
                                        {{ _i('Update settings') }}
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
            let configurations = new Configurations();
            configurations.levels();
        });
    </script>
@endsection

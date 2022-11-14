@extends('back.template')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                <header class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                    <div class="media">
                        <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                            {{ $title }}
                        </h3>
                    </div>
                    <div class="media-body d-flex justify-content-end">
                        <a href="{{ route('section-games.index', [$template_element_type, $section]) }}" class="btn u-btn-3d u-btn-primary float-right">
                            <i class="hs-admin-layout-list-thumb"></i>
                            {{ _i('Go to list') }}
                        </a>
                    </div>
                </header>
                <div class="card-block g-pa-15">
                <form action="{{ route('section-games.store') }}" id="games-form" method="post">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="games">{{ _i('Games') }}</label>
                                    <select name="games[]" id="games" class="form-control" multiple>
                                        <option value="">{{ _i('Select...') }}</option>
                                        @foreach ($games as $game)
                                            <option value="{{ $game->id }}">
                                                {{ $game->description }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @if($section == 'section-7')
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="additional_info">{{ _i('Additional Info') }}</label>
                                        <select name="additional_info" id="additional_info" class="form-control">
                                            <option value="">{{ _i('Select...') }}</option>
                                            @foreach ($additionals as $additional)
                                                <option value="{{ $additional }}">
                                                    {{ $additional }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif

                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="hidden" name="section" value="{{ $section }}">
                                    <button type="button" class="btn u-btn-3d u-btn-primary" id="save"
                                            data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Saving...') }}">
                                        <i class="hs-admin-save"></i>
                                        {{ _i('Save') }}
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
            let sectionGames = new SectionGames();
            sectionGames.store();
        });
    </script>
@endsection

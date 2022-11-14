@extends('back.template')

@section('content')
    <div class="noty_bar noty_type__warning noty_theme__unify--v1--dark g-mb-25">
        <div class="noty_body">
            <div class="g-mr-20">
                <div class="noty_body__icon">
                    <i class="hs-admin-info"></i>
                </div>
            </div>
            <div>
                <p>
                    {{ _i('You do not have credentials for $%, request your credentials.', [$provider_name]) }}
                </p>
            </div>
        </div>
    </div>
@endsection

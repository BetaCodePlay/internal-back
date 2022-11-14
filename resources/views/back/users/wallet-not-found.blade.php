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
                    {{ _i('The user does not have an active wallet in the %s currency. If you want to create the wallet click on the following button', [$currency]) }}
                </p>
                <a href="{{ route('wallets.create', [$user->id, $user->username, $user->uuid, $currency]) }}" class="btn u-btn-3d u-btn-primary">
                    {{ _i('Create wallet') }}
                </a>
            </div>
        </div>
    </div>
@endsection

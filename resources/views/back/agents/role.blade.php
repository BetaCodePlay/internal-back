@extends('back.template')

@section('styles')

@endsection

@section('content')
    <div class="wrapper-title g-pb-30">
        {{ _i('Role and permission management') }}
    </div>

    <div class="page-role">
        <div class="page-top">
            <div class="search-input">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" class="form-control" placeholder="{{ _i('Search') }}">
            </div>
            <button type="button" class="btn btn-theme"><i class="fa-solid fa-plus"></i> {{ _i('Create role') }}</button>
        </div>
    </div>
@endsection

@section('scripts')

@endsection

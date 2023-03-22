<form id="header-search-form" class="g-py-12" aria-labelledby="searchInvoker" action="{{ route('users.search') }}" method="get">
    <div class="col-sm">
        <div class="input-group">
            @can('access', [\Dotworkers\Security\Enums\Permissions::$users_search])
                <input class="form-control form-control-md g-rounded-4" type="text" name="username"
                       placeholder="{{ _i('Search user') }} {{$iphone}}" value="{{ isset($username) ? $username : '' }}">
                <button type="submit"
                        class="btn u-btn-outline-primary g-brd-none g-bg-transparent--hover g-pos-abs g-top-0 g-right-0 d-flex g-width-40 h-100 align-items-center justify-content-center g-font-size-18 g-z-index-2">
                    <i class="hs-admin-search"></i>
                </button>
            @endcan
        </div>
    </div>
</form>

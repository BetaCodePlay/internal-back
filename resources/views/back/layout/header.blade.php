<header id="js-header" class="u-header u-header--sticky-top">
    <div class="u-header__section u-header__section--admin-dark g-min-height-65">
        <nav class="navbar no-gutters g-pa-0">
            <div class="col-auto d-flex flex-nowrap u-header-logo-toggler g-py-12">
                <a href="{{ route('core.dashboard') }}"
                   class="navbar-brand d-flex align-self-center g-hidden-xs-down g-line-height-1 py-0 g-mt-0">
                    @if(!empty($logo))
                        @if(!is_null($logo->img_dark))
                            <img src="{{$logo->img_dark }}" alt="Bloko" width="180" height="37">
                        @endif
                    @endif
{{--                @if (\Dotworkers\Configurations\Configurations::getWhitelabel() == 109)--}}
{{--                    <img src="{{ asset('commons/img/bloko-light.png') }}" alt="Bloko" width="180" height="37">--}}
{{--                    @elseif(\Dotworkers\Configurations\Configurations::getWhitelabel() == 125)--}}
{{--                        <img src="{{ asset('commons/img/lat-soft-demo.png') }}" alt="LatSoft" width="180" height="37">--}}
{{--                    @elseif(\Dotworkers\Configurations\Configurations::getWhitelabel() == 132 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 133 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 141 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 142 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 144)--}}
{{--                        <img src="{{ asset('commons/img/lat-soft-logo.jpeg') }}" alt="LatSoft" width="180" height="37">--}}
{{--                    @else--}}
{{--                        <img src="{{ asset('commons/img/dotpanel-light.png') }}" width="180" height="37" alt="Dotpanel">--}}
{{--                    @endif--}}
                </a>
                <a class="js-side-nav u-header__nav-toggler d-flex align-self-center ml-auto" href="#!"
                   data-hssm-class="u-side-nav--mini u-sidebar-navigation-v1--mini"
                   data-hssm-body-class="u-side-nav-mini" data-hssm-is-close-all-except-this="true"
                   data-hssm-target="#sideNav">
                    <i class="hs-admin-align-left"></i>
                </a>
            </div>
            <form id="header-search-form" class="u-header--search col-sm g-py-12 g-ml-15--sm g-ml-20--md g-mr-10--sm"
                  aria-labelledby="searchInvoker" action="{{ route('users.search') }}" method="get">
                <div class="input-group g-max-width-450">
                    @can('access', [\Dotworkers\Security\Enums\Permissions::$users_search])
                        <input class="form-control form-control-md g-rounded-4" type="text" name="username"
                               placeholder="{{ _i('Search user') }}" value="{{ isset($username) ? $username : '' }}">
                        <button type="submit"
                                class="btn u-btn-outline-primary g-brd-none g-bg-transparent--hover g-pos-abs g-top-0 g-right-0 d-flex g-width-40 h-100 align-items-center justify-content-center g-font-size-18 g-z-index-2">
                            <i class="hs-admin-search"></i>
                        </button>
                    @endcan
                </div>
            </form>
            <a id="searchInvoker" class="g-hidden-sm-up text-uppercase u-header-icon-v1 g-pos-rel g-width-40 g-height-40 rounded-circle g-font-size-20" href="#!" aria-controls="header-search-form" aria-haspopup="true" aria-expanded="false" data-is-mobile-only="true" data-dropdown-event="click"
               data-dropdown-target="#header-search-form" data-dropdown-type="css-animation" data-dropdown-duration="300" data-dropdown-animation-in="fadeIn" data-dropdown-animation-out="fadeOut">
                <i class="hs-admin-search g-absolute-centered"></i>
            </a>
            <div class="col-auto d-flex g-py-12 ml-auto">
                <div class="col-auto d-flex g-pt-5 g-pt-0--sm g-pl-10 g-pr-10">
                    <div class="g-pos-rel">
                        <a class="btn btn-info text-white btn-header-chat btn-header-auth g-pl-10 g-pr-10" href="javascript:void(0)" style="border-radius: 50px">
                            <i class="fa fa-comment" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-auto d-flex g-py-12 ml-auto">
                <div class="col-auto d-flex g-pt-5 g-pt-0--sm g-pl-10 g-pr-10">
                    <div class="g-pos-rel">
                        @if(count($whitelabel_currencies)>1)
                            <a id="currency-menu-invoker" class="d-block" href="#!" aria-controls="currency-menu" aria-haspopup="true" aria-expanded="false" data-dropdown-event="click" data-dropdown-target="#currency-menu" data-dropdown-type="css-animation" data-dropdown-duration="300"
                               data-dropdown-animation-in="fadeIn" data-dropdown-animation-out="fadeOut">
                            <span class="g-pos-rel">
                                <span class="g-hidden-sm-down"><i class="fa fa-database"></i></span> {{ session('currency') == 'VEF' ? $free_currency->currency_name : session('currency') }}
                                <i class="hs-admin-angle-down g-pos-rel g-top-2 g-ml-10"></i>
                            </span>
                            </a>
                            <ul id="currency-menu" class="currency-menu-pro g-pos-abs g-left-0 g-nowrap g-font-size-14 g-py-20 g-mt-10 rounded" aria-labelledby="currency-menu-invoker">
                                @foreach ($whitelabel_currencies as $currency)
                                    <li class="mb-0">
                                        <a class="{{ $currency->iso == session('currency') ? 'active' : '' }} media g-color-primary--hover g-py-5 g-px-20" href="{{ route('core.change-currency', [$currency->iso]) }}">
                                        <span class="media-body align-self-center">
                                            {{ $currency->iso == 'VEF' ? $free_currency->currency_name : $currency->iso . " ({$currency->name})" }}
                                        </span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <a id="currency-menu-invoker" class="d-block" href="#!" aria-controls="currency-menu" aria-haspopup="true" aria-expanded="false" data-dropdown-event="click" data-dropdown-target="#currency-menu" data-dropdown-type="css-animation" data-dropdown-duration="300"
                               data-dropdown-animation-in="fadeIn" data-dropdown-animation-out="fadeOut">
                                <span class="g-pos-rel">
                                    <span class="g-hidden-sm-down"><i class="fa fa-database"></i></span> {{ session('currency') == 'VEF' ? $free_currency->currency_name : session('currency') }}
                                    <i class="hs-admin-angle-down g-pos-rel g-top-2 g-ml-10"></i>
                                </span>
                            </a>
                        @endif

                        <span class="balanceAuth_{{\Illuminate\Support\Facades\Auth::id()}}"></span>
                    </div>
                </div>
            </div>
            <div class="col-auto d-flex g-py-12 ml-auto">
                <div class="col-auto d-flex g-pt-5 g-pt-0--sm g-pl-10 g-pr-10">
                    @if(count($languages) > 1)
                        <div class="g-pos-rel">
                            <a id="languages-menu-invoker" class="d-block" href="#!" aria-controls="languages-menu" aria-haspopup="true" aria-expanded="false" data-dropdown-event="click" data-dropdown-target="#languages-menu" data-dropdown-type="css-animation" data-dropdown-duration="300"
                               data-dropdown-animation-in="fadeIn" data-dropdown-animation-out="fadeOut">
                            <span class="g-pos-rel">
                                <span class="g-hidden-sm-down"  {{ $selected_language['iso'] }}><i class="fa fa-globe"></i></span>
                                {{ $selected_language['name'] }}
                                <i class="hs-admin-angle-down g-pos-rel g-top-2 g-ml-10"></i>
                            </span>
                            </a>
                            <ul id="languages-menu" class="languages-menu-pro g-pos-abs g-left-0 g-nowrap g-font-size-14 g-py-20 g-mt-10 rounded" aria-labelledby="currency-menu-invoker">
                                @foreach ($languages as $language)
                                    <li class="mb-0">
                                        <a href="{{ route('core.change-language', [$language['iso']]) }}" class="change-language" data-locale="{{ $language['iso'] }}">
                                            <img class="lang-flag" src="{{ $language['flag'] }}" alt="{{ $language['name'] }}">
                                            {{ $language['name'] }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                </div>
            </div>
            {{--<div class="col-auto d-flex g-py-12 ml-auto">
                <div class="g-pos-rel g-hidden-sm-down">
                    <a id="notifications-menu-invoker" class="d-block text-uppercase u-header-icon-v1 g-pos-rel g-width-40 g-height-40 rounded-circle g-font-size-20" href="#!" aria-controls="notifications-menu" aria-haspopup="true" aria-expanded="false" data-dropdown-event="click"
                       data-dropdown-target="#notifications-menu" data-dropdown-type="css-animation" data-dropdown-duration="300" data-dropdown-animation-in="fadeIn" data-dropdown-animation-out="fadeOut">
                        <span class="u-badge-v1 g-top-7 g-right-7 g-width-18 g-height-18 g-bg-primary g-font-size-10 g-color-white rounded-circle p-0" id="notifications-quantity">
                            {{ $push_notifications_quantity }}
                        </span>
                        <i class="hs-admin-bell g-absolute-centered"></i>
                    </a>
                    <div id="notifications-menu" class="js-custom-scroll g-absolute-centered--x g-width-340 g-max-width-400 g-height-400 g-mt-17 rounded" aria-labelledby="notifications-menu-invoker">
                        <div class="media text-uppercase u-header-dropdown-bordered-v1 g-pa-20">
                            <h4 class="d-flex align-self-center g-font-size-default g-letter-spacing-0_5 g-mr-20 g-mb-0">
                                {{ _i('Notifications') }}
                            </h4>
                        </div>
                        <ul class="p-0 mb-0" id="notifications-container">
                            {!! $push_notifications !!}
                        </ul>
                    </div>
                </div>
            </div>--}}
            <div class="col-auto d-flex g-py-12 ml-auto">
                <div class="col-auto d-flex g-pt-5 g-pt-0--sm">
                    <div class="g-pos-rel">
                        <a id="profile-menu-invoker" class="d-block" href="#!" aria-controls="profile-menu" aria-haspopup="true" aria-expanded="false" data-dropdown-event="click" data-dropdown-target="#profile-menu" data-dropdown-type="css-animation" data-dropdown-duration="300"
                           data-dropdown-animation-in="fadeIn" data-dropdown-animation-out="fadeOut">
                           <span class="g-pos-rel">
                           <span class="u-badge-v2--xs u-badge--top-right g-hidden-sm-up g-bg-secondary g-mr-5"></span>
                               @php
                                   $avatar = \App\Users\Users::getAvatar();
                               @endphp
                               @if (!is_null ($avatar))
                                   <img class="g-width-30 g-width-40--md g-height-30 g-height-40--md rounded-circle g-mr-10--sm" src="{{ $avatar }}" alt="{{ isset(auth()->user()->username) ? auth()->user()->username : '' }}">
                               @else
                                   <img class="g-width-30 g-width-40--md g-height-30 g-height-40--md rounded-circle g-mr-10--sm" src="{{ asset('back/img/avatar-default.jpg') }}" alt="{{ isset(auth()->user()->username) ? auth()->user()->username : '' }}">
                               @endif
                            </span>
                            <span class="g-pos-rel">
                                <span class="g-hidden-sm-down">
                                    {{ isset(auth()->user()->username) ? auth()->user()->username : '' }}
                                </span>
                                <i class="hs-admin-angle-down g-pos-rel g-top-2 g-ml-10"></i>
                            </span>
                        </a>
                        <ul id="profile-menu" class="g-pos-abs g-left-0 g-nowrap g-font-size-14 g-py-20 g-mt-30 rounded"
                            aria-labelledby="profile-menu-invoker">
                            <li class="mb-0">
                                <a class="media g-color-primary--hover g-py-5 g-px-20"
                                   href="{{ route('auth.logout') }}">
                                    <span class="d-flex align-self-center g-mr-12">
                                        <i class="hs-admin-shift-right"></i>
                                    </span>
                                    <span class="media-body align-self-center">
                                        {{ _i('Logout') }}
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
    </div>
</header>

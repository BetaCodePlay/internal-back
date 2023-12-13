<header id="js-header" class="u-header u-header--sticky-top">
    <div class="u-header__section u-header__section--admin-dark g-min-height-65">
        <nav class="navbar no-gutters g-pa-0">
            <div class="col-auto d-flex flex-nowrap u-header-logo-toggler g-py-12">
                <a href="{{ route('core.dashboard') }}" class="navbar-brand d-flex align-self-center g-hidden-xs-down g-line-height-1 py-0 g-mt-0">
                    @if(!empty($logo))
                        @if(!is_null($logo->img_dark))
                            <img src="{{$logo->img_dark }}" alt="Logo" width="180" height="37" class="img-logo">
                        @endif
                    @endif
                </a>
                <a href="{{ route('core.dashboard') }}" class="navbar-brand-mini">
                    @if(!empty($logo))
                        @if(!is_null($logo->img_dark))
                            <img src="{{$logo->img_dark }}" alt="Logo" width="180" height="37" class="img-logo-mini">
                        @endif
                    @endif
                </a>
                <a class="js-side-nav u-header__nav-toggler align-self-center ml-auto collapse-menu-action" href="#!"
                   data-hssm-class="u-side-nav--mini u-sidebar-navigation-v1--mini"
                   data-hssm-body-class="u-side-nav-mini" data-hssm-is-close-all-except-this="true"
                   data-hssm-target="#sideNav">
                    <i class="hs-admin-align-left"></i>
                </a>
            </div>


            <div class="col-auto d-flex g-py-12 ml-auto">

                <div class="col-auto d-flex g-pt-5 g-pt-0--sm g-pl-5 g-pr-10">
                    <div class="g-pos-rel">
                        <span class="d-block">



                            <span class="g-pos-rel">
                                  300.000 {{ session('currency') == 'VEF' ? $free_currency->currency_name : session('currency') }}
                            </span>
                            <span class="g-pos-rel"><i class="fa-regular fa-bell"></i></span>
                            <span class="g-pos-rel"><i class="fa-solid fa-gear"></i></span>
                           <span class="g-pos-rel">
                               @php
                                   $avatar = \App\Users\Users::getAvatar();
                               @endphp
                               @if (!is_null ($avatar))
                                   <img class="g-width-30 img-avatar g-width-40--md g-height-30 g-height-40--md rounded-circle g-mr-10--sm" src="{{ $avatar }}" alt="{{ isset(auth()->user()->username) ? auth()->user()->username : '' }}">
                               @else
                                   <img class="g-width-30 img-avatar g-width-40--md g-height-30 g-height-40--md rounded-circle g-mr-10--sm" src="{{ asset('back/img/avatar-default.jpg') }}" alt="{{ isset(auth()->user()->username) ? auth()->user()->username : '' }}">
                               @endif
                            </span>
                        </span>
                    </div>
                </div>
                <div class="col-auto d-flex g-py-12 ml-auto">
                    <div class="col-auto d-flex g-pt-5 g-pt-0--sm g-pl-5 g-pr-10">
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
            </div>
        </nav>
    </div>
</header>

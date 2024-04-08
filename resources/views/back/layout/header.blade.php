<header id="js-header" class="u-header u-header--sticky-top" data-route-find="{{ route('agents.find') }}" data-auth-user="{{ auth()->user()->id }}" data-user-type="{{ auth()->user()->typeUser }}">
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
                    <img src="{{ $favicon }}" alt="Logo" width="180" height="37" class="img-logo-mini">
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
                        <div class="d-block">
                            <div class="d-inline-block g-pos-rel g-pos-rel-amount">
                                <i class="fa-solid fa-coins"></i> <span class="amount-refresh-{{auth()->user()->id}}">{{ getAuthenticatedUserBalance() }}</span>
                            </div>

                            @if(!empty($whitelabel_currencies) && count($whitelabel_currencies)>1)
                                <div class="d-inline-block g-pos-rel dropdown">
                                    <button class="btn dropdown-toggle" type="button" id="dropdownCurrency" data-toggle="dropdown" aria-expanded="false">
                                        {{ session('currency') == 'VEF' ? $free_currency->currency_name : session('currency') }} <i class="fa-solid fa-angle-down"></i>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownCurrency">
                                        @foreach ($whitelabel_currencies as $currency)
                                            <li><a class="dropdown-item" href="{{ route('core.change-currency', [$currency->iso]) }}">{{ $currency->iso == 'VEF' ? $free_currency->currency_name : $currency->iso  }}  <span class="mini-title">{{ $currency->name }}</span></a></li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="d-inline-block g-pos-rel btn-mini-h notified-active"><i class="fa-regular fa-bell"></i></div>
                            <div class="d-inline-block g-pos-rel btn-mini-h pos-mobile-hidden"><i class="fa-solid fa-gear"></i></div>
                            <div class="d-inline-block g-pos-rel pos-mobile-hidden">
                                @php
                                    $avatar = \App\Users\Users::getAvatar();
                                @endphp
                                @if (!is_null ($avatar))
                                    <img class="g-width-30 img-avatar g-width-40--md g-height-30 g-height-40--md rounded-circle g-mr-10--sm" src="{{ $avatar }}" alt="{{ isset(auth()->user()->username) ? auth()->user()->username : '' }}">
                                @else
                                    <img class="g-width-30 img-avatar g-width-40--md g-height-30 g-height-40--md rounded-circle g-mr-10--sm" src="{{ asset('back/img/avatar-default.jpg') }}" alt="{{ isset(auth()->user()->username) ? auth()->user()->username : '' }}">
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </div>
</header>

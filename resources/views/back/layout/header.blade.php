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
                <div class="col-auto d-flex g-pt-5 g-pt-0--sm g-pl-5 g-pr-5">
                    <div class="g-pos-rel">
                        <span class="balanceAuth_{{\Illuminate\Support\Facades\Auth::id()}}"></span> {{ session('currency') == 'VEF' ? $free_currency->currency_name : session('currency') }}
                    </div>
                </div>

                <div class="col-auto d-flex g-pt-5 g-pt-0--sm g-pl-5 g-pr-10">
                    <div class="g-pos-rel">
                        <span class="d-block">
                           <span class="g-pos-rel">
                               @php
                                   $avatar = \App\Users\Users::getAvatar();
                               @endphp
                               @if (!is_null ($avatar))
                                   <img class="g-width-30 g-width-40--md g-height-30 g-height-40--md rounded-circle g-mr-10--sm" src="{{ $avatar }}" alt="{{ isset(auth()->user()->username) ? auth()->user()->username : '' }}">
                               @else
                                   <img class="g-width-30 g-width-40--md g-height-30 g-height-40--md rounded-circle g-mr-10--sm" src="{{ asset('back/img/avatar-default.jpg') }}" alt="{{ isset(auth()->user()->username) ? auth()->user()->username : '' }}">
                               @endif
                            </span>
                        </span>
                    </div>
                </div>
            </div>
        </nav>
    </div>
</header>

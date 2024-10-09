@php
    use Dotworkers\Security\Enums\Permissions;

    $sectionsData = generateSections();
    $sliderSections = $sectionsData['sliderSections'];
    $imageSections = $sectionsData['imageSections'];

    $permissions = Permissions::class;
@endphp

<div id="sideNav" class="col-auto u-sidebar-navigation-v1 u-sidebar-navigation--dark">
    <ul id="sideNavMenu" class="u-sidebar-navigation-v1-menu u-side-nav--top-level-menu mb-0">
        {{--{!! \Core::buildMenu() !!}--}}

        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active has-menu">
            <div class="u-sidebar-menu action-mobile-menu"><i class="fa-solid fa-arrow-left-long"></i> {{ _i('Menu') }}
            </div>
        </li>

        @can('access', [\Dotworkers\Security\Enums\Permissions::$users_search])
            <li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
                <div class="u-sidebar-title"><span>{{ _i('Search global') }}</span></div>
            </li>
            <li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
                <form id="header-search-form" class="u-header--search" action="{{ route('users.search') }}" method="get" autocomplete="nope">
                    <div class="input-group">
                        <input class="form-control form-control-sidebar" type="text" name="username" placeholder="{{ _i('Search user') }}" autocomplete="nope" value="">
                        <button type="submit" class="btn btn-search g-brd-none g-bg-transparent--hover g-pos-abs g-top-0 g-right-0 d-flex g-width-40 h-100 align-items-center justify-content-center g-font-size-18 g-z-index-2">
                            <i class="hs-admin-search"></i>
                        </button>
                    </div>
                </form>
            </li>
            <li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active u-sidebar-navigation-v1-menu-item-search">
                <a class="media u-side-nav--top-level-menu-link u-side-nav--hide-on-hidden">
                    <span class="g-pos-rel"><i class="fa-solid fa-magnifying-glass"></i></span>
                </a>
            </li>

            <li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
                <hr>
            </li>
        @endcan

        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
            <div class="u-sidebar-title"><span>{{ _i('Categories') }}</span></div>
        </li>
        @can('access', [$permissions::$dashboard])
            @include('back.partials.sidebar.dashboard')
        @endif
        @can('access', [$permissions::$dashboard_assiria])
            @include('back.partials.sidebar.roleDashboard')
        @endif
        @can('access', [$permissions::$rol_assiria])
            @include('back.partials.sidebar.role')
        @endif
        @can('access', [$permissions::$reports_assiria])
            @include('back.partials.sidebar.report')
        @endif

        @can('access', [$permissions::$users_menu])
            @include('back.partials.sidebar.users', ['permissions' => $permissions])
        @endif
        @can('access', [$permissions::$agents_menu])
            @include('back.partials.sidebar.agents', ['permissions' => $permissions])
        @endcan
        @can('access', [$permissions::$create_user_agent])
            @include('back.partials.sidebar.createUserAgent')
        @endif
        @can('access', [$permissions::$agents_dashboard])
            @include('back.partials.sidebar.createUserPlayer')
        @endif
        @can('access', [$permissions::$financial_reports_menu])
            @include('back.partials.sidebar.financialReports', ['permissions' => $permissions])
        @endif
        @can('access', [$permissions::$operations_menu])
            @include('back.partials.sidebar.operations', ['permissions' => $permissions])
        @endif
        @can('access', [$permissions::$referrals_menu])
            @include('back.partials.sidebar.referrals', ['permissions' => $permissions])
        @endif
        @can('access', [$permissions::$betpay_menu])
            @include('back.partials.sidebar.betpay', ['permissions' => $permissions])
        @endif
        @can('access', [$permissions::$manage_sliders])
            @include('back.partials.sidebar.sliders', ['permissions' => $permissions])
        @endif
        @can('access', [$permissions::$section_images_menu])
            @include('back.partials.sidebar.images', ['permissions' => $permissions])
        @endif
        @can('access', [$permissions::$system_bonus_menu])
            @include('back.partials.sidebar.bonus', ['permissions' => $permissions])
        @endif
        @can('access', [$permissions::$section_games_menu])
            @include('back.partials.sidebar.lobbyGames', ['permissions' => $permissions])
        @endif
        @can('access', [$permissions::$section_images_menu])
            @include('back.partials.sidebar.lobby')
        @endif
        @can('access', [$permissions::$whitelabels_games_menu])
            @include('back.partials.sidebar.whitelabelsGames')
        @endif
        @can('access', [$permissions::$modals_menu])
            @include('back.partials.sidebar.modals', ['permissions' => $permissions])
        @endif
        @can('access', [$permissions::$promotions_menu])
            @include('back.partials.sidebar.posts', ['permissions' => $permissions])
        @endif
        @can('access', [$permissions::$pages_menu])
            @include('back.partials.sidebar.pages')
        @endif
        @can('access', [$permissions::$manage_whitelabels_status_menu])
            @include('back.partials.sidebar.whitelabelsActiveProviders')
        @endif
        @can('access', [$permissions::$manage_betpay_menu])
            @include('back.partials.sidebar.betpayClients')
        @endif
        @can('access', [$permissions::$exchange_rates])
            @include('back.partials.sidebar.exchangeRates')
        @endif
        @can('access', [$permissions::$manage_providers])
            @include('back.partials.sidebar.providers')
        @endif
        @can('access', [$permissions::$manage_main_agents])
            @include('back.partials.sidebar.mainAgents')
        @endif
        @can('access', [$permissions::$manage_main_users])
            @include('back.partials.sidebar.mainUsers')
        @endif
        @can('access', [$permissions::$update_rol_admin])
            @include('back.partials.sidebar.changeRolAdmin')
        @endif
        @can('access', [$permissions::$update_password_wolf])
            @include('back.partials.sidebar.updatePasswordOfWolf')
        @endif
        @can('access', [$permissions::$betpay_menu])
            @include('back.partials.sidebar.store', ['permissions' => $permissions])
        @endif
        <!--
        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
            <hr>
        </li>

        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
            <div class="u-sidebar-title"><span>{{ _i('Account') }}</span></div>
        </li>

        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
            <a class="media u-side-nav--top-level-menu-link u-side-nav--hide-on-hidden" href="#" target="_self">
                <span class="g-pos-rel"><i class="fa-regular fa-bell"></i></span> <span
                    class="media-body align-self-center">{{ _i('Notifications') }}</span>
                <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
            </a>
        </li>

        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
            <a class="media u-side-nav--top-level-menu-link u-side-nav--hide-on-hidden" href="#" target="_self">
                <span class="g-pos-rel"><i class="fa-solid fa-gear"></i></span> <span class="media-body align-self-center">{{ _i('Setting') }}</span>
                <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
            </a>
        </li>

        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
            <hr>
        </li>
-->

        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
            <a class="media u-side-nav--top-level-menu-link u-side-nav--hide-on-hidden" href="/support" target="_self">
                <span class="g-pos-rel"><i class="fa-solid fa-headset"></i></span> <span class="media-body align-self-center">{{ _i('Support') }}</span>
            </a>
        </li>

        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active has-logout">
            <a class="media u-side-nav--top-level-menu-link u-side-nav--hide-on-hidden" href="{{ route('auth.logout') }}" target="_self">
                <span class="g-pos-rel"><i class="fa-solid fa-power-off"></i></span> <span class="media-body align-self-center">{{ _i('Logout') }}</span>
            </a>
        </li>

        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active mobile-hidde">
            <a class="media u-side-nav--top-level-menu-link u-side-nav--hide-on-hidden collapse-menu-action-s" href="javascript:void(0)">
                <span class="g-pos-rel"><i class="fa-solid fa-arrows-left-right-to-line"></i></span> <span class="media-body align-self-center">{{ _i('Collapse') }}</span>
            </a>
        </li>
    </ul>
</div>

<div class="nav-mobile">
    <div class="nav-mobile-ex">
        @can('access', [$permissions::$dashboard])
            <div class="nav-mobile-opt"><a class="active" href="{{ route('core.dashboard') }}"><i class="fa-solid fa-house-chimney"></i> <span class="name">{{ _i('Home') }}</span></a></div>
        @endif
        @can('access', [$permissions::$rol_assiria])
            <div class="nav-mobile-opt">
                <a href="{{ route('agents.role') }}">
                    <i class="fa-solid fa-people-group"></i> <span class="name">{{ _i('Role') }}</span>
                </a>
            </div>
        @endif
        @can('access', [$permissions::$reports_assiria])
            <div class="nav-mobile-opt">
                <a href="{{ route('agents.reports.management') }}">
                    <i class="fa-solid fa-chart-column"></i> <span class="name">{{ _i('Reports') }}</span>
                </a>
            </div>
        @endif
        <div class="nav-mobile-opt action-mobile-menu">
            <a href="javascript:void(0)">
                <i class="fa-solid fa-bars"></i> <span class="name">{{ _i('Menu') }}</span>
            </a>
        </div>
    </div>
</div>

<!--
<div id="sideNav" class="col-auto u-sidebar-navigation-v1 u-sidebar-navigation--dark">
    <ul id="sideNavMenu" class="u-sidebar-navigation-v1-menu u-side-nav--top-level-menu mb-0">
        {{--{!! \Core::buildMenu() !!}--}}

<li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active has-menu">
    <div class="u-sidebar-menu action-mobile-menu"><i class="fa-solid fa-arrow-left-long"></i> {{ _i('Menu') }}</div>
        </li>

        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
            <div class="u-sidebar-title"><span>{{ _i('Categories') }}</span></div>
        </li>
        @can('access', [$permissions::$dashboard])
    <li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
        <a class="media u-side-nav--top-level-menu-link u-side-nav--hide-on-hidden" href="{{ route('core.dashboard') }}" target="_self">
                    <span class="g-pos-rel"><i class="fa-solid fa-house-chimney"></i></span> <span class="media-body align-self-center">{{ _i('Home') }}</span>
                    <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                </a>
            </li>















@endif
@can('access', [$permissions::$agents_dashboard])
    <li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
        <a class="media u-side-nav--top-level-menu-link u-side-nav--hide-on-hidden active" href="{{ route('agents.index') }}" target="_self">
                    <span class="g-pos-rel"><i class="fa-solid fa-people-group"></i></span> <span class="media-body align-self-center">{{ _i('Role') }}</span>
                    <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                </a>
            </li>















@endif
<li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
    <a class="media u-side-nav--top-level-menu-link u-side-nav--hide-on-hidden" href="javascript:void(0)" data-toggle="collapse" data-target="#collapseExample" aria-expanded="true">
        <span class="g-pos-rel"><i class="fa-regular fa-user"></i></span> <span class="media-body align-self-center">{{ _i('Usuarios') }}</span>
                <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
            </a>
            <ul id="collapseExample" class="u-sidebar-navigation-v1-menu u-side-nav--second-level-menu u-side-nav--second-level-menu-top mb-0 collapse show">
                <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item active">
                    <a class="media u-side-nav--second-level-menu-link" href="#" target="_self">
                        <span class="media-body align-self-center">Dashboard</span>
                        <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                    </a>
                </li>
                <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                    <a class="media u-side-nav--second-level-menu-link" href="#" target="_self">
                        <span class="media-body align-self-center">Agregar usuarios</span>
                        <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                    </a>
                </li>
                <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                    <a class="media u-side-nav--second-level-menu-link" href="javascript:void(0)" target="_self" data-toggle="collapse" data-target="#collapseExampleTwo" aria-expanded="false">
                        <span class="media-body align-self-center">Reportes</span>
                        <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                    </a>
                    <ul id="collapseExampleTwo" class="u-sidebar-navigation-v1-menu u-side-nav--second-level-menu mb-0 collapse">
                        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                            <a class="media u-side-nav--second-level-menu-link" href="#" target="_self">
                                <span class="media-body align-self-center">Estado</span>
                                <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                            </a>
                        </li>
                        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                            <a class="media u-side-nav--second-level-menu-link" href="#" target="_self">
                                <span class="media-body align-self-center">Resumen</span>
                                <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                            </a>
                        </li>
                        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                            <a class="media u-side-nav--second-level-menu-link" href="#" target="_self">
                                <span class="media-body align-self-center">Transacciones</span>
                                <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>
        </li>

        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
            <hr>
        </li>

        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
            <div class="u-sidebar-title"><span>{{ _i('Account') }}</span></div>
        </li>

        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
            <a class="media u-side-nav--top-level-menu-link u-side-nav--hide-on-hidden" href="#" target="_self">
                <span class="g-pos-rel"><i class="fa-regular fa-bell"></i></span> <span class="media-body align-self-center">{{ _i('Notifications') }}</span>
                <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
            </a>
        </li>

        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
            <a class="media u-side-nav--top-level-menu-link u-side-nav--hide-on-hidden" href="#" target="_self">
                <span class="g-pos-rel"><i class="fa-solid fa-gear"></i></span> <span class="media-body align-self-center">{{ _i('Setting') }}</span>
                <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
            </a>
        </li>
        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
            <hr>
        </li>

        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active has-logout">
            <a class="media u-side-nav--top-level-menu-link u-side-nav--hide-on-hidden" href="{{ route('auth.logout') }}" target="_self">
                <span class="g-pos-rel"><i class="fa-solid fa-right-from-bracket"></i></span> <span class="media-body align-self-center">{{ _i('Logout') }}</span>
            </a>
        </li>

        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active mobile-hidde">
            <a class="media u-side-nav--top-level-menu-link u-side-nav--hide-on-hidden collapse-menu-action-s" href="javascript:void(0)">
                <span class="g-pos-rel"><i class="fa-solid fa-arrows-left-right-to-line"></i></span> <span class="media-body align-self-center">{{ _i('Collapse') }}</span>
            </a>
        </li>
    </ul>
</div>
<div class="nav-mobile">
    <div class="nav-mobile-ex">
        @can('access', [$permissions::$dashboard])
    <div class="nav-mobile-opt"><a class="active" href="{{ route('core.dashboard') }}"><i class="fa-solid fa-house-chimney"></i> <span class="name">{{ _i('Home') }}</span></a></div>















@endif
@can('access', [$permissions::$agents_dashboard])
    <div class="nav-mobile-opt"><a href="{{ route('agents.index') }}"><i class="fa-solid fa-people-group"></i> <span class="name">{{ _i('Role') }}</span></a></div>















@endif
<div class="nav-mobile-opt"><a href="#"><i class="fa-solid fa-chart-column"></i> <span class="name">{{ _i('Reports') }}</span></a></div>
        <div class="nav-mobile-opt action-mobile-menu"><a href="javascript:void(0)"><i class="fa-solid fa-bars"></i> <span class="name">{{ _i('Menu') }}</span></a></div>
    </div>
</div>
-->


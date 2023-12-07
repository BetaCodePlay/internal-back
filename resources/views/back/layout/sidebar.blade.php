<div id="sideNav" class="col-auto u-sidebar-navigation-v1 u-sidebar-navigation--dark">
    <ul id="sideNavMenu" class="u-sidebar-navigation-v1-menu u-side-nav--top-level-menu mb-0">
        {{--{!! \Core::buildMenu() !!}--}}

        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active has-menu">
            <div class="u-sidebar-menu action-mobile-menu"><i class="fa-solid fa-arrow-left-long"></i> {{ _i('Menu') }}</div>
        </li>

        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
            <div class="u-sidebar-title"><span>{{ _i('Categories') }}</span></div>
        </li>
        @can('access', [\Dotworkers\Security\Enums\Permissions::$dashboard])
            <li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
                <a class="media u-side-nav--top-level-menu-link u-side-nav--hide-on-hidden" href="{{ route('core.dashboard') }}" target="_self">
                    <span class="g-pos-rel"><i class="fa-solid fa-house-chimney"></i></span> <span class="media-body align-self-center">{{ _i('Home') }}</span>
                    <span class="icon-mobile"><i class="fa-solid fa-chevron-right"></i></span>
                </a>
            </li>
        @endif
        @can('access', [\Dotworkers\Security\Enums\Permissions::$agents_dashboard])
            <li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
                <a class="media u-side-nav--top-level-menu-link u-side-nav--hide-on-hidden active" href="{{ route('agents.index') }}" target="_self">
                    <span class="g-pos-rel"><i class="fa-solid fa-people-group"></i></span> <span class="media-body align-self-center">{{ _i('Role') }}</span>
                    <span class="icon-mobile"><i class="fa-solid fa-chevron-right"></i></span>
                </a>
            </li>
        @endif
        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
            <a class="media u-side-nav--top-level-menu-link u-side-nav--hide-on-hidden" href="javascript:void(0)" data-toggle="collapse" data-target="#collapseExample">
                <span class="g-pos-rel"><i class="fa-regular fa-user"></i></span> <span class="media-body align-self-center">{{ _i('Usuarios') }}</span>
                <span class="icon-mobile"><i class="fa-solid fa-chevron-right"></i></span>
            </a>
            <ul id="collapseExample" class="u-sidebar-navigation-v1-menu u-side-nav--second-level-menu mb-0 collapse">
                <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                    <a class="d-flex media u-side-nav--second-level-menu-link u-side-nav--hide-on-hidden" href="https://staging-back.bestcasinos.lat/agents" target="_self">
                        <span class="media-body align-self-center">Dashboard</span>
                    </a>
                </li>
                <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                    <a class="d-flex media u-side-nav--second-level-menu-link u-side-nav--hide-on-hidden" href="https://staging-back.bestcasinos.lat/agents" target="_self">
                        <span class="media-body align-self-center">Agregar usuarios</span>
                    </a>
                </li>
                <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                    <a class="d-flex media u-side-nav--second-level-menu-link u-side-nav--hide-on-hidden" href="https://staging-back.bestcasinos.lat/agents" target="_self">
                        <span class="media-body align-self-center">Reportes</span>
                    </a>
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
                <span class="icon-mobile"><i class="fa-solid fa-chevron-right"></i></span>
            </a>
        </li>

        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
            <a class="media u-side-nav--top-level-menu-link u-side-nav--hide-on-hidden" href="#" target="_self">
                <span class="g-pos-rel"><i class="fa-solid fa-gear"></i></span> <span class="media-body align-self-center">{{ _i('Setting') }}</span>
                <span class="icon-mobile"><i class="fa-solid fa-chevron-right"></i></span>
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
        @can('access', [\Dotworkers\Security\Enums\Permissions::$dashboard])
            <div class="nav-mobile-opt"><a class="active" href="{{ route('core.dashboard') }}"><i class="fa-solid fa-house-chimney"></i> <span class="name">{{ _i('Home') }}</span></a></div>
        @endif
        @can('access', [\Dotworkers\Security\Enums\Permissions::$agents_dashboard])
            <div class="nav-mobile-opt"><a href="{{ route('agents.index') }}"><i class="fa-solid fa-people-group"></i> <span class="name">{{ _i('Role') }}</span></a></div>
        @endif
        <div class="nav-mobile-opt"><a href="#"><i class="fa-solid fa-chart-column"></i> <span class="name">{{ _i('Reports') }}</span></a></div>
        <div class="nav-mobile-opt action-mobile-menu"><a href="#"><i class="fa-solid fa-bars"></i> <span class="name">{{ _i('Menu') }}</span></a></div>
    </div>
</div>

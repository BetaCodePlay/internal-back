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
                    <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                </a>
            </li>
        @endif
        @can('access', [\Dotworkers\Security\Enums\Permissions::$agents_dashboard])
            <li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
                <a class="media u-side-nav--top-level-menu-link u-side-nav--hide-on-hidden active" href="{{ route('agents.index') }}" target="_self">
                    <span class="g-pos-rel"><i class="fa-solid fa-people-group"></i></span> <span class="media-body align-self-center">{{ _i('Role') }}</span>
                    <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                </a>
            </li>
        @endif
        @can('access', [\Dotworkers\Security\Enums\Permissions::$users_menu])
            <li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
            <a class="media u-side-nav--top-level-menu-link u-side-nav--hide-on-hidden" href="javascript:void(0)" data-toggle="collapse" data-target="#collapseExample" aria-expanded="true">
                <span class="g-pos-rel"><i class="fa-regular fa-user"></i></span> <span class="media-body align-self-center">{{ _i('Users') }}</span>
                <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
            </a>
            <ul id="collapseExample" class="u-sidebar-navigation-v1-menu u-side-nav--second-level-menu u-side-nav--second-level-menu-top mb-0 collapse show">
                @can('access', [\Dotworkers\Security\Enums\Permissions::$create_users])
                    <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                        <a class="media u-side-nav--second-level-menu-link" href="{{ route('users.create') }}" target="_self">
                            <span class="media-body align-self-center">{{ _i('Create') }}</span>
                            <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                        </a>
                    </li>
                @endif
                @can('access', [\Dotworkers\Security\Enums\Permissions::$advanced_users_search])
                    <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                        <a class="media u-side-nav--second-level-menu-link" href="{{ route('users.advanced-search') }}" target="_self">
                            <span class="media-body align-self-center">{{ _i('Advanced search') }}</span>
                            <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                        </a>
                    </li>
                @endif
                @can('access', [\Dotworkers\Security\Enums\Permissions::$web_registers])
                    <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                        <a class="media u-side-nav--second-level-menu-link" href="{{ route('reports.users.registered-users') }}" target="_self">
                            <span class="media-body align-self-center">{{ _i('Registered users') }}</span>
                            <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                        </a>
                    </li>
                @endif
                @can('access', [\Dotworkers\Security\Enums\Permissions::$users_status])
                    <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                        <a class="media u-side-nav--second-level-menu-link" href="{{ route('users.users-status') }}" target="_self">
                            <span class="media-body align-self-center">{{ _i('Users status') }}</span>
                            <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                        </a>
                    </li>
                @endif
                @can('access', [\Dotworkers\Security\Enums\Permissions::$users_balances])
                    <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                        <a class="media u-side-nav--second-level-menu-link" href="{{ route('reports.users.balances') }}" target="_self">
                            <span class="media-body align-self-center">{{ _i('Users balances') }}</span>
                            <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                        </a>
                    </li>
                @endif
                @can('access', [\Dotworkers\Security\Enums\Permissions::$users_conversion])
                    <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                        <a class="media u-side-nav--second-level-menu-link" href="{{ route('reports.users.users-conversion') }}" target="_self">
                            <span class="media-body align-self-center">{{ _i('Users conversion') }}</span>
                            <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                        </a>
                    </li>
                @endif
                @can('access', [\Dotworkers\Security\Enums\Permissions::$users_logins])
                    <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                        <a class="media u-side-nav--second-level-menu-link" href="{{ route('reports.users.total-logins') }}" target="_self">
                            <span class="media-body align-self-center">{{ _i('Logins') }}</span>
                            <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                        </a>
                    </li>
                @endif
                @can('access', [\Dotworkers\Security\Enums\Permissions::$temp_users])
                    <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                        <a class="media u-side-nav--second-level-menu-link" href="{{ route('users.temp') }}" target="_self">
                            <span class="media-body align-self-center">{{ _i('Temp users') }}</span>
                            <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                        </a>
                    </li>
                @endif
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
        @endif
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
        @can('access', [\Dotworkers\Security\Enums\Permissions::$dashboard])
            <div class="nav-mobile-opt"><a class="active" href="{{ route('core.dashboard') }}"><i class="fa-solid fa-house-chimney"></i> <span class="name">{{ _i('Home') }}</span></a></div>
        @endif
        @can('access', [\Dotworkers\Security\Enums\Permissions::$agents_dashboard])
            <div class="nav-mobile-opt"><a href="{{ route('agents.index') }}"><i class="fa-solid fa-people-group"></i> <span class="name">{{ _i('Role') }}</span></a></div>
        @endif
        <div class="nav-mobile-opt"><a href="#"><i class="fa-solid fa-chart-column"></i> <span class="name">{{ _i('Reports') }}</span></a></div>
        <div class="nav-mobile-opt action-mobile-menu"><a href="javascript:void(0)"><i class="fa-solid fa-bars"></i> <span class="name">{{ _i('Menu') }}</span></a></div>
    </div>
</div>

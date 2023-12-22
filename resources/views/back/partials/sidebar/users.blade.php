<li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
    <a class="media u-side-nav--top-level-menu-link u-side-nav--hide-on-hidden" href="javascript:void(0)"
       data-toggle="collapse" data-target="#usersSidebar" aria-expanded="true">
        <span class="g-pos-rel"><i class="fa-regular fa-user"></i></span> <span
            class="media-body align-self-center">{{ _i('Users') }}</span>
        <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
    </a>
    <ul id="usersSidebar"
        class="u-sidebar-navigation-v1-menu u-side-nav--second-level-menu u-side-nav--second-level-menu-top mb-0 collapse">
        @can('access', [$permissions::$create_users])
            <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item active">
                <a class="media u-side-nav--second-level-menu-link" href="{{ route('users.create') }}"
                   target="_self">
                    <span class="media-body align-self-center">{{ _i('Create') }}</span>
                    <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                </a>
            </li>
        @endif
        @can('access', [$permissions::$advanced_users_search])
            <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                <a class="media u-side-nav--second-level-menu-link"
                   href="{{ route('users.advanced-search') }}" target="_self">
                    <span class="media-body align-self-center">{{ _i('Advanced search') }}</span>
                    <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                </a>
            </li>
        @endif
        @can('access', [$permissions::$web_registers])
            <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                <a class="media u-side-nav--second-level-menu-link"
                   href="{{ route('reports.users.registered-users') }}" target="_self">
                    <span class="media-body align-self-center">{{ _i('Registered users') }}</span>
                    <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                </a>
            </li>
        @endif
        @can('access', [$permissions::$users_status])
            <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                <a class="media u-side-nav--second-level-menu-link" href="{{ route('users.users-status') }}"
                   target="_self">
                    <span class="media-body align-self-center">{{ _i('Users status') }}</span>
                    <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                </a>
            </li>
        @endif
        @can('access', [$permissions::$users_balances])
            <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                <a class="media u-side-nav--second-level-menu-link"
                   href="{{ route('reports.users.balances') }}" target="_self">
                    <span class="media-body align-self-center">{{ _i('Users balances') }}</span>
                    <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                </a>
            </li>
        @endif
        @can('access', [$permissions::$users_conversion])
            <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                <a class="media u-side-nav--second-level-menu-link"
                   href="{{ route('reports.users.users-conversion') }}" target="_self">
                    <span class="media-body align-self-center">{{ _i('Users conversion') }}</span>
                    <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                </a>
            </li>
        @endif
        @can('access', [$permissions::$users_logins])
            <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                <a class="media u-side-nav--second-level-menu-link"
                   href="{{ route('reports.users.total-logins') }}" target="_self">
                    <span class="media-body align-self-center">{{ _i('Logins') }}</span>
                    <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                </a>
            </li>
        @endif
        @can('access', [$permissions::$temp_users])
            <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                <a class="media u-side-nav--second-level-menu-link" href="{{ route('users.temp') }}"
                   target="_self">
                    <span class="media-body align-self-center">{{ _i('Temp users') }}</span>
                    <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                </a>
            </li>
        @endif
        @can('access', [$permissions::$users_actives])
            <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                <a class="media u-side-nav--second-level-menu-link"
                   href="{{ route('reports.users.active-users-platforms') }}" target="_self">
                    <span class="media-body align-self-center">{{ _i('Active users on platforms') }}</span>
                    <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                </a>
            </li>
        @endif
        @can('access', [$permissions::$document_verification])
            <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                <a class="media u-side-nav--second-level-menu-link"
                   href="{{ route('users.documents-verifications') }}" target="_self">
                    <span class="media-body align-self-center">{{ _i('Documents verifications') }}</span>
                    <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                </a>
            </li>
        @endif
        @can('access', [$permissions::$users_birthdays_report])
            <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                <a class="media u-side-nav--second-level-menu-link"
                   href="{{ route('reports.users.users-birthdays') }}" target="_self">
                    <span class="media-body align-self-center">{{ _i('Users birthdays') }}</span>
                    <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                </a>
            </li>
        @endif
        @can('access', [$permissions::$report_auto_lock_users])
            <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                <a class="media u-side-nav--second-level-menu-link"
                   href="{{ route('users.autolocked-users') }}" target="_self">
                    <span class="media-body align-self-center">{{ _i('Autolocked users') }}</span>
                    <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                </a>
            </li>
        @endif
        @can('access', [$permissions::$update_rol_admin])
            <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                <a class="media u-side-nav--second-level-menu-link"
                   href="{{ route('users.list.by.owner') }}" target="_self">
                    <span class="media-body align-self-center">{{ _i('My users') }}</span>
                    <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                </a>
            </li>
        @endif
    </ul>
</li>

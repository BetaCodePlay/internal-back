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
                <a class="media u-side-nav--top-level-menu-link u-side-nav--hide-on-hidden" href="{{ route('agents.index') }}" target="_self">
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
                    <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item active">
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
                @can('access', [\Dotworkers\Security\Enums\Permissions::$users_actives])
                    <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                        <a class="media u-side-nav--second-level-menu-link" href="{{ route('reports.users.active-users-platforms') }}" target="_self">
                            <span class="media-body align-self-center">{{ _i('Active users on platforms') }}</span>
                            <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                        </a>
                    </li>
                @endif
                @can('access', [\Dotworkers\Security\Enums\Permissions::$document_verification])
                    <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                        <a class="media u-side-nav--second-level-menu-link" href="{{ route('users.documents-verifications') }}" target="_self">
                            <span class="media-body align-self-center">{{ _i('Documents verifications') }}</span>
                            <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                        </a>
                    </li>
                @endif
                @can('access', [\Dotworkers\Security\Enums\Permissions::$users_birthdays_report])
                    <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                        <a class="media u-side-nav--second-level-menu-link" href="{{ route('reports.users.users-birthdays') }}" target="_self">
                            <span class="media-body align-self-center">{{ _i('Users birthdays') }}</span>
                            <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                        </a>
                    </li>
                @endif
                @can('access', [\Dotworkers\Security\Enums\Permissions::$report_auto_lock_users])
                    <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                        <a class="media u-side-nav--second-level-menu-link" href="{{ route('users.autolocked-users') }}" target="_self">
                            <span class="media-body align-self-center">{{ _i('Autolocked users') }}</span>
                            <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                        </a>
                    </li>
                @endif
                @can('access', [\Dotworkers\Security\Enums\Permissions::$update_rol_admin])
                    <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                        <a class="media u-side-nav--second-level-menu-link" href="{{ route('users.list.by.owner') }}" target="_self">
                            <span class="media-body align-self-center">{{ _i('My users') }}</span>
                            <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                        </a>
                    </li>
                @endif
                @can('access', [\Dotworkers\Security\Enums\Permissions::$update_rol_admin])
                    <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                        <a class="media u-side-nav--second-level-menu-link" href="{{ route('users.list.by.owner') }}" target="_self">
                            <span class="media-body align-self-center">{{ _i('My users') }}</span>
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

        @can('access', [\Dotworkers\Security\Enums\Permissions::$agents_menu])
            <li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
                <a class="media u-side-nav--top-level-menu-link u-side-nav--hide-on-hidden" href="javascript:void(0)" data-toggle="collapse" data-target="#collapseExample" aria-expanded="true">
                    <span class="g-pos-rel"><i class="fa-regular fa-user"></i></span> <span class="media-body align-self-center">{{ _i('See agents') }}</span>
                    <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                </a>
                <ul id="collapseExample" class="u-sidebar-navigation-v1-menu u-side-nav--second-level-menu u-side-nav--second-level-menu-top mb-0 collapse show">
                    @can('access', [\Dotworkers\Security\Enums\Permissions::$agents_dashboard])
                        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item active">
                            <a class="media u-side-nav--second-level-menu-link" href="{{ route('agents.index') }}" target="_self">
                                <span class="media-body align-self-center">{{ _i('Dashboard') }}</span>
                                <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                            </a>
                        </li>
                    @endif
                    @can('access', [\Dotworkers\Security\Enums\Permissions::$add_agent_users])
                        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                            <a class="media u-side-nav--second-level-menu-link" href="{{ route('agents.add-users') }}" target="_self">
                                <span class="media-body align-self-center">{{ _i('Add users') }}</span>
                                <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                            </a>
                        </li>
                    @endif
                    @can('access', [\Dotworkers\Security\Enums\Permissions::$agents_reports_menu])
                        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                            <a class="media u-side-nav--second-level-menu-link" href="javascript:void(0)" target="_self" data-toggle="collapse" data-target="#collapseExampleTwo" aria-expanded="false">
                                <span class="media-body align-self-center">{{ _i('Reports') }}</span>
                                <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                            </a>
                            <ul id="collapseExampleTwo" class="u-sidebar-navigation-v1-menu u-side-nav--second-level-menu mb-0 collapse">
                                @can('access', [\Dotworkers\Security\Enums\Permissions::$agents_financial_report])
                                    <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                                        <a class="media u-side-nav--second-level-menu-link" href="{{ route('agents.reports.financial-state') }}" target="_self">
                                            <span class="media-body align-self-center">{{ _i('Financial state') }}</span>
                                            <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                                        </a>
                                    </li>
                                @endif
                                @can('access', [\Dotworkers\Security\Enums\Permissions::$agents_financial_report])
                                    <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                                        <a class="media u-side-nav--second-level-menu-link" href="{{ route('agents.reports.financial-state-summary') }}" target="_self">
                                            <span class="media-body align-self-center">{{ _i('Financial state - Summary') }}</span>
                                            <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                                        </a>
                                    </li>
                                @endif
                                @can('access', [\Dotworkers\Security\Enums\Permissions::$report_financial_by_username])
                                    <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                                        <a class="media u-side-nav--second-level-menu-link" href="{{ route('agents.reports.financial.state.username') }}" target="_self">
                                            <span class="media-body align-self-center">{{ _i('By users') }}</span>
                                            <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                                        </a>
                                    </li>
                                @endif
                                @can('access', [\Dotworkers\Security\Enums\Permissions::$report_financial_by_provider])
                                    <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                                        <a class="media u-side-nav--second-level-menu-link" href="{{ route('agents.reports.financial.state.provider') }}" target="_self">
                                            <span class="media-body align-self-center">{{ _i('By providers') }}</span>
                                            <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                                        </a>
                                    </li>
                                @endif
                                @can('access', [\Dotworkers\Security\Enums\Permissions::$agents_menu])
                                    <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                                        <a class="media u-side-nav--second-level-menu-link" href="{{ route('reports.view.transaction.timeline') }}" target="_self">
                                            <span class="media-body align-self-center">{{ _i('Transaction Timeline') }}</span>
                                            <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                                        </a>
                                    </li>
                                @endif
                                @can('access', [\Dotworkers\Security\Enums\Permissions::$agents_transactions])
                                    <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                                        <a class="media u-side-nav--second-level-menu-link" href="{{ route('agents.reports.agents-transactions') }}" target="_self">
                                            <span class="media-body align-self-center">{{ _i('Agents transactions') }}</span>
                                            <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                                        </a>
                                    </li>
                                @endif
                                @can('access', [\Dotworkers\Security\Enums\Permissions::$agents_cash_flow])
                                    <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                                        <a class="media u-side-nav--second-level-menu-link" href="{{ route('agents.reports.cash-flow') }}" target="_self">
                                            <span class="media-body align-self-center">{{ _i('Cash flow') }}</span>
                                            <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                                        </a>
                                    </li>
                                @endif
                                @can('access', [\Dotworkers\Security\Enums\Permissions::$agents_balances])
                                    <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                                        <a class="media u-side-nav--second-level-menu-link" href="{{ route('agents.reports.agents-balances') }}" target="_self">
                                            <span class="media-body align-self-center">{{ _i('Agents balances') }}</span>
                                            <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                                        </a>
                                    </li>
                                @endif
                                @can('access', [\Dotworkers\Security\Enums\Permissions::$agents_users_balances])
                                    <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                                        <a class="media u-side-nav--second-level-menu-link" href="{{ route('agents.reports.users-balances') }}" target="_self">
                                            <span class="media-body align-self-center">{{ _i('Users balances') }}</span>
                                            <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                                        </a>
                                    </li>
                                @endif
                                    @can('access', [\Dotworkers\Security\Enums\Permissions::$agents_balances])
                                        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                                            <a class="media u-side-nav--second-level-menu-link" href="{{ route('agents.reports.agents-balances') }}" target="_self">
                                                <span class="media-body align-self-center">{{ _i('Agents balances') }}</span>
                                                <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                                            </a>
                                        </li>
                                    @endif
                                    @can('access', [\Dotworkers\Security\Enums\Permissions::$agents_users_balances])
                                        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                                            <a class="media u-side-nav--second-level-menu-link" href="{{ route('agents.reports.users-balances') }}" target="_self">
                                                <span class="media-body align-self-center">{{ _i('Users balances') }}</span>
                                                <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                                            </a>
                                        </li>
                                    @endif
                                    @can('access', [\Dotworkers\Security\Enums\Permissions::$locked_providers])
                                        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                                            <a class="media u-side-nav--second-level-menu-link" href="{{ route('agents.reports.locked-providers') }}" target="_self">
                                                <span class="media-body align-self-center">{{ _i('Locked providers') }}</span>
                                                <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                                            </a>
                                        </li>
                                    @endif
                                    @can('access', [\Dotworkers\Security\Enums\Permissions::$locked_providers])
                                        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                                            <a class="media u-side-nav--second-level-menu-link" href="{{ route('agents.reports.exclude-providers-agents') }}" target="_self">
                                                <span class="media-body align-self-center">{{ _i('Exclude agents from providers') }}</span>
                                                <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                                            </a>
                                        </li>
                                    @endif
                            </ul>
                        </li>
                    @endif
                </ul>
            </li>
        @endif

        @can('access', [\Dotworkers\Security\Enums\Permissions::$create_user_agent])
            <li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
                <a class="media u-side-nav--top-level-menu-link u-side-nav--hide-on-hidden" href="{{ route('agents.create.agent') }}" target="_self">
                    <span class="g-pos-rel"><i class="fa-solid fa-people-group"></i></span> <span class="media-body align-self-center">{{ _i('Create agent user') }}</span>
                    <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                </a>
            </li>
        @endif

        @can('access', [\Dotworkers\Security\Enums\Permissions::$agents_dashboard])
            <li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
                <a class="media u-side-nav--top-level-menu-link u-side-nav--hide-on-hidden" href="{{ route('agents.create.user') }}" target="_self">
                    <span class="g-pos-rel"><i class="fa-solid fa-people-group"></i></span> <span class="media-body align-self-center">{{ _i('Create player user') }}</span>
                    <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                </a>
            </li>
        @endif

        @can('access', [\Dotworkers\Security\Enums\Permissions::$financial_reports_menu])
            <li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
                <a class="media u-side-nav--top-level-menu-link u-side-nav--hide-on-hidden" href="javascript:void(0)" data-toggle="collapse" data-target="#collapseExample" aria-expanded="true">
                    <span class="g-pos-rel"><i class="fa-regular fa-user"></i></span> <span class="media-body align-self-center">{{ _i('Financial') }}</span>
                    <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                </a>
                <ul id="collapseExample" class="u-sidebar-navigation-v1-menu u-side-nav--second-level-menu u-side-nav--second-level-menu-top mb-0 collapse show">
                    @can('access', [\Dotworkers\Security\Enums\Permissions::$payments_report])
                        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item active">
                            <a class="media u-side-nav--second-level-menu-link" href="{{ route('reports.payment-methods.totals') }}" target="_self">
                                <span class="media-body align-self-center">{{ _i('Totals by payment method') }}</span>
                                <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                            </a>
                        </li>
                    @endif
                    @can('access', [\Dotworkers\Security\Enums\Permissions::$totals_report])
                        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                            <a class="media u-side-nav--second-level-menu-link" href="{{ route('reports.financial.totals') }}" target="_self">
                                <span class="media-body align-self-center">{{ _i('Totals') }}</span>
                                <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                            </a>
                        </li>
                    @endif
                    @can('access', [\Dotworkers\Security\Enums\Permissions::$deposits_report])
                        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                            <a class="media u-side-nav--second-level-menu-link" href="{{ route('reports.financial.deposits') }}" target="_self">
                                <span class="media-body align-self-center">{{ _i('Deposits') }}</span>
                                <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                            </a>
                        </li>
                    @endif
                    @can('access', [\Dotworkers\Security\Enums\Permissions::$withdrawals_report])
                        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                            <a class="media u-side-nav--second-level-menu-link" href="{{ route('reports.financial.deposits') }}" target="_self">
                                <span class="media-body align-self-center">{{ _i('Withdrawals') }}</span>
                                <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                            </a>
                        </li>
                    @endif
                    @can('access', [\Dotworkers\Security\Enums\Permissions::$manual_transactions_report])
                        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                            <a class="media u-side-nav--second-level-menu-link" href="{{ route('reports.financial.manual-transactions') }}" target="_self">
                                <span class="media-body align-self-center">{{ _i('Manual transactions') }}</span>
                                <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                            </a>
                        </li>
                    @endif
                    @can('access', [\Dotworkers\Security\Enums\Permissions::$daily_sales])
                        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                            <a class="media u-side-nav--second-level-menu-link" href="{{ route('reports.financial.daily-sales') }}" target="_self">
                                <span class="media-body align-self-center">{{ _i('Daily sales') }}</span>
                                <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                            </a>
                        </li>
                    @endif
                    @can('access', [\Dotworkers\Security\Enums\Permissions::$monthly_sales])
                        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                            <a class="media u-side-nav--second-level-menu-link" href="{{ route('reports.financial.monthly-sales') }}" target="_self">
                                <span class="media-body align-self-center">{{ _i('Monthly sales') }}</span>
                                <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                            </a>
                        </li>
                    @endif
                    @can('access', [\Dotworkers\Security\Enums\Permissions::$sales_by_whitelabels])
                        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                            <a class="media u-side-nav--second-level-menu-link" href="{{ route('reports.financial.whitelabels-sales') }}" target="_self">
                                <span class="media-body align-self-center">{{ _i('Sales by whitelabels') }}</span>
                                <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                            </a>
                        </li>
                    @endif
                    @can('access', [\Dotworkers\Security\Enums\Permissions::$deposit_withdrawal_by_user])
                        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                            <a class="media u-side-nav--second-level-menu-link" href="{{ route('reports.financial.profit-by-user') }}" target="_self">
                                <span class="media-body align-self-center">{{ _i('Profit by user') }}</span>
                                <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                            </a>
                        </li>
                    @endif
                    @can('access', [\Dotworkers\Security\Enums\Permissions::$report_manual_adjustments])
                        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                            <a class="media u-side-nav--second-level-menu-link" href="{{ route('reports.financial.manual-adjustments') }}" target="_self">
                                <span class="media-body align-self-center">{{ _i('Manual adjustments') }}</span>
                                <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                            </a>
                        </li>
                    @endif
                    @can('access', [\Dotworkers\Security\Enums\Permissions::$manual_adjustments_whitelabel])
                        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                            <a class="media u-side-nav--second-level-menu-link" href="{{ route('reports.financial.manual-adjustments-users') }}" target="_self">
                                <span class="media-body align-self-center">{{ _i('Manual adjustments users') }}</span>
                                <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                            </a>
                        </li>
                    @endif
                    @can('access', [\Dotworkers\Security\Enums\Permissions::$agents_financial])
                        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                            <a class="media u-side-nav--second-level-menu-link" href="javascript:void(0)" target="_self" data-toggle="collapse" data-target="#collapseExampleTwo" aria-expanded="false">
                                <span class="media-body align-self-center">{{ _i('By Agents') }}</span>
                                <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                            </a>
                            <ul id="collapseExampleTwo" class="u-sidebar-navigation-v1-menu u-side-nav--second-level-menu mb-0 collapse">
                                @can('access', [\Dotworkers\Security\Enums\Permissions::$sales_by_whitelabels_by_agents])
                                    <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                                        <a class="media u-side-nav--second-level-menu-link" href="{{ route('agents.reports.financial-state-makers-details') }}" target="_self">
                                            <span class="media-body align-self-center">{{ _i('Sales by whitelabels') }}</span>
                                            <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                                        </a>
                                    </li>
                                @endif
                                @can('access', [\Dotworkers\Security\Enums\Permissions::$sales_by_providers_by_agents])
                                    <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                                        <a class="media u-side-nav--second-level-menu-link" href="{{ route('agents.reports.financial-state-makers') }}" target="_self">
                                            <span class="media-body align-self-center">{{ _i('Sales by providers') }}</span>
                                            <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                </ul>
            </li>
        @endif

        @can('access', [\Dotworkers\Security\Enums\Permissions::$operations_menu])
            <li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
                <a class="media u-side-nav--top-level-menu-link u-side-nav--hide-on-hidden" href="javascript:void(0)" data-toggle="collapse" data-target="#collapseExample" aria-expanded="true">
                    <span class="g-pos-rel"><i class="fa-regular fa-user"></i></span> <span class="media-body align-self-center">{{ _i('Operations') }}</span>
                    <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                </a>
                <ul id="collapseExample" class="u-sidebar-navigation-v1-menu u-side-nav--second-level-menu u-side-nav--second-level-menu-top mb-0 collapse show">
                    @can('access', [\Dotworkers\Security\Enums\Permissions::$products_totals])
                        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item active">
                            <a class="media u-side-nav--second-level-menu-link" href="{{ route('reports.products-totals') }}" target="_self">
                                <span class="media-body align-self-center">{{ _i('Products totals') }}</span>
                                <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                            </a>
                        </li>
                    @endif
                    @can('access', [\Dotworkers\Security\Enums\Permissions::$whitelabels_totals])
                        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                            <a class="media u-side-nav--second-level-menu-link" href="{{ route('reports.whitelabels-totals') }}" target="_self">
                                <span class="media-body align-self-center">{{ _i('Whitelabels totals') }}</span>
                                <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                            </a>
                        </li>
                    @endif
                    @can('access', [\Dotworkers\Security\Enums\Permissions::$whitelabels_totals])
                        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                            <a class="media u-side-nav--second-level-menu-link" href="{{ route('reports.products-totals-overview') }}" target="_self">
                                <span class="media-body align-self-center">{{ _i('Products totals') }}</span>
                                <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif

        @can('access', [\Dotworkers\Security\Enums\Permissions::$referrals_menu])
            <li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
                <a class="media u-side-nav--top-level-menu-link u-side-nav--hide-on-hidden" href="javascript:void(0)" data-toggle="collapse" data-target="#collapseExample" aria-expanded="true">
                    <span class="g-pos-rel"><i class="fa-regular fa-user"></i></span> <span class="media-body align-self-center">{{ _i('Referrals') }}</span>
                    <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                </a>
                <ul id="collapseExample" class="u-sidebar-navigation-v1-menu u-side-nav--second-level-menu u-side-nav--second-level-menu-top mb-0 collapse show">
                    @can('access', [\Dotworkers\Security\Enums\Permissions::$report_referrals])
                        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item active">
                            <a class="media u-side-nav--second-level-menu-link" href="{{ route('referrals.referral-totals') }}" target="_self">
                                <span class="media-body align-self-center">{{ _i('List Referral Totals') }}</span>
                                <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                            </a>
                        </li>
                    @endif
                    @can('access', [\Dotworkers\Security\Enums\Permissions::$report_referrals])
                        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                            <a class="media u-side-nav--second-level-menu-link" href="{{ route('referrals.referral-top') }}" target="_self">
                                <span class="media-body align-self-center">{{ _i('List Referral Top') }}</span>
                                <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif

        @can('access', [\Dotworkers\Security\Enums\Permissions::$betpay_menu])
            <li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
                <a class="media u-side-nav--top-level-menu-link u-side-nav--hide-on-hidden" href="javascript:void(0)" data-toggle="collapse" data-target="#collapseExample" aria-expanded="true">
                    <span class="g-pos-rel"><i class="fa-regular fa-user"></i></span> <span class="media-body align-self-center">{{ _i('BetPay') }}</span>
                    <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                </a>
                <ul id="collapseExample" class="u-sidebar-navigation-v1-menu u-side-nav--second-level-menu u-side-nav--second-level-menu-top mb-0 collapse show">
                    @can('access', [\Dotworkers\Security\Enums\Permissions::$activate_payments_methods])
                        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item active">
                            <a class="media u-side-nav--second-level-menu-link" href="{{ route('betpay.clients.accounts.create') }}" target="_self">
                                <span class="media-body align-self-center">{{ _i('Activate Payment Methods') }}</span>
                                <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                            </a>
                        </li>
                    @endif
                    @can('access', [\Dotworkers\Security\Enums\Permissions::$list_payments_methods])
                        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                            <a class="media u-side-nav--second-level-menu-link" href="{{ route('betpay.clients.accounts') }}" target="_self">
                                <span class="media-body align-self-center">{{ _i('List Accounts') }}</span>
                                <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                            </a>
                        </li>
                    @endif
                    @can('access', [\Dotworkers\Security\Enums\Permissions::$binance_menu])
                        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                            <a class="media u-side-nav--second-level-menu-link" href="javascript:void(0)" target="_self" data-toggle="collapse" data-target="#collapseExampleTwo" aria-expanded="false">
                                <span class="media-body align-self-center">{{ _i('Binance') }}</span>
                                <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                            </a>
                            <ul id="collapseExampleTwo" class="u-sidebar-navigation-v1-menu u-side-nav--second-level-menu mb-0 collapse">
                                @can('access', [\Dotworkers\Security\Enums\Permissions::$credit_binance_menu])
                                    <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                                        <a class="media u-side-nav--second-level-menu-link" href="{{ route('betpay.binance.credit') }}" target="_self">
                                            <span class="media-body align-self-center">{{ _i('Credit') }}</span>
                                            <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                                        </a>
                                    </li>
                                @endif
                                @can('access', [\Dotworkers\Security\Enums\Permissions::$debit_binance_menu])
                                    <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                                        <a class="media u-side-nav--second-level-menu-link" href="{{ route('betpay.binance.debit') }}" target="_self">
                                            <span class="media-body align-self-center">{{ _i('Debit') }}</span>
                                            <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
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
-->

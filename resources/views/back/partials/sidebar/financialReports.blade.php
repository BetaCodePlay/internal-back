<li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
    <a class="media u-side-nav--top-level-menu-link u-side-nav--hide-on-hidden" href="javascript:void(0)"
       data-toggle="collapse" data-target="#financialReportsSidebar" aria-expanded="true">
        <span class="g-pos-rel"><i class="fa-regular hs-admin-stats-up"></i></span> <span
            class="media-body align-self-center">{{ _i('Financial') }}</span>
        <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
    </a>
    <ul id="financialReportsSidebar"
        class="u-sidebar-navigation-v1-menu u-side-nav--second-level-menu u-side-nav--second-level-menu-top mb-0 collapse">
        @can('access', [$permissions::$payments_report])
            <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item active">
                <a class="media u-side-nav--second-level-menu-link"
                   href="{{ route('reports.payment-methods.totals') }}" target="_self">
                    <span class="media-body align-self-center">{{ _i('Totals by payment method') }}</span>
                    <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                </a>
            </li>
        @endif
        @can('access', [$permissions::$totals_report])
            <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                <a class="media u-side-nav--second-level-menu-link"
                   href="{{ route('reports.financial.totals') }}" target="_self">
                    <span class="media-body align-self-center">{{ _i('Totals') }}</span>
                    <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                </a>
            </li>
        @endif
        @can('access', [$permissions::$deposits_report])
            <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                <a class="media u-side-nav--second-level-menu-link"
                   href="{{ route('reports.financial.deposits') }}" target="_self">
                    <span class="media-body align-self-center">{{ _i('Deposits') }}</span>
                    <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                </a>
            </li>
        @endif
        @can('access', [$permissions::$withdrawals_report])
            <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                <a class="media u-side-nav--second-level-menu-link"
                   href="{{ route('reports.financial.withdrawals') }}" target="_self">
                    <span class="media-body align-self-center">{{ _i('Withdrawals') }}</span>
                    <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                </a>
            </li>
        @endif
        @can('access', [$permissions::$manual_transactions_report])
            <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                <a class="media u-side-nav--second-level-menu-link"
                   href="{{ route('reports.financial.manual-transactions') }}" target="_self">
                    <span class="media-body align-self-center">{{ _i('Manual transactions') }}</span>
                    <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                </a>
            </li>
        @endif
        @can('access', [$permissions::$daily_sales])
            <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                <a class="media u-side-nav--second-level-menu-link"
                   href="{{ route('reports.financial.daily-sales') }}" target="_self">
                    <span class="media-body align-self-center">{{ _i('Daily sales') }}</span>
                    <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                </a>
            </li>
        @endif
        @can('access', [$permissions::$monthly_sales])
            <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                <a class="media u-side-nav--second-level-menu-link"
                   href="{{ route('reports.financial.monthly-sales') }}" target="_self">
                    <span class="media-body align-self-center">{{ _i('Monthly sales') }}</span>
                    <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                </a>
            </li>
        @endif
        @can('access', [$permissions::$sales_by_whitelabels])
            <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                <a class="media u-side-nav--second-level-menu-link"
                   href="{{ route('reports.financial.whitelabels-sales') }}" target="_self">
                    <span class="media-body align-self-center">{{ _i('Sales by whitelabels') }}</span>
                    <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                </a>
            </li>
        @endif
        @can('access', [$permissions::$deposit_withdrawal_by_user])
            <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                <a class="media u-side-nav--second-level-menu-link"
                   href="{{ route('reports.financial.profit-by-user') }}" target="_self">
                    <span class="media-body align-self-center">{{ _i('Profit by user') }}</span>
                    <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                </a>
            </li>
        @endif
        @can('access', [$permissions::$report_manual_adjustments])
            <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                <a class="media u-side-nav--second-level-menu-link"
                   href="{{ route('reports.financial.manual-adjustments') }}" target="_self">
                    <span class="media-body align-self-center">{{ _i('Manual adjustments') }}</span>
                    <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                </a>
            </li>
        @endif
        @can('access', [$permissions::$manual_adjustments_whitelabel])
            <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                <a class="media u-side-nav--second-level-menu-link"
                   href="{{ route('reports.financial.manual-adjustments-users') }}" target="_self">
                    <span class="media-body align-self-center">{{ _i('Manual adjustments users') }}</span>
                    <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                </a>
            </li>
        @endif
        @can('access', [$permissions::$agents_financial])
            <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                <a class="media u-side-nav--second-level-menu-link" href="javascript:void(0)" target="_self"
                   data-toggle="collapse" data-target="#collapseExampleTwo" aria-expanded="false">
                    <span class="media-body align-self-center">{{ _i('By Agents') }}</span>
                    <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                </a>
                <ul id="agentsFinancialSidebar"
                    class="u-sidebar-navigation-v1-menu u-side-nav--second-level-menu mb-0 collapse">
                    @can('access', [$permissions::$sales_by_whitelabels_by_agents])
                        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                            <a class="media u-side-nav--second-level-menu-link"
                               href="{{ route('agents.reports.financial-state-makers-details') }}"
                               target="_self">
                                            <span
                                                class="media-body align-self-center">{{ _i('Sales by whitelabels') }}</span>
                                <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                            </a>
                        </li>
                    @endif
                    @can('access', [$permissions::$sales_by_providers_by_agents])
                        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                            <a class="media u-side-nav--second-level-menu-link"
                               href="{{ route('agents.reports.financial-state-makers') }}" target="_self">
                                            <span
                                                class="media-body align-self-center">{{ _i('Sales by providers') }}</span>
                                <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif
    </ul>
</li>

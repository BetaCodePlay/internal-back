<li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
    <a class="media u-side-nav--top-level-menu-link u-side-nav--hide-on-hidden" href="javascript:void(0)"
       data-toggle="collapse" data-target="#betpaySidebar" aria-expanded="true">
        <span class="g-pos-rel"><i class="fa-regular fa-user"></i></span> <span
            class="media-body align-self-center">{{ _i('BetPay') }}</span>
        <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
    </a>
    <ul id="betpaySidebar"
        class="u-sidebar-navigation-v1-menu u-side-nav--second-level-menu u-side-nav--second-level-menu-top mb-0 collapse">
        @can('access', [$permissions::$activate_payments_methods])
            <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item active">
                <a class="media u-side-nav--second-level-menu-link"
                   href="{{ route('betpay.clients.accounts.create') }}" target="_self">
                    <span class="media-body align-self-center">{{ _i('Activate Payment Methods') }}</span>
                    <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                </a>
            </li>
        @endif
        @can('access', [$permissions::$list_payments_methods])
            <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                <a class="media u-side-nav--second-level-menu-link"
                   href="{{ route('betpay.clients.accounts') }}" target="_self">
                    <span class="media-body align-self-center">{{ _i('List Accounts') }}</span>
                    <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                </a>
            </li>
        @endif
        @can('access', [$permissions::$binance_menu])
            <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                <a class="media u-side-nav--second-level-menu-link" href="javascript:void(0)" target="_self"
                   data-toggle="collapse" data-target="#binanceSidebar" aria-expanded="false">
                    <span class="media-body align-self-center">{{ _i('Binance') }}</span>
                    <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                </a>
                <ul id="binanceSidebar"
                    class="u-sidebar-navigation-v1-menu u-side-nav--second-level-menu mb-0 collapse">
                    @can('access', [$permissions::$credit_binance_menu])
                        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                            <a class="media u-side-nav--second-level-menu-link"
                               href="{{ route('betpay.binance.credit') }}" target="_self">
                                <span class="media-body align-self-center">{{ _i('Credit') }}</span>
                                <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                            </a>
                        </li>
                    @endif
                    @can('access', [$permissions::$debit_binance_menu])
                        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                            <a class="media u-side-nav--second-level-menu-link"
                               href="{{ route('betpay.binance.debit') }}" target="_self">
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

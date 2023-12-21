<li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
    <a class="media u-side-nav--top-level-menu-link u-side-nav--hide-on-hidden" href="javascript:void(0)"
       data-toggle="collapse" data-target="#betpayClientsSidebar" aria-expanded="true">
        <span class="g-pos-rel"><i class="fa-regular fa-user"></i></span> <span
            class="media-body align-self-center">{{ _i('BetPay Clients') }}</span>
        <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
    </a>
    <ul id="betpayClientsSidebar"
        class="u-sidebar-navigation-v1-menu u-side-nav--second-level-menu u-side-nav--second-level-menu-top mb-0 collapse">
        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item active">
            <a class="media u-side-nav--second-level-menu-link"
               href="{{ route('betpay.clients.create') }}" target="_self">
                <span class="media-body align-self-center">{{ _i('Create') }}</span>
                <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
            </a>
        </li>
        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
            <a class="media u-side-nav--second-level-menu-link"
               href="{{ route('betpay.clients.index') }}" target="_self">
                <span class="media-body align-self-center">{{ _i('List Clients') }}</span>
                <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
            </a>
        </li>
    </ul>
</li>

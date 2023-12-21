<li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
    <a class="media u-side-nav--top-level-menu-link u-side-nav--hide-on-hidden" href="javascript:void(0)"
       data-toggle="collapse" data-target="#manageWhitelabels" aria-expanded="true">
        <span class="g-pos-rel"><i class="fa-regular fa-user"></i></span> <span
            class="media-body align-self-center">{{ _i('Manage whitelabels') }}</span>
        <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
    </a>
    <ul id="manageWhitelabels"
        class="u-sidebar-navigation-v1-menu u-side-nav--second-level-menu u-side-nav--second-level-menu-top mb-0 collapse">
        @can('access', [$permissions::$manage_whitelabels_status])
            <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item active">
                <a class="media u-side-nav--second-level-menu-link"
                   href="{{ route('whitelabels.status') }}" target="_self">
                    <span class="media-body align-self-center">{{ _i('Whitelabels status') }}</span>
                    <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                </a>
            </li>
        @endif
        @can('access', [$permissions::$whitelabels_active_providers])
            <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                <a class="media u-side-nav--second-level-menu-link"
                   href="{{ route('reports.whitelabels-active-providers') }}" target="_self">
                    <span class="media-body align-self-center">{{ _i('Active providers') }}</span>
                    <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                </a>
            </li>
        @endif
    </ul>
</li>

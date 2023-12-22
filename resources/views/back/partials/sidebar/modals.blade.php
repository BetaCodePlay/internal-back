<li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
    <a class="media u-side-nav--top-level-menu-link u-side-nav--hide-on-hidden" href="javascript:void(0)"
       data-toggle="collapse" data-target="#modalSidebar" aria-expanded="true">
        <span class="g-pos-rel"><i class="hs-admin-layout-slider-alt"></i></span> <span
            class="media-body align-self-center">{{ _i('Popups') }}</span>
        <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
    </a>
    <ul id="modalSidebar"
        class="u-sidebar-navigation-v1-menu u-side-nav--second-level-menu u-side-nav--second-level-menu-top mb-0 collapse">
        @can('access', [$permissions::$manage_modals])
            <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item active">
                <a class="media u-side-nav--second-level-menu-link"
                   href="{{ route('section-modals.create') }}" target="_self">
                    <span class="media-body align-self-center">{{ _i('New') }}</span>
                    <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                </a>
            </li>
        @endif
        @can('access', [$permissions::$modals_list])
            <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                <a class="media u-side-nav--second-level-menu-link"
                   href="{{ route('section-modals.index') }}" target="_self">
                    <span class="media-body align-self-center">{{ _i('List') }}</span>
                    <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                </a>
            </li>
        @endif
    </ul>
</li>

<li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
    <a class="media u-side-nav--top-level-menu-link u-side-nav--hide-on-hidden" href="javascript:void(0)"
       data-toggle="collapse" data-target="#bonusSidebar" aria-expanded="true">
        <span class="g-pos-rel"><i class="fa-regular fa-user"></i></span> <span
            class="media-body align-self-center">{{ _i('Bonus system') }}</span>
        <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
    </a>
    <ul id="bonusSidebar"
        class="u-sidebar-navigation-v1-menu u-side-nav--second-level-menu u-side-nav--second-level-menu-top mb-0 collapse show">
        @can('access', [$permissions::$campaigns_menu])
            <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                <a class="media u-side-nav--second-level-menu-link" href="javascript:void(0)" target="_self"
                   data-toggle="collapse" data-target="#campaignsSidebar" aria-expanded="false">
                    <span class="media-body align-self-center">{{ _i('Campaigns') }}</span>
                    <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                </a>
                <ul id="campaignsSidebar"
                    class="u-sidebar-navigation-v1-menu u-side-nav--second-level-menu mb-0 collapse">
                    @can('access', [$permissions::$manage_campaigns])
                        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                            <a class="media u-side-nav--second-level-menu-link"
                               href="{{ route('bonus-system.campaigns.create') }}"
                               target="_self">
                                            <span
                                                class="media-body align-self-center">{{ _i('New') }}</span>
                                <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                            </a>
                        </li>
                    @endif
                    @can('access', [$permissions::$manage_campaigns])
                        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                            <a class="media u-side-nav--second-level-menu-link"
                               href="{{ route('bonus-system.campaigns.index') }}" target="_self">
                                            <span
                                                class="media-body align-self-center">{{ _i('List') }}</span>
                                <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif
    </ul>
</li>

@php
    use Dotworkers\Configurations\Configurations;

    $store = Configurations::getStore();
    $storeConfiguration = Configurations::getTemplateElement('store');
@endphp

@if($store?->active && $storeConfiguration?->data?->slider?->active)
    <li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
        <a class="media u-side-nav--top-level-menu-link u-side-nav--hide-on-hidden" href="javascript:void(0)"
           data-toggle="collapse" data-target="#storeSidebar" aria-expanded="true">
            <span class="g-pos-rel"><i class="hs-admin-shopping-cart"></i></span> <span
                class="media-body align-self-center">{{ _i('Store') }}</span>
            <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
        </a>
        <ul id="storeSidebar"
            class="u-sidebar-navigation-v1-menu u-side-nav--second-level-menu u-side-nav--second-level-menu-top mb-0 collapse">

            <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                <a class="media u-side-nav--second-level-menu-link" href="javascript:void(0)" target="_self"
                   data-toggle="collapse" data-target="#rewardsSidebar" aria-expanded="false">
                    <span class="media-body align-self-center">{{ _i('Rewards') }}</span>
                    <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                </a>
                <ul id="rewardsSidebar"
                    class="u-sidebar-navigation-v1-menu u-side-nav--second-level-menu mb-0 collapse {!! request()->is('store/rewards*') ? 'show' : '' !!}">
                    <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                        <a class="media u-side-nav--second-level-menu-link"
                           href="{{ route('store.rewards.create') }}"
                           target="_self">
                            <span class="media-body align-self-center">{{ _i('Create') }}</span>
                            <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                        </a>
                    </li>

                    <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                        <a class="media u-side-nav--second-level-menu-link"
                           href="{{ route('store.rewards.index') }}" target="_self">
                            <span class="media-body align-self-center">{{ _i('List') }}</span>
                            <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                <a class="media u-side-nav--second-level-menu-link" href="javascript:void(0)" target="_self"
                   data-toggle="collapse" data-target="#categoriesSidebar" aria-expanded="false">
                    <span class="media-body align-self-center">{{ _i('Categories') }}</span>
                    <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                </a>
                <ul id="categoriesSidebar"
                    class="u-sidebar-navigation-v1-menu u-side-nav--second-level-menu mb-0 collapse {!! request()->is('store/categories*') ? 'show' : '' !!}">
                    <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                        <a class="media u-side-nav--second-level-menu-link"
                           href="{{ route('store.categories.create') }}"
                           target="_self">
                            <span class="media-body align-self-center">{{ _i('Manage') }}</span>
                            <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                <a class="media u-side-nav--second-level-menu-link" href="javascript:void(0)" target="_self"
                   data-toggle="collapse" data-target="#reportsStoreSidebar" aria-expanded="false">
                    <span class="media-body align-self-center">{{ _i('Reports') }}</span>
                    <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                </a>
                <ul id="reportsStoreSidebar"
                    class="u-sidebar-navigation-v1-menu u-side-nav--second-level-menu mb-0 collapse {!! request()->is('store/reports*') ? 'show' : '' !!}">
                    <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                        <a class="media u-side-nav--second-level-menu-link"
                           href="{{ route('store.reports.redeemed-rewards') }}"
                           target="_self">
                            <span class="media-body align-self-center">{{ _i('Redeemed rewards') }}</span>
                            <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </li>
@endif

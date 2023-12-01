<div id="sideNav" class="col-auto u-sidebar-navigation-v1 u-sidebar-navigation--dark">
    <ul id="sideNavMenu" class="u-sidebar-navigation-v1-menu u-side-nav--top-level-menu g-min-height-100vh mb-0">
        {{--{!! \Core::buildMenu() !!}--}}
        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
            <a class="media u-side-nav--top-level-menu-link u-side-nav--hide-on-hidden" href="#" target="_self">
                <span class="g-pos-rel"><i class="fa-solid fa-house-chimney"></i></span> <span class="media-body align-self-center">{{ _i('Home') }}</span>
            </a>
        </li>

        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
            <a class="media u-side-nav--top-level-menu-link u-side-nav--hide-on-hidden active" href="#" target="_self">
                <span class="g-pos-rel"><i class="fa-solid fa-laptop-code"></i></span> <span class="media-body align-self-center">{{ _i('Dashboard') }}</span>
            </a>
        </li>

        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
            <a class="media u-side-nav--top-level-menu-link u-side-nav--hide-on-hidden" href="#" target="_self">
                <span class="g-pos-rel"><i class="fa-regular fa-user"></i></span> <span class="media-body align-self-center">{{ _i('Usuarios') }}</span>
            </a>
        </li>

        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
            <a class="media u-side-nav--top-level-menu-link u-side-nav--hide-on-hidden g-px-15 g-py-12 active" href="https://dev-back.bestcasinos.lat/agents/create-agent" target="_self">
                <span class="d-flex align-self-center g-pos-rel g-font-size-18 g-mr-18"><i class="hs-admin-bar-chart"></i></span><span class="media-body align-self-center">Crear usuario de agente</span>
            </a>
        </li>
    </ul>
</div>

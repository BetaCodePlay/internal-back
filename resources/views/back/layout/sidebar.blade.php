<div id="sideNav" class="col-auto u-sidebar-navigation-v1 u-sidebar-navigation--dark">
    <ul id="sideNavMenu" class="u-sidebar-navigation-v1-menu u-side-nav--top-level-menu g-min-height-100vh mb-0">
        {!! \Core::buildMenu() !!}
    </ul>

    <div class="col-social-navigation">
        @if(\Dotworkers\Configurations\Configurations::getWhitelabel() == 20 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 11)
            <a class="color-telegram" href="https://t.me/+uc7jrJU0DfY0NTcx" target="_blank"><i class="fa fa-telegram"></i> <span class="social-name">Canal Publicitario</span></a>
        @elseif(\Dotworkers\Configurations\Configurations::getWhitelabel() == 25)
            <a class="color-telegram" href="https://t.me/+uc7jrJU0DfY0NTcx" target="_blank"><i class="fa fa-telegram"></i> <span class="social-name">Canal Publicitario</span></a>
        @else
            <a class="color-telegram" href="https://t.me/graficascasino" target="_blank"><i class="fa fa-telegram"></i> <span class="social-name">Canal Publicitario</span></a>
        @endif

    </div>
</div>

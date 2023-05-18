<div id="sideNav" class="col-auto u-sidebar-navigation-v1 u-sidebar-navigation--dark">
    <ul id="sideNavMenu" class="u-sidebar-navigation-v1-menu u-side-nav--top-level-menu g-min-height-100vh mb-0">
        {!! \Core::buildMenu() !!}
    </ul>

    @if(auth()->user()->username === 'wolf')
        <div class="col-social-navigation">
            <a class="color-telegram" href="https://t.me/graficascasino" target="_blank"><i class="fa fa-telegram"></i> <span class="social-name">Telegram</span></a>
        </div>
    @endif
</div>

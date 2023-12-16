<li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
    <a class="media u-side-nav--top-level-menu-link u-side-nav--hide-on-hidden" href="javascript:void(0)"
       data-toggle="collapse" data-target="#collapseExample" aria-expanded="true">
        <span class="g-pos-rel"><i class="hs-admin-list"></i></span>
        <span class="media-body align-self-center">{{ _i('Sliders') }}</span>
        <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>

        <ul id="collapseExample"
            class="u-sidebar-navigation-v1-menu u-side-nav--second-level-menu u-side-nav--second-level-menu-top mb-0 collapse show">
            @foreach($sliderSections as $slider)
                <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item active">
                    <a class="media u-side-nav--second-level-menu-link"
                       href="{{ route('betpay.clients.accounts.create') }}" target="_self">
                        <span class="media-body align-self-center">{{ $slider->text }}</span>
                        <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                    </a>
                </li>
            @endforeach

            {{-- Todo Esto sería la forma de agregar un tercer nivel. --}}
            <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                <a class="media u-side-nav--second-level-menu-link" href="javascript:void(0)" target="_self"
                   data-toggle="collapse" data-target="#collapseExampleTwo" aria-expanded="false">
                    <span class="media-body align-self-center">Reportes</span>
                    <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                </a>
                <ul id="collapseExampleTwo"
                    class="u-sidebar-navigation-v1-menu u-side-nav--second-level-menu mb-0 collapse">
                    <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                        <a class="media u-side-nav--second-level-menu-link" href="#" target="_self">
                            <span class="media-body align-self-center">Estado</span>
                            <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                        </a>
                    </li>
                    <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                        <a class="media u-side-nav--second-level-menu-link" href="#" target="_self">
                            <span class="media-body align-self-center">Resumen</span>
                            <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                        </a>
                    </li>
                    <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                        <a class="media u-side-nav--second-level-menu-link" href="#" target="_self">
                            <span class="media-body align-self-center">Transacciones</span>
                            <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </a>
</li>

@php
    $lobbies = lobbySections();
    $lobbiesSections = $lobbies['lobby'];
@endphp

{{--
<li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
    <a class="media u-side-nav--top-level-menu-link u-side-nav--hide-on-hidden" href="javascript:void(0)"
       data-toggle="collapse" data-target="#lobbiesSidebar" aria-expanded="true">
        <span class="g-pos-rel"><i class="hs-admin-gallery"></i></span> <span
            class="media-body align-self-center">{{ _i('Lobbys') }}</span>
        <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
    </a>
    <ul id="lobbiesSidebar"
        class="u-sidebar-navigation-v1-menu u-side-nav--second-level-menu u-side-nav--second-level-menu-top mb-0 collapse">
        @foreach($lobbiesSections as $sectionKey => $slider)
            <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                <a class="media u-side-nav--second-level-menu-link" href="javascript:void(0)" target="_self"
                   data-toggle="collapse" data-target="#lobbiesActionSidebar-{{$sectionKey}}" aria-expanded="false">
                    <span class="media-body align-self-center">{{ $slider->text }}</span>
                    <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                </a>
            </li>
        @endforeach
    </ul>
--}}

<li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
    <a class="media u-side-nav--top-level-menu-link u-side-nav--hide-on-hidden" href="javascript:void(0)"
       data-toggle="collapse" data-target="#lobbiesSidebar" aria-expanded="true">
        <span class="g-pos-rel"><i class="hs-admin-gallery"></i></span> <span
            class="media-body align-self-center">{{ _i('Lobbys') }}</span>
        <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
    </a>
    <ul id="lobbiesSidebar"
        class="u-sidebar-navigation-v1-menu u-side-nav--second-level-menu u-side-nav--second-level-menu-top mb-0 collapse">
        @foreach($lobbiesSections as $sectionKey => $slider)
            <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                <a class="media u-side-nav--second-level-menu-link" href="javascript:void(0)" target="_self"
                   data-toggle="collapse" data-target="#lobbiesActionSidebar-{{$sectionKey}}" aria-expanded="false">
                    <span class="media-body align-self-center">{{ $slider->text }}</span>
                    <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                </a>
                <ul id="lobbiesActionSidebar-{{$sectionKey}}"
                    class="u-sidebar-navigation-v1-menu u-side-nav--second-level-menu mb-0 collapse {{ request()->is('sliders*' . $sectionKey) ? 'show' : '' }}">
                    @can('access', [$permissions::$manage_section_images])
                        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                            <a class="media u-side-nav--second-level-menu-link"
                               href="{{ route('sliders.create', [TemplateElementTypes::$home, $sectionKey]) }}"
                               target="_self">
                                <span class="media-body align-self-center">{{ _i('Upload') }}</span>
                                <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                            </a>
                        </li>
                    @endif
                    @can('access', [$permissions::$sliders_list])
                        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                            <a class="media u-side-nav--second-level-menu-link"
                               href="{{ route('sliders.index', [TemplateElementTypes::$home, $sectionKey]) }}"
                               target="_self">
                                <span class="media-body align-self-center">{{ _i('List') }}</span>
                                <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endforeach
    </ul>
</li>




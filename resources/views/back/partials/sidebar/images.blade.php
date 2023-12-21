@php
    use Dotworkers\Configurations\Enums\TemplateElementTypes;
@endphp

<li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
    <a class="media u-side-nav--top-level-menu-link u-side-nav--hide-on-hidden" href="javascript:void(0)"
       data-toggle="collapse" data-target="#collapseExample" aria-expanded="true">
        <span class="g-pos-rel"><i class="hs-admin-list"></i></span>
        <span class="media-body align-self-center">{{ _i('Image') }}</span>
        <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>

        <ul id="collapseExample"
            class="u-sidebar-navigation-v1-menu u-side-nav--second-level-menu u-side-nav--second-level-menu-top mb-0 collapse show">
            @foreach($imageSections as $sectionKey => $image)
                <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item active">
                    <a class="media u-side-nav--second-level-menu-link"
                       href="{{ route('section-images.index', [TemplateElementTypes::$home, $sectionKey]) }}" target="_self">
                        <span class="media-body align-self-center">{{ $image->text }}</span>
                        <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                    </a>
                </li>
            @endforeach
        </ul>
    </a>
</li>

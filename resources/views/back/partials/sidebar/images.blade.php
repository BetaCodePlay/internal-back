@php
    use Dotworkers\Configurations\Enums\TemplateElementTypes;
@endphp

<li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
    <a class="media u-side-nav--top-level-menu-link u-side-nav--hide-on-hidden" href="javascript:void(0)"
       data-toggle="collapse" data-target="#collapseExample" aria-expanded="true">
        <span class="g-pos-rel"><i class="hs-admin-image"></i></span>
        <span class="media-body align-self-center">{{ _i('Image') }}</span>
        <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>

        <ul id="collapseExample"
            class="u-sidebar-navigation-v1-menu u-side-nav--second-level-menu u-side-nav--second-level-menu-top mb-0 collapse">
            @foreach($imageSections as $sectionKey => $image)
                <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item active">
                    <a class="media u-side-nav--second-level-menu-link"
                       href="{{ route('section-images.index', [TemplateElementTypes::$home, $sectionKey]) }}" target="_self">
                        <span class="media-body align-self-center">{{ $image->text }}</span>
                        <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                    </a>
                </li>
            @endforeach
            @can('access', [$permissions::$section_images_list])
                <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item active">
                    <a class="media u-side-nav--second-level-menu-link"
                       href="{{ route('section-images.index', [TemplateElementTypes::$register_form]) }}" target="_self">
                        <span class="media-body align-self-center">{{ _i('Register form') }}</span>
                        <span class="icon-mobile"><i class="hs-admin-shift-left"></i></span>
                    </a>
                </li>

                <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item active">
                    <a class="media u-side-nav--second-level-menu-link"
                       href="{{ route('section-images.index', [TemplateElementTypes::$login_form]) }}" target="_self">
                        <span class="media-body align-self-center">{{ _i('Login form') }}</span>
                        <span class="icon-mobile"><i class="hs-admin-import"></i></span>
                    </a>
                </li>
                <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item active">
                    <a class="media u-side-nav--second-level-menu-link"
                       href="{{ route('section-images.index', [TemplateElementTypes::$header]) }}" target="_self">
                        <span class="media-body align-self-center">{{ _i('Logo and favicon') }}</span>
                        <span class="icon-mobile"><i class="hs-admin-import"></i></span>
                    </a>
                </li>
            @endif
        </ul>
    </a>
</li>

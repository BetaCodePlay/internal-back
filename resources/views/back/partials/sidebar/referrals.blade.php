<li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
    <a class="media u-side-nav--top-level-menu-link u-side-nav--hide-on-hidden" href="javascript:void(0)"
       data-toggle="collapse" data-target="#reportReferralsSidebar" aria-expanded="true">
        <span class="g-pos-rel"><i class="fa-regular fa-user"></i></span> <span
            class="media-body align-self-center">{{ _i('Referrals') }}</span>
        <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
    </a>
    <ul id="reportReferralsSidebar"
        class="u-sidebar-navigation-v1-menu u-side-nav--second-level-menu u-side-nav--second-level-menu-top mb-0 collapse">
        @can('access', [$permissions::$report_referrals])
            <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item active">
                <a class="media u-side-nav--second-level-menu-link"
                   href="{{ route('referrals.referral-totals') }}" target="_self">
                    <span class="media-body align-self-center">{{ _i('List Referral Totals') }}</span>
                    <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                </a>
            </li>
        @endif
        @can('access', [$permissions::$report_referrals])
            <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                <a class="media u-side-nav--second-level-menu-link"
                   href="{{ route('referrals.referral-top') }}" target="_self">
                    <span class="media-body align-self-center">{{ _i('List Referral Top') }}</span>
                    <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                </a>
            </li>
        @endif
    </ul>
</li>

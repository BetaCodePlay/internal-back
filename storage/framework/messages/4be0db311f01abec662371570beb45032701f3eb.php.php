<li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
    <a class="media u-side-nav--top-level-menu-link u-side-nav--hide-on-hidden" href="javascript:void(0)"
       data-toggle="collapse" data-target="#bonusSidebar" aria-expanded="true">
        <span class="g-pos-rel"><i class="fa-regular fa-user"></i></span> <span
            class="media-body align-self-center"><?php echo e(_i('Bonus system')); ?></span>
        <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
    </a>
    <ul id="bonusSidebar"
        class="u-sidebar-navigation-v1-menu u-side-nav--second-level-menu u-side-nav--second-level-menu-top mb-0 collapse">
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [$permissions::$campaigns_menu])): ?>
            <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                <a class="media u-side-nav--second-level-menu-link" href="javascript:void(0)" target="_self"
                   data-toggle="collapse" data-target="#campaignsSidebar" aria-expanded="false">
                    <span class="media-body align-self-center"><?php echo e(_i('Campaigns')); ?></span>
                    <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                </a>
                <ul id="campaignsSidebar"
                    class="u-sidebar-navigation-v1-menu u-side-nav--second-level-menu mb-0 collapse <?php echo request()->is('bonus-system/campaigns*') ? 'show' : ''; ?>">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [$permissions::$manage_campaigns])): ?>
                        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                            <a class="media u-side-nav--second-level-menu-link"
                               href="<?php echo e(route('bonus-system.campaigns.create')); ?>"
                               target="_self">
                                            <span
                                                class="media-body align-self-center"><?php echo e(_i('New')); ?></span>
                                <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [$permissions::$manage_campaigns])): ?>
                        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                            <a class="media u-side-nav--second-level-menu-link"
                               href="<?php echo e(route('bonus-system.campaigns.index')); ?>" target="_self">
                                            <span
                                                class="media-body align-self-center"><?php echo e(_i('List')); ?></span>
                                <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </li>
        <?php endif; ?>
    </ul>
</li>

<li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
    <a class="media u-side-nav--top-level-menu-link u-side-nav--hide-on-hidden" href="javascript:void(0)"
       data-toggle="collapse" data-target="#postsSidebar" aria-expanded="true">
        <span class="g-pos-rel"><i class="hs-admin-files"></i></span> <span
            class="media-body align-self-center"><?php echo e(_i('Posts')); ?></span>
        <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
    </a>
    <ul id="postsSidebar"
        class="u-sidebar-navigation-v1-menu u-side-nav--second-level-menu u-side-nav--second-level-menu-top mb-0 collapse">
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [$permissions::$manage_promotions])): ?>
            <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item active">
                <a class="media u-side-nav--second-level-menu-link"
                   href="<?php echo e(route('posts.create')); ?>" target="_self">
                    <span class="media-body align-self-center"><?php echo e(_i('New')); ?></span>
                    <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                </a>
            </li>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [$permissions::$promotions_list])): ?>
            <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                <a class="media u-side-nav--second-level-menu-link"
                   href="<?php echo e(route('posts.index')); ?>" target="_self">
                    <span class="media-body align-self-center"><?php echo e(_i('List')); ?></span>
                    <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                </a>
            </li>
        <?php endif; ?>
    </ul>
</li>

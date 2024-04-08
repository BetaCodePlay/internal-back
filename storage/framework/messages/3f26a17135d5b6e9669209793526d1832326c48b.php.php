<?php
    $lobbies = lobbySections();
    $lobbiesSections = $lobbies['lobby'];
?>



<li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
    <a class="media u-side-nav--top-level-menu-link u-side-nav--hide-on-hidden" href="javascript:void(0)"
       data-toggle="collapse" data-target="#lobbiesSidebar" aria-expanded="true">
        <span class="g-pos-rel"><i class="hs-admin-gallery"></i></span> <span
            class="media-body align-self-center"><?php echo e(_i('Lobbys')); ?></span>
        <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
    </a>
    <ul id="lobbiesSidebar"
        class="u-sidebar-navigation-v1-menu u-side-nav--second-level-menu u-side-nav--second-level-menu-top mb-0 collapse">
        <?php $__currentLoopData = $lobbiesSections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sectionKey => $slider): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                <a class="media u-side-nav--second-level-menu-link" href="javascript:void(0)" target="_self"
                   data-toggle="collapse" data-target="#lobbiesActionSidebar-<?php echo e($sectionKey); ?>" aria-expanded="false">
                    <span class="media-body align-self-center"><?php echo e($slider->text); ?></span>
                    <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                </a>
                <ul id="lobbiesActionSidebar-<?php echo e($sectionKey); ?>"
                    class="u-sidebar-navigation-v1-menu u-side-nav--second-level-menu mb-0 collapse <?php echo e(request()->is('sliders*' . $sectionKey) ? 'show' : ''); ?>">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [$permissions::$manage_section_images])): ?>
                        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                            <a class="media u-side-nav--second-level-menu-link"
                               href="<?php echo e(route('sliders.create', [TemplateElementTypes::$home, $sectionKey])); ?>"
                               target="_self">
                                <span class="media-body align-self-center"><?php echo e(_i('Upload')); ?></span>
                                <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [$permissions::$sliders_list])): ?>
                        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                            <a class="media u-side-nav--second-level-menu-link"
                               href="<?php echo e(route('sliders.index', [TemplateElementTypes::$home, $sectionKey])); ?>"
                               target="_self">
                                <span class="media-body align-self-center"><?php echo e(_i('List')); ?></span>
                                <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
</li>




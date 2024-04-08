<?php
    use Dotworkers\Security\Enums\Permissions;

    $sectionsData = generateSections();
    $sliderSections = $sectionsData['sliderSections'];
    $imageSections = $sectionsData['imageSections'];

    $permissions = Permissions::class;
?>

<div id="sideNav" class="col-auto u-sidebar-navigation-v1 u-sidebar-navigation--dark">
    <ul id="sideNavMenu" class="u-sidebar-navigation-v1-menu u-side-nav--top-level-menu mb-0">
        

        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active has-menu">
            <div class="u-sidebar-menu action-mobile-menu"><i class="fa-solid fa-arrow-left-long"></i> <?php echo e(_i('Menu')); ?>

            </div>
        </li>

        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
            <div class="u-sidebar-title"><span><?php echo e(_i('Categories')); ?></span></div>
        </li>
        <!--
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [$permissions::$dashboard])): ?>
            <?php echo $__env->make('back.partials.sidebar.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>
        -->
        <?php echo $__env->make('back.partials.sidebar.roleDashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <?php echo $__env->make('back.partials.sidebar.role', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <?php echo $__env->make('back.partials.sidebar.report', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <!--
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [$permissions::$users_menu])): ?>
            <?php echo $__env->make('back.partials.sidebar.users', ['permissions' => $permissions], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [$permissions::$agents_menu])): ?>
            <?php echo $__env->make('back.partials.sidebar.agents', ['permissions' => $permissions], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [$permissions::$create_user_agent])): ?>
            <?php echo $__env->make('back.partials.sidebar.createUserAgent', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [$permissions::$agents_dashboard])): ?>
            <?php echo $__env->make('back.partials.sidebar.createUserPlayer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [$permissions::$financial_reports_menu])): ?>
            <?php echo $__env->make('back.partials.sidebar.financialReports', ['permissions' => $permissions], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [$permissions::$operations_menu])): ?>
            <?php echo $__env->make('back.partials.sidebar.operations', ['permissions' => $permissions], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [$permissions::$referrals_menu])): ?>
            <?php echo $__env->make('back.partials.sidebar.referrals', ['permissions' => $permissions], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [$permissions::$betpay_menu])): ?>
            <?php echo $__env->make('back.partials.sidebar.betpay', ['permissions' => $permissions], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [$permissions::$manage_sliders])): ?>
            <?php echo $__env->make('back.partials.sidebar.sliders', ['permissions' => $permissions], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [$permissions::$section_images_menu])): ?>
            <?php echo $__env->make('back.partials.sidebar.images', ['permissions' => $permissions], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [$permissions::$system_bonus_menu])): ?>
            <?php echo $__env->make('back.partials.sidebar.bonus', ['permissions' => $permissions], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [$permissions::$section_games_menu])): ?>
            <?php echo $__env->make('back.partials.sidebar.lobbyGames', ['permissions' => $permissions], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [$permissions::$section_images_menu])): ?>
            <?php echo $__env->make('back.partials.sidebar.lobby', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [$permissions::$whitelabels_games_menu])): ?>
            <?php echo $__env->make('back.partials.sidebar.whitelabelsGames', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [$permissions::$modals_menu])): ?>
            <?php echo $__env->make('back.partials.sidebar.modals', ['permissions' => $permissions], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [$permissions::$promotions_menu])): ?>
            <?php echo $__env->make('back.partials.sidebar.posts', ['permissions' => $permissions], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [$permissions::$pages_menu])): ?>
            <?php echo $__env->make('back.partials.sidebar.pages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [$permissions::$manage_whitelabels_status_menu])): ?>
            <?php echo $__env->make('back.partials.sidebar.whitelabelsActiveProviders', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [$permissions::$manage_betpay_menu])): ?>
            <?php echo $__env->make('back.partials.sidebar.betpayClients', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [$permissions::$exchange_rates])): ?>
            <?php echo $__env->make('back.partials.sidebar.exchangeRates', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [$permissions::$manage_providers])): ?>
            <?php echo $__env->make('back.partials.sidebar.providers', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [$permissions::$manage_main_agents])): ?>
            <?php echo $__env->make('back.partials.sidebar.mainAgents', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [$permissions::$manage_main_users])): ?>
            <?php echo $__env->make('back.partials.sidebar.mainUsers', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [$permissions::$update_rol_admin])): ?>
            <?php echo $__env->make('back.partials.sidebar.changeRolAdmin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [$permissions::$update_password_wolf])): ?>
            <?php echo $__env->make('back.partials.sidebar.updatePasswordOfWolf', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [$permissions::$betpay_menu])): ?>
            <?php echo $__env->make('back.partials.sidebar.store', ['permissions' => $permissions], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>
        -->
        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
            <hr>
        </li>

        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
            <div class="u-sidebar-title"><span><?php echo e(_i('Account')); ?></span></div>
        </li>

        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
            <a class="media u-side-nav--top-level-menu-link u-side-nav--hide-on-hidden" href="#" target="_self">
                <span class="g-pos-rel"><i class="fa-regular fa-bell"></i></span> <span
                    class="media-body align-self-center"><?php echo e(_i('Notifications')); ?></span>
                <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
            </a>
        </li>

        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
            <a class="media u-side-nav--top-level-menu-link u-side-nav--hide-on-hidden" href="#" target="_self">
                <span class="g-pos-rel"><i class="fa-solid fa-gear"></i></span> <span class="media-body align-self-center"><?php echo e(_i('Setting')); ?></span>
                <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
            </a>
        </li>

        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
            <hr>
        </li>

        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active has-logout">
            <a class="media u-side-nav--top-level-menu-link u-side-nav--hide-on-hidden" href="<?php echo e(route('auth.logout')); ?>" target="_self">
                <span class="g-pos-rel"><i class="fa-solid fa-power-off"></i></span> <span class="media-body align-self-center"><?php echo e(_i('Logout')); ?></span>
            </a>
        </li>

        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active mobile-hidde">
            <a class="media u-side-nav--top-level-menu-link u-side-nav--hide-on-hidden collapse-menu-action-s" href="javascript:void(0)">
                <span class="g-pos-rel"><i class="fa-solid fa-arrows-left-right-to-line"></i></span> <span class="media-body align-self-center"><?php echo e(_i('Collapse')); ?></span>
            </a>
        </li>
    </ul>
</div>

<div class="nav-mobile">
    <div class="nav-mobile-ex">
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [$permissions::$dashboard])): ?>
            <div class="nav-mobile-opt"><a class="active" href="<?php echo e(route('core.dashboard')); ?>"><i class="fa-solid fa-house-chimney"></i> <span class="name"><?php echo e(_i('Home')); ?></span></a></div>
        <?php endif; ?>

        <div class="nav-mobile-opt">
            <a href="<?php echo e(route('agents.role')); ?>">
                <i class="fa-solid fa-people-group"></i> <span class="name"><?php echo e(_i('Role')); ?></span>
            </a>
        </div>

        <div class="nav-mobile-opt">
            <a href="#">
                <i class="fa-solid fa-chart-column"></i> <span class="name"><?php echo e(_i('Reports')); ?></span>
            </a>
        </div>

        <div class="nav-mobile-opt action-mobile-menu">
            <a href="javascript:void(0)">
                <i class="fa-solid fa-bars"></i> <span class="name"><?php echo e(_i('Menu')); ?></span>
            </a>
        </div>
    </div>
</div>

<!--
<div id="sideNav" class="col-auto u-sidebar-navigation-v1 u-sidebar-navigation--dark">
    <ul id="sideNavMenu" class="u-sidebar-navigation-v1-menu u-side-nav--top-level-menu mb-0">
        

<li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active has-menu">
    <div class="u-sidebar-menu action-mobile-menu"><i class="fa-solid fa-arrow-left-long"></i> <?php echo e(_i('Menu')); ?></div>
        </li>

        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
            <div class="u-sidebar-title"><span><?php echo e(_i('Categories')); ?></span></div>
        </li>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [$permissions::$dashboard])): ?>
    <li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
        <a class="media u-side-nav--top-level-menu-link u-side-nav--hide-on-hidden" href="<?php echo e(route('core.dashboard')); ?>" target="_self">
                    <span class="g-pos-rel"><i class="fa-solid fa-house-chimney"></i></span> <span class="media-body align-self-center"><?php echo e(_i('Home')); ?></span>
                    <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                </a>
            </li>













<?php endif; ?>
<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [$permissions::$agents_dashboard])): ?>
    <li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
        <a class="media u-side-nav--top-level-menu-link u-side-nav--hide-on-hidden active" href="<?php echo e(route('agents.index')); ?>" target="_self">
                    <span class="g-pos-rel"><i class="fa-solid fa-people-group"></i></span> <span class="media-body align-self-center"><?php echo e(_i('Role')); ?></span>
                    <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                </a>
            </li>













<?php endif; ?>
<li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
    <a class="media u-side-nav--top-level-menu-link u-side-nav--hide-on-hidden" href="javascript:void(0)" data-toggle="collapse" data-target="#collapseExample" aria-expanded="true">
        <span class="g-pos-rel"><i class="fa-regular fa-user"></i></span> <span class="media-body align-self-center"><?php echo e(_i('Usuarios')); ?></span>
                <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
            </a>
            <ul id="collapseExample" class="u-sidebar-navigation-v1-menu u-side-nav--second-level-menu u-side-nav--second-level-menu-top mb-0 collapse show">
                <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item active">
                    <a class="media u-side-nav--second-level-menu-link" href="#" target="_self">
                        <span class="media-body align-self-center">Dashboard</span>
                        <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                    </a>
                </li>
                <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                    <a class="media u-side-nav--second-level-menu-link" href="#" target="_self">
                        <span class="media-body align-self-center">Agregar usuarios</span>
                        <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                    </a>
                </li>
                <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                    <a class="media u-side-nav--second-level-menu-link" href="javascript:void(0)" target="_self" data-toggle="collapse" data-target="#collapseExampleTwo" aria-expanded="false">
                        <span class="media-body align-self-center">Reportes</span>
                        <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                    </a>
                    <ul id="collapseExampleTwo" class="u-sidebar-navigation-v1-menu u-side-nav--second-level-menu mb-0 collapse">
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
        </li>

        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
            <hr>
        </li>

        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
            <div class="u-sidebar-title"><span><?php echo e(_i('Account')); ?></span></div>
        </li>

        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
            <a class="media u-side-nav--top-level-menu-link u-side-nav--hide-on-hidden" href="#" target="_self">
                <span class="g-pos-rel"><i class="fa-regular fa-bell"></i></span> <span class="media-body align-self-center"><?php echo e(_i('Notifications')); ?></span>
                <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
            </a>
        </li>

        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
            <a class="media u-side-nav--top-level-menu-link u-side-nav--hide-on-hidden" href="#" target="_self">
                <span class="g-pos-rel"><i class="fa-solid fa-gear"></i></span> <span class="media-body align-self-center"><?php echo e(_i('Setting')); ?></span>
                <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
            </a>
        </li>
        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
            <hr>
        </li>

        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active has-logout">
            <a class="media u-side-nav--top-level-menu-link u-side-nav--hide-on-hidden" href="<?php echo e(route('auth.logout')); ?>" target="_self">
                <span class="g-pos-rel"><i class="fa-solid fa-right-from-bracket"></i></span> <span class="media-body align-self-center"><?php echo e(_i('Logout')); ?></span>
            </a>
        </li>

        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active mobile-hidde">
            <a class="media u-side-nav--top-level-menu-link u-side-nav--hide-on-hidden collapse-menu-action-s" href="javascript:void(0)">
                <span class="g-pos-rel"><i class="fa-solid fa-arrows-left-right-to-line"></i></span> <span class="media-body align-self-center"><?php echo e(_i('Collapse')); ?></span>
            </a>
        </li>
    </ul>
</div>
<div class="nav-mobile">
    <div class="nav-mobile-ex">
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [$permissions::$dashboard])): ?>
    <div class="nav-mobile-opt"><a class="active" href="<?php echo e(route('core.dashboard')); ?>"><i class="fa-solid fa-house-chimney"></i> <span class="name"><?php echo e(_i('Home')); ?></span></a></div>













<?php endif; ?>
<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [$permissions::$agents_dashboard])): ?>
    <div class="nav-mobile-opt"><a href="<?php echo e(route('agents.index')); ?>"><i class="fa-solid fa-people-group"></i> <span class="name"><?php echo e(_i('Role')); ?></span></a></div>













<?php endif; ?>
<div class="nav-mobile-opt"><a href="#"><i class="fa-solid fa-chart-column"></i> <span class="name"><?php echo e(_i('Reports')); ?></span></a></div>
        <div class="nav-mobile-opt action-mobile-menu"><a href="javascript:void(0)"><i class="fa-solid fa-bars"></i> <span class="name"><?php echo e(_i('Menu')); ?></span></a></div>
    </div>
</div>
-->


<header id="js-header" class="u-header u-header--sticky-top" data-route-find="<?php echo e(route('agents.find')); ?>" data-auth-user="<?php echo e(auth()->user()->id); ?>" data-user-type="<?php echo e(auth()->user()->typeUser); ?>">
    <div class="u-header__section u-header__section--admin-dark g-min-height-65">
        <nav class="navbar no-gutters g-pa-0">
            <div class="col-auto d-flex flex-nowrap u-header-logo-toggler g-py-12">
                <a href="<?php echo e(route('agents.role.dashboard')); ?>" class="navbar-brand d-flex align-self-center g-hidden-xs-down g-line-height-1 py-0 g-mt-0">
                    <?php if(!empty($logo)): ?>
                        <?php if(!is_null($logo->img_dark)): ?>
                            <img src="<?php echo e($logo->img_dark); ?>" alt="Logo" width="180" height="37" class="img-logo">
                        <?php endif; ?>
                    <?php endif; ?>
                </a>
                <a href="<?php echo e(route('agents.role.dashboard')); ?>" class="navbar-brand-mini">
                    <img src="<?php echo e($favicon); ?>" alt="Logo" width="180" height="37" class="img-logo-mini">
                </a>

                <a class="js-side-nav u-header__nav-toggler align-self-center ml-auto collapse-menu-action" href="#!"
                   data-hssm-class="u-side-nav--mini u-sidebar-navigation-v1--mini"

                   data-hssm-body-class="u-side-nav-mini" data-hssm-is-close-all-except-this="true"
                   data-hssm-target="#sideNav">
                    <i class="hs-admin-align-left"></i>
                </a>
            </div>


            <div class="col-auto d-flex g-py-12 ml-auto">
                <div class="col-auto d-flex g-pt-5 g-pt-0--sm g-pl-5 g-pr-10">
                    <div class="g-pos-rel">
                        <div class="d-block">
                            <div class="d-inline-block g-pos-rel g-pos-rel-amount">
                                <i class="fa-solid fa-coins"></i> <span class="amount-refresh-<?php echo e(auth()->user()->id); ?>"><?php echo e(getAuthenticatedUserBalance()); ?></span>
                            </div>

                            <?php if(!empty($whitelabel_currencies) && count($whitelabel_currencies)>1): ?>
                                <div class="d-inline-block g-pos-rel dropdown">
                                    <button class="btn dropdown-toggle" type="button" id="dropdownCurrency" data-toggle="dropdown" aria-expanded="false">
                                        <?php echo e(session('currency') == 'VEF' ? $free_currency->currency_name : session('currency')); ?> <i class="fa-solid fa-angle-down"></i>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownCurrency">
                                        <?php $__currentLoopData = $whitelabel_currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li><a class="dropdown-item" href="<?php echo e(route('core.change-currency', [$currency->iso])); ?>"><?php echo e($currency->iso == 'VEF' ? $free_currency->currency_name : $currency->iso); ?>  <span class="mini-title"><?php echo e($currency->name); ?></span></a></li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                        <!--
                            <div class="d-inline-block g-pos-rel btn-mini-h notified-active"><i class="fa-regular fa-bell"></i></div>
                            <div class="d-inline-block g-pos-rel btn-mini-h pos-mobile-hidden"><i class="fa-solid fa-gear"></i></div>
                            -->
                            <div class="d-inline-block g-pos-rel pos-mobile-hidden">
                                <?php
                                    $avatar = \App\Users\Users::getAvatar();
                                ?>
                                <?php if(!is_null ($avatar)): ?>
                                    <img class="g-width-30 img-avatar g-width-40--md g-height-30 g-height-40--md rounded-circle g-mr-10--sm" src="<?php echo e($avatar); ?>" alt="<?php echo e(isset(auth()->user()->username) ? auth()->user()->username : ''); ?>">
                                <?php else: ?>
                                    <img class="g-width-30 img-avatar g-width-40--md g-height-30 g-height-40--md rounded-circle g-mr-10--sm" src="<?php echo e(asset('back/img/avatar-default.jpg')); ?>" alt="<?php echo e(isset(auth()->user()->username) ? auth()->user()->username : ''); ?>">
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </div>
</header>

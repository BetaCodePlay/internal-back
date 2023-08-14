<header id="js-header" class="u-header u-header--sticky-top">
    <div class="u-header__section u-header__section--admin-dark g-min-height-65">
        <nav class="navbar no-gutters g-pa-0">
            <div class="col-auto d-flex flex-nowrap u-header-logo-toggler g-py-12">
                <a href="<?php echo e(route('core.dashboard')); ?>"
                   class="navbar-brand d-flex align-self-center g-hidden-xs-down g-line-height-1 py-0 g-mt-0">
                    <?php if(!empty($logo)): ?>
                        <?php if(!is_null($logo->img_dark)): ?>
                            <img src="<?php echo e($logo->img_dark); ?>" alt="Bloko" width="180" height="37" class="img-logo">
                        <?php endif; ?>
                    <?php endif; ?>
                    
                    
                    
                    
                    
                    
                    
                    
                    
                </a>
                <a class="js-side-nav u-header__nav-toggler d-flex align-self-center ml-auto" href="#!"
                   data-hssm-class="u-side-nav--mini u-sidebar-navigation-v1--mini"
                   data-hssm-body-class="u-side-nav-mini" data-hssm-is-close-all-except-this="true"
                   data-hssm-target="#sideNav">
                    <i class="hs-admin-align-left"></i>
                </a>
            </div>


                    <form id="header-search-form" class="u-header--search col-sm g-py-12 g-ml-15--sm g-ml-20--md g-mr-10--sm"
                          aria-labelledby="searchInvoker" action="<?php echo e(route('users.search')); ?>" method="get">
                        <div class="input-group g-max-width-450">
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [\Dotworkers\Security\Enums\Permissions::$users_search])): ?>
                                <input class="form-control form-control-md g-rounded-4" type="text" name="username" placeholder="<?php echo e(_i('Search user')); ?>" value="<?php echo e(isset($username) ? $username : ''); ?>">
                                <button type="submit"
                                        class="btn u-btn-outline-primary g-brd-none g-bg-transparent--hover g-pos-abs g-top-0 g-right-0 d-flex g-width-40 h-100 align-items-center justify-content-center g-font-size-18 g-z-index-2">
                                    <i class="hs-admin-search"></i>
                                </button>
                            <?php endif; ?>
                        </div>
                    </form>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [\Dotworkers\Security\Enums\Permissions::$users_search])): ?>
                        <a id="searchInvoker" class="g-hidden-sm-up text-uppercase u-header-icon-v1 g-pos-rel g-width-40 g-height-40 rounded-circle g-font-size-20" href="#!" aria-controls="header-search-form" aria-haspopup="true" aria-expanded="false" data-is-mobile-only="true" data-dropdown-event="click"
                           data-dropdown-target="#header-search-form" data-dropdown-type="css-animation" data-dropdown-duration="300" data-dropdown-animation-in="fadeIn" data-dropdown-animation-out="fadeOut">
                            <i class="hs-admin-search g-absolute-centered"></i>
                        </a>
                    <?php endif; ?>


            <div class="col-auto d-flex g-py-12 ml-auto">
                <div class="col-auto d-flex g-pt-5 g-pt-0--sm g-pl-5 g-pr-5">
                    <div class="g-pos-rel">
                        <a class="btn btn-info text-white btn-header-chat btn-header-auth g-pl-10 g-pr-10" href="javascript:void(0)" style="border-radius: 50px">
                            <i class="fa fa-comment" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-auto d-flex g-py-12 ml-auto">
                <div class="col-auto d-flex g-pt-5 g-pt-0--sm g-pl-5 g-pr-5">
                    <div class="g-pos-rel">
                        <span class="balanceAuth_<?php echo e(\Illuminate\Support\Facades\Auth::id()); ?>"></span> <?php echo e(session('currency') == 'VEF' ? $free_currency->currency_name : session('currency')); ?>

                    </div>
                </div>
            </div>
            <div class="col-auto d-flex g-py-12 ml-auto">
                <div class="col-auto d-flex g-pt-5 g-pt-0--sm g-pl-5 g-pr-10">
                    <div class="g-pos-rel">
                        <a id="profile-menu-invoker" class="d-block" href="#!" aria-controls="profile-menu" aria-haspopup="true" aria-expanded="false" data-dropdown-event="click" data-dropdown-target="#profile-menu" data-dropdown-type="css-animation" data-dropdown-duration="300"
                           data-dropdown-animation-in="fadeIn" data-dropdown-animation-out="fadeOut">
                           <span class="g-pos-rel">
                           <span class="u-badge-v2--xs u-badge--top-right g-hidden-sm-up g-bg-secondary g-mr-5"></span>
                               <?php
                                   $avatar = \App\Users\Users::getAvatar();
                               ?>
                               <?php if(!is_null ($avatar)): ?>
                                   <img class="g-width-30 g-width-40--md g-height-30 g-height-40--md rounded-circle g-mr-10--sm" src="<?php echo e($avatar); ?>" alt="<?php echo e(isset(auth()->user()->username) ? auth()->user()->username : ''); ?>">
                               <?php else: ?>
                                   <img class="g-width-30 g-width-40--md g-height-30 g-height-40--md rounded-circle g-mr-10--sm" src="<?php echo e(asset('back/img/avatar-default.jpg')); ?>" alt="<?php echo e(isset(auth()->user()->username) ? auth()->user()->username : ''); ?>">
                               <?php endif; ?>
                            </span>
                            <span class="g-pos-rel">
                                <span class="g-hidden-sm-down">
                                    <?php echo e(isset(auth()->user()->username) ? auth()->user()->username : ''); ?>

                                </span>
                                <i class="hs-admin-angle-down g-pos-rel g-top-2 g-ml-10"></i>
                            </span>
                        </a>
                        <ul id="profile-menu" class="g-pos-abs g-left-0 g-nowrap g-font-size-14 g-py-20 g-mt-30 rounded"
                            aria-labelledby="profile-menu-invoker">
                            <li class="mb-0">
                                <a class="media g-color-primary--hover g-py-5 g-px-20"
                                   href="<?php echo e(route('auth.logout')); ?>">
                                    <span class="d-flex align-self-center g-mr-12">
                                        <i class="hs-admin-shift-right"></i>
                                    </span>
                                    <span class="media-body align-self-center">
                                        <?php echo e(_i('Logout')); ?>

                                    </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
    </div>
</header>

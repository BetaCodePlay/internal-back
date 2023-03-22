

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-3">
            <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-15">
                <section class="text-center g-mb-30 g-mb-30--md">
                    <div class="d-inline-block g-pos-rel g-mb-20">
                        <?php if(is_null($user->avatar)): ?>
                            <img class="img-fluid rounded-circle" src="<?php echo e(asset('back/img/avatar-default.jpg')); ?>"
                                alt="<?php echo e($title); ?>">
                        <?php else: ?>
                            <img class="img-fluid rounded-circle" src="<?php echo e($user->avatar); ?>"
                                alt="<?php echo e($title); ?>">
                        <?php endif; ?>
                    </div>
                    <h3 class="g-font-weight-300 g-font-size-20 g-color-black g-mb-10">
                        <?php echo e($title); ?>

                    </h3>
                    <div class="media-body d-flex justify-content-center">
                        <button type="button" data-route="<?php echo e(route('users.change-status', [$user->id, 1, 1])); ?>"
                                class="btn u-btn-3d u-btn-teal g-mr-10 change-status <?php echo e(!$user->status ? 'd-none' : ''); ?>"
                                data-loading-text="<i class='fa fa-spin fa-spinner'></i> <?php echo e(_i('Updating')); ?>" id="active-status">
                            <i class="hs-admin-check"></i>
                            <?php echo e(_i('Active')); ?>

                        </button>
                        <button type="button" data-route="<?php echo e(route('users.change-status', [$user->id, 0, 1])); ?>"
                                class="btn u-btn-3d u-btn-primary g-mr-10 change-status <?php echo e($user->status ? 'd-none' : ''); ?>"
                                data-loading-text="<i class='fa fa-spin fa-spinner'></i> <?php echo e(_i('Updating')); ?>" id="inactive-status">
                            <i class="hs-admin-close"></i>
                            <?php echo e(_i('Inactive')); ?>

                        </button>
                        <?php if(!in_array(\Dotworkers\Security\Enums\Roles::$admin_Beet_sweet, session('roles'))): ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [\Dotworkers\Security\Enums\Permissions::$user_login])): ?>
                                <a type="button" class="btn u-btn-3d u-btn-blue g-mr-10" href="<?php echo e($login_user); ?>" data-route="<?php echo e(route('users.audit-users')); ?>" target="_blank" id="login_user">
                                    <i class="hs-admin-user"></i>
                                    <?php echo e(_i('See how')); ?>

                                </a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                    <br>
                </section>
                <section>
                    <ul class="list-unstyled g-mb-0">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [\Dotworkers\Security\Enums\Permissions::$reset_users_password])): ?>
                            <li class="g-brd-top g-brd-gray-light-v7 g-mb-0 g-pl-10">
                                <a href="#reset-password-modal" class="d-flex align-items-center u-link-v5 g-parent g-py-5"
                                   data-toggle="modal">
                                <span class="g-font-size-18 g-color-gray-light-v6 g-color-primary--parent-hover g-color-primary--parent-active g-mr-15">
						            <i class="hs-admin-lock"></i>
					            </span>
                                    <span class="g-color-gray-dark-v6 g-color-primary--parent-hover g-color-primary--parent-active">
                                    <?php echo e(_i('Reset password')); ?>

                                </span>
                                </a>
                            </li>
                        <?php endif; ?>
                        
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [\Dotworkers\Security\Enums\Permissions::$manual_adjustments])): ?>
                            <li class="g-brd-top g-brd-gray-light-v7 g-mb-0 g-pl-10">
                                <a href="#manual-adjustments-modal"
                                   class="d-flex align-items-center u-link-v5 g-parent g-py-5" data-toggle="modal">
                                    <span class="g-font-size-18 g-color-gray-light-v6 g-color-primary--parent-hover g-color-primary--parent-active g-mr-15">
                                        <i class="hs-admin-pencil"></i>
                                    </span>
                                    <span class="g-color-gray-dark-v6 g-color-primary--parent-hover g-color-primary--parent-active">
                                        <?php echo e(_i('Manual adjustment (Balance corrections)')); ?>

                                    </span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [\Dotworkers\Security\Enums\Permissions::$manual_transactions])): ?>
                            <li class="g-brd-top g-brd-gray-light-v7 g-mb-0 g-pl-10">
                                <a href="#manual-transaction-modal"
                                   class="d-flex align-items-center u-link-v5 g-parent g-py-5" data-toggle="modal" data-transaction-type="<?php echo e(\Dotworkers\Configurations\Enums\TransactionTypes::$credit); ?>" data-transaction-name="<?php echo e(_i('credit')); ?>">
                                    <span class="g-font-size-18 g-color-gray-light-v6 g-color-primary--parent-hover g-color-primary--parent-active g-mr-15">
                                        <i class="hs-admin-plus"></i>
                                    </span>
                                    <span class="g-color-gray-dark-v6 g-color-primary--parent-hover g-color-primary--parent-active">
                                        <?php echo e(_i('Manual credit transaction')); ?>

                                    </span>
                                </a>
                            </li>
                            <li class="g-brd-top g-brd-gray-light-v7 g-mb-0 g-pl-10">
                                <a href="#manual-transaction-modal"
                                   class="d-flex align-items-center u-link-v5 g-parent g-py-5" data-toggle="modal" data-transaction-type="<?php echo e(\Dotworkers\Configurations\Enums\TransactionTypes::$debit); ?>" data-transaction-name="<?php echo e(_i('debit')); ?>">
                                    <span class="g-font-size-18 g-color-gray-light-v6 g-color-primary--parent-hover g-color-primary--parent-active g-mr-15">
                                        <i class="hs-admin-minus"></i>
                                    </span>
                                    <span class="g-color-gray-dark-v6 g-color-primary--parent-hover g-color-primary--parent-active">
                                        <?php echo e(_i('Manual debit transaction')); ?>

                                    </span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [\Dotworkers\Security\Enums\Permissions::$bonus_transactions])): ?>
                            <li class="g-brd-top g-brd-gray-light-v7 g-mb-0 g-pl-10">
                                <a href="#bonus-transaction-modal"
                                   class="d-flex align-items-center u-link-v5 g-parent g-py-5"
                                   data-toggle="modal">
                                        <span
                                            class="g-font-size-18 g-color-gray-light-v6 g-color-primary--parent-hover g-color-primary--parent-active g-mr-15">
                                            <i class="hs-admin-gift"></i>
                                        </span>
                                    <span
                                        class="g-color-gray-dark-v6 g-color-primary--parent-hover g-color-primary--parent-active">
                                            <?php echo e(_i('Assign bonus')); ?> (<?php echo e(_i('Real money')); ?>)
                                        </span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if($store): ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [\Dotworkers\Security\Enums\Permissions::$points_transactions])): ?>
                                <li class="g-brd-top g-brd-gray-light-v7 g-mb-0 g-pl-10">
                                    <a href="#points-transactions-modal"
                                       class="d-flex align-items-center u-link-v5 g-parent g-py-5" data-toggle="modal">
                                        <span class="g-font-size-18 g-color-gray-light-v6 g-color-primary--parent-hover g-color-primary--parent-active g-mr-15">
                                            <i class="hs-admin-package"></i>
                                        </span>
                                        <span class="g-color-gray-dark-v6 g-color-primary--parent-hover g-color-primary--parent-active">
                                            <?php echo e(_i('Points transactions')); ?>

                                        </span>
                                    </a>
                                </li>
                            <?php endif; ?>
                        <?php endif; ?>






















                        <?php if($bonus): ?>



































                        <?php endif; ?>

                    </ul>
                </section>
            </div>
            <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-15">
                <header class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-py-10">
                    <div class="media">
                        <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 g-mb-0">
                            <?php echo e(_i('Balance')); ?> (<?php echo e($wallet->currency_iso); ?>)
                        </h3>
                    </div>
                </header>
                <div class="card-block g-px-15 g-py-5">
                    <div class="media u-header-dropdown-bordered-v2 g-brd-gray-light-v7 g-py-10">
                        <div class="d-flex align-self-center g-mr-12">
                            <span class="u-badge-v2--sm g-pos-stc g-transform-origin--top-left g-bg-darkblue-v2"></span>
                        </div>
                        <div class="media-body align-self-center">
                            <?php echo e(_i('Main')); ?>

                        </div>
                        <div class="d-flex text-right g-font-size-18 g-font-weight-900" id="main-balance">
                            <?php echo e($wallet->balance); ?>

                        </div>
                    </div>
                    <?php if( $wallet->balance_locked > 0 ): ?>
                        <div class="media u-header-dropdown-bordered-v2 g-brd-gray-light-v7 g-py-10">
                            <div class="d-flex align-self-center g-mr-12">
                                <span class="u-badge-v2--sm g-pos-stc g-transform-origin--top-left g-bg-orange"></span>
                            </div>
                            <div class="media-body align-self-center">
                                <?php echo e(_i('Locked')); ?>

                            </div>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [\Dotworkers\Security\Enums\Permissions::$unlock_balance])): ?>
                                <div class="d-flex text-right g-font-size-18 g-font-weight-900">
                                    <a href="#unlock-balance-modal" class="btn u-btn-3d u-btn-bluegray u-btn-3d" data-toggle="modal">
                                        <?php echo e($wallet->balance_locked); ?>

                                    </a>
                                </div>
                            <?php else: ?>
                                <div class="d-flex text-right g-font-size-18 g-font-weight-900">
                                    <?php echo e($wallet->balance_locked); ?>

                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php $__currentLoopData = $wallets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $walletData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($walletData->id != $wallet->id): ?>
                    <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-15">
                        <header class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-py-10">
                            <div class="media">
                                <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 g-mb-0">
                                    <?php echo e(_i('Balance')); ?> (<?php echo e($walletData->currency_iso); ?>)
                                </h3>
                            </div>
                        </header>
                        <div class="card-block g-px-15 g-py-5">
                            <div class="media u-header-dropdown-bordered-v2 g-brd-gray-light-v7 g-py-10">
                                <div class="d-flex align-self-center g-mr-12">
                                    <span class="u-badge-v2--sm g-pos-stc g-transform-origin--top-left g-bg-darkblue-v2"></span>
                                </div>
                                <div class="media-body align-self-center">
                                    <?php echo e(_i('Main')); ?>

                                </div>
                                <div class="d-flex text-right g-font-size-18 g-font-weight-900">
                                    <?php echo e($walletData->balance); ?>

                                </div>
                            </div>
                            <?php if( $walletData->balance_locked > 0 ): ?>
                                <div class="media u-header-dropdown-bordered-v2 g-brd-gray-light-v7 g-py-10">
                                    <div class="d-flex align-self-center g-mr-12">
                                        <span class="u-badge-v2--sm g-pos-stc g-transform-origin--top-left g-bg-orange"></span>
                                    </div>
                                    <div class="media-body align-self-center">
                                        <?php echo e(_i('Locked')); ?>

                                    </div>
                                    <div class="d-flex text-right g-font-size-18 g-font-weight-900">
                                        <?php echo e($walletData->balance_locked); ?>

                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            
        </div>

        <div class="col-md-6">
            <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                <form action="<?php echo e(route('users.profiles.update')); ?>" id="profile-form" method="post">
                    <header class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 g-mb-0">
                                <?php echo e(_i('Personal information')); ?>

                            </h3>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [\Dotworkers\Security\Enums\Permissions::$update_users_data])): ?>
                                <div class="media-body d-flex justify-content-end">
                                    <input type="hidden" name="user" value="<?php echo e($user->id); ?>">
                                    <button type="button" class="btn u-btn-3d u-btn-primary float-right" id="update-profile"
                                            data-loading-text="<i class='fa fa-spin fa-spinner'></i> <?php echo e(_i('Updating...')); ?>">
                                        <i class="hs-admin-reload"></i>
                                        <?php echo e(_i('Update data')); ?>

                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </header>
                    <div class="card-block g-pa-15">
                        <div class="row g-mb-15">
                            <div class="col-md-2 align-self-center g-mb-5 g-mb-0--md">
                                <label class="g-mb-0" for="id">
                                    <?php echo e(_i('ID')); ?>

                                </label>
                            </div>
                            <div class="col-md-10 align-self-center">
                                <div class="form-group g-pos-rel g-mb-0">
                                    <?php echo e($user->id); ?>

                                </div>
                            </div>
                        </div>
                        <div class="row g-mb-15">
                            <div class="col-md-2 align-self-center g-mb-5 g-mb-0--md">
                                <label class="g-mb-0" for="id">
                                    <?php echo e(_i('Username')); ?>

                                </label>
                            </div>
                            <div class="col-md-10 align-self-center">
                                <div class="form-group g-pos-rel g-mb-0">
                                    <?php echo e($user->username); ?>

                                </div>
                            </div>
                        </div>
                        <div class="row g-mb-15">
                            <div class="col-md-2 align-self-center g-mb-5 g-mb-0--md">
                                <label class="g-mb-0" for="id">
                                    <?php echo e(_i('Referral code')); ?>

                                </label>
                            </div>
                            <div class="col-md-10 align-self-center">
                                <div class="form-group g-pos-rel g-mb-0">
                                    <?php echo e($user->referral_code); ?>

                                </div>
                            </div>
                        </div>
                        <?php if(!in_array(\Dotworkers\Security\Enums\Roles::$admin_Beet_sweet, session('roles'))): ?>
                            <?php if(isset($agent)): ?>
                                <div class="row g-mb-15">
                                    <div class="col-md-2 align-self-center g-mb-5 g-mb-0--md">
                                        <label class="g-mb-0" for="id">
                                            <?php echo e(_i('Parent agent')); ?>

                                        </label>
                                    </div>
                                    <div class="col-md-10 align-self-center">
                                        <div class="form-group g-pos-rel g-mb-0">
                                            <?php echo $agent; ?>

                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [\Dotworkers\Security\Enums\Permissions::$show_wallet_id])): ?>
                            <div class="row g-mb-15">
                                <div class="col-md-2 align-self-center g-mb-5 g-mb-0--md">
                                    <label class="g-mb-0" for="id">
                                        <?php echo e(_i('Wallet ID')); ?>

                                    </label>
                                </div>
                                <div class="col-md-10 align-self-center">
                                    <div class="form-group g-pos-rel g-mb-0">
                                        <?php echo e($wallet->id); ?>

                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="row g-mb-10">
                            <div class="col-md-2 align-self-center g-mb-5 g-mb-0--md">
                                <label class="g-mb-0" for="email">
                                    <?php echo e(_i('Email')); ?>

                                </label>
                            </div>
                            <div class="col-md-10 align-self-center">
                                <div class="form-group g-pos-rel g-mb-0">
                                    <input id="email" name="email" class="form-control" type="email"
                                           value="<?php echo e($user->email); ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row g-mb-10">
                            <div class="col-md-2 align-self-center g-mb-5 g-mb-0--md">
                                <label class="g-mb-0" for="dni">
                                    <?php echo e(_i('DNI')); ?>

                                </label>
                            </div>

                            <div class="col-md-10 align-self-center">
                                <div class="form-group g-pos-rel g-mb-0">
                                    <input id="dni" name="dni" class="form-control" type="text"
                                           value="<?php echo e($user->dni); ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row g-mb-10">
                            <div class="col-md-2 align-self-center g-mb-5 g-mb-0--md">
                                <label class="g-mb-0" for="first_name">
                                    <?php echo e(_i('Name')); ?>

                                </label>
                            </div>
                            <div class="col-md-10 align-self-center">
                                <div class="form-group g-pos-rel g-mb-0">
                                    <input id="first_name" name="first_name" class="form-control" type="text"
                                           value="<?php echo e($user->first_name); ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row g-mb-10">
                            <div class="col-md-2 align-self-center g-mb-5 g-mb-0--md">
                                <label class="g-mb-0" for="last_name">
                                    <?php echo e(_i('Last Name')); ?>

                                </label>
                            </div>
                            <div class="col-md-10 align-self-center">
                                <div class="form-group g-pos-rel g-mb-0">
                                    <input id="last_name" name="last_name" class="form-control" type="text"
                                           value="<?php echo e($user->last_name); ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row g-mb-10">
                            <div class="col-md-2 align-self-center g-mb-5 g-mb-0--md">
                                <label class="g-mb-0" for="gender">
                                    <?php echo e(_i('Gender')); ?>

                                </label>
                            </div>
                            <div class="col-md-10 align-self-center">
                                <div class="form-group g-pos-rel g-mb-0">
                                    <select name="gender" id="gender" class="form-control">
                                        <?php if(is_null($user->gender)): ?>
                                            <option value="" selected><?php echo e(_i('Select...')); ?></option>
                                        <?php endif; ?>
                                        <option value="F" <?php echo e($user->gender == 'F' ? 'selected' : ''); ?>>
                                            <?php echo e(_i('Female')); ?>

                                        </option>
                                        <option value="M" <?php echo e($user->gender == 'M' ? 'selected' : ''); ?>>
                                            <?php echo e(_i('Male')); ?>

                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row g-mb-10">
                            <div class="col-md-2 align-self-center g-mb-5 g-mb-0--md">
                                <label class="g-mb-0" for="level">
                                    <?php echo e(_i('User level')); ?>

                                </label>
                            </div>
                            <div class="col-md-10 align-self-center">
                                <div class="form-group g-pos-rel g-mb-0">
                                    <select name="level" id="level" class="form-control">
                                        <?php $__currentLoopData = $levels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $level): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($level->id); ?>" <?php echo e($level->id == $user->level ? 'selected' : ''); ?>>
                                                <?php echo e($level->{$selected_language['iso']}); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row g-mb-10">
                            <div class="col-md-2 align-self-center g-mb-5 g-mb-0--md">
                                <label class="g-mb-0" for="country">
                                    <?php echo e(_i('Country')); ?>

                                </label>
                            </div>
                            <div class="col-md-10 align-self-center">
                                <div class="form-group g-pos-rel g-mb-0">
                                    <select name="country" id="country" class="form-control" data-route="<?php echo e(route('core.states')); ?>">
                                        <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($country->iso); ?>" <?php echo e($country->iso == $user->country_iso ? 'selected' : ''); ?>>
                                                <?php echo e($country->name); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row g-mb-10">
                            <div class="col-md-2 align-self-center g-mb-5 g-mb-0--md">
                                <label class="g-mb-0" for="state">
                                    <?php echo e(_i('State')); ?>

                                </label>
                            </div>
                            <div class="col-md-10 align-self-center">
                                <div class="form-group g-pos-rel g-mb-0">
                                    <select name="state" id="state" class="form-control" data-route="<?php echo e(route('core.city')); ?>">
                                        <option value=""><?php echo e(_i('Select...')); ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row g-mb-10">
                            <div class="col-md-2 align-self-center g-mb-5 g-mb-0--md">
                                <label class="g-mb-0" for="city">
                                    <?php echo e(_i('City')); ?>

                                </label>
                            </div>
                            <div class="col-md-10 align-self-center">
                                <div class="form-group g-pos-rel g-mb-0">
                                    <select name="city" id="city" class="form-control" >
                                        <option value=""><?php echo e(_i('Select...')); ?></option>
                                            <?php if($user->city): ?>
                                                <option value="<?php echo e($user->city); ?>" selected><?php echo e($user->city); ?></option>
                                            <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row g-mb-10">
                            <div class="col-md-2 align-self-center g-mb-5 g-mb-0--md">
                                <label class="g-mb-0" for="timezone">
                                    <?php echo e(_i('Timezone')); ?>

                                </label>
                            </div>
                            <div class="col-md-10 align-self-center">
                                <div class="form-group g-pos-rel g-mb-0">
                                    <select name="timezone" id="timezone" class="form-control">
                                        <?php $__currentLoopData = $timezones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $timezone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($timezone); ?>" <?php echo e($timezone == $user->timezone ? 'selected' : ''); ?>>
                                                <?php echo e($timezone); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row g-mb-10">
                            <div class="col-md-2 align-self-center g-mb-5 g-mb-0--md">
                                <label class="g-mb-0" for="phone">
                                    <?php echo e(_i('Phone')); ?>

                                </label>
                            </div>
                            <div class="col-md-10 align-self-center">
                                <div class="form-group g-pos-rel g-mb-0">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <select name="calling_code" class="form-control">
                                                <option value=""><?php echo e(_i('Select...')); ?></option>
                                                <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($country->calling_code); ?>" <?php echo e($country->calling_code == $user->calling_code ? 'selected' : ''); ?>>
                                                        <?php echo e($country->name); ?> (+<?php echo e($country->calling_code); ?>)
                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>

                                        <div class="col-md-8">
                                            <input id="phone" name="phone" class="form-control" type="text"
                                                   value="<?php echo e($user->phone); ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-mb-10">
                            <div class="col-md-2 align-self-center g-mb-5 g-mb-0--md">
                                <label class="g-mb-0" for="birth_date">
                                    <?php echo e(_i('Date of birth')); ?>

                                </label>
                            </div>
                            <div class="col-md-10 align-self-center">
                                <div class="form-group g-pos-rel g-mb-0">
                                    <input id="birth_date" name="birth_date" class="form-control datepicker" type="text"
                                           value="<?php echo e(!is_null($user->birth_date) ? $user->birth_date : ''); ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row g-mb-10">
                            <div class="col-md-2 align-self-center g-mb-5 g-mb-0--md">
                                <label class="g-mb-0" for="registration_date">
                                    <?php echo e(_i('Registration date')); ?>

                                </label>
                            </div>
                            <div class="col-md-10 align-self-center">
                                <div class="form-group g-pos-rel g-mb-0">
                                    <?php echo e($user->created); ?>

                                </div>
                            </div>
                        </div>
                        <div class="row g-mb-10">
                            <div class="col-md-2 align-self-center g-mb-5 g-mb-0--md">
                                <label class="g-mb-0" for="last_access">
                                    <?php echo e(_i('Last access')); ?>

                                </label>
                            </div>
                            <div class="col-md-10 align-self-center">
                                <div class="form-group g-pos-rel g-mb-0">
                                    <?php echo e($user->login); ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-15">
                <header class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                    <div class="media">
                        <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 g-mb-0">
                            <?php echo e(_i('Financial summary')); ?>

                        </h3>
                    </div>
                </header>
                <div class="card-block g-pa-15">
                    <div class="media u-header-dropdown-bordered-v2 g-brd-gray-light-v7 g-py-10">
                        <div class="d-flex align-self-center g-mr-12">
                            <span class="u-badge-v2--sm g-pos-stc g-transform-origin--top-left g-bg-teal"></span>
                        </div>
                        <div class="media-body align-self-center">
                            <?php echo e(_i('Total deposited')); ?>

                        </div>
                        <div class="d-flex text-right g-font-weight-900" id="main-balance">
                            <?php echo e($user->deposits); ?>

                        </div>
                    </div>
                    <div class="media u-header-dropdown-bordered-v2 g-brd-gray-light-v7 g-py-10">
                        <div class="d-flex align-self-center g-mr-12">
                            <span class="u-badge-v2--sm g-pos-stc g-transform-origin--top-left g-bg-indigo"></span>
                        </div>
                        <div class="media-body align-self-center">
                            <?php echo e(_i('Manual deposits')); ?>

                        </div>
                        <div class="d-flex text-right g-font-weight-900" id="main-balance">
                            <?php echo e($user->manual_deposits); ?>

                        </div>
                    </div>

                    <div class="media u-header-dropdown-bordered-v2 g-brd-gray-light-v7 g-py-10">
                        <div class="d-flex align-self-center g-mr-12">
                            <span class="u-badge-v2--sm g-pos-stc g-transform-origin--top-left g-bg-primary"></span>
                        </div>
                        <div class="media-body align-self-center">
                            <?php echo e(_i('Total withdrawn')); ?>

                        </div>
                        <div class="d-flex text-right g-font-weight-900">
                            <?php echo e($user->withdrawals); ?>

                        </div>
                    </div>

                    <div class="media u-header-dropdown-bordered-v2 g-brd-gray-light-v7 g-py-10">
                        <div class="d-flex align-self-center g-mr-12">
                            <span class="u-badge-v2--sm g-pos-stc g-transform-origin--top-left g-bg-darkred"></span>
                        </div>
                        <div class="media-body align-self-center">
                            <?php echo e(_i('Manual withdrawals')); ?>

                        </div>
                        <div class="d-flex text-right g-font-weight-900">
                            <?php echo e($user->manual_withdrawals); ?>

                        </div>
                    </div>
                    <div class="media u-header-dropdown-bordered-v2 g-brd-gray-light-v7 g-py-10">
                        <div class="d-flex align-self-center g-mr-12">
                            <span class="u-badge-v2--sm g-pos-stc g-transform-origin--top-left g-bg-blue"></span>
                        </div>
                        <div class="media-body align-self-center">
                            <?php echo e(_i('Total profit')); ?>

                        </div>
                        <div class="d-flex text-right g-font-weight-900">
                            <?php echo e($user->profit); ?>

                        </div>
                    </div>
                    <div class="media u-header-dropdown-bordered-v2 g-brd-gray-light-v7 g-py-10">
                        <div class="d-flex align-self-center g-mr-12">
                            <span class="u-badge-v2--sm g-pos-stc g-transform-origin--top-left g-bg-orange"></span>
                        </div>
                        <div class="media-body align-self-center">
                            <?php echo e(_i('Total bonus')); ?>

                        </div>
                        <div class="d-flex text-right g-font-weight-900">
                            <?php echo e($user->bonus); ?>

                        </div>
                    </div>
                </div>
            </div>
            <?php if($store): ?>
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-15">
                    <header class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-py-10">
                        <div class="media">
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 g-mb-0">
                                <?php echo e(_i('Store')); ?>

                            </h3>
                        </div>
                    </header>
                    <div class="card-block g-px-15 g-py-5">

                        <div class="media u-header-dropdown-bordered-v2 g-brd-gray-light-v7 g-py-10">
                            <div class="d-flex align-self-center g-mr-12">
                                <span class="u-badge-v2--sm g-pos-stc g-transform-origin--top-left g-bg-purple"></span>
                            </div>
                            <div class="media-body align-self-center">
                                <?php echo e(_i('Points')); ?>

                            </div>
                            <div class="d-flex text-right g-font-size-24 g-font-weight-900" id="points-balance">
                                <?php echo e($points); ?>

                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-15">
                <header class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">

                    <div class="media">
                        <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 g-mb-0">
                            <?php echo e(_i('Payment methods')); ?>

                        </h3>
                    </div>
                </header>
                <div class="card-block g-pa-15">
                    <?php if($user_accounts): ?>
                        <?php $__currentLoopData = $user_accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="media-md align-items-center g-parent g-brd-around g-brd-gray-light-v2 g-rounded-4 g-px-20 g-py-5 g-mb-3">
                                <div class="d-flex-md text-center g-mb-20 g-mb-0--md">
                                    <div class="d-inline-block u-icon-v3 u-icon-size--lg g-bg-gray-light-v3 g-font-size-24 g-color-secondary rounded-circle">
                                        <i class="g-font-size-0">
                                            <?php echo $account->logo; ?>

                                        </i>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center g-font-size-12 g-font-size-default--md g-mb-10 g-mb-0--md g-mx-10--md">
                                    <div>
                                        <?php echo $account->info; ?>

                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <label class="js-check g-pos-rel d-block g-mb-20">
                            <div class="media-md align-items-center g-parent g-bg-gray-light-v8--sibling-checked g-brd-around g-brd-gray-light-v7 g-rounded-4 g-px-10 g-py-15">
                                <div class="d-flex align-items-center g-font-size-12 g-font-size-default--md g-mb-10 g-mb-0--md g-mx-10--md">
                                    <div>
                                        <?php echo e(_i('This user does not have payment methods')); ?>

                                    </div>
                                </div>
                            </div>
                        </label>
                    <?php endif; ?>
                </div>
            </div>
            <?php if(isset($wallets_bonuses)): ?>
                <?php if(count($wallets_bonuses) > 0): ?>
                    <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-15">
                        <header class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-py-10">
                            <div class="media">
                                <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 g-mb-0">
                                    <?php echo e(_i('Bonus')); ?> (<?php echo e(session('currency')); ?>)
                                </h3>
                            </div>
                        </header>
                        <?php $__currentLoopData = $wallets_bonuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bonusWallet): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="card-block g-px-15 g-py-5">
                                <div class="media u-header-dropdown-bordered-v2 g-brd-gray-light-v7 g-py-10">
                                    <div class="d-flex align-self-center g-mr-12">
                                        <span class="u-badge-v2--sm g-pos-stc g-transform-origin--top-left g-bg-darkblue-v2"></span>
                                    </div>
                                    <div class="media-body align-self-center">
                                        <?php echo e($bonusWallet->provider_type); ?>

                                    </div>
                                    <div class="d-flex text-right g-font-size-18 g-font-weight-900">
                                        <?php echo e($bonusWallet->balance); ?>

                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <div class="col-md-12">
            <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                <header class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                    <div class="media">
                        <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 g-mb-0">
                            <?php echo e(_i('Wallet transactions')); ?>

                        </h3>
                        <div class="media-body d-flex justify-content-end g-mb-10" id="table-buttons-wallet">

                        </div>

                        <div class="justify-content-end g-ml-10" style="padding-left: 10px">
                            <button class="btn u-btn-3d u-btn-primary" type="button" id="update-wallet"
                                    data-loading-text="<i class='fa fa-spin fa-refresh g-color-white'></i>">
                                <i class="hs-admin-reload g-color-white"></i>
                            </button>
                        </div>
                    </div>
                </header>
                <div class="card-block g-pa-15">
                    <div class="table-responsive">
                        <input type="hidden" name="wallet" id="wallet" value="<?php echo e($wallet->id); ?>">
                        <table class="table table-bordered w-100" id="wallet-table"
                               data-route="<?php echo e(route('wallets.transactions')); ?>">
                            <thead>
                            <tr>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Date')); ?>

                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('ID')); ?>

                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Platform')); ?>

                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Description')); ?>

                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Debit')); ?>

                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Credit')); ?>

                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Balance')); ?>

                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-12">
            <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                <header class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                    <div class="media">
                        <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 g-mb-0">
                            <?php echo e(_i('Deposits and withdrawals')); ?>

                        </h3>
                        <div class="media-body d-flex justify-content-end g-mb-10" id="deposit-withdrawals-table-buttons">

                        </div>
                        <div class="justify-content-end g-ml-10" style="padding-left: 10px">
                            <button class="btn u-btn-3d u-btn-primary" type="button" id="update-payments"
                                    data-loading-text="<i class='fa fa-spin fa-refresh g-color-white'></i>">
                                <i class="hs-admin-reload g-color-white"></i>
                            </button>
                        </div>
                    </div>
                </header>
                <div class="card-block g-pa-15">
                    <div class="table-responsive">
                        <input type="hidden" name="currency" id="currency" value="<?php echo e($wallet->currency_iso); ?>">
                        <input type="hidden" name="user_id" id="user_id" value="<?php echo e($user->id); ?>">
                        <table class="table table-bordered w-100" id="payments-transactions-table"
                               data-route="<?php echo e(route('transactions.user')); ?>">
                            <thead>
                            <tr>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Date')); ?>

                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('ID')); ?>

                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Platform')); ?>

                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Description')); ?>

                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Debit')); ?>

                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Credit')); ?>

                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Status')); ?>

                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                <header class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                    <div class="media">
                        <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 g-mb-0">
                            <?php echo e(_i('Profit')); ?>

                        </h3>
                        <div class="media-body d-flex justify-content-end g-mb-10" id="products-total-date-table-buttons">

                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="text" id="daterange" class="form-control daterange g-pr-80 g-pl-15 g-py-9" autocomplete="off">
                                <input type="hidden" id="start_date" name="start_date">
                                <input type="hidden" id="end_date" name="end_date">
                            </div>
                        </div>
                        <div class="justify-content-end g-ml-10" style="padding-left: 10px">
                            <button class="btn u-btn-3d u-btn-primary" type="button" id="update-products-total-date"
                                    data-loading-text="<i class='fa fa-spin fa-refresh g-color-white'></i>">
                                <i class="hs-admin-reload g-color-white"></i>
                            </button>
                        </div>
                    </div>
                </header>
                <div class="card-block g-pa-15">
                    <div class="table-responsive">
                        <table class="table table-bordered table-responsive-sm w-100" id="products-users-totals-date-table"
                               data-route="<?php echo e(route('users.products-users-totals-data', [$user->id])); ?>">
                            <thead>
                            <tr>
                                <th colspan="2" class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none text-center">
                                    <?php echo e(_i('Provider')); ?>

                                </th>
                                <th rowspan="2" class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Currency')); ?>

                                </th>
                                <th rowspan="2" class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none text-center">
                                    <?php echo e(_i('Bets')); ?>

                                </th>
                                <th rowspan="2" class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Played')); ?>

                                </th>
                                <th rowspan="2" class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Won')); ?>

                                </th>
                                <th rowspan="2" class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Profit')); ?>

                                </th>
                                <th rowspan="2" class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('RTP')); ?>

                                </th>
                                <th rowspan="2" class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Hold')); ?>

                                </th>
                            </tr>
                            <tr>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Name')); ?>

                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Type')); ?>

                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                <header class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                    <div class="media">
                        <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 g-mb-0">
                            <?php echo e(_i('Connections')); ?>

                        </h3>

                        <div class="media-body d-flex justify-content-end g-mb-10" id="ip-table-buttons">

                        </div>
                        <div class="justify-content-end g-ml-10" style="padding-left: 10px">
                            <input type="hidden" name="user_id" id="user_id" value="<?php echo e($user->id); ?>">
                            <button class="btn u-btn-3d u-btn-primary" type="button" id="update-ip"
                                    data-loading-text="<i class='fa fa-spin fa-refresh g-color-white'></i>">
                                <i class="hs-admin-reload g-color-white"></i>
                            </button>
                        </div>
                    </div>
                </header>
                <div class="card-block g-pa-15">
                    <div class="table-responsive">
                        <input type="hidden" name="user_id" id="user_id" value="<?php echo e($user->id); ?>">
                        <table class="table table-bordered w-100" id="ip-table"
                               data-route="<?php echo e(route('users.users-ips-data')); ?>">
                            <thead>
                            <tr>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('IP')); ?>

                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Quantity')); ?>

                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [\Dotworkers\Security\Enums\Permissions::$users_audits])): ?>
            <div class="col-md-12">
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                    <header class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 g-mb-0">
                                <?php echo e(_i('Audits')); ?>

                            </h3>

                            <div class="media-body d-flex justify-content-end g-mb-10" id="audit-table-buttons">

                            </div>
                            <div class="justify-content-end g-ml-10" style="padding-left: 10px">
                                <input type="hidden" name="user_id" id="user_id" value="<?php echo e($user->id); ?>">
                                <button class="btn u-btn-3d u-btn-primary" type="button" id="update-audit"
                                        data-loading-text="<i class='fa fa-spin fa-refresh g-color-white'></i>">
                                    <i class="hs-admin-reload g-color-white"></i>
                                </button>
                            </div>
                        </div>
                    </header>
                    <div class="card-block g-pa-15">
                        <div class="table-responsive">
                            <input type="hidden" name="user_id" id="user_id" value="<?php echo e($user->id); ?>">
                            <table class="table table-bordered w-100" id="audit-table"
                                   data-route="<?php echo e(route('users.users-audit-data')); ?>">
                                <thead>
                                <tr>
                                    <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                        <?php echo e(_i('Details')); ?>

                                    </th>
                                    <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                        <?php echo e(_i('Type')); ?>

                                    </th>
                                    <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                        <?php echo e(_i('Date')); ?>

                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if($store): ?>
            <div class="col-md-12">
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                    <header class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 g-mb-0">
                                <?php echo e(_i('Store transactions history')); ?>

                            </h3>
                            <div class="media-body d-flex justify-content-end g-mb-10" id="store-transactions-table-buttons">

                            </div>
                            <div class="justify-content-end g-ml-10" style="padding-left: 10px">
                                <button class="btn u-btn-3d u-btn-primary" type="button" id="store-transactions-update"
                                        data-loading-text="<i class='fa fa-spin fa-refresh g-color-white'></i>">
                                    <i class="hs-admin-reload g-color-white"></i>
                                </button>
                            </div>
                        </div>
                    </header>
                    <div class="card-block g-pa-15">
                        <div class="table-responsive">
                            <input type="hidden" name="user_id" id="user_id" value="<?php echo e($user->id); ?>">
                            <table class="table table-bordered w-100" id="store-transactions-table"
                                   data-route="<?php echo e(route('store.transactions')); ?>">
                                <thead>
                                <tr>
                                    <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                        <?php echo e(_i('Date')); ?>

                                    </th>
                                    <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                        <?php echo e(_i('Platform')); ?>

                                    </th>
                                    <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                        <?php echo e(_i('Debit')); ?>

                                    </th>
                                    <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                        <?php echo e(_i('Credit')); ?>

                                    </th>
                                    <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                        <?php echo e(_i('Balance')); ?>

                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                    <header class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 g-mb-0">
                                <?php echo e(_i('Store claims history')); ?>

                            </h3>
                            <div class="media-body d-flex justify-content-end g-mb-10" id="store-claims-table-buttons">

                            </div>
                            <div class="justify-content-end g-ml-10" style="padding-left: 10px">
                                <button class="btn u-btn-3d u-btn-primary" type="button" id="store-claims-update"
                                        data-loading-text="<i class='fa fa-spin fa-refresh g-color-white'></i>">
                                    <i class="hs-admin-reload g-color-white"></i>
                                </button>
                            </div>
                        </div>
                    </header>
                    <div class="card-block g-pa-15">
                        <div class="table-responsive">
                            <input type="hidden" name="user_id" id="user_id" value="<?php echo e($user->id); ?>">
                            <table class="table table-bordered w-100" id="store-claims-table"
                                   data-route="<?php echo e(route('store.rewards.claims')); ?>">
                                <thead>
                                <tr>
                                    <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                        <?php echo e(_i('Date')); ?>

                                    </th>
                                    <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                        <?php echo e(_i('Reward')); ?>

                                    </th>
                                    <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                        <?php echo e(_i('Prize')); ?>

                                    </th>
                                    <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                        <?php echo e(_i('Points')); ?>

                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <?php if($document_verification): ?>
            <div class="col-md-12">
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                    <header class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 g-mb-0">
                                <?php echo e(_i('Verification of documents')); ?>

                            </h3>
                            <div class="media-body d-flex justify-content-end g-mb-10" id="verification-document-table-buttons">

                            </div>
                            <div class="justify-content-end g-ml-10" style="padding-left: 10px">
                                <button class="btn u-btn-3d u-btn-primary" type="button" id="verification-document-update"
                                        data-loading-text="<i class='fa fa-spin fa-refresh g-color-white'></i>">
                                    <i class="hs-admin-reload g-color-white"></i>
                                </button>
                            </div>
                        </div>
                    </header>
                    <div class="card-block g-pa-15">
                        <div class="table-responsive">
                            <input type="hidden" name="user_id" id="user_id" value="<?php echo e($user->id); ?>">
                            <table class="table table-bordered w-100" id="verification-document-table"
                                   data-route="<?php echo e(route('store.documents-user')); ?>">
                                <thead>
                                <tr>
                                    <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                        <?php echo e(_i('Date')); ?>

                                    </th>
                                    <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                        <?php echo e(_i('Document type')); ?>

                                    </th>
                                    <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                        <?php echo e(_i('Status')); ?>

                                    </th>
                                    <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none text-right">
                                        <?php echo e(_i('Actions')); ?>

                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <?php echo $__env->make('back.users.modals.reset-password', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('back.users.modals.send-message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('back.users.modals.send-email', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('back.users.modals.reset-password', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


    <?php echo $__env->make('back.users.modals.edit-account', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [\Dotworkers\Security\Enums\Permissions::$unlock_balance])): ?>
        <?php echo $__env->make('back.users.modals.unlock-balance', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [\Dotworkers\Security\Enums\Permissions::$manual_adjustments])): ?>
        <?php echo $__env->make('back.users.modals.manual-adjustment', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [\Dotworkers\Security\Enums\Permissions::$manual_transactions])): ?>
        <?php echo $__env->make('back.users.modals.manual-transaction', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [\Dotworkers\Security\Enums\Permissions::$bonus_transactions])): ?>
        <?php echo $__env->make('back.users.modals.bonus-transaction', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>
    <?php if($store): ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [\Dotworkers\Security\Enums\Permissions::$points_transactions])): ?>
            <?php echo $__env->make('back.users.modals.points-transactions', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>
    <?php endif; ?>
    <?php if($document_verification): ?>
        <?php echo $__env->make('back.users.modals.watch-document', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php echo $__env->make('back.users.modals.document-rejected', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>
    <?php if($bonus): ?>





    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        $(function () {
            let users = new Users();
            let segments = new Segments();
            let bonussystems = new BonusSystem();
            let core = new Core();
            // let security = new Security();
            users.details();
            users.detailsModals();
            users.walletTransactions();
            users.walletTransactionsHistoric();
            users.paymentsTransactions();
            users.productsUsersTotalsDate();
            users.usersIps();
            users.usersAudit();
            users.storeClaims();
            users.storeTransactions();
            users.updateUserAccounts();
            segments.addUser();
            segments.allUser();
            core.states('<?php echo e($user->state); ?>');
            core.city('<?php echo e($user->city); ?>');
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [\Dotworkers\Security\Enums\Permissions::$unlock_balance])): ?>
            users.unlockBalance();
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [\Dotworkers\Security\Enums\Permissions::$update_users_status])): ?>
            users.changeStatus();
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [\Dotworkers\Security\Enums\Permissions::$update_users_data])): ?>
            users.updateProfile();
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [\Dotworkers\Security\Enums\Permissions::$reset_users_password])): ?>
            users.resetPassword();
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [\Dotworkers\Security\Enums\Permissions::$manual_transactions])): ?>
            users.manualTransactions();
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [\Dotworkers\Security\Enums\Permissions::$manual_adjustments])): ?>
            users.manualAdjustments();
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [\Dotworkers\Security\Enums\Permissions::$bonus_transactions])): ?>
            users.bonusTransactions();
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [\Dotworkers\Security\Enums\Permissions::$user_login])): ?>
            users.loginUser();
            <?php endif; ?>
            <?php if($store): ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [\Dotworkers\Security\Enums\Permissions::$points_transactions])): ?>
            users.pointsTransactions();
            <?php endif; ?>
            <?php endif; ?>
            <?php if($document_verification): ?>
            users.documentsByUser();
            <?php endif; ?>
            <?php if($bonus): ?>
            // bonussystems.addUser();
            // bonussystems.removeUser();



            <?php endif; ?>
            users.disableAccount();
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('back.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
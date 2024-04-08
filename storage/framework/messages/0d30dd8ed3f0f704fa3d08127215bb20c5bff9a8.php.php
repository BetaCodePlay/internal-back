

<?php $__env->startSection('content'); ?>
    <form action="<?php echo e(route('store.actions.update')); ?>" id="actions-configurations-form" method="post" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-12">
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                    <header
                            class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                <?php echo e($title); ?>

                            </h3>
                        </div>
                        <div class="media-body d-flex justify-content-end">
                            <a href="<?php echo e(route('store.actions.index')); ?>" class="btn u-btn-3d u-btn-primary float-right">
                                <i class="hs-admin-layout-list-thumb"></i>
                                <?php echo e(_i('Go to list')); ?>

                            </a>
                        </div>
                    </header>
                    <div class="card-block g-pa-15">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="currency"><?php echo e(_i('Currency')); ?></label>
                                    <select name="currency" id="currency" class="form-control" data-route="<?php echo e(route('store.actions.type-providers')); ?>">
                                        <?php $__currentLoopData = $whitelabel_currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($currency->iso); ?>" <?php echo e($currency->iso == $action->currency_iso ? 'selected' : ''); ?>>
                                                <?php echo e($currency->iso == 'VEF' ? $free_currency->currency_name : $currency->iso . " ({$currency->name})"); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="points_desk"><?php echo e(_i('Points desktop')); ?></label>
                                    <input type="text" name="points_desk" id="points_desk" class="form-control" value="<?php echo e($action->points); ?>" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-6" id="desktop">
                                <div class="form-group">
                                    <label for="amount_desk"><?php echo e(_i('Amount desktop')); ?></label>
                                    <input type="text" name="amount_desk" id="amount_desk" class="form-control" value="<?php echo e($action->amount); ?>" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="points_mobile"><?php echo e(_i('Points mobile')); ?></label>
                                    <input type="text" name="points_mobile" id="points_mobile" class="form-control" value="<?php echo e($action->mobile_points); ?>" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-6" id="mobile">
                                <div class="form-group">
                                    <label for="amount_mobile"><?php echo e(_i('Amount mobile')); ?></label>
                                    <input type="text" name="amount_mobile" id="amount_mobile" class="form-control" value="<?php echo e($action->mobile_amount); ?>" autocomplete="off">
                                </div>
                            </div>
                            <?php if(empty($action->start_date)): ?>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="start_date"><?php echo e(_i('Start date')); ?></label>
                                        <input type="text" name="start_date" id="start_date" class="form-control datetimepicker" autocomplete="off">
                                    </div>
                                </div>
                            <?php else: ?>
                                <input type="hidden" name="start_date_old" value="<?php echo e($action->start_date); ?>">
                            <?php endif; ?>
                            <?php if(empty($action->end_date)): ?>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="end_date"><?php echo e(_i('End date')); ?></label>
                                        <input type="text" name="end_date" id="end_date" class="form-control datetimepicker" autocomplete="off">
                                    </div>
                                </div>
                            <?php else: ?>
                                <input type="hidden" name="end_date_old" value="<?php echo e($action->end_date); ?>">
                            <?php endif; ?>
                            <?php if(empty($action->provider_type_id)): ?>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="provider_type"><?php echo e(_i('Provider type')); ?></label>
                                        <select name="provider_type" id="provider_type" class="form-control" data-route="<?php echo e(route('store.actions.exclude-providers')); ?>">
                                            <option value=""><?php echo e(_i('Select...')); ?></option>
                                        </select>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="hidden" name="provider_type" value="<?php echo e($action->provider_type_id); ?>">
                                        <label for="provider_type"><?php echo e(_i('Provider type')); ?></label>
                                        <select name="provider_type" id="provider_type" class="form-control" disabled>
                                            <option value="<?php echo e($action->provider_type_id); ?>" selected><?php echo e($action->provider_type_name); ?></option>
                                        </select>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if(!is_null($action->exclude_providers) && !in_array(null, $action->exclude_providers)): ?>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exclude_provider"><?php echo e(_i('Exclude provider')); ?></label>
                                        <select name="exclude_provider[]" id="exclude_provider" class="form-control" multiple>
                                            <option value=""><?php echo e(_i('Select...')); ?></option>
                                            <?php $__currentLoopData = $providers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $provider): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($provider->id); ?>" <?php echo e(in_array($provider->id,  $action->exclude_providers) ? 'selected' : ''); ?>>
                                                    <?php echo e(\Dotworkers\Configurations\Enums\Providers::getName($provider->id)); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exclude_provider"><?php echo e(_i('Exclude provider')); ?></label>
                                        <select name="exclude_provider[]" id="exclude_provider" class="form-control" multiple>
                                            <option value=""><?php echo e(_i('Select...')); ?></option>
                                        </select>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="hidden" name="id" id="action" value="<?php echo e($action->action_id); ?>">
                                    <input type="hidden" name="currency_old" value="<?php echo e($action->currency_iso); ?>">
                                    <button type="submit" class="btn u-btn-3d u-btn-primary" id="update"
                                            data-loading-text="<i class='fa fa-spin fa-spinner'></i> <?php echo e(_i('Uploading...')); ?>">
                                        <i class="hs-admin-reload"></i>
                                        <?php echo e(_i('Update')); ?>

                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        $(function () {
            let store = new Store();
            store.updateActionConfiguration();
            store.typeProviders()
            store.excludeProvider()
            store.actionsFormType();
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('back.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
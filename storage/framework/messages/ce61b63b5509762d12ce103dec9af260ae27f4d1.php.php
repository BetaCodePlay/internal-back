

<?php $__env->startSection('content'); ?>
    <form action="<?php echo e(route('providers-limits.update')); ?>" id="providers-limits-form" method="post">
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
                    </header>
                    <div class="card-block g-pa-15">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="whitelabel"><?php echo e(_i('Whitelabel')); ?></label>
                                    <select name="whitelabel" id="whitelabel" class="form-control">
                                        <option value=""><?php echo e(_i('Select...')); ?></option>
                                        <?php $__currentLoopData = $whitelabels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $whitelabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($whitelabel->id); ?>" <?php echo e($whitelabel->id == $limit->whitelabel ? 'selected' : ''); ?>>
                                                <?php echo e($whitelabel->description); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="currency"><?php echo e(_i('Currency')); ?></label>
                                    <select name="currency" id="currency" class="form-control">
                                        <option value=""><?php echo e(_i('Select...')); ?></option>
                                        <?php $__currentLoopData = $whitelabel_currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($currency->iso); ?>" <?php echo e($currency->iso == session('currency') ? 'selected' : ''); ?>>
                                                <?php echo e($currency->iso == 'VEF' ? $free_currency->currency_name : $currency->iso . " ({$currency->name})"); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="min_bet"><?php echo e(_i('Min bet')); ?></label>
                                    <input type="number" name="min_bet" id="min_bet" class="form-control" value="<?php echo e(limit->min_bet); ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="max_bet"><?php echo e(_i('Max bet')); ?></label>
                                    <input type="number" name="max_bet" id="max_bet" class="form-control" value="<?php echo e(limit->max_bet); ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="max_selections"><?php echo e(_i('Max selections')); ?></label>
                                    <input type="number" name="max_selections" id="max_selections" class="form-control" value="<?php echo e(limit->max_selections); ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="max_selections_not_favorites"><?php echo e(_i('Max selections not favorites')); ?></label>
                                    <input type="number" name="max_selections_not_favorites" id="max_selections_not_favorites" class="form-control" value="<?php echo e(limit->max_selections_not_favorites); ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="straight_bet_limit"><?php echo e(_i('Straight bet limit')); ?></label>
                                    <input type="number" name="straight_bet_limit" id="straight_bet_limit" class="form-control" value="<?php echo e(limit->straight_bet_limit); ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="parlay_bet_limit"><?php echo e(_i('Parlay bet limit')); ?></label>
                                    <input type="number" name="parlay_bet_limit" id="parlay_bet_limit" class="form-control" value="<?php echo e(limit->parlay_bet_limit); ?>">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="hidden" name="provider" value="<?php echo e($provider); ?>">
                                    <button type="button" class="btn u-btn-3d u-btn-primary" id="store"
                                            data-loading-text="<i class='fa fa-spin fa-spinner'></i> <?php echo e(_i('Saving...')); ?>">
                                        <i class="hs-admin-save"></i>
                                        <?php echo e(_i('Save')); ?>

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
            let limits = new ProductsLimits();
            limits.store();
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('back.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
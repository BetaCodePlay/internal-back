

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                <header class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                    <div class="media">
                        <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                            <?php echo e($title); ?>

                        </h3>
                    </div>
                </header>
                <div class="card-block g-pa-15">
                    <form action="<?php echo e(route('dot-suite.credentials.store-credentials')); ?>" id="credentials-form" method="post">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="client"><?php echo e(_i('Whitelabel')); ?></label>
                                    <select name="client" id="client" class="form-control">
                                        <option value=""><?php echo e(_i('Select...')); ?></option>
                                        <?php $__currentLoopData = $whitelabels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $whitelabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($whitelabel->id); ?>">
                                                <?php echo e($whitelabel->description); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="currencies"><?php echo e(_i('Currency')); ?></label>
                                    <select name="currencies[]" id="currencies" class="form-control" data-placeholder="<?php echo e(_i('Select...')); ?>" multiple>
                                        <?php $__currentLoopData = $currency_client; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($currency->iso); ?>">
                                                <?php echo e($currency->iso == 'VEF' ? $free_currency->currency_name : $currency->iso . " ({$currency->name})"); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="providers_type"><?php echo e(_i('Provider type')); ?></label>
                                    <select name="providers_type"  id="providers_type" class="form-control" data-route="<?php echo e(route('dot-suite.credentials.providers')); ?>">
                                        <option value=""><?php echo e(_i('Select...')); ?></option>
                                        <?php $__currentLoopData = $providers_types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($type->id); ?>">
                                                <?php echo e($type->name); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="providers"><?php echo e(_i('Providers')); ?></label>
                                    <select name="providers"  id="providers" class="form-control">
                                        <option value=""><?php echo e(_i('Select...')); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="percentage"><?php echo e(_i('Percentage')); ?></label>
                                        <input type="text" name="percentage" id="percentage" class="form-control">
                                    </div>
                            </div>
                            <div class="col-md-3">
                                <div class="grant-secret d-none">
                                    <div class="form-group">
                                        <label for="grant_secret"><?php echo e(_i('Grant secret')); ?></label>
                                        <input type="text" name="grant_secret" id="grant_secret" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="button" class="btn u-btn-3d u-btn-primary" id="save"
                                            data-loading-text="<i class='fa fa-spin fa-spinner'></i> <?php echo e(_i('Saving...')); ?>">
                                        <i class="hs-admin-save"></i>
                                        <?php echo e(_i('Save')); ?>

                                    </button>
                                    <button type="button" class="btn u-btn-3d u-btn-bluegray" id="clear">
                                        <i class="hs-admin-close"></i>
                                        <?php echo e(_i('Clear')); ?>

                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        $(function () {
            let dotSuite = new DotSuite();
            dotSuite.storeCredentials();
            dotSuite.providerTypes();
            dotSuite.credentialsData();
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('back.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
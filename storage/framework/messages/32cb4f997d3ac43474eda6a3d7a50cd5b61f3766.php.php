

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
                    <form action="<?php echo e(route('configurations.credentials.store.credentials')); ?>" id="save-form" method="post">
                        <div class="row">
                            <div class="col-6">
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
                            <div class="col-6">
                            <div class="form-group">
                                <label for="currency"><?php echo e(_i('Currency')); ?></label>
                                <select name="currency" id='currency' class="form-control" data-route="<?php echo e(route('configurations.credentials.type-providers')); ?>">
                                    <option value=""><?php echo e(_i('Select...')); ?></option>
                                        <?php $__currentLoopData = $whitelabel_currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($currency->iso); ?>">
                                                <?php echo e($currency->iso == 'VEF' ? $free_currency->currency_name : $currency->iso . " ({$currency->name})"); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="provider_type"><?php echo e(_i('Provider type')); ?></label>
                                    <select name="provider_type" id="provider_type" data-route="<?php echo e(route('configurations.credentials.exclude-providers')); ?>" class="form-control">
                                        <option value=""><?php echo e(_i('Select...')); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="exclude_providers"><?php echo e(_i('Exclude providers')); ?></label>
                                    <select name="exclude_providers" id="exclude_providers" class="form-control">
                                        <option value=""><?php echo e(_i('Select...')); ?></option>
                                    </select>
                                </div>
                            </div> 
                            <div class="col-6">
                                    <div class="form-group">
                                        <label for="percentage"><?php echo e(_i('Percentage')); ?></label>
                                        <input type="text" name="percentage" id="percentage" class="form-control">
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
            let configurations = new Configurations();
            configurations.providerTypes();
            configurations.providers();
            configurations.save();
            $(document).on('click', '.update_checkbox', function () {
                if (!$(this).hasClass('active')) {
                    $.post('<?php echo e(route('configurations.credentials.status')); ?>', {client_id: $(this).data('id'),  name: 'status', value: true}, function () {});
                } else {
                    $.post('<?php echo e(route('configurations.credentials.status')); ?>', {client_id: $(this).data('id'),  name: 'status', value: false}, function () {});
                }

                $(this).toggleClass('active');
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('back.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
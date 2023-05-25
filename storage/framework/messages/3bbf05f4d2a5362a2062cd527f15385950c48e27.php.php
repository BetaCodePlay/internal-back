

<?php $__env->startSection('content'); ?>
    <div class="noty_bar noty_type__warning noty_theme__unify--v1--dark g-mb-25">
        <div class="noty_body">
            <div class="g-mr-20">
                <div class="noty_body__icon">
                    <i class="hs-admin-info"></i>
                </div>
            </div>
            <div>
                <p>
                    <?php echo e(_i('You do not have credentials for $%, request your credentials.', [$provider_name])); ?>

                </p>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('back.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
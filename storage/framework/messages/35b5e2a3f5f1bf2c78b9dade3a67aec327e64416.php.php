<?php $__env->startSection('content'); ?>
        <div class="noty_bar noty_type__warning noty_theme__unify--v1 g-mb-25">
            <div class="noty_body">
                <div class="g-mr-20">
                    <div class="noty_body__icon">
                        <i class="hs-admin-alert"></i>
                    </div>
                </div>
                <div>
                    <?php echo e(_i('This report makes closings and calculations every hour')); ?>

                </div>
            </div>
        </div>
    <?php echo $__env->make('back.layout.litepicker', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
        <header class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
            <div class="media">
                <h3 class="d-flex align-self-center text-uppercase g-font-size-12 g-font-size-default--md g-color-black mb-0">
                    <?php echo e($title); ?>

                </h3>
            </div>
        </header>
        <div class="card-block g-pa-15">
            <div class="media">
                <div class="media-body d-flex justify-content-end g-mb-10" id="table-buttons">

                </div>
            </div>
            <div class="table-responsive" id="total-financial-table" data-route="<?php echo e(route('agents.reports.financial-state-summary-bonus-data')); ?>">

            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        $(function () {
            let agents = new Agents();
            agents.totalFinancial(<?php echo e($user); ?>);
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('back.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
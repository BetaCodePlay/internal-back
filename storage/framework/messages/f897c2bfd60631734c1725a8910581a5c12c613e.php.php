

<?php $__env->startSection('content'); ?>
    <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
        <header
            class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
            <div class="media">
                <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                    <?php echo e($title); ?>

                </h3>
                <div class="media-body d-flex justify-content-end">
                    <a href="<?php echo e(route('section-modals.create')); ?>" class="btn u-btn-3d u-btn-primary float-right">
                        <i class="hs-admin-upload"></i>
                        <?php echo e(_i('Upload')); ?>

                    </a>
                </div>
            </div>
        </header>
        <div class="card-block g-pa-15">
            <div class="media">
                <div class="media-body d-flex justify-content-end g-mb-10" id="table-buttons">

                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered w-100" id="modals-table" data-route="<?php echo e(route('section-modals.all')); ?>">
                    <thead>
                    <tr>
                        <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                            <?php echo e(_i('Image')); ?>

                        </th>
                        <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                            <?php echo e(_i('Menu')); ?>

                        </th>
                        <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                            <?php echo e(_i('Show one time')); ?>

                        </th>
                        <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                            <?php echo e(_i('Show in scroll')); ?>

                        </th>
                        <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                            <?php echo e(_i('Language')); ?>

                        </th>
                        <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                            <?php echo e(_i('Currency')); ?>

                        </th>
                        <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                            <?php echo e(_i('Status')); ?>

                        </th>
                        <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                            <?php echo e(_i('Actions')); ?>

                        </th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        $(function () {
            let modals = new SectionModals();
            modals.all();
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('back.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
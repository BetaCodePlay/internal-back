

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-xl-12">
            <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                <header
                    class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                    <div class="media">
                        <h3 class="d-flex align-self-center text-uppercase g-font-size-12 g-font-size-default--md g-color-black mb-0">
                            <?php echo e($title); ?>

                        </h3>
                    </div>
                </header>
                <div class="card-block g-pa-15 g-pb-5">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="start_date"><?php echo e(_i('Birthday date')); ?></label>
                                <input type="text" name="start_date" id="start_date" class="form-control datepicker">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <button type="button" class="btn u-btn-3d u-btn-primary" id="search"
                                        data-loading-text="<i class='fa fa-spin fa-spinner'></i> <?php echo e(_i('Searching...')); ?>">
                                    <i class="hs-admin-search"></i>
                                    <?php echo e(_i('Search')); ?>

                                </button>
                                <button type="button" class="btn u-btn-3d u-btn-bluegray" id="clear">
                                    <i class="hs-admin-close"></i>
                                    <?php echo e(_i('Clear')); ?>

                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-block g-pa-15">
                    <div class="media">
                        <div class="media-body d-flex justify-content-end g-mb-10" id="table-buttons">

                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered w-100" id="users-birthdays-table"
                               data-route="<?php echo e(route('reports.users.users-birthdays-data')); ?>">
                            <thead>
                            <tr>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('ID')); ?>

                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Username')); ?>

                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Email')); ?>

                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Phone')); ?>

                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Birthday')); ?>

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
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        $(function () {
            let reports = new Reports();
            reports.usersBirthdays();
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('back.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
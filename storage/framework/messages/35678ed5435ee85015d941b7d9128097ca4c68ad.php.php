

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                <header
                    class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                    <div class="media">
                        <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                            <?php echo e(_i('Filter data')); ?>

                        </h3>
                    </div>
                </header>
                <div class="card-block g-pa-15">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tester"><?php echo e(_i('Date range')); ?></label>
                                <input type="text" id="daterange" class="form-control daterange g-pr-80 g-pl-15 g-py-9" autocomplete="off">
                                <input type="hidden" id="start_date" name="start_date">
                                <input type="hidden" id="end_date" name="end_date">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                            <label for="month"><?php echo e(_i('Time')); ?></label>
                                <select name="month" id="month" class="form-control">
                                    <option value=""> <?php echo e(_i('All options')); ?> </option>
                                    <option value="31"> <?php echo e(_i('1 month')); ?> </option>
                                    <option value="91"> <?php echo e(_i('3 month')); ?> </option>
                                    <option value="0"> <?php echo e(_i('Permanent')); ?> </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <button type="button" class="btn u-btn-3d u-btn-primary" id="update"
                                        data-loading-text="<i class='fa fa-spin fa-spinner'></i> <?php echo e(_i('Consulting...')); ?>">
                                    <i class="hs-admin-search"></i>
                                    <?php echo e(_i('Consult data')); ?>

                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
                <div class="card-block g-pa-15">
                    <div class="media">
                        <div class="media-body d-flex justify-content-end g-mb-10" id="table-buttons">

                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered w-100" id="autolocked-users-table"
                               data-route="<?php echo e(route('users.autolocked-users-data')); ?>">
                            <thead>
                            <tr>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('ID')); ?>

                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Username')); ?>

                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Currency')); ?>

                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Lock date')); ?>

                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Auto unlock date')); ?>

                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Lock time / Months')); ?>

                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Number of locks')); ?>

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
            let users = new Users();
            users.autoLockedUsers();
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('back.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
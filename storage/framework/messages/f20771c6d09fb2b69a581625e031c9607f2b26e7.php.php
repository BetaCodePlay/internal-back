

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col">
            <div class="noty_bar noty_type__warning noty_theme__unify--v1--dark g-mb-25">
                <div class="noty_body">
                    <div class="g-mr-20">
                        <div class="noty_body__icon">
                            <i class="hs-admin-info"></i>
                        </div>
                    </div>
                    <div>
                        <p>
                            <?php echo e(_i('This report shows the total deposits and withdrawals, both pending and approved, for each activated payment method in the selected currency.')); ?>

                        </p>
                        <p>
                            <?php echo e(_i('Transactions made through agents or manual transactions are not reflected in this report.')); ?>

                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row <?php echo e(count($currencies) > 1 ? 'totals d-none' : ''); ?>">
        <div class="col-sm-6 col-lg-6 col-xl g-mb-30">
            <div class="card h-100 g-brd-gray-light-v7 g-rounded-3">
                <div class="card-block g-font-weight-300 g-pa-20">
                    <div class="media">
                        <div class="d-flex g-mr-15">
                            <div
                                class="u-header-dropdown-icon-v1 g-pos-rel g-width-60 g-height-60 g-bg-teal g-font-size-18 g-font-size-24--md g-color-white rounded-circle">
                                <i class="hs-admin-stats-up g-absolute-centered"></i>
                            </div>
                        </div>
                        <div class="media-body align-self-center">
                            <div class="d-flex align-items-center g-mb-5">
                                <span class="g-font-size-24 g-line-height-1 g-color-black" id="credit-approved">0.00</span>
                            </div>
                            <h6 class="g-font-size-16 g-font-weight-300 g-color-gray-dark-v6 mb-0">
                                <?php echo e(_i('Total approved deposits')); ?>

                            </h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-6 col-xl g-mb-30">
            <div class="card h-100 g-brd-gray-light-v7 g-rounded-3">
                <div class="card-block g-font-weight-300 g-pa-20">
                    <div class="media">
                        <div class="d-flex g-mr-15">
                            <div
                                class="u-header-dropdown-icon-v1 g-pos-rel g-width-60 g-height-60 g-bg-lightblue-v3 g-font-size-18 g-font-size-24--md g-color-white rounded-circle">
                                <i class="hs-admin-stats-up g-absolute-centered"></i>
                            </div>
                        </div>
                        <div class="media-body align-self-center">
                            <div class="d-flex align-items-center g-mb-5">
                                <span class="g-font-size-24 g-line-height-1 g-color-black" id="credit-pending">0.00</span>
                            </div>
                            <h6 class="g-font-size-16 g-font-weight-300 g-color-gray-dark-v6 mb-0">
                                <?php echo e(_i('Total pending deposits')); ?>

                            </h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-6 col-xl g-mb-30">
            <div class="card h-100 g-brd-gray-light-v7 g-rounded-3">
                <div class="card-block g-font-weight-300 g-pa-20">
                    <div class="media">
                        <div class="d-flex g-mr-15">
                            <div
                                class="u-header-dropdown-icon-v1 g-pos-rel g-width-60 g-height-60 g-bg-primary g-font-size-18 g-font-size-24--md g-color-white rounded-circle">
                                <i class="hs-admin-stats-down g-absolute-centered"></i>
                            </div>
                        </div>
                        <div class="media-body align-self-center">
                            <div class="d-flex align-items-center g-mb-5">
                                <span class="g-font-size-24 g-line-height-1 g-color-black" id="debit-approved">0.00</span>
                            </div>
                            <h6 class="g-font-size-16 g-font-weight-300 g-color-gray-dark-v6 mb-0">
                                <?php echo e(_i('Total approved withdrawals')); ?>

                            </h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-6 col-xl g-mb-30">
            <div class="card h-100 g-brd-gray-light-v7 g-rounded-3">
                <div class="card-block g-font-weight-300 g-pa-20">
                    <div class="media">
                        <div class="d-flex g-mr-15">
                            <div
                                class="u-header-dropdown-icon-v1 g-pos-rel g-width-60 g-height-60 g-bg-lightblue-v3 g-font-size-18 g-font-size-24--md g-color-white rounded-circle">
                                <i class="hs-admin-stats-down g-absolute-centered"></i>
                            </div>
                        </div>
                        <div class="media-body align-self-center">
                            <div class="d-flex align-items-center g-mb-5">
                                <span class="g-font-size-24 g-line-height-1 g-color-black" id="debit-pending">0.00</span>
                            </div>
                            <h6 class="g-font-size-16 g-font-weight-300 g-color-gray-dark-v6 mb-0">
                                <?php echo e(_i('Total pending withdrawals')); ?>

                            </h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-lg-12 col-xl g-mb-30">
            <div class="card h-100 g-brd-gray-light-v7 g-rounded-3">
                <div class="card-block g-font-weight-300 g-pa-20">
                    <div class="media">
                        <div class="d-flex g-mr-15">
                            <div
                                class="u-header-dropdown-icon-v1 g-pos-rel g-width-60 g-height-60 g-bg-lightblue-v3 g-font-size-18 g-font-size-24--md g-color-white rounded-circle">
                                <i class="hs-admin-bar-chart g-absolute-centered"></i>
                            </div>
                        </div>
                        <div class="media-body align-self-center">
                            <div class="d-flex align-items-center g-mb-5">
                                <span class="g-font-size-24 g-line-height-1 g-color-black" id="profit">0.00</span>
                            </div>
                            <h6 class="g-font-size-16 g-font-weight-300 g-color-gray-dark-v6 mb-0">
                                <?php echo e(_i('Difference')); ?>

                            </h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                <header
                    class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                    <div class="media">
                        <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                            <?php echo e(_i('Search')); ?>

                        </h3>
                    </div>
                </header>
                <div class="card-block g-pa-15">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="daterange"><?php echo e(_i('Date range')); ?></label>
                                <input type="text" id="daterange" class="form-control daterange g-pr-80 g-pl-15 g-py-9" autocomplete="off">
                                <input type="hidden" id="start_date" name="start_date">
                                <input type="hidden" id="end_date" name="end_date">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="currency"><?php echo e(_i('Currency')); ?></label>
                                <select name="currency" id="currency" class="form-control">
                                    <?php $__currentLoopData = $currency_client; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($currency); ?>" <?php echo e($currency == session('currency') ? 'selected' : ''); ?>>
                                            <?php echo e($currency == 'VEF' ? $free_currency->currency_name : $currency); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="payment_method"><?php echo e(_i('Payment method')); ?></label>
                                <select name="payment_method" id="payment_method" class="form-control">
                                    <option value=""><?php echo e(_i('All')); ?></option>
                                    <?php $__currentLoopData = $payment_methods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($payment['id']); ?>">
                                            <?php echo e(\Dotworkers\Configurations\Enums\PaymentMethods::getName($payment['id'])); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
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
                    <table class="table table-bordered table-responsive-sm w-100" id="payment-totals-table"
                           data-route="<?php echo e(route('reports.payment-methods.totals-data')); ?>">
                        <thead>
                        <tr>
                            <th rowspan="2" class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                <?php echo e(_i('Payment method')); ?>

                            </th>
                            <th colspan="2" class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none text-center">
                                <?php echo e(_i('Deposits')); ?>

                            </th>
                            <th colspan="2" class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none text-center">
                                <?php echo e(_i('Withdrawals')); ?>

                            </th>
                            <th rowspan="2" class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                <?php echo e(_i('Profit')); ?>

                            </th>
                        </tr>
                        <tr>
                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                <?php echo e(_i('Pending')); ?>

                            </th>
                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                <?php echo e(_i('Approved')); ?>

                            </th>
                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                <?php echo e(_i('Pending')); ?>

                            </th>
                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                <?php echo e(_i('Approved')); ?>

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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        $(function () {
            let reports = new Reports();
            reports.paymentTotal();
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('back.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
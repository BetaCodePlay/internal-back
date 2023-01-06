

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                <header
                    class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                    <div class="media">
                        <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                            <?php echo e(_i('Filters')); ?>

                        </h3>
                    </div>
                </header>
                <div class="card-block g-pa-15">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="month"><?php echo e(_i('Month')); ?></label>
                                <select name="month" id="month" class="form-control" multiple>
                                    <option value=""><?php echo e(_i('Select...')); ?></option>
                                    <option value="01"> <?php echo e(_i('January')); ?> </option>
                                    <option value="02"> <?php echo e(_i('February')); ?> </option>
                                    <option value="03"> <?php echo e(_i('March')); ?> </option>
                                    <option value="04"> <?php echo e(_i('April')); ?> </option>
                                    <option value="05"> <?php echo e(_i('May')); ?> </option>
                                    <option value="06"> <?php echo e(_i('June')); ?> </option>
                                    <option value="07"> <?php echo e(_i('July')); ?> </option>
                                    <option value="08"> <?php echo e(_i('August')); ?> </option>
                                    <option value="09"> <?php echo e(_i('September')); ?> </option>
                                    <option value="10"> <?php echo e(_i('October')); ?> </option>
                                    <option value="11"> <?php echo e(_i('November')); ?> </option>
                                    <option value="12"> <?php echo e(_i('December')); ?> </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="years"><?php echo e(_i('Year')); ?></label>
                                <select name="years" id="years" class="form-control">
                                    <option value=""><?php echo e(_i('Select...')); ?></option>
                                    <?php for($i = $end_year; $i >= $start_year; $i--): ?>
                                        <option value="<?php echo e($i); ?>">
                                            <?php echo e($i); ?>

                                        </option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="currency"><?php echo e(_i('Currency')); ?></label>
                                <select name="currency" id="currency" class="form-control">
                                    <option value=""><?php echo e(_i('All currencies')); ?></option>
                                    <?php $__currentLoopData = $whitelabel_currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($currency->iso); ?>" <?php echo e($currency->iso == session('currency') ? 'selected' : ''); ?>>
                                            <?php echo e($currency->iso == 'VEF' ? $free_currency->currency_name : $currency->iso . " ({$currency->name})"); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="convert"><?php echo e(_i('Convert to')); ?></label>
                                <select name="convert" id="convert" class="form-control">
                                    <option value=""><?php echo e(_i('No conversion')); ?></option>
                                    <?php $__currentLoopData = $all_currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($currency->iso); ?>">
                                            <?php echo e($currency->iso == 'VEF' ? $free_currency->currency_name : $currency->iso); ?>

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
                    <div class="table-responsive">
                        <table class="table table-bordered w-100" id="monthly-sales-table"
                               data-route="<?php echo e(route('reports.financial.monthly-sales-data')); ?>">
                            <thead>
                            <tr>
                                <th rowspan="2" class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Month')); ?>

                                </th>
                                <th rowspan="2" class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('New registers')); ?>

                                </th>
                                <th rowspan="2" class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Unique depositors')); ?>

                                </th>
                                <th rowspan="2" class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Active users')); ?>

                                </th>
                                <th rowspan="2" class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('FTD')); ?>

                                </th>
                                <th rowspan="2" class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Bonuses')); ?>

                                </th>
                                <th colspan="2" class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none text-center">
                                    <?php echo e(_i('Deposits')); ?>

                                </th>
                                <th colspan="2" class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none text-center">
                                    <?php echo e(_i('Withdrawals')); ?>

                                </th>
                                <th colspan="2" class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none text-center">
                                    <?php echo e(_i('Manual transactions')); ?>

                                </th>
                                <th rowspan="2" class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Played')); ?>

                                </th>
                                <th rowspan="2" class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Profit')); ?>

                                </th>
                            </tr>
                            <tr>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Approved')); ?>

                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Rejected')); ?>

                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Approved')); ?>

                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Rejected')); ?>

                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Credit')); ?>

                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Debit')); ?>

                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th class="text-right"><?php echo e(_i('Percentage')); ?></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                            <tr>
                                <th class="text-right"><?php echo e(_i('Totals')); ?></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                            </tfoot>
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
            reports.monthlySales();
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('back.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<div class="noty_bar noty_type__warning noty_theme__unify--v1 g-mb-25">
    <div class="noty_body">
        <div class="g-mr-20">
            <div class="noty_body__icon">
                <i class="hs-admin-alert"></i>
            </div>
        </div>
        <div>
            <?php echo e(_i('This report makes closings and calculations every hour')); ?>

            <br>
            <?php echo e(_i('If you want to see data in real time please use the product reports')); ?>

        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-6 col-lg-6 col-xl g-mb-30">
        <div class="card h-100 g-brd-gray-light-v7 g-rounded-3">
            <div class="card-block g-font-weight-300 g-pa-20">
                <div class="media">
                    <div class="d-flex g-mr-15">
                        <div
                            class="u-header-dropdown-icon-v1 g-pos-rel g-width-60 g-height-60 g-bg-lightred-v2 g-font-size-18 g-font-size-24--md g-color-white rounded-circle">
                            <i class="hs-admin-arrow-down g-absolute-centered"></i>
                        </div>
                    </div>
                    <div class="media-body align-self-center">
                        <div class="d-flex align-items-center g-mb-5">
                            <span class="g-font-size-24 g-line-height-1 g-color-black" id="played">0.00</span>
                        </div>
                        <h6 class="g-font-size-16 g-font-weight-300 g-color-gray-dark-v6 mb-0">
                            <?php echo e(_i('Total played')); ?>

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
                            class="u-header-dropdown-icon-v1 g-pos-rel g-width-60 g-height-60 g-bg-darkblue-v2 g-font-size-18 g-font-size-24--md g-color-white rounded-circle">
                            <i class="hs-admin-arrow-up g-absolute-centered"></i>
                        </div>
                    </div>
                    <div class="media-body align-self-center">
                        <div class="d-flex align-items-center g-mb-5">
                            <span class="g-font-size-24 g-line-height-1 g-color-black" id="won">0.00</span>
                        </div>
                        <h6 class="g-font-size-16 g-font-weight-300 g-color-gray-dark-v6 mb-0">
                            <?php echo e(_i('Total won')); ?>

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
                            <i class="hs-admin-bar-chart g-absolute-centered"></i>
                        </div>
                    </div>
                    <div class="media-body align-self-center">
                        <div class="d-flex align-items-center g-mb-5">
                            <span class="g-font-size-24 g-line-height-1 g-color-black" id="profit">0.00</span>
                        </div>
                        <h6 class="g-font-size-16 g-font-weight-300 g-color-gray-dark-v6 mb-0">
                            <?php echo e(_i('Total profit')); ?>

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
                            class="u-header-dropdown-icon-v1 g-pos-rel g-width-60 g-height-60 g-bg-teal-v2 g-font-size-18 g-font-size-24--md g-color-white rounded-circle">
                            <i class="hs-admin-stats-up g-absolute-centered"></i>
                        </div>
                    </div>
                    <div class="media-body align-self-center">
                        <div class="d-flex align-items-center g-mb-5">
                            <span class="g-font-size-24 g-line-height-1 g-color-black" id="rtp">0.00%</span>
                        </div>
                        <h6 class="g-font-size-16 g-font-weight-300 g-color-gray-dark-v6 mb-0">
                            <?php echo e(_i('Total RTP')); ?>

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
                            class="u-header-dropdown-icon-v1 g-pos-rel g-width-60 g-height-60 g-bg-darkblue-v2 g-font-size-18 g-font-size-24--md g-color-white rounded-circle">
                            <i class="hs-admin-stats-up g-absolute-centered"></i>
                        </div>
                    </div>
                    <div class="media-body align-self-center">
                        <div class="d-flex align-items-center g-mb-5">
                            <span class="g-font-size-24 g-line-height-1 g-color-black" id="hold">0.00%</span>
                        </div>
                        <h6 class="g-font-size-16 g-font-weight-300 g-color-gray-dark-v6 mb-0">
                            <?php echo e(_i('Total hold')); ?>

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
                        <?php echo e(_i('Filter data')); ?>

                    </h3>
                </div>
            </header>
            <div class="card-block g-pa-15">
                <div class="row">
                    <div class="col-xs-12 col-md">
                        <div class="form-group">
                            <label for="provider"><?php echo e(_i('Provider')); ?></label>
                            <select name="provider" id="provider" class="form-control">
                                <option value=""><?php echo e(_i('All providers')); ?></option>
                                <?php $__currentLoopData = $providers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $provider): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($provider['id']); ?>">
                                        <?php echo e($provider['name']); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md type">
                        <div class="form-group">
                            <label for="type"><?php echo e(_i('Provider type')); ?></label>
                            <select name="type" id="type" class="form-control">
                                <option value=""><?php echo e(_i('All provider types')); ?></option>
                                <?php $__currentLoopData = $providers_types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $providers_type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($providers_type->id); ?>">
                                        <?php echo e($providers_type->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md">
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
                            <small class="form-text">
                                <?php echo e(_i('The currency (VES) will not be converted')); ?>

                            </small>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md">
                        <div class="form-group">
                            <label for="convert"><?php echo e(_i('Convert to')); ?></label>
                            <select name="convert" id="convert" class="form-control">
                                <option value=""><?php echo e(_i('No conversion')); ?></option>
                                <?php $__currentLoopData = $all_currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($currency->iso); ?>">
                                        <?php echo e($currency->iso == 'VEF' ? $free_currency->currency_name : $currency->iso . " ({$currency->name})"); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <small class="form-text">
                                <?php echo e(_i('The currency (VES) will not be converted')); ?>

                            </small>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md">
                        <div class="form-group">
                            <label for="tester"><?php echo e(_i('Date range')); ?></label>
                            <input type="text" id="daterange" class="form-control daterange g-pr-80 g-pl-15 g-py-9" autocomplete="off">
                            <input type="hidden" id="start_date" name="start_date">
                            <input type="hidden" id="end_date" name="end_date">
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
            <div class="card-block g-pa-15">
                <div class="media">
                    <div class="media-body d-flex justify-content-end g-mb-10" id="table-buttons">

                    </div>
                </div>
                <table class="table table-bordered table-responsive-sm w-100" id="products-totals-table"
                       data-route="<?php echo e(route('reports.products-totals-data')); ?>">
                    <thead>
                    <tr>
                        <th colspan="2" class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none text-center">
                            <?php echo e(_i('Provider')); ?>

                        </th>
                        <th colspan="2" class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none text-center">
                            <?php echo e(_i('Users')); ?>

                        </th>
                        <th colspan="2" class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none text-center">
                            <?php echo e(_i('Bets')); ?>

                        </th>
                        <th rowspan="2" class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                            <?php echo e(_i('Played')); ?>

                        </th>
                        <th rowspan="2" class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                            <?php echo e(_i('Won')); ?>

                        </th>
                        <th rowspan="2" class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                            <?php echo e(_i('Profit')); ?>

                        </th>
                        <th rowspan="2" class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                            <?php echo e(_i('RTP')); ?>

                        </th>
                        <th rowspan="2" class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                            <?php echo e(_i('Hold')); ?>

                        </th>
                    </tr>
                    <tr>
                        <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                            <?php echo e(_i('Name')); ?>

                        </th>
                        <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                            <?php echo e(_i('Type')); ?>

                        </th>
                        <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                            <?php echo e(_i('Total')); ?>

                        </th>
                        <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                            <?php echo e(_i('Last 30 days')); ?>

                        </th>
                        <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                            <?php echo e(_i('Total')); ?>

                        </th>
                        <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                            <?php echo e(_i('Last 30 days')); ?>

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

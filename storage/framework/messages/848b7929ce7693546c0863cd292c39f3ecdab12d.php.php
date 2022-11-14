

<?php $__env->startSection('content'); ?>
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
                                <span class="g-font-size-24 g-line-height-1 g-color-black" id="used-bonus">0.00</span>
                            </div>
                            <h6 class="g-font-size-16 g-font-weight-300 g-color-gray-dark-v6 mb-0">
                                <?php echo e(_i('Awarded bonuses')); ?>

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
                                <span class="g-font-size-24 g-line-height-1 g-color-black" id="ended-bonus">0.00</span>
                            </div>
                            <h6 class="g-font-size-16 g-font-weight-300 g-color-gray-dark-v6 mb-0">
                                <?php echo e(_i('Ended bonuses')); ?>

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
                                <span class="g-font-size-24 g-line-height-1 g-color-black" id="active-bonus">0.00</span>
                            </div>
                            <h6 class="g-font-size-16 g-font-weight-300 g-color-gray-dark-v6 mb-0">
                                <?php echo e(_i('Active bonuses')); ?>

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
                                <span class="g-font-size-24 g-line-height-1 g-color-black" id="converted-bonus">0.00%</span>
                            </div>
                            <h6 class="g-font-size-16 g-font-weight-300 g-color-gray-dark-v6 mb-0">
                                <?php echo e(_i('Converted bonuses')); ?>

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
                                <span class="g-font-size-24 g-line-height-1 g-color-black" id="amount-deposited">0.00</span>
                            </div>
                            <h6 class="g-font-size-16 g-font-weight-300 g-color-gray-dark-v6 mb-0">
                                <?php echo e(_i('Amount deposited')); ?>

                            </h6>
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
                    <div class="row">
                       <div class="col-md-4">
                           <div class="form-group">
                               <label for="date_ranger"><?php echo e(_i('Date range')); ?></label>
                               <input type="text" id="daterange" class="form-control daterange g-pr-80 g-pl-15 g-py-9"
                                      autocomplete="off">
                               <input type="hidden" id="start_date" name="start_date">
                               <input type="hidden" id="end_date" name="end_date">
                           </div>
                       </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="allocation_criteria"><?php echo e(_i('Allocation criteria')); ?></label>
                                <select name="allocation_criteria" id="allocation_criteria" class="form-control" class="form-control" data-route="<?php echo e(route('bonus-system.campaigns.allocation-criteria-types')); ?>">
                                    <option value="*" selected><?php echo e(_i('All options')); ?></option>
                                    <?php $__currentLoopData = $allocation_criteria; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $criteria): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($criteria->id); ?>">
                                            <?php echo e($criteria->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="campaign_data"><?php echo e(_i('Campaigns')); ?></label>
                                <select name="campaign_data[]" id="campaign_data" class="form-control" multiple>
                                    <option value="*"><?php echo e(_i('All options')); ?></option>
                                    <?php $__currentLoopData = $campaigns_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $campaign): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($campaign->id); ?>">
                                            <?php echo e($campaign->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="status"><?php echo e(_i('Status')); ?></label>
                                <select name="status" id="status" class="form-control">
                                    <option value="*"><?php echo e(_i('All options')); ?></option>
                                    <?php $__currentLoopData = $campaigns_status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($status->id); ?>">
                                            <?php echo e($status->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="currency"><?php echo e(_i('Currency')); ?></label>
                                <select name="currency" id="currency" class="form-control">
                                    <option value=""><?php echo e(_i('All currencies')); ?></option>
                                    <?php $__currentLoopData = $whitelabel_currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option
                                            value="<?php echo e($currency->iso); ?>" <?php echo e($currency->iso == session('currency') ? 'selected' : ''); ?>>
                                            <?php echo e($currency->iso == 'VEF' ? $free_currency->currency_name : $currency->iso . " ({$currency->name})"); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
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
                        <div class="col-md-4">
                            <div class="form-group">
                                <button type="button" class="btn u-btn-3d u-btn-primary" id="search"
                                        data-loading-text="<i class='fa fa-spin fa-spinner'></i> <?php echo e(_i('Searching...')); ?>">
                                    <i class="hs-admin-search"></i>
                                    <?php echo e(_i('Consult data')); ?>

                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                <div class="card-block g-pa-15">
                    <div class="media">
                        <div class="media-body d-flex justify-content-end g-mb-10" id="table-buttons">

                        </div>
                    </div>
                    <table class="table table-bordered table-responsive-sm w-100" id="user-participation-table"
                           data-route="<?php echo e(route('bonus-system.reports.participation-by-users-data')); ?>">
                        <thead>
                        <tr>
                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                <?php echo e(_i('ID')); ?>

                            </th>
                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                <?php echo e(_i('Username')); ?>

                            </th>
                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none" width="15%">
                                <?php echo e(_i('Campaign')); ?>

                            </th>
                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                <?php echo e(_i('Vertical')); ?>

                            </th>
                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                <?php echo e(_i('Currency')); ?>

                            </th>
                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                <?php echo e(_i('Awarded')); ?>

                            </th>
                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                <?php echo e(_i('Converted')); ?>

                            </th>
                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                <?php echo e(_i('Ended')); ?>

                            </th>
                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                <?php echo e(_i('Active')); ?>

                            </th>
                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none text-right">
                                <?php echo e(_i('Deposited')); ?>

                            </th>
                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none text-right">
                                <?php echo e(_i('Historical deposits')); ?>

                            </th>
                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none text-right">
                                <?php echo e(_i('Historical withdrawals')); ?>

                            </th>
                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none text-right">
                                <?php echo e(_i('Gross amount')); ?>

                            </th>
                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none text-right">
                                <?php echo e(_i('% Bonus / Deposits')); ?>

                            </th>
                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none" width="15%">
                                <?php echo e(_i('Dates')); ?>

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
            let bonusSystem = new BonusSystem();
            bonusSystem.campaignsUserParticipation()
            bonusSystem.allocationCriteriaTypes();
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('back.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


<?php $__env->startSection('content'); ?>
    <form action="<?php echo e(route('segments.users-data')); ?>" id="segmentation-form" method="post">
        <div class="row">
            <div class="col-md-4">
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-10">
                    <header
                        class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                <?php echo e(_i('Country filter')); ?>

                            </h3>
                        </div>
                    </header>
                    <div class="card-block g-pa-15 g-pb-5">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="country"><?php echo e(_i('Country')); ?></label>
                                    <select name="country[]" id="country" class="form-control" multiple="multiple">
                                        <option value=""><?php echo e(_i('All countries')); ?></option>
                                        <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($country->iso); ?>">
                                                <?php echo e($country->name); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exclude_country"><?php echo e(_i('Exclude country')); ?></label>
                                    <select name="exclude_country[]" id="exclude_country" class="form-control" multiple="multiple">
                                        <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($country->iso); ?>">
                                                <?php echo e($country->name); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-10">
                    <header
                        class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                <?php echo e(_i('Balance filter')); ?>

                            </h3>
                        </div>
                    </header>
                    <div class="card-block g-pa-15 g-pb-5">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="balance_options"><?php echo e(_i('Options')); ?></label>
                                    <select name="balance_options" id="balance_options" class="form-control">
                                        <option value="<="><?php echo e(_i('Less than or equal to')); ?></option>
                                        <option value=">="><?php echo e(_i('Greater than or equal to')); ?></option>
                                        <option value="=="><?php echo e(_i('Same to')); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <div class="form-group">
                                        <label for="balance"><?php echo e(_i('Balance')); ?></label>
                                        <input type="number" name="balance" id="balance" class="form-control" min="0">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-10">
                    <header
                        class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                <?php echo e(_i('Deposits filter')); ?>

                            </h3>
                        </div>
                    </header>
                    <div class="card-block g-pa-15 g-pb-5">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="deposits_options"><?php echo e(_i('Options')); ?></label>
                                    <select name="deposits_options" id="deposits_options" class="form-control">
                                        <option value="<="><?php echo e(_i('Less than or equal to')); ?></option>
                                        <option value=">="><?php echo e(_i('Greater than or equal to')); ?></option>
                                        <option value="=="><?php echo e(_i('Same to')); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="deposits"><?php echo e(_i('Deposits')); ?></label>
                                    <input type="number" class="form-control" name="deposits" id="deposits">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-10">
                    <header
                        class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                <?php echo e(_i('Last login filter')); ?>

                            </h3>
                        </div>
                    </header>
                    <div class="card-block g-pa-15 g-pb-5">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="last_login_options"><?php echo e(_i('Options')); ?></label>
                                    <select name="last_login_options" id="last_login_options" class="form-control">
                                        <option value="<="><?php echo e(_i('Less than or equal to')); ?></option>
                                        <option value=">="><?php echo e(_i('Greater than or equal to')); ?></option>
                                        <option value="=="><?php echo e(_i('Same to')); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <div class="form-group">
                                        <label for="last_login"><?php echo e(_i('Last login')); ?></label>
                                        <input type="text" name="last_login" id="last_login"
                                               class="form-control datepicker" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-10">
                    <header
                        class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                <?php echo e(_i('Last deposit filter')); ?>

                            </h3>
                        </div>
                    </header>
                    <div class="card-block g-pa-15 g-pb-5">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="last_deposit_options"><?php echo e(_i('Options')); ?></label>
                                    <select name="last_deposit_options" id="last_deposit_options" class="form-control">
                                        <option value="<="><?php echo e(_i('Less than or equal to')); ?></option>
                                        <option value=">="><?php echo e(_i('Greater than or equal to')); ?></option>
                                        <option value="=="><?php echo e(_i('Same to')); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <div class="form-group">
                                        <label for="last_deposit"><?php echo e(_i('Last deposit')); ?></label>
                                        <input type="text" name="last_deposit" id="last_deposit"
                                               class="form-control datepicker" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-10">
                    <header
                        class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                <?php echo e(_i('Last withdrawal filter')); ?>

                            </h3>
                        </div>
                    </header>
                    <div class="card-block g-pa-15 g-pb-5">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="last_withdrawal_options"><?php echo e(_i('Options')); ?></label>
                                    <select name="last_withdrawal_options" id="last_withdrawal_options" class="form-control">
                                        <option value="<="><?php echo e(_i('Less than or equal to')); ?></option>
                                        <option value=">="><?php echo e(_i('Greater than or equal to')); ?></option>
                                        <option value="=="><?php echo e(_i('Same to')); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <div class="form-group">
                                        <label for="last_withdrawal"><?php echo e(_i('Last withdrawal')); ?></label>
                                        <input type="text" name="last_withdrawal" id="last_withdrawal" class="form-control datepicker" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-10">
                    <header
                        class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                <?php echo e(_i('Registration filter')); ?>

                            </h3>
                        </div>
                    </header>
                    <div class="card-block g-pa-15 g-pb-5">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="registration_options"><?php echo e(_i('Options')); ?></label>
                                    <select name="registration_options" id="registration_options" class="form-control">
                                        <option value="<="><?php echo e(_i('Less than or equal to')); ?></option>
                                        <option value=">="><?php echo e(_i('Greater than or equal to')); ?></option>
                                        <option value="=="><?php echo e(_i('Same to')); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <div class="form-group">
                                        <label for="registration_date"><?php echo e(_i('Date registration')); ?></label>
                                        <input type="text" name="registration_date" id="registration_date" class="form-control datepicker" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-10">
                    <header
                        class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                <?php echo e(_i('Sales filter')); ?>

                            </h3>
                        </div>
                    </header>
                    <div class="card-block g-pa-15 g-pb-5">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="played_options"><?php echo e(_i('Options')); ?></label>
                                    <select name="played_options" id="played_options" class="form-control">
                                        <option value="<="><?php echo e(_i('Less than or equal to')); ?></option>
                                        <option value=">="><?php echo e(_i('Greater than or equal to')); ?></option>
                                        <option value="=="><?php echo e(_i('Same to')); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <div class="form-group">
                                        <label for="played"><?php echo e(_i('Played')); ?></label>
                                        <input type="text" name="played" id="played" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-10">
                    <header
                        class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                <?php echo e(_i('Complete profile filter')); ?>

                            </h3>
                        </div>
                    </header>
                    <div class="card-block g-pa-15 g-pb-5">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="registration_options"><?php echo e(_i('Full profile')); ?></label>
                                    <select name="full_profile" id="full_profile" class="form-control">
                                        <option value=""><?php echo e(_i('Select')); ?></option>
                                        <option value="1"><?php echo e(_i('Completed')); ?></option>
                                        <option value="0"><?php echo e(_i('Incomplete')); ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-10">
                    <header
                        class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                <?php echo e(_i('Other filters')); ?>

                            </h3>
                        </div>
                    </header>
                    <div class="card-block g-pa-15 g-pb-5">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="currency"><?php echo e(_i('Currency')); ?></label>
                                    <select name="currency[]" id="currency" class="form-control" multiple="multiple">
                                        <option value=""><?php echo e(_i('All currencies')); ?></option>
                                        <?php $__currentLoopData = $currency_client; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($currency); ?>">
                                                <?php echo e($currency == 'VEF' ? $free_currency->currency_name : $currency); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="status"><?php echo e(_i('Player status')); ?></label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="1"><?php echo e(_i('Active')); ?></option>
                                        <option value="0"><?php echo e(_i('Blocked')); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="language"><?php echo e(_i('Language')); ?></label>
                                    <select name="language[]" id="language" class="form-control" multiple="multiple">
                                        <option value=""><?php echo e(_i('All languages')); ?></option>
                                        <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($language['iso']); ?>">
                                                <?php echo e($language['name']); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
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
                </div>
            </div>
        </div>
    </form>
    <div class="row">
        <div class="col-md-12">
            <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                <header
                    class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                    <div class="media">
                        <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0 title">
                            <?php echo e(_i('Search results')); ?>

                        </h3>
                        <div class="media-body d-flex justify-content-end" id="table-buttons">

                        </div>
                        <div class="justify-content-end g-ml-10 d-none g-pl-10" id="segments">
                            <a href="#store-segments-modal" class="btn u-btn-3d u-btn-primary" data-toggle="modal">
                                <i class="hs-admin-save"></i>
                                <?php echo e(_i('Save segment')); ?>

                            </a>
                        </div>
                        <div class="justify-content-end g-ml-10 g-pl-10" id="create-segment">
                            <a href="#store-segments-modal" class="btn u-btn-3d u-btn-primary" data-toggle="modal">
                                <i class="hs-admin-save"></i>
                                <?php echo e(_i('Create empty segment')); ?>

                            </a>
                        </div>
                    </div>
                </header>
                <div class="card-block g-pa-15">
                    <div class="table-responsive">
                        <table class="table table-bordered w-100" id="segmentation-table">
                            <thead>
                            <tr>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('ID')); ?>

                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Username')); ?>

                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Full Name')); ?>

                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Email')); ?>

                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Phone')); ?>

                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Country')); ?>

                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Currency')); ?>

                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Last deposit')); ?>

                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Last withdrawal')); ?>

                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Last login')); ?>

                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Profile')); ?>

                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Registered')); ?>

                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Language')); ?>

                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Deposits')); ?>

                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Played')); ?>

                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Balance')); ?>

                                </th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php echo $__env->make('back.crm.segments.modals.store', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        $(function () {
            let segments = new Segments();
            segments.usersData();
            segments.update();
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('back.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
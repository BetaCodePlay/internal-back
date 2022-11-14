

<?php $__env->startSection('content'); ?>
    <form action="<?php echo e(route('bonus-system.campaigns.store')); ?>" id="campaigns-form" method="post" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-9">
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-10">
                    <header
                        class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                <?php echo e(_i('Campaign details')); ?>

                            </h3>
                            <div class="media-body d-flex justify-content-end">
                                <a href="<?php echo e(route('bonus-system.campaigns.index')); ?>" class="btn u-btn-3d u-btn-primary float-right">
                                    <i class="hs-admin-layout-list-thumb"></i>
                                    <?php echo e(_i('Go to list')); ?>

                                </a>
                            </div>
                        </div>
                    </header>
                    <div class="card-block g-pa-15">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="internal_name"><?php echo e(_i('Internal name (not displayed to user)')); ?></label>
                                    <input type="text" name="internal_name" id="internal_name" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="start_date"><?php echo e(_i('Start date')); ?></label>
                                    <input type="text" name="start_date" id="start_date" class="form-control datetimepicker" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="end_date"><?php echo e(_i('Finish date')); ?></label>
                                    <input type="text" name="end_date" id="end_date" class="form-control datetimepicker" autocomplete="off" placeholder="<?php echo e(_i('Optional')); ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="complete_profile"><?php echo e(_i('Does the user have to complete the profile?')); ?></label>
                                    <select name="complete_profile" id="complete_profile" class="form-control">
                                        <option value=""><?php echo e(_i('Select...')); ?></option>
                                        <option value="true"><?php echo e(_i('Yes')); ?></option>
                                        <option value="false"><?php echo e(_i('No')); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="currency"><?php echo e(_i('Currency')); ?></label>
                                    <select name="currency" id="currency" class="form-control" data-route="<?php echo e(route('bonus-system.campaigns.provider-types')); ?>" data-payments-route="<?php echo e(route('bonus-system.campaigns.payment-methods')); ?>">
                                        <option value=""><?php echo e(_i('Select...')); ?></option>
                                        <?php $__currentLoopData = $whitelabel_currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($currency->iso); ?>">
                                                <?php echo e($currency->iso == 'VEF' ? $free_currency->currency_name : $currency->iso . " ({$currency->name})"); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="status"><?php echo e(_i('Status')); ?></label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="true"><?php echo e(_i('Active')); ?></option>
                                        <option value="false"><?php echo e(_i('Inactive')); ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-10 promo_codes">
                    <header
                        class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                <?php echo e(_i('Promo code')); ?>

                            </h3>
                        </div>
                    </header>
                    <div class="card-block g-pa-15">
                        <div class="repeater">
                            <div data-repeater-list="promo_codes">
                                <div data-repeater-item>
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label for="promo_code"><?php echo e(_i('Promo code')); ?></label>
                                                <input type="text" name="promo_code" id="promo_code" class="form-control text-uppercase">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label for="btag"><?php echo e(_i('BTag')); ?></label>
                                                <input type="text" name="btag" id="btag" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-2" style="margin-top: 25px">
                                            <div class="form-group">
                                                <label for=""></label>
                                                <button data-repeater-delete class="btn u-btn-3d u-btn-primary" type="button">
                                                    <i class="hs-admin-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <button data-repeater-create class="btn u-btn-3d u-btn-primary" type="button">
                                    <i class="hs-admin-plus"></i>
                                    <?php echo e(_i('Add')); ?>

                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-10">
                    <header
                        class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                <?php echo e(_i('Additional data')); ?>

                            </h3>
                        </div>
                    </header>
                    <div class="card-block g-pa-15">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="allocation_criteria"><?php echo e(_i('Allocation criteria')); ?></label>
                                    <select name="allocation_criteria" id="allocation_criteria" class="form-control">
                                        <option value=""><?php echo e(_i('Select...')); ?></option>
                                        <?php $__currentLoopData = $allocation_criteria; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $criteria): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($criteria->id); ?>">
                                                <?php echo e($criteria->name); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 bonus-code d-none">
                                <div class="form-group">
                                    <label for="bonus_code"><?php echo e(_i('Bonus code')); ?></label>
                                    <input type="text" name="bonus_code" id="bonus_code" class="form-control text-uppercase">
                                </div>
                            </div>
                            <div class="col-md-4 bonus-type d-none">
                                <div class="form-group">
                                    <label for="bonus_type"><?php echo e(_i('Type of bonus to be awarded')); ?></label>
                                    <select name="bonus_type" id="bonus_type" class="form-control">
                                        <option value=""><?php echo e(_i('Select...')); ?></option>
                                        <option value="1"><?php echo e(_i('Fixed amount')); ?></option>
                                        <option value="2"><?php echo e(_i('Percentage with limit')); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 min d-none">
                                <div class="form-group">
                                    <label for="min_deposit"><?php echo e(_i('Minimum deposit')); ?></label>
                                    <input type="text" name="min_deposit" id="min_deposit" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4 deposits-options d-none">
                                <div class="form-group">
                                    <label for="deposit_options"><?php echo e(_i('Deposit options')); ?></label>
                                    <select name="deposit_options" id="deposit_options" class="form-control">
                                        <option value="<="><?php echo e(_i('Less than')); ?></option>
                                        <option value=">="><?php echo e(_i('Greater than')); ?></option>
                                        <option value="=="><?php echo e(_i('Same to')); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 deposits-quantity d-none">
                                <div class="form-group">
                                    <label for="deposits_quantity"><?php echo e(_i('Quantity of deposits')); ?></label>
                                    <input type="number" class="form-control" name="deposits_quantity" id="deposits_quantity" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-4 payments d-none">
                                <div class="form-group">
                                    <label for="include_payment_methods"><?php echo e(_i('Include payments methods')); ?></label>
                                    <select name="include_payment_methods[]" id="include_payment_methods" class="form-control select2" multiple>
                                        <option value=""><?php echo e(_i('Select...')); ?></option>
                                        <option value="<?php echo e(\Dotworkers\Configurations\Enums\Providers::$dotworkers); ?>">
                                            <?php echo e(_i('Manual transactions')); ?>

                                        </option>
                                        <option value="<?php echo e(\Dotworkers\Configurations\Enums\Providers::$agents_users); ?>">
                                            <?php echo e(_i('Agents transactions')); ?>

                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 payments d-none">
                                <div class="form-group">
                                    <label for="exclude_payment_methods"><?php echo e(_i('Exclude payments methods')); ?></label>
                                    <select name="exclude_payment_methods[]" id="exclude_payment_methods" class="form-control select2" multiple>
                                        <option value=""><?php echo e(_i('Select...')); ?></option>
                                        <option value="<?php echo e(\Dotworkers\Configurations\Enums\Providers::$dotworkers); ?>">
                                            <?php echo e(_i('Manual transactions')); ?>

                                        </option>
                                        <option value="<?php echo e(\Dotworkers\Configurations\Enums\Providers::$agents_users); ?>">
                                            <?php echo e(_i('Agents transactions')); ?>

                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-10">
                    <header
                        class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                <?php echo e(_i('Rollovers data')); ?>

                            </h3>
                        </div>
                    </header>
                    <div class="card-block g-pa-15">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="complete_rollovers"><?php echo e(_i('Complete rollovers')); ?></label>
                                    <select name="complete_rollovers" id="complete_rollovers" class="form-control">
                                        <option value=""><?php echo e(_i('Select...')); ?></option>
                                        <option value="yes"><?php echo e(_i('Yes')); ?></option>
                                        <option value="no"><?php echo e(_i('No')); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 rollovers-data d-none">
                                <div class="form-group">
                                    <label for="include_deposit"><?php echo e(_i('Rollover type')); ?></label>
                                    <select name="include_deposit" id="include_deposit" class="form-control">
                                        <option value=""><?php echo e(_i('Select...')); ?></option>
                                        <option value="deposit"><?php echo e(_i('Deposit')); ?></option>
                                        <option value="bonus"><?php echo e(_i('Bonus')); ?></option>
                                        <option value="both"><?php echo e(_i('Bonus + deposit')); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 rollovers-data d-none">
                                <div class="form-group">
                                    <label for="provider_type"><?php echo e(_i('Provider type')); ?></label>
                                    <select name="provider_type" id="provider_type" data-route="<?php echo e(route('bonus-system.campaigns.exclude-providers')); ?>" class="form-control">
                                        <option value=""><?php echo e(_i('Select...')); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 rollovers-data d-none">
                                <div class="form-group">
                                    <label for="exclude_providers"><?php echo e(_i('Exclude providers')); ?></label>
                                    <select name="exclude_providers[]" id="exclude_providers" class="form-control" multiple>
                                        <option value=""><?php echo e(_i('Select...')); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 rollovers-data d-none">
                                <div class="form-group">
                                    <label for="multiplier"><?php echo e(_i('Rollover multiplier')); ?></label>
                                    <input type="number" name="multiplier" id="multiplier" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4 rollovers-data d-none">
                                <div class="form-group">
                                    <label for="days"><?php echo e(_i('Days to complete rollover')); ?></label>
                                    <input type="text" name="days" id="days" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4 sports d-none">
                                <div class="form-group">
                                    <label for="bet_type"><?php echo e(_i('Bet type')); ?></label>
                                    <select name="bet_type" id="bet_type" class="form-control">
                                        <option value="both"><?php echo e(_i('Simple and combined')); ?></option>
                                        <option value="simple"><?php echo e(_i('Simple')); ?></option>
                                        <option value="combined"><?php echo e(_i('Combined')); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 sports d-none">
                                <div class="form-group">
                                    <label for="odd"><?php echo e(_i('Quota or Achievement')); ?></label>
                                    <input type="text" min="0" name="odd" id="odd" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="submit" class="btn u-btn-3d u-btn-primary" id="store"
                                            data-loading-text="<i class='fa fa-spin fa-spinner'></i> <?php echo e(_i('Publishing...')); ?>">
                                        <i class="hs-admin-upload"></i>
                                        <?php echo e(_i('Publish campaign')); ?>

                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-10">
                    <header
                        class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                <?php echo e(_i('Bonus data')); ?>

                            </h3>
                        </div>
                    </header>
                    <div class="card-block g-pa-15">
                        <div class="col-md-12 bonus d-none">
                            <div class="form-group">
                                <label for="bonus"><?php echo e(_i('Bonus to be awarded')); ?></label>
                                <input type="text" name="bonus" id="bonus" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-12 deposit-percentage d-none">
                            <div class="form-group">
                                <label for="percentage"><?php echo e(_i('Deposit percentage')); ?></label>
                                <input type="text" name="percentage" id="percentage" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-12 bonus-limit d-none">
                            <div class="form-group">
                                <label for="limit"><?php echo e(_i('Bonus limit to be awarded')); ?></label>
                                <input type="text" name="limit" id="limit" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="max_balance_convert"><?php echo e(_i('Amount that can be converted into a real balance')); ?></label>
                                <input type="text" name="max_balance_convert" id="max_balance_convert" class="form-control" placeholder="<?php echo e(_i('Optional')); ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-10">
                    <header
                        class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                <?php echo e(_i('Users restriction')); ?>

                            </h3>
                        </div>
                    </header>
                    <div class="card-block g-pa-15">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="users_restriction_type"><?php echo e(_i('Users restriction type')); ?></label>
                                <select name="users_restriction_type" id="users_restriction_type" class="form-control">
                                    <option value=""><?php echo e(_i('No restriction')); ?></option>
                                    <option value="users"><?php echo e(_i('Users')); ?></option>
                                    <option value="segments"><?php echo e(_i('Segments')); ?></option>
                                    <option value="excel"><?php echo e(_i('Excel')); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="search-users d-none">
                                <div class="form-group">
                                    <label for="include_users"><?php echo e(_i('Include users')); ?></label>
                                    <select name="include_users[]" id="include_users" class="form-control select2" data-route="<?php echo e(route('users.search-username')); ?>" multiple>
                                        <option></option>
                                    </select>
                                </div>
                            </div>
                            <div class="search-users d-none">
                                <div class="form-group">
                                    <label for="exclude_users"><?php echo e(_i('Exclude users')); ?></label>
                                    <select name="exclude_users[]" id="exclude_users" class="form-control select2" data-route="<?php echo e(route('users.search-username')); ?>" multiple>
                                        <option></option>
                                    </select>
                                </div>
                            </div>
                            <div class="search-segments d-none">
                                <div class="form-group">
                                    <label for="include_segments"><?php echo e(_i('Include segments')); ?></label>
                                    <select name="include_segments[]" id="include_segments" class="form-control" multiple>
                                        <option value=""><?php echo e(_i('Select...')); ?></option>
                                        <?php $__currentLoopData = $segments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $segment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($segment->id); ?>">
                                                <?php echo e($segment->name); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="search-segments d-none">
                                <div class="form-group">
                                    <label for="exclude_segments"><?php echo e(_i('Exclude segments')); ?></label>
                                    <select name="exclude_segments[]" id="exclude_segments" class="form-control" multiple>
                                        <option value=""><?php echo e(_i('Select...')); ?></option>
                                        <?php $__currentLoopData = $segments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $segment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($segment->id); ?>">
                                                <?php echo e($segment->name); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="search-excel d-none">
                                <div class="form-group">
                                    <label for="include_excel"><?php echo e(_i('Include excel')); ?></label>
                                    <input type="file" name="include_excel" id="include_excel" class="opacity-0">
                                </div>
                            </div>
                            <div class="search-excel d-none">
                                <div class="form-group">
                                    <label for="exclude_excel"><?php echo e(_i('Exclude excel')); ?></label>
                                    <input type="file" name="exclude_excel" id="exclude_excel" class="opacity-0">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-10">
                    <header
                        class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                <?php echo e(_i('Translations')); ?>

                            </h3>
                        </div>
                    </header>
                    <div class="card-block g-pa-15">
                        <table class="table table-bordered w-100">
                            <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($language['name']); ?></td>
                                    <td class="text-right">
                                        <a href="#add-translations-modal" data-toggle="modal" class="btn u-btn-3d u-btn-primary btn-sm add-translation" data-language="<?php echo e($language['name']); ?>" data-language-iso="<?php echo e($language['iso']); ?>">
                                            <i class="hs-admin-plus"></i>
                                            <?php echo e(_i('Add')); ?>

                                        </a>
                                        <a href="#add-translations-modal" data-toggle="modal" class="btn u-btn-3d u-btn-primary btn-sm edit-translation d-none" data-language="<?php echo e($language['name']); ?>" data-language-iso="<?php echo e($language['iso']); ?>">
                                            <i class="hs-admin-pencil"></i>
                                            <?php echo e(_i('Edit')); ?>

                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <?php echo $__env->make('back.bonus-system.campaigns.modals.add-translations-modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        $(function () {
            let bonusSystem = new BonusSystem();
            bonusSystem.store(<?php echo json_encode($languages, 15, 512) ?>);
            bonusSystem.rollovers();
            bonusSystem.providers();
            bonusSystem.allocationCriteria();
            bonusSystem.bonusType();
            bonusSystem.providerTypes();
            bonusSystem.sportsProvider();
            bonusSystem.addTranslations(<?php echo json_encode($languages, 15, 512) ?>);
            bonusSystem.usersRestrictionType();
            bonusSystem.includeUsers('<?php echo e(_i('Select user')); ?>');
            bonusSystem.excludeUsers('<?php echo e(_i('Select user')); ?>');
            bonusSystem.paymentMethods();
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('back.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
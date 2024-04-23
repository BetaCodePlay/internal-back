

<?php $__env->startSection('content'); ?>
    <form action="<?php echo e(route('bonus-system.campaigns.store')); ?>" id="campaigns-form" method="post" enctype="multipart/form-data" novalidate>
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
                                    <label for="internal_name"><?php echo e(_i('Internal name')); ?></label>
                                    <input type="text" name="internal_name" id="internal_name" placeholder="Ej. Bono por deposito" class="form-control input_placeholder">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="start_date"><?php echo e(_i('Start date')); ?></label>
                                    <input type="text" name="start_date" id="start_date" class="form-control datetimepicker input_placeholder" placeholder="Ej. 07-07-2023 01:15 pm" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="end_date"><?php echo e(_i('Finish date')); ?></label>
                                    <input type="text" name="end_date" id="end_date" class="form-control datetimepicker input_placeholder" autocomplete="off" placeholder="<?php echo e(_i('Optional')); ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="currencies"><?php echo e(_i('Currencies')); ?></label>
                                    <select name="currencies[]" id="currencies" class="form-control input_placeholder" data-route="<?php echo e(route('bonus-system.campaigns.provider-types')); ?>" data-payments-route="<?php echo e(route('bonus-system.campaigns.payment-methods')); ?>">
                                        <option value="" disabled selected ><?php echo e(_i('Select...')); ?></option>
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
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="bonus_type"><?php echo e(_i('Bonus type')); ?></label>
                                    <select name="bonus_type" id="bonus_type" class="form-control">
                                        <option value="1"><?php echo e(_i('Instant')); ?></option>
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
                                                <input type="text" name="promo_code" id="promo_code" class="form-control text-uppercase input_placeholder" placeholder="Ej. 12345">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label for="btag"><?php echo e(_i('BTag')); ?></label>
                                                <input type="text" name="btag" id="btag" class="form-control input_placeholder" placeholder="Ej. Bono del mes">
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
                                <?php echo e(_i('Allocation criteria')); ?>

                            </h3>
                        </div>
                    </header>
                    <div class="card-block g-pt-15 g-pb-0">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="bonus"><?php echo e(_i('Bonus')); ?></label>
                                    <select name="allocation_criteria[]" id="allocation_criteria" class="form-control" data-route="<?php echo e(route('bonus-system.campaigns.allocation-criteria-all')); ?>">
                                        <option value=""><?php echo e(_i('Select...')); ?></option>
                                        <?php $__currentLoopData = $criterias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $criteria): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($criteria->id); ?>">
                                                <?php echo e(_i($criteria->name)); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for=""><?php echo e(_i('Commission real balance(%)')); ?>

                                        <i class="fa fa-info-circle" aria-hidden="true" data-container="body" data-toggle="popover" data-placement="top" data-trigger="hover" data-content="<?php echo e(_i('Assign the percentage of your actual balance that you want to spend along with the bonus')); ?>">
                                        </i>
                                    </label>
                                    <input type="text" name="commission_real" id="commission_real" onkeyup="BonusSystem.updateCommissionBonus()" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for=""><?php echo e(_i('Commission bonus balance(%)')); ?>

                                        <i class="fa fa-info-circle" aria-hidden="true" data-container="body" data-toggle="popover" data-placement="top" data-trigger="hover" data-content="<?php echo e(_i('Assign the percentage of bonus balance you want to spend along with the actual balance')); ?>">
                                        </i>
                                    </label>
                                    <input type="text" name="commission_bonus_v" id="commission_bonus_v" class="form-control" disabled>
                                    <input type="hidden" name="commission_bonus" id="commission_bonus" class="form-control" >
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
                                <?php echo e(_i('Bonus data')); ?>

                            </h3>
                        </div>
                    </header>
                    <div class="card-block g-pt-15 g-pb-0">
                        <table class="table align-middle g-mb-0">
                            <tr>
                                <td width="20%">
                                    <div class="form-group g-mb-10">
                                        <label class="u-check g-pl-25">
                                            <input class="g-hidden-xs-up g-pos-abs g-top-0 g-left-0" name="bonus_type_awarded" id="fixed-bonus" type="radio" value="<?php echo e(\Dotworkers\Bonus\Enums\BonusTypeAwarded::$fixed); ?>">
                                            <div class="u-check-icon-font g-absolute-centered--y g-left-0">
                                                <i class="fa" data-check-icon="" data-uncheck-icon=""></i>
                                            </div>
                                            <?php echo e(_i('Fixed bonus')); ?>

                                        </label>
                                    </div>
                                    <div class="form-group g-mb-10">
                                        <label class="u-check g-pl-25 ">
                                            <input class="g-hidden-xs-up g-pos-abs g-top-0 g-left-0" name="bonus_type_awarded" id="deposit-percentage" type="radio"  value="<?php echo e(\Dotworkers\Bonus\Enums\BonusTypeAwarded::$percentage); ?>">
                                            <div class="u-check-icon-font g-absolute-centered--y g-left-0">
                                                <i class="fa" data-check-icon="" data-uncheck-icon=""></i>
                                            </div>
                                            <?php echo e(_i('Deposit percentage')); ?>

                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <div class="bonus-table d-none">
                                        <table class="table table-bordered">
                                            <?php $__currentLoopData = $whitelabel_currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr class="bonus-row bonus-row-<?php echo e($currency->iso); ?> d-none">
                                                    <td class="align-middle text-center">
                                                        <?php echo e($currency->iso); ?>

                                                    </td>
                                                    <td class="align-middle">
                                                        <div class="row">
                                                            <div class="col-md-4 fixed-bonus d-none">
                                                                <div class="form-group">
                                                                    <label for="bonus"><?php echo e(_i('Bonus to be awarded')); ?></label>
                                                                    <input type="number" min="0" name="bonus[<?php echo e($currency->iso); ?>]" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 deposit-percentage d-none">
                                                                <div class="form-group">
                                                                    <label for="percentage"><?php echo e(_i('Percentage bonus ')); ?></label>
                                                                    <input type="number" min="0" name="percentages[<?php echo e($currency->iso); ?>]" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 deposit-percentage d-none">
                                                                <div class="form-group">
                                                                    <label for="limit"><?php echo e(_i('Limit to be awarded')); ?></label>
                                                                    <input type="number" min="0" name="limits[<?php echo e($currency->iso); ?>]" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 max-convert d-none">
                                                                <div class="form-group">
                                                                    <label for="max_balance_convert"><?php echo e(_i('Maximum real balance')); ?></label>
                                                                    <input type="number" min="0" name="max_balances_convert[<?php echo e($currency->iso); ?>]" class="form-control" placeholder="<?php echo e(_i('Optional')); ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                        </table>
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
                                    <label for="exclude_providers"><?php echo e(_i('Exclude providers')); ?></label>
                                    <select name="exclude_providers[]" id="exclude_providers" class="form-control" multiple>
                                        <option value=""><?php echo e(_i('Select...')); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 rollovers-data d-none">
                                <div class="form-group">
                                    <label for="multiplier"><?php echo e(_i('Rollover multiplier')); ?></label>
                                    <input type="number" min="0" name="multiplier" id="multiplier" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4 rollovers-data d-none">
                                <div class="form-group">
                                    <label for="days"><?php echo e(_i('Days to complete rollover')); ?></label>
                                    <input type="number" min="0" name="days" id="days" class="form-control">
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
            bonusSystem.addTranslations(<?php echo json_encode($languages, 15, 512) ?>);
            $('[data-toggle="popover"]').popover();
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('back.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
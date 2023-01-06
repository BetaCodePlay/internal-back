

<?php $__env->startSection('content'); ?>
    <form action="<?php echo e(route('bonus-system.campaigns.update')); ?>" id="campaigns-form" method="post" enctype="multipart/form-data" novalidate>
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
                                    <input type="text" name="internal_name" id="internal_name" class="form-control" value="<?php echo e($campaign->name); ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="start_date"><?php echo e(_i('Start date')); ?></label>
                                    <input type="text" name="start_date" id="start_date" class="form-control datetimepicker" autocomplete="off" value="<?php echo e($campaign->start); ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="end_date"><?php echo e(_i('Finish date')); ?></label>
                                    <input type="text" name="end_date" id="end_date" class="form-control datetimepicker" autocomplete="off" value="<?php echo e($campaign->end); ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="currency"><?php echo e(_i('Currency')); ?></label>
                                    <select name="currency" id="currency" class="form-control" data-route="<?php echo e(route('bonus-system.campaigns.provider-types')); ?>" data-payments-route="<?php echo e(route('bonus-system.campaigns.payment-methods')); ?>">
                                        <?php $__currentLoopData = $whitelabel_currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($currency->iso); ?>" <?php echo e($currency->iso == $campaign->currency_iso ? 'selected' : ''); ?>>
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
                                        <option value="true" <?php echo e($campaign->status ? 'selected' : ''); ?>>
                                            <?php echo e(_i('Active')); ?>

                                        </option>
                                        <option value="false" <?php echo e(!$campaign->status ? 'selected' : ''); ?>>
                                            <?php echo e(_i('Inactive')); ?>

                                        </option>
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
                                <?php echo e(_i('Allocation criteria')); ?>

                            </h3>
                        </div>
                    </header>
                    <div class="card-block g-pt-15 g-pb-0">
                        <table class="table align-middle g-mb-0">
                            <tr>
                                <td width="20%">
                                    <div class="form-check">
                                        <label class="u-check g-pl-25">
                                            <input class="g-hidden-xs-up g-pos-abs g-top-0 g-left-0" type="checkbox" name="allocation_criteria[]"
                                                   value="<?php echo e(\Dotworkers\Bonus\Enums\AllocationCriteria::$registration); ?>"
                                                <?php echo e(in_array(\Dotworkers\Bonus\Enums\AllocationCriteria::$registration, $campaign->data->allocation_criteria) ? 'checked' : ''); ?>>
                                            <div class="u-check-icon-checkbox-v4 g-absolute-centered--y g-left-0">
                                                <i class="fa" data-check-icon="&#xf00c"></i>
                                            </div>
                                            <?php echo e(_i('Registration')); ?>

                                        </label>
                                    </div>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="form-check">
                                        <label class="u-check g-pl-25">
                                            <input class="g-hidden-xs-up g-pos-abs g-top-0 g-left-0" type="checkbox" name="allocation_criteria[]"
                                                   value="<?php echo e(\Dotworkers\Bonus\Enums\AllocationCriteria::$complete_profile); ?>"
                                                <?php echo e(in_array(\Dotworkers\Bonus\Enums\AllocationCriteria::$complete_profile, $campaign->data->allocation_criteria) ? 'checked' : ''); ?>>
                                            <div class="u-check-icon-checkbox-v4 g-absolute-centered--y g-left-0">
                                                <i class="fa" data-check-icon="&#xf00c"></i>
                                            </div>
                                            <?php echo e(_i('Complete profile')); ?>

                                        </label>
                                    </div>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="form-check">
                                        <label class="u-check g-pl-25 <?php echo e(!in_array(\Dotworkers\Bonus\Enums\AllocationCriteria::$deposit, $campaign->data->allocation_criteria) ? 'disabled' : ''); ?>">
                                            <input class="g-hidden-xs-up g-pos-abs g-top-0 g-left-0" type="checkbox" name="allocation_criteria[]"
                                                   id="deposits" value="<?php echo e(\Dotworkers\Bonus\Enums\AllocationCriteria::$deposit); ?>"
                                                <?php echo e(in_array(\Dotworkers\Bonus\Enums\AllocationCriteria::$deposit, $campaign->data->allocation_criteria) ? 'checked' : ''); ?>

                                                <?php echo e(!in_array(\Dotworkers\Bonus\Enums\AllocationCriteria::$deposit, $campaign->data->allocation_criteria) ? 'disabled' : ''); ?>>
                                            <div class="u-check-icon-checkbox-v4 g-absolute-centered--y g-left-0">
                                                <i class="fa" data-check-icon="&#xf00c"></i>
                                            </div>
                                            <?php echo e(_i('Deposits')); ?>

                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <div class="deposits-table <?php echo e(!in_array(\Dotworkers\Bonus\Enums\AllocationCriteria::$deposit, $campaign->data->allocation_criteria) ? 'd-none' : ''); ?>">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="deposit_type"><?php echo e(_i('Deposit type')); ?></label>
                                                    <select name="deposit_type" class="form-control">
                                                        <option value="<?php echo e(\Dotworkers\Bonus\Enums\DepositTypes::$first); ?>" <?php echo e(isset($campaign->data->deposit_type) && $campaign->data->deposit_type == \Dotworkers\Bonus\Enums\DepositTypes::$first ? 'selected' : ''); ?>>
                                                            <?php echo e(_i('First')); ?>

                                                        </option>
                                                        <option value="<?php echo e(\Dotworkers\Bonus\Enums\DepositTypes::$next); ?>" <?php echo e(isset($campaign->data->deposit_type) && $campaign->data->deposit_type == \Dotworkers\Bonus\Enums\DepositTypes::$next ? 'selected' : ''); ?>>
                                                            <?php echo e(_i('Next')); ?>

                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="min_deposit"><?php echo e(_i('Minimum')); ?></label>
                                                    <input type="number" min="0" name="min_deposit" class="form-control" value="<?php echo e(isset($campaign->data->min) ? $campaign->data->min : ''); ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="include_payment_methods"><?php echo e(_i('Include payment methods')); ?></label>
                                                    <select name="include_payment_methods[]" class="form-control" multiple id="include_payment_methods">
                                                        <?php $__currentLoopData = $payment_methods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment_method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($payment_method->id); ?>">
                                                                <?php echo e($payment_method->name); ?>

                                                            </option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e(\Dotworkers\Configurations\Enums\Providers::$dotworkers); ?>">
                                                            <?php echo e(_i('Manual transactions')); ?>

                                                        </option>
                                                        <option value="<?php echo e(\Dotworkers\Configurations\Enums\Providers::$agents_users); ?>">
                                                            <?php echo e(_i('Agents transactions')); ?>

                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exclude_payment_methods"><?php echo e(_i('Exclude payment methods')); ?></label>
                                                    <select name="exclude_payment_methods[]" class="form-control" multiple id="exclude_payment_methods">
                                                        <?php $__currentLoopData = $payment_methods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment_method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($payment_method->id); ?>">
                                                                <?php echo e($payment_method->name); ?>

                                                            </option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                                            <input class="g-hidden-xs-up g-pos-abs g-top-0 g-left-0" name="bonus_type_awarded" id="fixed-bonus" type="radio"
                                                   value="<?php echo e(\Dotworkers\Bonus\Enums\BonusTypeAwarded::$fixed); ?>"
                                                <?php echo e($campaign->bonus_type_id == \Dotworkers\Bonus\Enums\BonusTypeAwarded::$fixed ? 'checked' : ''); ?>>
                                            <div class="u-check-icon-font g-absolute-centered--y g-left-0">
                                                <i class="fa" data-check-icon="" data-uncheck-icon=""></i>
                                            </div>
                                            <?php echo e(_i('Fixed bonus')); ?>

                                        </label>
                                    </div>
                                    <div class="form-group g-mb-10">
                                        <label class="u-check g-pl-25 <?php echo e(!in_array(\Dotworkers\Bonus\Enums\AllocationCriteria::$deposit, $campaign->data->allocation_criteria) ? 'disabled' : ''); ?>">
                                            <input class="g-hidden-xs-up g-pos-abs g-top-0 g-left-0" name="bonus_type_awarded" id="deposit-percentage" type="radio"
                                                   value="<?php echo e(\Dotworkers\Bonus\Enums\BonusTypeAwarded::$percentage); ?>"
                                                <?php echo e($campaign->data->bonus_type_awarded == \Dotworkers\Bonus\Enums\BonusTypeAwarded::$percentage ? 'checked' : ''); ?>

                                                <?php echo e(!in_array(\Dotworkers\Bonus\Enums\AllocationCriteria::$deposit, $campaign->data->allocation_criteria) ? 'disabled' : ''); ?>>
                                            <div class="u-check-icon-font g-absolute-centered--y g-left-0">
                                                <i class="fa" data-check-icon="" data-uncheck-icon=""></i>
                                            </div>
                                            <?php echo e(_i('Deposit percentage')); ?>

                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <div class="row">
                                        <div class="col-md-4 fixed-bonus <?php echo e($campaign->data->bonus_type_awarded != \Dotworkers\Bonus\Enums\BonusTypeAwarded::$fixed ? 'd-none' : ''); ?>">
                                            <div class="form-group">
                                                <label for="bonus"><?php echo e(_i('Bonus to be awarded')); ?></label>
                                                <input type="number" min="0" name="bonus" class="form-control" value="<?php echo e($campaign->data->bonus ?? ''); ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-4 deposit-percentage <?php echo e($campaign->data->bonus_type_awarded != \Dotworkers\Bonus\Enums\BonusTypeAwarded::$percentage ? 'd-none' : ''); ?>">
                                            <div class="form-group">
                                                <label for="percentage"><?php echo e(_i('Percentage')); ?></label>
                                                <input type="number" min="0" name="percentage" class="form-control" value="<?php echo e(isset($campaign->data->percentage) ? $campaign->data->percentage * 100 : ''); ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-4 deposit-percentage <?php echo e($campaign->data->bonus_type_awarded != \Dotworkers\Bonus\Enums\BonusTypeAwarded::$percentage ? 'd-none' : ''); ?>">
                                            <div class="form-group">
                                                <label for="limit"><?php echo e(_i('Limit to be awarded')); ?></label>
                                                <input type="number" min="0" name="limit" class="form-control" value="<?php echo e($campaign->data->limit ?? ''); ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="max_balance_convert"><?php echo e(_i('Maximum to convert into real balance')); ?></label>
                                                <input type="number" min="0" name="max_balance_convert" class="form-control" placeholder="<?php echo e(_i('Optional')); ?>" value="<?php echo e($campaign->data->max_balance_convert); ?>">
                                            </div>
                                        </div>
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
                                        <option value="yes" <?php echo e($campaign->data->rollovers ? 'selected' : ''); ?>>
                                            <?php echo e(_i('Yes')); ?>

                                        </option>
                                        <option value="no" <?php echo e(!$campaign->data->rollovers ? 'selected' : ''); ?>>
                                            <?php echo e(_i('No')); ?>

                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 rollovers-data <?php echo e(is_null($rollovers) ? 'd-none' : ''); ?>">
                                <div class="form-group">
                                    <label for="include_deposit"><?php echo e(_i('Rollover type')); ?></label>
                                    <select name="include_deposit" id="include_deposit" class="form-control">
                                        <option value="deposit" <?php echo e(!is_null($rollovers) && $rollovers->include_deposit ? 'selected' : ''); ?>>
                                            <?php echo e(_i('Deposit')); ?>

                                        </option>
                                        <option value="bonus" <?php echo e(!is_null($rollovers) && !$rollovers->include_deposit ? 'selected' : ''); ?>>
                                            <?php echo e(_i('Bonus')); ?>

                                        </option>
                                        <option value="both" <?php echo e(!is_null($rollovers) && is_null($rollovers->include_deposit) ? 'selected' : ''); ?>>
                                            <?php echo e(_i('Bonus + deposit')); ?>

                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 rollovers-data <?php echo e(is_null($rollovers) ? 'd-none' : ''); ?>">
                            <div class="form-group">
                                <label for="provider_type"><?php echo e(_i('Provider type')); ?></label>
                                <select name="provider_type" id="provider_type" data-route="<?php echo e(route('bonus-system.campaigns.exclude-providers')); ?>" class="form-control">
                                    <option value=""><?php echo e(_i('Select...')); ?></option>
                                    <?php $__currentLoopData = $provider_types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $providerType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($providerType->id); ?>" <?php echo e(!is_null($rollovers) && $rollovers->provider_type_id == $providerType->id ? 'selected' : ''); ?>>
                                            <?php echo e($providerType->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 rollovers-data <?php echo e(is_null($rollovers) ? 'd-none' : ''); ?>">
                            <div class="form-group">
                                <label for="exclude_providers"><?php echo e(_i('Exclude providers')); ?></label>
                                <select name="exclude_providers[]" id="exclude_providers" class="form-control" multiple>
                                    <option value=""><?php echo e(_i('Select...')); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 rollovers-data <?php echo e(is_null($rollovers) ? 'd-none' : ''); ?>">
                            <div class="form-group">
                                <label for="multiplier"><?php echo e(_i('Rollover multiplier')); ?></label>
                                <input type="number" name="multiplier" id="multiplier" class="form-control" value="<?php echo e(!is_null($rollovers) ? $rollovers->multiplier : ''); ?>">
                            </div>
                        </div>
                        <div class="col-md-4 rollovers-data <?php echo e(is_null($rollovers) ? 'd-none' : ''); ?>">
                            <div class="form-group">
                                <label for="days"><?php echo e(_i('Days to complete rollover')); ?></label>
                                <input type="text" name="days" id="days" class="form-control" value="<?php echo e(!is_null($rollovers) ? $rollovers->days : ''); ?>">
                            </div>
                        </div>
                        <div class="col-md-4 sports <?php echo e(!is_null($rollovers) ? $rollovers->provider_type_id != \Dotworkers\Configurations\Enums\ProviderTypes::$sportbook ? 'd-none' : '' : 'd-none'); ?>">
                            <div class="form-group">
                                <label for="bet_type"><?php echo e(_i('Bet type')); ?></label>
                                <select name="bet_type" id="bet_type" class="form-control">
                                    <option value="both" <?php echo e(isset($campaign->data->simple) && $campaign->data->simple == null ? 'selected' : ''); ?>>
                                        <?php echo e(_i('Simple and combined')); ?>

                                    </option>
                                    <option value="simple" <?php echo e(isset($campaign->data->simple) && $campaign->data->simple ? 'selected' : ''); ?>>
                                        <?php echo e(_i('Simple')); ?>

                                    </option>
                                    <option value="combined" <?php echo e(isset($campaign->data->simple) && !$campaign->data->simple ? 'selected' : ''); ?>>
                                        <?php echo e(_i('Combined')); ?>

                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 sports <?php echo e(!is_null($rollovers) ? $rollovers->provider_type_id != \Dotworkers\Configurations\Enums\ProviderTypes::$sportbook ? 'd-none' : '' : 'd-none'); ?>">
                            <div class="form-group">
                                <label for="odd"><?php echo e(_i('Quota')); ?></label>
                                <input type="text" name="odd" id="odd" class="form-control" value="<?php echo e($campaign->data->odd ?? ''); ?>">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="hidden" name="id" value="<?php echo e($campaign->id); ?>">
                                <input type="hidden" name="rollover_id" value="<?php echo e(!is_null($rollovers) ? $rollovers->id : ''); ?>">
                                <input type="hidden" name="parent_campaign" value="<?php echo e($campaign->parent_campaign); ?>">
                                <input type="hidden" name="version" id="version" value="<?php echo e($campaign->version); ?>">
                                <input type="hidden" name="original_campaign" id="original_campaign" value="<?php echo e($campaign->original_campaign); ?>">
                                <button type="submit" class="btn u-btn-3d u-btn-primary" id="update"
                                        data-loading-text="<i class='fa fa-spin fa-spinner'></i> <?php echo e(_i('Updating...')); ?>">
                                    <i class="hs-admin-upload"></i>
                                    <?php echo e(_i('Update campaign')); ?>

                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-10 <?php echo e(!is_null($campaign->original_campaign) ? '' : 'd-none'); ?>">
                <header
                    class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                    <div class="media">
                        <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                            <?php echo e(_i('Versions')); ?>

                        </h3>
                    </div>
                </header>
                <div class="card-block g-pa-15">
                    <label for="versions"><?php echo e(_i('Versions')); ?></label>
                    <select name="versions" id="versions" data-route="<?php echo e(route('bonus-system.campaigns.edit', [$campaign->id])); ?>" class="form-control">
                        <option value=""><?php echo e(_i('Select...')); ?></option>
                        <?php $__currentLoopData = $versions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $version): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($version->id_campaign); ?>">
                                <?php echo e($version->version); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
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
                                <option value="" <?php echo e(!isset($campaign->data->users_restriction_type) ? 'selected' : ''); ?>>
                                    <?php echo e(_i('None')); ?>

                                </option>
                                <option value="users" <?php echo e(isset($campaign->data->users_restriction_type) && $campaign->data->users_restriction_type == 'users' ? 'selected' : ''); ?>>
                                    <?php echo e(_i('Users')); ?>

                                </option>
                                <option value="segments" <?php echo e(isset($campaign->data->users_restriction_type) && $campaign->data->users_restriction_type == 'segments' ? 'selected' : ''); ?>>
                                    <?php echo e(_i('Segments')); ?>

                                </option>
                                <option value="excel" <?php echo e(isset($campaign->data->users_restriction_type) && $campaign->data->users_restriction_type == 'excel' ? 'selected' : ''); ?>>
                                    <?php echo e(_i('Excel')); ?>

                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="search-users <?php echo e(isset($campaign->data->users_restriction_type) && $campaign->data->users_restriction_type == 'users' ? '' : 'd-none'); ?>">
                            <div class="form-group">
                                <label for="include_users"><?php echo e(_i('Include users')); ?></label>
                                <select name="include_users[]" id="include_users" class="form-control select2" data-route="<?php echo e(route('users.search-username')); ?>" multiple>
                                    <option></option>
                                </select>
                            </div>
                        </div>
                        <div class="search-users <?php echo e(isset($campaign->data->users_restriction_type) && $campaign->data->users_restriction_type == 'users' ? '' : 'd-none'); ?>">
                            <div class="form-group">
                                <label for="exclude_users"><?php echo e(_i('Exclude users')); ?></label>
                                <select name="exclude_users[]" id="exclude_users" class="form-control select2" data-route="<?php echo e(route('users.search-username')); ?>" multiple>
                                    <option></option>
                                </select>
                            </div>
                        </div>
                        <div class="search-segments <?php echo e(isset($campaign->data->users_restriction_type) && $campaign->data->users_restriction_type == 'segments' ? '' : 'd-none'); ?>">
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
                        <div class="search-segments <?php echo e(isset($campaign->data->users_restriction_type) && $campaign->data->users_restriction_type == 'segments' ? '' : 'd-none'); ?>">
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
                        <div class="search-excel <?php echo e(isset($campaign->data->users_restriction_type) && $campaign->data->users_restriction_type == 'excel' ? '' : 'd-none'); ?>">
                            <div class="form-group">
                                <label for="include_excel"><?php echo e(_i('Include excel')); ?></label>
                                <input type="file" name="include_excel" id="include_excel" class="opacity-0">
                            </div>
                        </div>
                        <div class="search-excel  <?php echo e(isset($campaign->data->users_restriction_type) && $campaign->data->users_restriction_type == 'excel' ? '' : 'd-none'); ?>">
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
            let bonusSystem = new BonusSystem()
            bonusSystem.addTranslations(<?php echo json_encode($languages, 15, 512) ?>);
            bonusSystem.setTranslations(<?php echo json_encode($languages, 15, 512) ?>, <?php echo json_encode($campaign->translations, 15, 512) ?>);
            bonusSystem.versions();

            <?php if(isset($campaign->include_users)): ?>
            bonusSystem.fillSelects('#include_users', <?php echo json_encode($campaign->include_users, 15, 512) ?>)
            <?php endif; ?>

            <?php if(isset($campaign->exclude_users)): ?>
            bonusSystem.fillSelects('#exclude_users', <?php echo json_encode($campaign->exclude_users, 15, 512) ?>)
            <?php endif; ?>

            <?php if(isset($campaign->include_segments)): ?>
            bonusSystem.fillSelects('#include_segments', <?php echo json_encode($campaign->include_segments, 15, 512) ?>)
            <?php endif; ?>

            <?php if(isset($campaign->exclude_segments)): ?>
            bonusSystem.fillSelects('#exclude_segments', <?php echo json_encode($campaign->exclude_segments, 15, 512) ?>)
            <?php endif; ?>

            <?php if(isset($campaign->include_payment_methods)): ?>
            bonusSystem.fillSelects('#include_payment_methods', <?php echo json_encode($campaign->include_payment_methods, 15, 512) ?>)
            <?php endif; ?>

            <?php if(isset($campaign->exclude_payment_methods)): ?>
            bonusSystem.fillSelects('#exclude_payment_methods', <?php echo json_encode($campaign->exclude_payment_methods, 15, 512) ?>)
            <?php endif; ?>

            <?php if(isset($campaign->exclude_provider_bet)): ?>
            bonusSystem.fillSelects('#exclude_providers_bet', <?php echo json_encode($campaign->exclude_provider_bet, 15, 512) ?>)
            <?php endif; ?>

            bonusSystem.update(<?php echo json_encode($languages, 15, 512) ?>, <?php echo json_encode($campaign->promo_codes, 15, 512) ?>);
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('back.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
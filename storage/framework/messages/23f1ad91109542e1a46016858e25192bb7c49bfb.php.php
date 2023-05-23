

<?php $__env->startSection('content'); ?>
    <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
        <header
            class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
            <div class="media">
                <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                    <?php echo e($title); ?>

                </h3>
                <div class="media-body d-flex justify-content-end">
                    <a href="<?php echo e(route('betpay.clients.accounts')); ?>" class="btn u-btn-3d u-btn-primary float-right">
                        <i class="hs-admin-layout-list-thumb"></i>
                        <?php echo e(_i('Go to list')); ?>

                    </a>
                </div>
            </div>
        </header>
        <div class="card-block g-pa-15">
            <form id="client-account-form" method="post" action="<?php echo e(route('betpay.clients.accounts.update-client-account')); ?>">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="currency"><?php echo e(_i('Currency')); ?></label>
                            <select name="currency" id="currency" class="form-control">
                                <option value=""><?php echo e(_i('Select...')); ?></option>
                                <?php $__currentLoopData = $currency_client; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($currency->iso); ?>" <?php echo e($currency->iso == $client->currency_iso ? 'selected' : ''); ?>>
                                        <?php echo e($currency->iso == 'VEF' ? $free_currency->currency_name : $currency->iso . " ({$currency->name})"); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="payment"><?php echo e(_i('Payment methods')); ?></label>
                            <select name="payments" id="payments" class="form-control">
                                <option value=""><?php echo e(_i('Select...')); ?></option>
                                <?php $__currentLoopData = $payment_methods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($payment->id); ?>" <?php echo e($payment->id == $client->payment_method_id ? 'selected' : ''); ?> data-account-required="<?php echo e($payment->account_required); ?>">
                                        <?php echo e($payment->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="status"><?php echo e(_i('Status')); ?></label>
                            <select name="status" id="status" class="form-control">
                                <option value="true" <?php echo e('true' == $client->status ?? 'selected'); ?>><?php echo e(_i('Active')); ?></option>
                                <option value="false" <?php echo e('false' == $client->status ?? 'selected'); ?>><?php echo e(_i('Inactive')); ?></option>
                            </select>
                        </div>
                    </div>
                    <?php if($client->payment_method_id == \Dotworkers\Configurations\Enums\PaymentMethods::$zelle || $client->payment_method_id == \Dotworkers\Configurations\Enums\PaymentMethods::$paypal
                        || $client->payment_method_id == \Dotworkers\Configurations\Enums\PaymentMethods::$skrill || $client->payment_method_id == \Dotworkers\Configurations\Enums\PaymentMethods::$neteller
                        || $client->payment_method_id == \Dotworkers\Configurations\Enums\PaymentMethods::$airtm
                        || $client->payment_method_id == \Dotworkers\Configurations\Enums\PaymentMethods::$uphold): ?>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="email"><?php echo e(_i('Email')); ?></label>
                            <input type="email" name="account_email" id="account_email" class="form-control" autocomplete="off" value="<?php echo e($client->data->email); ?>">
                        </div>
                    </div>
                        <?php if($client->payment_method_id == \Dotworkers\Configurations\Enums\PaymentMethods::$zelle): ?>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="first_name"><?php echo e(_i('First name')); ?></label>
                                    <input type="text" name="first_name" id="first_name" class="form-control" autocomplete="off" value="<?php echo e($client->data->first_name); ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="last_name"><?php echo e(_i('Last name')); ?></label>
                                    <input type="text" name="last_name" id="last_name" class="form-control" autocomplete="off" value="<?php echo e($client->data->last_name); ?>">
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if($client->payment_method_id == \Dotworkers\Configurations\Enums\PaymentMethods::$wire_transfers || $client->payment_method_id == \Dotworkers\Configurations\Enums\PaymentMethods::$ves_to_usd): ?>
                        <input type="hidden" name="old_bank_id" id="back" value="<?php echo e($client->data->bank_id); ?>">
                        <input type="hidden" name="old_bank_name" value="<?php echo e($client->data->bank_name); ?>">
                        <input type="hidden" name="payments" id="payments" value="<?php echo e($client->payment_method_id); ?>">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="country"><?php echo e(_i('Country')); ?></label>
                                <select name="country" class="form-control country"  data-route="<?php echo e(route('betpay.banks.data')); ?>">
                                    <option value=""><?php echo e(_i('Select...')); ?></option>
                                    <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($country->iso); ?>">
                                            <?php echo e($country->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="back"><?php echo e(_i('Bank ')); ?></label>
                                <select name="bank" class="form-control select2 bank">
                                    <option value=""><?php echo e(_i('Select ...')); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="account_number"><?php echo e(_i('Account number')); ?></label>
                                <input type="text" name="account_number" class="form-control" autocomplete="off" value="<?php echo e($client->data->account_number); ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="account_type"><?php echo e(_i('Account type')); ?></label>
                                <select name="account_type" class="form-control">
                                    <option value=""><?php echo e(_i('Select...')); ?></option>
                                    <option value="C" <?php echo e('C' == $client->data->account_type ? 'selected' : ''); ?>><?php echo e(_i('Current')); ?></option>
                                    <option value="S" <?php echo e('S' == $client->data->account_type ? 'selected' : ''); ?>><?php echo e(_i('Saving')); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="account_type"><?php echo e(_i('Social reasons')); ?></label>
                                <input type="text" name="social_reason" class="form-control" autocomplete="off" value="<?php echo e($client->data->social_reason); ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="dni"><?php echo e(_i('DNI')); ?></label>
                                <input name="account_dni" class="form-control" type="text" autocomplete="off" value="<?php echo e($client->data->dni); ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="title"><?php echo e(_i('Title')); ?></label>
                                <input type="text" name="title" class="form-control" autocomplete="off" value="<?php echo e($client->data->title); ?>">
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if($client->payment_method_id == \Dotworkers\Configurations\Enums\PaymentMethods::$cryptocurrencies): ?>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="crypto_wallet"><?php echo e(_i('Wallet ')); ?></label>
                                <input id="crypto_wallet" name="crypto_wallet" class="form-control" type="text" autocomplete="off" value="<?php echo e($client->data->wallet); ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="crypto_currencies"><?php echo e(_i('Criptocurrency')); ?></label>
                                <select name="crypto_currencies" class="form-control">
                                    <option value=""><?php echo e(_i('Select...')); ?></option>
                                    <option value="BTC" <?php echo e('BTC' == $client->data->cryptocurrency ? 'selected' : ''); ?>><?php echo e(_i('BTC')); ?></option>
                                    <option value="USDT" <?php echo e('USDT' == $client->data->cryptocurrency ? 'selected' : ''); ?>><?php echo e(_i('USDT')); ?></option>
                                </select>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if($client->payment_method_id == \Dotworkers\Configurations\Enums\PaymentMethods::$just_pay): ?>
                        <div class="col-md-4 d-none alps">
                            <div class="form-group">
                                <label for="public_key"><?php echo e(_i('Public key ')); ?></label>
                                <input id="public_key" name="public_key" class="form-control" type="text" autocomplete="off" value="<?php echo e($client->data->public_key); ?>">
                            </div>
                        </div>
                        <div class="col-md-4 d-none alps">
                            <div class="form-group">
                                <label for="secret_key"><?php echo e(_i('Secret key')); ?></label>
                                <input id="secret_key" name="secret_key" class="form-control" type="text" autocomplete="off" value="<?php echo e($client->data->secret_key); ?>">
                            </div>
                        </div>
                        <div class="col-md-4 d-none alps">
                            <div class="form-group">
                                <label for="username"><?php echo e(_i('Username')); ?></label>
                                <input id="username" name="username" class="form-control" type="text" autocomplete="off" value="<?php echo e($client->data->username); ?>">
                            </div>
                        </div>
                        <div class="col-md-4 d-none alps">
                            <div class="form-group">
                                <label for="password"><?php echo e(_i('Password')); ?></label>
                                <input id="password" name="password" class="form-control" type="password" autocomplete="off" value="<?php echo e($client->data->password); ?>">
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if($client->payment_method_id == \Dotworkers\Configurations\Enums\PaymentMethods::$vcreditos_api): ?>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="vcreditos_user"><?php echo e(_i('Vcreditos user ')); ?></label>
                                <input id="vcreditos_user" name="vcreditos_user" class="form-control" type="text" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="vcreditos_secure_id"><?php echo e(_i('Vcreditos secure')); ?></label>
                                <input id="vcreditos_secure_id" name="vcreditos_secure_id" class="form-control" type="text" autocomplete="off">
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="col-md-12">
                        <input type="hidden" name="client_account" id="client_account" value="<?php echo e($client->id); ?>">
                        <input type="hidden" name="payments" id="payments" value="<?php echo e($client->payment_method_id); ?>">
                        <div class="form-group">
                            <button type="button" class="btn u-btn-3d u-btn-primary" id="update"
                                    data-loading-text="<i class='fa fa-spin fa-spinner'></i> <?php echo e(_i('Updating...')); ?>">
                                <i class="hs-admin-reload"></i>
                                <?php echo e(_i('Update')); ?>

                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        $(function () {
            let betpay = new BetPay();
            betpay.banksData();
            betpay.updateClientAccount();
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('back.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
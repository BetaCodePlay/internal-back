

<?php $__env->startSection('content'); ?>
        <div class="row">
            <div class="col-md-12">
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                    <header
                        class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                <?php echo e($title); ?>

                            </h3>
                            <div class="media-body d-flex justify-content-end">
                                <a href="<?php echo e(route('betpay.clients.index')); ?>" class="btn u-btn-3d u-btn-primary float-right">
                                    <i class="hs-admin-layout-list-thumb"></i>
                                    <?php echo e(_i('Go to list')); ?>

                                </a>
                            </div>
                        </div>
                    </header>
                    <div class="card-block g-pa-15">
                        <form action="<?php echo e(route('betpay.clients.store-payment-method')); ?>" id="clients-payment-methods-form" method="post">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="client"><?php echo e(_i('Whitelabel')); ?></label>
                                        <select name="client" id="client" class="form-control">
                                            <option value=""><?php echo e(_i('Select...')); ?></option>
                                            <?php $__currentLoopData = $whitelabels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $whitelabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($whitelabel->id); ?>">
                                                    <?php echo e($whitelabel->description); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="currency"><?php echo e(_i('Currency')); ?></label>
                                        <select name="currency" id="currency" class="form-control">
                                            <option value=""><?php echo e(_i('Select...')); ?></option>
                                            <?php $__currentLoopData = $currency_client; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($currency->iso); ?>">
                                                    <?php echo e($currency->iso == 'VEF' ? $free_currency->currency_name : $currency->iso . " ({$currency->name})"); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="payments"><?php echo e(_i('Payment methods')); ?></label>
                                        <select name="payments" class="form-control" id="payments">
                                            <option value=""><?php echo e(_i('Select...')); ?></option>
                                            <?php $__currentLoopData = $payment_methods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($payment->id); ?>" data-account-required="<?php echo e($payment->account_required); ?>">
                                                    <?php echo e($payment->name); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="payments"><?php echo e(_i('Transaction type')); ?></label>
                                        <div class="form-group">
                                            <select name="transaction_type" id="transaction_type" class="form-control">
                                                <option value=""><?php echo e(_i('Credit and debit')); ?></option>
                                                <option value="<?php echo e(\Dotworkers\Configurations\Enums\TransactionTypes::$credit); ?>"><?php echo e(_i('Credit')); ?></option>
                                                <option value="<?php echo e(\Dotworkers\Configurations\Enums\TransactionTypes::$debit); ?>"><?php echo e(_i('Debit')); ?></option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-none email-account col-md-4">
                                    <div class="form-group">
                                        <label for="email"><?php echo e(_i('Email')); ?></label>
                                        <input type="email" name="account_email" id="account_email" class="form-control" autocomplete="off">
                                    </div>
                                </div>
                                <div class="d-none full-name col-md-4">
                                    <div class="form-group">
                                        <label for="first_name"><?php echo e(_i('First name')); ?></label>
                                        <input type="text" name="first_name" id="first_name" class="form-control" autocomplete="off">
                                    </div>
                                </div>
                                <div class="d-none full-name col-md-4">
                                    <div class="form-group">
                                        <label for="last_name"><?php echo e(_i('Last name')); ?></label>
                                        <input type="text" name="last_name" id="last_name" class="form-control" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-4 d-none wire-transfers">
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
                                <div class="col-md-4 d-none wire-transfers">
                                    <div class="form-group">
                                        <label for="back"><?php echo e(_i('Bank ')); ?></label>
                                        <select name="bank" class="form-control select2 bank">
                                            <option value=""><?php echo e(_i('Select ...')); ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 d-none wire-transfers">
                                    <div class="form-group">
                                        <label for="account_number"><?php echo e(_i('Account number')); ?></label>
                                        <input type="text" name="account_number" class="form-control" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-4 d-none wire-transfers">
                                    <div class="form-group">
                                        <label for="account_type"><?php echo e(_i('Account type')); ?></label>
                                        <select name="account_type" class="form-control">
                                            <option value=""><?php echo e(_i('Select...')); ?></option>
                                            <option value="C"><?php echo e(_i('Current')); ?></option>
                                            <option value="S"><?php echo e(_i('Saving')); ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 d-none wire-transfers">
                                    <div class="form-group">
                                        <label for="account_type"><?php echo e(_i('Social reasons')); ?></label>
                                        <input type="text" name="social_reason" class="form-control" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-4 d-none wire-transfers">
                                    <div class="form-group">
                                        <label for="dni"><?php echo e(_i('DNI')); ?></label>
                                        <input name="account_dni" class="form-control" type="text" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-4 d-none wire-transfers">
                                    <div class="form-group">
                                        <label for="title"><?php echo e(_i('Title')); ?></label>
                                        <input type="text" name="title" class="form-control" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-4 d-none criptocurrency">
                                    <div class="form-group">
                                        <label for="crypto_wallet"><?php echo e(_i('Wallet ')); ?></label>
                                        <input id="crypto_wallet" name="crypto_wallet" class="form-control" type="text" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-4 d-none criptocurrency">
                                    <div class="form-group">
                                        <label for="crypto_currencies"><?php echo e(_i('Criptocurrency')); ?></label>
                                        <select name="crypto_currencies" class="form-control">
                                            <option value=""><?php echo e(_i('Select...')); ?></option>
                                            <option value="BTC"><?php echo e(_i('BTC')); ?></option>
                                            <option value="USDT"><?php echo e(_i('USDT')); ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 d-none alps">
                                    <div class="form-group">
                                        <label for="public_key"><?php echo e(_i('Public key ')); ?></label>
                                        <input id="public_key" name="public_key" class="form-control" type="text" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-4 d-none alps">
                                    <div class="form-group">
                                        <label for="secret_key"><?php echo e(_i('Secret key')); ?></label>
                                        <input id="secret_key" name="secret_key" class="form-control" type="text" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-4 d-none alps">
                                    <div class="form-group">
                                        <label for="username"><?php echo e(_i('Username')); ?></label>
                                        <input id="username" name="username" class="form-control" type="text" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-4 d-none alps">
                                    <div class="form-group">
                                        <label for="password"><?php echo e(_i('Password')); ?></label>
                                        <input id="password" name="password" class="form-control" type="password" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-4 d-none vcreditos_api">
                                    <div class="form-group">
                                        <label for="vcreditos_user"><?php echo e(_i('Vcreditos user ')); ?></label>
                                        <input id="vcreditos_user" name="vcreditos_user" class="form-control" type="text" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-4 d-none vcreditos_api">
                                    <div class="form-group">
                                        <label for="vcreditos_secure_id"><?php echo e(_i('Vcreditos secure')); ?></label>
                                        <input id="vcreditos_secure_id" name="vcreditos_secure_id" class="form-control" type="text" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="button" class="btn u-btn-3d u-btn-primary" id="save"
                                                data-loading-text="<i class='fa fa-spin fa-spinner'></i> <?php echo e(_i('Saving...')); ?>">
                                            <i class="hs-admin-save"></i>
                                            <?php echo e(_i('Save')); ?>

                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        $(function () {
            let betpay = new BetPay();
            betpay.storeClientsPaymentMethod();
            betpay.accountRequired();
            betpay.banksData();
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('back.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
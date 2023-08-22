

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
            <form id="client-account-form" method="post" action="<?php echo e(route('betpay.clients.accounts.store')); ?>" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="currency"><?php echo e(_i('Currency')); ?></label>
                            <select name="currency" id="currency" class="form-control">
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
                            <label for="payment"><?php echo e(_i('Payment methods')); ?></label>
                            <select name="payments" id="payments" class="form-control">
                                <option value=""><?php echo e(_i('Select...')); ?></option>
                                <?php $__currentLoopData = $payment_methods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($payment->id); ?>">
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
                    <?php echo $__env->make('back.betpay.clients.payment-methods.binance', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <?php echo $__env->make('back.betpay.clients.payment-methods.cryptocurrency', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <?php echo $__env->make('back.betpay.clients.payment-methods.paypal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <?php echo $__env->make('back.betpay.clients.payment-methods.mercado-pago', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="submit" class="btn u-btn-3d u-btn-primary" id="save"
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        $(function () {
            let betpay = new BetPay();
            betpay.changeClientAccount();
            betpay.storeAccountClient();
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('back.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
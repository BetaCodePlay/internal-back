<div class="modal fade" id="process-debit-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?php echo e(route('betpay.paypal.process-debit')); ?>" method="post" id="process-debit-form">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo e(_i('Process debit')); ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="action"><?php echo e(_i('Action')); ?></label>
                        <select name="action" id="action" class="form-control">
                            <option value=""><?php echo e(_i('Select...')); ?></option>
                            <option value="1"><?php echo e(_i('Approve')); ?></option>
                            <option value="0"><?php echo e(_i('Reject')); ?></option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="description"><?php echo e(_i('Description')); ?></label>
                        <textarea name="description" id="description" cols="30" rows="5" class="form-control"></textarea>
                        <small class="form-text text-muted">
                            <?php echo e(_i('This description will be shown to the user')); ?>

                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="transaction" id="transaction">
                    <input type="hidden" name="wallet" id="wallet">
                    <input type="hidden" name="user" id="user">
                    <input type="hidden" name="payment_method" value="<?php echo e(\Dotworkers\Configurations\Enums\PaymentMethods::$paypal); ?>">
                    <input type="hidden" name="provider" value="<?php echo e(\Dotworkers\Configurations\Enums\Providers::$paypal); ?>">
                    <button type="button" class="btn u-btn-primary u-btn-3d" id="process-debit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> <?php echo e(_i('Please wait...')); ?>">
                        <?php echo e(_i('Process')); ?>

                    </button>
                    <button type="button" class="btn u-btn-3d u-btn-bluegray u-btn-3d" data-dismiss="modal">
                        <?php echo e(_i('Close')); ?>

                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

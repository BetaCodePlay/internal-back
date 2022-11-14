<div class="modal fade" id="manual-adjustments-bonus-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo e(_i('Manual adjustment bonus')); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?php echo e(route('bonus-system.campaigns.users.manual-adjustments')); ?>" method="post" id="manual-adjustments-bonus-form">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="wallet"><?php echo e(_i('Bonus wallets')); ?> (<?php echo e(session('currency')); ?>)</label>
                        <select name="wallet" id="wallet" class="form-control">
                            <option value=""><?php echo e(_i('Select...')); ?></option>
                            <?php if(isset($wallets_bonuses)): ?>
                                <?php $__currentLoopData = $wallets_bonuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bonusWallet): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($bonusWallet->id); ?>">
                                        <?php echo e($bonusWallet->provider_type); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="amount"><?php echo e(_i('Amount')); ?></label>
                        <input type="number" name="amount" class="form-control" min="0">
                    </div>
                    <div class="form-group">
                        <label for="transaction_type"><?php echo e(_i('Transaction type')); ?></label>
                        <select name="transaction_type" class="form-control">
                            <option value=""><?php echo e(_i('Select...')); ?></option>
                            <option value="<?php echo e(\Dotworkers\Configurations\Enums\TransactionTypes::$credit); ?>">
                                <?php echo e(_i('Credit')); ?>

                            </option>
                            <option value="<?php echo e(\Dotworkers\Configurations\Enums\TransactionTypes::$debit); ?>">
                                <?php echo e(_i('Debit')); ?>

                            </option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="description"><?php echo e(_i('Description')); ?></label>
                        <textarea name="description" cols="30" rows="5"
                                  class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="user" class="user" value="<?php echo e(isset($user) ? $user->id : ''); ?>">
                    <button type="button" class="btn u-btn-primary u-btn-3d" id="manual-adjustment-bonus" data-loading-text="<?php echo e(_i('Please wait...')); ?>">
                        <?php echo e(_i('Accept')); ?>

                    </button>
                    <button type="button" class="btn u-btn-3d u-btn-bluegray u-btn-3d" data-dismiss="modal">
                        <?php echo e(_i('Close')); ?>

                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

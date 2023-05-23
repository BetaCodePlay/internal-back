<div class="modal fade" id="unlock-balance-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo e(_i('Unlock balance')); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?php echo e(route('users.unlock-balance')); ?>" method="post" id="unlock-balance-form">
                <div class="modal-body">
                    <h6>
                        <?php echo e(_i('Are you sure you want to unlock the balance?')); ?>

                    </h6>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="unlock_wallet" id="unlock_wallet" value="<?php echo e(isset($wallet) ? $wallet->id : ''); ?>">
                    <input type="hidden" name="user_id" class="user_id" value="<?php echo e(isset($user) ? $user->id : ''); ?>">
                    <button type="button" class="btn u-btn-primary u-btn-3d" id="unclock" data-loading-text="<?php echo e(_i('Please wait...')); ?>">
                        <?php echo e(_i('Unlock')); ?>

                    </button>
                    <button type="button" class="btn u-btn-3d u-btn-bluegray u-btn-3d" data-dismiss="modal">
                        <?php echo e(_i('Close')); ?>

                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

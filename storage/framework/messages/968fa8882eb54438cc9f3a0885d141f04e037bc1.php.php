<div class="modal fade" id="activation-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo e(_i('Activation')); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?php echo e(route('users.activate-temp')); ?>" method="post" id="active-form">
                <div class="modal-body">
                    <h6>
                        <?php echo e(_i('Activation of account')); ?>

                    </h6>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="user_name" id="user_name">
                    <input type="hidden" name="email_user" id="email_user">
                    <button type="button" class="btn u-btn-primary u-btn-3d" id="send-activation" data-loading-text="<?php echo e(_i('Please wait...')); ?>">
                        <?php echo e(_i('Send')); ?>

                    </button>
                    <button type="button" class="btn u-btn-3d u-btn-bluegray u-btn-3d" data-dismiss="modal">
                        <?php echo e(_i('Close')); ?>

                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
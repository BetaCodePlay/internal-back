<div class="modal fade" id="send-email-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo e(_i('Send email')); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?php echo e(route('users.resend-activate-email')); ?>" method="post" id="resend-active-form">
                <div class="modal-body">
                    <h6>
                        <?php echo e(_i('Send email for activation of account')); ?>

                    </h6>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="username" id="username" value="">
                    <input type="hidden" name="email" id="email" value="">
                    <button type="button" class="btn u-btn-primary u-btn-3d" id="send-email" data-loading-text="<?php echo e(_i('Please wait...')); ?>">
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
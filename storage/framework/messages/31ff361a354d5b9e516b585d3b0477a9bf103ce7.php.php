<div class="modal fade" id="change-password">
    <div class="modal-dialog">
        <div class="modal-content bg-darkgray">
            <form action="<?php echo e(route('auth.change-password')); ?>" method="post" id="change-password-form">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo e(_i('Change password')); ?></h5>
                    <button type="button" class="close btn-color-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    
                    <div class="form-group">
                        <label for="newPassword"><?php echo e(_i('New Password')); ?></label>
                        <input type="password" class="form-control" name="newPassword" id="newPassword">
                        <span class="info-change-password">Mínimo 8 caracteres, 1 letra y 1 número.</span>
                    </div>
                    <div class="form-group">
                        <label for="repeatNewPassword"><?php echo e(_i('Repeat New Password')); ?></label>
                        <input type="password" class="form-control" name="repeatNewPassword" id="repeatNewPassword">
                        
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="pUsername" id="pUsername">
                    <input type="hidden" name="oldPassword" id="oldPassword">
                    <button type="button" class="btn u-btn-primary u-btn-3d btn-color-gradient" id="update-button" data-loading-text="<i class='fa fa-spin fa-spinner'></i> <?php echo e(_i('Please wait...')); ?>">
                        <?php echo e(_i('Update')); ?>

                    </button>
                    
                </div>
            </form>
        </div>
    </div>
</div>


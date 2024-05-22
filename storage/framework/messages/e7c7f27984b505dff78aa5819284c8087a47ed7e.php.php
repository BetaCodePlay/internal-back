<div class="modal fade modal-style" id="role-password-reset">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo e(_i('Reset password')); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><span class="font-weight-bold text-form"><?php echo e(_i('Are you sure you want to reset the agent key?')); ?></span> <span class="username-form"></span></p>
                <p><?php echo e(_i('Check for security that the user is correct, when logging in the agent will be able to enter a new password.')); ?></p>
                <form class="form" autocomplete="destroy">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label><?php echo e(_i('Password')); ?></label>
                                <div class="wrap-input validate-input" data-validate="<?php echo e(_i('Enter password')); ?>">
						            <span class="btn-show-pass">
							            <i class="fa fa-eye-slash"></i>
						            </span>
                                    <input class="form-control" type="password" id="password-role-reset" autocomplete="off" placeholder="<?php echo e(_i('At least 8 characters')); ?>" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-transparent" data-dismiss="modal">
                    <?php echo e(_i('Cancel')); ?>

                </button>
                <button type="button" class="btn btn-theme resetUserPassword" data-route="<?php echo e(route('request.password')); ?>" data-loading-text="<i class='fa fa-spin fa-spinner'></i> <?php echo e(_i('Resetting password')); ?>...">
                    <?php echo e(_i('Perfect! reset key')); ?>

                </button>
            </div>
        </div>
    </div>
</div>

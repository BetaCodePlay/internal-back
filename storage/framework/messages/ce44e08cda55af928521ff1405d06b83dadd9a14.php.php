<div class="modal fade" id="test-mail-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?php echo e(route('email-templates.test-email')); ?>" method="post" id="test-email-form">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo e(_i('Test email')); ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="email"><?php echo e(_i('Email')); ?></label>
                        <input type="email" class="form-control" name="email" id="email" autocomplete="off">
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="template_id" id="template_id">
                    <button type="button" class="btn u-btn-primary u-btn-3d" id="test-button" data-loading-text="<i class='fa fa-spin fa-spinner'></i> <?php echo e(_i('Testing...')); ?>">
                        <i class="hs-admin-email"></i>
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
<?php

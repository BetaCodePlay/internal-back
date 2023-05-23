<!-- <div class="modal fade" id="permission-user-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo e(_i('Relate Permissions to User')); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?php echo e(route('security.manage-store-permissions')); ?>" method="post" id="permission-user-form">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="permissions"><?php echo e(_i('Permission')); ?></label>
                        <select name="permissions[]" id="permissions" class="form-control" multiple='multiple'>
                        <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($permission->id); ?>">
                                <?php echo e($permission->description); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="user" value="<?php echo e($user->id); ?>">
                    <button type="button" class="btn u-btn-primary u-btn-3d" id="permission-user"
                            data-loading-text="<i class='fa fa-spin fa-spinner'></i> <?php echo e(_i('Please wait...')); ?>">
                        <?php echo e(_i('Accept')); ?>

                    </button>
                    <button type="button" class="btn u-btn-3d u-btn-bluegray u-btn-3d" data-dismiss="modal">
                        <?php echo e(_i('Close')); ?>

                    </button>
                </div>
            </form>
        </div>
    </div>
</div> -->

<div class="modal fade modal-edit" id="edit-permission-users">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo e(_i('Edit Permissions to User')); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?php echo e(route('security.manage-store-permissions-users')); ?>" method="post" id="permission-user-form">
                <div class="modal-body">
                <div class="row">    
                    <div class="col-md-3">
                                <div class="form-group">
                                <label for="useredit"><?php echo e(_i('User')); ?></label>
                                    <select class="form-control useredit" id="useredit" name="users[]" multiple disabled>
                                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($user->id); ?>" >
                                                <?php echo e($user->username); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <input type="hidden" id="hiddenuser" name="users[]" value="" />
                                    </select>
                                </div>
                    </div>
                    <div class="col-md-3">
                                <div class="form-group">
                                    <label for="permissionsedit"><?php echo e(_i('Permissions')); ?></label>
                                    <select name="permissions[]" id="permissionsedit" class="form-control permissionsedit" multiple>
                                        <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($permission->id); ?>" >
                                                <?php echo e($permission->description); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                    </div>
                </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn u-btn-primary u-btn-3d" id="permission-edit-user"
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
</div>

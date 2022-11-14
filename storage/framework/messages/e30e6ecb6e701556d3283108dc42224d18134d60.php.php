<div class="modal fade" id="add-segmentations-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo e(_i('Add to segment')); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?php echo e(route('segments.add-user')); ?>" method="post" id="segment-user-form">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="client"><?php echo e(_i('Segments')); ?></label>
                        <select name="segments" id="segments" class="form-control">
                            <option value=""><?php echo e(_i('Select...')); ?></option>
                            <?php $__currentLoopData = $segments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $segment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($segment->id); ?>">
                                    <?php echo e($segment->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="user" class="user" value="<?php echo e(isset($user) ? $user->id : ''); ?>">
                    <button type="button" class="btn u-btn-primary u-btn-3d" id="add-user" data-loading-text="<?php echo e(_i('Please wait...')); ?>">
                        <?php echo e(_i('Add')); ?>

                    </button>
                    <button type="button" class="btn u-btn-3d u-btn-bluegray u-btn-3d" data-dismiss="modal">
                        <?php echo e(_i('Close')); ?>

                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

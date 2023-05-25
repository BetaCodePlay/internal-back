<div class="modal fade" id="add-bonus-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo e(_i('Activate bonus')); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?php echo e(route('bonus-system.campaigns.users.add')); ?>" method="post" id="campaigns-user-form">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="campaigns"><?php echo e(_i('Campaigns')); ?></label>
                        <select name="campaigns" id="campaigns" class="form-control">
                            <option value=""><?php echo e(_i('Select...')); ?></option>
                            <?php if(isset($campaigns)): ?>
                                <?php $__currentLoopData = $campaigns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $campaign): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($campaign->id); ?>">
                                        <?php echo e($campaign->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="user" class="user" value="<?php echo e(isset($user) ? $user->id : ''); ?>">
                    <button type="submit" class="btn u-btn-primary u-btn-3d" id="add-user" data-loading-text="<?php echo e(_i('Please wait...')); ?>">
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

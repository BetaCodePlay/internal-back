<div class="modal fade" id="bonus-transaction-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo e(_i('Assign bonus')); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?php echo e(route('users.bonus-transactions')); ?>" method="post" id="bonus-transactions-form">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="amount"><?php echo e(_i('Amount')); ?></label>
                        <input type="number" name="amount" id="amount" class="form-control" min="0">
                    </div>
                    <div class="form-group">
                        <label for="allocation_criteria"><?php echo e(_i('Allocation criteria')); ?></label>
                        <select name="allocation_criteria" id="allocation_criteria" class="form-control">
                            <option value=""><?php echo e(_i('Select...')); ?></option>
                            <?php $__currentLoopData = $allocation_criteria; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $criteria): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($criteria->id == \Dotworkers\Bonus\Enums\AllocationCriteria::$welcome_bonus_without_deposit || $criteria->id == \Dotworkers\Bonus\Enums\AllocationCriteria::$bonus_code || $criteria->id == \Dotworkers\Bonus\Enums\AllocationCriteria::$welcome_bonus_with_deposit || $criteria->id == \Dotworkers\Bonus\Enums\AllocationCriteria::$bonus_code_with_deposit): ?>
                                <option value="<?php echo e($criteria->id); ?>">
                                    <?php echo e($criteria->name); ?>

                                </option>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="description"><?php echo e(_i('Description')); ?></label>
                        <textarea name="description" id="description" cols="30" rows="5" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="user" value="<?php echo e($user->id); ?>">
                    <input type="hidden" name="wallet" value="<?php echo e($wallet->id); ?>">
                    <button type="button" class="btn u-btn-primary u-btn-3d" id="bonus-transactions"
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

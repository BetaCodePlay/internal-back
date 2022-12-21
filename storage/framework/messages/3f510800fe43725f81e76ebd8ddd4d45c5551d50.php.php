<div class="modal fade" id="store-segments-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo e(_i('Segment')); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?php echo e(route('segments.store')); ?>" method="post" id="segments-form">
                <div class="modal-body">
                    <input type="hidden" name="users_id" class="users_id">
                    <div class="form-group">
                        <label for="name"><?php echo e(_i('Name')); ?></label>
                        <input type="text" name="name" id="name" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="description"><?php echo e(_i('Description')); ?></label>
                        <textarea name="description" id="description" cols="30" rows="5"
                                  class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="users" id="users">
                    <input type="hidden" name="filter" id="filter">
                    <button type="button" class="btn u-btn-primary u-btn-3d" id="store"
                            data-loading-text="<?php echo e(_i('Please wait...')); ?>">
                        <?php echo e(_i('Save')); ?>

                    </button>
                    <button type="button" class="btn u-btn-3d u-btn-bluegray u-btn-3d" data-dismiss="modal">
                        <?php echo e(_i('Close')); ?>

                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

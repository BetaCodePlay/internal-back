<div class="modal fade" id="add-translations-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo e(_i('Add translation')); ?>: <span id="language-name"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="add-translations-form">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name"><?php echo e(_i('Name')); ?></label>
                        <input type="text" class="form-control" name="name" id="name">
                    </div>
                    <div class="form-group">
                        <label for="content"><?php echo e(_i('Description')); ?></label>
                        <textarea name="content" id="content" cols="30" rows="5" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn u-btn-primary u-btn-3d" id="save-translation">
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

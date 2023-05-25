<div class="modal fade" id="template-preview-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo e(_i('Template preview')); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <iframe id="html" allowfullscreen width="100%" height="800px" frameborder="0"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn u-btn-3d u-btn-bluegray u-btn-3d" data-dismiss="modal">
                    <?php echo e(_i('Close')); ?>

                </button>
            </div>
        </div>
    </div>
</div>

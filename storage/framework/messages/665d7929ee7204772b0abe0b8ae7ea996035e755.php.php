<div class="modal fade" id="remove-segmentations-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo e(_i('Remove from segment')); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="media">
                    <div class="media-body d-flex justify-content-end g-mb-10" id="table-buttons">

                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered w-100" id="segments-user-table" data-route="<?php echo e(route('segments.user-segments', [ isset($user) ? $user->id : ''])); ?>">
                        <thead>
                        <tr>
                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                <?php echo e(_i('Name')); ?>

                            </th>
                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                <?php echo e(_i('Options')); ?>

                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn u-btn-3d u-btn-bluegray u-btn-3d" data-dismiss="modal">
                    <?php echo e(_i('Close')); ?>

                </button>
            </div>
        </div>
    </div>
</div>
<?php

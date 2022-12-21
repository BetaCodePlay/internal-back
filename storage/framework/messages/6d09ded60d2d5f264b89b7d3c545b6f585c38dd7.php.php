<div class="modal fade" id="users-notificacion">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo e(_i('List users')); ?></h5>
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
                    <table class="table table-bordered w-100" id="notifications-users-table" data-route="<?php echo e(route('notifications.list-user',[$notification->id, $notification->notification_type_id])); ?>">
                        <thead>
                        <tr>
                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                <?php echo e(_i('ID')); ?>

                            </th>
                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                <?php echo e(_i('Username')); ?>

                            </th>
                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                <?php echo e(_i('Actions')); ?>

                            </th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

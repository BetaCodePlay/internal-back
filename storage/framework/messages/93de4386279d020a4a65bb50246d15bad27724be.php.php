

<?php $__env->startSection('content'); ?>
    <form action="<?php echo e(route('notifications.update')); ?>" id="notifications-form" method="post"
          enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-3">
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                    <header
                        class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                <?php echo e($title); ?>

                            </h3>
                        </div>
                    </header>
                    <div class="card-block g-pa-15">
                        <div class="form-group">
                            <label for="image"><?php echo e(_i('Image')); ?></label>
                            <input type="file" name="image" id="image" class="opacity-0">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                    <header
                        class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                <?php echo e(_i('Notification details')); ?>

                            </h3>
                            <div class="media-body d-flex justify-content-end">
                                <a href="<?php echo e(route('notifications.index')); ?>"
                                   class="btn u-btn-3d u-btn-primary float-right">
                                    <i class="hs-admin-layout-list-thumb"></i>
                                    <?php echo e(_i('Go to list')); ?>

                                </a>
                            </div>
                        </div>
                    </header>
                    <div class="card-block g-pa-15">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title"><?php echo e(_i('Title')); ?></label>
                                    <input type="text" name="title" id="title" class="form-control"
                                           value="<?php echo e($notification->title); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="language"><?php echo e(_i('Language')); ?></label>
                                    <select name="language" id="language" class="form-control">
                                        <option value="*" <?php echo e($notification->language == '*' ? 'selected' : ''); ?>>
                                            <?php echo e(_i('All')); ?>

                                        </option>
                                        <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option
                                                value="<?php echo e($language['iso']); ?>" <?php echo e($notification->language == $language['iso'] ? 'selected' : ''); ?>>
                                                <?php echo e($language['name']); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="currency"><?php echo e(_i('Currency')); ?></label>
                                    <select name="currency" id="currency" class="form-control">
                                        <option value="*" <?php echo e($notification->currency_iso == '*' ? 'selected' : ''); ?>>
                                            <?php echo e(_i('All')); ?>

                                        </option>
                                        <?php $__currentLoopData = $whitelabel_currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option
                                                value="<?php echo e($currency->iso); ?>" <?php echo e($currency->iso == session('currency') ? 'selected' : ''); ?>>
                                                <?php echo e($currency->iso == 'VEF' ? $free_currency->currency_name : $currency->iso . " ({$currency->name})"); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status"><?php echo e(_i('Status')); ?></label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="true" <?php echo e($notification->status ? 'selected' : ''); ?>>
                                            <?php echo e(_i('Active')); ?>

                                        </option>
                                        <option value="false" <?php echo e(!$notification->status ? 'selected' : ''); ?>>
                                            <?php echo e(_i('Inactive')); ?>

                                        </option>
                                    </select>
                                </div>
                            </div>
                            <?php if($notification->notification_type_id == \App\Notifications\Enums\NotificationTypes::$user
                                //|| $notification->notification_type_id == \App\Notifications\Enums\NotificationTypes::$segment
                                || $notification->notification_type_id == \App\Notifications\Enums\NotificationTypes::$excel): ?>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <br>
                                        <a href="#users-notificacion" class="btn u-btn-3d u-btn-primary"
                                           data-toggle="modal" id="create-segment">
                                            <i class="hs-admin-save"></i>
                                            <?php echo e(_i('Show users')); ?>

                                        </a>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <br>
                                        <h5 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                            <?php echo e(_i('All users')); ?>

                                        </h5>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="content"><?php echo e(_i('Content')); ?></label>
                                    <textarea name="content" id="content"
                                              class="form-control"><?php echo $notification->content; ?></textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="hidden" name="id" value="<?php echo e($notification->id); ?>">
                                    <input type="hidden" name="file" id="file" value="<?php echo e($notification->file); ?>">
                                    <input type="hidden" name="image" id="image" value="<?php echo e($notification->file); ?>">
                                    <input type="hidden" name="type_notification" id="type_notification"
                                           value="<?php echo e($notification->notification_type_id); ?>">
                                    <button type="submit" class="btn u-btn-3d u-btn-primary" id="update"
                                            data-loading-text="<i class='fa fa-spin fa-spinner'></i> <?php echo e(_i('Updating...')); ?>">
                                        <i class="hs-admin-reload"></i>
                                        <?php echo e(_i('Update notification')); ?>

                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <?php echo $__env->make('back.notifications.modals.users', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        $(function () {
            let notifications = new Notifications;
            let users = new Users();
            notifications.update("<?php echo $notification->image; ?>");
            notifications.usersNotificacion();
            notifications.typeNotificationEdit(<?php echo e($notification->notification_type_id); ?>);
            users.select2Users('<?php echo e(_i('Select user')); ?>');
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('back.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
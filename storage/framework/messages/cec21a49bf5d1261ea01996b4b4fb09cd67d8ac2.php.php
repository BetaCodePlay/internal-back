

<?php $__env->startSection('content'); ?>
    <form action="<?php echo e(route('notifications.store')); ?>" id="notifications-form" method="post" enctype="multipart/form-data">
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
                                <a href="<?php echo e(route('notifications.index')); ?>" class="btn u-btn-3d u-btn-primary float-right">
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
                                    <input type="text" name="title" id="title" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="language"><?php echo e(_i('Language')); ?></label>
                                    <select name="language" id="language" class="form-control">
                                        <option value=""><?php echo e(_i('Select...')); ?></option>
                                        <option value="*"><?php echo e(_i('All')); ?></option>
                                        <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($language['iso']); ?>">
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
                                        <option value=""><?php echo e(_i('Select...')); ?></option>
                                        <option value="*"><?php echo e(_i('All')); ?></option>
                                        <?php $__currentLoopData = $whitelabel_currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($currency->iso); ?>" <?php echo e($currency->iso == session('currency') ? 'selected' : ''); ?>>
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
                                        <option value="true"><?php echo e(_i('Active')); ?></option>
                                        <option value="false"><?php echo e(_i('Inactive')); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type"><?php echo e(_i('Notification type')); ?></label>
                                    <select name="type" id="type" class="form-control">
                                        <option value=""><?php echo e(_i('Select...')); ?></option>
                                        <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if( \Dotworkers\Configurations\Configurations::getWhitelabel() == 68 ): ?>
                                                <?php if($type->id != \App\Notifications\Enums\NotificationTypes::$group && $type->id != \App\Notifications\Enums\NotificationTypes::$segment && $type->id != \App\Notifications\Enums\NotificationTypes::$excel): ?>
                                                    <option value="<?php echo e($type->id); ?>">
                                                        <?php echo e($type->name); ?>

                                                    </option>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <?php if($type->id != \App\Notifications\Enums\NotificationTypes::$group && $type->id != \App\Notifications\Enums\NotificationTypes::$segment): ?>
                                                    <option value="<?php echo e($type->id); ?>">
                                                        <?php echo e($type->name); ?>

                                                    </option>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group d-none search-user">
                                    <label for="user"><?php echo e(_i('User')); ?></label>
                                    <select class="form-control select2" id="user" name="users[]"
                                            data-route="<?php echo e(route('users.search-username')); ?>" multiple>
                                        <option></option>
                                    </select>
                                </div>
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                <div class="form-group d-none excel">
                                    <label for="excel"><?php echo e(_i('Excel')); ?></label>
                                    <input type="file" name="excel_file" id="excel_file" class="opacity-0">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="content"><?php echo e(_i('Content')); ?></label>
                                    <textarea name="content" id="content" cols="30" rows="5" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="submit" class="btn u-btn-3d u-btn-primary" id="store"
                                            data-loading-text="<i class='fa fa-spin fa-spinner'></i> <?php echo e(_i('Publishing...')); ?>">
                                        <i class="hs-admin-upload"></i>
                                        <?php echo e(_i('Publish notification')); ?>

                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        $(function () {
            let notifications = new Notifications();
            let users = new Users();
            notifications.store();
            notifications.typeNotification();
            users.select2Users('<?php echo e(_i('Select user')); ?>');
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('back.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
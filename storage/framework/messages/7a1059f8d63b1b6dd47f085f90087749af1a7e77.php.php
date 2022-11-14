

<?php $__env->startSection('content'); ?>
    <form action="<?php echo e(route('notifications.groups.update')); ?>" id="notifications-groups-form" method="post" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-12">
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                    <header
                        class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                <?php echo e($title); ?>

                            </h3>
                        </div>
                        <div class="media-body d-flex justify-content-end">
                            <a href="<?php echo e(route('notifications.groups.index')); ?>" class="btn u-btn-3d u-btn-primary float-right">
                                <i class="hs-admin-layout-list-thumb"></i>
                                <?php echo e(_i('Go to list')); ?>

                            </a>
                        </div>
                    </header>
                    <div class="card-block g-pa-15">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name"><?php echo e(_i('Name')); ?></label>
                                    <input type="text" name="name" id="name" class="form-control" value="<?php echo e($notification->name); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="description"><?php echo e(_i('Description')); ?></label>
                                    <input type="text" name="description" id="description" class="form-control" value="<?php echo e($notification->description); ?>">
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
                                            <option value="<?php echo e($currency->iso); ?>" <?php echo e($currency->iso == session('currency') ? 'selected' : ''); ?>>
                                                <?php echo e($currency->iso == 'VEF' ? $free_currency->currency_name : $currency->iso . " ({$currency->name})"); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="hidden" name="id" value="<?php echo e($notification->id); ?>">
                                    <input type="hidden" name="file" id="file" value="<?php echo e($notification->file); ?>">
                                    <input type="hidden" name="image" id="image" value="<?php echo e($notification->file); ?>">
                                    <button type="submit" class="btn u-btn-3d u-btn-primary" id="update"
                                            data-loading-text="<i class='fa fa-spin fa-spinner'></i> <?php echo e(_i('Updating...')); ?>">
                                        <i class="hs-admin-reload"></i>
                                        <?php echo e(_i('Update group')); ?>

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
            let notifications = new Notifications;
            notifications.updateGroup();
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('back.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
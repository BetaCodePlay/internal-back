

<?php $__env->startSection('content'); ?>
    <form action="<?php echo e(route('section-modals.update')); ?>" id="modals-form" method="post" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-6">
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

            <div class="col-md-6">
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                    <header
                        class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                <?php echo e(_i('Popup details')); ?>

                            </h3>
                            <div class="media-body d-flex justify-content-end">
                                <a href="<?php echo e(route('section-modals.index')); ?>" class="btn u-btn-3d u-btn-primary float-right">
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
                                    <label for="route"><?php echo e(_i('Menu where it will be shown')); ?></label>
                                    <select name="route" id="route" class="form-control">
                                        <option value="core.index" <?php echo e($modal->route == 'core.index' ? 'selected' : ''); ?>>
                                            <?php echo e(_i('Home')); ?>

                                        </option>
                                        <option value="users.panel" <?php echo e($modal->route == 'users.panel' ? 'selected' : ''); ?>>
                                            <?php echo e(_i('User panel')); ?>

                                        </option>
                                        <?php $__currentLoopData = $menu; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($item->route); ?>" <?php echo e($item->route == $modal->route ? 'selected' : ''); ?>>
                                                <?php echo e($item->name); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="one_time"><?php echo e(_i('Show one time only')); ?></label>
                                    <select name="one_time" id="one_time" class="form-control">
                                        <option value="true" <?php echo e($modal->one_time ? 'selected' : ''); ?>>
                                            <?php echo e(_i('Yes')); ?>

                                        </option>
                                        <option value="false" <?php echo e(!$modal->one_time ? 'selected' : ''); ?>>
                                            <?php echo e(_i('No')); ?>

                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="scroll"><?php echo e(_i('Show when scrolling')); ?></label>
                                    <select name="scroll" id="scroll" class="form-control">
                                        <option value="true" <?php echo e($modal->scroll ? 'selected' : ''); ?>>
                                            <?php echo e(_i('Yes')); ?>

                                        </option>
                                        <option value="false" <?php echo e(!$modal->scroll ? 'selected' : ''); ?>>
                                            <?php echo e(_i('No')); ?>

                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="language"><?php echo e(_i('Language')); ?></label>
                                    <select name="language" id="language" class="form-control">
                                        <option value="*" <?php echo e($modal->language == '*' ? 'selected' : ''); ?>>
                                            <?php echo e(_i('All')); ?>

                                        </option>
                                        <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($language['iso']); ?>" <?php echo e($modal->language == $language['iso'] ? 'selected' : ''); ?>>
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
                                        <option value="*" <?php echo e($modal->currency == '*' ? 'selected' : ''); ?>>
                                            <?php echo e(_i('All')); ?>

                                        </option>
                                        <?php $__currentLoopData = $whitelabel_currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($currency->iso); ?>" <?php echo e($currency->iso == $modal->currency ? 'selected' : ''); ?>>
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
                                        <option value="true" <?php echo e($modal->status ? 'selected' : ''); ?>>
                                            <?php echo e(_i('Published')); ?>

                                        </option>
                                        <option value="false" <?php echo e(!$modal->status ? 'selected' : ''); ?>>
                                            <?php echo e(_i('Unpublished')); ?>

                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="url"><?php echo e(_i('Url')); ?></label>
                                    <input type="text" class="form-control" name="url" id="url" value="<?php echo e($modal->url); ?>">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="hidden" name="id" value="<?php echo e($modal->id); ?>">
                                    <input type="hidden" name="file" id="file" value="<?php echo e($modal->file); ?>">
                                    <input type="hidden" name="image" id="image" value="<?php echo e($modal->file); ?>">
                                    <button type="submit" class="btn u-btn-3d u-btn-primary" id="update"
                                            data-loading-text="<i class='fa fa-spin fa-spinner'></i> <?php echo e(_i('Updating...')); ?>">
                                        <i class="hs-admin-reload"></i>
                                        <?php echo e(_i('Update popup')); ?>

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
            let modals = new SectionModals();
            modals.update("<?php echo $modal->image; ?>");
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('back.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
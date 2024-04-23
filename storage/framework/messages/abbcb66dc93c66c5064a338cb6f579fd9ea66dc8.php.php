

<?php $__env->startSection('content'); ?>
    <form action="<?php echo e(route('store.rewards.update')); ?>" id="reward-form" method="post" enctype="multipart/form-data">
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
                        <div class="noty_bar noty_type__warning noty_theme__unify--v1--dark g-mb-25">
                            <div class="noty_body">
                                <div class="g-mr-20">
                                    <div class="noty_body__icon">
                                        <i class="hs-admin-info"></i>
                                    </div>
                                </div>
                                <div>
                                    <p>
                                        <?php echo e(_i('The recommended size for the image is 280x280 pixels. Using a different size can cause misalignments on the page.')); ?>

                                    </p>
                                </div>
                            </div>
                        </div>
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
                                <?php echo e(_i('Rewards details')); ?>

                            </h3>
                            <div class="media-body d-flex justify-content-end">
                                <a href="<?php echo e(route('store.rewards.index')); ?>" class="btn u-btn-3d u-btn-primary float-right">
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
                                    <label for="name"><?php echo e(_i('Name')); ?></label>
                                    <input type="text" name="name" id="name" class="form-control" value="<?php echo e($reward->name); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="description"><?php echo e(_i('Description to help the user understand the reward')); ?></label>
                                    <input type="text" name="description" id="description" class="form-control" value="<?php echo e($reward->description); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="quantity"><?php echo e(_i('Available quantity (optional)')); ?></label>
                                    <input type="text" name="quantity" id="quantity" class="form-control" value="<?php echo e($reward->quantity); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="currency"><?php echo e(_i('Currency')); ?></label>
                                    <select name="currency" id="currency" class="form-control">
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
                                    <label for="points"><?php echo e(_i('Points needed to get the reward')); ?></label>
                                    <input type="text" name="points" id="points" class="form-control" value="<?php echo e($reward->points); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="amount"><?php echo e(_i('Amount that the reward will deliver as a prize')); ?></label>
                                    <input type="number" name="amount" id="amount" class="form-control" value="<?php echo e($reward->data->amount); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="start_date"><?php echo e(_i('Start date (optional)')); ?></label>
                                    <input type="text" name="start_date" id="start_date"
                                           class="form-control datepicker" value="<?php echo e($reward->start); ?>" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end_date"><?php echo e(_i('Finish date (optional)')); ?></label>
                                    <input type="text" name="end_date" id="end_date" class="form-control datepicker" value="<?php echo e($reward->end); ?>" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="language"><?php echo e(_i('Language')); ?></label>
                                    <select name="language" id="language" class="form-control">
                                        <option value="*" <?php echo e($reward->language == '*' ? 'selected' : ''); ?>>
                                            <?php echo e(_i('All')); ?>

                                        </option>
                                        <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($language['iso']); ?>" <?php echo e($reward->language == $language['iso'] ? 'selected' : ''); ?>>
                                                <?php echo e($language['name']); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status"><?php echo e(_i('Status')); ?></label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="true" <?php echo e($reward->status ? 'selected' : ''); ?>>
                                            <?php echo e(_i('Published')); ?>

                                        </option>
                                        <option value="false" <?php echo e(!$reward->status ? 'selected' : ''); ?>>
                                            <?php echo e(_i('Unpublished')); ?>

                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="currency"><?php echo e(_i('Category (optional)')); ?></label>
                                    <select name="category" id="category" class="form-control">
                                        <option value=""><?php echo e(_i('Select...')); ?></option>
                                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($category->id); ?>" <?php echo e($reward->category_id == $category->id ? 'selected' : ''); ?>>
                                                <?php echo e($category->name); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="hidden" name="id" value="<?php echo e($reward->id); ?>">
                                    <input type="hidden" name="file" id="file" value="<?php echo e($reward->file); ?>">
                                    <input type="hidden" name="image" id="image" value="<?php echo e($reward->file); ?>">
                                    <button type="submit" class="btn u-btn-3d u-btn-primary" id="update"
                                            data-loading-text="<i class='fa fa-spin fa-spinner'></i> <?php echo e(_i('Updating...')); ?>">
                                        <i class="hs-admin-reload"></i>
                                        <?php echo e(_i('Update reward')); ?>

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
            let store = new Store();
            store.update("<?php echo $reward->image; ?>");
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('back.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
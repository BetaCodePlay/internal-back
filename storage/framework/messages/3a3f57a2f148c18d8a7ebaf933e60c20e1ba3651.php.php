

<?php $__env->startSection('content'); ?>
    <form action="<?php echo e(route('landing-pages.update')); ?>" id="landing-form" method="post" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-6">
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                    <header
                        class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                <?php echo e(_i('Landing Pages details')); ?>

                            </h3>
                            <div class="media-body d-flex justify-content-end">
                                <a href="<?php echo e(route('landing-pages.index')); ?>"
                                   class="btn u-btn-3d u-btn-primary float-right">
                                    <i class="hs-admin-layout-list-thumb"></i>
                                    <?php echo e(_i('Go to list')); ?>

                                </a>
                            </div>
                        </div>
                    </header>

                    <div class="card-block g-pa-15">
                        <div class="row">

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="name"><?php echo e(_i('Name')); ?></label>
                                    <input type="text" name="name" id="name" class="form-control"
                                           value="<?php echo e($landing->name); ?>">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="subtitle"><?php echo e(_i('Subtitle')); ?></label>
                                    <input type="text" name="subtitle" id="subtitle" class="form-control"
                                           value="<?php echo e($landing->data->props->subtitle); ?>">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="text"><?php echo e(_i('Text button')); ?></label>
                                    <input type="text" name="text" id="text" class="form-control"
                                           value="<?php echo e($landing->data->props->button->text); ?>">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="url"><?php echo e(_i('URL')); ?></label>
                                    <input type="text" name="url" id="url" class="form-control"
                                           value="<?php echo e($landing->data->props->button->url); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="start_date"><?php echo e(_i('Start date')); ?></label>
                                    <input type="text" name="start_date" id="start_date"
                                           class="form-control datepicker" value="<?php echo e($landing->start); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end_date"><?php echo e(_i('Finish date')); ?></label>
                                    <input type="text" name="end_date" id="end_date" class="form-control datepicker"
                                           value="<?php echo e($landing->end); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="language"><?php echo e(_i('Language')); ?></label>
                                    <select name="language" id="language" class="form-control">
                                        <option value="*" <?php echo e($landing->language == '*' ? 'selected' : ''); ?>>
                                            <?php echo e(_i('All')); ?>

                                        </option>
                                        <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option
                                                value="<?php echo e($language['iso']); ?>" <?php echo e($landing->language == $language['iso'] ? 'selected' : ''); ?>>
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
                                        <option value="*" <?php echo e($landing->currency == '*' ? 'selected' : ''); ?>>
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
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status"><?php echo e(_i('Status')); ?></label>
                                    <select name="status" id="status" class="form-control">
                                        <?php if(is_null($landing->status)): ?>
                                            <option value="true" selected>
                                                <?php echo e(_i('Published')); ?>

                                            </option>
                                            <option value="false">
                                                <?php echo e(_i('Unpublished')); ?>

                                            </option>
                                        <?php else: ?>
                                            <option value="true" <?php echo e($landing->status ? 'selected' : ''); ?>>
                                                <?php echo e(_i('Published')); ?>

                                            </option>
                                            <option value="false" <?php echo e(!$landing->status ? 'selected' : ''); ?>>
                                                <?php echo e(_i('Unpublished')); ?>

                                            </option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="hidden" name="id" id="id" value="<?php echo e($landing->id); ?>">
                                    <input type="hidden" name="file" id="file" value="<?php echo e($landing->file); ?>">
                                    <input type="hidden" name="image" id="image" value="<?php echo e($landing->file); ?>">
                                    <input type="hidden" name="file_1" id="file_1" value="<?php echo e($landing->file_1); ?>">
                                    <input type="hidden" name="background_1" id="background_1"
                                           value="<?php echo e($landing->file_1); ?>">
                                    <input type="hidden" name="file_2" id="file" value="<?php echo e($landing->file_2); ?>">
                                    <input type="hidden" name="background_2" id="background_2"
                                           value="<?php echo e($landing->file_2); ?>">
                                    <input type="hidden" name="file_3" id="file_3" value="<?php echo e($landing->file_3); ?>">
                                    <input type="hidden" name="logo" id="logo" value="<?php echo e($landing->file_3); ?>">
                                    <button type="submit" class="btn u-btn-3d u-btn-primary" id="store"
                                            data-loading-text="<i class='fa fa-spin fa-spinner'></i> <?php echo e(_i('Updating...')); ?>">
                                        <i class="hs-admin-reload"></i>
                                        <?php echo e(_i('Update landing')); ?>

                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                        <header
                            class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                            <div class="media">
                                <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                    <?php echo e($title); ?> <?php echo e(_i('Background 1')); ?>

                                </h3>
                            </div>
                        </header>
                        <div class="card-block g-pa-15">
                            <div class="form-group">
                                <label for="background_1"><?php echo e(_i('Background 1')); ?></label>
                                <input type="file" name="background_1" id="background_1" class="opacity-0">
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
                                    <?php echo e($title); ?> <?php echo e(_i('Background 2')); ?>

                                </h3>
                            </div>
                        </header>
                        <div class="card-block g-pa-15">
                            <div class="form-group">
                                <label for="background_2"><?php echo e(_i('Background 2')); ?></label>
                                <input type="file" name="background_2" id="background_2" class="opacity-0">
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
                                    <?php echo e($title); ?> <?php echo e(_i('Image left')); ?>

                                </h3>
                            </div>
                        </header>
                        <div class="card-block g-pa-15">
                            <div class="form-group">
                                <label for="image"><?php echo e(_i('Image left')); ?></label>
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
                                    <?php echo e($title); ?> <?php echo e(_i('Logo')); ?>

                                </h3>
                            </div>
                        </header>
                        <div class="card-block g-pa-15">
                            <div class="form-group">
                                <label for="logo"><?php echo e(_i('Logo')); ?></label>
                                <input type="file" name="logo" id="logo" class="opacity-0">
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
                                    <?php echo e($title); ?> <?php echo e(_i('Steps')); ?>

                                </h3>
                            </div>
                        </header>
                        <div class="card-block g-pa-15">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="steps_title"><?php echo e(_i('Steps Title')); ?></label>
                                        <input type="text" name="steps_title" id="steps_title" class="form-control"
                                               value="<?php echo e($landing->data->props->steps->title); ?>">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="steps_content"><?php echo e(_i('Steps Content')); ?></label>
                                        <textarea name="steps_content" id="steps_content" cols="30" rows="10"
                                                  class="form-control"> <?php echo $landing->data->props->steps->content; ?></textarea>

                                    </div>
                                </div>
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
                                    <?php echo e($title); ?> <?php echo e(_i('Terms')); ?>

                                </h3>
                            </div>
                        </header>
                        <div class="card-block g-pa-15">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="terms_title"><?php echo e(_i('Terms Title')); ?></label>
                                        <input type="text" name="terms_title" id="terms_title" class="form-control"
                                               value="<?php echo e($landing->data->props->terms->title); ?>">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="terms_content"><?php echo e(_i('Terms Content')); ?></label>
                                        <textarea name="terms_content" id="terms_content" cols="30" rows="10"
                                                  class="form-control"><?php echo $landing->data->props->terms->content; ?></textarea>

                                    </div>
                                </div>
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
                                    <?php echo e($title); ?> <?php echo e(_i('Additional')); ?>

                                </h3>
                            </div>
                        </header>
                        <div class="card-block g-pa-15">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="additional_title"><?php echo e(_i('Additional Title')); ?></label>
                                        <input type="text" name="additional_title" id="additional_title"
                                               class="form-control"
                                               value="<?php echo e($landing->data->props->additional_info->title); ?>">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="additional_content"><?php echo e(_i('Additional Content')); ?></label>
                                        <textarea name="additional_content" id="additional_content" cols="30" rows="10"
                                                  class="form-control"> <?php echo $landing->data->props->additional_info->content; ?></textarea>

                                    </div>
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
            let landingPages = new LandingPages();
            landingPages.update();
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('back.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
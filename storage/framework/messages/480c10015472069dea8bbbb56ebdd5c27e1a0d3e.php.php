

<?php $__env->startSection('content'); ?>
    <form action="<?php echo e(route('section-images.update')); ?>" id="images-form" method="post" enctype="multipart/form-data">
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
                        <div class="noty_bar noty_type__warning noty_theme__unify--v1--dark g-mb-25">
                            <div class="noty_body">
                                <div class="g-mr-20">
                                    <div class="noty_body__icon">
                                        <i class="hs-admin-info"></i>
                                    </div>
                                </div>
                                <div>
                                    <p>
                                        <?php echo e(_i('The recommended size for the image is %s pixels. Using a different size can cause misalignments on the page.', [$image->size])); ?>

                                    </p>
                                    <p>
                                        <?php echo e(_i('The maximum file size is 5mb')); ?>

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
            <div class="col-md-6">
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                    <header
                        class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                <?php echo e(_i('Image details')); ?>

                            </h3>
                            <div class="media-body d-flex justify-content-end">
                                <a href="<?php echo e(route('section-images.index', [$template_element_type, $section])); ?>"
                                   class="btn u-btn-3d u-btn-primary float-right">
                                    <i class="hs-admin-layout-list-thumb"></i>
                                    <?php echo e(_i('Go to list')); ?>

                                </a>
                            </div>
                        </div>
                    </header>
                    <div class="card-block g-pa-15">
                        <div class="row">
                            <?php if($position != \App\Core\Enums\ImagesPositions::$logo_light && $position != \App\Core\Enums\ImagesPositions::$logo_dark && $position != \App\Core\Enums\ImagesPositions::$favicon && $position != \App\Core\Enums\ImagesPositions::$mobile_light && $position != \App\Core\Enums\ImagesPositions::$mobile_dark): ?>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="title"><?php echo e(_i('Title')); ?></label>
                                        <input type="text" name="title" id="title" class="form-control"
                                               value="<?php echo e($image->title); ?>">
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if($props->button): ?>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="button"><?php echo e(_i('Button text')); ?></label>
                                        <input type="text" name="button" id="button" class="form-control"
                                               value="<?php echo e($image->button); ?>">
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if($props->description): ?>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="description"><?php echo e(_i('Description')); ?></label>
                                        <textarea name="description" id="description" cols="30" rows="5"
                                                  class="form-control"><?php echo e($image->description); ?></textarea>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if($position != \App\Core\Enums\ImagesPositions::$logo_light && $position != \App\Core\Enums\ImagesPositions::$logo_dark && $position != \App\Core\Enums\ImagesPositions::$favicon && $position != \App\Core\Enums\ImagesPositions::$mobile_light && $position != \App\Core\Enums\ImagesPositions::$mobile_dark): ?>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="url"><?php echo e(_i('URL')); ?></label>
                                        <input type="text" name="url" id="url" class="form-control"
                                               value="<?php echo e($image->url); ?>">
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="start_date"><?php echo e(_i('Start date')); ?></label>
                                    <?php if(isset($image->start_date)): ?>
                                        <input type="text" name="start_date" id="start_date"
                                               class="form-control datetimepicker"
                                               value="<?php echo e($image->start_date->format('d-m-Y h:i a')); ?>"
                                               autocomplete="off">
                                    <?php else: ?>
                                        <input type="text" name="start_date" id="start_date"
                                               class="form-control datetimepicker" autocomplete="off">
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end_date"><?php echo e(_i('End date')); ?></label>
                                    <?php if(isset($image->end_date)): ?>
                                        <input type="text" name="end_date" id="end_date"
                                               class="form-control datetimepicker"
                                               value="<?php echo e($image->end_date->format('d-m-Y h:i a')); ?>"
                                               autocomplete="off">
                                    <?php else: ?>
                                        <input type="text" name="end_date" id="end_date"
                                               class="form-control datetimepicker" autocomplete="off">
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="status"><?php echo e(_i('Status')); ?></label>
                                    <select name="status" id="status" class="form-control">
                                        <?php if(is_null($image->status)): ?>
                                            <option value="true" selected>
                                                <?php echo e(_i('Published')); ?>

                                            </option>
                                            <option value="false">
                                                <?php echo e(_i('Unpublished')); ?>

                                            </option>
                                        <?php else: ?>
                                            <option value="true" <?php echo e($image->status ? 'selected' : ''); ?>>
                                                <?php echo e(_i('Published')); ?>

                                            </option>
                                            <option value="false" <?php echo e(!$image->status ? 'selected' : ''); ?>>
                                                <?php echo e(_i('Unpublished')); ?>

                                            </option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="hidden" name="id" value="<?php echo e(isset($image->id) ? $image->id : null); ?>">
                                    <input type="hidden" name="file" value="<?php echo e($image->file); ?>">
                                    <input type="hidden" name="image" value="<?php echo e($image->file); ?>">
                                    <input type="hidden" name="template_element_type"
                                           value="<?php echo e($template_element_type); ?>">
                                    <input type="hidden" name="section" value="<?php echo e($section); ?>">
                                    <input type="hidden" name="position" value="<?php echo e($position); ?>">
                                    <button type="submit" class="btn u-btn-3d u-btn-primary" id="update"
                                            data-loading-text="<i class='fa fa-spin fa-spinner'></i> <?php echo e(_i('Updating...')); ?>">
                                        <i class="hs-admin-reload"></i>
                                        <?php echo e(_i('Update image')); ?>

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
            let sectionImages = new SectionImages();
            sectionImages.update("<?php echo $image->image; ?>");
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('back.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
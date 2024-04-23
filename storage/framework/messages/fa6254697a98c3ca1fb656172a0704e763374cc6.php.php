

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-12">
        <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
            <header
                class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                <div class="media">
                    <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                        <?php echo e($title); ?>

                    </h3>
                    <div class="media-body d-flex justify-content-end">
                        <a href="<?php echo e(route('email-configurations.index')); ?>" class="btn u-btn-3d u-btn-primary float-right">
                            <i class="hs-admin-layout-list-thumb"></i>
                            <?php echo e(_i('Go to list')); ?>

                        </a>
                    </div>
                </div>
            </header>
            <form action="<?php echo e(route('email-configurations.updateEmail')); ?>" id="email-form" method="post" enctype="multipart/form-data">
                <div class="card-block g-pa-15">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title"><?php echo e(_i('Title')); ?></label>
                                <input type="text" name="title" id="title" class="form-control" autocomplete="off" value="<?php echo e($email->title); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="subtitle"><?php echo e(_i('Subtitle')); ?></label>
                                <input type="text" class="form-control" name="subtitle" id="subtitle" autocomplete="off" value="<?php echo e($email->subtitle); ?>">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="content"><?php echo e(_i('Content')); ?></label>
                                <textarea type="text" class="form-control" name="content" id="content" autocomplete="off"><?php echo $email->content; ?></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="button"><?php echo e(_i('Button')); ?></label>
                                <input type="text" class="form-control" name="button" id="button" autocomplete="off" value="<?php echo e($email->button); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="footer"><?php echo e(_i('Footer')); ?></label>
                                <input type="text" class="form-control" name="footer" id="footer" autocomplete="off" value="<?php echo e($email->footer); ?>">
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

                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="hidden" name="id" name="id" value="<?php echo e($email->email_type_id); ?>">
                                <button type="submit" class="btn u-btn-3d u-btn-primary" id="update-email"
                                        data-loading-text="<i class='fa fa-spin fa-spinner'></i> <?php echo e(_i('Updating...')); ?>">
                                    <i class="hs-admin-reload"></i>
                                    <?php echo e(_i('Update')); ?>

                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
    <script>
        $(function () {
            let emailType = new EmailConfigurations();
            emailType.updateEmail();
        });
    </script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('back.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
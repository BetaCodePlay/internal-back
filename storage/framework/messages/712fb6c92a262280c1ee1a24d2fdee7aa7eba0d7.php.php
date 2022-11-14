

<?php $__env->startSection('content'); ?>
    <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
        <header
            class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
            <div class="media">
                <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                    <?php echo e($title); ?>

                </h3>
                <div class="media-body d-flex justify-content-end">
                    <a href="<?php echo e(route('pages.index')); ?>" class="btn u-btn-3d u-btn-primary float-right">
                        <i class="hs-admin-layout-list-thumb"></i>
                        <?php echo e(_i('Go to list')); ?>

                    </a>
                </div>
            </div>
        </header>
        <div class="card-block g-pa-15">
            <form action="<?php echo e(route('pages.update')); ?>" method="post" id="posts-form">
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="title"><?php echo e(_i('Title')); ?></label>
                            <input type="text" name="title" id="title" class="form-control" value="<?php echo e($page->title); ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="status"><?php echo e(_i('Status')); ?></label>
                            <select name="status" id="status" class="form-control">
                                <option value="true" <?php echo e($page->status ? 'selected' : ''); ?>>
                                    <?php echo e(_i('Published')); ?>

                                </option>
                                <option value="false" <?php echo e(!$page->status ? 'selected' : ''); ?>>
                                    <?php echo e(_i('Unpublished')); ?>

                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="content"><?php echo e(_i('Content')); ?></label>
                            <textarea name="content" id="content" cols="30" rows="10"
                                      class="form-control"><?php echo $page->content; ?></textarea>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <input type="hidden" name="id" id="id" value="<?php echo e($page->id); ?>">
                            <button type="button" class="btn u-btn-3d u-btn-primary" id="update"
                                    data-loading-text="<i class='fa fa-spin fa-spinner'></i> <?php echo e(_i('Updating...')); ?>">
                                <i class="hs-admin-reload"></i>
                                <?php echo e(_i('Update page')); ?>

                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        $(function () {
            let pages = new Pages();
            pages.update();
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('back.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
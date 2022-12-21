

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                <header class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                    <div class="media">
                        <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                            <?php echo e(_i('New category')); ?>

                        </h3>
                    </div>
                </header>
                <div class="card-block g-pa-15">
                    <form action="<?php echo e(route('store.categories.store')); ?>" id="save-form" method="post">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="credential"><?php echo e(_i('Name')); ?></label>
                                    <input type="text" name="name" id="name" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="button" class="btn u-btn-3d u-btn-primary" id="save"
                                            data-loading-text="<i class='fa fa-spin fa-spinner'></i> <?php echo e(_i('Saving...')); ?>">
                                        <i class="hs-admin-save"></i>
                                        <?php echo e(_i('Save')); ?>

                                    </button>
                                    <button type="button" class="btn u-btn-3d u-btn-bluegray" id="clear">
                                        <i class="hs-admin-close"></i>
                                        <?php echo e(_i('Clear')); ?>

                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                <header class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                    <div class="media">
                        <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0 title">
                            <?php echo e(_i('Categories')); ?>

                        </h3>
                        <div class="media-body d-flex justify-content-end" id="table-buttons">

                        </div>
                    </div>
                </header>
                <div class="card-block g-pa-15">
                    <table class="table table-bordered table-responsive-sm w-100" id="categories-table" data-route="<?php echo e(route('store.categories.data')); ?>">
                        <thead>
                        <tr>
                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                <?php echo e(_i('ID')); ?>

                            </th>
                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                <?php echo e(_i('Category')); ?>

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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        $(function () {
            let store = new Store();
            store.saveCategory();
            store.categories();
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('back.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
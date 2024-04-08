

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                <header class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                    <div class="media">
                        <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                            <?php echo e($title); ?>

                        </h3>
                    </div>
                </header>
                <div class="card-block g-pa-15">
                    <form action="<?php echo e(route('core.update.password.wolf')); ?>" id="updatePassword-of-wolf" method="post">
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <label for="password"><?php echo e(_i('Password')); ?></label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="password">
                                    <div class="input-group-append">
                                        <button
                                            class="btn u-input-btn--v1 g-width-40 u-btn-primary g-rounded-right-4 u-btn-3d refresh-password"
                                            type="button">
                                            <i class="hs-admin-reload g-absolute-centered g-font-size-16 g-color-white"></i>
                                        </button>
                                    </div>
                                </div>
                                <small
                                    class="form-text text-muted"><?php echo e(_i('Minimum 8 characters, 1 letter and 1 number')); ?></small>
                            </div>

                            <div class="col-md-12" style="text-align: end;">
                                <div class="form-group">
                                    <button type="button" class="btn u-btn-3d u-btn-primary" id="updatePasswordOfWolf"
                                            data-loading-text="<i class='fa fa-spin fa-spinner'></i> <?php echo e(_i('Please wait...')); ?>">
                                        <i class="hs-admin-save"></i>
                                        <?php echo e(_i('Add')); ?>

                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        $(function () {
            let users = new Users();
            users.updatePasswordForWold();
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('back.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
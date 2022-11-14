

<?php $__env->startSection('content'); ?>
    <form action="<?php echo e(route('configurations.main-route.update')); ?>" method="post" id="routes-form"
          data-levels-route="<?php echo e(route('configurations.main-route.data')); ?>">
        <div class="row">
            <div class="col-md-3">
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                    <header class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                <?php echo e($title); ?>

                            </h3>
                        </div>
                    </header>
                    <div class="card-block g-pa-15">
                        <div class="form-group">
                            <label for="whitelabel"><?php echo e(_i('Whitelabel')); ?></label>
                            <select name="whitelabel" id="whitelabel" class="form-control">
                                <option value=""><?php echo e(_i('Select...')); ?></option>
                                <?php $__currentLoopData = $whitelabels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $whitelabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($whitelabel->id); ?>">
                                        <?php echo e($whitelabel->description); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <button type="button" class="btn u-btn-3d u-btn-primary" id="load-button"
                                    data-loading-text="<i class='fa fa-spin fa-spinner'></i> <?php echo e(_i('Loading...')); ?>">
                                <?php echo e(_i('Upload data')); ?>

                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                    <header class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0 title">
                                <?php echo e(_i('Desktop settings')); ?>

                            </h3>
                        </div>
                    </header>
                    <div class="card-block g-pa-15">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="desktop_main"><?php echo e(_i('Main page')); ?></label>
                                    <select name="desktop_main" id="desktop_main" class="form-control">
                                        <option value=""><?php echo e(_i('Select...')); ?></option>
                                        <option value="CoreController@index"><?php echo e(_i('Home')); ?></option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="desktop_auth"><?php echo e(_i('Where will the user go when logging in?')); ?></label>
                                    <select name="desktop_auth" id="desktop_auth" class="form-control">
                                        <option value=""><?php echo e(_i('Select...')); ?></option>
                                        <option value="core.index"><?php echo e(_i('Home')); ?></option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-check-inline u-check g-pl-25">
                                        <input class="g-hidden-xs-up g-pos-abs g-top-0 g-left-0" type="checkbox"
                                               name="desktop_ssl" id="desktop_ssl">
                                        <div class="u-check-icon-checkbox-v6 g-absolute-centered--y g-left-0">
                                            <i class="fa" data-check-icon=""></i>
                                        </div>
                                        <?php echo e(_i('Secure Sockets Layer (SSL)?')); ?>

                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="noty_bar noty_type__warning noty_theme__unify--v1--dark g-mb-25">
                                    <div class="noty_body">
                                        <div class="g-mr-20">
                                            <div class="noty_body__icon">
                                                <i class="hs-admin-info"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <p>
                                                <?php echo e(_i('This setting is used when the user accesses from a desktop or laptop')); ?>

                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                    <header class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0 title">
                                <?php echo e(_i('Mobile setup')); ?>

                            </h3>
                        </div>
                    </header>
                    <div class="card-block g-pa-15">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="mobile_main"><?php echo e(_i('Main page')); ?></label>
                                    <select name="mobile_main" id="mobile_main" class="form-control">
                                        <option value=""><?php echo e(_i('Select...')); ?></option>
                                        <option value="CoreController@index"><?php echo e(_i('Home')); ?></option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="mobile_auth"><?php echo e(_i('Where will the user go when logging in?')); ?></label>
                                    <select name="mobile_auth" id="mobile_auth" class="form-control">
                                        <option value=""><?php echo e(_i('Select...')); ?></option>
                                        <option value="core.index"><?php echo e(_i('Home')); ?></option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-check-inline u-check g-pl-25">
                                        <input class="g-hidden-xs-up g-pos-abs g-top-0 g-left-0" type="checkbox"
                                               name="mobile_ssl" id="mobile_ssl">
                                        <div class="u-check-icon-checkbox-v6 g-absolute-centered--y g-left-0">
                                            <i class="fa" data-check-icon=""></i>
                                        </div>
                                        <?php echo e(_i('Secure Sockets Layer (SSL)?')); ?>

                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="noty_bar noty_type__warning noty_theme__unify--v1--dark g-mb-25">
                                    <div class="noty_body">
                                        <div class="g-mr-20">
                                            <div class="noty_body__icon">
                                                <i class="hs-admin-info"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <p>
                                                <?php echo e(_i('This configuration is used when the user accesses from a mobile device')); ?>

                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                    <div class="card-block g-pa-15">
                        <button type="button" class="btn u-btn-3d u-btn-primary" id="update-button"
                                data-loading-text="<i class='fa fa-spin fa-spinner'></i> <?php echo e(_i('Updating...')); ?>"
                                disabled>
                            <?php echo e(_i('Update settings')); ?>

                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        $(function () {
            let configurations = new Configurations();
            configurations.mainRoute();
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('back.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
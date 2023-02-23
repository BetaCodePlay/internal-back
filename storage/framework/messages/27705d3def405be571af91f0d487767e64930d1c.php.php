

<?php $__env->startSection('content'); ?>
    <form action="<?php echo e(route('sliders.store')); ?>" id="sliders-form" method="post" enctype="multipart/form-data">
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
                                        <?php echo e(_i('The maximum file size is 5mb and the maximum width is 3440px')); ?>

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
                                <?php echo e(_i('Slider details')); ?>

                            </h3>
                            <div class="media-body d-flex justify-content-end">
                                <a href="<?php echo e(route('sliders.index', [$template_element_type, $section])); ?>" class="btn u-btn-3d u-btn-primary float-right">
                                    <i class="hs-admin-layout-list-thumb"></i>
                                    <?php echo e(_i('Go to list')); ?>

                                </a>
                            </div>
                        </div>
                    </header>

                    <div class="card-block g-pa-15">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="url"><?php echo e(_i('URL')); ?></label>
                                    <input type="text" name="url" id="url" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="start_date"><?php echo e(_i('Start date')); ?></label>
                                    <input type="text" name="start_date" id="start_date"
                                           class="form-control datetimepicker" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end_date"><?php echo e(_i('Finish date')); ?></label>
                                    <input type="text" name="end_date" id="end_date" class="form-control datetimepicker" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="device"><?php echo e(_i('Devices')); ?></label>
                                    <select name="device" id="device" class="form-control">
                                        <option value=""><?php echo e(_i('Select...')); ?></option>
                                        <option value="*"><?php echo e(_i('All')); ?></option>
                                        <option value="false"><?php echo e(_i('Desktop')); ?></option>
                                        <option value="true"><?php echo e(_i('Mobile')); ?></option>
                                    </select>
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
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="currency"><?php echo e(_i('Currency')); ?></label>

                                    <select name="currency" id="currency" class="form-control">
                                        <option value=""><?php echo e(_i('Select...')); ?></option>
                                        <option value="*"><?php echo e(_i('All')); ?></option>
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
                                        <option value="true"><?php echo e(_i('Published')); ?></option>
                                        <option value="false"><?php echo e(_i('Unpublished')); ?></option>
                                    </select>
                                </div>
                            </div>
                            <?php if(isset($menu)): ?>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="route"><?php echo e(_i('Menu where it will be shown')); ?></label>
                                        <select name="route[]" id="route" class="form-control" multiple>

                                                <option value="core.index">
                                                    <?php echo e(_i('Home')); ?>

                                                </option>
                                            <?php $__currentLoopData = $menu; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($item->route); ?>">
                                                    <?php echo e($item->name); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php if(\Dotworkers\Configurations\Configurations::getWhitelabel() == 2 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 6 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 7 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 8 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 9 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 20 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 27 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 42 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 47 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 50 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 68 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 73 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 74 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 75 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 76 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 79 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 81 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 112 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 116 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 130): ?>
                                                <option value="pragmatic-play.live">
                                                    <?php echo e(_i('Pragmatic Live Casino')); ?>

                                                </option>
                                            <?php endif; ?>
                                            <?php if( \Dotworkers\Configurations\Configurations::getWhitelabel() == 116): ?>
                                                <option value="vivo-gaming.lobby">
                                                    <?php echo e(_i('Live Casino')); ?>

                                                </option>
                                            <?php endif; ?>
                                            <?php if( \Dotworkers\Configurations\Configurations::getWhitelabel() == 147 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 149 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 144): ?>
                                                 <option value="store.index">
                                                      <?php echo e(_i('Store')); ?>

                                                 </option>
                                            <?php endif; ?>
                                            <?php if( \Dotworkers\Configurations\Configurations::getWhitelabel() == 114): ?>
                                                <option value="vivo-gaming-dotsuite.lobby">
                                                    <?php echo e(_i('Vivo Gaming Dotsuite')); ?>

                                                </option>
                                            <?php endif; ?>
                                                <?php if( \Dotworkers\Configurations\Configurations::getWhitelabel() == 114 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 132 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 133 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 141 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 144 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 142 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 125 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 153): ?>
                                                    <option value="bet-soft.vg.lobby">
                                                        <?php echo e(_i('Bet Soft')); ?>

                                                    </option>
                                                    <option value="tom-horn.vg.lobby">
                                                        <?php echo e(_i('Tom Horn')); ?>

                                                    </option>
                                                    <option value="platipus.vg.lobby">
                                                        <?php echo e(_i('Platipus')); ?>

                                                    </option>
                                                    <option value="booongo.vg.lobby">
                                                        <?php echo e(_i('Booongo')); ?>

                                                    </option>
                                                    <option value="leap.vg.lobby">
                                                        <?php echo e(_i('Leap')); ?>

                                                    </option>
                                                    <option value="arrows-edge.vg.lobby">
                                                        <?php echo e(_i('Arrows Edge')); ?>

                                                    </option>
                                                    <option value="red-rake.vg.lobby">
                                                        <?php echo e(_i('Red Rake')); ?>

                                                    </option>
                                                    <option value="playson.vg.lobby">
                                                        <?php echo e(_i('Playson')); ?>

                                                    </option>
                                                    <option value="5men.vg.lobby">
                                                        <?php echo e(_i('5 Men')); ?>

                                                    </option>
                                                    <option value="spinomenal.vg.lobby">
                                                        <?php echo e(_i('Spinomenal')); ?>

                                                    </option>
                                                <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="order"><?php echo e(_i('Order (optional)')); ?></label>
                                    <input type="number" name="order" id="order" value="0" class="form-control" min="0">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="hidden" name="template_element_type" value="<?php echo e($template_element_type); ?>">
                                    <input type="hidden" name="section" value="<?php echo e($section); ?>">
                                    <button type="submit" class="btn u-btn-3d u-btn-primary" id="store"
                                            data-loading-text="<i class='fa fa-spin fa-spinner'></i> <?php echo e(_i('Uploading...')); ?>">
                                        <i class="hs-admin-upload"></i>
                                        <?php echo e(_i('Upload slider')); ?>

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
            let sliders = new Sliders();
            sliders.store();
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('back.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
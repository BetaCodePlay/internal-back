

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                <header
                    class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                    <div class="media">
                        <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                            <?php echo e(_i('Filters')); ?>

                        </h3>
                    </div>
                </header>
                <div class="card-block g-pa-15">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="device"><?php echo e(_i('Devices')); ?></label>
                                <select name="device[]"  id="device" class="form-control" multiple>
                                    <option value=""><?php echo e(_i('All')); ?></option>
                                    <option value="*"><?php echo e(_i('All devices')); ?></option>
                                    <option value="false"><?php echo e(_i('Desktop')); ?></option>
                                    <option value="true"><?php echo e(_i('Mobile')); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="language"><?php echo e(_i('Language')); ?></label>
                                <select name="language[]"  id="language" class="form-control" multiple>
                                    <option value=""><?php echo e(_i('All')); ?></option>
                                    <option value="*"><?php echo e(_i('All languages')); ?></option>
                                    <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($language['iso']); ?>">
                                            <?php echo e($language['name']); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="currency"><?php echo e(_i('Currency')); ?></label>
                                <select name="currency[]" id="currency" class="form-control" multiple>
                                    <option value=""><?php echo e(_i('All')); ?></option>
                                    <option value="*"><?php echo e(_i('All currencies')); ?></option>
                                    <?php $__currentLoopData = $whitelabel_currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($currency->iso); ?>" <?php echo e($currency->iso == session('currency') ? 'selected' : ''); ?>>
                                            <?php echo e($currency->iso == 'VEF' ? $free_currency->currency_name : $currency->iso . " ({$currency->name})"); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="status"><?php echo e(_i('Status')); ?></label>
                                <select name="status" id="status" class="form-control">
                                    <option value="true"><?php echo e(_i('Published')); ?></option>
                                    <option value="false"><?php echo e(_i('Unpublished')); ?></option>
                                </select>
                                <input type="hidden" id="template_element_type" name="template_element_type" value="<?php echo e($template_element_type); ?>">
                                <input type="hidden" id="section" name="section" value="<?php echo e($section); ?>">
                            </div>
                        </div>
                        <?php if(isset($menu)): ?>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="route"><?php echo e(_i('Menu where it will be shown')); ?></label>
                                    <select name="routes[]"  id="routes" class="form-control" multiple>
                                        <option value=""><?php echo e(_i('All')); ?></option>
                                        <?php $__currentLoopData = $menu; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($item->route); ?>">
                                                <?php echo e($item->name); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php if( \Dotworkers\Configurations\Configurations::getWhitelabel() == 112 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 116 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 76 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 124 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 44): ?>
                                            <option value="core.index">
                                                <?php echo e(_i('Home')); ?>

                                            </option>
                                        <?php endif; ?>
                                        <?php if(\Dotworkers\Configurations\Configurations::getWhitelabel() == 2 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 6 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 7 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 8 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 9 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 20 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 27 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 42 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 47 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 50 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 68 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 73 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 74 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 75 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 76 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 79 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 81 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 112 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 116 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 130 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 129
                                            || \Dotworkers\Configurations\Configurations::getWhitelabel() == 137 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 145 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 144 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 132 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 140 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 142 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 141 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 119 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 126 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 134 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 133 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 117 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 104): ?>
                                            <option value="pragmatic-play.live">
                                                <?php echo e(_i('Pragmatic Live Casino')); ?>

                                            </option>
                                        <?php endif; ?>
                                        <?php if(\Dotworkers\Configurations\Configurations::getWhitelabel() == 147 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 149 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 144): ?>
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
                                <button type="button" class="btn u-btn-3d u-btn-primary" id="update"
                                        data-loading-text="<i class='fa fa-spin fa-spinner'></i> <?php echo e(_i('Consulting...')); ?>">
                                    <i class="hs-admin-search"></i>
                                    <?php echo e(_i('Consult data')); ?>

                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                <header
                    class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                    <div class="media">
                        <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                            <?php echo e($title); ?>

                        </h3>
                        <div class="media-body d-flex justify-content-end">
                            <a href="<?php echo e(route('sliders.create', [$template_element_type, $section])); ?>" class="btn u-btn-3d u-btn-primary float-right">
                                <i class="hs-admin-upload"></i>
                                <?php echo e(_i('Upload')); ?>

                            </a>
                        </div>
                    </div>
                </header>
                <div class="card-block g-pa-15">
                    <div class="media">
                        <div class="media-body d-flex justify-content-end g-mb-10" id="table-buttons">

                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered w-100" id="sliders-table" data-route="<?php echo e(route('sliders.all', [$template_element_type, $section])); ?>">
                            <thead>
                            <tr>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Image')); ?>

                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Menu')); ?>

                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Start / End')); ?>

                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Language')); ?>

                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Currency')); ?>

                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Device')); ?>

                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Order')); ?>

                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Status')); ?>

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
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        $(function () {
            let sliders = new Sliders();
            sliders.all();
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('back.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
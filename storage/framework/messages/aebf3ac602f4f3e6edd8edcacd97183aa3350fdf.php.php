<footer id="footer"
        class="u-footer--bottom-sticky g-bg-white g-color-gray-dark-v6 g-brd-top g-brd-gray-light-v7 g-pa-20">
    <div class="row">
        <div class="offset-md-8 col-md-4 opt-footer">

        </div>
        <div class="col-md-12 opt-footer">
            <div class="opt-footer-ex">
                
                <div class="opt-footer-form-group">
                    <div class="form-group">
                        <select name="timezone" class="form-control change-timezone" data-route="<?php echo e(route('core.change-timezone')); ?>">
                            <?php $__currentLoopData = $global_timezones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $global_timezone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($global_timezone['timezone']); ?>" <?php echo e($global_timezone['timezone'] == session()->get('timezone') ? 'selected' : ''); ?>>
                                    <?php echo e($global_timezone['text']); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>

                
                <?php if(!empty($whitelabel_currencies) && count($whitelabel_currencies)>1): ?>
                    <div class="dropdown">
                        <button class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span><i class="fa fa-database"></i> <?php echo e(session('currency') == 'VEF' ? $free_currency->currency_name : session('currency')); ?></span> <i class="fa fa-caret-up"></i>
                        </button>

                        <div class="dropdown-menu">
                            <?php $__currentLoopData = $whitelabel_currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a class="dropdown-item" href="<?php echo e(route('core.change-currency', [$currency->iso])); ?>"><i class="fa fa-database"></i> <?php echo e($currency->iso == 'VEF' ? $free_currency->currency_name : $currency->iso . " ({$currency->name})"); ?></a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if(count($languages) > 1): ?>
                    <div class="dropdown">
                        <button class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span <?php echo e($selected_language['iso']); ?>><i class="fa fa-globe"></i> <?php echo e($selected_language['name']); ?></span> <i class="fa fa-caret-up"></i>
                        </button>

                        <div class="dropdown-menu">
                            <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a class="dropdown-item change-language" href="<?php echo e(route('core.change-language', [$language['iso']])); ?>" data-locale="<?php echo e($language['iso']); ?>"><img class="lang-flag" src="<?php echo e($language['flag']); ?>" alt="<?php echo e($language['name']); ?>"> <?php echo e($language['name']); ?></a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <small class="g-font-size-default">
                <?php echo e($whitelabel_info->copyright ? _i('Developed by Betsweet. Operated by') : ''); ?> <?php echo e($whitelabel_description); ?> © <?php echo e(_i('Copyright')); ?> - <?php echo e(date('Y')); ?>. <?php echo e(_i('All rights reserved')); ?>

            </small>
        </div>
    </div>
</footer>

<footer class="footer">
    <div class="footer-ex">
        <div class="footer-top">
            <div class="footer-top-left">
                <?php if(!empty($logo)): ?>
                    <?php if(!is_null($logo->img_dark)): ?>
                        <img src="<?php echo e($logo->img_dark); ?>" alt="Logo" width="180" height="37" class="img-logo-footer">
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <div class="footer-top-right">
                <a href="#"><?php echo e(_i('Help')); ?></a>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="footer-bottom-left">
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
            </div>

            <div class="footer-bottom-right">
                <a href="#"><?php echo e(_i('Legal information')); ?></a>
                <a href="#"><?php echo e(_i('Privacy policies')); ?></a>
            </div>
        </div>
    </div>
</footer>



<?php $__env->startSection('content'); ?>
    <form action="<?php echo e(route('sliders.update')); ?>" id="sliders-form" method="post" enctype="multipart/form-data">
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
                            <input type="file" name="image" id="show-image" class="opacity-0">
                        </div>
                        <div class="col-md-6">
                            <label class="">
                                <input type="checkbox" class="checkshow" name="personalize" autocomplete="off">
                                <span
                                    class="glyphicon glyphicon-ok"><?php echo e(_i('Enable only for moving sliders: ')); ?></span>
                            </label>
                            <div class="div_a_show">
                                <div class="noty_bar noty_type__warning noty_theme__unify--v1--dark g-mb-25">
                                    <div class="noty_body">
                                        <div class="g-mr-20">
                                            <div class="noty_body__icon">
                                                <i class="hs-admin-info"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <p>
                                                <?php echo e(_i('This image is only if you want to activate images with movement.The maximum file size is 5mb and the maximum width is 3440px')); ?>

                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="front"><?php echo e(_i('Image')); ?></label>
                                    <input type="file" name="front" id="show-front" class="opacity-0">
                                </div>
                            </div>
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
                                <a href="<?php echo e(route('sliders.index', [$slider->element_type_id, $slider->section])); ?>"
                                   class="btn u-btn-3d u-btn-primary float-right">
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
                                    <input type="text" name="url" id="url" class="form-control"
                                           value="<?php echo e($slider->url); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="start_date"><?php echo e(_i('Start date')); ?></label>
                                    <input type="text" name="start_date" id="start_date"
                                           class="form-control datetimepicker" value="<?php echo e($slider->start); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end_date"><?php echo e(_i('Finish date')); ?></label>
                                    <input type="text" name="end_date" id="end_date" class="form-control datetimepicker"
                                           value="<?php echo e($slider->end); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="device"><?php echo e(_i('Devices')); ?></label>
                                    <select name="device" id="device" class="form-control">
                                        <option
                                            value="*" <?php echo e($slider->mobile == '*' ? 'selected' : ''); ?>><?php echo e(_i('All')); ?></option>
                                        <option
                                            value="false" <?php echo e($slider->mobile == 'false' ? 'selected' : ''); ?>><?php echo e(_i('Desktop')); ?></option>
                                        <option
                                            value="true" <?php echo e($slider->mobile == 'true' ? 'selected' : ''); ?>><?php echo e(_i('Mobile')); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="language"><?php echo e(_i('Language')); ?></label>
                                    <select name="language" id="language" class="form-control">
                                        <option value="*" <?php echo e($slider->language == '*' ? 'selected' : ''); ?>>
                                            <?php echo e(_i('All')); ?>

                                        </option>
                                        <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option
                                                value="<?php echo e($language['iso']); ?>" <?php echo e($slider->language == $language['iso'] ? 'selected' : ''); ?>>
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
                                        <option value="*" <?php echo e($slider->currency_iso == '*' ? 'selected' : ''); ?>>
                                            <?php echo e(_i('All')); ?>

                                        </option>
                                        <?php $__currentLoopData = $whitelabel_currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option
                                                value="<?php echo e($currency->iso); ?>" <?php echo e($currency->iso == $slider->currency_iso ? 'selected' : ''); ?>>
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
                                        <option value="true" <?php echo e($slider->status ? 'selected' : ''); ?>>
                                            <?php echo e(_i('Published')); ?>

                                        </option>
                                        <option value="false" <?php echo e(!$slider->status ? 'selected' : ''); ?>>
                                            <?php echo e(_i('Unpublished')); ?>

                                        </option>
                                    </select>
                                </div>
                            </div>
                            <?php if(isset($menu)): ?>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="route"><?php echo e(_i('Menu where it will be shown')); ?></label>
                                        <select name="route" id="route" class="form-control">
                                            <?php $__currentLoopData = $menu; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option
                                                    value="<?php echo e($item->route); ?>" <?php echo e($item->route == $slider->route ? 'selected' : ''); ?>>
                                                    <?php echo e($item->name); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <option
                                                value="core.index" <?php echo e('core.index' == $slider->route ? 'selected' : ''); ?>>
                                                <?php echo e(_i('Home')); ?>

                                            </option>
                                        </select>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="order"><?php echo e(_i('Order (optional)')); ?></label>
                                    <input type="number" name="order" id="order" class="form-control"
                                           value="<?php echo e($slider->order); ?>">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="hidden" name="id" value="<?php echo e($slider->id); ?>">
                                    <input type="hidden" name="file" id="file" value="<?php echo e($slider->file); ?>">
                                    <input type="hidden" name="image" id="image" value="<?php echo e($slider->file); ?>">
                                    <input type="hidden" name="front" id="front" value="<?php echo e($slider->file); ?>">
                                    <input type="hidden" name="template_element_type"
                                           value="<?php echo e($slider->element_type_id); ?>">
                                    <input type="hidden" name="section" value="<?php echo e($slider->section); ?>">
                                    <button type="submit" class="btn u-btn-3d u-btn-primary" id="update"
                                            data-loading-text="<i class='fa fa-spin fa-spinner'></i> <?php echo e(_i('Updating...')); ?>">
                                        <i class="hs-admin-reload"></i>
                                        <?php echo e(_i('Update slider')); ?>

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
            sliders.update("<?php echo $slider->image; ?>", "show-image");
            sliders.update("<?php echo $slider->front; ?>", "show-front");
        });
    </script>
    <script>
        $(function () {

            // obtener campos ocultar div
            var checkbox = $(".checkshow");
            var hidden = $(".div_a_show");
            //

            hidden.hide();
            checkbox.change(function () {
                if (checkbox.is(':checked')) {
                    //hidden.show();
                    $(".div_a_show").fadeIn("200")
                } else {
                    //hidden.hide();
                    $(".div_a_show").fadeOut("200")
                    $('input[type=checkbox]').prop('checked', false);// limpia los valores de checkbox al ser ocultado

                }
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('back.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
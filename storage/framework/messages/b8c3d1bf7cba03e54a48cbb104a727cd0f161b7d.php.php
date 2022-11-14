

<?php $__env->startSection('content'); ?>
    <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
        <header class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
            <div class="media">
                <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                    <?php echo e($title); ?>

                </h3>
                <div class="media-body d-flex justify-content-end">
                    <a href="<?php echo e(route('email-templates-transaction.index')); ?>" class="btn u-btn-3d u-btn-primary float-right">
                        <i class="hs-admin-layout-list-thumb"></i>
                        <?php echo e(_i('Go to list')); ?>

                    </a>
                </div>
            </div>
        </header>
        <form action="<?php echo e(route('email-templates-transaction.update')); ?>" id="email-templates-transaction-form" method="post">
            <div class="card-block g-pa-15">
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="email_templates_type_id"><?php echo e(_i('Email templates')); ?></label>
                            <select name="email_templates_type_id" id="email_templates_type_id" class="form-control">
                                <option value=""><?php echo e(_i('Select...')); ?></option>
                                <?php $__currentLoopData = $emailTemplateTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emailTypes): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($emailTypes->id); ?>" <?php echo e($emailTypes->id == $template->email_templates_type_id ? 'selected' : ''); ?>>
                                        <?php echo e($emailTypes->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="title"><?php echo e(_i('Title')); ?></label>
                            <input type="text" name="title" id="title" class="form-control" autocomplete="off" value="<?php echo e($template->title); ?>">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="subject"><?php echo e(_i('Subject')); ?></label>
                            <input type="text" name="subject" id="subject" class="form-control" autocomplete="off" value="<?php echo e($template->subject); ?>">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group"><label for="language"><?php echo e(_i('Language')); ?></label>
                            <select name="language" id="language" class="form-control">
                                <option value="*" <?php echo e($template->language == '*' ? 'selected' : ''); ?>>
                                    <?php echo e(_i('All languages')); ?>

                                </option>
                                <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($language['iso']); ?>" <?php echo e($template->language == $language['iso'] ? 'selected' : ''); ?>>
                                        <?php echo e($language['name']); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="currency"><?php echo e(_i('Currency')); ?></label>
                            <select name="currency" id="currency" class="form-control">
                                <option value="*" <?php echo e($template->currency_iso == '*' ? 'selected' : ''); ?>>
                                    <?php echo e(_i('All currencies')); ?>

                                </option>
                                <?php $__currentLoopData = $whitelabel_currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($currency->iso); ?>" <?php echo e($currency->iso == session('currency') ? 'selected' : ''); ?>>
                                        <?php echo e($currency->iso == 'VEF' ? $free_currency->currency_name : $currency->iso . " ({$currency->name})"); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group"><label for="status"><?php echo e(_i('Status')); ?></label>
                            <select name="status" id="status" class="form-control">
                                <option value="true" <?php echo e($template->status ? 'selected' : ''); ?>>
                                    <?php echo e(_i('Active')); ?>

                                </option>
                                <option value="false" <?php echo e(!$template->status ? 'selected' : ''); ?>>
                                    <?php echo e(_i('Inactive')); ?>

                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <hr>
                        <iframe id="mosaico" src="<?php echo e(env('MOSAICO_SERVER')); ?>/editor2.html?route=<?php echo e(route('email-templates.upload-images')); ?>#<?php echo e($template->metadata->key); ?>" frameborder="0" width="100%" height="900px"></iframe>
                        <hr>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <input type="hidden" id="id" value="<?php echo e($template->id); ?>">
                            <button type="button" class="btn u-btn-3d u-btn-primary" id="update"
                                    data-loading-text="<i class='fa fa-spin fa-spinner'></i> <?php echo e(_i('Updating...')); ?>">
                                <i class="hs-admin-reload"></i>
                                <?php echo e(_i('Update template')); ?>

                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        $(function() {
            let emailTemplates = new EmailTemplates();
            emailTemplates.updateTransaction(<?php echo json_encode($template->metadata, 15, 512) ?>, <?php echo json_encode($template->content, 15, 512) ?>);
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('back.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


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
                    <div class="media-body d-flex justify-content-end">
                        <a href="<?php echo e(route('section-games.index', [$template_element_type, $section])); ?>" class="btn u-btn-3d u-btn-primary float-right">
                            <i class="hs-admin-layout-list-thumb"></i>
                            <?php echo e(_i('Go to list')); ?>

                        </a>
                    </div>
                </header>
                <div class="card-block g-pa-15">
                <form action="<?php echo e(route('section-games.store')); ?>" id="games-form" method="post">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="games"><?php echo e(_i('Games')); ?></label>
                                    <select name="games[]" id="games" class="form-control" multiple>
                                        <option value=""><?php echo e(_i('Select...')); ?></option>
                                        <?php $__currentLoopData = $games; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $game): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($game->id); ?>">
                                                <?php echo e($game->description); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <?php if($section == 'section-7'): ?>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="additional_info"><?php echo e(_i('Additional Info')); ?></label>
                                        <select name="additional_info" id="additional_info" class="form-control">
                                            <option value=""><?php echo e(_i('Select...')); ?></option>
                                            <?php $__currentLoopData = $additionals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $additional): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($additional); ?>">
                                                    <?php echo e($additional); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="hidden" name="section" value="<?php echo e($section); ?>">
                                    <button type="button" class="btn u-btn-3d u-btn-primary" id="save"
                                            data-loading-text="<i class='fa fa-spin fa-spinner'></i> <?php echo e(_i('Saving...')); ?>">
                                        <i class="hs-admin-save"></i>
                                        <?php echo e(_i('Save')); ?>

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
            let sectionGames = new SectionGames();
            sectionGames.store();
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('back.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
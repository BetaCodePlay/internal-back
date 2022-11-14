

<?php $__env->startSection('content'); ?>
    <form action="<?php echo e(route('dot-suite.free-spins.store')); ?>" id="slot-store-form" method="post">
        <div class="row">
            <div class="col-md-12">
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
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type_user"><?php echo e(_i('Type of user load')); ?></label>
                                    <select name="type_user" id="type_user" class="form-control">
                                        <option value=""><?php echo e(_i('Select...')); ?></option>
                                        <option value="1"><?php echo e(_i('Search')); ?></option>
                                        <option value="2"><?php echo e(_i('Segments')); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 d-none" id="user">
                                <div class="form-group">
                                    <label for="users"><?php echo e(_i('User')); ?></label>
                                    <select name="users[]" class="form-control" multiple>
                                        <option value=""><?php echo e(_i('Select...')); ?></option>
                                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($user->id); ?>">
                                                <?php echo e($user->username); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 d-none" id="segments">
                                <div class="form-group">
                                    <label for="segment"><?php echo e(_i('Segments')); ?></label>
                                    <select name="segment" id="segment" class="form-control">
                                        <option value=""><?php echo e(_i('Select...')); ?></option>
                                        <?php $__currentLoopData = $segments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $segment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($segment->id); ?>">
                                                <?php echo e($segment->name); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="amount"><?php echo e(_i('Amount')); ?></label>
                                    <input type="number" name="amount" id="amount" class="form-control" min="0">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="quantity"><?php echo e(_i('Quantity of turns')); ?></label>
                                    <input type="number" name="quantity" id="quantity" class="form-control" min="0">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="games"><?php echo e(_i('Games')); ?></label>
                                    <select name="game" id="game" class="form-control">
                                        <option value=""><?php echo e(_i('Select...')); ?></option>
                                        <?php $__currentLoopData = $games; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $game): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($game->game_id); ?>">
                                                <?php echo e($game->name); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="form-group">
                                        <label for="expiration_date"><?php echo e(_i('Expiration date')); ?></label>
                                        <input type="text" name="expiration_date" id="expiration_date"
                                               class="form-control datepicker" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="hidden" id="provider" name="provider" value="<?php echo e($provider); ?>">
                                    <button type="submit" class="btn u-btn-3d u-btn-primary" id="store"
                                            data-loading-text="<i class='fa fa-spin fa-spinner'></i> <?php echo e(_i('Creating...')); ?>">
                                        <i class="hs-admin-save"></i>
                                        <?php echo e(_i('Create')); ?>

                                    </button>
                                    <button type="button" class="btn u-btn-3d u-btn-bluegray" id="clear">
                                        <i class="hs-admin-close"></i>
                                        <?php echo e(_i('Clear')); ?>

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
            let dotSuite = new DotSuite();
            dotSuite.storeSlot();
            dotSuite.typeFormUsers();
        });
    </script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('back.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
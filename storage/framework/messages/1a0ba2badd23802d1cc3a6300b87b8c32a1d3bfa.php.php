

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
                    <form action="<?php echo e(route('whitelabels-games.store')); ?>" id="store-form" method="post">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="devices"><?php echo e(_i('Devices')); ?></label>
                                    <select name="devices" id="devices" class="form-control">
                                        <option value=""><?php echo e(_i('All')); ?></option>
                                        <option value="true"><?php echo e(_i('Mobile')); ?></option>
                                        <option value="false"><?php echo e(_i('Desktop')); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="change_provider"><?php echo e(_i('Provider')); ?></label>
                                    <select name="change_provider" id="change_provider" data-route="<?php echo e(route('whitelabels-games.game')); ?>" class="form-control">
                                        <option value=""><?php echo e(_i('Select...')); ?></option>
                                        <?php $__currentLoopData = $providers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $provider): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($provider->provider_id); ?>">
                                                <?php echo e($provider->name); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="games"><?php echo e(_i('Games')); ?></label>
                                    <select name="games[]" id="games" class="form-control" data-loading-text="<i class='fa fa-spin fa-spinner'></i>  <?php echo e(_i('Loading...')); ?>" multiple>
                                        <option value=""><?php echo e(_i('Select...')); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="game_category"><?php echo e(_i('Categories')); ?></label>
                                    <select name="game_category" id="game_category" class="form-control">
                                        <option value=""><?php echo e(_i('Select...')); ?></option>
                                        <?php $__currentLoopData = $games_categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $games_category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($games_category->id); ?>">
                                                <?php echo e($games_category->category); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="button" class="btn u-btn-3d u-btn-primary" id="store"
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
                <div class="card-block g-pa-15 g-pb-5">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="options_balance"><?php echo e(_i('Provider')); ?></label>
                                <select name="provider" id="provider" data-route="<?php echo e(route('whitelabels-games.game')); ?>" class="form-control">
                                    <option value=""><?php echo e(_i('All')); ?></option>
                                    <?php $__currentLoopData = $providers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $provider): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($provider->provider_id); ?>">
                                            <?php echo e($provider->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="categories_games"><?php echo e(_i('Category')); ?></label>
                                <select name="category" id="category" class="form-control">
                                    <option value=""><?php echo e(_i('All')); ?></option>
                                    <?php $__currentLoopData = $games_categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $games_category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($games_category->id); ?>">
                                            <?php echo e($games_category->category); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <button type="button" class="btn u-btn-3d u-btn-primary" id="search"
                                        data-loading-text="<i class='fa fa-spin fa-spinner'></i> <?php echo e(_i('Searching...')); ?>">
                                    <i class="hs-admin-search"></i>
                                    <?php echo e(_i('Search')); ?>

                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                <header class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                    <div class="media">
                        <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0 title">
                            <?php echo e(_i('Games')); ?>

                        </h3>
                        <div class="media-body d-flex justify-content-end" id="table-buttons">

                        </div>
                        <div class="justify-content-end g-ml-10" style="padding-left: 10px">
                        </div>
                    </div>
                </header>
                <div class="card-block g-pa-15">
                    <table class="table table-bordered table-responsive-sm w-100" id="games-table"  data-route="<?php echo e(route('whitelabels-games.all')); ?>">
                        <thead>
                        <tr>
                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                <?php echo e(_i('Provider')); ?>

                            </th>
                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                <?php echo e(_i('Game')); ?>

                            </th>
                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                <?php echo e(_i('Category')); ?>

                            </th>
                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                <?php echo e(_i('Device')); ?>

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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>

    <script>
        $(function () {
            let whitelabelsGames = new WhitelabelsGames()
            whitelabelsGames.all();
            whitelabelsGames.game();
            whitelabelsGames.store();
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('back.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
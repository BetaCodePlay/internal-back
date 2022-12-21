

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
                    <form action="<?php echo e(route('dot-suite.lobby-games.store')); ?>" id="store-form" method="post">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="provider"><?php echo e(_i('Provider')); ?></label>
                                    <select name="provider" id="provider" data-route="<?php echo e(route('dot-suite.lobby-games.game')); ?>" class="form-control">
                                        <option value=""><?php echo e(_i('Select...')); ?></option>
                                        <?php $__currentLoopData = $providers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $provider): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($provider->dotsuite_provider_id); ?>">
                                                <?php echo e($provider->name); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="">
                                    <input type="checkbox" class="checkshow" name="personalize" autocomplete="off">
                                    <span class="glyphicon glyphicon-ok"><?php echo e(_i('Games Personalize: ')); ?></span>
                                </label>
                                <div class="div_a_show">
                                    <div>
                                        <select name="games[]" id="games" class="form-control"
                                                data-loading-text="<i class='fa fa-spin fa-spinner'></i>  <?php echo e(_i('Loading...')); ?>"
                                                multiple>
                                            <option value=""><?php echo e(_i('Select')); ?></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <?php if(isset($menu)): ?>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="route"><?php echo e(_i('Menu where it will be shown')); ?></label>
                                        <select select name="route" id="route" class="form-control">
                                            <option value=""><?php echo e(_i('Select...')); ?></option>
                                            <?php $__currentLoopData = $menu; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($item->route); ?>">
                                                    <?php echo e($item->name); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php if(\Dotworkers\Configurations\Configurations::getWhitelabel() == 2 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 6 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 7 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 8 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 9 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 20 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 27 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 42 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 47 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 50 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 68 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 73 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 74 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 75 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 76 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 79 || \Dotworkers\Configurations\Configurations::getWhitelabel() == 81 ): ?>
                                                <option value="pragmatic-play.live">
                                                    <?php echo e(_i('Pragmatic Live Casino')); ?>

                                                </option>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="div_a_show col-md-6">
                                <div class="form-group">
                                    <label for="order"><?php echo e(_i('Order (optional)')); ?></label>
                                    <input type="number" name="order" id="order" value="0" class="form-control" min="0">
                                </div>
                            </div>
                            <br>
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
                            <label for="change_provider"><?php echo e(_i('Provider')); ?></label>
                            <select name="change_provider" id="change_provider" data-route="<?php echo e(route('dot-suite.lobby-games.game')); ?>" class="form-control">
                                <option value=""><?php echo e(_i('All')); ?></option>
                                <?php $__currentLoopData = $providers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $provider): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($provider->dotsuite_provider_id); ?>">
                                        <?php echo e($provider->name); ?>

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
                <table class="table table-bordered table-responsive-sm w-100" id="games-table" data-route="<?php echo e(route('dot-suite.lobby-games.all')); ?>">
                    <thead>
                    <tr>
                        <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                            <?php echo e(_i('Provider')); ?>

                        </th>
                        <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                            <?php echo e(_i('Game')); ?>

                        </th>
                        <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                            <?php echo e(_i('Menu')); ?>

                        </th>
                        <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                            <?php echo e(_i('Order')); ?>

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
        let dotSuiteGames = new DotSuiteGames()
        dotSuiteGames.all();
        dotSuiteGames.game();
        dotSuiteGames.store();
    });
</script>
<script>
    $(function() {

        // obtener campos ocultar div
        var checkbox = $(".checkshow");
        var hidden = $(".div_a_show");
        //

        hidden.hide();
        checkbox.change(function() {
            if (checkbox.is(':checked')) {
                //hidden.show();
                $(".div_a_show").fadeIn("200")
            } else {
                //hidden.hide();
                $(".div_a_show").fadeOut("200")
                $('input[type=checkbox]').prop('checked',false);// limpia los valores de checkbox al ser ocultado

            }
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('back.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
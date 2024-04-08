

<?php $__env->startSection('content'); ?>
        <div class="row">
            <div class="col-md-12">
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                    <header
                        class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                <?php echo e(_i('Search by filter')); ?>

                            </h3>
                        </div>
                    </header>
                    <div class="card-block g-pa-15">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="currency"><?php echo e(_i('Currency')); ?></label>
                                    <select name="currency"  id="currency" class="form-control" data-route="<?php echo e(route('dot-suite.currency-users')); ?>" data-provider="<?php echo e(\Dotworkers\Configurations\Enums\Providers::$pragmatic_play); ?>">
                                        <option value=""><?php echo e(_i('Select...')); ?></option>
                                        <?php $__currentLoopData = $whitelabel_currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($currency->iso); ?>">
                                                <?php echo e($currency->iso == 'VEF' ? $free_currency->currency_name : $currency->iso . " ({$currency->name})"); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="submit" class="btn u-btn-3d u-btn-primary" id="search"
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
                    <header
                        class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                <?php echo e($title); ?>

                            </h3>
                            <div class="media-body d-flex justify-content-end">
                                <a href="<?php echo e(route('dot-suite.free-spins')); ?>" class="btn u-btn-3d u-btn-primary float-right">
                                    <i class="hs-admin-plus"></i>
                                    <?php echo e(_i('New')); ?>

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
                            <table class="table table-bordered w-100" id="free-spins-list-table" data-route="<?php echo e(route('dot-suite.cancel-free-list')); ?>">
                                <thead>
                                <tr>
                                    <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                        <?php echo e(_i('Code bonus')); ?>

                                    </th>
                                    <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                        <?php echo e(_i('Currency')); ?>

                                    </th>
                                    <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                        <?php echo e(_i('Quantity of turns')); ?>

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
            let dotSuite = new DotSuite();
            dotSuite.cancelFreeRoundsPragmatic();
            dotSuite.freeSpinsPragmaticList();
        });
    </script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('back.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-xl-12">
            <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                <header class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                    <div class="media">
                        <h3 class="d-flex align-self-center text-uppercase g-font-size-12 g-font-size-default--md g-color-black mb-0">
                            <?php echo e($title); ?>

                        </h3>
                    </div>
                </header>
                <div class="card-block g-pa-15">
                    <div class="media">
                        <div class="media-body d-flex justify-content-end g-mb-10" id="table-buttons">

                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered w-100" id="exchange-rates-table" data-route="<?php echo e(route('core.update-exchange-rates')); ?>">
                            <thead>
                            <tr>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Currency')); ?>

                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none text-right">
                                    <?php echo e(_i('Last update')); ?>

                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none text-right">
                                    <?php echo e(_i('Rate')); ?>

                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $__currentLoopData = $exchange_rates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($rate->currency_iso); ?></td>
                                    <td class="text-right">
                                        <span id="updated">
                                            <?php echo e($rate->updated); ?>

                                        </span>
                                    </td>
                                    <td class="d-flex justify-content-end form-inline">
                                        <div class="input-group">
                                            <input type="text" id="rate-<?php echo e($rate->id); ?>" class="form-control" value="<?php echo e($rate->amount); ?>">
                                            <div class="input-group-append">
                                                <button class="btn u-btn-primary update-exchange" type="button" data-rate="<?php echo e($rate->id); ?>" data-loading-text="<i class='fa fa-spin fa-spinner'></i>">
                                                    <i class="hs-admin-reload"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
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
            let core = new Core();
            core.exchangeRates();
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('back.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
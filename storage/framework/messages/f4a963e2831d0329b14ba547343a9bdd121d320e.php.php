

<?php $__env->startSection('content'); ?>
    <form action="<?php echo e(route('betpay.clients.update')); ?>" id="clients-form" method="post">
        <div class="row">
            <div class="col-md-12">
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                    <header
                        class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                <?php echo e($title); ?>

                            </h3>
                            <div class="media-body d-flex justify-content-end">
                                <a href="<?php echo e(route('betpay.clients.index')); ?>" class="btn u-btn-3d u-btn-primary float-right">
                                    <i class="hs-admin-layout-list-thumb"></i>
                                    <?php echo e(_i('Go to list')); ?>

                                </a>
                            </div>
                        </div>
                    </header>
                    <div class="card-block g-pa-15">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name"><?php echo e(_i('Name')); ?></label>
                                    <input type="text" name="name" id="name" class="form-control" value="<?php echo e($client->name); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="endpoin"><?php echo e(_i('End point')); ?></label>
                                    <input type="text" name="endpoint" id="endpoint" class="form-control" value="<?php echo e($client->endpoint); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="secret"><?php echo e(_i('Secret')); ?></label>
                                    <input type="text" name="secret" id="secret" class="form-control" value="<?php echo e($client->secret); ?>">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="hidden" name="id" value="<?php echo e($client->id); ?>">

                                    <button type="submit" class="btn u-btn-3d u-btn-primary" id="update"
                                            data-loading-text="<i class='fa fa-spin fa-spinner'></i> <?php echo e(_i('Updating...')); ?>">
                                        <i class="hs-admin-reload"></i>
                                        <?php echo e(_i('Update client')); ?>

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
            let  betpay= new BetPay();
            betpay.updateClient();
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('back.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
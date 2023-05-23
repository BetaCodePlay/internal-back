

<?php $__env->startSection('content'); ?>
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
                <form action="<?php echo e(route('users.transactions-by-lot-file')); ?>" id="transactions-by-lot-form" method="post" enctype="multipart/form-data">
                    <div class="col-md-12">
                        <div class="card-block g-pa-15">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="transactions-by-lot-file"><?php echo e(_i('Transactions file')); ?></label>
                                        <div class="form-group">
                                            <input type="file" name="transactions-by-lot-file" id="transactions-by-lot-file"/>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="description"><?php echo e(_i('Description')); ?></label>
                                        <input type="text" class="form-control" name="description" id="description">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="button" class="btn u-btn-3d u-btn-primary" id="transactions-by-lot"
                                                data-loading-text="<i class='fa fa-spin fa-spinner'></i> <?php echo e(_i('Loading...')); ?>">
                                            <i class="hs-admin-save"></i>
                                            <?php echo e(_i('Upload')); ?>

                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-12 show">
            <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                <header
                    class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                    <div class="media">
                        <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0 title">
                            <?php echo e(_i('Import results')); ?>

                        </h3>
                        <div class="media-body d-flex justify-content-end" id="transactions-lot-buttons">

                        </div>
                    </div>
                </header>
                <div class="card-block g-pa-15">
                    <div class="table-responsive">
                        <table class="table table-bordered w-100" id="transactions-lot-table">
                            <thead>
                            <tr>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Username')); ?>

                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Currency')); ?>

                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Amount')); ?>

                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Description')); ?>

                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Attribute')); ?>

                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Error')); ?>

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
            let users = new Users();
            users.transactionsByLot();
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('back.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
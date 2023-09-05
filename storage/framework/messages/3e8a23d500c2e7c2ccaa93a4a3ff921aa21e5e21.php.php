

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
            <header
                class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                <div class="media">
                    <h3 class="d-flex align-self-center text-uppercase g-font-size-12 g-font-size-default--md g-color-black mb-0">
                        <?php echo e($title); ?>

                    </h3>
                </div>
            </header>
            <div class="card-block g-pa-15">
                <div class="media">
                    <div class="media-body d-flex justify-content-start g-mb-10" id="table-buttons">

                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover dt-responsive" id="tableMyUsers"
                           data-route="<?php echo e(route('users.list.by-owner')); ?>" width="100%">
                        <thead>
                        <tr>
                            <th> <?php echo e(_i('ID')); ?></th>
                            <th> <?php echo e(_i('name')); ?></th>
                            <th> <?php echo e(_i('date')); ?></th>
                            <th> <?php echo e(_i('details')); ?></th>

                        </tr>
                        </thead>
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
            users.getMyUsers([50, 100, 500, 1000, 2000]);
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('back.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
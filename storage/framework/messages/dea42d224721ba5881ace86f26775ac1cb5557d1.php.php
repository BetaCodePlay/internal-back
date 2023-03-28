
<?php $__env->startSection('styles'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

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
            <div class="table-reponsive">

            <table id="exampleTable" class="table table-bordered table-hover dt-responsive"  width="100%">
                <?php echo $__env->make('back.layout.litepicker', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <thead>
                <tr>
                    <th> <?php echo e(_i('Date')); ?></th>
                    <th> <?php echo e(_i('Description')); ?></th>


                    <th> <?php echo e(_i('Debit')); ?></th>
                    <th> <?php echo e(_i('Credit')); ?></th>





                    <th> <?php echo e(_i('Balance')); ?></th>
                </tr>
                </thead>
            </table>
        </div>



        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        $(function () {
            let agents = new Agents();
            agents.transactionTimeline('<?php echo e(route('reports.data.transaction.timeline')); ?>','#exampleTable',[10,20,50,100]);
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('back.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
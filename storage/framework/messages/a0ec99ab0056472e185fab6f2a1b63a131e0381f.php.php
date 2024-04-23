

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('back.layout.litepicker', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
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
            <div class="table-responsive" id="financial-state-table" data-route="<?php echo e(route('agents.reports.financial-state-data.username')); ?>">

            </div>
            <br><br>
            <div class="table-responsive" id="financial-state-table" data-route="<?php echo e(route('agents.reports.financial-state-data.provider')); ?>">

            </div>
            






















































        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        $(function () {
            let agents = new Agents();
            agents.financialState(<?php echo e($user); ?>);

            // financialStateDetails(user = null) {
            //     let picker = initLitepickerEndToday();
            //     let $table = $('#financial-state-table');
            //     let $button = $('#update');
            //     let api;
            //     if (user == null) {
            //         $('#financial-state-tab').on('show.bs.tab', function () {
            //             $table.children().remove();
            //             user = $('.user').val();
            //         });
            //     }
            //
            //     $button.click(function () {
            //         $button.button('loading');
            //         let startDate = moment(picker.getStartDate()).format('YYYY-MM-DD');
            //         let endDate = moment(picker.getEndDate()).format('YYYY-MM-DD');
            //
            //         $.ajax({
            //             url: `${$table.data('route')}/${user}/${startDate}/${endDate}`,
            //             type: 'get',
            //             dataType: 'json'
            //
            //         }).done(function (json) {
            //             $table.html(json.data.table);
            //
            //         }).fail(function (json) {
            //             swalError(json);
            //
            //         }).always(function () {
            //             $button.button('reset');
            //         });
            //     });
            // }

        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('back.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
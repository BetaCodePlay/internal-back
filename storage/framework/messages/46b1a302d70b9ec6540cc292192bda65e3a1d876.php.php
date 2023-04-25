

<?php $__env->startSection('styles'); ?>
    <style>
        #financial-state-table .bg-warning {
            background-color: rgba(255, 193, 7, 0.4) !important;
        }

        #financial-state-table .bg-primary {
            background-color: rgba(0, 123, 255, .4) !important;
        }

        #financial-state-table .bg-success {
            background-color: rgba(40, 167, 69, .4) !important
        }

        .init_tree {
            color: rgb(77 77 77) !important
        }

        .init_agent {
            color: #3398dc !important;
            font-weight: bold !important;
        }

        .init_user {
            color: #e62154 !important;
            font-weight: bold !important;
        }

        .nav_link_blue {
            color: white !important;
            background-color: #38a7ef !important;
        }

        /*#dashboard {*/
        /*    border-color: #38a7ef;*/
        /*    border-top-style: solid;*/
        /*    border-right-style: solid;*/
        /*    border-bottom-style: solid;*/
        /*    border-left-style: solid;*/
        /*}*/
        .nav_link_red {
            color: white !important;
            background-color: #e62154 !important
        }

        .nav_link_green {
            color: white !important;
            background-color: green !important
        }

        .nav_link_orange {
            color: white !important;
            background-color: darkorange !important
        }

    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-lg-12 col-xl-12">
            <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                <header
                    class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                    <div class="media">
                        <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 g-mb-0">
                            <?php echo e($title); ?>

                        </h3>
                        <div class="media-body d-flex justify-content-end g-mb-10" id="table-buttons-wallet">

                        </div>
                        <div class="d-none d-sm-none d-md-block">
                            <div class="justify-content-end g-ml-10" style="padding-left: 10px">
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                
                            </div>
                        </div>
                    </div>
                </header>
                <div class="card-block g-pa-15">
                    <div class="d-block d-sm-block d-md-none g-pa-10">
                        <div class="row">
                            <div class="col-12 g-py-5 g-pa-5">
                                <?php if(!in_array(\Dotworkers\Security\Enums\Roles::$admin_Beet_sweet, session('roles'))): ?>
                                    <select name="agent_id_search" id="username_search"
                                            class="form-control select2 username_search agent_id_search"
                                            data-route="<?php echo e(route('agents.search-username')); ?>"
                                            data-select="<?php echo e(route('agents.reports.find-user-payment')); ?>">
                                        <option></option>
                                    </select>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <div id="tree" data-route="<?php echo e(route('agents.reports.find-user-payment')); ?>" data-json="<?php echo e($tree); ?>"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <?php echo $__env->make('back.layout.litepicker', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
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
                        <table class="table table-bordered w-100" id="agent-payment-transactions-table"
                               data-route="<?php echo e(route('agents.reports.find-user-payment')); ?>">
                            <thead>
                            <tr>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Agent / User')); ?>

                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Loads (Total)')); ?>

                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Downloads (Total)')); ?>

                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Totals 100%')); ?>

                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Commission %')); ?>

                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Total to pay %')); ?>

                                </th>
                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                    <?php echo e(_i('Total receivable %')); ?>

                                </th>
                            </tr>
                            </thead>
                            <tbody>
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
            let agents = new Agents();
            agents.agentsPayments();
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('back.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
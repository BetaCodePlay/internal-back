

<?php $__env->startSection('styles'); ?>
    <style>
        #financial-state-table .bg-warning {
            background-color: rgba(255, 193, 7, 0.4) !important;
        }

        #financial-state-table .bg-primary {
            background-color: rgba(0, 123, 255,.4) !important;
        }

        #financial-state-table .bg-success {
            background-color: rgba(40, 167, 69,.4) !important
        }
        .init_tree{
            color: rgb(77 77 77) !important
        }
         .init_agent{
             color: #3398dc !important;
             font-weight: bold!important;
         }
        .init_user{
            color: #e62154 !important;
            font-weight: bold!important;
        }
        .nav_link_blue {
            color: white!important;
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
            color: white!important;
            background-color:  #e62154 !important
        }
        .nav_link_green {
            color: white!important;
            background-color:  green !important
        }
        .nav_link_orange {
            color: white!important;
            background-color:  darkorange !important
        }

    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-lg-3 col-xl-4">
            <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                <header class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
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
                                    <select name="agent_id_search" id="username_search" class="form-control select2 username_search agent_id_search" data-route="<?php echo e(route('agents.search-username')); ?>" data-select="<?php echo e(route('agents.find-user')); ?>">
                                        <option></option>
                                    </select>
                                <?php endif; ?>

                            </div>
                            <div class="col-6 g-py-5">
                                <a href="#add-users-modal" data-toggle="modal" class="btn u-btn-3d u-btn-primary btn-block" id="new-user">
                                    <i class="hs-admin-plus"></i>
                                    <?php echo e(_i(' Player')); ?>

                                </a>
                            </div>
                            <?php if($agent->master): ?>
                                <div class="col-6 g-py-5">
                                    <a href="#add-agents-modal" data-toggle="modal" class="btn u-btn-3d u-btn-blue btn-block" id="new-agent">
                                        <i class="hs-admin-plus"></i>
                                        <?php echo e(_i(' Agent')); ?>

                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                        </div>
                    </div>
                    <div class="">
                        <div id="tree" data-route="<?php echo e(route('agents.find')); ?>" data-json="<?php echo e($tree); ?>"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-9 col-xl-8">
            <div class="d-none d-sm-none d-md-block">
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30 g-pa-15">
                    <div class="d-block d-sm-block d-md-none">
                        <header class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                            <div class="media">
                                <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                    <?php echo e($title); ?>

                                </h3>
                            </div>
                        </header>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-8 g-py-5 g-pa-5">
                            <?php if(!in_array(\Dotworkers\Security\Enums\Roles::$admin_Beet_sweet, session('roles'))): ?>
                                <select name="agent_id_search" id="agent_id_search"  class="form-control select2 agent_id_search" data-route="<?php echo e(route('agents.search-username')); ?>" data-select="<?php echo e(route('agents.find-user')); ?>">
                                    <option></option>
                                </select>
                            <?php endif; ?>
                        </div>
                        <div class="col-6 col-md-2 g-py-5">
                            <a href="#add-users-modal" data-toggle="modal" class="btn u-btn-3d u-btn-primary btn-block" id="new-user">
                                <i class="hs-admin-plus"></i>
                                <?php echo e(_i(' Player')); ?>

                            </a>
                        </div>
                        <?php if($agent->master): ?>
                            <div class="col-6 col-md-2 g-py-5">
                                <a href="#add-agents-modal" data-toggle="modal" class="btn u-btn-3d u-btn-blue btn-block" id="new-agent">
                                    <i class="hs-admin-plus"></i>
                                    <?php echo e(_i(' Agent ')); ?>

                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                <div class="card-block g-pa-15">
                    <div class="d-block d-sm-block d-md-none">
                        <div class="d-flex align-self-center justify-content-end">
                            <div class="g-pos-rel g-top-3 d-inline-block">
                                <a id="agents-menu-invoker" class="d-block g-text-underline--none--hover text-dark" href="#!" aria-controls="agents-menu" aria-haspopup="true" aria-expanded="false" data-dropdown-event="click" data-dropdown-target="#agents-menu" data-dropdown-type="css-animation"
                                   data-dropdown-duration="300" data-dropdown-animation-in="fadeIn" data-dropdown-animation-out="fadeOut">
                                <span class="g-pos-rel g-left-70">
                                    <i class="hs-admin-angle-down g-pos-rel g-top-2 g-ml-10"></i> <?php echo e(_i('Options')); ?>

                                </span>
                                </a>
                                <ul id="agents-menu" class="languages-menu-pro g-z-index-9999 g-pos-abs g-left-0 g-nowrap g-font-size-14 g-py-20 g-mt-10 rounded" aria-labelledby="agents-menu-invoker">
                                    <li class="mb-0">
                                        <a href="#dashboard" id="dashboard-mobile" data-target="#dashboard" aria-controls="dashboard" aria-selected="true">
                                            <i class="hs-admin-dashboard"></i>
                                            <?php echo e(_i('Dashboard')); ?>

                                        </a>
                                    </li>
                                    <li class="mb-0">
                                        <a href="#agents-transactions" id="agents-transactions-mobile" data-target="#agents-transactions" aria-controls="agents-transactions" aria-selected="false">
                                            <i class="hs-admin-layout-list-thumb"></i>
                                            <?php echo e(_i('Transactions')); ?>

                                        </a>
                                    </li>
                                    <li class="mb-0">
                                        <a class="d-none" data-target="#users-transactions" href="#users-transactions" id="users-transactions-mobile" aria-controls="users-transactions" aria-selected="false">
                                            <i class="hs-admin-layout-list-thumb"></i>
                                            <?php echo e(_i('Transactions')); ?>

                                        </a>
                                    </li>
                                    <li class="mb-0">
                                        <a data-target="#users" href="#users" id="users-mobile" aria-controls="users" aria-selected="false">
                                            <i class="hs-admin-user"></i>
                                            <?php echo e(_i('Players')); ?>

                                        </a>
                                    </li>
                                    <?php if($agent->master): ?>
                                        <li class="mb-0">
                                            <a data-target="#agents" href="#agents" id="agents-mobile" aria-controls="agents" aria-selected="false">
                                                <i class="hs-admin-briefcase"></i>
                                                <?php echo e(_i('Agents')); ?>

                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    <li class="mb-0">
                                        <a data-target="#financial-state" href="#financial-state" id="financial-state-mobile" aria-controls="agents" aria-selected="false">
                                            <i class="hs-admin-pie-chart"></i>
                                            <?php echo e(_i('Financial state')); ?>

                                        </a>
                                    </li>
                                    <?php if($agent->master): ?>
                                        <li class="mb-0">
                                            <a class="d-none" data-target="#locks" href="#locks" id="locks-mobile" aria-controls="agents" aria-selected="false">
                                                <i class="hs-admin-lock"></i>
                                                <?php echo e(_i('Locks')); ?>

                                            </a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="d-none d-sm-none d-md-block">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active nav_link_blue" id="dashboard-tab" data-toggle="tab" href="#dashboard" role="tab" aria-controls="dashboard" aria-selected="true">
                                    <i class="hs-admin-dashboard"></i>
                                    <?php echo e(_i('Dashboard')); ?>

                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link nav_link_red" id="agents-transactions-tab" data-toggle="tab" href="#agents-transactions" role="tab" aria-controls="agents-transactions" aria-selected="false">
                                    <i class="hs-admin-layout-list-thumb"></i>
                                    <?php echo e(_i('Transactions')); ?>

                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link d-none nav_link_red" id="users-transactions-tab" data-toggle="tab" href="#users-transactions" role="tab" aria-controls="users-transactions" aria-selected="false">
                                    <i class="hs-admin-layout-list-thumb"></i>
                                    <?php echo e(_i('Transactions')); ?>

                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link nav_link_blue" id="users-tab" data-toggle="tab" href="#users" role="tab" aria-controls="users" aria-selected="false">
                                    <i class="hs-admin-user"></i>
                                    <?php echo e(_i('Players')); ?>

                                </a>
                            </li>
                            <?php if($agent->master): ?>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link nav_link_red" id="agents-tab" data-toggle="tab" href="#agents" role="tab" aria-controls="agents" aria-selected="false">
                                        <i class="hs-admin-briefcase"></i>
                                        <?php echo e(_i('Agents')); ?>

                                    </a>
                                </li>
                            <?php endif; ?>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link nav_link_green" id="financial-state-tab" data-toggle="tab" href="#financial-state" role="tab" aria-controls="agents" aria-selected="false">
                                    <i class="hs-admin-pie-chart"></i>
                                    <?php echo e(_i('Financial state')); ?>

                                </a>
                            </li>
                            <?php if($agent->master): ?>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link d-none nav_link_orange" id="locks-tab" data-toggle="tab" href="#locks" role="tab" aria-controls="agents" aria-selected="false">
                                        <i class="hs-admin-lock"></i>
                                        <?php echo e(_i('Locks')); ?>

                                    </a>
                                </li>
                            <?php endif; ?>






                        </ul>
                    </div>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active mobile g-py-20 g-px-5" id="dashboard" role="tabpanel" aria-labelledby="dashboard-tab">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row g-mb-15">
                                        <div class="col-4 col-sm-4 col-md-3 g-mb-5 g-mb-0--md g-mb-10 align-self-center">
                                            <label class="g-mb-0">
                                                <strong><?php echo e(_i('Username')); ?></strong>
                                            </label>
                                        </div>
                                        <div class="col-4 col-sm-7 col-md-7 align-self-center">
                                            <div class="form-group g-pos-rel g-mb-0">
                                                <span id="username"></span>
                                            </div>
                                        </div>
                                        <div class="col-4 col-sm-2 col-md-2 align-self-center" id="modals-transaction">
                                            <div class="d-block d-sm-block d-md-none">
                                                <div class="row">
                                                    <div class="form-group mb-0">
                                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [\Dotworkers\Security\Enums\Permissions::$agents_credit_transactions])): ?>
                                                            <label href="#transaction-modal" class="btn u-btn-3d u-btn-blue" data-toggle="modal" data-transaction-type="<?php echo e(\Dotworkers\Configurations\Enums\TransactionTypes::$credit); ?>" data-transaction-name="<?php echo e(_i('credit')); ?>">
                                                                <i class="hs-admin-plus"></i>
                                                            </label>
                                                        <?php endif; ?>
                                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [\Dotworkers\Security\Enums\Permissions::$agents_debit_transactions])): ?>
                                                            <label href="#transaction-modal" class="btn u-btn-3d u-btn-primary" data-toggle="modal" data-transaction-type="<?php echo e(\Dotworkers\Configurations\Enums\TransactionTypes::$debit); ?>" data-transaction-name="<?php echo e(_i('debit')); ?>">
                                                                <i class="hs-admin-layout-line-solid"></i>
                                                            </label>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <?php if(!in_array(\Dotworkers\Security\Enums\Roles::$admin_Beet_sweet, session('roles'))): ?>
                                        <div class="row g-mb-15">
                                            <div class="col-4 col-sm-4 col-md-3 g-mb-5 g-mb-0--md g-mb-10 align-self-center">
                                                <label class="g-mb-0">
                                                    <strong> <?php echo e(_i('Code')); ?></strong>
                                                </label>
                                            </div>
                                            <div class="col-4 col-sm-5 col-md-4 align-self-center">
                                                <div class="form-group g-pos-rel g-mb-0">
                                                    <span id="referral_code"></span>
                                                </div>
                                            </div>
                                            <div class="col-4 col-sm-3 col-md-5 align-self-center">
                                                <div class="form-group g-pos-rel g-mb-0">
                                                    <button class="btn g-width-40 g-height-40 u-btn-primary g-rounded-4 u-btn-3d btn-sm clipboard"
                                                            type="button" type="button" id="clipboard" data-title="<?php echo e(_i('Copied')); ?>">
                                                        <i class="hs-admin-clipboard g-absolute-centered g-font-size-16 g-color-white"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-mb-15">
                                            <div class="col-4 col-sm-4 col-md-3 g-mb-5 g-mb-0--md g-mb-10 align-self-center">
                                                <label class="g-mb-0">
                                                    <strong><?php echo e(_i('Timezone')); ?></strong>
                                                </label>
                                            </div>
                                            <div class="col-8 col-sm-8 col-md-9 align-self-center">
                                                <div class="form-group g-pos-rel g-mb-0">
                                                    <span id="agent_timezone"></span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <div class="row g-mb-15">
                                        <div class="col-4 col-sm-4 col-md-3 g-mb-5 g-mb-0--md g-mb-10 align-self-center">
                                            <label class="g-mb-0">
                                                <strong><?php echo e(_i('Balance')); ?></strong>
                                            </label>
                                        </div>
                                        <div class="col-8 col-sm-8 col-md-9 align-self-center">
                                            <div class="form-group g-pos-rel g-mb-0">
                                                <span class="balance"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-mb-15">
                                        <div class="col-4 col-sm-4 col-md-3 g-mb-5 g-mb-0--md g-mb-10 align-self-center">
                                            <label class="g-mb-0">
                                                <strong> <?php echo e(_i('Type')); ?></strong>
                                            </label>
                                        </div>
                                        <div class="col-8 col-sm-8 col-md-9 align-self-center">
                                            <div class="form-group g-pos-rel g-mb-0">
                                                <span id="user_type"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-mb-15">
                                        <div class="col-4 col-sm-4 col-md-3 g-mb-5 g-mb-0--md g-mb-10 align-self-center">
                                            <label class="g-mb-0">
                                                <strong><?php echo e(_i('Status')); ?></strong>
                                            </label>
                                        </div>
                                        <div class="col-8 col-sm-8 col-md-9 align-self-center">
                                            <div class="form-group g-pos-rel g-mb-0">
                                                <span id="status"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-mb-15">
                                        <div class="col-4 col-sm-4 col-md-3 g-mb-5 g-mb-0--md g-mb-10 align-self-center">
                                            <label class="g-mb-0">
                                                <strong><?php echo e(_i('Password')); ?></strong>
                                            </label>
                                        </div>
                                        <div class="col-8 col-sm-8 col-md-9 align-self-center">
                                            <div class="form-group g-pos-rel g-mb-0">
                                                <a href="#reset-password-modal" class="btn u-btn-3d u-btn-primary btn-sm" data-toggle="modal">
                                                    <?php echo e(_i('Reset')); ?>

                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if($agent->master): ?>
                                        <div class="row g-mb-15 d-none" id="move-agents-user">
                                            <div class="col-4 col-sm-4 col-md-3 g-mb-5 g-mb-0--md g-mb-10 align-self-center">
                                                <label class="g-mb-0">
                                                    <strong><?php echo e(_i('Move user')); ?></strong>
                                                </label>
                                            </div>
                                            <div class="col-8 col-sm-8 col-md-9 align-self-center">
                                                <div class="form-group g-pos-rel g-mb-0">
                                                    <a href="#move-agents-users-modal" id="move-agents-users"
                                                       class="btn u-btn-3d u-btn-blue btn-sm" data-toggle="modal">
                                                        <?php echo e(_i('Move')); ?>

                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-mb-15 d-none" id="move-agents">
                                            <div
                                                class="col-4 col-sm-4 col-md-3 g-mb-5 g-mb-0--md g-mb-10 align-self-center">
                                                <label class="g-mb-0">
                                                    <strong><?php echo e(_i('Move agent')); ?></strong>
                                                </label>
                                            </div>
                                            <div class="col-8 col-sm-8 col-md-9 align-self-center">
                                                <div class="form-group g-pos-rel g-mb-0">
                                                    <a href="#move-agents-modal" id="move-agents"
                                                       class="btn u-btn-3d u-btn-blue btn-sm" data-toggle="modal">
                                                        <?php echo e(_i('Move')); ?>

                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [\Dotworkers\Security\Enums\Permissions::$agents_credit_transactions,  \Dotworkers\Security\Enums\Permissions::$agents_debit_transactions])): ?>
                                    <div class="col-md-6">
                                        <div class="d-none d-sm-none d-md-block">
                                            <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30 d-none" id="transactions-form-container">
                                                <header class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                                                    <div class="media">
                                                        <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                                            <?php echo e(_i('Balance adjustments')); ?>

                                                        </h3>
                                                    </div>
                                                </header>
                                                <div class="card-block g-pa-15">
                                                    <form action="<?php echo e(route('agents.perform-transactions')); ?>" id="transactions-form" method="post">
                                                        <input type="hidden" name="wallet" id="wallet">
                                                        <input type="hidden" name="user" class="user">
                                                        <input type="hidden" name="type" id="type">
                                                        <div class="form-group">
                                                            <label for="amount"><?php echo e(_i('Amount')); ?></label>
                                                            <input type="number" name="amount" id="amount" class="form-control" min="0">
                                                        </div>
                                                        <div class="row">
                                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [\Dotworkers\Security\Enums\Permissions::$agents_credit_transactions])): ?>
                                                                <div class="col-6">
                                                                    <button type="button" class="btn u-btn-3d u-btn-blue btn-block" id="credit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> <?php echo e(_i('Please wait...')); ?>">
                                                                        <?php echo e(_i('Credit')); ?>

                                                                    </button>
                                                                </div>
                                                            <?php endif; ?>
                                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [\Dotworkers\Security\Enums\Permissions::$agents_debit_transactions])): ?>
                                                                <div class="col-6">
                                                                    <button type="button" class="btn u-btn-3d u-btn-primary btn-block" id="debit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> <?php echo e(_i('Please wait...')); ?>">
                                                                        <?php echo e(_i('Debit')); ?>

                                                                    </button>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                        <br>
                                                        <div class="row">
                                                            <div class="col-6" id="ticket">
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="tab-pane fade mobile g-py-20 g-px-5" id="agents-transactions" role="tabpanel" aria-labelledby="agents-transactions-tab">
                            <div class="media">
                                <div class="media-body d-flex justify-content-end g-mb-10" id="table-buttons-agents-transactions">
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered w-100" id="agents-transactions-table" data-route="<?php echo e(route('agents.transactions')); ?>">
                                    <thead>
                                    <tr>
                                        <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                            <?php echo e(_i('Date')); ?>

                                        </th>
                                        <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                            <?php echo e(_i('From')); ?>

                                        </th>
                                        <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                            <?php echo e(_i('Toward')); ?>

                                        </th>
                                        <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                            <?php echo e(_i('Debit')); ?>

                                        </th>
                                        <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                            <?php echo e(_i('Credit')); ?>

                                        </th>
                                        <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                            <?php echo e(_i('Balance')); ?>

                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade mobile g-py-20 g-px-5" id="users-transactions" role="tabpanel" aria-labelledby="users-transactions-tab">
                            <div class="table-responsive">
                                <table class="table table-bordered w-100" id="users-transactions-table" data-route="<?php echo e(route('wallets.transactions')); ?>">
                                    <thead>
                                    <tr>
                                        <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                            <?php echo e(_i('Date')); ?>

                                        </th>
                                        <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                            <?php echo e(_i('Platform')); ?>

                                        </th>
                                        <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                            <?php echo e(_i('Description')); ?>

                                        </th>
                                        <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                            <?php echo e(_i('Debit')); ?>

                                        </th>
                                        <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                            <?php echo e(_i('Credit')); ?>

                                        </th>
                                        <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                            <?php echo e(_i('Balance')); ?>

                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade mobile g-py-20 g-px-5" id="users" role="tabpanel" aria-labelledby="users-tab">
                            <div class="media">
                                <div class="media-body d-flex justify-content-end g-mb-10" id="table-buttons-users">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered w-100" id="users-table" data-route="<?php echo e(route('agents.users')); ?>">
                                            <thead>
                                            <tr>
                                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                                    <?php echo e(_i('Username')); ?>

                                                </th>
                                                <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                                    <?php echo e(_i('Balance')); ?>

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
                        <?php if($agent->master): ?>
                            <div class="tab-pane fade mobile g-py-20 g-px-5" id="agents" role="tabpanel" aria-labelledby="agents-tab">
                                <div class="media">
                                    <div class="media-body d-flex justify-content-end g-mb-10" id="table-buttons-agents">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered w-100" id="agents-table" data-route="<?php echo e(route('agents.agents')); ?>">
                                                <thead>
                                                <tr>
                                                    <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                                        <?php echo e(_i('Username')); ?>

                                                    </th>
                                                    <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                                        <?php echo e(_i('Type')); ?>

                                                    </th>
                                                    <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                                        <?php echo e(_i('Percentage')); ?>

                                                    </th>
                                                    <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                                        <?php echo e(_i('Balance')); ?>

                                                    </th>
                                                    <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                                        <?php echo e(_i('Options')); ?>

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
                        <?php endif; ?>
                        <div class="tab-pane fade mobile g-py-20 g-px-5" id="financial-state" role="tabpanel" aria-labelledby="financial-state-tab">
                            <div class="noty_bar noty_type__warning noty_theme__unify--v1 g-mb-25">
                                <div class="noty_body">
                                    <div class="g-mr-20">
                                        <div class="noty_body__icon">
                                            <i class="hs-admin-alert"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <?php echo e(_i('This report makes closings and calculations every hour')); ?>

                                    </div>
                                </div>
                            </div>
                            <input type="hidden" id="username_like" name="username_like">
                            <?php echo $__env->make('back.layout.litepicker', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>










































                            <div class="table-responsive" id="financial-state-table" data-route="<?php echo e(route('agents.reports.financial-state-data')); ?>"></div>









                        </div>
                        <?php if($agent->master): ?>
                            <div class="tab-pane fade mobile g-py-20 g-px-5" id="locks" role="tabpanel" aria-labelledby="locks-tab">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                                            <header class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                                                <div class="media">
                                                    <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                                        <?php echo e(_i('Providers locking')); ?>

                                                    </h3>
                                                </div>
                                            </header>
                                            <div class="card-block g-pa-15">
                                                <div class="noty_bar noty_type__warning noty_theme__unify--v1--dark g-mb-25">
                                                    <div class="noty_body">
                                                        <div class="g-mr-20">
                                                            <div class="noty_body__icon">
                                                                <i class="hs-admin-info"></i>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <p>
                                                                <?php echo e(_i('The provider lock locks the agent and its entire tree')); ?>

                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <form action="<?php echo e(route('agents.block-agent-data')); ?>" id="lock-agent-form" method="post">
                                                    <div class="row">
                                                        <input type="hidden" name="user" class="user">
                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <label for="agent"><?php echo e(_i('Provider')); ?></label>
                                                                <select name="provider" id="provider" class="form-control">
                                                                    <option value=""><?php echo e(_i('Select...')); ?></option>
                                                                    <?php $__currentLoopData = $providers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $provider): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <option value="<?php echo e($provider->id); ?>">
                                                                            <?php echo e($provider->name); ?>

                                                                        </option>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <button type="button" class="btn u-btn-3d u-btn-primary btn-block" id="lock-agent" data-loading-text="<i class='fa fa-spin fa-spinner'></i> <?php echo e(_i('Please wait...')); ?>">
                                                                <?php echo e(_i('Lock')); ?>

                                                            </button>
                                                        </div>
                                                        <div class="col-6">
                                                            <button type="button" class="btn u-btn-3d u-btn-blue btn-block" id="unlock-agent" data-loading-text="<i class='fa fa-spin fa-spinner'></i> <?php echo e(_i('Please wait...')); ?>">
                                                                <?php echo e(_i('Unlock')); ?>

                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                                            <header class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                                                <div class="media">
                                                    <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                                        <?php echo e(_i('Users locking')); ?>

                                                    </h3>
                                                </div>
                                            </header>
                                            <div class="card-block g-pa-15">
                                                <div class="noty_bar noty_type__warning noty_theme__unify--v1--dark g-mb-25">
                                                    <div class="noty_body">
                                                        <div class="g-mr-20">
                                                            <div class="noty_body__icon">
                                                                <i class="hs-admin-info"></i>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <p>
                                                                <?php echo e(_i('The user locking locks the agent and his entire tree')); ?>

                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <form action="<?php echo e(route('agents.block-agent-data')); ?>" id="lock-user-form" method="post">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="description"><?php echo e(_i('Description of the lock')); ?></label>
                                                                <textarea name="description" id="description" cols="30" rows="5" class="form-control"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <input type="hidden" name="user" class="user">
                                                        <div class="col-6">
                                                            <button type="button" class="btn u-btn-3d u-btn-primary btn-block" id="lock-users" data-loading-text="<i class='fa fa-spin fa-spinner'></i> <?php echo e(_i('Please wait...')); ?>">
                                                                <?php echo e(_i('Lock')); ?>

                                                            </button>
                                                        </div>
                                                        <div class="col-6">
                                                            <button type="button" class="btn u-btn-3d u-btn-blue btn-block" id="unlock-users" data-loading-text="<i class='fa fa-spin fa-spinner'></i> <?php echo e(_i('Please wait...')); ?>">
                                                                <?php echo e(_i('Unlock')); ?>

                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="tab-pane fade mobile g-py-20 g-px-5" id="connect" role="tabpanel" aria-labelledby="connect-tab">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row g-mb-15">
                                        <div class="col-4 col-sm-4 col-md-3 g-mb-5 g-mb-0--md g-mb-10 align-self-center">
                                            <label class="g-mb-0">
                                                <strong><?php echo e(_i('Username')); ?></strong>
                                            </label>
                                        </div>
                                        <div class="col-4 col-sm-7 col-md-7 align-self-center">
                                            <div class="form-group g-pos-rel g-mb-0">
                                                <span id="username"></span>
                                            </div>
                                        </div>
                                        <div class="col-4 col-sm-2 col-md-2 align-self-center" id="modals-transaction">
                                            <div class="d-block d-sm-block d-md-none">
                                                <div class="row">
                                                    <div class="form-group mb-0">
                                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [\Dotworkers\Security\Enums\Permissions::$agents_credit_transactions])): ?>
                                                            <label href="#transaction-modal" class="btn u-btn-3d u-btn-blue" data-toggle="modal" data-transaction-type="<?php echo e(\Dotworkers\Configurations\Enums\TransactionTypes::$credit); ?>" data-transaction-name="<?php echo e(_i('credit')); ?>">
                                                                <i class="hs-admin-plus"></i>
                                                            </label>
                                                        <?php endif; ?>
                                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [\Dotworkers\Security\Enums\Permissions::$agents_debit_transactions])): ?>
                                                            <label href="#transaction-modal" class="btn u-btn-3d u-btn-primary" data-toggle="modal" data-transaction-type="<?php echo e(\Dotworkers\Configurations\Enums\TransactionTypes::$debit); ?>" data-transaction-name="<?php echo e(_i('debit')); ?>">
                                                                <i class="hs-admin-layout-line-solid"></i>
                                                            </label>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [\Dotworkers\Security\Enums\Permissions::$agents_credit_transactions,  \Dotworkers\Security\Enums\Permissions::$agents_debit_transactions])): ?>
                                    <div class="col-md-6">
                                        <div class="d-none d-sm-none d-md-block">
                                            <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30 d-none" id="transactions-form-container">
                                                <header class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                                                    <div class="media">
                                                        <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                                            <?php echo e(_i('Balance adjustments')); ?>

                                                        </h3>
                                                    </div>
                                                </header>
                                                <div class="card-block g-pa-15">
                                                    <form action="<?php echo e(route('agents.perform-transactions')); ?>" id="transactions-form" method="post">
                                                        <input type="hidden" name="wallet" id="wallet">
                                                        <input type="hidden" name="user" class="user">
                                                        <input type="hidden" name="type" id="type">
                                                        <div class="form-group">
                                                            <label for="amount"><?php echo e(_i('Amount')); ?></label>
                                                            <input type="number" name="amount" id="amount" class="form-control" min="0">
                                                        </div>
                                                        <div class="row">
                                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [\Dotworkers\Security\Enums\Permissions::$agents_credit_transactions])): ?>
                                                                <div class="col-6">
                                                                    <button type="button" class="btn u-btn-3d u-btn-blue btn-block" id="credit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> <?php echo e(_i('Please wait...')); ?>">
                                                                        <?php echo e(_i('Credit')); ?>

                                                                    </button>
                                                                </div>
                                                            <?php endif; ?>
                                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [\Dotworkers\Security\Enums\Permissions::$agents_debit_transactions])): ?>
                                                                <div class="col-6">
                                                                    <button type="button" class="btn u-btn-3d u-btn-primary btn-block" id="debit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> <?php echo e(_i('Please wait...')); ?>">
                                                                        <?php echo e(_i('Debit')); ?>

                                                                    </button>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                        <br>
                                                        <div class="row">
                                                            <div class="col-6" id="ticket">
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <?php if($agent->master): ?>
        <?php echo $__env->make('back.agents.modals.add-agents', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php echo $__env->make('back.agents.modals.update-percentage', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>
    <?php echo $__env->make('back.agents.modals.manual-transaction', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('back.agents.modals.move-agents', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('back.agents.modals.move-agents-users', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('back.agents.modals.add-users', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('back.users.modals.reset-password', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        $(function () {
            let agents = new Agents();
            let users = new Users();
            users.usersIps();
            agents.dashboard();
            agents.searchAgentDashboard();
            agents.performTransactions();
            agents.manualTransactionsModal();
            agents.agentsTransactions();
            agents.usersTransactions();
            agents.users();
            agents.agents();
            agents.storeAgents();
            agents.storeUsers();
            agents.changeUserStatus();
            users.resetPassword();
            agents.financialState();
            agents.lockProvider();
            agents.moveAgentUser();
            agents.moveAgent();
            agents.optionsFormUser();
            agents.optionsFormAgent();
            agents.menuMobile();
            agents.selectAgentOrUser('<?php echo e(_i('Agents search...')); ?>');
            agents.selectUsernameSearch('<?php echo e(_i('Agents search...')); ?>');
            agents.statusFilter();
            <?php if($agent->master): ?>
            agents.changeAgentType();
            <?php endif; ?>
            agents.relocationAgents();
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('back.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
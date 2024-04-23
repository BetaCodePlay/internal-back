

<?php $__env->startSection('styles'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="wrapper-title g-pb-30">
        <?php echo e(_i('Role and permission management')); ?>

    </div>

    <div class="page-role" data-id="<?php echo e($authUser->id); ?>">
        <div class="page-top">
            <form class="search-input" autocomplete="destroy">
                <i class="fa-solid fa-magnifying-glass"></i>
                <select class="form-control roleUsernameSearch" placeholder="<?php echo e(_i('Search')); ?>" data-route="<?php echo e(route('agents.search-username')); ?>" data-redirect="<?php echo e(route('agents.find-user')); ?>">
                    <option></option>
                </select>
            </form>
            <button type="button" class="btn btn-theme" data-toggle="modal" data-target="#role-create" data-value="true"><i class="fa-solid fa-plus"></i> <?php echo e(_i('Create role')); ?></button>
        </div>
        <div class="nav-roles">
            <ul class="nav nav-tabs" id="roleTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active tab-role" data-toggle="tab" data-target="#roleTabProfileManager" type="button" role="tab" aria-controls="roleTabProfileManager" aria-selected="true">
                        <?php echo e(_i('Profile management')); ?>

                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link tab-role" data-toggle="tab" data-target="#roleTabTransactions" type="button" role="tab" aria-controls="roleTabTransactions" aria-selected="false">
                        <?php echo e(_i('Transactions')); ?>

                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link tab-role" data-toggle="tab" data-target="#roleTabMoreInformation" type="button" role="tab" aria-controls="roleTabMoreInformation" aria-selected="false">
                        <?php echo e(_i('More information')); ?>

                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link tab-role" data-toggle="tab" data-target="#roleTabLocks" type="button" role="tab" aria-controls="roleTabLocks" aria-selected="false">
                        <?php echo e(_i('Locks')); ?>

                    </button>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="roleTabProfileManager" role="tabpanel" aria-labelledby="profile-tab">
                    <div class="tab-manager">
                        <div class="tab-manager-top">
                            <div class="tab-manager-data">
                                <div class="data-title"><?php echo e(_i('Name')); ?></div>
                                <div class="data-text"><?php echo e($authUser->username); ?> <span class="separator"></span><span class="deco-role"><?php echo e($authUser->type_user); ?></span></div>
                            </div>
                            <div class="tab-manager-data">
                                <div class="data-title"><?php echo e(_i('ID User')); ?></div>
                                <div class="data-text text-id"><?php echo e($authUser->id); ?> <span class="separator"></span>
                                    <button class="btn btn-theme btn-xs clipboard" data-title="<?php echo e(_i('Copied')); ?>" data-clipboard-text="<?php echo e($authUser->id); ?>"><?php echo e(_i('Copy')); ?></button>
                                </div>
                            </div>
                            <div class="tab-manager-data">
                                <div class="data-title"><?php echo e(_i('Status')); ?></div>
                                <div class="data-text text-status <?php echo e(!$authUser->status ? 'force-text-finish' : ''); ?>">
                                    <?php if(auth()->user()->id !== $authUser->id): ?>
                                        <i class="fa-solid i-status fa-circle <?php echo e($authUser->status ? 'green' : 'red'); ?>"></i> <?php echo e($authUser->statusText); ?>

                                        <span class="separator"></span>
                                        <button class="btn btn-theme btn-xs currentDataRole" data-toggle="modal" data-target="#role-lock"
                                                data-lock="<?php echo e(_i('Lock profile')); ?>"
                                                data-unlock="<?php echo e(_i('Unlock profile')); ?>"
                                                data-value="<?php echo e($authUser->status ? 'true' : 'false'); ?>"
                                                data-type="<?php echo e($authUser->action); ?>"
                                                data-userid="<?php echo e($authUser->id); ?>"
                                                data-username="<?php echo e($authUser->username); ?>"
                                                data-rol="<?php echo e($authUser->agentType); ?>"><?php echo e($authUser->status ? _i('Lock') : _i('Unlock')); ?>

                                        </button>
                                    <?php else: ?>
                                        <span class="separator"> &nbsp;</span>
                                        <i class="fa-solid i-status fa-circle <?php echo e($authUser->status ? 'green' : 'red'); ?>"></i> <?php echo e($authUser->statusText); ?>

                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="tab-manager-bottom">
                            <div class="tab-manager-data">
                                <div class="data-title"><?php echo e(_i('Balance')); ?></div>
                                <div class="data-text text-id"><?php echo e($authUser->balanceUser); ?> <?php echo e(session('currency') == 'VEF' ? $free_currency->currency_name : session('currency')); ?>

                                    <?php if($authUser->status): ?>
                                        <span class="separator"></span>
                                        <?php if(auth()->user()->id !== $authUser->id): ?>
                                            <button class="btn btn-theme btn-xs currentDataRole" data-toggle="modal" data-target="#role-balance"
                                                    data-userid="<?php echo e($authUser->id); ?>"
                                                    data-username="<?php echo e($authUser->username); ?>"
                                                    data-rol="<?php echo e($authUser->agentType); ?>"><?php echo e(_i('Adjustment')); ?>

                                            </button>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <?php if($authUser->status): ?>
                                <div class="tab-manager-data text-center">
                                    <div class="data-title"><?php echo e(_i('Password')); ?></div>
                                    <div class="data-text">
                                        <span class="separator">
                                            <?php if(auth()->user()->id !== $authUser->id): ?>
                                                &nbsp;
                                            <?php endif; ?>
                                        </span>
                                        <button class="btn btn-theme btn-xs currentDataRole" data-toggle="modal" data-target="#role-password-reset"
                                                data-userid="<?php echo e($authUser->id); ?>"
                                                data-username="<?php echo e($authUser->username); ?>"
                                                data-rol="<?php echo e($authUser->agentType); ?>"><?php echo e(_i('Reset')); ?>

                                        </button>
                                    </div>
                                </div>
                                <?php if(auth()->user()->id !== $authUser->id): ?>
                                    <div class="tab-manager-data text-center">
                                        <div class="data-title"><?php echo e(_i('Account')); ?></div>
                                        <div class="data-text">
                                        <span class="separator">
                                              &nbsp;
                                        </span>
                                            <button class="btn btn-theme btn-xs currentDataRole" data-toggle="modal" data-target="#role-modify"
                                                    data-userid="<?php echo e($authUser->id); ?>"
                                                    data-username="<?php echo e($authUser->username); ?>"
                                                    data-rol="<?php echo e($authUser->agentType); ?>"
                                                    data-route="<?php echo e(route('agents.role.user-find')); ?>"><?php echo e(_i('Modify')); ?>

                                            </button>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>
                <div class="tab-pane fade" id="roleTabTransactions" role="tabpanel" aria-labelledby="transactions-tab">
                    <form autocomplete="destroy" class="tab-form">
                        <div class="row">
                            <div class="col-12 col-form <?php echo e($authUser->agentType === 5 ? 'col-lg-4' : 'col-lg-3'); ?>">
                                <label><?php echo e(_i('Action')); ?></label>
                                <select class="form-control" id="roleTabTransactionsAction">
                                    <option value="all"><?php echo e(_i('All')); ?></option>
                                    <option value="credit"><?php echo e(_i('Credit')); ?></option>
                                    <option value="debit"><?php echo e(_i('Debit')); ?></option>
                                </select>
                            </div>
                            <div class="col-12 col-lg-3 col-form <?php echo e($authUser->agentType === 5 ? 'd-none' : ''); ?>">
                                <label><?php echo e(_i('User type')); ?></label>
                                <select class="form-control" id="roleTabTransactionsType">
                                    <option value="all"><?php echo e(_i('All')); ?></option>
                                    <option value="agent"><?php echo e(_i('Agents')); ?></option>
                                    <option value="user"><?php echo e(_i('Players')); ?></option>
                                </select>
                            </div>
                            <div class="col-12 col-form <?php echo e($authUser->agentType === 5 ? 'col-lg-4' : 'col-lg-3'); ?>">
                                <div class="form-group">
                                    <label><?php echo e(_i('Date')); ?></label>
                                    <input type="text" class="form-control" id="date_range_new" placeholder="">
                                </div>
                            </div>
                            <div class="col-12 col-form <?php echo e($authUser->agentType === 5 ? 'col-lg-4' : 'col-lg-3'); ?>">
                                <div class="form-group">
                                    <label class="d-none d-lg-block">&nbsp;</label>
                                    <button type="button" class="btn btn-theme btn-block currentDataRole searchTransactionsRole" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Searching..."
                                            data-userid="<?php echo e($authUser->id); ?>"
                                            data-username="<?php echo e($authUser->username); ?>"
                                            data-rol="<?php echo e($authUser->agentType); ?>">
                                        <?php echo e(_i('Search')); ?>

                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="tab-body">
                        <form autocomplete="destroy" class="col table-load">
                            <table id="table-transactions" class="display nowrap" data-route="<?php echo e($authUser->agentType === 5 ? route('transactions.players') : route('transactions.agents')); ?>">
                                <thead>
                                <tr>
                                    <th><?php echo e(_i('Date')); ?></th>
                                    <th data-priority="3"><?php echo e(_i('Origin')); ?></th>
                                    <th data-priority="1"><?php echo e(_i('Destination')); ?></th>
                                    <th data-priority="2"><?php echo e(_i('Amount')); ?></th>
                                    <th><?php echo e(_i('Balance')); ?></th>
                                </tr>
                                </thead>
                            </table>
                        </form>
                        <div class="loading-style"></div>
                    </div>
                </div>
                <div class="tab-pane fade" id="roleTabMoreInformation" role="tabpanel" aria-labelledby="information-tab">
                    <div class="tab-manager">
                        <div class="tab-manager-top">
                            <div class="tab-manager-data">
                                <div class="data-title"><?php echo e(_i('Created the')); ?></div>
                                <div class="data-text"><?php echo e(($authUser->created_at)->format('d-m-Y ')); ?></div>
                            </div>
                            <?php if(auth()->user()->id !== $authUser->id): ?>
                                <div class="tab-manager-data">
                                    <div class="data-title"><?php echo e(_i('Father')); ?></div>
                                    <div class="data-text"><?php echo e($authUser->owner); ?></div>
                                </div>
                            <?php endif; ?>
                            <div class="tab-manager-data">
                                <div class="data-title"><?php echo e(_i('Percentage')); ?></div>
                                <div class="data-text text-finish"><?php echo e($authUser->percentage); ?>%</div>
                            </div>
                        </div>

                        <div class="tab-manager-data">
                            <div class="data-title"><?php echo e(_i('Number of dependent agents')); ?></div>
                            <div class="data-text-inline"><span class="name"><?php echo e(_i('Master')); ?></span> <span class="number"><?php echo e($agent?->masterQuantity ?? '0.00'); ?></span></div>
                            <div class="data-text-inline"><span class="name"><?php echo e(_i('Support')); ?></span> <span class="number"><?php echo e($agent?->cashierQuantity ?? '0.00'); ?></span></div>
                            <div class="data-text-inline"><span class="name"><?php echo e(_i('Players')); ?></span> <span class="number"><?php echo e($agent?->playerQuantity ?? '0.00'); ?></span></div>
                        </div>
                    </div>

                    <div class="tab-body">
                        <form autocomplete="destroy" class="col table-load">
                            <table id="table-information" class="display nowrap" data-route="<?php echo e(route('users.user-ip-data')); ?>?userId=<?php echo e($authUser->id); ?>">
                                <thead>
                                <tr>
                                    <th data-priority="1"><?php echo e(_i('IP')); ?></th>
                                    <th data-priority="2"><?php echo e(_i('Quantity')); ?></th>
                                </tr>
                                </thead>
                            </table>
                        </form>
                        <div class="loading-style"></div>
                    </div>
                </div>
                <div class="tab-pane fade" id="roleTabLocks" role="tabpanel" aria-labelledby="locks-tab">
                    <br>
                    <div class="text-center"><b><?php echo e(_i('Coming soon')); ?>...</b></div>
                    <br>
                </div>
            </div>
        </div>
        <?php if($authUser->agentType !== 5): ?>
            <div class="page-body">
                <form autocomplete="destroy" class="col table-load">
                    <table id="table-roles" class="display nowrap" data-route="<?php echo e(route('agents.get.direct.children')); ?>?draw=2&start=0&username=<?php echo e($username); ?>">
                        <thead>
                        <tr>
                            <th data-priority="1"><?php echo e(_i('Name')); ?></th>
                            <th><?php echo e(_i('Rol')); ?></th>
                            <th><?php echo e(_i('ID User')); ?></th>
                            <th><?php echo e(_i('Status')); ?></th>
                            <th data-priority="3"><?php echo e(_i('Balance')); ?></th>
                            <th data-priority="2"></th>
                        </tr>
                        </thead>
                    </table>
                </form>
                <div class="loading-style"></div>
            </div>
        <?php endif; ?>

        <div class="d-none" id="user-buttons">
            <div class="d-inline-block dropdown">
                <button class="btn dropdown-toggle" type="button" id="dropdownRoleProfile" data-toggle="dropdown" aria-expanded="false">
                    <i class="fa-solid fa-ellipsis-vertical"></i>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownRoleProfile">
                    <li><a class="dropdown-item currentDataRole" href="javascript:void(0)" data-toggle="modal" data-target="#role-create"><?php echo e(_i('Add role')); ?></a></li>
                    <li><a class="dropdown-item currentDataRole" href="javascript:void(0)" data-toggle="modal" data-target="#role-password-reset"><?php echo e(_i('Reset password')); ?></a></li>
                    <li><a class="dropdown-item currentDataRole" href="javascript:void(0)" data-toggle="modal" data-target="#role-lock"
                           data-lock="<?php echo e(_i('Lock profile')); ?>"
                           data-unlock="<?php echo e(_i('Unlock profile')); ?>"
                           data-rol=""
                           data-value=""
                           data-type="">
                        </a>
                    </li>
                    <li><a class="dropdown-item currentDataRole" href="javascript:void(0)" data-toggle="modal" data-target="#role-balance"><?php echo e(_i('Balance adjustment')); ?></a></li>
                    <li><a class="dropdown-item currentDataRole" href="javascript:void(0)" data-toggle="modal" data-target="#role-modify" data-route="<?php echo e(route('agents.role.user-find')); ?>"><?php echo e(_i('Modify')); ?></a></li>
                </ul>
            </div>

            <a href="" class="btn btn-href" target="_blank"><i class="fa-solid fa-chevron-right"></i></a>
        </div>
    </div>
    <div class="d-none" id="globalActionID" data-userid="" data-username=""></div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('modals'); ?>
    <?php echo $__env->make('back.agents.modals.role-password-reset', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('back.agents.modals.role-balance', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('back.agents.modals.role-create', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('back.agents.modals.role-modify', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('back.agents.modals.role-lock', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        $(function () {
            let roles = new Roles();
            roles.initTableRoles();
            roles.userSearch("<?php echo e(_i('Search user')); ?>...", "<?php echo e(_i('Write more than 3 characters')); ?>...", 3);
            roles.userResetPassword();
            roles.userBalance();
            roles.userCreate();
            roles.userModify();
            roles.userLock();
            roles.tabsTablesSection();
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('back.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
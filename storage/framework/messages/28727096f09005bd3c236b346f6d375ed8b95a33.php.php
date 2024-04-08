<?php if(isset($permissions) && is_string($permissions)): ?>
<li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
    <a class="media u-side-nav--top-level-menu-link u-side-nav--hide-on-hidden" href="javascript:void(0)"
       data-toggle="collapse" data-target="#agentsSidebar" aria-expanded="true">
        <span class="g-pos-rel"><i class="fa-regular hs-admin-briefcase"></i></span> <span
            class="media-body align-self-center"><?php echo e(_i('See agents')); ?></span>
        <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
    </a>
    <ul id="agentsSidebar"
        class="u-sidebar-navigation-v1-menu u-side-nav--second-level-menu u-side-nav--second-level-menu-top mb-0 collapse">
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [$permissions::$agents_dashboard])): ?>
        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item active">
            <a class="media u-side-nav--second-level-menu-link" href="<?php echo e(route('agents.index')); ?>"
               target="_self">
                <span class="media-body align-self-center"><?php echo e(_i('Dashboard')); ?></span>
                <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
            </a>
        </li>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [$permissions::$add_agent_users])): ?>
        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
            <a class="media u-side-nav--second-level-menu-link" href="<?php echo e(route('agents.add-users')); ?>"
               target="_self">
                <span class="media-body align-self-center"><?php echo e(_i('Add users')); ?></span>
                <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
            </a>
        </li>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [$permissions::$agents_reports_menu])): ?>
        <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
            <a class="media u-side-nav--second-level-menu-link" href="javascript:void(0)" target="_self"
               data-toggle="collapse" data-target="#agentFinancialSidebar" aria-expanded="false">
                <span class="media-body align-self-center"><?php echo e(_i('Reports')); ?></span>
                <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
            </a>
            <ul id="agentFinancialSidebar"
                class="u-sidebar-navigation-v1-menu u-side-nav--second-level-menu mb-0 collapse <?php echo request()->is('agents/reports*') ? 'show' : ''; ?>">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [$permissions::$agents_financial_report])): ?>
                <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                    <a class="media u-side-nav--second-level-menu-link"
                       href="<?php echo e(route('agents.reports.financial-state')); ?>" target="_self">
                                            <span
                                                class="media-body align-self-center"><?php echo e(_i('Financial state')); ?></span>
                        <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                    </a>
                </li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [$permissions::$agents_financial_report])): ?>
                <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                    <a class="media u-side-nav--second-level-menu-link"
                       href="<?php echo e(route('agents.reports.financial-state-summary')); ?>" target="_self">
                                            <span
                                                class="media-body align-self-center"><?php echo e(_i('Financial state - Summary')); ?></span>
                        <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                    </a>
                </li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [$permissions::$report_financial_by_username])): ?>
                <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                    <a class="media u-side-nav--second-level-menu-link"
                       href="<?php echo e(route('agents.reports.financial.state.username')); ?>" target="_self">
                        <span class="media-body align-self-center"><?php echo e(_i('By users')); ?></span>
                        <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                    </a>
                </li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [$permissions::$report_financial_by_provider])): ?>
                <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                    <a class="media u-side-nav--second-level-menu-link"
                       href="<?php echo e(route('agents.reports.financial.state.provider')); ?>" target="_self">
                        <span class="media-body align-self-center"><?php echo e(_i('By providers')); ?></span>
                        <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                    </a>
                </li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [$permissions::$agents_menu])): ?>
                <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                    <a class="media u-side-nav--second-level-menu-link"
                       href="<?php echo e(route('reports.view.transaction.timeline')); ?>" target="_self">
                                            <span
                                                class="media-body align-self-center"><?php echo e(_i('Transaction Timeline')); ?></span>
                        <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                    </a>
                </li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [$permissions::$agents_transactions])): ?>
                <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                    <a class="media u-side-nav--second-level-menu-link"
                       href="<?php echo e(route('agents.reports.agents-transactions')); ?>" target="_self">
                                            <span
                                                class="media-body align-self-center"><?php echo e(_i('Agents transactions')); ?></span>
                        <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                    </a>
                </li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [$permissions::$agents_cash_flow])): ?>
                <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                    <a class="media u-side-nav--second-level-menu-link"
                       href="<?php echo e(route('agents.reports.cash-flow')); ?>" target="_self">
                        <span class="media-body align-self-center"><?php echo e(_i('Cash flow')); ?></span>
                        <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                    </a>
                </li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [$permissions::$agents_balances])): ?>
                <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                    <a class="media u-side-nav--second-level-menu-link"
                       href="<?php echo e(route('agents.reports.agents-balances')); ?>" target="_self">
                                            <span
                                                class="media-body align-self-center"><?php echo e(_i('Agents balances')); ?></span>
                        <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                    </a>
                </li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [$permissions::$agents_users_balances])): ?>
                <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                    <a class="media u-side-nav--second-level-menu-link"
                       href="<?php echo e(route('agents.reports.users-balances')); ?>" target="_self">
                        <span class="media-body align-self-center"><?php echo e(_i('Users balances')); ?></span>
                        <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                    </a>
                </li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [$permissions::$locked_providers])): ?>
                <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                    <a class="media u-side-nav--second-level-menu-link"
                       href="<?php echo e(route('agents.reports.locked-providers')); ?>" target="_self">
                                            <span
                                                class="media-body align-self-center"><?php echo e(_i('Locked providers')); ?></span>
                        <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                    </a>
                </li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [$permissions::$locked_providers])): ?>
                <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                    <a class="media u-side-nav--second-level-menu-link"
                       href="<?php echo e(route('agents.reports.exclude-providers-agents')); ?>" target="_self">
                                            <span
                                                class="media-body align-self-center"><?php echo e(_i('Exclude agents from providers')); ?></span>
                        <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </li>
        <?php endif; ?>
    </ul>
</li>
<?php endif; ?>



<?php $__env->startSection('styles'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="wrapper-title g-pb-30">
        <?php echo e(_i('Dashboard')); ?>

    </div>

    <div class="page-dashboard">
        <div class="row">
            <div class="col-12 col-xl-4 mb-4">
                <div class="dashboard-content dashboard-content-mobile">
                    <div class="dashboard-content-title"><?php echo e(_i('Total balance')); ?></div>
                    <div class="dash-balance">
                        <div class="dash-balance-total">
                            <!-- <span class="minus">$</span> 80,000.<span class="minus">00</span> -->
                            <span class="minus"></span><?php echo e($dashboard['amounts']['totalBalance']); ?></span>
                        </div>
                        <div class="dash-balance-amount">
                            <div class="dash-balance-amount-ex">
                                <div class="dash-balance-amount-item">
                                    <div class="dash-balance-amount-name">
                                        <div class="dash-balance-amount-icon">
                                            <i class="fa-solid fa-arrow-trend-up"></i>
                                        </div>
                                        <div class="dash-balance-amount-name-text">
                                            <div class="span"><?php echo e(_i('Amount deposited')); ?></div>
                                            <div class="span"><?php echo e(_i('on day')); ?></div>
                                        </div>
                                    </div>

                                    <div class="dash-balance-amount-balance">
                                        <!--  <span class="minus">$</span>80,000.<span class="minus">00</span> -->
                                        <?php echo e($dashboard['amounts']['totalDeposited']); ?>

                                    </div>
                                </div>

                                <div class="dash-balance-amount-item">
                                    <div class="dash-balance-amount-name">
                                        <div class="dash-balance-amount-icon">
                                            <i class="fa-solid fa-arrow-trend-up"></i>
                                        </div>
                                        <div class="dash-balance-amount-name-text">
                                            <div class="span"><?php echo e(_i('Total amount')); ?></div>
                                            <div class="span"><?php echo e(_i('prizewinning')); ?></div>
                                        </div>
                                    </div>

                                    <div class="dash-balance-amount-balance">
                                        <?php echo e($dashboard['amounts']['totalPrizeWinningAmount']); ?>

                                    </div>
                                </div>

                                <div class="dash-balance-amount-item">
                                    <div class="dash-balance-amount-name">
                                        <div class="dash-balance-amount-icon">
                                            <i class="fa-solid fa-arrow-trend-up"></i>
                                        </div>
                                        <div class="dash-balance-amount-name-text">
                                            <div class="span"><?php echo e(_i('Total amount')); ?></div>
                                            <div class="span"><?php echo e(_i('played')); ?></div>
                                        </div>
                                    </div>

                                    <div class="dash-balance-amount-balance">
                                        <?php echo e($dashboard['amounts']['totalPlayedAmount']); ?>

                                    </div>
                                </div>

                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6 col-xl-4 mb-4">
                <div class="dashboard-content">
                    <div class="dashboard-content-title"><?php echo e(_i('Transactions')); ?></div>
                    <div class="dash-transactions">
                        <div class="dash-transactions-ex">
                            <?php $__currentLoopData = $dashboard['transactions']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transactions): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="dash-transactions-item">
                                    <div class="dash-transactions-item-text">
                                        <div class="dash-transactions-item-text-top">
                                            <span class="icon">
                                                <?php if( $transactions->transactionType == 1): ?>
                                                    <i class="fa-solid fa-arrow-down-long"></i>
                                                <?php else: ?>
                                                    <i class="fa-solid fa-arrow-up-long"></i>
                                                <?php endif; ?>
                                            </span>

                                            <?php if( $transactions->transactionType == 1): ?>
                                                <?php echo e(_i('You receive')); ?>

                                            <?php else: ?>
                                                <?php echo e(_i('You sent')); ?>

                                            <?php endif; ?>
                                        </div>
                                        <div
                                            class="dash-transactions-item-text-middle"><?php echo e($transactions->transactionType == 1 ?_i('Payment with debit for') : _i('Transfer to')); ?> <?php echo e($transactions->username); ?></div>
                                        <div class="dash-transactions-item-text-bottom">
                                            <?php echo e($transactions->date); ?>

                                        </div>
                                    </div>

                                    <div
                                        class="dash-transactions-amount <?php echo e($transactions->transactionType == 1 ? '' : 'transactions-send'); ?>">
                                        <span
                                            class="minus"><?php echo e($transactions->transactionType == 1 ? '+' : '-'); ?>$</span> <?php echo e($transactions->amount); ?>

                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <!--
                            <div class="dash-transactions-item">
                                <div class="dash-transactions-item-text">
                                    <div class="dash-transactions-item-text-top">
                                        <span class="icon green"><i class="fa-solid fa-circle"></i></span>
                                        orlando_99
                                    </div>
                                    <div class="dash-transactions-item-text-bottom">
                                        01-01-2024 10:32
                                    </div>
                                </div>

                                <div class="dash-transactions-amount">
                                    <span class="minus">$</span>2,034.<span class="minus">00</span>
                                </div>
                            </div>

                            <div class="dash-transactions-item">
                                <div class="dash-transactions-item-text">
                                    <div class="dash-transactions-item-text-top">
                                        <span class="icon green"><i class="fa-solid fa-circle"></i></span>
                                        username123
                                    </div>
                                    <div class="dash-transactions-item-text-bottom">
                                        01-01-2024 14:11
                                    </div>
                                </div>

                                <div class="dash-transactions-amount">
                                    <span class="minus">$</span>542.<span class="minus">57</span>
                                </div>
                            </div>

                            <div class="dash-transactions-item">
                                <div class="dash-transactions-item-text">
                                    <div class="dash-transactions-item-text-top">
                                        <span class="icon"><i class="fa-solid fa-circle"></i></span>
                                        gustavoperez
                                    </div>
                                    <div class="dash-transactions-item-text-bottom">
                                        01-01-2024 01:01
                                    </div>
                                </div>

                                <div class="dash-transactions-amount">
                                    <span class="minus">$</span>1,000.<span class="minus">00</span>
                                </div>
                            </div>

                            <div class="dash-transactions-item">
                                <div class="dash-transactions-item-text">
                                    <div class="dash-transactions-item-text-top">
                                        <span class="icon green"><i class="fa-solid fa-circle"></i></span>
                                        username123
                                    </div>
                                    <div class="dash-transactions-item-text-bottom">
                                        01-01-2024 14:11
                                    </div>
                                </div>

                                <div class="dash-transactions-amount">
                                    <span class="minus">$</span>542.<span class="minus">57</span>
                                </div>
                            </div>

                            <div class="dash-transactions-item">
                                <div class="dash-transactions-item-text">
                                    <div class="dash-transactions-item-text-top">
                                        <span class="icon green"><i class="fa-solid fa-circle"></i></span>
                                        username123
                                    </div>
                                    <div class="dash-transactions-item-text-bottom">
                                        01-01-2024 14:11
                                    </div>
                                </div>

                                <div class="dash-transactions-amount">
                                    <span class="minus">$</span>542.<span class="minus">57</span>
                                </div>
                            </div>

                            <div class="dash-transactions-item">
                                <div class="dash-transactions-item-text">
                                    <div class="dash-transactions-item-text-top">
                                        <span class="icon green"><i class="fa-solid fa-circle"></i></span>
                                        username123
                                    </div>
                                    <div class="dash-transactions-item-text-bottom">
                                        01-01-2024 14:11
                                    </div>
                                </div>

                                <div class="dash-transactions-amount">
                                    <span class="minus">$</span>542.<span class="minus">57</span>
                                </div>
                            </div>

                            <div class="dash-transactions-item">
                                <div class="dash-transactions-item-text">
                                    <div class="dash-transactions-item-text-top">
                                        <span class="icon green"><i class="fa-solid fa-circle"></i></span>
                                        username123
                                    </div>
                                    <div class="dash-transactions-item-text-bottom">
                                        01-01-2024 14:11
                                    </div>
                                </div>

                                <div class="dash-transactions-amount">
                                    <span class="minus">$</span>542.<span class="minus">57</span>
                                </div>
                            </div>

                            <div class="dash-transactions-item">
                                <div class="dash-transactions-item-text">
                                    <div class="dash-transactions-item-text-top">
                                        <span class="icon green"><i class="fa-solid fa-circle"></i></span>
                                        username123
                                    </div>
                                    <div class="dash-transactions-item-text-bottom">
                                        01-01-2024 14:11
                                    </div>
                                </div>

                                <div class="dash-transactions-amount">
                                    <span class="minus">$</span>542.<span class="minus">57</span>
                                </div>
                            </div>

                            <div class="dash-transactions-item">
                                <div class="dash-transactions-item-text">
                                    <div class="dash-transactions-item-text-top">
                                        <span class="icon green"><i class="fa-solid fa-circle"></i></span>
                                        username123
                                    </div>
                                    <div class="dash-transactions-item-text-bottom">
                                        01-01-2024 14:11
                                    </div>
                                </div>

                                <div class="dash-transactions-amount">
                                    <span class="minus">$</span>542.<span class="minus">57</span>
                                </div>
                            </div>

                            <div class="dash-transactions-item">
                                <div class="dash-transactions-item-text">
                                    <div class="dash-transactions-item-text-top">
                                        <span class="icon green"><i class="fa-solid fa-circle"></i></span>
                                        username123
                                    </div>
                                    <div class="dash-transactions-item-text-bottom">
                                        01-01-2024 14:11
                                    </div>
                                </div>

                                <div class="dash-transactions-amount">
                                    <span class="minus">$</span>542.<span class="minus">57</span>
                                </div>
                            </div>

                            <div class="dash-transactions-item">
                                <div class="dash-transactions-item-text">
                                    <div class="dash-transactions-item-text-top">
                                        <span class="icon green"><i class="fa-solid fa-circle"></i></span>
                                        username123
                                    </div>
                                    <div class="dash-transactions-item-text-bottom">
                                        01-01-2024 14:11
                                    </div>
                                </div>

                                <div class="dash-transactions-amount">
                                    <span class="minus">$</span>542.<span class="minus">57</span>
                                </div>
                            </div>
                             -->
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6 col-xl-4 mb-4">
                <div class="dashboard-content">
                    <div class="dashboard-content-title">
                        <?php echo e(_i('Recent activity')); ?>

                        
                    </div>

                    <div class="dash-recent-activity">
                        <div class="dash-recent-activity-ex">
                            <?php $__currentLoopData = $dashboard['audits']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $audits): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="dash-recent-activity-item">
                                    <div class="dash-recent-activity-item-text">
                                        <div class="dash-recent-activity-item-text-top">
                                            <span class="icon green"><i class="fa-solid fa-circle"></i></span>
                                            <?php echo e($audits->name); ?>

                                        </div>
                                        <div class="dash-recent-activity-item-text-bottom">
                                            <?php echo e($audits->formatted_date); ?>

                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <!--
                            <div class="dash-recent-activity-item">
                                <div class="dash-recent-activity-item-text">
                                    <div class="dash-recent-activity-item-text-top">
                                        <span class="icon green"><i class="fa-solid fa-circle"></i></span>
                                        Update Server log
                                    </div>
                                    <div class="dash-recent-activity-item-text-bottom">
                                        01-01-2024 10:32
                                    </div>
                                </div>
                            </div>

                            <div class="dash-recent-activity-item">
                                <div class="dash-recent-activity-item-text">
                                    <div class="dash-recent-activity-item-text-top">
                                        <span class="icon green"><i class="fa-solid fa-circle"></i></span>
                                        Send Mail to HR and Admin
                                    </div>
                                    <div class="dash-recent-activity-item-text-bottom">
                                        01-01-2024 10:32
                                    </div>
                                </div>
                            </div>

                            <div class="dash-recent-activity-item">
                                <div class="dash-recent-activity-item-text">
                                    <div class="dash-recent-activity-item-text-top">
                                        <span class="icon green"><i class="fa-solid fa-circle"></i></span>
                                        Update Server log
                                    </div>
                                    <div class="dash-recent-activity-item-text-bottom">
                                        01-01-2024 10:32
                                    </div>
                                </div>
                            </div>

                            <div class="dash-recent-activity-item">
                                <div class="dash-recent-activity-item-text">
                                    <div class="dash-recent-activity-item-text-top">
                                        <span class="icon green"><i class="fa-solid fa-circle"></i></span>
                                        Send Mail to HR and Admin
                                    </div>
                                    <div class="dash-recent-activity-item-text-bottom">
                                        01-01-2024 10:32
                                    </div>
                                </div>
                            </div>

                            <div class="dash-recent-activity-item">
                                <div class="dash-recent-activity-item-text">
                                    <div class="dash-recent-activity-item-text-top">
                                        <span class="icon green"><i class="fa-solid fa-circle"></i></span>
                                        Update Server log
                                    </div>
                                    <div class="dash-recent-activity-item-text-bottom">
                                        01-01-2024 10:32
                                    </div>
                                </div>
                            </div>

                            <div class="dash-recent-activity-item">
                                <div class="dash-recent-activity-item-text">
                                    <div class="dash-recent-activity-item-text-top">
                                        <span class="icon green"><i class="fa-solid fa-circle"></i></span>
                                        Send Mail to HR and Admin
                                    </div>
                                    <div class="dash-recent-activity-item-text-bottom">
                                        01-01-2024 10:32
                                    </div>
                                </div>
                            </div>

                            <div class="dash-recent-activity-item">
                                <div class="dash-recent-activity-item-text">
                                    <div class="dash-recent-activity-item-text-top">
                                        <span class="icon green"><i class="fa-solid fa-circle"></i></span>
                                        Update Server log
                                    </div>
                                    <div class="dash-recent-activity-item-text-bottom">
                                        01-01-2024 10:32
                                    </div>
                                </div>
                            </div>

                            <div class="dash-recent-activity-item">
                                <div class="dash-recent-activity-item-text">
                                    <div class="dash-recent-activity-item-text-top">
                                        <span class="icon green"><i class="fa-solid fa-circle"></i></span>
                                        Send Mail to HR and Admin
                                    </div>
                                    <div class="dash-recent-activity-item-text-bottom">
                                        01-01-2024 10:32
                                    </div>
                                </div>
                            </div>

                            <div class="dash-recent-activity-item">
                                <div class="dash-recent-activity-item-text">
                                    <div class="dash-recent-activity-item-text-top">
                                        <span class="icon green"><i class="fa-solid fa-circle"></i></span>
                                        Update Server log
                                    </div>
                                    <div class="dash-recent-activity-item-text-bottom">
                                        01-01-2024 10:32
                                    </div>
                                </div>
                            </div>

                            <div class="dash-recent-activity-item">
                                <div class="dash-recent-activity-item-text">
                                    <div class="dash-recent-activity-item-text-top">
                                        <span class="icon green"><i class="fa-solid fa-circle"></i></span>
                                        Send Mail to HR and Admin
                                    </div>
                                    <div class="dash-recent-activity-item-text-bottom">
                                        01-01-2024 10:32
                                    </div>
                                </div>
                            </div>

                            <div class="dash-recent-activity-item">
                                <div class="dash-recent-activity-item-text">
                                    <div class="dash-recent-activity-item-text-top">
                                        <span class="icon green"><i class="fa-solid fa-circle"></i></span>
                                        Update Server log
                                    </div>
                                    <div class="dash-recent-activity-item-text-bottom">
                                        01-01-2024 10:32
                                    </div>
                                </div>
                            </div>

                            <div class="dash-recent-activity-item">
                                <div class="dash-recent-activity-item-text">
                                    <div class="dash-recent-activity-item-text-top">
                                        <span class="icon green"><i class="fa-solid fa-circle"></i></span>
                                        Send Mail to HR and Admin
                                    </div>
                                    <div class="dash-recent-activity-item-text-bottom">
                                        01-01-2024 10:32
                                    </div>
                                </div>
                            </div>
                            -->
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6 col-xl-6 mb-4">
                <div class="dashboard-content">
                    <div class="dashboard-content-title"><?php echo e(_i('Top 10 games')); ?></div>
                    <div class="top-ten-games">
                        <div class="top-ten-games-ex">
                            <div class="top-ten-games-head">
                                <div class="top-ten-games-head-tr">
                                    <?php echo e(_i('Game')); ?>

                                </div>
                                <div class="top-ten-games-head-tr">
                                    <?php echo e(_i('Provider')); ?>

                                </div>
                                <div class="top-ten-games-head-tr">
                                    <?php echo e(_i('Players')); ?>

                                </div>
                            </div>

                            <div class="top-ten-games-body">
                                <div class="top-ten-games-body-ex">
                                    <?php $__currentLoopData = $dashboard['games']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $game): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="top-ten-games-body-tr">
                                            <div class="top-ten-games-body-th">
                                                <figure style="background-image: url('<?php echo e(imageUrlFormat($game, $game?->maker)); ?>')"></figure>
                                                <?php echo e($game?->name); ?>

                                            </div>

                                            <div class="top-ten-games-body-th">
                                                <?php echo e($game?->maker); ?>

                                            </div>
                                            <div class="top-ten-games-body-th">
                                                <span class="deco-text"><?php echo e($game?->total_users); ?></span>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6 col-xl-6 mb-4">
                <div class="dashboard-content">
                    <div class="dashboard-content-title"><?php echo e(_i('Top 10 providers')); ?></div>
                    <div class="top-ten-games">
                        <div class="top-ten-games-ex">
                            <div class="top-ten-games-head">
                                <div class="top-ten-games-head-tr">
                                    <?php echo e(_i('Provider')); ?>

                                </div>
                                <div class="top-ten-games-head-tr">
                                    <?php echo e(_i('Games')); ?>

                                </div>
                                <div class="top-ten-games-head-tr">
                                    <?php echo e(_i('Players')); ?>

                                </div>
                            </div>

                            <div class="top-ten-games-body">
                                <div class="top-ten-games-body-ex">
                                    <?php $__currentLoopData = $dashboard['makers']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $maker): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="top-ten-games-body-tr">
                                            <div class="top-ten-games-body-th">
                                                
                                                <?php echo e($maker?->maker); ?>

                                            </div>
                                            <div class="top-ten-games-body-th">
                                                <span class="deco-text"><?php echo e($maker?->total_games); ?></span>
                                            </div>
                                            <div class="top-ten-games-body-th">
                                                <span class="deco-text"><?php echo e($maker?->total_users); ?></span>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('back.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


<?php $__env->startSection('styles'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="wrapper-title g-pb-30">
        <?php echo e(_i('Dashboard')); ?>

    </div>

    <div class="page-dashboard">
        <div class="row">
            <div class="col-12 col-xl-4 mb-4">
                <div class="dashboard-content">
                    <div class="dashboard-content-title"><?php echo e(_i('Total balance')); ?></div>
                    <div class="dash-balance">
                        <div class="dash-balance-total">
                            <!-- <span class="minus">$</span> 80,000.<span class="minus">00</span> -->
                            <span class="minus">$</span><?php echo e($dashboard['balance']['totalBalance']); ?></span>
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
                                        <span class="minus">$</span><?php echo e($dashboard['balance']['totalDeposited']); ?></span>
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
                                        <span class="minus">$</span>2,000.<span class="minus">00</span>
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
                                        <span class="minus">$</span>17,550.<span class="minus">00</span>
                                    </div>
                                </div>

                                <div class="dash-balance-amount-item">
                                    <div class="dash-balance-amount-name">
                                        <div class="dash-balance-amount-icon">
                                            <i class="fa-solid fa-arrow-trend-up"></i>
                                        </div>
                                        <div class="dash-balance-amount-name-text">
                                            <div class="span"><?php echo e(_i('Total amount')); ?></div>
                                            <div class="span"><?php echo e(_i('to turn off')); ?></div>
                                        </div>
                                    </div>

                                    <div class="dash-balance-amount-balance">
                                        <span class="minus">$</span>5,000.<span class="minus">00</span>
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
                                        <?php if( $transactions->transactionType == 1): ?>
                                            <div class="dash-transactions-item-text-top">
                                                <span class="icon green"><i class="fa-solid fa-circle"></i></span>
                                                <?php echo e($transactions->username); ?>

                                            </div>
                                        <?php else: ?>
                                            <div class="dash-transactions-item-text-top">
                                                <span class="icon"><i class="fa-solid fa-circle"></i></span>
                                                <?php echo e($transactions->username); ?>

                                            </div>
                                        <?php endif; ?>
                                        <div class="dash-transactions-item-text-bottom">
                                            <?php echo e($transactions->date); ?>

                                        </div>
                                    </div>

                                    <div class="dash-transactions-amount">
                                        <span class="minus">$</span>  <?php echo e($transactions->amount); ?>

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
                    <div class="dashboard-content-title"><?php echo e(_i('Recent activity')); ?></div>
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
                                    <div class="top-ten-games-body-tr">
                                        <div class="top-ten-games-body-th">
                                            <figure style="background-image: url('https://static.vecteezy.com/system/resources/previews/008/854/564/non_2x/dark-roulette-casino-3d-design-elements-png.png')"></figure>
                                            Black jack
                                        </div>
                                        <div class="top-ten-games-body-th">
                                            Vivogames
                                        </div>
                                        <div class="top-ten-games-body-th">
                                            <span class="deco-text">500</span>
                                        </div>
                                    </div>

                                    <div class="top-ten-games-body-tr">
                                        <div class="top-ten-games-body-th">
                                            <figure style="background-image: url('https://static.vecteezy.com/system/resources/previews/008/854/560/non_2x/black-gold-roulette-casino-3d-design-elements-free-png.png')"></figure>
                                            Ruleta
                                        </div>
                                        <div class="top-ten-games-body-th">
                                            Pragmatic
                                        </div>
                                        <div class="top-ten-games-body-th">
                                            <span class="deco-text">1,547</span>
                                        </div>
                                    </div>

                                    <div class="top-ten-games-body-tr">
                                        <div class="top-ten-games-body-th">
                                            <figure style="background-image: url('https://static.vecteezy.com/system/resources/previews/008/854/564/non_2x/dark-roulette-casino-3d-design-elements-png.png')"></figure>
                                            Black jack
                                        </div>
                                        <div class="top-ten-games-body-th">
                                            Vivogames
                                        </div>
                                        <div class="top-ten-games-body-th">
                                            <span class="deco-text">500</span>
                                        </div>
                                    </div>

                                    <div class="top-ten-games-body-tr">
                                        <div class="top-ten-games-body-th">
                                            <figure style="background-image: url('https://static.vecteezy.com/system/resources/previews/008/854/560/non_2x/black-gold-roulette-casino-3d-design-elements-free-png.png')"></figure>
                                            Ruleta
                                        </div>
                                        <div class="top-ten-games-body-th">
                                            Pragmatic
                                        </div>
                                        <div class="top-ten-games-body-th">
                                            <span class="deco-text">1,547</span>
                                        </div>
                                    </div>

                                    <div class="top-ten-games-body-tr">
                                        <div class="top-ten-games-body-th">
                                            <figure style="background-image: url('https://static.vecteezy.com/system/resources/previews/008/854/564/non_2x/dark-roulette-casino-3d-design-elements-png.png')"></figure>
                                            Black jack
                                        </div>
                                        <div class="top-ten-games-body-th">
                                            Vivogames
                                        </div>
                                        <div class="top-ten-games-body-th">
                                            <span class="deco-text">500</span>
                                        </div>
                                    </div>

                                    <div class="top-ten-games-body-tr">
                                        <div class="top-ten-games-body-th">
                                            <figure style="background-image: url('https://static.vecteezy.com/system/resources/previews/008/854/560/non_2x/black-gold-roulette-casino-3d-design-elements-free-png.png')"></figure>
                                            Ruleta
                                        </div>
                                        <div class="top-ten-games-body-th">
                                            Pragmatic
                                        </div>
                                        <div class="top-ten-games-body-th">
                                            <span class="deco-text">1,547</span>
                                        </div>
                                    </div>

                                    <div class="top-ten-games-body-tr">
                                        <div class="top-ten-games-body-th">
                                            <figure style="background-image: url('https://static.vecteezy.com/system/resources/previews/008/854/564/non_2x/dark-roulette-casino-3d-design-elements-png.png')"></figure>
                                            Black jack
                                        </div>
                                        <div class="top-ten-games-body-th">
                                            Vivogames
                                        </div>
                                        <div class="top-ten-games-body-th">
                                            <span class="deco-text">500</span>
                                        </div>
                                    </div>

                                    <div class="top-ten-games-body-tr">
                                        <div class="top-ten-games-body-th">
                                            <figure style="background-image: url('https://static.vecteezy.com/system/resources/previews/008/854/560/non_2x/black-gold-roulette-casino-3d-design-elements-free-png.png')"></figure>
                                            Ruleta
                                        </div>
                                        <div class="top-ten-games-body-th">
                                            Pragmatic
                                        </div>
                                        <div class="top-ten-games-body-th">
                                            <span class="deco-text">1,547</span>
                                        </div>
                                    </div>

                                    <div class="top-ten-games-body-tr">
                                        <div class="top-ten-games-body-th">
                                            <figure style="background-image: url('https://static.vecteezy.com/system/resources/previews/008/854/564/non_2x/dark-roulette-casino-3d-design-elements-png.png')"></figure>
                                            Black jack
                                        </div>
                                        <div class="top-ten-games-body-th">
                                            Vivogames
                                        </div>
                                        <div class="top-ten-games-body-th">
                                            <span class="deco-text">500</span>
                                        </div>
                                    </div>

                                    <div class="top-ten-games-body-tr">
                                        <div class="top-ten-games-body-th">
                                            <figure style="background-image: url('https://static.vecteezy.com/system/resources/previews/008/854/560/non_2x/black-gold-roulette-casino-3d-design-elements-free-png.png')"></figure>
                                            Ruleta
                                        </div>
                                        <div class="top-ten-games-body-th">
                                            Pragmatic
                                        </div>
                                        <div class="top-ten-games-body-th">
                                            <span class="deco-text">1,547</span>
                                        </div>
                                    </div>
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
                                    <div class="top-ten-games-body-tr">
                                        <div class="top-ten-games-body-th">
                                            <figure style="background-image: url('https://www.gammastack.com/wp-content/uploads/2021/12/PragmaticPlay-300x173.png')"></figure>
                                            Pragmatic
                                        </div>
                                        <div class="top-ten-games-body-th">
                                            <span class="deco-text">259</span>
                                        </div>
                                        <div class="top-ten-games-body-th">
                                            <span class="deco-text">1,500</span>
                                        </div>
                                    </div>

                                    <div class="top-ten-games-body-tr">
                                        <div class="top-ten-games-body-th">
                                            <figure style="background-image: url('https://nodepositslots.org/static/softwares/netent.png')"></figure>
                                            Netent
                                        </div>
                                        <div class="top-ten-games-body-th">
                                            <span class="deco-text">1,152</span>
                                        </div>
                                        <div class="top-ten-games-body-th">
                                            <span class="deco-text">3,251</span>
                                        </div>
                                    </div>

                                    <div class="top-ten-games-body-tr">
                                        <div class="top-ten-games-body-th">
                                            <figure style="background-image: url('https://www.gammastack.com/wp-content/uploads/2021/12/PragmaticPlay-300x173.png')"></figure>
                                            Pragmatic
                                        </div>
                                        <div class="top-ten-games-body-th">
                                            <span class="deco-text">259</span>
                                        </div>
                                        <div class="top-ten-games-body-th">
                                            <span class="deco-text">1,500</span>
                                        </div>
                                    </div>

                                    <div class="top-ten-games-body-tr">
                                        <div class="top-ten-games-body-th">
                                            <figure style="background-image: url('https://nodepositslots.org/static/softwares/netent.png')"></figure>
                                            Netent
                                        </div>
                                        <div class="top-ten-games-body-th">
                                            <span class="deco-text">1,152</span>
                                        </div>
                                        <div class="top-ten-games-body-th">
                                            <span class="deco-text">3,251</span>
                                        </div>
                                    </div>

                                    <div class="top-ten-games-body-tr">
                                        <div class="top-ten-games-body-th">
                                            <figure style="background-image: url('https://www.gammastack.com/wp-content/uploads/2021/12/PragmaticPlay-300x173.png')"></figure>
                                            Pragmatic
                                        </div>
                                        <div class="top-ten-games-body-th">
                                            <span class="deco-text">259</span>
                                        </div>
                                        <div class="top-ten-games-body-th">
                                            <span class="deco-text">1,500</span>
                                        </div>
                                    </div>

                                    <div class="top-ten-games-body-tr">
                                        <div class="top-ten-games-body-th">
                                            <figure style="background-image: url('https://nodepositslots.org/static/softwares/netent.png')"></figure>
                                            Netent
                                        </div>
                                        <div class="top-ten-games-body-th">
                                            <span class="deco-text">1,152</span>
                                        </div>
                                        <div class="top-ten-games-body-th">
                                            <span class="deco-text">3,251</span>
                                        </div>
                                    </div>

                                    <div class="top-ten-games-body-tr">
                                        <div class="top-ten-games-body-th">
                                            <figure style="background-image: url('https://www.gammastack.com/wp-content/uploads/2021/12/PragmaticPlay-300x173.png')"></figure>
                                            Pragmatic
                                        </div>
                                        <div class="top-ten-games-body-th">
                                            <span class="deco-text">259</span>
                                        </div>
                                        <div class="top-ten-games-body-th">
                                            <span class="deco-text">1,500</span>
                                        </div>
                                    </div>

                                    <div class="top-ten-games-body-tr">
                                        <div class="top-ten-games-body-th">
                                            <figure style="background-image: url('https://nodepositslots.org/static/softwares/netent.png')"></figure>
                                            Netent
                                        </div>
                                        <div class="top-ten-games-body-th">
                                            <span class="deco-text">1,152</span>
                                        </div>
                                        <div class="top-ten-games-body-th">
                                            <span class="deco-text">3,251</span>
                                        </div>
                                    </div>

                                    <div class="top-ten-games-body-tr">
                                        <div class="top-ten-games-body-th">
                                            <figure style="background-image: url('https://www.gammastack.com/wp-content/uploads/2021/12/PragmaticPlay-300x173.png')"></figure>
                                            Pragmatic
                                        </div>
                                        <div class="top-ten-games-body-th">
                                            <span class="deco-text">259</span>
                                        </div>
                                        <div class="top-ten-games-body-th">
                                            <span class="deco-text">1,500</span>
                                        </div>
                                    </div>

                                    <div class="top-ten-games-body-tr">
                                        <div class="top-ten-games-body-th">
                                            <figure style="background-image: url('https://nodepositslots.org/static/softwares/netent.png')"></figure>
                                            Netent
                                        </div>
                                        <div class="top-ten-games-body-th">
                                            <span class="deco-text">1,152</span>
                                        </div>
                                        <div class="top-ten-games-body-th">
                                            <span class="deco-text">3,251</span>
                                        </div>
                                    </div>
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
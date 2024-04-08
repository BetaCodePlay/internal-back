<?php
    use Dotworkers\Configurations\Configurations;
    use Dotworkers\Configurations\Enums\PaymentMethods;

    $uniquePaymentMethods = getUniquePaymentMethods();
?>

<?php if(Configurations::getPayments()): ?>
    <li class="u-sidebar-navigation-v1-menu-item u-side-nav--top-level-menu-item has-active">
    <a class="media u-side-nav--top-level-menu-link u-side-nav--hide-on-hidden" href="javascript:void(0)"
       data-toggle="collapse" data-target="#betpaySidebar" aria-expanded="true">
        <span class="g-pos-rel"><i class="fa-regular fa-user"></i></span> <span
            class="media-body align-self-center"><?php echo e(_i('BetPay')); ?></span>
        <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
    </a>
    <ul id="betpaySidebar"
        class="u-sidebar-navigation-v1-menu u-side-nav--second-level-menu u-side-nav--second-level-menu-top mb-0 collapse">
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [$permissions::$activate_payments_methods])): ?>
            <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item active">
                <a class="media u-side-nav--second-level-menu-link"
                   href="<?php echo e(route('betpay.clients.accounts.create')); ?>" target="_self">
                    <span class="media-body align-self-center"><?php echo e(_i('Activate Payment Methods')); ?></span>
                    <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                </a>
            </li>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [$permissions::$list_payments_methods])): ?>
            <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                <a class="media u-side-nav--second-level-menu-link"
                   href="<?php echo e(route('betpay.clients.accounts')); ?>" target="_self">
                    <span class="media-body align-self-center"><?php echo e(_i('List Accounts')); ?></span>
                    <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                </a>
            </li>
        <?php endif; ?>
        <?php if(in_array(PaymentMethods::$binance, $uniquePaymentMethods)): ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [$permissions::$binance_menu])): ?>
                <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                    <a class="media u-side-nav--second-level-menu-link" href="javascript:void(0)" target="_self"
                       data-toggle="collapse" data-target="#binanceSidebar" aria-expanded="false">
                        <span class="media-body align-self-center"><?php echo e(_i('Binance')); ?></span>
                        <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                    </a>
                    <ul id="binanceSidebar"
                        class="u-sidebar-navigation-v1-menu u-side-nav--second-level-menu mb-0 collapse <?php echo request()->is('betpay/binance*') ? 'show' : ''; ?>">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [$permissions::$credit_binance_menu])): ?>
                            <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                                <a class="media u-side-nav--second-level-menu-link"
                                   href="<?php echo e(route('betpay.binance.credit', [PaymentMethods::$binance])); ?>" target="_self">
                                    <span class="media-body align-self-center"><?php echo e(_i('Credit')); ?></span>
                                    <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [$permissions::$debit_binance_menu])): ?>
                            <li class="u-sidebar-navigation-v1-menu-item u-side-nav--second-level-menu-item">
                                <a class="media u-side-nav--second-level-menu-link"
                                   href="<?php echo e(route('betpay.binance.debit', [PaymentMethods::$binance])); ?>" target="_self">
                                    <span class="media-body align-self-center"><?php echo e(_i('Debit')); ?></span>
                                    <span class="icon-mobile"><i class="fa-solid fa-chevron-down"></i></span>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endif; ?>
        <?php endif; ?>
    </ul>
</li>
<?php endif; ?>

<?php

namespace App\Whitelabels\Collections;

/**
 * Class OperationalBalancesCollection
 *
 * This class allows to format operational balances
 *
 * @package App\Whitelabels\Collections
 * @author  Eborio Linárez
 */
class OperationalBalancesCollection
{
    /**
     * Format all balances
     *
     * @param array $operationalBalances Operational balances data
     */
    public function formatAll($operationalBalances)
    {
        foreach ($operationalBalances as $operationalBalance) {
            $operationalBalance->balance = number_format($operationalBalance->balance, 2);

            $operationalBalance->actions = sprintf(
                '<button class="btn u-btn-3d btn-sm u-btn-bluegray mr-2" data-toggle="modal" data-target="#update-operational-balance" data-whitelabel="%s" data-currency="%s">%s</button>',
                $operationalBalance->whitelabel_id,
                $operationalBalance->currency_iso,
                _i('Transactions')
            );
        }
    }
}

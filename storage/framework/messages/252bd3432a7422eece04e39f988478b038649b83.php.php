<?php

namespace App\Whitelabels\Repositories;

use App\Whitelabels\Entities\OperationalBalanceTransaction;

/**
 * Class OperationalBalancesTransactionsRepo
 *
 * This class allows to interact with OperationalBalanceTransaction entity
 *
 * @package App\Whitelabels\Repositories
 * @author  Eborio Linárez
 */
class OperationalBalancesTransactionsRepo
{
    /**
     * Store transaction
     *
     * @param array $data Transaction data
     * @return mixed
     */
    public function store($data)
    {
        $transaction = OperationalBalanceTransaction::create($data);
        return $transaction;
    }
}

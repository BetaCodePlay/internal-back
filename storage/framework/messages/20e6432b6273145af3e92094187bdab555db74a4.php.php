<?php

namespace App\Whitelabels\Repositories;

use App\Whitelabels\Entities\OperationalBalance;

/**
 * Class OperationalBalancesRepo
 *
 * This class allows to interact with OperationalBalance entity
 *
 * @package App\Whitelabels\Repositories
 * @author  Eborio Linárez
 */
class OperationalBalancesRepo
{
    /**
     * Get all whitelabels balances
     *
     * @return mixed
     */
    public function all()
    {
        $balances = OperationalBalance::select('balance', 'currency_iso', 'whitelabel_id', 'description')
            ->join('whitelabels', 'operational_balance.whitelabel_id', '=', 'whitelabels.id')
            ->orderBy('description', 'ASC')
            ->get();
        return $balances;
    }

    /**
     * Decrement balance
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $currency Currency ISO
     * @param float $amount Amount to decrement
     * @return mixed
     */
    public function decrement($whitelabel, $currency, $amount)
    {
        $balance = OperationalBalance::where('whitelabel_id', $whitelabel)
            ->where('currency_iso', $currency)
            ->decrement('balance', $amount);
        return $balance;
    }

    /**
     * Find operational balance
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $currency Currency ISO
     * @return mixed
     */
    public function find($whitelabel, $currency)
    {
        $balance = OperationalBalance::where('whitelabel_id', $whitelabel)
            ->where('currency_iso', $currency)
            ->first();
        return $balance;
    }

    /**
     * Increment balance
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $currency Currency ISO
     * @param float $amount Amount to increment
     * @return mixed
     */
    public function increment($whitelabel, $currency, $amount)
    {
        $balance = OperationalBalance::where('whitelabel_id', $whitelabel)
            ->where('currency_iso', $currency)
            ->increment('balance', $amount);
        return $balance;
    }
}

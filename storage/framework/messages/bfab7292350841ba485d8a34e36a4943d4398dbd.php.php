<?php

namespace App\Store\Repositories;

use App\Store\Entities\StoreExchange;

/**
 * Class StoreExchangesRepo
 *
 * This class allows to interact with Store_exchange entity
 *
 * @package App\Store\Repositories
 * @author  Damelys Espinoza
 */
class StoreExchangesRepo
{
    /**
     * Get exchanges by dates
     *
     * @param $currency
     * @param $whitelabel
     * @param $startDate
     * @param $endDate
     * @return mixed
     */
    public function getExchangeByDates($currency, $whitelabel, $startDate, $endDate)
    {
        $reward = StoreExchange::select('store_exchanges.*', 'users.username')
            ->join('users', 'users.id', '=', 'store_exchanges.user_id')
            ->where('store_rewards.currency_iso', $currency)
            ->where('store_rewards.whitelabel_id', $whitelabel)
            ->whereBetween('store_rewards.created_at', [$startDate, $endDate])
            ->get();
        return $reward;
    }

    /**
     * Get exchanges by dates and currency
     *
     * @param $currency
     * @param $whitelabel
     * @param $startDate
     * @param $endDate
     * @return mixed
     */
    public function getExchangeByDatesAndCurrency($currency, $whitelabel, $startDate, $endDate)
    {
        $rewards = StoreExchange::select('store_exchanges.*', 'users.username', 'store_rewards.name')
            ->join('users', 'users.id', '=', 'store_exchanges.user_id')
            ->join('store_rewards', 'store_rewards.id', '=', 'store_exchanges.reward_id')
            ->where('store_rewards.whitelabel_id', $whitelabel)
            ->whereBetween('store_exchanges.created_at', [$startDate, $endDate]);

        if (!is_null($currency)){
            $rewards->where('store_rewards.currency_iso', $currency);
        }
        $dataRewards = $rewards->get();
        return $dataRewards;
    }

    /**
     * Store exchange
     *
     * @param $exchange
     * @return StoreExchange
     */
    public function storeExchange($exchange)
    {
        $exchanges = new StoreExchange();
        $exchanges->fill($exchange);
        $exchanges->save();
        return $exchanges;
    }
}

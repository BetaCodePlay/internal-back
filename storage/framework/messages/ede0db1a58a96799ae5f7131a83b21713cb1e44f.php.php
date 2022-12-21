<?php

namespace Dotworkers\Store\Repositories;

use Dotworkers\Store\Entities\StoreExchange;

/**
 * Class StoreExchangesRepo
 *
 * This class allows to interact with StoreExchange entity
 *
 * @package Dotworkers\Store\Repositories
 * @author  Damelys Espinoza
 */
class StoreExchangesRepo
{
    /**
     * Get by user
     *
     * @param int $user User ID
     * @param string $currency Currency ISO
     * @param int $limit Transactions limit
     * @param int $offset Transactions offset
     * @return mixed
     */
    public function getByUser($user, $currency, $limit = 1000, $offset = 0)
    {
        $exchanges = StoreExchange::on(config('store.connection'))
            ->select('store_exchanges.created_at', 'store_exchanges.points', 'store_exchanges.data', 'store_rewards.name')
            ->join('store_rewards', 'store_exchanges.reward_id', '=', 'store_rewards.id')
            ->where('store_exchanges.user_id', $user)
            ->where('store_exchanges.currency_iso', $currency)
            ->orderBy('store_exchanges.id', 'DESC')
            ->limit($limit)
            ->offset($offset)
            ->get();
        return $exchanges;
    }

    /**
     * Store exchange
     *
     * @param array $data Exchange data
     * @return StoreExchange
     */
    public function store($data)
    {
        $exchange = StoreExchange::on(config('store.connection'))
            ->create($data);
        return $exchange;
    }
}
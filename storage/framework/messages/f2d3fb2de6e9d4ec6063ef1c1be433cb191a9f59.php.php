<?php

namespace Dotworkers\Store\Repositories;

use Dotworkers\Store\Entities\PointsTransaction;
use Dotworkers\Store\Entities\StoreTransaction;

/**
 * Class PointsTransactionsRepo
 *
 * This class allows to interact with PointsTransaction entity
 *
 * @package Dotworkers\Store\Repositories
 * @author  Eborio Linarez
 */
class PointsTransactionsRepo
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
        $transactions = PointsTransaction::on(config('store.connection'))
            ->select('points_transactions.id', 'points_transactions.amount', 'points_transactions.transaction_type_id',
            'points_transactions.created_at', 'points_transactions.balance', 'points_transactions.provider_id')
            ->join('points_wallets', 'points_transactions.points_wallet_id', '=', 'points_wallets.id')
            ->where('points_wallets.user_id', $user)
            ->where('points_transactions.currency_iso', $currency)
            ->orderBy('points_transactions.id', 'DESC')
            ->limit($limit)
            ->offset($offset)
            ->get();
        return $transactions;
    }

    /**
     * Get by user
     *
     * @param int $user User ID
     * @param string $currency Currency ISO
     * @param int $limit Transactions limit
     * @param int $offset Transactions offset
     * @return mixed
     */
    public function getByUserV2($user, $currency, $limit = 1000, $offset = 0)
    {
        $transactions = StoreTransaction::on(config('store.connection'))
            ->select('store_transactions.id', 'store_transactions.amount', 'store_transactions.transaction_type_id',
                'store_transactions.created_at', 'store_transactions.balance', 'store_transactions.provider_id')
            ->join('points_wallets', 'store_transactions.points_wallet_id', '=', 'points_wallets.id')
            ->where('points_wallets.user_id', $user)
            ->where('store_transactions.currency_iso', $currency)
            ->orderBy('store_transactions.id', 'DESC')
            ->limit($limit)
            ->offset($offset)
            ->get();
        return $transactions;
    }

    /**
     * Store transactions
     *
     * @param array $data Transaction data
     * @return mixed
     */
    public function store($data)
    {
        $transaction = PointsTransaction::on(config('store.connection'))
            ->create($data);
        return $transaction;
    }

    /**
     * Store transactions
     *
     * @param array $data Transaction data
     * @return mixed
     */
    public function storeV2($data)
    {
        $transaction = StoreTransaction::on(config('store.connection'))
            ->create($data);
        return $transaction;
    }
}

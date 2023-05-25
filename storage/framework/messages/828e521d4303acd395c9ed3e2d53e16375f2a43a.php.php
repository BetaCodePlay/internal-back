<?php

namespace Dotworkers\Store\Repositories;

use Dotworkers\Configurations\Enums\TransactionTypes;
use Dotworkers\Store\Entities\PointsWallet;

/**
 * Class PointsWalletsRepo
 *
 * This class allows to interact with PointsWallet entity
 *
 * @package Dotworkers\Store\Repositories
 * @author  Eborio Linarez
 */
class PointsWalletsRepo
{
    /**
     * Find by user and currency
     *
     * @param int $user User ID
     * @param string $currency Currency ISO
     * @return mixed
     */
    public function findByUserAndCurrency($user, $currency)
    {
        $wallet = PointsWallet::on(config('store.connection'))
            ->where('user_id', $user)
            ->where('currency_iso', $currency)
            ->first();
        return $wallet;
    }

    /**
     * Store wallets
     *
     * @param array $data Wallet data
     * @return mixed
     */
    public function store($data)
    {
        $wallet = PointsWallet::on(config('store.connection'))
            ->create($data);
        return $wallet;
    }

    /**
     * Update wallet balance
     *
     * @param int $user User ID
     * @param string $currency Currency ISO
     * @param float $amount Amount to update
     * @param int $transactionType Transaction type
     * @return mixed
     */
    public function updateBalance($user, $currency, $amount, $transactionType)
    {
        if ($transactionType == TransactionTypes::$credit) {
            $wallet = PointsWallet::on(config('store.connection'))
                ->where('user_id', $user)
                ->where('currency_iso', $currency)
                ->increment('balance', $amount);
        } else {
            $wallet = PointsWallet::on(config('store.connection'))
                ->where('user_id', $user)
                ->where('currency_iso', $currency)
                ->decrement('balance', $amount);
        }
        return $wallet;
    }
}

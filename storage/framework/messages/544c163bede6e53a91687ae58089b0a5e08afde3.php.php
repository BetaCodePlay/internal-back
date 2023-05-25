<?php

namespace Dotworkers\Bonus\Repositories;

use Dotworkers\Bonus\Entities\Transaction;
use Dotworkers\Configurations\Enums\ProviderTypes;
use Dotworkers\Configurations\Enums\TransactionStatus;
use Dotworkers\Configurations\Enums\TransactionTypes;

/**
 * Class TransactionsRepo
 *
 * This class allows to manage the data of Transaction entity
 *
 * @package Dotworkers\Bonus\Repositories
 * @author  Damelys Espinoza
 */
class TransactionsRepo
{
    /**
     * Find first deposit
     *
     * @param int $user User ID
     * @param string $currency Currency ISO
     * @return mixed
     */
    public function findFirstDeposit(int $user, string $currency)
    {
        return Transaction::on(config('bonus.connection'))
            ->join('providers', 'transactions.provider_id', '=', 'providers.id')
            ->where('user_id', $user)
            ->where('currency_iso', $currency)
            ->where('transaction_status_id', TransactionStatus::$approved)
            ->where('transaction_type_id', TransactionTypes::$credit)
            ->whereIn('providers.provider_type_id', [ProviderTypes::$dotworkers, ProviderTypes::$payment])
            ->orderBy('transactions.id', 'ASC')
            ->first();
    }

    /**
     * Find last deposit before date
     *
     * @param int $user User ID
     * @param int $currency Currency type ISO
     * @param string $startDate Start date
     * @return mixed
     */
    public function findLastDepositBeforeDate($user, $currency, $startDate)
    {
        return Transaction::on(config('bonus.connection'))
            ->join('providers', 'transactions.provider_id', '=', 'providers.id')
            ->where('transactions.user_id', $user)
            ->where('transactions.currency_iso', $currency)
            ->where('transaction_status_id', TransactionStatus::$approved)
            ->where('transaction_type_id', TransactionTypes::$credit)
            ->whereIn('providers.provider_type_id', [ProviderTypes::$dotworkers, ProviderTypes::$payment])
            ->where('transactions.created_at', '>=', $startDate)
            ->orderBy('transactions.id', 'DESC')
            ->first();
    }

    /**
     * Store transactions
     *
     * @param array $data Transaction data
     * @param int $status RolloversStatus ID
     * @param array $detailsData Details data
     * @return mixed
     */
    public function store($data, $status, $detailsData)
    {
        $transaction = Transaction::on(config('bonus.connection'))->create($data);
        $transaction->details()->attach($status, $detailsData);
        return $transaction;
    }
}

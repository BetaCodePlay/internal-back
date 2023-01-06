<?php

namespace App\Wallets\Collections;

use App\Core\Core;
use Carbon\Carbon;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Configurations\Enums\Providers;
use Dotworkers\Configurations\Enums\TransactionTypes;
use Dotworkers\Wallet\Enums\Actions;

/**
 * Class TransactionsCollection
 *
 * This class allow to format transactions data
 *
 * @package App\Wallets\Collections
 * @author  Damelys Espinoza
 */
class TransactionsCollection
{
    /**
     * Format transactions
     *
     * @param $transactions
     */
    public function formatTransactions($transactions)
    {
        foreach ($transactions as $transaction) {
            try {
                $totalBalance = $transaction->balance;
                $timezone = session('timezone');
                $transaction->date = Carbon::createFromFormat('Y-m-d H:i:s', $transaction->created_at)->setTimezone($timezone)->format('d-m-Y H:i:s');
                $transaction->amount = number_format($transaction->amount, 2);
                $transaction->modified_amount = $transaction->transaction_type_id == TransactionTypes::$debit ? "-{$transaction->amount}" : "+{$transaction->amount}";
                $transaction->debit = $transaction->transaction_type_id == TransactionTypes::$debit ? $transaction->amount : '-';
                $transaction->credit = $transaction->transaction_type_id == TransactionTypes::$credit ? $transaction->amount : '-';
                $transaction->description = Providers::getDescription($transaction->provider_id, $transaction->transaction_type_id, $transaction->action_id, $transaction->data);
                $transaction->provider = Providers::getName($transaction->provider_id);

                if (isset($transaction->balances)) {
                    foreach ($transaction->balances as $balance) {
                        $totalBalance += $balance->balance;
                    }
                }

                $transaction->balance = number_format($totalBalance, 2);
            } catch (\Exception $ex) {
                \Log::error(__METHOD__, ['exception' => $ex, 'transaction' => $transaction]);
            }
        }
    }
}

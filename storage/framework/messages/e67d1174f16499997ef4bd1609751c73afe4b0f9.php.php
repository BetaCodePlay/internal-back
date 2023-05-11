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
                if((Configurations::getWhitelabel() == 1) && ($transaction->provider_id == 171) && ($transaction->id ==13315112)){
                    \Log::info(__METHOD__, ['formatTransactions' => $transaction]);
                }
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

    /**
     * Data Table transactions user page
     * Format transactions
     *
     * @param $transactions
     */
    public function formatTransactionsPage($transactions,$total,$request)
    {

        $data = array();
        foreach ($transactions as $transaction) {
                $totalBalance = $transaction->balance;
                $timezone = session('timezone');

                $debit = $transaction->transaction_type_id == TransactionTypes::$debit ?  number_format($transaction->amount, 2, ",", ".") : '-';
                $credit = $transaction->transaction_type_id == TransactionTypes::$credit ?  number_format($transaction->amount, 2, ",", ".") : '-';

                if (isset($transaction->balances)) {
                    foreach ($transaction->balances as $balance) {
                        $totalBalance += $balance->balance;
                    }
                }

                $data[] = [
                    'id' => null,
                    'date' => Carbon::createFromFormat('Y-m-d H:i:s', $transaction->created_at)->setTimezone($timezone)->format('d-m-Y H:i:s'),
                    'amount' => number_format($transaction->amount, 2),
                    'description' => Providers::getDescription($transaction->provider_id, $transaction->transaction_type_id, $transaction->action_id, $transaction->data),
                    'provider' =>  Providers::getName($transaction->provider_id),
                    'modified_amount' =>  $transaction->transaction_type_id == TransactionTypes::$debit ? "-{$transaction->amount}" : "+{$transaction->amount}",
    //                'data' => [
    //                    'from' => $from,
    //                    'to' => $to,
    //                ],
                    'debit' => $debit,
                    'credit' =>$credit,
                    'balance' => number_format($totalBalance, 2),
                ];


        }

        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($total),
            "recordsFiltered" => intval($total),
            "data" => $data
        );

        return $json_data;

    }
}

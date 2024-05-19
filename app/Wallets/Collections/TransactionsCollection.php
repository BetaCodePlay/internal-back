<?php

namespace App\Wallets\Collections;

use Carbon\Carbon;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Configurations\Enums\Providers;
use Dotworkers\Configurations\Enums\TransactionTypes;
use Dotworkers\Configurations\Utils;
use Dotworkers\Wallet\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\Response;

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
                $totalBalance                 = $transaction->balance;
                $timezone                     = session('timezone');
                $transaction->date            = Carbon::createFromFormat(
                    'Y-m-d H:i:s',
                    $transaction->created_at
                )->setTimezone(
                    $timezone
                )->format('d-m-Y H:i:s');
                $transaction->amount          = number_format($transaction->amount, 2);
                $transaction->modified_amount = $transaction->transaction_type_id == TransactionTypes::$debit ? "-{$transaction->amount}" : "+{$transaction->amount}";
                $transaction->debit           = $transaction->transaction_type_id == TransactionTypes::$debit ? $transaction->amount : '-';
                $transaction->credit          = $transaction->transaction_type_id == TransactionTypes::$credit ? $transaction->amount : '-';
                if ((Configurations::getWhitelabel() == 1)
                    && ($transaction->provider_id == 171)
                    && ($transaction->id == 13315112)) {
                    \Log::info(__METHOD__, ['formatTransactions' => $transaction]);
                }
                $transaction->description = Providers::getDescription(
                    $transaction->provider_id,
                    $transaction->transaction_type_id,
                    $transaction->action_id,
                    $transaction->data
                );
                $transaction->provider    = Providers::getName($transaction->provider_id);

                if (isset($transaction->balances)) {
                    foreach ($transaction->balances as $balance) {
                        $totalBalance += $balance->balance;
                    }
                }

//                if($transaction->provider_id ==  Providers::$agents_users){
//                    $transaction->debit = $transaction->transaction_type_id == TransactionTypes::$debit ?  '-':$transaction->amount;
//                    $transaction->credit = $transaction->transaction_type_id == TransactionTypes::$credit ?  '-':$transaction->amount;
//                }

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
    public function formatTransactionsPage($transactions, $total, $request)
    {
        $data = array();
        foreach ($transactions as $transaction) {
            $totalBalance = $transaction->balance;
            $timezone     = session('timezone');

            $debit  = $transaction->transaction_type_id == TransactionTypes::$debit ? number_format(
                $transaction->amount,
                2,
                ",",
                "."
            ) : '-';
            $credit = $transaction->transaction_type_id == TransactionTypes::$credit ? number_format(
                $transaction->amount,
                2,
                ",",
                "."
            ) : '-';

            if (isset($transaction->balances)) {
                foreach ($transaction->balances as $balance) {
                    $totalBalance += $balance->balance;
                }
            }

            $data[] = [
                'id'              => null,
                'date'            => Carbon::createFromFormat('Y-m-d H:i:s', $transaction->created_at)->setTimezone(
                    $timezone
                )->format('d-m-Y H:i:s'),
                'amount'          => number_format($transaction->amount, 2),
                'description'     => Providers::getDescription(
                    $transaction->provider_id,
                    $transaction->transaction_type_id,
                    $transaction->action_id,
                    $transaction->data
                ),
                'provider'        => Providers::getName($transaction->provider_id),
                'modified_amount' => $transaction->transaction_type_id == TransactionTypes::$debit ? "-{$transaction->amount}" : "+{$transaction->amount}",
                //                'data' => [
                //                    'from' => $from,
                //                    'to' => $to,
                //                ],
                'debit'           => $debit,
                'credit'          => $credit,
                'balance'         => number_format($totalBalance, 2),
            ];
        }

        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($total),
            "recordsFiltered" => intval($total),
            "data"            => $data
        );

        return $json_data;
    }

    /**
     * Formats transactions according to Assiria specifications.
     *
     * This method formats the provided transactions according to the specific requirements
     * of the Assiria application.
     *
     * @param Request $request The HTTP request containing the necessary data for formatting.
     *
     * @return array|Response The formatted transaction data or an error response.
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function formatTransactionsAssiria(Request $request, int | string $walletId)
    : array|Response {
        try {
            $draw             = $request->input('draw', 1);
            $data             = $request->all();
            $timezone         = session()->get('timezone');
            $data['timezone'] = $timezone;
            dd($data, $walletId);
            $resp             = Wallet::getTransactionsByWalletAssiriaBack($data);
            $transactionsData = $resp->transactions ?? [];
            $recordsTotal     = $resp->recordsTotal ?? 0;

            $formattedResults = Collection::make($transactionsData)->map(function ($transaction) use ($timezone) {
                $transaction->date = Carbon::createFromFormat(
                    'Y-m-d H:i:s',
                    $transaction->created_at
                )->setTimezone(
                    $timezone
                )->format('d-m-Y H:i:s');

                $transaction->amount          = number_format($transaction->amount, 2);
                $transaction->modified_amount = ($transaction->transaction_type_id == TransactionTypes::$debit ? '-$ ' : '+$ ') . $transaction->amount;

                $transaction->concept = ($transaction->transaction_type_id == TransactionTypes::$credit ? _i(
                    'Income'
                ) : _i('Withdrawal'));

                $transaction->balance = number_format($transaction->balance, 2);

                $transaction->description = Providers::getDescription(
                    $transaction->provider_id,
                    $transaction->transaction_type_id,
                    $transaction->action_id,
                    $transaction->data
                );

                $transaction->provider = (isset($transaction->data->provider_id)) ? Providers::getName(
                    $transaction->data->provider_id
                ) : Providers::getName($transaction->provider_id) . ' - ' . $transaction->data->maker;

                return [
                    $transaction->id ?? '',
                    $transaction->date ?? '',
                    $transaction->data->provider_transaction ?? '',
                    $transaction->provider ?? '',
                    $transaction->modified_amount,
                    $transaction->description
                ];
            });

            return [
                'draw'            => (int)$draw,
                'recordsTotal'    => $recordsTotal,
                'recordsFiltered' => $recordsTotal,
                'data'            => $formattedResults->toArray()
            ];
        } catch (\Exception $ex) {
            Log::error(__METHOD__, ['exception' => $ex, 'wallet' => $request->input('wallet')]);
            return Utils::failedResponse();
        }
    }
}

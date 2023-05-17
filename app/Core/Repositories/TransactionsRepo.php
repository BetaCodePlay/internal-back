<?php

namespace App\Core\Repositories;

use App\Core\Entities\Transaction;
use Carbon\Carbon;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Configurations\Enums\Providers;
use Dotworkers\Configurations\Enums\ProviderTypes;
use Dotworkers\Configurations\Enums\TransactionTypes;
use Dotworkers\Configurations\Enums\TransactionStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Utilities\Helper;

/**
 * Class TransactionsRepo
 *
 * This class allows to interact with Transaction entity
 *
 * @package App\Core\Repositories
 * @author  Eborio Linarez
 */
class TransactionsRepo
{
    /**
     * Count approved transactions by user and currency and date
     *
     * @param int $user User ID
     * @param int $currency Currency Iso
     * @param string $startDate Start date
     * @return mixed
     */
    public function approvedByUserAndDate($user, $currency, $startDate)
    {
        return Transaction::join('providers', 'transactions.provider_id', '=', 'providers.id')
            ->where('transactions.user_id', $user)
            ->where('transactions.currency_iso', $currency)
            ->where('transaction_status_id', TransactionStatus::$approved)
            ->where('transaction_type_id', TransactionTypes::$credit)
            ->whereIn('providers.provider_type_id', [ProviderTypes::$dotworkers, ProviderTypes::$payment])
            ->where('transactions.created_at', '>=', $startDate)
            ->orderBy('transactions.id', 'ASC')
            ->limit(1)
            ->first();
    }

    /**
     * Get count by provider types
     *
     * @param int $whitelabel Whitelabel Id
     * @param int $transactionType Transaction type
     * @param string $currency Currency Iso
     * @param array $providerTypes Provider types
     * @param string $startDate Start date to filter
     * @param string $endDate End date to filter
     * @param int $status Transaction status
     * @return mixed
     */
    public function countByProviderTypes($whitelabel, $transactionType, $currency, $providerTypes, $startDate, $endDate, $status)
    {
        $transactions = Transaction::on('replica')
            ->join('providers', 'transactions.provider_id', '=', 'providers.id')
            ->where('transactions.whitelabel_id', $whitelabel)
            ->where('transactions.currency_iso', $currency)
            ->whereIn('providers.provider_type_id', $providerTypes)
            ->where('transaction_type_id', $transactionType)
            ->where('transaction_status_id', $status);

        if (!is_null($startDate) && !is_null($endDate)) {
            $transactions->whereBetween('transactions.created_at', [$startDate, $endDate]);
        }

        return $transactions->count();
    }

    /**
     * Find by BetPay transaction
     *
     * @param int $betPayTransaction BetPay transaction ID
     * @return mixed
     */
    public function findByBetPayTransaction($betPayTransaction)
    {
        return Transaction::whereRaw("data::json->>'betpay_transaction' = ?", $betPayTransaction)
            ->where('whitelabel_id', Configurations::getWhitelabel())
            ->where('currency_iso', session('currency'))
            ->first();
    }

    /**
     * Find first deposit
     *
     * @param int $user User ID
     * @param string $currency Currency Iso
     * @return mixed
     */
    public function findFirstDeposit(int $user, string $currency)
    {
        return Transaction::join('providers', 'transactions.provider_id', '=', 'providers.id')
            ->where('user_id', $user)
            ->where('currency_iso', $currency)
            ->where('transaction_status_id', TransactionStatus::$approved)
            ->where('transaction_type_id', TransactionTypes::$credit)
            ->whereIn('providers.provider_type_id', [ProviderTypes::$dotworkers, ProviderTypes::$payment])
            ->orderBy('transactions.id', 'DESC')
            ->first();
    }

    /**
     * Get agents transactions
     *
     * @param int $user Users IDs
     * @param array $providers Providers IDs
     * @param string $currency Currency Iso
     * @param string $startDate Start date to filter
     * @param string $endDate End date to filter
     * @return mixed
     */
    public function getAgentsTransactions($user, $providers, $currency, $startDate, $endDate)
    {
        $debit = Transaction::select('users.id', 'users.username', \DB::raw('sum(amount) AS total'))
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->where('transactions.user_id', $user)
            ->where('transactions.currency_iso', $currency)
            ->whereIn('transactions.provider_id', $providers)
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->where('transaction_type_id', TransactionTypes::$debit)
            ->groupBy('users.id', 'users.username')
            ->get();

        $credit = Transaction::select('users.id', 'users.username', \DB::raw('sum(amount) AS total'))
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->where('transactions.user_id', $user)
            ->where('transactions.currency_iso', $currency)
            ->whereIn('transactions.provider_id', $providers)
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->where('transaction_type_id', TransactionTypes::$credit)
            ->groupBy('users.id', 'users.username')
            ->get();

        return [
            'debit' => $debit,
            'credit' => $credit
        ];
    }

    /**
     * Get bonus total by user
     *
     * @param int $user User ID
     * @param string $currency Currency Iso
     * @return array
     */
    public function getBonusTotalByUser($user, $currency)
    {
        return Transaction::join('providers', 'transactions.provider_id', '=', 'providers.id')
            ->where('transactions.user_id', $user)
            ->where('transactions.currency_iso', $currency)
            ->where('transaction_type_id', TransactionTypes::$credit)
            ->where('transaction_status_id', TransactionStatus::$approved)
            ->whereIn('providers.provider_type_id', [ProviderTypes::$bonus_transactions])
            ->sum('amount');
    }

    /**
     * Get bonus totals by users
     *
     * @param array $users User ID
     * @param string $currency Currency Iso
     * @param string $startDate Start date to filter
     * @param string $endDate End date to filter
     * @return array
     */
    public function getBonusTotalByUsers($users, $currency, $startDate, $endDate)
    {
        return DB::select("SELECT user_id, sum(amount) AS bonus
            FROM transactions
            JOIN providers ON transactions.provider_id = providers.id
            WHERE user_id IN (" . implode(',', $users) . ")
            AND transactions.currency_iso = ?
            AND transaction_type_id = ?
            AND transaction_status_id = ?
            AND providers.provider_type_id IN (?)
            AND transactions.created_at BETWEEN ? AND ?
            GROUP BY user_id", [$currency, TransactionTypes::$credit, TransactionStatus::$approved, ProviderTypes::$bonus_transactions, $startDate, $endDate]);
        return $bonus;
    }

    /**
     * Get by status
     *
     * @param int $provider Provider ID
     * @param int $transactionType Transaction type
     * @param string $currency Currency Iso
     * @param int $status Status ID
     * @param string $startDate Start date to filter
     * @param string $endDate End date to filter
     * @return mixed
     */
    public function getByStatusAndDates($provider, $transactionType, $currency, $status, $startDate, $endDate)
    {
        $transactions = Transaction::select('transactions.id', 'amount', 'transactions.currency_iso', 'transactions.transaction_status_id',
            'transactions.created_at', 'transactions.updated_at', 'users.id', 'users.username', 'transactions.data', 'transactions.reference', 'transaction_details.data AS details_data')
            ->leftJoin('users', 'transactions.user_id', '=', 'users.id')
            ->join('transaction_details', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->where('transaction_type_id', $transactionType)
            ->where('transactions.currency_iso', $currency)
            ->where('transactions.transaction_status_id', $status)
            ->where('transaction_details.transaction_status_id', $status)
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->where('transactions.provider_id', $provider)
            ->orderBy('created_at', 'DESC')
            ->get();
        return $transactions;
    }

    /**
     * Get  transactions by transaction type and provider types
     *
     * @param int $whitelabel Whitelabel Id
     * @param string $currency Currency Iso
     * @param int $transactionType Transaction type ID
     * @param array $providers Providers
     * @param string $startDate Start date to filter
     * @param string $endDate End date to filter
     * @param int $status Status ID
     * @return mixed
     */
    public function getByTransactionTypeAndProviderTypes($whitelabel, $currency, $transactionType, $providerTypes, $startDate, $endDate, $status)
    {
        $transactions = Transaction::select('transactions.*', 'users.username', 'users.id as user', 'transaction_details.data as details')
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->rightJoin('providers', 'transactions.provider_id', '=', 'providers.id')
            ->join('transaction_details', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->where('users.whitelabel_id', $whitelabel)
            ->where('transactions.currency_iso', $currency)
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->where('transactions.transaction_type_id', $transactionType)
            ->where('transactions.transaction_status_id', $status)
            ->where('transaction_details.transaction_status_id', $status)
            ->whereIn('providers.provider_type_id', $providerTypes)
            ->get();
        return $transactions;
    }

    /**
     * Get  transactions by transaction type and providers
     *
     * @param int $whitelabel Whitelabel Id
     * @param string $currency Currency Iso
     * @param int $transactionType Transaction type ID
     * @param array $providers Providers
     * @param string $startDate Start date to filter
     * @param string $endDate End date to filter
     * @param int $status Status ID
     * @return mixed
     */
    public function getByTransactionTypeAndProviders($whitelabel, $currency, $transactionType, $providers, $startDate, $endDate, $status)
    {
        $transactions = Transaction::select('transactions.*', 'users.username', 'users.id as user')
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->join('transaction_details', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->where('transaction_details.transaction_status_id', $status)
            ->where('users.whitelabel_id', $whitelabel)
            ->where('transactions.currency_iso', $currency)
            ->whereIn('transactions.provider_id', $providers)
            ->whereBetween('transactions.updated_at', [$startDate, $endDate])
            ->where('transactions.transaction_type_id', $transactionType)
            ->where('transactions.transaction_status_id', $status)
            ->orderBy('transactions.updated_at', 'DESC')
            ->get();
        return $transactions;
    }

    /**
     * Get transactions list by user and provider types
     *
     * @param int $user User ID
     * @param string $currency Currency Iso
     * @param array $provideTypes Provider types
     * @param int $limit Transactions limit
     * @param int $offset Transactions offset
     * @return mixed
     */
    public function getByUserAndProviderTypes($user, $currency, $provideTypes, $limit = 2000, $offset = 0)
    {
        $transactions = Transaction::select('transactions.id', 'transactions.amount', 'transactions.transaction_type_id',
            'transactions.created_at', 'transactions.provider_id', 'transactions.data', 'transactions.transaction_status_id', 'transaction_details.data as details')
            ->join('providers', 'transactions.provider_id', '=', 'providers.id')
            ->join('transaction_details', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->where('transactions.user_id', $user)
            ->where('transactions.currency_iso', $currency)
            ->whereIn('providers.provider_type_id', $provideTypes)
            ->whereRaw("transactions.transaction_status_id = transaction_details.transaction_status_id")
            ->orderBy('transactions.id', 'DESC')
            ->limit($limit)
            ->offset($offset)
            ->get();
        return $transactions;
    }

    /**
     * Get transactions list by user and provider
     *
     * @param int $user User ID
     * @param array $providers Providers IDS
     * @param string $currency Currency Iso
     * @param int $limit Transactions limit
     * @param int $offset Transactions offset
     * @return mixed
     */
    public function getByUserAndProviders($user, $providers, $currency, $limit = 2000, $offset = 0)
    {
        $transactions = Transaction::select('transactions.id', 'transactions.amount', 'transactions.transaction_type_id',
            'transactions.created_at', 'transactions.provider_id', 'transactions.data', 'transactions.transaction_status_id')
            ->where('transactions.user_id', $user)
            ->where('transactions.currency_iso', $currency)
            ->whereIn('transactions.provider_id', $providers)
            ->orderBy('transactions.id', 'DESC')
            ->limit($limit)
            ->offset($offset)
            ->get();

        return $transactions;
    }

    /**
     * Get transactions list by user and provider With Paginate
     *
     * @param int $user User ID
     * @param array $providers Providers IDS
     * @param string $currency Currency Iso
     * @param int $limit Transactions limit
     * @param int $offset Transactions offset
     * @return mixed
     */
    public function getByUserAndProvidersPaginate($user, $providers, $currency, $startDate, $endDate, $limit = 2000, $offset = 0,$username = null,$typeUser = null)
    {
        if (is_null($typeUser) || $typeUser == 'all') {
            $countTransactions = Transaction::select('transactions.id')
                ->where('transactions.user_id', $user)
                ->whereBetween('transactions.created_at', [$startDate, $endDate])
                ->where('transactions.currency_iso', $currency)
                ->whereIn('transactions.provider_id', $providers)
                ->orderBy('transactions.id', 'DESC');

                if (!is_null($username)) {
                    $countTransactions = $countTransactions->where('username', 'ilike', "%$username%");
                }
                $countTransactions = $countTransactions->get();

            $transactions = Transaction::select('users.username','transactions.user_id', 'transactions.id', 'transactions.amount', 'transactions.transaction_type_id',
                'transactions.created_at', 'transactions.provider_id', 'transactions.data', 'transactions.transaction_status_id')
                ->join('users', 'transactions.user_id', '=', 'users.id')
                ->where('transactions.user_id', $user)
                ->whereBetween('transactions.created_at', [$startDate, $endDate])
                ->where('transactions.currency_iso', $currency)
                ->whereIn('transactions.provider_id', $providers)
                ->orderBy('transactions.id', 'DESC')
                ->limit($limit)
                ->offset($offset);

            if (!is_null($username)) {
                $transactions = $transactions->where('username', 'ilike', "%$username%");
            }

            $transactions = $transactions->get();
            return [$transactions, count($countTransactions)];
        }elseif ($typeUser == 'agent'){
            $countTransactions = Transaction::select('transactions.id')
                ->where('transactions.user_id', $user)
                ->whereBetween('transactions.created_at', [$startDate, $endDate])
                ->where('transactions.currency_iso', $currency)
                ->whereNull('data->provider_transaction')
                ->whereIn('transactions.provider_id', $providers)
                ->orderBy('transactions.id', 'DESC');

            if (!is_null($username)) {
                $countTransactions = $countTransactions->where('username', 'ilike', "%$username%");
            }
            $countTransactions = $countTransactions->get();

            $transactions = Transaction::select('users.username','transactions.user_id', 'transactions.id', 'transactions.amount', 'transactions.transaction_type_id',
                'transactions.created_at', 'transactions.provider_id', 'transactions.data', 'transactions.transaction_status_id')
                ->join('users', 'transactions.user_id', '=', 'users.id')
                ->whereNull('data->provider_transaction')
                ->where('transactions.user_id', $user)
                ->whereBetween('transactions.created_at', [$startDate, $endDate])
                ->where('transactions.currency_iso', $currency)
                ->whereIn('transactions.provider_id', $providers)
                ->orderBy('transactions.id', 'DESC')
                ->limit($limit)
                ->offset($offset);

            if (!is_null($username)) {
                $transactions = $transactions->where('username', 'ilike', "%$username%");
            }

            $transactions = $transactions->get();
            return [$transactions, count($countTransactions)];

        } else {
            $countTransactions = Transaction::select('transactions.id')
                ->where('transactions.user_id', $user)
                ->whereBetween('transactions.created_at', [$startDate, $endDate])
                ->where('transactions.currency_iso', $currency)
                ->whereNotNull('data->provider_transaction')
                ->whereIn('transactions.provider_id', $providers)
                ->orderBy('transactions.id', 'DESC');

            if (!is_null($username)) {
                $countTransactions = $countTransactions->where('username', 'ilike', "%$username%");
            }
            $countTransactions = $countTransactions->get();

            $transactions = Transaction::select('users.username', 'transactions.user_id','transactions.id', 'transactions.amount', 'transactions.transaction_type_id',
                'transactions.created_at', 'transactions.provider_id', 'transactions.data', 'transactions.transaction_status_id')
                ->join('users', 'transactions.user_id', '=', 'users.id')
                ->whereNotNull('data->provider_transaction')
                ->where('transactions.user_id', $user)
                ->whereBetween('transactions.created_at', [$startDate, $endDate])
                ->where('transactions.currency_iso', $currency)
                ->whereIn('transactions.provider_id', $providers)
                ->orderBy('transactions.id', 'DESC')
                ->limit($limit)
                ->offset($offset);

            if (!is_null($username)) {
                $transactions = $transactions->where('username', 'ilike', "%$username%");
            }

            $transactions = $transactions->get();
            return [$transactions, count($countTransactions)];

        }

    }

    /**
     * Totals Data Makers
     * Providers And Currency
     *
     * @param int $user User ID
     * @param array $providers Providers IDS
     * @param string $currency Currency Iso
     * @return mixed
     */
    public function getFinancialDataMakersTotals(string $startDate, string $endDate, string $currency, $provider, $whitelabel)
    {
        return DB::select('SELECT * FROM site.get_closure_totals_by_provider_and_maker_global_total(?,?,?,?,?,?,?,?)', [$whitelabel, $currency, $startDate, $endDate, $provider]);
    }

     /**
     *  Get transactions list by user and provider With Paginate V 1.0
     *
     * @param int $user User ID
     * @param array $providers Providers IDS
     * @param array $arraySonIds Ids Son All
     * @param string $currency Currency Iso
     * @param int $limit Transactions limit
     * @param int $offset Transactions offset
     * @return mixed
     */
    public function getByUserAndProvidersPaginateV1($user, $providers, $currency, $startDate, $endDate, $limit = 2000, $offset = 0,$username = null,$typeUser = null,$arraySonIds = [])
    {

        $countTransactions = Transaction::select('transactions.id')
            ->whereIn('transactions.user_id', $arraySonIds)
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->where('transactions.currency_iso', $currency)
            ->whereIn('transactions.provider_id', $providers)
            ->orderBy('transactions.id', 'DESC');

       $transactions = Transaction::select('users.username','transactions.user_id', 'transactions.id', 'transactions.amount', 'transactions.transaction_type_id',
            'transactions.created_at', 'transactions.provider_id', 'transactions.data', 'transactions.transaction_status_id')
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->whereIn('transactions.user_id', $arraySonIds)
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->where('transactions.currency_iso', $currency)
            ->whereIn('transactions.provider_id', $providers)
            ->orderBy('transactions.id', 'DESC')
            ->limit($limit)
            ->offset($offset);

        if (is_null($typeUser) || $typeUser === 'all') {

        }elseif ($typeUser === 'agent'){
            $countTransactions = $countTransactions->whereNull('data->provider_transaction');
            $transactions = $transactions->whereNull('data->provider_transaction');
        } else {
            $countTransactions = $countTransactions->whereNotNull('data->provider_transaction');
            $transactions = $transactions->whereNotNull('data->provider_transaction');
        }

        if (!is_null($username)) {
            $countTransactions = $countTransactions->where('username', 'ilike', "%$username%");
            $transactions = $transactions->where('username', 'ilike', "%$username%");
        }

        $countTransactions = $countTransactions->get();
        $transactions = $transactions->get();

        return [$transactions, count($countTransactions)];

    }

    /**
     * Totals Transactions by user
     * Providers And Currency
     *
     * @param int $user User ID
     * @param array $providers Providers IDS
     * @param string $currency Currency Iso
     * @return mixed
     */
    public function getByUserAndProvidersTotales($user, $providers, $currency, $startDate, $endDate,$typeUser=null)
    {

        $countTransactions = Transaction::select('transactions.id', 'transactions.user_id','transactions.data','transactions.amount', 'transactions.transaction_type_id')
            ->where('transactions.user_id', $user)
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->where('transactions.currency_iso', $currency)
            ->whereIn('transactions.provider_id', $providers)
            ->orderBy('transactions.id', 'DESC');

        if (!is_null($typeUser) && $typeUser == 'user') {
            $countTransactions = $countTransactions->whereNotNull('data->provider_transaction');
        }
        if (!is_null($typeUser) && $typeUser == 'agent'){
            $countTransactions = $countTransactions->whereNull('data->provider_transaction');
        }
            $countTransactions = $countTransactions->get();

        $totalDebit = 0;
        $totalCredit = 0;
        foreach ($countTransactions as $item => $value) {

            if ($value->transaction_type_id == TransactionTypes::$debit) {
                $totalDebit = $totalDebit + $value->amount;
                if($value->user_id === Auth::user()->id){
                    $totalDebit = $totalDebit - $value->amount;
                    $totalCredit = $totalCredit + $value->amount;
                }

                if($value->data->from != Auth::user()->username){
                    $totalDebit = $totalDebit + $value->amount;
                    $totalCredit = $totalCredit - $value->amount;
                }
            }
            if ($value->transaction_type_id == TransactionTypes::$credit) {
                $totalCredit = $totalCredit + $value->amount;
                if($value->user_id === Auth::user()->id){
                    $totalCredit = $totalCredit - $value->amount;
                    $totalDebit = $totalDebit + $value->amount;

                }
                if($value->data->from != Auth::user()->username){
                    $totalCredit = $totalCredit + $value->amount;
                    $totalDebit = $totalDebit - $value->amount;
                }
            }

        }

        return [$totalCredit, $totalDebit];
    }

    /**
     * Get financial cash flow data by providers grouped by users
     *
     * @param int $whitelabel Whitelabel Id
     * @param string $currency Currency Iso
     * @param string $startDate Start date to filter
     * @param string $endDate End date to filter
     * @return array
     */
    public function getCashFlowTransactions($username, $agents, $whitelabel, $currency, $startDate, $endDate)
    {
        $deposits = Transaction::select('users.id', 'users.username', \DB::raw('sum(amount) AS total'))
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->where('transactions.provider_id', Providers::$agents)
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->where('users.whitelabel_id', $whitelabel)
            ->where('transactions.currency_iso', $currency)
            ->where('transaction_type_id', TransactionTypes::$credit)
            ->where('transaction_status_id', TransactionStatus::$approved)
            ->whereIn('transactions.user_id', $agents)
            ->where('transactions.data->from', $username)
            ->groupBy('users.id', 'users.username')
            ->get();

        $withdrawals = Transaction::select('users.id', 'users.username', \DB::raw('sum(amount) AS total'))
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->where('transactions.provider_id', Providers::$agents)
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->where('users.whitelabel_id', $whitelabel)
            ->where('transactions.currency_iso', $currency)
            ->where('transaction_type_id', TransactionTypes::$debit)
            ->where('transaction_status_id', TransactionStatus::$approved)
            ->whereIn('transactions.user_id', $agents)
            ->where('transactions.data->to', $username)
            ->groupBy('users.id', 'users.username')
            ->get();

        return [
            'deposits' => $deposits,
            'withdrawals' => $withdrawals
        ];
    }

    /**
     * Get financial cash flow data by providers grouped by users new
     *
     * @param int $whitelabel Whitelabel Id
     * @param string $currency Currency Iso
     * @param string $startDate Start date to filter
     * @param string $endDate End date to filter
     * @return array
     */
    public function getCashFlowTransactionsNew($username, $agents, $whitelabel, $currency, $startDate, $endDate)
    {

        $providersArray = [Providers::$agents,Providers::$agents_users,Providers::$dotworkers,Providers::$manual_adjustments];
        $result = DB::SELECT("
                             SELECT u.id,
                               u.username,
                               SUM(CASE WHEN t.transaction_type_id = 1 THEN t.amount ELSE 0 END) AS debit,
                               SUM(CASE WHEN t.transaction_type_id = 2 THEN t.amount ELSE 0 END) AS credit
                                FROM site.transactions as t
                                INNER JOIN site.users as u ON t.user_id = u.id
                                WHERE t.provider_id IN (" . implode(',', $providersArray) . ")
                                AND t.created_at BETWEEN ? AND ?
                                AND u.whitelabel_id = ?
                                AND t.currency_iso = ?
                                AND t.transaction_status_id = ?
                                AND t.user_id IN (" . implode(',', $agents) . ")

                                AND ((t.data->>'from' = ? AND t.transaction_type_id = 1) OR (t.data->>'to' = ? AND t.transaction_type_id = 2))
                                GROUP BY u.id, u.username", [$startDate, $endDate, $whitelabel, $currency, TransactionStatus::$approved, $username, $username]);

        $financialDataExample = [];
        foreach ($result as $item => $value) {
            $debit = [
                'id' => $value->id,
                'username' => $value->username,
                'total' => $value->debit,
            ];
            $credit = [
                'id' => $value->id,
                'username' => $value->username,
                'total' => $value->credit,
            ];
            $financialDataExample['deposits'][] = json_decode(json_encode($debit));
            $financialDataExample['withdrawals'][] = json_decode(json_encode($credit));

        }

        return $financialDataExample;
    }

    /**
     * Get deposists withdrawals provider data
     *
     * @param int $whitelabel Whitelabel Id
     * @param string $currency Currency Iso
     * @param string $startDate Start date to filter
     * @param string $endDate End date to filter
     * @param int $transactionType Transaction type ID
     * @return mixed
     */
    public function getDeposistsWithdrawalsProvider($currency, $startDate, $endDate, $transactionType, $whitelabel)
    {
        $data = Transaction::select('transactions.*', 'users.username', 'users.id as user')
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->where('users.whitelabel_id', $whitelabel)
            ->where('transactions.currency_iso', $currency)
            ->where('transactions.provider_id', Providers::$agents_users)
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->where('transactions.transaction_type_id', $transactionType)
            ->where('transaction_status_id', TransactionStatus::$approved)
            ->get();
        return $data;
    }

    /**
     * Get deposit withdrawal by user
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $currency Currency Iso
     * @param array $providerTypes Provider types
     * @return array
     */
    public function getDepositWithdrawalByUser($whitelabel, $currency, $level, $providerTypes)
    {
        $deposits = Transaction::select('users.id', 'users.username', 'users.last_login', \DB::raw('sum(transactions.amount) AS amount'))
            ->join('providers', 'transactions.provider_id', '=', 'providers.id')
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->join('profiles', 'users.id', '=', 'profiles.user_id')
            ->where('transactions.whitelabel_id', $whitelabel)
            ->where('transactions.currency_iso', $currency)
            ->where('transaction_type_id', TransactionTypes::$credit)
            ->where('transaction_status_id', TransactionStatus::$approved)
            ->whereIn('providers.provider_type_id', $providerTypes);

        if (!is_null($level)) {
            $deposits->where('profiles.level', $level);
        }
        $totalDeposits = $deposits->groupBy('users.id', 'users.username')->get();

        $withdrawals = Transaction::select('users.id', 'users.username', \DB::raw('sum(transactions.amount) AS amount'))
            ->join('providers', 'transactions.provider_id', '=', 'providers.id')
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->join('profiles', 'users.id', '=', 'profiles.user_id')
            ->where('transactions.whitelabel_id', $whitelabel)
            ->where('transactions.currency_iso', $currency)
            ->where('transaction_type_id', TransactionTypes::$debit)
            ->where('transaction_status_id', TransactionStatus::$approved)
            ->whereIn('providers.provider_type_id', $providerTypes);

        if (!is_null($level)) {
            $withdrawals->where('profiles.level', $level);
        }
        $totalWithdrawals = $withdrawals->groupBy('users.id', 'users.username')->get();

        return [
            'deposits' => $totalDeposits,
            'withdrawals' => $totalWithdrawals
        ];
    }

    /**
     * Get financial data excluding agents to agents transactions grouped by dates
     *
     * @param int $whitelabel Whitelabel Id
     * @param string $currency Currency Iso
     * @param array $providerTypes Provider types
     * @param string $startDate Start date to filter
     * @param string $endDate End date to filter
     * @return array
     */
    public function getFinancialData($whitelabel, $currency, $providerTypes, $startDate, $endDate, $timezone, $paymentMethod)
    {
        $deposits = Transaction::select(\DB::raw("(transactions.created_at::TIMESTAMP WITH TIME ZONE AT TIME ZONE '$timezone')::DATE AS date"), \DB::raw('sum(amount) AS total'))
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->join('providers', 'transactions.provider_id', '=', 'providers.id')
            ->join('transaction_details', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->whereIn('providers.provider_type_id', $providerTypes)
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->where('users.whitelabel_id', $whitelabel)
            ->where('transactions.currency_iso', $currency)
            ->where('transactions.transaction_type_id', TransactionTypes::$credit)
            ->where('transactions.transaction_status_id', TransactionStatus::$approved)
            ->where('transaction_details.transaction_status_id', TransactionStatus::$approved)
            ->groupBy(\DB::raw("(transactions.created_at::TIMESTAMP WITH TIME ZONE AT TIME ZONE '$timezone')::DATE"))
            ->get();

        $withdrawals = Transaction::select(\DB::raw("(transactions.created_at::TIMESTAMP WITH TIME ZONE AT TIME ZONE '$timezone')::DATE AS date"), \DB::raw('sum(amount) AS total'))
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->join('providers', 'transactions.provider_id', '=', 'providers.id')
            ->join('transaction_details', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->whereIn('providers.provider_type_id', $providerTypes)
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->where('users.whitelabel_id', $whitelabel)
            ->where('transactions.currency_iso', $currency)
            ->where('transactions.transaction_type_id', TransactionTypes::$debit)
            ->where('transactions.transaction_status_id', TransactionStatus::$approved)
            ->where('transaction_details.transaction_status_id', TransactionStatus::$approved)
            ->groupBy(\DB::raw("(transactions.created_at::TIMESTAMP WITH TIME ZONE AT TIME ZONE '$timezone')::DATE"))
            ->get();

        return [
            'deposits' => $deposits,
            'withdrawals' => $withdrawals
        ];
    }

    /**
     * Get financial data excluding agents to agents transactions grouped by dates
     *
     * @param int $whitelabel Whitelabel Id
     * @param string $currency Currency Iso
     * @param array $providerTypes Provider types
     * @param string $startDate Start date to filter
     * @param string $endDate End date to filter
     * @param int $paymentMethod payment method id
     * @return array
     */
    public function getFinancialDataByPaymentMethod($whitelabel, $currency, $providerTypes, $startDate, $endDate, $timezone, $paymentMethod)
    {
        $deposits = Transaction::select(\DB::raw("(transactions.created_at::TIMESTAMP WITH TIME ZONE AT TIME ZONE '$timezone')::DATE AS date"), \DB::raw('sum(amount) AS total'))
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->join('providers', 'transactions.provider_id', '=', 'providers.id')
            ->join('transaction_details', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->whereIn('providers.provider_type_id', $providerTypes)
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->where('users.whitelabel_id', $whitelabel)
            ->where('transactions.currency_iso', $currency)
            ->where('transactions.provider_id', $paymentMethod)
            ->where('transactions.transaction_type_id', TransactionTypes::$credit)
            ->where('transactions.transaction_status_id', TransactionStatus::$approved)
            ->where('transaction_details.transaction_status_id', TransactionStatus::$approved)
            ->groupBy(\DB::raw("(transactions.created_at::TIMESTAMP WITH TIME ZONE AT TIME ZONE '$timezone')::DATE"))->get();

        $withdrawals = Transaction::select(\DB::raw("(transactions.created_at::TIMESTAMP WITH TIME ZONE AT TIME ZONE '$timezone')::DATE AS date"), \DB::raw('sum(amount) AS total'))
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->join('providers', 'transactions.provider_id', '=', 'providers.id')
            ->join('transaction_details', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->whereIn('providers.provider_type_id', $providerTypes)
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->where('users.whitelabel_id', $whitelabel)
            ->where('transactions.currency_iso', $currency)
            ->where('transactions.provider_id', $paymentMethod)
            ->where('transactions.transaction_type_id', TransactionTypes::$debit)
            ->where('transactions.transaction_status_id', TransactionStatus::$approved)
            ->where('transaction_details.transaction_status_id', TransactionStatus::$approved)
            ->groupBy(\DB::raw("(transactions.created_at::TIMESTAMP WITH TIME ZONE AT TIME ZONE '$timezone')::DATE"))->get();

        return [
            'deposits' => $deposits,
            'withdrawals' => $withdrawals
        ];
    }

    /**
     * Get financial totals by currency
     *
     * @param int $whitelabel Whitelabel Id
     * @param string $currency Currency Iso
     * @param string $startDate Start date to filter
     * @param string $endDate End date to filter
     * @return array
     */
    public function getFinancialTotalsByCurrency($currency, $startDate, $endDate, $whitelabel)
    {
        $totals = Transaction::select('amount', 'transaction_type_id', 'provider_id')
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->where('transaction_status_id', TransactionStatus::$approved)
            ->where('users.whitelabel_id', $whitelabel);

        if (!empty($currency)) {
            $totals->where('transactions.currency_iso', $currency);
        }

        $data = $totals->get();
        return $data;
    }

    /**
     * Get first agent transaction
     *
     * @param array $agents Agents data
     * @param int $whitelabel Whitelabel Id
     * @param string $currency Currency Iso
     * @param string $startDate Start date to filter
     * @param string $endDate End date to filter
     * @return mixed
     */
    public function getFirstAgentTransaction($agents, $whitelabel, $currency, $startDate, $endDate)
    {
        $transactions = Transaction::select('transactions.*')
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->where('transactions.provider_id', Providers::$agents)
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->where('users.whitelabel_id', $whitelabel)
            ->where('transactions.currency_iso', $currency)
            ->where('transaction_status_id', TransactionStatus::$approved)
            ->where('transactions.user_id', $agents)
            ->orderBy('transactions.id', 'ASC')
            ->first();
        return $transactions;
    }

    /**
     * Get last agent transaction
     *
     * @param array $agents Agents data
     * @param int $whitelabel Whitelabel Id
     * @param string $currency Currency Iso
     * @param string $startDate Start date to filter
     * @param string $endDate End date to filter
     * @return mixed
     */
    public function getLastAgentTransaction($agents, $whitelabel, $currency, $startDate, $endDate)
    {
        $transactions = Transaction::select('transactions.*')
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->where('transactions.provider_id', Providers::$agents)
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->where('users.whitelabel_id', $whitelabel)
            ->where('transactions.currency_iso', $currency)
            ->where('transaction_status_id', TransactionStatus::$approved)
            ->where('transactions.user_id', $agents)
            ->orderBy('transactions.id', 'DESC')
            ->first();
        return $transactions;
    }

    /**
     * Get manual adjustments by transaction type and providers
     *
     * @param int $whitelabel Whitelabel Id
     * @param string $currency Currency Iso
     * @param int $transactionType Transaction type ID
     * @param array $providers Providers
     * @param string $startDate Start date to filter
     * @param string $endDate End date to filter
     * @param int $status Status ID
     * @return mixed
     */
    public function getManualAdjustmentsByTransactionTypeAndProviders($whitelabel, $currency, $transactionType, $providers, $startDate, $endDate, $status)
    {
        $transactions = Transaction::select('transactions.*', 'users.username', 'users.id as user', 'transaction_details.data as details', 'whitelabels.description as whitelabel_description')
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->join('transaction_details', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->join('whitelabels', 'transactions.whitelabel_id', '=', 'whitelabels.id')
            ->where('transaction_details.transaction_status_id', $status)
            ->whereIn('transactions.provider_id', $providers)
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->where('transactions.transaction_status_id', $status);

        if (!empty($transactionType)) {
            $transactions->where('transactions.transaction_type_id', $transactionType);
        }

        if (!empty($currency)) {
            $transactions->where('transactions.currency_iso', $currency);
        }

        if (!empty($whitelabel)) {
            $transactions->where('users.whitelabel_id', $whitelabel);
        }
        $data = $transactions->orderBy('transactions.created_at', 'DESC')->get();
        return $data;
    }

    /**
     * Get transactions list by user and provider
     *
     * @param int $user User ID
     * @param int $whitelabel Whitelabel Id
     * @param string $currency Currency Iso
     * @param array $providers Providers IDS
     * @param int $limit Transactions limit
     * @param int $offset Transactions offset
     * @param string $startDate Start date to filter
     * @param string $endDate End date to filter
     * @return mixed
     */
    public function getManualTransactionsFromAgents($users, $startDate, $endDate, $providers, $currency, $whitelabel, $limit = 2000, $offset = 0)
    {
        $transactions = Transaction::select('transactions.id', 'transactions.amount', 'transactions.transaction_type_id',
            'transactions.created_at', 'transactions.provider_id', 'transactions.data', 'transactions.transaction_status_id', 'transactions.whitelabel_id')
            ->where('transactions.currency_iso', $currency)
            ->where('transactions.whitelabel_id', $whitelabel)
            ->whereIn('transactions.provider_id', $providers)
            ->whereIn('transactions.user_id', $users)
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->orderBy('transactions.id', 'DESC')
            ->limit($limit)
            ->offset($offset)
            ->get();
        return $transactions;
    }

    /**
     * Get sequence next value
     *
     * @return mixed
     */
    public function getNextValue()
    {
        $nextValue = \DB::select("select nextval('transactions_id_seq')");
        return $nextValue[0]->nextval;
    }

    /**
     * Get monthly sales data
     *
     * @param int $whitelabel Whitelabel Id
     * @param string $currency Currency Iso
     * @param string $startDate Start date to filter
     * @param string $endDate End date to filter
     * @param string $timezone Time zone
     * @return mixed
     */
    public function getSalesData($currency, $startDate, $endDate, $whitelabel, $timezone)
    {
        $credit = TransactionTypes::$credit;
        $debit = TransactionTypes::$debit;
        $rejected = TransactionStatus::$rejected;
        $approved = TransactionStatus::$approved;
        $payments = ProviderTypes::$payment;
        $dotworkers = Providers::$dotworkers;
        $bonus = Providers::$bonus;

        $sales = Transaction::selectRaw("(created_at::TIMESTAMP WITH TIME ZONE AT TIME ZONE '$timezone')::DATE AS date, currency_iso,
            CASE WHEN (transaction_type_id = $credit AND transaction_status_id = $rejected AND provider_type_id = $payments) THEN
              sum(amount)
              END AS credit_rejected,
            CASE WHEN (transaction_type_id = $credit AND transaction_status_id = $approved AND provider_type_id = $payments) THEN
              sum(amount)
              END AS credit_approved,
            CASE WHEN (transaction_type_id = $debit AND transaction_status_id = $rejected AND provider_type_id = $payments) THEN
              sum(amount)
              END AS debit_rejected,
            CASE WHEN (transaction_type_id = $debit AND transaction_status_id = $approved AND provider_type_id = $payments) THEN
              sum(amount)
              END AS debit_approved,
            CASE WHEN (transaction_type_id = $credit and transaction_status_id = $approved and provider_id = $dotworkers) THEN
              sum(amount)
              END AS credit_manual,
            CASE WHEN (transaction_type_id = $debit and transaction_status_id = $approved and provider_id = $dotworkers) THEN
              sum(amount)
              END AS debit_manual,
            CASE WHEN (provider_id = $bonus) THEN
              sum(amount)
              END AS bonus
            ")
            ->join('providers', 'transactions.provider_id', '=', 'providers.id')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('whitelabel_id', $whitelabel);

        if (!is_null($currency)) {
            $sales->where('transactions.currency_iso', $currency);
        }

        $data = $sales->groupBy(\DB::raw("(created_at::TIMESTAMP WITH TIME ZONE AT TIME ZONE '$timezone')::DATE"), 'transaction_type_id', 'transaction_status_id', 'provider_id', 'provider_type_id', 'currency_iso')
            ->get();
        return $data;
    }

    /**
     * Get segmentation
     *
     * @param int $user User ID
     * @param string $currency Currency Iso
     * @param array $providerTypes Provider types
     * @return array
     */
    public function getSegmentation($whitelabel, $language, $currency, $country, $initialBalance, $finalBalance)
    {
        $transactions = Transaction::select('transactions.currency_iso', 'profiles.first_name', 'profiles.last_name', 'profiles.phone', 'countries.name as country', 'users.id', 'users.username', 'users.email', 'users.uuid')
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->join('profiles', 'users.id ', '=', 'profiles.user_id')
            ->join('countries', 'profiles.country_iso ', '=', 'countries.iso')
            //  ->whereBetween('transactions.created_at', [$startDateDeposit, $endDateDeposit])
            //  ->where('profiles.country_iso',$country)
            //  ->where('users.status', $status)
            ->where('users.whitelabel_id', $whitelabel)
            ->conditions($currency)
            ->where('transactions.transaction_type_id', TransactionTypes::$debit)
            ->where('transactions.transaction_status_id', TransactionStatus::$approved)
            ->orderBy('transactions.id', 'DESC')
            ->get();
        return $transactions;
    }

    /**
     * Get deposits totals by user
     *
     * @param int $user User ID
     * @param string $currency Currency Iso
     * @param array $providerTypes Provider types
     * @return array
     */
    public function getTotalsByProviderTypes($user, $currency, $providerTypes)
    {
        $deposits = Transaction::join('providers', 'transactions.provider_id', '=', 'providers.id')
            ->where('transactions.user_id', $user)
            ->where('transactions.currency_iso', $currency)
            ->where('transaction_type_id', TransactionTypes::$credit)
            ->where('transaction_status_id', TransactionStatus::$approved)
            ->whereIn('providers.provider_type_id', $providerTypes)
            ->sum('amount');

        $withdrawals = Transaction::join('providers', 'transactions.provider_id', '=', 'providers.id')
            ->where('transactions.user_id', $user)
            ->where('transactions.currency_iso', $currency)
            ->where('transaction_type_id', TransactionTypes::$debit)
            ->where('transaction_status_id', TransactionStatus::$approved)
            ->whereIn('providers.provider_type_id', $providerTypes)
            ->sum('amount');

        return [
            'deposits' => $deposits,
            'withdrawals' => $withdrawals
        ];
    }

    /**
     * Get Transactions Timeline All
     *
     * @param array $providers Provider Ids
     * @param int $whitelabel Whitelabel Id
     * @param string $currency Currency Iso
     * @param $limit
     * @param $offset
     * @param string $startDate Start date to filter
     * @param string $endDate End date to filter
     * @return mixed
     */
    public function getTransactions($providers, $currency, $whitelabel, $startDate, $endDate, $limit = 10, $offset = 0)
    {
        $transactions = Transaction::select('transactions.id', 'transactions.amount', 'transactions.transaction_type_id',
            'transactions.created_at', 'transactions.provider_id', 'transactions.data', 'transactions.transaction_status_id')
            ->where('transactions.currency_iso', $currency)
            ->where('transactions.whitelabel_id', $whitelabel)
            ->whereIn('transactions.provider_id', $providers)
            ->orderBy('transactions.id', 'DESC')
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->limit($limit)
            ->offset($offset)
            ->get();

        return $transactions;
    }

    /**
     * Get Transactions By User
     *
     * @param array $providers Provider Ids
     * @param int $whitelabel Whitelabel Id
     * @param string $currency Currency Iso
     * @param $limit
     * @param $offset
     * @param string $startDate Start date to filter
     * @param string $endDate End date to filter
     * @return mixed
     */
    public function getTransactionsByUser($providers, $currency, $whitelabel, $user, $startDate, $endDate, $limit = 10, $offset = 0)
    {
        $transactions = Transaction::select('transactions.id', 'transactions.amount', 'transactions.transaction_type_id',
            'transactions.created_at', 'transactions.provider_id', 'transactions.data', 'transactions.transaction_status_id')
            ->where('transactions.user_id', $user)
            ->where('transactions.currency_iso', $currency)
            ->where('transactions.whitelabel_id', $whitelabel)
            ->whereIn('transactions.provider_id', $providers)
            ->orderBy('transactions.id', 'DESC')
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->limit($limit)
            ->offset($offset)
            ->get();

        return $transactions;
    }

    /**
     * Get transactions history
     *
     * @param int $user User Id
     * @param string $currency Currency Iso
     * @param int $transactionType Transaction type
     * @param array $providerTypes Provider types
     * @return mixed
     */
    public function getTransactionsHistory($user, $transactionType, $currency, $providerTypes)
    {
        return Transaction::join('providers', 'transactions.provider_id', '=', 'providers.id')
            ->where('transactions.user_id', $user)
            ->where('transactions.currency_iso', $currency)
            ->whereIn('providers.provider_type_id', $providerTypes)
            ->where('transaction_type_id', $transactionType)
            ->where('transaction_status_id', TransactionStatus::$approved)
            ->sum('amount');
    }

    /**
     * Get Sql Transactions Timeline Page
     *
     * @param int $whitelabel Whitelabel Id
     * @param string $currency Currency Iso
     * @param string $providers Provider Ids
     * @param string $user User Ids
     * @param int $limit Transactions limit
     * @param int $offset Transactions offset
     * @param string $startDate Start date to filter
     * @param string $endDate End date to filter
     * @return mixed
     */
    public function getTransactionsTimelinePage($whitelabel, $currency, $startDate, $endDate, $providers, $user, $limit = 10, $offset = 0)
    {
        return DB::select('SELECT * FROM site.get_transactions_timeline_page(?,?,?,?,?,?,?,?)', [$whitelabel, $currency, $startDate, $endDate, $providers, $user, $limit, $offset]);
    }

    /**
     * Get unique depositors
     *
     * @param int $whitelabel Whitelabel Id
     * @param string $currency Currency Iso
     * @param string $startDate Start date to filter
     * @param string $endDate End date to filter
     * @return mixed
     */
    public function getUniqueDepositors($whitelabel, $currency, $startDate, $endDate)
    {
        $depositors = Transaction::select('user_id')
            ->join('providers', 'transactions.provider_id', '=', 'providers.id')
            ->where('whitelabel_id', $whitelabel)
            ->where('transaction_status_id', TransactionStatus::$approved)
            ->where('transaction_type_id', TransactionTypes::$credit)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('providers.provider_type_id', ProviderTypes::$payment)
            ->groupBy('user_id');

        if (!is_null($currency)) {
            $depositors->where('currency_iso', $currency);
        }
        $data = $depositors->get();
        return $data;
    }

    /**
     * Get unique depositors by user ID
     *
     * @param int $whitelabel Whitelabel Id
     * @param string $currency Currency Iso
     * @param int $userId User ID
     * @return mixed
     */
    public function getUniqueDepositorsByUserId($userId, $currency, $whitelabel)
    {
        $depositors = Transaction::select('transactions.user_id')
            ->join('providers', 'transactions.provider_id', '=', 'providers.id')
            ->where('transactions.user_id', $userId)
            ->where('whitelabel_id', $whitelabel)
            ->where('transaction_status_id', TransactionStatus::$approved)
            ->where('transaction_type_id', TransactionTypes::$credit)
            ->where('providers.provider_type_id', ProviderTypes::$payment);

        if (!is_null($currency)) {
            $depositors->where('transactions.currency_iso', $currency);
        }

        $data = $depositors->get();
        return $data;
    }

    /**
     * Get whitelabel sales
     *
     * @param string $currency Currency Iso
     * @param string $startDate Start date to filter
     * @param string $endDate End date to filter
     * @return mixed
     */
    public function getWhitelabelsSalesData($currency, $startDate, $endDate)
    {
        $credit = TransactionTypes::$credit;
        $debit = TransactionTypes::$debit;
        $rejected = TransactionStatus::$rejected;
        $approved = TransactionStatus::$approved;
        $payments = ProviderTypes::$payment;
        $dotworkers = Providers::$dotworkers;
        $bonus = Providers::$bonus;

        $sales = Transaction::selectRaw("whitelabels.description, transactions.whitelabel_id,
            CASE WHEN (transaction_type_id = $credit AND transaction_status_id = $rejected AND provider_type_id = $payments) THEN
              sum(amount)
              END AS credit_rejected,
            CASE WHEN (transaction_type_id = $credit AND transaction_status_id = $approved AND provider_type_id = $payments) THEN
              sum(amount)
              END AS credit_approved,
            CASE WHEN (transaction_type_id = $debit AND transaction_status_id = $rejected AND provider_type_id = $payments) THEN
              sum(amount)
              END AS debit_rejected,
            CASE WHEN (transaction_type_id = $debit AND transaction_status_id = $approved AND provider_type_id = $payments) THEN
              sum(amount)
              END AS debit_approved,
            CASE WHEN (transaction_type_id = $credit and transaction_status_id = $approved and provider_id = $dotworkers) THEN
              sum(amount)
              END AS credit_manual,
            CASE WHEN (transaction_type_id = $debit and transaction_status_id = $approved and provider_id = $dotworkers) THEN
              sum(amount)
              END AS debit_manual,
            CASE WHEN (provider_id = $bonus) THEN
              sum(amount)
              END AS bonus
            ")
            ->join('providers', 'transactions.provider_id', '=', 'providers.id')
            ->join('whitelabels', 'transactions.whitelabel_id', '=', 'whitelabels.id')
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->where('currency_iso', $currency)
            ->groupBy('transaction_type_id', 'transaction_status_id', 'provider_id', 'provider_type_id', 'whitelabel_id', 'whitelabels.description')
            ->get();
        return $sales;
    }

    /**
     * Store transactions
     *
     * @param array $data Transaction data
     * @param int $status Status ID
     * @param array $detailsData Details data
     * @return mixed
     */
    public function store($data, $status, $detailsData)
    {
        $transaction = Transaction::create($data);
        $transaction->details()->attach($status, $detailsData);
        return $transaction;
    }

    /**
     * Store transactions details
     *
     * @param int $id Transaction ID
     * @param int $status Transaction status
     * @param array $data Detail additional data
     * @return mixed
     */
    public function storeTransactionsDetails($id, $status, $data)
    {
        $transaction = Transaction::find($id);
        $transaction->transaction_status_id = $status;
        $transaction->save();
        $transaction->details()->attach($status, $data);
        return $transaction;
    }

    /**
     * Get ticket transactions user
     * @param int $whitelabel Whitelabel Id
     */
    public function ticketTransactionsUser($id, $whitelabel)
    {
        $ticket = Transaction::select('transactions.id', 'transactions.currency_iso', 'transactions.amount', 'transactions.data', 'transactions.transaction_type_id', 'transactions.created_at', 'users.username', 'providers.name')
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->join('providers', 'transactions.provider_id', '=', 'providers.id')
            ->where('transactions.id', $id)
            ->where('transactions.whitelabel_id', $whitelabel)
            ->first();

        return $ticket;
    }

    /**
     * Get total by provider types
     *
     * @param int $transactionType Transaction type
     * @param int $whitelabel Whitelabel Id
     * @param string $currency Currency Iso
     * @param array $providerTypes Provider types
     * @param string $startDate Start date to filter
     * @param string $endDate End date to filter
     * @return mixed
     */
    public function totalByProviderTypes($whitelabel, $transactionType, $currency, $providerTypes, $startDate, $endDate)
    {
        return Transaction::on('replica')
            ->join('providers', 'transactions.provider_id', '=', 'providers.id')
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->where('transactions.whitelabel_id', $whitelabel)
            ->where('transactions.currency_iso', $currency)
            ->whereIn('providers.provider_type_id', $providerTypes)
            ->where('transaction_type_id', $transactionType)
            ->where('transaction_status_id', TransactionStatus::$approved)
            ->sum('amount');
    }

    /**
     * Update data transaction
     *
     * @param int $id Transaction id to modify
     * @param int $newId Add field "transaction_id" in transaction data json
     * @param int $balance Add field "second_balance" in transaction data json
     * @return mixed
     */
    public function updateData($id, $newId, $balance)
    {
        $transaction = Transaction::find($id);
        $dataTmp = Helper::convertToArray($transaction->data);
        $dataTmp['transaction_id'] = $newId;
        $dataTmp['second_balance'] = $balance;
        $transaction->data = $dataTmp;
        $transaction->update();
        return $transaction;
    }

    /**
     * Update transactions
     *
     * @param int $id Transaction ID
     * @param array $data Transaction data
     * @return mixed
     */
    public function update($id, $data)
    {
        $transaction = Transaction::find($id);
        $transaction->fill($data);
        $transaction->save();
        return $transaction;
    }
}

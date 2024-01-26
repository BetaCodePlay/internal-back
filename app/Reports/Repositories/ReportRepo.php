<?php

namespace App\Reports\Repositories;

use App\Core\Repositories\TransactionsRepo;
use Carbon\Carbon;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Configurations\Enums\ProviderTypes;
use Dotworkers\Configurations\Utils;
use Dotworkers\Store\Enums\TransactionTypes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 *
 */
class ReportRepo
{
    /**
     * @param TransactionsRepo $transactionsRepo
     */
    public function __construct(private TransactionsRepo $transactionsRepo) { }

    /**
     * @return array
     */
    public function dashboard()
    : array
    {
        $currency     = session('currency');
        $whitelabelId = Configurations::getWhitelabel();
        $timezone     = session('timezone');

        // TODO: solo deben mostrarse las trasnsacciones propias del usuairo autenticado y la de sus hijos.
        $transactions = $this->getTransactions($currency, $whitelabelId, $timezone);
        $audits       = $this->getAudits($timezone);

        $today         = Carbon::now($timezone);
        $startDate     = Utils::startOfDayUtc($today->format('Y-m-d'), 'Y-m-d', 'Y-m-d H:i:s', $timezone);
        $endDate       = Utils::endOfDayUtc($today->format('Y-m-d'), 'Y-m-d', 'Y-m-d H:i:s', $timezone);
        $providerTypes = [ProviderTypes::$dotworkers, ProviderTypes::$payment, ProviderTypes::$agents];

        $totalDeposited       = $this->transactionsRepo->totalByProviderTypesWithUser(
            $whitelabelId,
            TransactionTypes::$credit,
            $currency,
            $providerTypes,
            $startDate,
            $endDate,
            auth()->id()
        );

        return [
            'audits'       => $audits,
            'balance'      => [
                'totalBalance' => getAuthenticatedUserBalance(),
                'totalDeposited' => number_format($totalDeposited, 2),
            ],
            'transactions' => $transactions,

        ];
    }

    /**
     * @param string $currency
     * @param int $whitelabelId
     * @param string $timezone
     * @return Collection
     */
    public function getTransactions(string $currency, int $whitelabelId, string $timezone)
    : Collection {
        return DB::table('transactions')
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->latest('transactions.created_at')
            ->take(10)
            ->select([
                'users.username',
                'transactions.transaction_type_id as transactionType',
                DB::raw("TO_CHAR(transactions.amount, 'FM999999999.00') as amount"),
                DB::raw(
                    "TO_CHAR(transactions.created_at AT TIME ZONE 'UTC' AT TIME ZONE '$timezone', 'YYYY-MM-DD hh:MI:SS AM') AS date"
                ),
            ])
            ->where([
                'transactions.currency_iso'  => $currency,
                'transactions.whitelabel_id' => $whitelabelId,
            ])
            ->get();
    }

    /**
     * @param string $timezone
     * @return Collection
     */
    public function getAudits(string $timezone)
    : Collection {
        return DB::table('audits')
            ->join('audit_types', 'audits.audit_type_id', '=', 'audit_types.id')
            ->latest('audits.created_at')
            ->take(10)
            ->select([
                'audit_types.name',
                DB::raw("to_char(audits.created_at AT TIME ZONE '$timezone', 'DD Mon HH:MIAM') as formattedDate")
            ])
            ->get();
    }

}

<?php

namespace App\Reports\Repositories;

use App\Agents\Repositories\AgentsRepo;
use App\Audits\Repositories\AuditsRepo;
use App\Core\Repositories\TransactionsRepo;
use Carbon\Carbon;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Configurations\Enums\ProviderTypes;
use Dotworkers\Configurations\Utils;
use Dotworkers\Store\Enums\TransactionTypes;

/**
 *
 */
class ReportRepo
{
    /**
     * @param TransactionsRepo $transactionsRepo
     * @param AuditsRepo $auditsRepo
     * @param AgentsRepo $agentsRepo
     */
    public function __construct(
        private TransactionsRepo $transactionsRepo,
        private AuditsRepo $auditsRepo,
        private AgentsRepo $agentsRepo,
    ) {
    }

    /**
     * @return array
     */
    public function dashboard()
    : array
    {
        $currency               = session('currency');
        $whitelabelId           = Configurations::getWhitelabel();
        $timezone               = session('timezone');
        $authUserId             = auth()->id();
        $authUserAndChildrenIds = $this->agentsRepo->getChildrenIdsWithParentAuth(
            $authUserId,
            $currency,
            $whitelabelId
        );
        $transactions           = $this->transactionsRepo->getRecentTransactions(
            $currency,
            $whitelabelId,
            $timezone,
            $authUserAndChildrenIds
        );
        $audits                 = $this->auditsRepo->getRecentAudits($timezone);
        $today                  = Carbon::now($timezone);
        $startDate              = Utils::startOfDayUtc($today->format('Y-m-d'), 'Y-m-d', 'Y-m-d H:i:s', $timezone);
        $endDate                = Utils::endOfDayUtc($today->format('Y-m-d'), 'Y-m-d', 'Y-m-d H:i:s', $timezone);
        $providerTypes          = [ProviderTypes::$dotworkers, ProviderTypes::$payment, ProviderTypes::$agents];

        $totalDeposited = $this->transactionsRepo->totalByProviderTypesWithUser(
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
                'totalBalance'   => getAuthenticatedUserBalance(),
                'totalDeposited' => number_format($totalDeposited, 2),
            ],
            'transactions' => $transactions,

        ];
    }

}

<?php

namespace App\Reports\Repositories;

use App\Agents\Repositories\AgentsRepo;
use App\Audits\Repositories\AuditsRepo;
use App\Core\Repositories\GamesRepo;
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
     * @param GamesRepo $gamesRepo
     */
    public function __construct(
        private TransactionsRepo $transactionsRepo,
        private AuditsRepo $auditsRepo,
        private AgentsRepo $agentsRepo,
        private GamesRepo $gamesRepo,
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
        $lastMonth              = Carbon::now()->subMonth()->setTimezone($timezone);

        $providerTypes = [ProviderTypes::$dotworkers, ProviderTypes::$payment, ProviderTypes::$agents];

        $totalDeposited = $this->transactionsRepo->totalByProviderTypesWithUser(
            $whitelabelId,
            TransactionTypes::$credit,
            $currency,
            $providerTypes,
            $startDate,
            $endDate,
            $authUserId
        );

        $totalPrizeWinningAmount = $this->transactionsRepo->sumByField([
            'userId'       => $authUserId,
            'whitelabelId' => $whitelabelId,
            'currency'     => $currency,
            'lastMonth'    => $lastMonth,
            'field'        => 'won',
        ]);

        $totalPlayedAmount = $this->transactionsRepo->sumByField([
            'userId'       => $authUserId,
            'whitelabelId' => $whitelabelId,
            'currency'     => $currency,
            'lastMonth'    => $lastMonth,
            'field'        => 'played',
        ]);

        dd($this->gamesRepo->bestMakers($whitelabelId, $currency, $lastMonth));

        return [
            'audits'       => $audits,
            'amounts'      => [
                'totalBalance'            => getAuthenticatedUserBalance(),
                'totalDeposited'          => number_format($totalDeposited, 2),
                'totalPrizeWinningAmount' => number_format($totalPrizeWinningAmount, 2),
                'totalPlayedAmount'       => number_format($totalPlayedAmount, 2),
            ],
            'games'        => $this->gamesRepo->best10($whitelabelId, $currency, $lastMonth),
            'makers'       => $this->gamesRepo->bestMakers($whitelabelId, $currency, $lastMonth),
            'transactions' => $transactions,
        ];
    }

}

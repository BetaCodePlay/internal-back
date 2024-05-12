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
        $providerTypes          = [ProviderTypes::$dotworkers, ProviderTypes::$payment, ProviderTypes::$agents];

        $totalDeposited = $this->transactionsRepo->totalByProviderTypesWithUser(
            $whitelabelId,
            TransactionTypes::$credit,
            $currency,
            $providerTypes,
            $startDate,
            $endDate,
            $authUserId
        );


        dd($this->gamesRepo->best10($whitelabelId, $currency));

        return [
            'audits'        => $audits,
            'balance'       => [
                'totalBalance'   => getAuthenticatedUserBalance(),
                'totalDeposited' => number_format($totalDeposited, 2),
            ],
            'games'         => [
                [
                    'name'     => 'Carnival of Venice',
                    'provider' => 'Ka gaming',
                    'amount'   => number_format(95959568, 2),
                ],
                [
                    'name'     => 'Fruity Mayan',
                    'provider' => 'Habanero',
                    'amount'   => number_format(76060539, 2),
                ],
                [
                    'name'     => 'The Wild Gang',
                    'provider' => 'Pragmatic Play',
                    'amount'   => number_format(91015561, 2),
                ],
                [
                    'name'     => 'Jelly Valley',
                    'provider' => 'Playson',
                    'amount'   => number_format(90687900, 2),
                ],
                [
                    'name'     => 'European Roulette',
                    'provider' => 'Habanero',
                    'amount'   => number_format(90601325, 2),
                ],
                [
                    'name'     => 'Carnival of Venice',
                    'provider' => 'Kagaming',
                    'amount'   => number_format(85798876, 2),
                ],
                [
                    'name'     => 'Castle of Fire',
                    'provider' => 'Pragmatic Play',
                    'amount'   => number_format(75503989, 2),
                ],
                [
                    'name'     => 'Fruit Party 2',
                    'provider' => 'Pragmatic Play',
                    'amount'   => number_format(75201567, 2),
                ],
                [
                    'name'     => 'Panda Panda',
                    'provider' => 'Habanero',
                    'amount'   => number_format(75200991, 2),
                ],
                [
                    'name'     => '81 Joker X',
                    'provider' => 'Tom Horn',
                    'amount'   => number_format(75000680, 2),
                ],
                [
                    'name'     => 'Fire Coins: Hold and Win',
                    'provider' => 'Playson',
                    'amount'   => number_format(74609987, 2),
                ],
            ],
            'manufacturers' => [
                [
                    'name'        => 'Pragmatic Play',
                    'total_prize' => number_format(76060539, 2),
                    'total_bet'   => number_format(76060539, 2),
                ],
                [
                    'name'        => 'Playson',
                    'total_prize' => number_format(89999678, 2),
                    'total_bet'   => number_format(89999678, 2),
                ],
                [
                    'name'        => 'Habanero',
                    'total_prize' => number_format(91015561, 2),
                    'total_bet'   => number_format(91015561, 2),
                ],
                [
                    'name'        => 'Kagaming',
                    'total_prize' => number_format(90687900, 2),
                    'total_bet'   => number_format(90687900, 2),
                ],
                [
                    'name'        => 'Tom Horn',
                    'total_prize' => number_format(90601325, 2),
                    'total_bet'   => number_format(90601325, 2),
                ],
                [
                    'name'        => 'Sportbook',
                    'total_prize' => number_format(85798876, 2),
                    'total_bet'   => number_format(85798876, 2),
                ],
                [
                    'name'        => 'Belatra',
                    'total_prize' => number_format(75503989, 2),
                    'total_bet'   => number_format(75503989, 2),
                ],
                [
                    'name'        => '1x2 Gaming',
                    'total_prize' => number_format(75201567, 2),
                    'total_bet'   => number_format(75201567, 2),
                ],
                [
                    'name'        => 'Evoplay Entertainment',
                    'total_prize' => number_format(75200991, 2),
                    'total_bet'   => number_format(75200991, 2),
                ],
                [
                    'name'        => 'Calera Gaming',
                    'total_prize' => number_format(75000680, 2),
                    'total_bet'   => number_format(75000680, 2),
                ],
                [
                    'name'        => '7mojos',
                    'total_prize' => number_format(74609987, 2),
                    'total_bet'   => number_format(74609987, 2),
                ],
                [
                    'name'        => 'Gamzix',
                    'total_prize' => number_format(74567632, 2),
                    'total_bet'   => number_format(74567632, 2),
                ],
            ],
            'transactions'  => $transactions,
        ];
    }

}

<?php

namespace App\DotSuite\Repositories;

use App\DotSuite\Entities\DotSuiteGame;
use App\DotSuite\Entities\DotSuiteTicket;
use Dotworkers\Configurations\Enums\Providers;
use Dotworkers\Configurations\Enums\TransactionTypes;

class DotSuiteRepo
{
    /**
     * Get games totals by provider tickets
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $startDate Start date
     * @param string $endDate End date
     * @param string $currency Currency ISO
     * @param string $ticketsTable Tickets table name
     * @return mixed
     */
    public function getGamesTotals($whitelabel, $startDate, $endDate, $currency, $provider)
    {
        $debit = DotSuiteGame::select('dotsuite_games.id', 'name', 'mobile', \DB::raw("sum(dotsuite_tickets.amount) AS total"), \DB::raw("count(dotsuite_tickets.id) AS bets"))
            ->join('dotsuite_tickets', "dotsuite_tickets.game_id", '=', 'games.id')
            ->where("dotsuite_tickets.transaction_type_id", TransactionTypes::$debit)
            ->where("dotsuite_tickets.whitelabel_id", $whitelabel)
            ->where("dotsuite_tickets.currency_iso", $currency)
            ->whereBetween("dotsuite_tickets.created_at", [$startDate, $endDate])
            ->groupBy('games.id', 'games.name', 'games.mobile')
            ->get();

        $credit = DotSuiteGame::select('dotsuite_games.id', \DB::raw("sum(dotsuite_tickets.amount) AS total"))
            ->join('dotsuite_tickets', "dotsuite_tickets.game_id", '=', 'games.id')
            ->where("dotsuite_tickets.transaction_type_id", TransactionTypes::$credit)
            ->where("dotsuite_tickets.whitelabel_id", $whitelabel)
            ->where("dotsuite_tickets.currency_iso", $currency)
            ->whereBetween("dotsuite_tickets.created_at", [$startDate, $endDate])
            ->groupBy('games.id')
            ->get();

        if (!empty($provider)) {
            $debit->where("dotsuite_tickets.provider_id", $provider);
        }

        $dataDebit = $debit->groupBy('name', 'mobile', 'dotsuite_tickets.provider_id')->get();

        if (!empty($provider)) {
            $credit->where("dotsuite_tickets.provider_id", $provider);
        }

        $dataCredit = $credit->groupBy('name', 'mobile', 'dotsuite_tickets.provider_id')->get();

        return [
            'debit' => $dataDebit,
            'credit' => $dataCredit
        ];
    }

    /**
     * Get most played games
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $startDate Start date
     * @param string $endDate End date
     * @param string $currency Currency ISO
     * @param string $ticketsTable Tickets table name
     * @return mixed
     */
    public function getMostPlayedGames($whitelabel, $startDate, $endDate, $currency, $ticketsTable)
    {
        $games = DotSuiteGame::select('name', 'mobile', \DB::raw("count(dotsuite_tickets.id) AS bets"), 'dotsuite_tickets.provider_id')
            ->join("dotsuite_tickets", 'dotsuite_games.id', "dotsuite_tickets.dotsuite_game_id")
            ->where("dotsuite_tickets.transaction_type_id", TransactionTypes::$debit)
            ->where("dotsuite_tickets.whitelabel_id", $whitelabel)
            ->where("dotsuite_tickets.currency_iso", $currency)
            ->whereBetween("dotsuite_tickets.created_at", [$startDate, $endDate]);

        if (!empty($provider)) {
            $games->where("dotsuite_tickets.provider_id", $provider);
        }

        $dataGames = $games->groupBy('name', 'mobile', 'dotsuite_tickets.provider_id')->get();

        return $dataGames;
    }

    /**
     * Get users totals
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $startDate Start date
     * @param string $endDate End date
     * @param string $currency Currency ISO
     * @return array
     */
    public function getUsersTotals($whitelabel, $startDate, $endDate, $currency, $provider)
    {
        $debit = DotSuiteTicket::select('users.id', 'users.username', \DB::raw('sum(dotsuite_tickets.amount) AS total'), \DB::raw('count(dotsuite_tickets.id) AS bets'), 'dotsuite_tickets.provider_id', 'dotsuite_tickets.provider')
            ->join('users', 'dotsuite_tickets.user_id', '=', 'users.id')
            ->where('dotsuite_tickets.transaction_type_id', TransactionTypes::$debit)
            ->where('dotsuite_tickets.whitelabel_id', $whitelabel)
            ->where('dotsuite_tickets.currency_iso', $currency)
            ->where('dotsuite_tickets.status', 2)
            ->whereBetween('dotsuite_tickets.created_at', [$startDate, $endDate]);

        $credit = DotSuiteTicket::select('users.id', 'users.username', \DB::raw('sum(dotsuite_tickets.amount) AS total'), 'dotsuite_tickets.provider_id', 'dotsuite_tickets.provider')
            ->join('users', 'dotsuite_tickets.user_id', '=', 'users.id')
            ->where('dotsuite_tickets.transaction_type_id', TransactionTypes::$credit)
            ->where('dotsuite_tickets.whitelabel_id', $whitelabel)
            ->where('dotsuite_tickets.currency_iso', $currency)
            ->where('dotsuite_tickets.status', 2)
           // ->where('dotsuite_tickets.type', 'win')
            ->whereBetween('dotsuite_tickets.created_at', [$startDate, $endDate]);

        if (!empty($provider)) {
            $debit->where('dotsuite_tickets.provider', $provider);
            $credit->where('dotsuite_tickets.provider', $provider);
        }
        $dataDebit = $debit->groupBy('users.id', 'users.username', 'dotsuite_tickets.provider_id', 'dotsuite_tickets.provider')->get();
        $dataCredit = $credit->groupBy("users.id", "users.username", 'dotsuite_tickets.provider_id', 'dotsuite_tickets.provider')->get();

        return [
            'debit' => $dataDebit,
            'credit' => $dataCredit
        ];
    }
}

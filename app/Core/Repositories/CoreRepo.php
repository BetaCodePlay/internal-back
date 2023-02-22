<?php

namespace App\Core\Repositories;

use App\Core\Entities\Game;
use App\Reports\Entities\ClosureUserTotal;
use Dotworkers\Configurations\Enums\Providers;
use Dotworkers\Configurations\Enums\TransactionTypes;
use Illuminate\Support\Facades\DB;

class CoreRepo
{
    /**
     * Get games totals closure
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $startDate Start date to filter
     * @param string $endDate End date to filter
     * @param string $currency Currency ISO
     * @param string $ticketsTable Tickets table
     * @return array
     */
    public function getGamesTotalsClosure($whitelabel, $startDate, $endDate, $currency, $ticketsTable)
    {
        $debit = \DB::table('games')
            ->select('games.id', 'games.name', 'games.mobile', \DB::raw('sum(amount) AS total'), \DB::raw('count(*) AS bets'))
            ->join($ticketsTable, "games.id", '=', "$ticketsTable.game_id")
            ->where('whitelabel_id', $whitelabel)
            ->where('transaction_type_id', TransactionTypes::$debit)
            ->where('currency_iso', $currency)
            ->whereBetween("$ticketsTable.created_at", [$startDate, $endDate])
            ->groupBy('games.id')
            ->get();

        $credit = \DB::table($ticketsTable)
            ->select("$ticketsTable.game_id AS id", \DB::raw('sum(amount) AS total'))
            ->where('whitelabel_id', $whitelabel)
            ->where('transaction_type_id', TransactionTypes::$credit)
            ->where('currency_iso', $currency)
            ->whereBetween("$ticketsTable.created_at", [$startDate, $endDate])
            ->groupBy("$ticketsTable.game_id")
            ->get();

        return [
            'debit' => $debit,
            'credit' => $credit
        ];
    }

    /**
     * Get users total closure
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $startDate Start date
     * @param string $endDate End date
     * @param string $currency Currency ISO
     * @param string $ticketsTable Tickets table name
     * @param bool $games Games integration
     * @param int|null $provider Provider ID
     * @return array
     */
    public function getUsersTotalsClosure($whitelabel, $startDate, $endDate, $currency, $ticketsTable, $games, $provider = null)
    {
        if ($games) {
            if (is_null($provider)) {
                $debit = \DB::connection('replica')
                    ->table($ticketsTable)
                    ->select("$ticketsTable.game_id", \DB::raw('sum(amount) AS total'), \DB::raw('count(*) AS bets'), "$ticketsTable.user_id", 'users.username')
                    ->join('users', "$ticketsTable.user_id", '=', 'users.id')
                    ->where("$ticketsTable.whitelabel_id", $whitelabel)
                    ->where('transaction_type_id', TransactionTypes::$debit)
                    ->where("$ticketsTable.currency_iso", $currency)
                    ->whereBetween("$ticketsTable.created_at", [$startDate, $endDate])
                    ->groupBy("$ticketsTable.game_id", "$ticketsTable.user_id", 'users.username')
                    ->get();

                $credit = \DB::connection('replica')
                    ->table($ticketsTable)
                    ->select("$ticketsTable.game_id", "$ticketsTable.user_id", \DB::raw('sum(amount) AS total'), 'users.username')
                    ->join('users', "$ticketsTable.user_id", '=', 'users.id')
                    ->where("$ticketsTable.whitelabel_id", $whitelabel)
                    ->where('transaction_type_id', TransactionTypes::$credit)
                    ->where("$ticketsTable.currency_iso", $currency)
                    ->whereBetween("$ticketsTable.created_at", [$startDate, $endDate])
                    ->groupBy("$ticketsTable.game_id", "$ticketsTable.user_id", 'users.username')
                    ->get();
            } else {
                $debit = \DB::connection('replica')
                    ->table($ticketsTable)
                    ->select("$ticketsTable.game_id", \DB::raw('sum(amount) AS total'), \DB::raw('count(*) AS bets'), "$ticketsTable.user_id", 'users.username')
                    ->join('users', "$ticketsTable.user_id", '=', 'users.id')
                    ->where("$ticketsTable.whitelabel_id", $whitelabel)
                    ->where('transaction_type_id', TransactionTypes::$debit)
                    ->where("$ticketsTable.currency_iso", $currency)
                    ->where("$ticketsTable.provider_id", $provider)
                    ->whereBetween("$ticketsTable.created_at", [$startDate, $endDate])
                    ->groupBy("$ticketsTable.game_id", "$ticketsTable.user_id", 'users.username')
                    ->get();

                $credit = \DB::connection('replica')
                    ->table($ticketsTable)
                    ->select("$ticketsTable.game_id", "$ticketsTable.user_id", \DB::raw('sum(amount) AS total'), 'users.username')
                    ->join('users', "$ticketsTable.user_id", '=', 'users.id')
                    ->where("$ticketsTable.whitelabel_id", $whitelabel)
                    ->where('transaction_type_id', TransactionTypes::$credit)
                    ->where("$ticketsTable.currency_iso", $currency)
                    ->where("$ticketsTable.provider_id", $provider)
                    ->whereBetween("$ticketsTable.created_at", [$startDate, $endDate])
                    ->groupBy("$ticketsTable.game_id", "$ticketsTable.user_id", 'users.username')
                    ->get();
            }

        } else {
            $debit = \DB::connection('replica')
                ->table($ticketsTable)
                ->select("$ticketsTable.user_id", 'users.username', \DB::raw('sum(amount) AS total'), \DB::raw('count(*) AS bets'))
                ->join('users', "$ticketsTable.user_id", '=', 'users.id')
                ->where("$ticketsTable.whitelabel_id", $whitelabel)
                ->where('transaction_type_id', TransactionTypes::$debit)
                ->where("$ticketsTable.currency_iso", $currency)
                ->whereBetween("$ticketsTable.created_at", [$startDate, $endDate])
                ->groupBy("$ticketsTable.user_id", 'users.username')
                ->get();

            $credit = \DB::connection('replica')
                ->table($ticketsTable)
                ->select("$ticketsTable.user_id", 'users.username', \DB::raw('sum(amount) AS total'))
                ->join('users', "$ticketsTable.user_id", '=', 'users.id')
                ->where("$ticketsTable.whitelabel_id", $whitelabel)
                ->where('transaction_type_id', TransactionTypes::$credit)
                ->where("$ticketsTable.currency_iso", $currency)
                ->whereBetween("$ticketsTable.created_at", [$startDate, $endDate])
                ->groupBy("$ticketsTable.user_id", 'users.username')
                ->get();
        }

        return [
            'debit' => $debit,
            'credit' => $credit
        ];
    }

    /**
     * Get users total closure
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $startDate Start date
     * @param string $endDate End date
     * @param string $currency Currency ISO
     * @param string $ticketsTable Tickets table name
     * @param bool $games Games integration
     * @param int $provider Provider ID
     * @return array
     */
    public function getUsersTotalsClosureDotSuite($whitelabel, $startDate, $endDate, $currency, $ticketsTable, $games, $provider)
    {
        if ($games) {
            if($ticketsTable == 'dotsuite_tickets'){
                $debit = \DB::connection('replica')
                    ->table($ticketsTable)
                    ->select("$ticketsTable.dotsuite_game_id", \DB::raw('sum(amount) AS total'), \DB::raw('count(*) AS bets'), "$ticketsTable.user_id", 'users.username')
                    ->join('users', "$ticketsTable.user_id", '=', 'users.id')
                    ->where("$ticketsTable.whitelabel_id", $whitelabel)
                    ->where('transaction_type_id', TransactionTypes::$debit)
                    ->where("$ticketsTable.currency_iso", $currency)
                    ->where("$ticketsTable.wallet_transaction", '>=', 0)
                    ->where("$ticketsTable.status", '=', 2)
                    ->where("$ticketsTable.provider", $provider)
                    ->whereBetween("$ticketsTable.created_at", [$startDate, $endDate])
                    ->groupBy("$ticketsTable.dotsuite_game_id", "$ticketsTable.user_id", 'users.username')
                    ->get();

                $credit = \DB::connection('replica')
                    ->table($ticketsTable)
                    ->select("$ticketsTable.dotsuite_game_id", "$ticketsTable.user_id", \DB::raw('sum(amount) AS total'), 'users.username')
                    ->join('users', "$ticketsTable.user_id", '=', 'users.id')
                    ->where("$ticketsTable.whitelabel_id", $whitelabel)
                    ->where('transaction_type_id', TransactionTypes::$credit)
                    ->where("$ticketsTable.currency_iso", $currency)
                    ->where("$ticketsTable.wallet_transaction", '>=', 0)
                    ->where("$ticketsTable.status", '=', 2)
                    ->where("$ticketsTable.provider", $provider)
                    ->whereBetween("$ticketsTable.created_at", [$startDate, $endDate])
                    ->groupBy("$ticketsTable.dotsuite_game_id", "$ticketsTable.user_id", 'users.username')
                    ->get();
            } else {
                $debit = \DB::connection('replica')
                    ->table($ticketsTable)
                    ->select("$ticketsTable.dotsuite_game_id", \DB::raw('sum(amount) AS total'), \DB::raw('count(*) AS bets'), "$ticketsTable.user_id", 'users.username')
                    ->join('users', "$ticketsTable.user_id", '=', 'users.id')
                    ->where("$ticketsTable.whitelabel_id", $whitelabel)
                    ->where('transaction_type_id', TransactionTypes::$debit)
                    ->where("$ticketsTable.currency_iso", $currency)
                    ->where("$ticketsTable.wallet_transaction", '>=', 0)
                    ->where("$ticketsTable.provider", $provider)
                    ->whereBetween("$ticketsTable.created_at", [$startDate, $endDate])
                    ->groupBy("$ticketsTable.dotsuite_game_id", "$ticketsTable.user_id", 'users.username')
                    ->get();

                $credit = \DB::connection('replica')
                    ->table($ticketsTable)
                    ->select("$ticketsTable.dotsuite_game_id", "$ticketsTable.user_id", \DB::raw('sum(amount) AS total'), 'users.username')
                    ->join('users', "$ticketsTable.user_id", '=', 'users.id')
                    ->where("$ticketsTable.whitelabel_id", $whitelabel)
                    ->where('transaction_type_id', TransactionTypes::$credit)
                    ->where("$ticketsTable.currency_iso", $currency)
                    ->where("$ticketsTable.wallet_transaction", '>=', 0)
                    ->where("$ticketsTable.provider", $provider)
                    ->whereBetween("$ticketsTable.created_at", [$startDate, $endDate])
                    ->groupBy("$ticketsTable.dotsuite_game_id", "$ticketsTable.user_id", 'users.username')
                    ->get();
            }


        } else {
            if($ticketsTable == 'dotsuite_tickets'){
                $debit = \DB::connection('replica')
                    ->table($ticketsTable)
                    ->select("$ticketsTable.user_id", 'users.username', \DB::raw('sum(amount) AS total'), \DB::raw('count(*) AS bets'))
                    ->join('users', "$ticketsTable.user_id", '=', 'users.id')
                    ->where("$ticketsTable.whitelabel_id", $whitelabel)
                    ->where('transaction_type_id', TransactionTypes::$debit)
                    ->where("$ticketsTable.currency_iso", $currency)
                    ->where("$ticketsTable.wallet_transaction", '>=', 0)
                    ->where("$ticketsTable.status", '=', 2)
                    ->where("$ticketsTable.provider", $provider)
                    ->whereBetween("$ticketsTable.created_at", [$startDate, $endDate])
                    ->groupBy("$ticketsTable.user_id", 'users.username')
                    ->get();

                $credit = \DB::connection('replica')
                    ->table($ticketsTable)
                    ->select("$ticketsTable.user_id", 'users.username', \DB::raw('sum(amount) AS total'))
                    ->join('users', "$ticketsTable.user_id", '=', 'users.id')
                    ->where("$ticketsTable.whitelabel_id", $whitelabel)
                    ->where('transaction_type_id', TransactionTypes::$credit)
                    ->where("$ticketsTable.currency_iso", $currency)
                    ->where("$ticketsTable.wallet_transaction", '>=', 0)
                    ->where("$ticketsTable.status", '=', 2)
                    ->where("$ticketsTable.provider", $provider)
                    ->whereBetween("$ticketsTable.created_at", [$startDate, $endDate])
                    ->groupBy("$ticketsTable.user_id", 'users.username')
                    ->get();
            } else {
                $debit = \DB::connection('replica')
                    ->table($ticketsTable)
                    ->select("$ticketsTable.user_id", 'users.username', \DB::raw('sum(amount) AS total'), \DB::raw('count(*) AS bets'))
                    ->join('users', "$ticketsTable.user_id", '=', 'users.id')
                    ->where("$ticketsTable.whitelabel_id", $whitelabel)
                    ->where('transaction_type_id', TransactionTypes::$debit)
                    ->where("$ticketsTable.currency_iso", $currency)
                    ->where("$ticketsTable.wallet_transaction", '>=', 0)
                    ->where("$ticketsTable.provider", $provider)
                    ->whereBetween("$ticketsTable.created_at", [$startDate, $endDate])
                    ->groupBy("$ticketsTable.user_id", 'users.username')
                    ->get();

                $credit = \DB::connection('replica')
                    ->table($ticketsTable)
                    ->select("$ticketsTable.user_id", 'users.username', \DB::raw('sum(amount) AS total'))
                    ->join('users', "$ticketsTable.user_id", '=', 'users.id')
                    ->where("$ticketsTable.whitelabel_id", $whitelabel)
                    ->where('transaction_type_id', TransactionTypes::$credit)
                    ->where("$ticketsTable.currency_iso", $currency)
                    ->where("$ticketsTable.wallet_transaction", '>=', 0)
                    ->where("$ticketsTable.provider", $provider)
                    ->whereBetween("$ticketsTable.created_at", [$startDate, $endDate])
                    ->groupBy("$ticketsTable.user_id", 'users.username')
                    ->get();
            }

        }

        return [
            'debit' => $debit,
            'credit' => $credit
        ];


    }

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
    public function getGamesTotals($whitelabel, $startDate, $endDate, $currency, $ticketsTable)
    {
        $debit = Game::select('games.id', 'name', 'mobile', \DB::raw("sum($ticketsTable.amount) AS total"), \DB::raw("count($ticketsTable.id) AS bets"))
            ->join($ticketsTable, "$ticketsTable.game_id", '=', 'games.id')
            ->where("$ticketsTable.transaction_type_id", TransactionTypes::$debit)
            ->where("$ticketsTable.whitelabel_id", $whitelabel)
            ->where("$ticketsTable.currency_iso", $currency)
            ->whereBetween("$ticketsTable.created_at", [$startDate, $endDate])
            ->groupBy('games.id', 'games.name', 'games.mobile')
            ->get();

        $credit = Game::select('games.id', \DB::raw("sum($ticketsTable.amount) AS total"))
            ->join($ticketsTable, "$ticketsTable.game_id", '=', 'games.id')
            ->where("$ticketsTable.transaction_type_id", TransactionTypes::$credit)
            ->where("$ticketsTable.whitelabel_id", $whitelabel)
            ->where("$ticketsTable.currency_iso", $currency)
            ->whereBetween("$ticketsTable.created_at", [$startDate, $endDate])
            ->groupBy('games.id')
            ->get();

        return [
            'debit' => $debit,
            'credit' => $credit
        ];
    }

    /**
     * Get latest 30 days products totals data
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $startDate Start date to filter
     * @param string $endDate End date to filter
     * @param string $currency Currency ISO
     * @param string $providerType Provider type ID
     * @return mixed
     */
    public function getLatestProductsTotals($whitelabel, $startDate, $endDate, $currency, $providerType, $provider)
    {
        $totals = ClosureUserTotal::select('provider_id', \DB::raw('count (*) AS users'), \DB::raw('sum(bets) AS bets', 'closures_users_totals.currency_iso'),
            \DB::raw('sum(profit) AS profit'), 'closures_users_totals.currency_iso')
            ->join('providers', 'closures_users_totals.provider_id', '=', 'providers.id')
            ->join('users', 'users.id', '=', 'closures_users_totals.user_id')
            ->join('provider_types', 'provider_types.id', '=', 'providers.provider_type_id')
            ->where('start_date', '>=', $startDate)
            ->where('end_date', '<=', $endDate)
            ->where('closures_users_totals.whitelabel_id', $whitelabel);

        if (!empty($currency)) {
            $totals->where('closures_users_totals.currency_iso', $currency);
        }

        if (!empty($providerType)) {
            $totals->where('providers.provider_type_id', $providerType);
        }

        if (!empty($provider)) {
            $totals->where('closures_users_totals.provider_id', $provider);
        }

        $data = $totals->groupBy('closures_users_totals.provider_id', 'provider_types.name', 'closures_users_totals.currency_iso')->get();
        return $data;
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
        $games = Game::select('name', 'mobile', \DB::raw("count($ticketsTable.id) AS bets"));
        if ($ticketsTable == 'dotsuite_tickets') {
            $games->join($ticketsTable, 'games.id', '=', "$ticketsTable.dotsuite_game_id");
        } else {
            $games->join($ticketsTable, 'games.id', '=', "$ticketsTable.game_id");
        }

        $gamesDate = $games->where("$ticketsTable.transaction_type_id", TransactionTypes::$debit)
            ->where("$ticketsTable.whitelabel_id", $whitelabel)
            ->where("$ticketsTable.currency_iso", $currency)
            ->whereBetween("$ticketsTable.created_at", [$startDate, $endDate])
            ->groupBy('name', 'mobile')
            ->get();
        return $gamesDate;
    }

    /**
     * Get users totals
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $startDate Start date
     * @param string $endDate End date
     * @param string $currency Currency ISO
     * @param string $ticketsTable Tickets table
     * @return array
     */
    public function getUsersTotals($whitelabel, $startDate, $endDate, $currency, $ticketsTable, $provider)
    {
        $provider = (int)$provider;
        switch ($provider) {
            case Providers::$center_horses:
            {
                $debit = \DB::table($ticketsTable)
                    ->select('users.id', 'users.username', \DB::raw("sum($ticketsTable.amount) AS total"), \DB::raw("count($ticketsTable.id) AS bets"))
                    ->join('users', "$ticketsTable.user_id", '=', 'users.id')
                    ->where("$ticketsTable.transaction_type_id", TransactionTypes::$debit)
                    ->where("$ticketsTable.whitelabel_id", $whitelabel)
                    ->where("$ticketsTable.currency_iso", $currency)
                    ->where('bet_type', 'debit')
                    ->whereBetween("$ticketsTable.created_at", [$startDate, $endDate])
                    ->groupBy('users.id', 'users.username')
                    ->get();

                $credit = \DB::table($ticketsTable)
                    ->select('users.id', 'users.username', \DB::raw("sum($ticketsTable.amount) AS total"), \DB::raw("count($ticketsTable.id) AS bets"))
                    ->join('users', "$ticketsTable.user_id", '=', 'users.id')
                    ->where("$ticketsTable.transaction_type_id", TransactionTypes::$credit)
                    ->where("$ticketsTable.whitelabel_id", $whitelabel)
                    ->where("$ticketsTable.currency_iso", $currency)
                    ->where('bet_type', 'credit')
                    ->whereBetween("$ticketsTable.created_at", [$startDate, $endDate])
                    ->groupBy('users.id', 'users.username')
                    ->get();

                $reverse = \DB::table($ticketsTable)->select("$ticketsTable.user_id as id", \DB::raw("sum($ticketsTable.amount) AS total"))
                    ->where("$ticketsTable.transaction_type_id", TransactionTypes::$debit)
                    ->where("$ticketsTable.whitelabel_id", $whitelabel)
                    ->where("$ticketsTable.currency_iso", $currency)
                    ->where('bet_type', 'revert')
                    ->whereBetween("$ticketsTable.created_at", [$startDate, $endDate])
                    ->groupBy("$ticketsTable.user_id")
                    ->get();

                $return = \DB::table($ticketsTable)->select("$ticketsTable.user_id as id", \DB::raw("sum($ticketsTable.amount) AS total"))
                    ->where("$ticketsTable.transaction_type_id", TransactionTypes::$credit)
                    ->where("$ticketsTable.whitelabel_id", $whitelabel)
                    ->where("$ticketsTable.currency_iso", $currency)
                    ->where('bet_type', 'return')
                    ->whereBetween("$ticketsTable.created_at", [$startDate, $endDate])
                    ->groupBy("$ticketsTable.user_id")
                    ->get();

                $cancel = \DB::table($ticketsTable)->select("$ticketsTable.user_id as id", \DB::raw("sum($ticketsTable.amount) AS total"))
                    ->where("$ticketsTable.transaction_type_id", TransactionTypes::$credit)
                    ->where("$ticketsTable.whitelabel_id", $whitelabel)
                    ->where("$ticketsTable.currency_iso", $currency)
                    ->where('bet_type', 'cancel')
                    ->whereBetween("$ticketsTable.created_at", [$startDate, $endDate])
                    ->groupBy("$ticketsTable.user_id")
                    ->get();

                return [
                    'debit' => $debit,
                    'credit' => $credit,
                    'reverse' => $reverse,
                    'return' => $return,
                    'cancel' => $cancel
                ];
                break;
            }
            case Providers::$sportbook:
            {
                $debit = \DB::table($ticketsTable)
                    ->select('users.id', 'users.username', \DB::raw("sum($ticketsTable.amount) AS total"), \DB::raw("count($ticketsTable.id) AS bets"))
                    ->join('users', "$ticketsTable.user_id", '=', 'users.id')
                    ->where("$ticketsTable.transaction_type_id", TransactionTypes::$debit)
                    ->where("$ticketsTable.whitelabel_id", $whitelabel)
                    ->where("$ticketsTable.currency_iso", $currency)
                    ->where('bet_type', 'DEBIT_BET')
                    ->whereBetween("$ticketsTable.created_at", [$startDate, $endDate])
                    ->groupBy('users.id', 'users.username')
                    ->get();

                $credit = \DB::table($ticketsTable)
                    ->select('users.id', 'users.username', \DB::raw("sum($ticketsTable.amount) AS total"), \DB::raw("count($ticketsTable.id) AS bets"))
                    ->join('users', "$ticketsTable.user_id", '=', 'users.id')
                    ->where("$ticketsTable.transaction_type_id", TransactionTypes::$credit)
                    ->where("$ticketsTable.whitelabel_id", $whitelabel)
                    ->where("$ticketsTable.currency_iso", $currency)
                    ->where('bet_type', 'CREDIT_BET')
                    ->whereBetween("$ticketsTable.created_at", [$startDate, $endDate])
                    ->groupBy('users.id', 'users.username')
                    ->get();

                $reverse = \DB::table($ticketsTable)->select("$ticketsTable.user_id as id", \DB::raw("sum($ticketsTable.amount) AS total"))
                    ->where("$ticketsTable.transaction_type_id", TransactionTypes::$debit)
                    ->where("$ticketsTable.whitelabel_id", $whitelabel)
                    ->where("$ticketsTable.currency_iso", $currency)
                    ->where('bet_type', 'REVERSE_EVENT')
                    ->whereBetween("$ticketsTable.created_at", [$startDate, $endDate])
                    ->groupBy("$ticketsTable.user_id")
                    ->get();

                $return = \DB::table($ticketsTable)->select("$ticketsTable.user_id as id", \DB::raw("sum($ticketsTable.amount) AS total"))
                    ->where("$ticketsTable.transaction_type_id", TransactionTypes::$credit)
                    ->where("$ticketsTable.whitelabel_id", $whitelabel)
                    ->where("$ticketsTable.currency_iso", $currency)
                    ->where('bet_type', 'RETURN_BET')
                    ->whereBetween("$ticketsTable.created_at", [$startDate, $endDate])
                    ->groupBy("$ticketsTable.user_id")
                    ->get();

                return [
                    'debit' => $debit,
                    'credit' => $credit,
                    'reverse' => $reverse,
                    'return' => $return
                ];
                break;
            }
            case Providers::$inmejorable:
            {
                $debit = \DB::table($ticketsTable)
                    ->select('users.id', 'users.username', \DB::raw("sum($ticketsTable.amount) AS total"), \DB::raw("count($ticketsTable.id) AS bets"))
                    ->join('users', "$ticketsTable.user_id", '=', 'users.id')
                    ->where("$ticketsTable.transaction_type_id", TransactionTypes::$debit)
                    ->where("$ticketsTable.whitelabel_id", $whitelabel)
                    ->where("$ticketsTable.currency_iso", $currency)
                    ->where('bet_type', 'bet')
                    ->whereBetween("$ticketsTable.created_at", [$startDate, $endDate])
                    ->groupBy('users.id', 'users.username')
                    ->get();

                $credit = \DB::table($ticketsTable)
                    ->select('users.id', 'users.username', \DB::raw("sum($ticketsTable.amount) AS total"))
                    ->join('users', "$ticketsTable.user_id", '=', 'users.id')
                    ->where("$ticketsTable.transaction_type_id", TransactionTypes::$credit)
                    ->where("$ticketsTable.whitelabel_id", $whitelabel)
                    ->where("$ticketsTable.currency_iso", $currency)
                    ->where('bet_type', 'win')
                    ->whereBetween("$ticketsTable.created_at", [$startDate, $endDate])
                    ->groupBy('users.id', 'users.username')
                    ->get();

                $return = \DB::table($ticketsTable)->select("$ticketsTable.user_id as id", \DB::raw("sum($ticketsTable.amount) AS total"))
                    ->where("$ticketsTable.transaction_type_id", TransactionTypes::$credit)
                    ->where("$ticketsTable.whitelabel_id", $whitelabel)
                    ->where("$ticketsTable.currency_iso", $currency)
                    ->where('bet_type', 'refund')
                    ->whereBetween("$ticketsTable.created_at", [$startDate, $endDate])
                    ->groupBy("$ticketsTable.user_id")
                    ->get();

                return [
                    'debit' => $debit,
                    'credit' => $credit,
                    'return' => $return
                ];
                break;
            }
            default:
            {
                $debit = \DB::table($ticketsTable)
                    ->select('users.id', 'users.username', \DB::raw("sum($ticketsTable.amount) AS total"), \DB::raw("count($ticketsTable.id) AS bets"))
                    ->join('users', "$ticketsTable.user_id", '=', 'users.id')
                    ->where("$ticketsTable.transaction_type_id", TransactionTypes::$debit)
                    ->where("$ticketsTable.whitelabel_id", $whitelabel)
                    ->where("$ticketsTable.currency_iso", $currency)
                    ->whereBetween("$ticketsTable.created_at", [$startDate, $endDate])
                    ->groupBy('users.id', 'users.username')
                    ->get();

                $credit = \DB::table($ticketsTable)
                    ->select('users.id', 'users.username', \DB::raw("sum($ticketsTable.amount) AS total"))
                    ->join('users', "$ticketsTable.user_id", '=', 'users.id')
                    ->where("$ticketsTable.transaction_type_id", TransactionTypes::$credit)
                    ->where("$ticketsTable.whitelabel_id", $whitelabel)
                    ->where("$ticketsTable.currency_iso", $currency)
                    ->whereBetween("$ticketsTable.created_at", [$startDate, $endDate])
                    ->groupBy('users.id', 'users.username')
                    ->get();

                return [
                    'debit' => $debit,
                    'credit' => $credit
                ];
            }
        }
    }

    /**
    * Get users totals by users IDs
    *
    * @param int $whitelabel Whitelabel ID
    * @param string $startDate Start date
    * @param string $endDate End date
    * @param string $currency Currency ISO
    * @param string $ticketsTable Tickets table
    * @param array $users Users IDs
    * @return array
    */
    public function getUsersTotalsByIds($whitelabel, $startDate, $endDate, $currency, $ticketsTable, $users)
    {
        $debit = DB::select("SELECT $ticketsTable.user_id AS id, users.username, sum($ticketsTable.amount) AS total, count($ticketsTable.id) AS bets
            FROM $ticketsTable
            JOIN users ON $ticketsTable.user_id = users.id
            WHERE $ticketsTable.transaction_type_id = ?
            AND $ticketsTable.whitelabel_id = ?
            AND $ticketsTable.currency_iso = ?
            AND $ticketsTable.created_at BETWEEN ? AND ?
            AND $ticketsTable.user_id IN (" . implode(',', $users) . ")
            GROUP BY $ticketsTable.user_id, username", [TransactionTypes::$debit, $whitelabel, $currency, $startDate, $endDate]);

        $credit = DB::select("SELECT $ticketsTable.user_id AS id, users.username, sum($ticketsTable.amount) AS total
            FROM $ticketsTable
            JOIN users ON $ticketsTable.user_id = users.id
            WHERE $ticketsTable.transaction_type_id = ?
            AND $ticketsTable.whitelabel_id = ?
            AND $ticketsTable.currency_iso = ?
            AND $ticketsTable.created_at BETWEEN ? AND ?
            AND $ticketsTable.user_id IN (" . implode(',', $users) . ")
            GROUP BY $ticketsTable.user_id, username", [TransactionTypes::$credit, $whitelabel, $currency, $startDate, $endDate]);

        return [
            'debit' => $debit,
            'credit' => $credit
        ];
    }
}

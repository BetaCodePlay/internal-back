<?php


namespace App\Reports\Repositories;

use App\Reports\Entities\ClosureGameTotal;
use App\Reports\Entities\ClosureUserTotal;
use App\Reports\Entities\ClosureUserTotal2023;
use Illuminate\Support\Facades\DB;

/**
 * Class ClosuresUsersTotalsRepo
 *
 * This class allows to manage ClosureUserTotal entity
 *
 * @package App\Reports\Repositories
 * @author Damelys Espinoza
 */
class ClosuresUsersTotalsRepo
{

    /**
    * Get Closure User Totals
    *
    * @param string $startDate Start date to filter
    * @param string $endDate End date to filter
    * @param int $whitelabel Whitelabel ID
    * @return array
    */
    public function getClosureUserTotals($startDate, $endDate, $whitelabel)
    {
        $closure =  ClosureUserTotal::join('whitelabels', 'closures_users_totals.whitelabel_id', '=', 'whitelabels.id')
            ->join('users', 'closures_users_totals.user_id', '=', 'users.id')
            ->where('start_date', '>=', $startDate)
            ->where('end_date', '<=', $endDate)
            ->where('closures_users_totals.whitelabel_id', $whitelabel)
            ->orderBy('end_date', 'DESC')
            ->get();
        return $closure;
    }

    /**
     * Get games totals data
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $startDate Start date to filter
     * @param string $endDate End date to filter
     * @param string $currency Currency ISO
     * @param int $provider Provider ID
     * @return mixed
     */
    public function getGamesTotals($whitelabel, $startDate, $endDate, $currency, $provider)
    {
        $totals = ClosureGameTotal::select('game_id as id', 'game_name', 'mobile', \DB::raw('sum(bets) AS bets'), \DB::raw('sum(played) AS played'), \DB::raw('sum(won) AS won'),
            \DB::raw('sum(profit) AS profit'))
            ->where('start_date', '>=', $startDate)
            ->where('end_date', '<=', $endDate)
            ->where('whitelabel_id', $whitelabel)
            ->where('currency_iso', $currency)
            ->where('provider_id', $provider)
            ->groupBy('game_id', 'game_name', 'mobile')
            ->get();
        return $totals;
    }

    /**
     * Get las closure
     *
     * @param int $whitelabel Whitelabel ID
     * @param string|null $currency Currency ISO
     * @return mixed
     */
    public function getLastClosure(int $whitelabel, ?string $currency)
    {
        return ClosureUserTotal::on('replica')
            ->select('end_date')
            ->where('whitelabel_id', $whitelabel)
            ->where('currency_iso', $currency)
            ->orderBy('id', 'DESC')
            ->first();
    }

    /**
     * Get las closure
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $currency Currency ISO
     * @return mixed
     */
    public function getPlayedByUser($user, $currency, $whitelabel)
    {
        $totals = ClosureUserTotal::where('user_id', $user)
            ->where('whitelabel_id', $whitelabel);

       if (!is_null($currency)) {
           $totals->where('currency_iso', $currency);
       }
       return $totals->sum('played');
    }

    /**
     * Get products bets
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $startDate Start date to filter
     * @param string $endDate End date to filter
     * @param string $currency Currency ISO
     * @param string $providerType Provider type ID
     * @return mixed
     */
    public function getProductsBets($whitelabel, $startDate, $endDate, $currency, $providerType, $provider)
    {
        $totals = ClosureUserTotal::on('replica')
            ->select('provider_id', 'closures_users_totals.currency_iso', \DB::raw('sum(bets) AS bets'))
            ->join('providers', 'closures_users_totals.provider_id', '=', 'providers.id')
            ->join('users', 'users.id', '=', 'closures_users_totals.user_id')
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

        $data = $totals->groupBy('closures_users_totals.provider_id', 'closures_users_totals.currency_iso')->get();
        return $data;
    }

    /**
     * Get products totals data
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $startDate Start date to filter
     * @param string $endDate End date to filter
     * @param string $currency Currency ISO
     * @param string $providerType Provider type ID
     * @return mixed
     */
    public function getProductsTotals($whitelabel, $startDate, $endDate, $currency, $providerType, $provider)
    {
            $totals = ClosureUserTotal::on('replica')
            ->select('provider_id', 'start_date', 'providers.provider_type_id', \DB::raw('sum(bets) AS bets'),
            \DB::raw('sum(played) AS played'), \DB::raw('sum(won) AS won'),
            \DB::raw('sum(profit) AS profit'), 'closures_users_totals.currency_iso')
            ->join('providers', 'closures_users_totals.provider_id', '=', 'providers.id')
            ->join('users', 'users.id', '=', 'closures_users_totals.user_id')
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

        $data = $totals->groupBy('closures_users_totals.provider_id', 'start_date', 'providers.provider_type_id', 'closures_users_totals.currency_iso')
            ->get();
        return $data;
    }

    /**
     * Get products totals data
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $startDate Start date to filter
     * @param string $endDate End date to filter
     * @param string $currency Currency ISO
     * @param string $providerType Provider type ID
     * @return mixed
     */
    public function getProductsTotalsOverview($startDate, $endDate, $currency, $provider)
    {
        $totals = ClosureUserTotal::on('replica')
            ->select('provider_id', 'start_date', 'providers.provider_type_id',
                \DB::raw('sum(played) AS played'), \DB::raw('sum(won) AS won'),
                \DB::raw('sum(profit) AS profit'), 'closures_users_totals.currency_iso')
            ->join('providers', 'closures_users_totals.provider_id', '=', 'providers.id')
            ->join('users', 'users.id', '=', 'closures_users_totals.user_id')
            ->where('start_date', '>=', $startDate)
            ->where('end_date', '<=', $endDate);

        if (!empty($currency)) {
            $totals->where('closures_users_totals.currency_iso', $currency);
        }

        if (!empty($provider)) {
            $totals->where('closures_users_totals.provider_id', $provider);
        }

        $data = $totals->groupBy('closures_users_totals.provider_id', 'start_date', 'providers.provider_type_id', 'closures_users_totals.currency_iso')
            ->get();
        return $data;
    }

    /**
     * Get products totals data
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $startDate Start date to filter
     * @param string $endDate End date to filter
     * @param string $currency Currency ISO
     * @param int $user User ID
     * @return mixed
     */
    public function getProductsTotalsByUser($whitelabel, $startDate, $endDate, $currency, $user)
    {
        return ClosureUserTotal::select('provider_id', 'providers.provider_type_id', \DB::raw('sum(bets) AS bets'),
            \DB::raw('sum(played) AS played'), \DB::raw('sum(won) AS won'),
            \DB::raw('sum(profit) AS profit'), 'closures_users_totals.currency_iso', \DB::raw('count (*) AS users'))
            ->join('providers', 'closures_users_totals.provider_id', '=', 'providers.id')
            ->where('start_date', '>=', $startDate)
            ->where('end_date', '<=', $endDate)
            ->where('user_id', '=', $user)
            ->where('closures_users_totals.whitelabel_id', $whitelabel)
            ->where('closures_users_totals.currency_iso', $currency)
            ->groupBy('closures_users_totals.provider_id', 'providers.provider_type_id', 'closures_users_totals.currency_iso')
            ->get();
    }

    /**
     * Get products users
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $startDate Start date to filter
     * @param string $endDate End date to filter
     * @param string $currency Currency ISO
     * @param string $providerType Provider type ID
     * @return mixed
     */
    public function getProductsUsers($whitelabel, $startDate, $endDate, $currency, $providerType, $provider)
    {
        $totals = ClosureUserTotal::on('replica')
            ->select('provider_id', 'closures_users_totals.currency_iso', 'user_id')
            ->distinct()
            ->join('providers', 'closures_users_totals.provider_id', '=', 'providers.id')
            ->join('users', 'users.id', '=', 'closures_users_totals.user_id')
            ->where('start_date', '>=', $startDate)
            ->where('end_date', '<=', $endDate)
            ->where('closures_users_totals.whitelabel_id', $whitelabel);

        if (!is_null($currency)) {
            $totals->where('closures_users_totals.currency_iso', $currency);
        }

        if (!is_null($providerType)) {
            $totals->where('providers.provider_type_id', $providerType);
        }

        if (!is_null($provider)) {
            $totals->where('closures_users_totals.provider_id', $provider);
        }

        $data = $totals->groupBy('closures_users_totals.provider_id', 'closures_users_totals.currency_iso', 'user_id')->get();
        return $data;
    }

    /**
     * Get active users
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $startDate Start date
     * @param string $endDate End date
     * @return array
     */
    public function getActiveUsers($whitelabel, $startDate, $endDate)
    {
        return ClosureUserTotal2023::select('users.id', 'users.username', 'users.email', 'users.created_at')
            ->join('users', 'closures_users_totals_2023.user_id', '=', 'users.id')
            ->where('closures_users_totals_2023.start_date', '>=', $startDate)
            //->where('closures_users_totals_2023.end_date', '<=', $endDate)
            //->where('users.status', true)
            ->where('closures_users_totals_2023.whitelabel_id', $whitelabel)
            ->groupBy('users.id', 'users.username', 'users.email', 'users.created_at')
            ->orderBy('users.username', 'DESC')
            ->get();
    }

    /**
     * Get users played
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $startDate Start date to filter
     * @param string $endDate End date to filter
     * @param string $currency Currency ISO
     * @param int $provider Provider ID
     * @return mixed
     */
    public function getUsersPlayed($whitelabel, $startDate, $endDate, $currency, $provider)
    {
        $played = ClosureUserTotal::select('user_id as id', 'username', \DB::raw('sum(played) AS played'),  \DB::raw('sum(profit) AS profit'), \DB::raw('sum(won) AS won'), 'games.name')
            ->join('games', 'closures_users_totals.game_id', '=', 'games.id')
            ->where('closures_users_totals.start_date', '>=', $startDate)
            ->where('closures_users_totals.end_date', '<=', $endDate)
            ->where('closures_users_totals.whitelabel_id', $whitelabel)
            ->where('closures_users_totals.currency_iso', $currency)
            ->where('closures_users_totals.provider_id', $provider)
            ->groupBy('closures_users_totals.user_id', 'closures_users_totals.username', 'games.name')->get();
        return $played;
    }

     /**
     * Get users totals data
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $startDate Start date to filter
     * @param string $endDate End date to filter
     * @param string $currency Currency ISO
     * @param int $provider Provider ID
     * @return mixed
     */
    public function getUsersTotals($whitelabel, $startDate, $endDate, $currency, $provider)
    {
        $totals = ClosureUserTotal::select('user_id as id', 'username', \DB::raw('sum(bets) AS bets'), \DB::raw('sum(played) AS played'), \DB::raw('sum(won) AS won'),
            \DB::raw('sum(profit) AS profit'))
            ->where('start_date', '>=', $startDate)
            ->where('end_date', '<=', $endDate)
            ->where('whitelabel_id', $whitelabel)
            ->where('currency_iso', $currency)
            ->where('provider_id', $provider)
            ->groupBy('user_id', 'username')
            ->get();
        return $totals;
    }

    /**
     * Get user providers totals data
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $startDate Start date to filter
     * @param string $endDate End date to filter
     * @param string $currency Currency ISO
     * @return mixed
     */
    public function getUserProvidersTotals($whitelabel, $startDate, $endDate, $currency)
    {
        $totals = DB::table('closures_users_totals')
            ->select('user_id as id', 'username', 'provider_id', 'currency_iso', \DB::raw('sum(played) AS bets'))
            ->where('start_date', '>=', $startDate)
            ->where('end_date', '<=', $endDate)
            ->where('played','>', 0)
            ->where('whitelabel_id', $whitelabel);

        if (!is_null($currency)) {
            $totals->where('currency_iso', $currency);
        }

        $data = $totals->groupBy('user_id', 'username', 'provider_id', 'currency_iso');
        return $data;
    }

    /**
     * Get users totals bets data'
     *
     * @param string $totals Totals table
     */
    public function getUsersTotalsBets($totals)
    {
        $bets = DB::table(DB::raw("({$totals->toSql()}) as t2") )
            ->select('bets', 't2.provider_id')
            ->mergeBindings($totals);
        return $bets;
    }

    /**
     * Get users totals max data
     *
     * @param string $bets  Bets table
     */
    public function getUsersTotalsMax($bets)
    {
        $max = DB::table(DB::raw("({$bets->toSql()}) as t3") )
            ->select(DB::raw('MAX(bets) as played'), 't3.provider_id')
            ->groupBy('t3.provider_id')
            ->mergeBindings($bets);
        return $max;
    }

    /**
     * Get users totals join data
     *
     * @param string $totals table UsersTotals
     * @param string $max  table
     */
    public function getUsersTotalsFinal($totals,$max)
    {
        $users =  DB::table($totals, 'table1')
            ->joinSub($max, 'table2', function ($join) {
                $join->on('table2.played', '=', 'table1.bets')
                    ->on('table2.provider_id', '=', "table1.provider_id");
            })->get();
        return $users;
    }

    /**
     * Get users totals by users IDs
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $startDate Start date
     * @param string $endDate End date
     * @param string $currency Currency ISO
     * @param array $users Users IDs
     * @return array
     */
    public function getUsersTotalsByIds($whitelabel, $startDate, $endDate, $currency, $users)
    {
        return DB::select("SELECT user_id AS id, username, sum(bets) AS bets, sum(played) AS played, sum(won) AS won, sum(profit) AS profit
            FROM closures_users_totals
            WHERE start_date >= ? AND end_date <= ?
            AND whitelabel_id = ?
            AND currency_iso = ?
            AND user_id IN (" . implode(',', $users) . ")
            GROUP BY user_id, username", [$startDate, $endDate, $whitelabel, $currency]);
    }

    /**
     * Get users totals by users IDs grouped by provider
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $startDate Start date
     * @param string $endDate End date
     * @param string $currency Currency ISO
     * @param array $users Users IDs
     * @return array
     */
    public function getUsersTotalsByIdsGroupedByProvider($whitelabel, $startDate, $endDate, $currency, $users)
    {
        return DB::select("SELECT user_id AS id, username, provider_id, sum(bets) AS bets, sum(played) AS played, sum(won) AS won, sum(profit) AS profit
            FROM closures_users_totals
            WHERE start_date >= ? AND end_date <= ?
            AND whitelabel_id = ?
            AND currency_iso = ?
            AND user_id IN (" . implode(',', $users) . ")
            GROUP BY user_id, username, provider_id", [$startDate, $endDate, $whitelabel, $currency]);
    }

    /**
     * Get users totals by users IDs and provider
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $startDate Start date
     * @param string $endDate End date
     * @param string $currency Currency ISO
     * @param int $providerType Provider type ID
     * @param array $users Users IDs
     * @return array
     */
    public function getUsersTotalsByIdsAndProviderType($whitelabel, $startDate, $endDate, $currency, $providerType, $users)
    {
        return DB::select("SELECT user_id AS id, username, sum(bets) AS bets, sum(played) AS played, sum(won) AS won, sum(profit) AS profit
            FROM closures_users_totals
            JOIN providers on closures_users_totals.provider_id = providers.id
            WHERE start_date >= ? AND end_date <= ?
            AND whitelabel_id = ?
            AND currency_iso = ?
            AND provider_type_id = ?
            AND user_id IN (" . implode(',', $users) . ")
            GROUP BY user_id, username", [$startDate, $endDate, $whitelabel, $currency, $providerType]);
    }

    /**
     * Get users totals by users IDs and provider grouped by provider
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $startDate Start date
     * @param string $endDate End date
     * @param string $currency Currency ISO
     * @param array $users Users IDs
     * @return array
     */
    public function getUsersTotalsByIdsAndProvidersGroupedByProvider($whitelabel, $startDate, $endDate, $currency, $users)
    {
        return DB::select("SELECT user_id AS id, username, sum(bets) AS bets, sum(played) AS played, sum(won) AS won, sum(profit) AS profit, provider_id
            FROM closures_users_totals
            WHERE start_date >= ? AND end_date <= ?
            AND whitelabel_id = ?
            AND currency_iso = ?
            AND user_id IN (" . implode(',', $users) . ")
            GROUP BY user_id, username, provider_id", [$startDate, $endDate, $whitelabel, $currency]);
    }

    public function getUsersTotalsByIds_ByProvider($whitelabel, $startDate, $endDate, $currency, $users,$provider)
    {
        return DB::select("SELECT user_id AS id, username, sum(bets) AS bets, sum(played) AS played, sum(won) AS won, sum(profit) AS profit
            FROM closures_users_totals
            WHERE start_date >= ? AND end_date <= ?
            AND whitelabel_id = ?
            AND currency_iso = ?
            AND provider_id = ?
            AND user_id IN (" . implode(',', $users) . ")
            GROUP BY user_id, username, provider_id", [$startDate, $endDate, $whitelabel, $currency,$provider]);
    }

    /**
     * Get users totals by whitelabel
     *
     * @param string $startDate Start date to filter
     * @param string $endDate End date to filter
     * @param int $whitelabel Whitelabel ID
     * @param string $currency Currency ISO
     * @return mixed
     */
    public function getUsersTotalsByWhitelabelAndDates($startDate, $endDate, $whitelabel, $currency)
    {
        $totals = ClosureUserTotal::select(\DB::raw('sum(played) AS played'), \DB::raw('sum(profit) AS profit'))
            ->where('start_date', '>=', $startDate)
            ->where('end_date', '<=', $endDate)
            ->where('whitelabel_id', $whitelabel)
            ->where('currency_iso', $currency);
        if (!is_null($currency)) {
            $totals->where('currency_iso', $currency);
        }
        $data = $totals->first();
        return $data;
    }

    /**
     * Store closure
     *
     * @param $data
     * @return mixed
     */
    public function store($data)
    {
        $closure = ClosureUserTotal::create($data);
        return $closure;
    }

    /**
     * Get whitelabels totals data
     *
     * @param string $startDate Start date to filter
     * @param string $endDate End date to filter
     * @return mixed
     */
    public function whitelabelsTotals($startDate, $endDate, $currency, $provider, $whitelabel)
    {
        $totals = ClosureUserTotal::select('whitelabels.description AS whitelabel', 'closures_users_totals.provider_id', \DB::raw('sum(closures_users_totals.played) AS played'), \DB::raw('sum(closures_users_totals.won) AS won'),
            \DB::raw('sum(closures_users_totals.profit) AS profit'), 'closures_users_totals.currency_iso', 'provider_type_id')
            ->join('whitelabels', 'closures_users_totals.whitelabel_id', '=', 'whitelabels.id')
            ->join('providers', 'closures_users_totals.provider_id', '=', 'providers.id')
            ->where('closures_users_totals.start_date', '>=', $startDate)
            ->where('closures_users_totals.end_date', '<=', $endDate);

        if (!empty($whitelabel)) {
            $totals->where('closures_users_totals.whitelabel_id', $whitelabel);
        }

        if (!empty($currency)) {
            $totals->where('closures_users_totals.currency_iso', $currency);
        }

        if (!empty($provider)) {
            $totals->where('closures_users_totals.provider_id', $provider);
        }

        $data = $totals->orderBy('whitelabels.description', 'ASC')
            ->orderBy('closures_users_totals.provider_id', 'ASC')
            ->orderBy('closures_users_totals.currency_iso', 'ASC')
            ->groupBy('whitelabels.description', 'closures_users_totals.provider_id', 'closures_users_totals.currency_iso', 'providers.provider_type_id', 'closures_users_totals.whitelabel_id')
            ->get();
        return $data;
    }

    /**
     * Get whitelabels closures totals data
     *
     * @param string $startDate Start date to filter
     * @param string $endDate End date to filter
     * @param string $currency Currency ISO
     * @param int $provider Provider ID
     * @param int $whitelabel Whitelabel ID
     * @return mixed
     */
    public function whitelabelsClosuresTotals($startDate, $endDate, $currency, $provider , $whitelabel)
    {
        $totals = DB::table('closures_users_totals')
        ->select('whitelabels.description AS whitelabel', 'closures_users_totals.provider_id', 'closures_users_totals.whitelabel_id',
        \DB::raw('sum(closures_users_totals.played) AS played'), \DB::raw('sum(closures_users_totals.won) AS won'),
        \DB::raw('sum(closures_users_totals.profit) AS profit'), 'closures_users_totals.currency_iso', 'provider_type_id')
        ->join('whitelabels', 'closures_users_totals.whitelabel_id', '=', 'whitelabels.id')
        ->join('providers', 'closures_users_totals.provider_id', '=', 'providers.id')
        ->where('closures_users_totals.start_date', '>=', $startDate)
        ->where('closures_users_totals.end_date', '<=', $endDate);

        if (!empty($whitelabel)) {
            $totals ->where('closures_users_totals.currency_iso', $currency);
        }

        if (!empty($whitelabel)) {
            $totals->whereIn('closures_users_totals.whitelabel_id',explode(',', $whitelabel));
        }

        if (!empty($provider)) {
            $totals->whereIn('closures_users_totals.provider_id', explode(',',$provider));
        }

        $data = $totals->orderBy('whitelabels.description', 'ASC')
            ->orderBy('closures_users_totals.provider_id', 'ASC')
            ->orderBy('closures_users_totals.currency_iso', 'ASC')
            ->groupBy('whitelabels.description', 'closures_users_totals.provider_id', 'closures_users_totals.currency_iso',
             'closures_users_totals.whitelabel_id', 'providers.provider_type_id');
        return $data;
    }

     /**
     * Get whitelabels totals join data
     *
     * @param string $whitelabelsTotals  table whitelabelsClosuresTotals
     */
    public function getWhitelabelsTotal($whitelabelsTotals)
    {
        $users = DB::table('credentials')
        ->select('whitelabel_id', 'whitelabel','table_total.provider_id',
        'played','won','profit','table_total.currency_iso', 'provider_type_id', 'percentage' )
        ->joinSub($whitelabelsTotals, 'table_total', function ($join) {
            $join->on('credentials.client_id', '=', 'table_total.whitelabel_id')
                ->on('credentials.provider_id', '=', 'table_total.provider_id')
                ->on('credentials.currency_iso', '=', 'table_total.currency_iso');
        })
        ->groupBy('whitelabel', 'table_total.provider_id', 'table_total.currency_iso',
             'whitelabel_id', 'provider_type_id', 'played', 'won', 'profit', 'percentage')
        ->orderBy('table_total.whitelabel', 'ASC')
        ->orderBy('table_total.provider_id', 'ASC')
        ->orderBy('table_total.currency_iso', 'ASC')
        ->get();
        return $users;
    }

    /**
     * Get user providers totals data
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $startDate Start date to filter
     * @param string $endDate End date to filter
     * @param string $currency Currency ISO
     * @return mixed
     */
    public function closuresTotalsByProviders($whitelabel, $startDate, $endDate, $currency)
    {
        return \DB::select("WITH closures AS (
                SELECT username, closures_users_totals.user_id, provider_id, currency_iso, sum(played) AS bets
                FROM closures_users_totals
                WHERE whitelabel_id = ?
                AND currency_iso = ?
                AND start_date >= ?
                AND end_date <= ?
                AND played > 0
                GROUP BY username, closures_users_totals.user_id, provider_id, currency_iso
            ),
            total as (
                select max(bets) as max_played, provider_id from closures
                group by provider_id
            )

            SELECT username, closures.user_id, closures.provider_id, currency_iso, max_played
            FROM closures
                join total on closures.bets = total.max_played
                and closures.provider_id = total.provider_id", [$whitelabel, $currency, $startDate , $endDate]);
    }
}

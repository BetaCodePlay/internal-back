<?php


namespace App\Reports\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Class ReportAgentRepo
 *
 * @package App\Reports\Repositories
 *
 */
class ReportAgentRepo
{

    /**
     * Get Ids User Children From Father
     *
     * @param int $ownerId id user Father
     * @param string $currency Currency
     * @param int $whitelabel Whitelabel ID
     * @return array
     */
    public function getIdsChildrenFromFather(int $ownerId, string $currency, int $whitelabel)
    {
        $getIdsChildren = DB::select(
            'SELECT * FROM site.get_ids_children_from_father(?,?,?)',
            [$ownerId, $currency, $whitelabel]
        );

        return array_column($getIdsChildren, 'id');
    }

    /**
     *
     * @param int|string $userId
     * @param string $currency
     * @param int|string $whitelabelId
     * @param string $startDate
     * @param string $endDate
     * @param string|null $timezone
     * @param string|null $category
     * @param string|null $provider
     * @return Collection
     */
    public function getCommissionByCategory(
        int|string $userId,
        string $currency,
        int|string $whitelabelId,
        string $startDate,
        string $endDate,
        ?string $timezone = null,
        ?string $category = null,
        ?string $provider = null
    )
    : Collection {
        $query = "SELECT * FROM site.get_comission_by_category(?, ?, ?, ?, ?, ?, ?, ?)";

        $results = DB::select($query, [
            $userId,
            $currency,
            $whitelabelId,
            $startDate,
            $endDate,
            $timezone,
            $category,
            $provider
        ]);

        return collect($results);
    }

    /**
     *
     * @param int|string $userId
     * @param string $currency
     * @param int|string $whitelabelId
     * @param string $startDate
     * @param string $endDate
     * @param string|null $timezone
     * @param string|null $username
     * @return Collection
     */
    public function getTotalByUserFromAgent(
        int|string $userId,
        string $currency,
        int|string $whitelabelId,
        string $startDate,
        string $endDate,
        ?string $timezone = null,
        ?string $username = ''
    )
    : Collection {
        $query = "SELECT * FROM site.total_by_user_from_agent(?, ?, ?, ?, ?, ?, ?)";

        $results = DB::select($query, [
            $userId,
            $currency,
            $whitelabelId,
            $startDate,
            $endDate,
            $timezone,
            $username,
        ]);

        return collect($results);
    }

/**
     * Get Closure FinancialState
     *
     * @param int $whitelabel Whitelabel Id
     * @param int $ownerId User ID (agent)
     * @param string $currency Iso Currency
     * @param string $startDate Date Start
     * @param string $endDate Date End
     * @return array
     */
    public function getFinancialStateOld(
        string $startDate,
        string $endDate,
        $currency,
        $whitelabel,
        $ownerId,
        $timezone = null,
        $provider = null,
        $child = null,
        $text = null
    ) {
        $sql = "
        select g.category, sum(played)  as played, sum(won) as won,sum(profit) as profit,
            case
                when sum(profit*percentage/100) < 0 or sum(profit*percentage/100) is null then 0
            else sum(profit*percentage/100) end as commission
        from site.closures_users_totals_2023_hour cu
        inner join site.games g on cu.game_id=g.id
        inner join site.providers p on g.provider_id=p.id
        inner join site.agent_user au on cu.user_id=au.user_id
        inner join site.agents a on a.id=au.agent_id
        where cu.currency_iso='{$currency}'
            AND a.user_id = {$ownerId}
            AND cu.whitelabel_id = {$whitelabel}
        ";

        if (is_null($timezone)) {
            $sql .= "AND start_date BETWEEN '{$startDate}' AND '$endDate}'";
        } else {
            $sql .= "AND start_date AT TIME ZONE '{$timezone}' BETWEEN '{$startDate}' AND '$endDate}'";
        }

        if (! is_null($provider)) {
            $sql .= "
                AND g.maker = '{$provider}'
                ";
        }

        if (! is_null($child)) {
            $sql .= "
                AND cu.user_id = {$child}
                ";
        }
        if (! is_null($text)) {
            $sql .= "
                AND LOWER(g.category) LIKE LOWER('%{$text}%')
                ";
        }


        $sql .= "GROUP BY
                g.category
            ORDER BY
                won;
                ";

        return DB::select($sql);
    }

    public function getFinancialState(
        string $startDate,
        string $endDate,
        $currency,
        $whitelabel,
        $ownerIds,
        $timezone = null,
        $provider = null,
        $childs = null,
        $text = null
    ) {
        $query = DB::table('site.closures_users_totals_2023_hour as cu')
            ->select([
                    'g.category',
                    DB::raw('SUM(cu.played) as played'),
                    DB::raw('SUM(cu.won) as won'),
                    DB::raw('SUM(cu.profit) as profit'),
                    DB::raw(
                        'CASE
                    WHEN SUM(cu.profit * a.percentage / 100) < 0 OR SUM(cu.profit * a.percentage / 100) IS NULL THEN 0
                    ELSE SUM(cu.profit * a.percentage / 100)
                END as commission'
                    )
                ]
            )
            ->join('site.games as g', 'cu.game_id', '=', 'g.id')
            ->join('site.providers as p', 'g.provider_id', '=', 'p.id')
            ->join('site.agent_user as au', 'cu.user_id', '=', 'au.user_id')
            ->join('site.agents as a', 'a.id', '=', 'au.agent_id')
            ->whereIn('a.user_id', is_array($ownerIds) ? $ownerIds : [$ownerIds])
            ->where([
                'cu.currency_iso' => $currency,
                'cu.whitelabel_id' => $whitelabel,
            ]);

        if (is_null($timezone)) {
            $query->whereBetween('cu.start_date', [$startDate, $endDate]);
        } else {
            $query->whereRaw("cu.start_date AT TIME ZONE '{$timezone}' BETWEEN '{$startDate}' AND '{$endDate}'");
        }

        if (! is_null($provider)) {
            $query->where('g.maker', $provider);
        }

        if (! empty($childs)) {
            $query->whereIn('cu.user_id', is_array($childs) ? $childs : [$childs]);
        }

        if (! is_null($text)) {
            $query->whereRaw("LOWER(g.category) LIKE LOWER('%{$text}%'");
        }

        return $query
            ->groupBy('g.category')
            ->orderBy('won')
            ->get();
    }

    /**
     * Get Closure FinancialState
     *
     * @param int $whitelabel Whitelabel Id
     * @param int $ownerId User ID (agent)
     * @param string $currency Iso Currency
     * @param string $startDate Date Start
     * @param string $endDate Date End
     * @return array
     */
    public function getFinancialStateByCategory(
        string $startDate,
        string $endDate,
        $currency,
        $whitelabel,
        $ownerId,
        $category,
        $timezone = null,
        $provider = null,
        $child = null
    ) {
        $sql = "
            SELECT
                g.name,
                g.maker AS provider,
                SUM(played) AS played,
                SUM(won) AS won,
                SUM(profit) AS profit,
                CASE
                    WHEN SUM(profit*percentage/100) < 0 OR SUM(profit*percentage/100) IS NULL THEN 0
                    ELSE SUM(profit*percentage/100)
                END AS commission
            FROM
                site.closures_users_totals_2023_hour cu
                INNER JOIN site.games g ON cu.game_id = g.id
                INNER JOIN site.providers p ON g.provider_id = p.id
                INNER JOIN site.agent_user au ON cu.user_id = au.user_id
                INNER JOIN site.agents a ON a.id = au.agent_id
            WHERE
                cu.currency_iso = '{$currency}'
                AND a.user_id = {$ownerId}
                AND cu.whitelabel_id = {$whitelabel}
                AND g.category = '{$category}'
            ";

        if (is_null($timezone)) {
            $sql .= "AND start_date BETWEEN '{$startDate}' AND '$endDate}'";
        } else {
            $sql .= "AND start_date AT TIME ZONE '{$timezone}' BETWEEN '{$startDate}' AND '$endDate}'";
        }

        if (! is_null($provider)) {
            $sql .= "
                AND g.maker = '{$provider}'
                ";
        }

        if (! is_null($child)) {
            $sql .= "
                AND cu.user_id = {$child}
                ";
        }


        $sql .= "GROUP BY
                g.category, g.name, g.maker
            ORDER BY
                won;
                ";

        return DB::select($sql);
    }

    /**
     * Get Ids User Childrens
     *
     * @param int $ownerId id user Father
     * @param string $currency Currency
     * @param int $whitelabel Whitelabel ID
     * @return array
     */
    public function getChildrens(int $ownerId, string $currency, int $whitelabel)
    {
        $childs_users_id = implode(
            ',',
            array_column(
                DB::select('SELECT * FROM site.get_ids_children_from_father(?,?,?)', [$ownerId, $currency, $whitelabel]
                ),
                'id'
            )
        );

        $users = DB::select("SELECT id, username FROM site.users where id in ({$childs_users_id})");

        return $users;
    }


    /**
     * Get Providers
     *
     * @return array
     */

    public function getProviders()
    {
        $providers = DB::select("SELECT maker as provider FROM site.games group by maker");

        return $providers;
    }
}

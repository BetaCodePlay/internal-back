<?php


namespace App\Reports\Repositories;

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
        $getIdsChildren = DB::select('SELECT * FROM site.get_ids_children_from_father(?,?,?)', [$ownerId, $currency, $whitelabel]);

        return array_column($getIdsChildren,'id');
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
    public function getClosureFinancialState(string $startDate, string $endDate, $currency, $whitelabel, $ownerId)
    {
        return DB::select("
            select g.type, g.name, sum(played)  as played, sum(won) as won,sum(profit) as profit, sum(profit*percentage/100) as commission,
                case 
                    when sum(profit*percentage/100) < 0 or sum(profit*percentage/100) is null then 0
                else sum(profit*percentage/100) end as commission
            from site.closures_users_totals_2023_hour cu 
            inner join site.games g on cu.game_id=g.id 
            inner join site.providers p on g.provider_id=p.id 
            inner join site.agent_user au on cu.user_id=au.user_id
            inner join site.agents a on a.id=au.agent_id 
            where cu.currency_iso='{$currency}' and start_date between '{$startDate}' and '{$endDate}'
            and a.user_id={$ownerId}
            and cu.whitelabel_id={$whitelabel}
            group by  g.type, g.name
            order by g.type, won;
        ");
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
        $childs_users_id = implode(',', array_column(DB::select('SELECT * FROM site.get_ids_children_from_father(?,?,?)', [$ownerId, $currency, $whitelabel]),'id'));

        $users = DB::select("SELECT id, username FROM site.users where id in ({$childs_users_id})");

        return $users;
    }


}

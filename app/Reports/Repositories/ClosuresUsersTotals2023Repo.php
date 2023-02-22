<?php


namespace App\Reports\Repositories;

use App\Reports\Entities\ClosureGameTotal;
use App\Reports\Entities\ClosureUserTotal;
use App\Reports\Entities\ClosureUserTotal2023;
use Illuminate\Support\Facades\DB;

/**
 * Class ClosuresUsersTotals2023Repo
 *
 * This class allows to manage ClosureUserTotal2023 entity
 *
 * @package App\Reports\Repositories
 * @author Estarly Olivar
 */
class ClosuresUsersTotals2023Repo
{

    /**
    * Get Closure User Totals 2023
    *
    * @param string $startDate Start date to filter
    * @param string $endDate End date to filter
    * @param int $whitelabel Whitelabel ID
    * @return array
    */
    public function getClosureByGroupTotals($startDate, $endDate, $whitelabel,$currency_iso,$arrayUsers,$fieldGroup)
    {
        $usersTmp = null;
        foreach ($arrayUsers as $value){
            $usersTmp = is_null($usersTmp) ? json_encode($value): $usersTmp.','.json_encode($value);
        }
        $closure =  DB::select("SELECT
                site.closures_users_totals_2023.{$fieldGroup},
                SUM (site.closures_users_totals_2023.played) as total_played,
                SUM (site.closures_users_totals_2023.won) as total_won,
                SUM (site.closures_users_totals_2023.bets) as total_bet,
                SUM (site.closures_users_totals_2023.profit) as total_profit,
                SUM (site.closures_users_totals_2023.rtp) as total_rtp
            FROM site.closures_users_totals_2023
            WHERE site.closures_users_totals_2023.whitelabel_id = ?
            AND site.closures_users_totals_2023.currency_iso = ?
            AND site.closures_users_totals_2023.user_id IN (?)
            AND site.closures_users_totals_2023.start_date BETWEEN ? AND ?
            AND site.closures_users_totals_2023.end_date BETWEEN ? AND ?
            GROUP BY site.closures_users_totals_2023.{$fieldGroup}",[$whitelabel,$currency_iso,$usersTmp,$startDate,$endDate,$startDate,$endDate]);

        return $closure;

    }

    public function getClosureUserTotals($startDate, $endDate, $whitelabel,$currency_iso,$arrayUsers)
    {
        $closure =  ClosureUserTotal2023::select('*')
            ->where('start_date', '>=', $startDate)
            ->where('end_date', '<=', $endDate)
            ->where('whitelabel_id', $whitelabel)
            ->where('currency_iso', $currency_iso)
            ->whereIn('user_id', $arrayUsers)
            ->orderBy('end_date', 'DESC')
            ->get();

        return $closure;

    }

    public function closuresTotalsByProviders($whitelabel, $startDate, $endDate, $currency)
    {
        return \DB::select("WITH closures AS (
                SELECT username, closures_users_totals_2023.user_id, provider_id, currency_iso, sum(played) AS bets
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

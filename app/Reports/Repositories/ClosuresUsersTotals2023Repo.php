<?php


namespace App\Reports\Repositories;

use App\Reports\Entities\ClosureGameTotal;
use App\Reports\Entities\ClosureUserTotal;
use App\Reports\Entities\ClosureUserTotal2023;
use Dotworkers\Security\Enums\Roles;
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
                and closures.provider_id = total.provider_id", [$whitelabel, $currency, $startDate, $endDate]);
    }

    public function getClosureByGroupTotals($startDate, $endDate, $whitelabel, $currency_iso, $arrayUsers, $fieldGroup)
    {
        if(in_array(Roles::$super_admin,session('roles'))){

            $closure = DB::select("SELECT
                site.closures_users_totals_2023.{$fieldGroup},
                SUM (site.closures_users_totals_2023.played) as total_played,
                SUM (site.closures_users_totals_2023.won) as total_won,
                SUM (site.closures_users_totals_2023.bets) as total_bet,
                SUM (site.closures_users_totals_2023.profit) as total_profit,
                SUM (site.closures_users_totals_2023.rtp) as total_rtp
            FROM site.closures_users_totals_2023
            WHERE site.closures_users_totals_2023.whitelabel_id = '{$whitelabel}'
            AND site.closures_users_totals_2023.currency_iso = '{$currency_iso}'
            AND site.closures_users_totals_2023.start_date BETWEEN '{$startDate}' AND '{$endDate}'
            AND site.closures_users_totals_2023.end_date BETWEEN '{$startDate}' AND '{$endDate}'
            GROUP BY site.closures_users_totals_2023.{$fieldGroup}");

        } else {
            $arrayUsers = implode(',', $arrayUsers);

            $closure = DB::select("SELECT
                site.closures_users_totals_2023.{$fieldGroup},
                SUM (site.closures_users_totals_2023.played) as total_played,
                SUM (site.closures_users_totals_2023.won) as total_won,
                SUM (site.closures_users_totals_2023.bets) as total_bet,
                SUM (site.closures_users_totals_2023.profit) as total_profit,
                SUM (site.closures_users_totals_2023.rtp) as total_rtp
            FROM site.closures_users_totals_2023
            WHERE site.closures_users_totals_2023.whitelabel_id = '{$whitelabel}'
            AND site.closures_users_totals_2023.currency_iso = '{$currency_iso}'
            AND site.closures_users_totals_2023.user_id IN ({$arrayUsers})
            AND site.closures_users_totals_2023.start_date BETWEEN '{$startDate}' AND '{$endDate}'
            AND site.closures_users_totals_2023.end_date BETWEEN '{$startDate}' AND '{$endDate}'
            GROUP BY site.closures_users_totals_2023.{$fieldGroup}");
        }

        return $closure;

    }

    public function getClosureUserTotals($startDate, $endDate, $whitelabel, $currency_iso, $arrayUsers)
    {
        $closure = ClosureUserTotal2023::select('*')
            ->where('start_date', '>=', $startDate)
            ->where('end_date', '<=', $endDate)
            ->where('whitelabel_id', $whitelabel)
            ->where('currency_iso', $currency_iso)
            ->whereIn('user_id', $arrayUsers)
            ->orderBy('end_date', 'DESC')
            ->get();

        return $closure;

    }
}

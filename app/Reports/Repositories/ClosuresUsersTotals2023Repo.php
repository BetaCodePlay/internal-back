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

    public function allUsersByAgent(int $agent, string $currency, $arrayIds = false)
    {
        $arrayUsers = DB::select("SELECT au.user_id,u.username from site.agent_user as au
                          inner join site.users as u on u.id = au.user_id
                             where au.agent_id in (WITH RECURSIVE all_agents AS (
                                SELECT agents.id, agents.user_id, owner_id
                                FROM site.agents as agents
                                         join site.agent_currencies as agent_currencies on agents.id = agent_currencies.agent_id
                                WHERE agents.user_id = {$agent}
                                  AND currency_iso = '{$currency}'

                                UNION ALL

                                SELECT agents.id, agents.user_id, agents.owner_id
                                FROM site.agents as agents
                                         join site.agent_currencies as agent_currencies on agents.id = agent_currencies.agent_id
                                         JOIN all_agents ON agents.owner_id = all_agents.user_id
                                where agent_currencies.currency_iso  = '{$currency}'
                            )
                            SELECT all_agents.id
                            FROM all_agents)
                    ");

        if ($arrayIds) {
            $array = [];
            foreach ($arrayUsers as $myId) {
                $array[] = $myId->user_id;
            }
            $arrayUsers = $array;
        }

        return $arrayUsers;
    }

    public function closuresTotalsByProviders($whitelabel, $startDate, $endDate, $currency)
    {
        //FAKE
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

    public function dataUser($id)
    {
        $userArray = [
            'username' => '',
            'type_user' => ''
        ];
        $sql = DB::select('select type_user,username from site.users where id = ?', [$id]);
        if (isset($sql[0]->type_user)) {
            $userArray = [
                'username' => $sql[0]->username,
                'type_user' => $sql[0]->type_user
            ];
        }

        return json_decode(json_encode($userArray, true));


    }

    public function getClosureByGroupTotals($startDate, $endDate, $whitelabel, $currency_iso, $arrayUsers, $fieldGroup)
    {
        if (in_array(Roles::$super_admin, session('roles'))) {

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

    public function myAgents(int $user, string $currency, int $whitelabel)
    {
        return DB::select("(SELECT a.user_id, a.percentage, u.username, u.type_user
                    FROM site.agents a
                    INNER JOIN site.users u ON a.user_id=u.id
                    INNER JOIN site.user_currencies uc ON uc.user_id=u.id
                    WHERE a.owner_id='{$user}'
                     and u.whitelabel_id = {$whitelabel}
                     and uc.currency_iso = '{$currency}'
                    ) ORDER BY type_user ASC, username");

    }

    public function myUsersAndAgents(int $user, string $currency, int $whitelabel)
    {
        return DB::select('(SELECT a.user_id,a.percentage,u.type_user, u.username
                    FROM site.agents a
                    INNER JOIN site.users u ON a.user_id=u.id
                    INNER JOIN site.user_currencies uc ON uc.user_id=u.id
                    WHERE a.owner_id= ?
                     and u.whitelabel_id = ?
                     and uc.currency_iso = ?
                    )
                    UNION
                    (SELECT au.user_id,null,u.type_user, u.username
                    FROM site.agent_user au
                    INNER JOIN site.users u ON au.user_id=u.id
                    WHERE au.agent_id =
                    (
                        SELECT a.id FROM site.agents a
                        INNER JOIN site.agent_currencies ac ON ac.agent_id=a.id
                        WHERE a.user_id = ? and ac.currency_iso = ?
                    )
                     and u.whitelabel_id = ?
                    )
                    ORDER BY type_user,username ASC', [$user, $whitelabel, $currency, $user, $currency, $whitelabel]);

    }

    public function nameProvider($id)
    {
        $provider_name = null;
        $sql = DB::select('select site.providers.name from site.providers where id = ?', [$id]);
        if (isset($sql[0]->name)) {
            $provider_name = $sql[0]->name;
        }

        if (is_null($provider_name)) {
            switch ($id) {
                case 171:
                {
                    $provider_name = 'Bet Connections';
                    break;
                }
                default:
                {
                    $provider_name = 'Sin definir';
                    break;
                }
            }
        }

        return $provider_name;


    }

}

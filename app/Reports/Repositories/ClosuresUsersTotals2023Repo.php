<?php


namespace App\Reports\Repositories;

use Dotworkers\Configurations\Configurations;
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

    /**
     * @param int $agent
     * @param string $currency
     * @param $arrayIds
     * @return array
     */
    public function allUsersByAgent(int $agent, string $currency, $arrayIds = false)
    {
        $arrayUsers = DB::select("SELECT au.user_id,u.username from site.agent_user as au
                          inner join site.users as u on u.id = au.user_id
                             where au.agent_id in (WITH RECURSIVE all_agents AS (
                                SELECT agents.id, agents.user_id, ownerId
                                FROM site.agents as agents
                                         join site.agent_currencies as agent_currencies on agents.id = agent_currencies.agent_id
                                WHERE agents.user_id = {$agent}
                                  AND currency_iso = '{$currency}'

                                UNION ALL

                                SELECT agents.id, agents.user_id, agents.ownerId
                                FROM site.agents as agents
                                         join site.agent_currencies as agent_currencies on agents.id = agent_currencies.agent_id
                                         JOIN all_agents ON agents.ownerId = all_agents.user_id
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

    /**
     * @param int $whitelabel Whitelabel Id
     * @param $startDate
     * @param $endDate
     * @param $currency
     * @return mixed
     */
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

    /**
     * @param $id
     * @return mixed
     */
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

    /**
     * @param int $whitelabel Whitelabel Id
     * @param string $currency
     * @return array
     */
    public function getAgentsByWhitelabelAndCurrency(int $whitelabel, string $currency)
    {
        return DB::select('SELECT * FROM site.get_agents_by_whitelabel_and_currency(?,?)', [$whitelabel, $currency]);
    }

    /**
     * @param string $currency Iso Currency
     * @param string $startDate Date Start
     * @param string $endDate Date End
     * @param int $whitelabel Whitelabel Id
     * @param $arrayUsers
     * @param $fieldGroup
     * @return array
     */
    public function getClosureByGroupTotals($startDate, $endDate, $whitelabel, $currency, $arrayUsers, $fieldGroup)
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
            WHERE site.closures_users_totals_2023.whitelabel_id = {$whitelabel}
            AND site.closures_users_totals_2023.currency_iso = '{$currency}'
            AND site.closures_users_totals_2023.start_date BETWEEN '{$startDate}' AND '{$endDate}'
            GROUP BY site.closures_users_totals_2023.{$fieldGroup}");

        } else {

            $arrayUsers = implode(',', $arrayUsers);
//dd($arrayUsers);
            $closure = DB::select("SELECT
                site.closures_users_totals_2023.{$fieldGroup},
                SUM (site.closures_users_totals_2023.played) as total_played,
                SUM (site.closures_users_totals_2023.won) as total_won,
                SUM (site.closures_users_totals_2023.bets) as total_bet,
                SUM (site.closures_users_totals_2023.profit) as total_profit,
                SUM (site.closures_users_totals_2023.rtp) as total_rtp
            FROM site.closures_users_totals_2023
            WHERE site.closures_users_totals_2023.whitelabel_id = {$whitelabel}
            AND site.closures_users_totals_2023.currency_iso = '{$currency}'
            AND site.closures_users_totals_2023.user_id IN ({$arrayUsers})
            AND site.closures_users_totals_2023.start_date BETWEEN '{$startDate}' AND '{$endDate}'
            GROUP BY site.closures_users_totals_2023.{$fieldGroup}");
        }

        return $closure;

    }

    /**
     * @param int $whitelabel
     * @param string $currency
     * @param string $startDate
     * @param string $endDate
     * @param $provider
     * @param $username
     * @param $limit
     * @param $page
     * @return array
     */
    public function getClosureTmp(int $whitelabel, string $currency, string $startDate, string $endDate, $provider, $username, $limit, $page)
    {
        return DB::select('SELECT * FROM get_closure_totals_by_provider_and_maker_page(?,?,?,?,?,?,?,?)', [$whitelabel, $currency, $startDate, $endDate, $provider, $username, $limit, $page]);
    }

    /**
     * Get Closures Totals By Agent Group Provider
     *
     * @param int $whitelabel Whitelabel Id
     * @param string $currency Iso Currency
     * @param string $startDate Date Start
     * @param string $endDate Date End
     * @param int $ownerId User Id Owner
     * @return array
     */
    public function getClosureTotalsByAgentGroupProvider(int $whitelabel, string $currency, string $startDate, string $endDate, int $ownerId)
    {
        return DB::select('SELECT * FROM site.get_closure_totals_by_agent_group_provider(?,?,?,?,?)', [$whitelabel, $currency, $startDate, $endDate, $ownerId]);
    }

    /**
     * Get Closures Totals By Provider And Maker
     *
     * @param int $whitelabel Whitelabel Id
     * @param string $currency Iso Currency
     * @param string $startDate Date Start
     * @param string $endDate Date End
     * @param $provider
     * @param $username
     * @return array
     */
    public function getClosureTotalsByProviderAndMaker(int $whitelabel, string $currency, string $startDate, string $endDate, $provider, $username)
    {
        return DB::select('SELECT * FROM site.get_closure_totals_by_provider_and_maker(?,?,?,?,?,?)', [$whitelabel, $currency, $startDate, $endDate, $provider, $username]);
    }

    /**
     * Get Closures Totals By Provider And Maker Global
     *
     * @param int $whitelabel Whitelabel Id
     * @param string $currency Iso Currency
     * @param string $startDate Date Start
     * @param string $endDate Date End
     * @param $provider
     * @return array
     */
    public function getClosureTotalsByProviderAndMakerGlobal(string $startDate, string $endDate, $currency, $provider, $whitelabel)
    {
        return DB::select('SELECT * FROM site.get_closure_totals_by_provider_and_maker_global(?,?,?,?,?)', [$whitelabel, $currency, $startDate, $endDate, $provider]);
    }

    /**
     * @param int $whitelabel Whitelabel Id
     * @param string $currency Iso Currency
     * @param string $startDate Date Start
     * @param string $endDate Date End
     * @param $userId
     * @param $provider
     * @param $username
     * @return array
     */
    public function getClosureTotalsByProviderAndMakerWithSon(int $whitelabel, string $currency, string $startDate, string $endDate, $userId, $provider, $username)
    {
        return DB::select('SELECT * FROM site.get_closure_totals_by_provider_and_maker(?,?,?,?,?,?)', [$whitelabel, $currency, $startDate, $endDate, $provider, $username]);
        //return DB::select('SELECT * FROM site.get_closure_totals_by_provider_and_maker_with_son(?,?,?,?,?,?,?)', [$whitelabel,$currency,$startDate, $endDate,$userId,$provider,$username]);
    }

    /**
     * Get Closures Totals By Provider And Maker Page
     *
     * @param int $whitelabel Whitelabel Id
     * @param string $currency Iso Currency
     * @param string $startDate Date Start
     * @param string $endDate Date End
     * @param $provider
     * @param $username
     * @param $limit
     * @param $page
     * @return array
     */
    public function getClosureTotalsByProviderAndMakerpage(int $whitelabel, string $currency, string $startDate, string $endDate, $provider, $username, $limit, $page)
    {
        return DB::select('SELECT * FROM site.get_closure_totals_by_provider_and_maker_page(?,?,?,?,?,?,?,?)', [$whitelabel, $currency, $startDate, $endDate, $provider, $username, $limit, $page]);
    }

    /**
     * @param int $whitelabel Whitelabel Id
     * @param string $currency Iso Currency
     * @param string $startDate Date Start
     * @param string $endDate Date End
     * @param string $username
     * @return array
     */
    public function getClosureTotalsByUsername(int $whitelabel, string $currency, string $startDate, string $endDate, string $username)
    {
        return DB::select('SELECT * FROM site.get_closure_totals_by_username(?,?,?,?,?)', [$whitelabel, $currency, $startDate, $endDate, $username]);
    }

    /**
     * @param int $whitelabel Whitelabel Id
     * @param string $currency Iso Currency
     * @param string $startDate Date Start
     * @param string $endDate Date End
     * @param string $username
     * @param int $ownerId
     * @return array
     */
    public function getClosureTotalsByUsernameWithSon(int $whitelabel, string $currency, string $startDate, string $endDate, string $username, int $ownerId)
    {
        return DB::select('SELECT * FROM site.get_closure_totals_by_username_with_son(?,?,?,?,?,?)', [$whitelabel, $currency, $startDate, $endDate, $username, $ownerId]);
    }

    /**
     * Closure Totals hour by username
     * @param int $whitelabel Whitelabel Id
     * @param string $currency Iso Currency
     * @param string $startDate Date Start
     * @param string $endDate Date End
     * @param string $username
     * @param int $ownerId
     * @return array
     */
    public function getClosureTotalsHourByUsernameWithSon(int $whitelabel, string $currency, string $startDate, string $endDate, string $username, int $ownerId)
    {
        return DB::select('SELECT * FROM site.get_closure_totals_by_username_with_son(?,?,?,?,?,?)', [$whitelabel, $currency, $startDate, $endDate, $username, $ownerId]);
    }

    /**
     * @param int $whitelabel Whitelabel Id
     * @param string $currency Iso Currency
     * @param string $startDate Date Start
     * @param string $endDate Date End
     * @return array
     */
    public function getClosureTotalsByWhitelabel(int $whitelabel, string $currency, string $startDate, string $endDate)
    {
        return DB::select('SELECT * FROM site.get_closure_totals_by_whitelabel(?,?,?,?)', [$whitelabel, $currency, $startDate, $endDate]);
    }

    /**
     * @param int $whitelabel Whitelabel Id
     * @param string $currency Iso Currency
     * @param string $startDate Date Start
     * @param string $endDate Date End
     * @return array
     */
    public function getClosureTotalsByWhitelabelAndProviders(int $whitelabel, string $currency, string $startDate, string $endDate)
    {
        return DB::select('SELECT * FROM site.get_closure_totals_by_whitelabel_and_providers(?,?,?,?)', [$whitelabel, $currency, $startDate, $endDate]);
    }

    /**
     * @param int $whitelabel Whitelabel Id
     * @param string $currency Iso Currency
     * @param string $startDate Date Start
     * @param string $endDate Date End
     * @param int $userId User Id
     * @param array $arrayProvider Array Provider Id
     * @return array
     */
    public function getClosureTotalsByWhitelabelAndProvidersAndUser(int $whitelabel, string $currency, string $startDate, string $endDate, int $userId, string $arrayProvider)
    {
        return DB::select('SELECT * FROM site.get_closure_totals_by_whitelabel_and_providers_and_user(?,?,?,?,?,?)', [$whitelabel, $currency, $startDate, $endDate, $userId, $arrayProvider]);
    }

    /**
     * SCHEMA -> PUBLIC
     * @param int $whitelabel Whitelabel Id
     * @param string $currency Iso Currency
     * @param string $startDate Date Start
     * @param string $endDate Date End
     * @param int $userId User Id
     * @param array $arrayProvider Array Provider Id
     * @return array
     */
    public function getClosureTotalsByWhitelabelAndProvidersAndUserHour(int $whitelabel, string $currency, string $startDate, string $endDate, int $userId, string $arrayProvider)
    {
        return DB::select('SELECT * FROM public.get_closure_totals_hour_by_whitelabel_and_providers_and_user(?,?,?,?,?,?)', [$whitelabel, $currency, $startDate, $endDate, $userId, $arrayProvider]);
    }

    /**
     * FOR ADMIN STATE FINANCIAL
     * @param int $whitelabel Whitelabel Id
     * @param string $currency Iso Currency
     * @param string $startDate Date Start
     * @param string $endDate Date End
     * @param int $ownerId User Id Owner
     * @param array $arrayProvider Array Provider Id
     * @return array
     */
    public function getClosureTotalsByWhitelabelAndProvidersWithSon(int $whitelabel, string $currency, string $startDate, string $endDate, int $ownerId, string $arrayProvider)
    {
        return DB::select('SELECT * FROM site.get_closure_totals_by_whitelabel_and_providers_with_son(?,?,?,?,?,?)', [$whitelabel, $currency, $startDate, $endDate, $ownerId, $arrayProvider]);
    }

    /**
     * SCHEMA -> PUBLIC
     * FOR ADMIN STATE FINANCIAL FOR HOUR
     * @param int $whitelabel Whitelabel Id
     * @param string $currency Iso Currency
     * @param string $startDate Date Start
     * @param string $endDate Date End
     * @param int $ownerId User Id Owner
     * @param array $arrayProvider Array Provider Id
     * @return array
     */
    public function getClosureTotalsByWhitelabelAndProvidersWithSonHour(int $whitelabel, string $currency, string $startDate, string $endDate, int $ownerId, string $arrayProvider)
    {
        return DB::select('SELECT * FROM public.get_closure_totals_hour_by_whitelabel_and_providers_with_son(?,?,?,?,?,?)', [$whitelabel, $currency, $startDate, $endDate, $ownerId, $arrayProvider]);
    }


    /** FOR AGENT STATE FINANCIAL
     * @param int $whitelabel Whitelabel Id
     * @param string $currency Iso Currency
     * @param string $startDate Date Start
     * @param string $endDate Date End
     * @param int $ownerId User Id Owner
     * @return array
     */
    public function getClosureTotalsByWhitelabelWithSon(int $whitelabel, string $currency, string $startDate, string $endDate, int $ownerId)
    {
        return DB::select('SELECT * FROM site.get_closure_totals_by_whitelabel_with_son(?,?,?,?,?)', [$whitelabel, $currency, $startDate, $endDate, $ownerId]);
    }

    /**
     * SCHEMA -> PUBLIC
     * FOR AGENT STATE FINANCIAL HOUR
     * @param int $whitelabel Whitelabel Id
     * @param string $currency Iso Currency
     * @param string $startDate Date Start
     * @param string $endDate Date End
     * @param int $ownerId User Id Owner
     * @return array
     */
    public function getClosureTotalsByWhitelabelWithSonHour(int $whitelabel, string $currency, string $startDate, string $endDate, int $ownerId)
    {
        return DB::select('SELECT * FROM public.get_closure_totals_hour_by_whitelabel_with_son(?,?,?,?,?)', [$whitelabel, $currency, $startDate, $endDate, $ownerId]);
    }

    /**
     * @param int $whitelabel Whitelabel Id
     * @param string $currency Iso Currency
     * @param string $startDate Date Start
     * @param string $endDate Date End
     * @param int $limit Cant Items by Register
     * @return array
     */
    public function getClosureTotalsLimit(int $whitelabel, string $currency, string $startDate, string $endDate, int $limit = 1000)
    {
        return DB::select('SELECT * FROM site.get_closure_totals_limit(?,?,?,?,?)', [$whitelabel, $currency, $startDate, $endDate, $limit]);
    }

    /**
     * @param int $whitelabel Whitelabel Id
     * @param string $currency Iso Currency
     * @param string $startDate Date Start
     * @param string $endDate Date End
     * @param int $ownerId User Id Owner
     * @return array
     */
    public function getClosureTotalsWithSon(int $whitelabel, string $currency, string $startDate, string $endDate, int $ownerId)
    {
        return DB::select('SELECT * FROM site.get_closure_totals_with_son(?,?,?,?,?)', [$whitelabel, $currency, $startDate, $endDate, $ownerId]);
    }

    /**
     * Closure total hour swith son
     * @param int $whitelabel Whitelabel Id
     * @param string $currency Iso Currency
     * @param string $startDate Date Start
     * @param string $endDate Date End
     * @param int $ownerId User Id Owner
     * @return array
     */
    public function getClosureTotalsHourWithSon(int $whitelabel, string $currency, string $startDate, string $endDate, int $ownerId)
    {
        return DB::select('SELECT * FROM site.get_closure_totals_with_son(?,?,?,?,?)', [$whitelabel, $currency, $startDate, $endDate, $ownerId]);
    }

    /**
     * @param int $ownerId User Id Owner
     * @param int $whitelabel Whitelabel Id
     * @param string $currency Iso Currency
     * @return array
     */
    public function getMyAgentsSonByWhitelabelAndCurrency(int $ownerId, int $whitelabel, string $currency)
    {
        return DB::select('SELECT * FROM site.get_my_agents_son_by_whitelabel_and_currency(?,?,?)', [$ownerId, $whitelabel, $currency]);
    }

    /**
     * Get Provider Active The Client And Credentials
     * @param bool $active Status By Providers
     * @param int $whitelabel Whitelabel Id
     * @param string $currency Iso Currency
     * @return array
     */
    public function getProvidersActiveByCredentials(bool $active, string $currency, int $whitelabel)
    {
        return DB::select('select p.id,p.name from site.providers p
                            inner join site.credentials c on c.provider_id = p.id
                            where c.currency_iso = ?
                            --AND c.status = ?
                            AND c.client_id = ?
                            order by p.id desc ', [$currency, $whitelabel]);
    }

    /**
     * Get Provider Active The Client And Credentials
     * @param bool $active Status By Providers
     * @param int $whitelabel Whitelabel Id
     * @param string $currency Iso Currency
     * @return array
     */
    public function getProvidersByCurrencyAndCredentials(bool $active, $currency, $whitelabel)
    {
        return DB::select('SELECT * FROM site.get_providers(?,?,?)', [$currency, $active, $whitelabel]);
    }

    /**
     * Get Total Closure Payments By Owner
     * @param int $whitelabel Whitelabel Id
     * @param string $currency Iso Currency
     * @param string $startDate Date Start
     * @param string $endDate Date End
     * @param int $ownerId User Id Owner
     * @param string $arrayProvider Array Provider Id
     * @return array
     */
    public function getTotalsClosurePaymentsByOwner(int $whitelabel, string $currency, string $startDate, string $endDate, int $ownerId,string $arrayProvider)
    {
        return DB::select('SELECT * FROM site.get_totals_closure_payments_owner(?,?,?,?,?,?)', [$whitelabel, $currency, $startDate, $endDate, $ownerId,$arrayProvider]);
    }

    /**
     * Get Total Closure Payments By User
     * @param int $whitelabel Whitelabel Id
     * @param string $currency Iso Currency
     * @param string $startDate Date Start
     * @param string $endDate Date End
     * @param int $user User Id
     * @param string $arrayProvider Array Provider Id
     * @return array
     */
    public function getTotalsClosurePaymentsByUser(int $whitelabel, string $currency, string $startDate, string $endDate, int $user,string $arrayProvider)
    {
        return DB::select('SELECT * FROM site.get_totals_closure_payments_user(?,?,?,?,?,?)', [$whitelabel, $currency, $startDate, $endDate, $user,$arrayProvider]);
    }

    /**
     * @param int $whitelabel Whitelabel Id
     * @param string $currency Iso Currency
     * @param int $ownerId User Id Owner
     * @return array
     */
    public function getUsersAgentsSon(int $whitelabel, string $currency, int $ownerId)
    {
        return DB::select('SELECT * FROM site.get_users_agents_son(?,?,?)', [$ownerId, $currency, $whitelabel]);
    }

    /**
     * @param int $user User Id
     * @param string $currency Iso Currency
     * @param int $whitelabel Whitelabel Id
     * @return array
     */
    public function myAgents(int $user, string $currency, int $whitelabel)
    {
        return DB::select("(SELECT a.user_id, a.percentage, u.username, u.type_user
                    FROM site.agents a
                    INNER JOIN site.users u ON a.user_id=u.id
                    INNER JOIN site.user_currencies uc ON uc.user_id=u.id
                    WHERE a.ownerId='{$user}'
                     and u.whitelabel_id = {$whitelabel}
                     and uc.currency_iso = '{$currency}'
                    ) ORDER BY type_user ASC, username");

    }

    /**
     * @param int $user User Id
     * @param string $currency Iso Currency
     * @param int $whitelabel Whitelabel Id
     * @return array
     */
    public function myUsersAndAgents(int $user, string $currency, int $whitelabel)
    {
        return DB::select('(SELECT a.user_id,a.percentage,u.type_user, u.username
                    FROM site.agents a
                    INNER JOIN site.users u ON a.user_id=u.id
                    INNER JOIN site.user_currencies uc ON uc.user_id=u.id
                    WHERE a.ownerId= ?
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

    /**
     * @param int $idProvider Provider Id
     * @return string|null
     */
    public function nameProvider($idProvider)
    {
        $provider_name = null;
        $sql = DB::select('select site.providers.name from site.providers where id = ?', [$idProvider]);
        if (isset($sql[0]->name)) {
            $provider_name = $sql[0]->name;
        }

        if (is_null($provider_name)) {
            switch ($idProvider) {
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

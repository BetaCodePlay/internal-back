<?php

namespace App\Agents\Repositories;

use App\Agents\Entities\Agent;
use App\Users\Entities\User;
use App\Users\Enums\ActionUser;
use App\Users\Enums\TypeUser;
use Dotworkers\Security\Enums\Roles;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Class AgentsRepo
 *
 * This class allows to interact with Agent entity
 *
 * @package App\Agents\Repositories
 * @author  Eborio Linarez
 */
class AgentsRepo
{
    /**
     * Add user to agent
     *
     * @param int $agent Agent data
     * @param int $user User ID
     * @return mixed
     */
    public function addUser($agent, $user)
    {
        $agent = Agent::find($agent);
        $agent->users()->attach($user);
        return $agent;
    }

    /**
     * Balance Current by user ID and currency
     *
     * @param int $user User ID
     * @param string $currency Currency ISO
     * @return Builder|Model|object|null
     */
    public function balanceCurrentAgent(int $user, string $currency)
    {
        return Agent::select('agent_currencies.balance')
            ->join('agent_currencies', 'agents.id', '=', 'agent_currencies.agent_id')
            ->where('agent_currencies.currency_iso', $currency)
            ->where('agents.user_id', $user)
            ->first();
    }

    /**
     * Block agents and users
     *
     * @param array $agents Agent data
     * @return mixed
     */
    public function blockAgents($agents)
    {
        $agents = \DB::table('exclude_providers_users')->insertOrIgnore($agents);
        return $agents;
    }

    /**
     * Block agents and users
     *
     * @param array $agents Agent data
     * @return mixed
     */
    public function blockAgentsMakers($agents)
    {
        $agents = \DB::table('exclude_makers_users')->insertOrIgnore($agents);
        return $agents;
    }

    /**
     * Update Block agents and users
     *
     * @param array $agents Agent data
     * @return mixed
     */
    public function updateBlockAgents($currency, $category, $user, $agents)
    {
        $agents = \DB::table('exclude_makers_users')->updateOrInsert(
            ['category' => $category, 'user_id' => $user, 'currency_iso' => $currency],
            $agents
        );
        return $agents;
    }

    /**
     * Update unBlock agents and makers
     *
     * @param array $agents Agent data
     * @return mixed
     */
    public function unBlockAgentsMaker($currency, $category, $user, $data)
    {
        $agents = \DB::table('exclude_makers_users')->where('currency_iso', $currency)->where('category', $category)->where('user_id', $user)->update($data);
        return $agents;
    }

    /**
     * Block users
     *
     * @param int $user User ID
     * @return mixed
     */
    public function blockUsers($userId)
    {
        $user = User::find($userId);
        $user->status = false;
        $user->action = ActionUser::$blocked_branch;
        $user->save();
    }

    /**
     * Exists agent
     *
     * @param int $user User ID
     * @return mixed
     */
    public function existAgent(int $user)
    {
        return Agent::on('replica')
            ->where('user_id', $user)
            ->first();
    }

    /**
     * Exists user
     *
     * @param int $user User ID
     * @return mixed
     */
    public function existsUser(int $user)
    {
        $agentUser = Agent::on('replica')
            ->select('agents.user_id', 'users.username')
            ->join('agent_user', 'agent_user.agent_id', '=', 'agents.id')
            ->join('users', 'agents.user_id', '=', 'users.id')
            ->where('agent_user.user_id', $user)
            ->first();

        if (is_null($agentUser)) {
            $agentUser = Agent::on('replica')
                ->select('agents.owner_id as user_id', 'users.username')
                ->join('users', 'agents.owner_id', '=', 'users.id')
                ->where('agents.user_id', $user)
                ->first();
        }
        return $agentUser;
    }

    /**
     * Find admin agent by currency and whitelabel
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $currency Currency ISO
     * @return mixed
     */
    public function findAdminAgent($whitelabel, $currency)
    {
        $agent = Agent::on('replica')
            ->select('users.id', 'users.username', 'users.status', 'profiles.timezone', 'agents.id AS agent', 'users.referral_code',
                'agents.master', 'agents.owner_id as owner', 'profiles.country_iso', 'agent_currencies.balance', 'agent_currencies.currency_iso')
            ->join('agent_currencies', 'agents.id', '=', 'agent_currencies.agent_id')
            ->join('users', 'agents.user_id', '=', 'users.id')
            ->join('profiles', 'users.id', '=', 'profiles.user_id')
            ->where('agent_currencies.currency_iso', $currency)
            ->where('users.username', 'admin')
            ->where('users.whitelabel_id', $whitelabel)
            ->first();
        return $agent;
    }

    /**
     * Find by ID
     *
     * @param int $agent User ID
     * @return mixed
     */
    public function findAgentCashier($agent)
    {
        return Agent::find($agent);
    }

    /**
     * Find by user ID and currency
     *
     * @param int $user User ID
     * @param string $currency Currency ISO
     * @return Builder|Model|object|null
     */
    public function findByUserIdAndCurrency(int $user, string $currency)
    {
        return Agent::on('replica')
            ->select('users.id', 'users.created_at as created', 'users.email', 'users.username', 'users.status', 'users.action', 'profiles.timezone', 'agents.id AS agent', 'users.referral_code',
                'agents.master', 'agents.owner_id as owner', 'profiles.country_iso', 'agent_currencies.balance', 'agent_currencies.currency_iso')
            ->join('agent_currencies', 'agents.id', '=', 'agent_currencies.agent_id')
            ->join('users', 'agents.user_id', '=', 'users.id')
            ->join('profiles', 'users.id', '=', 'profiles.user_id')
            ->where('agent_currencies.currency_iso', $currency)
            ->where('users.id', $user)
            ->first();
    }

    public function statusActionByUser(int $user)
    {
        return User::select('username','status', 'action', 'type_user')
            ->where('id', $user)
            ->first();
    }

    /** get and update balance by agent
     * @param string $currency Curency Iso
     * @param int $userDebit User Id Debit
     * @param int $userCredit User Id Credit
     * @param float $amount Amount of the operation
     * @param int $idWolf id User
     * @return array
     */
    public function getAndUpdateBalance($currency, $userDebit, $userCredit, $amount,$idWolf)
    {

        $result = DB::select('SELECT * FROM site.get_and_update_balance_for_agent(?,?,?,?,?)', [$currency, $userDebit, $userCredit, $amount,$idWolf]);
        return $result;
    }

    /**
     * Fin user profile
     * @param int $user User Id
     * @param string $currency Currency ISO
     * @return mixed
     */
    public function findUserProfile(int $user, string $currency)
    {
        $sql = DB::select('SELECT u.id,u.id AS user_id,u.created_at AS created,u.email,u.username,u.status,u.action,p.timezone,a.id AS agent,u.referral_code,a.master,a.owner_id AS owner,
                           p.country_iso,ac.balance,ac.currency_iso
                    FROM site.agents a
                      INNER JOIN site.agent_currencies ac ON a.id = ac.agent_id
                      INNER JOIN site.users u ON a.user_id = u.id
                      INNER JOIN site.profiles p ON u.id = p.user_id
                    WHERE ac.currency_iso = ?
                      and u.id = ? LIMIT 1', [$currency, $user]);

        return isset($sql[0]->id) ? $sql[0] : null;
    }

    /**
     * Find user
     *
     * @param int $user User ID
     * @return mixed
     */
    public function findUser($user)
    {
        $user = Agent::on('replica')
            ->select('users.id', 'users.created_at as created', 'users.email', 'users.username', 'users.status', 'users.action', 'profiles.timezone', 'agents.user_id as owner_id', 'agent_user.agent_id as owner', 'users.referral_code')
            ->join('agent_user', 'agents.id', '=', 'agent_user.agent_id')
            ->join('users', 'agent_user.user_id', '=', 'users.id')
            ->join('profiles', 'users.id', '=', 'profiles.user_id')
            ->where('agent_user.user_id', $user)
            ->first();
        return $user;
    }

    /**
     * get agent lock by provider
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $currency Currency ISO
     * @param string $startDate Start date to filter
     * @param string $endDate End date to filter
     * @param int $provider Provider ID
     * @return mixed
     */
    public function getAgentLockByProvider($currency, $provider, $whitelabel)
    {
        $agents = Agent::select('agents.user_id', 'users.username', 'exclude_providers_users.provider_id', 'exclude_providers_users.makers', 'exclude_providers_users.created_at', 'providers.name')
            ->join('users', 'agents.user_id', '=', 'users.id')
            ->join('exclude_providers_users', 'users.id', '=', 'exclude_providers_users.user_id')
            ->join('providers', 'exclude_providers_users.provider_id', '=', 'providers.id')
            ->where('exclude_providers_users.currency_iso', $currency)
            ->where('users.whitelabel_id', $whitelabel);

        if (!empty($provider)) {
            $agents->where('exclude_providers_users.provider_id', $provider);
        }

        $data = $agents->get();
        return $data;
    }

    /**
     * get agent lock by user
     *
     * @param int $user User ID
     * @param int $whitelabel Whitelabel ID
     * @param string $currency Currency ISO
     * @return mixed
     */
    public function getAgentLockByUserAndCategory($user, $currency, $category, $whitelabel)
    {
        $agents = Agent::select('agents.user_id', 'users.username', 'exclude_makers_users.makers', 'exclude_makers_users.category')
            ->join('users', 'agents.user_id', '=', 'users.id')
            ->join('exclude_makers_users', 'users.id', '=', 'exclude_makers_users.user_id')
            ->where('exclude_makers_users.user_id', $user)
            ->where('exclude_makers_users.category', $category)
            ->where('exclude_makers_users.currency_iso', $currency)
            ->where('users.whitelabel_id', $whitelabel)
            ->first();
        return $agents;
    }

    /**
     * Get agents by owner
     *
     * @param int $owner Owner ID
     * @param string $currency Currency ISO
     * @return mixed
     */
    public function getAgentsByOwner($owner, $currency)
    {
        return Agent::on('replica')
            ->select('agents.*', 'users.username', 'agent_currencies.balance', 'users.referral_code', 'users.status')
            ->join('users', 'agents.user_id', '=', 'users.id')
            ->join('agent_currencies', 'agents.id', '=', 'agent_currencies.agent_id')
            ->where('agents.owner_id', $owner)
            ->where('agent_currencies.currency_iso', $currency)
            ->whitelabel()
            ->orderBy('users.username', 'ASC')
            ->get();
    }

    /**
     * Get agents all by owner
     *
     * @param int $owner Owner ID
     * @param string $currency Currency ISO
     * @param int $whitelabel Whitelabel ID
     * @return mixed
     */
    public function getAgentsAllByOwner(int $owner, string $currency, int $whitelabel)
    {
        return DB::select('SELECT u.id,u.id AS user_id,u.username FROM site.users AS u
                   WHERE u.whitelabel_id= ? AND
                    u.id IN (WITH RECURSIVE all_agents AS (
                        SELECT agents.user_id
                        FROM site.agents AS agents
                        JOIN site.agent_currencies AS agent_currencies ON agents.id = agent_currencies.agent_id
                        WHERE agents.user_id = ?
                        AND currency_iso = ?
                        UNION ALL
                        SELECT agents.user_id
                        FROM site.agents AS agents
                        JOIN site.agent_currencies AS agent_currencies ON agents.id = agent_currencies.agent_id
                        JOIN all_agents ON agents.owner_id = all_agents.user_id
                        WHERE agent_currencies.currency_iso  = ?
                    ) SELECT user_id FROM all_agents) ORDER BY username ASC', [$whitelabel, $owner, $currency, $currency]);
    }

    /**
     * Get agents children by owner
     *
     * @param int $owner Owner ID
     * @param string $currency Currency ISO
     * @return mixed
     */
    public function getAgentsChildrenByOwner($owner, $currency)
    {
        return Agent::select('agents.user_id', 'users.username')
            ->join('users', 'agents.user_id', '=', 'users.id')
            ->join('agent_currencies', 'agents.id', '=', 'agent_currencies.agent_id')
            ->where('agents.owner_id', $owner)
            ->where('agent_currencies.currency_iso', $currency)
            ->whitelabel()
            ->orderBy('users.username', 'ASC')
            ->get();
    }

    /**
     * Format Json Tree V1.0
     * Get user and agents son (first generation)
     * @param int $owner Owner ID
     * @param string $currency Currency ISO
     * @param int $whitelabel Whitelabel ID
     * @return mixed
     */
    public function getChildrenByOwner(int $owner, string $currency, int $whitelabel)
    {
        $response = DB::select('SELECT * FROM site.get_users_agents_son(?,?,?)', [$owner, $currency, $whitelabel]);
        $treeItem = [];
        foreach ($response as $item => $value) {

            $icon = $value->type_user == 1 ? 'star' : ($value->type_user == 2 ? 'users' : 'user');
            $type = $value->type_user == 5 ? 'user' : 'agent';
            $datTmp = [
                'id' => $value->user_id,
                'text' => $value->username,
                'status' => $value->status,
                'icon' => "fa fa-{$icon}",
                'li_attr' => [
                    'data_type' => $type,
                    'class' => 'init_' . $type
                ],
                //'children'=>[]
            ];
            if (in_array($value->type_user, [TypeUser::$agentMater, TypeUser::$agentCajero])) {
                $datTmp['children'] = $this->getChildrenByOwner($value->user_id, $currency, $whitelabel);
            }
            $treeItem[] = $datTmp;
        }

        return $treeItem;

    }

    /**
     * Find Agent (not admin)
     * @param int $user User ID
     * @param string $currency Currency ISO
     * @param int $whitelabel Whitelabel ID
     * @return mixed
     */
    public function findAgent(int $user, int $whitelabel)
    {
        $response = DB::select('select u.id as id_agent
                    from site.users u inner join site.role_user rl on u.id = rl.user_id where rl.role_id = ? and u.username != ? and u.whitelabel_id = ? and u.id = ?;', [Roles::$admin_Beet_sweet, 'admin', $whitelabel, $user]);

        return (int)isset($response[0]->id_agent);

    }

    /**
     * Get Tree Sql Levels
     * @param int $user User Id
     * @return array
     */
    public function getTreeSqlLevels(int $user, string $currency, int $whitelabel)
    {
        return DB::select('WITH RECURSIVE all_agents AS (
                      SELECT agents.id, agents.user_id, agents.owner_id,0 AS level
                      FROM site.agents AS agents
                      WHERE agents.user_id = ?
                      UNION
                      SELECT agents.id, agents.user_id, agents.owner_id,level+1 AS level
                      FROM site.agents AS agents
                      JOIN all_agents ON agents.owner_id = all_agents.user_id
                      )

                    SELECT u.id, a.id as id_agent, u.username, a.owner_id, u.type_user, ac.currency_iso as currency, u.status, level
                        FROM site.users u
                        INNER JOIN  all_agents a on u.id=a.user_id
                        INNER JOIN site.agent_currencies AS ac ON a.id = ac.agent_id
                        WHERE u.whitelabel_id = ?
                        AND ac.currency_iso = ?
                    UNION
                        SELECT u.id,a.id as id_agent,u.username, a.user_id, u.type_user, ac.currency_iso as currency,u.status, level+1 as level FROM site.agent_user AS au
                        INNER JOIN site.users AS u ON u.id = au.user_id
                        INNER JOIN  all_agents  a on au.agent_id=a.id
                        INNER JOIN site.agent_currencies AS ac ON a.id = ac.agent_id
                        WHERE u.whitelabel_id = ?
                        AND ac.currency_iso = ?
                        ORDER BY type_user,username', [$user, $whitelabel, $currency, $whitelabel, $currency]);
    }

    /**
     * Get agents dependency
     *
     * @param int $user Agent user ID
     * @param string $currency Currency ISO
     * @return mixed
     */
    public function getAgentsDependency($user, $currency)
    {
        return \DB::connection('replica')
            ->select("WITH RECURSIVE all_agents AS (
            SELECT agents.id, agents.user_id, users.username, owner_id
            FROM agents
                     join users on agents.user_id = users.id
                     join agent_currencies on agents.id = agent_currencies.agent_id
            WHERE agents.user_id = ?
              AND currency_iso = ?

            UNION ALL

            SELECT agents.id, agents.user_id, users.username, agents.owner_id
            FROM agents
                     join users on agents.user_id = users.id
                     join agent_currencies on agents.id = agent_currencies.agent_id
                     JOIN all_agents ON agents.owner_id = all_agents.user_id
            where agent_currencies.currency_iso = ?
        )

        SELECT *
        FROM all_agents", [$user, $currency, $currency]);
    }

    /**
     * Get exclude user provider
     *
     * @param int $user User ID
     * @return mixed
     */
    public function getExcludeUserProvider($user)
    {
        $data = User::select('exclude_providers_users.provider_id', 'exclude_providers_users.makers', 'exclude_providers_users.currency_iso')
            ->join('exclude_providers_users', 'users.id', '=', 'exclude_providers_users.user_id')
            ->where('exclude_providers_users.user_id', $user)
            ->get();
        return $data;
    }

    /**
     * Get user blocked status
     *
     * @param int $user User ID
     * @return mixed
     */
    public function getUserBlocked($user)
    {
        $data = User::find($user);//where('id', $user)->where('status', false)->get();
        return $data;
    }

    /**
     * Get exclude user maker
     *
     * @param int $user User ID
     * @return mixed
     */
    public function getExcludeUserMaker($user)
    {
        $data = User::select('exclude_makers_users.category', 'exclude_makers_users.makers', 'exclude_makers_users.currency_iso')
            ->join('exclude_makers_users', 'users.id', '=', 'exclude_makers_users.user_id')
            ->where('exclude_makers_users.user_id', $user)
            ->get();
        return $data;
    }

    /**
     * Get searcg agents by owner
     *
     * @param var $username Username
     * @param string $currency Currency ISO
     * @param int $owner Owner ID
     * @return mixed
     */
    public function getSearchAgentsByOwner($currency, $owner, $whitelabel)
    {
        $agents = Agent::on('replica')
            ->select('agents.*', 'users.username', 'agent_currencies.balance', 'users.referral_code')
            ->join('users', 'agents.user_id', '=', 'users.id')
            ->join('agent_currencies', 'agents.id', '=', 'agent_currencies.agent_id')
            ->where('agents.owner_id', $owner)
            ->where('users.whitelabel_id', $whitelabel)
            ->where('agent_currencies.currency_iso', $currency)
            ->orderBy('users.username', 'ASC')
            ->get();

        return $agents;
    }

    /**
     * Get search users by agent
     *
     * @param int $agent Agent ID
     * @param string $currency Currency ISO
     * @return mixed
     */
    public function getSearchUsersByAgent($currency, $agent, $whitelabel)
    {
        $users = Agent::on('replica')
            ->select('users.id', 'users.username', 'users.status', 'profiles.timezone', 'agent_user.agent_id as owner', 'users.referral_code')
            ->join('agent_user', 'agents.id', '=', 'agent_user.agent_id')
            ->join('users', 'agent_user.user_id', '=', 'users.id')
            ->join('user_currencies', 'users.id', '=', 'user_currencies.user_id')
            ->join('profiles', 'users.id', '=', 'profiles.user_id')
            ->where('agents.id', $agent)
            ->where('user_currencies.currency_iso', $currency)
            ->where('users.whitelabel_id', $whitelabel)
            ->orderBy('users.username', 'ASC')
            ->get();
        return $users;
    }

    /**
     * Get users by agent
     *
     * @param int $agent Agent ID
     * @param string $currency Currency ISO¡
     * @return mixed
     */
    public function getUsersByAgent($agent, $currency)
    {
        return Agent::on('replica')
            ->select('users.id', 'users.username', 'users.status', 'profiles.timezone', 'agent_user.agent_id as owner', 'users.referral_code')
            ->join('agent_user', 'agents.id', '=', 'agent_user.agent_id')
            ->join('users', 'agent_user.user_id', '=', 'users.id')
            ->join('user_currencies', 'users.id', '=', 'user_currencies.user_id')
            ->join('profiles', 'users.id', '=', 'profiles.user_id')
            ->where('user_currencies.currency_iso', $currency)
            ->where('agents.id', $agent)
            ->whitelabel()
            ->orderBy('users.username', 'ASC')
            ->get();
    }

    /**
     * Get users by agent
     *
     * @param array $agents Agents ID
     * @param string $currency Currency ISO
     * @return mixed
     */
    public function getUsersByAgents($agents, $currency)
    {
        return Agent::on('replica')
            ->select('users.id', 'users.username', 'users.status', 'profiles.timezone', 'agent_user.agent_id as owner', 'users.referral_code')
            ->join('agent_user', 'agents.id', '=', 'agent_user.agent_id')
            ->join('users', 'agent_user.user_id', '=', 'users.id')
            ->join('user_currencies', 'users.id', '=', 'user_currencies.user_id')
            ->join('profiles', 'users.id', '=', 'profiles.user_id')
            ->where('user_currencies.currency_iso', $currency)
            ->whereIn('agents.id', $agents)
            ->whitelabel()
            ->get();
    }

    public function iAgent($user)
    {
        $iAgent = DB::select('SELECT a.percentage
                 FROM site.agents a
                 WHERE user_id = ?', [$user]);
        return $iAgent[0];
    }

    /**
     * Move agent from user
     *
     * @param int $agent Agent ID
     * @param array $userAgent Agent data
     * @return mixed
     */
    public function moveAgentFromUser($agent, $userAgent)
    {
        $data = \DB::table('agent_user')
            ->where('user_id', $userAgent)
            ->update(['agent_id' => $agent->id]);

        return $data;
    }

    /**
     * Update agents
     *
     * @param int $id Agent ID
     * @param array $data Agent data
     * @return mixed
     */
    public function update($id, $data)
    {
        $agent = Agent::find($id);
        $agent->fill($data);
        $agent->save();
        return $agent;
    }

    /**
     * Sql Temp Change Action By Agent
     */
    public function updateActionTemp()
    {
        return 'stop temp';
        return DB::select('UPDATE site.users
                                    SET action = 10
                                    FROM site.role_user ru
                                    WHERE site.users.id = ru.user_id
                                      AND ru.role_id = 19');
    }

    /**
     * Consult Percentage By Currency
     * @param int $user User Id
     * @param string $currency Currency Iso
     * @return mixed
     */
    public function myPercentageByCurrency(int $user, string $currency)
    {
        $iAgent = DB::select('SELECT * FROM get_my_percentage_by_currency(?,?)', [$user, $currency]);
        return $iAgent;
    }

    /**
     * Store agent
     *
     * @param array $data Agent data
     * @return mixed
     */
    public function store($data)
    {
        $agent = Agent::create($data);
        return $agent;
    }

    /**
     * unblock agents and users
     *
     * @param array $agents Agent data
     * @return mixed
     */
    public function unBlockAgents($currencyIso, $category, $userId)
    {
        \DB::table('exclude_makers_users')->where('currency_iso', $currencyIso)->where('category', $category)->where('user_id', $userId)->delete();
    }

    /**
     * Unblock users
     *
     * @param int $user User ID
     * @return mixed
     */
    public function unBlockUsers($userId)
    {
        $user = User::find($userId);
        $user->status = true;
        $user->action = ActionUser::$active;
        $user->save();
    }
}

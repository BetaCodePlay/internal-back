<?php

namespace App\Agents\Repositories;

use App\Agents\Entities\Agent;
use App\Users\Entities\User;
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
     * Block users
     *
     * @param int $user User ID
     * @return mixed
     */
    public function blockUsers($userId)
    {
        $user = User::find($userId);
        $user->status = false;
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
            ->select('users.id', 'users.username', 'users.status','users.action', 'profiles.timezone', 'agents.id AS agent', 'users.referral_code',
            'agents.master', 'agents.owner_id as owner', 'profiles.country_iso', 'agent_currencies.balance', 'agent_currencies.currency_iso')
            ->join('agent_currencies', 'agents.id', '=', 'agent_currencies.agent_id')
            ->join('users', 'agents.user_id', '=', 'users.id')
            ->join('profiles', 'users.id', '=', 'profiles.user_id')
            ->where('agent_currencies.currency_iso', $currency)
            ->where('users.id', $user)
            ->first();
    }

    /** find action and status by user
     * @param int $user Ids
     * @return mixed
     */
    public function statusActionByUser(int $user)
    {
        return User::select('username','status','action')
            ->where('id', $user)
            ->first();
    }

    /** get and update balance by agent
     * @param string $currency Curency Iso
     * @param int $userDebit User Id Debit
     * @param int $userCredit User Id Credit
     * @param  float $amount Amount of the operation
     * @return array
     */
    public function getAndUpdateBalance($currency, $userDebit, $userCredit, $amount)
    {
        $result = DB::select('SELECT * FROM site.get_and_update_balance_for_agent(?,?,?,?)',[$currency,$userDebit,$userCredit,$amount]);
        return $result;
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
            ->select('users.id', 'users.username', 'users.status','users.action', 'profiles.timezone', 'agent_user.agent_id as owner', 'users.referral_code')
            ->join('agent_user', 'agents.id', '=', 'agent_user.agent_id')
            ->join('users', 'agent_user.user_id', '=', 'users.id')
            ->join('profiles', 'users.id', '=', 'profiles.user_id')
            ->where('agent_user.user_id', $user)
            ->first();
        return $user;
    }

    /**
     * Consult Percentage By Currency
     * @param int $user User Id
     * @param string $currency Currency Iso
     * @return mixed
     */
    public function myPercentageByCurrency(int $user, string $currency)
    {
        $iAgent = DB::select('SELECT * FROM get_my_percentage_by_currency(?,?)',[$user,$currency]);
        return $iAgent;
    }

    public function iAgent($user)
    {
        $iAgent = DB::select('SELECT a.percentage
                 FROM site.agents a
                 WHERE user_id = ?',[$user]);
        return $iAgent[0];
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
     * Get exclude user provider
     *
     * @param int $user User ID
     * @return mixed
     */
    public function getExcludeUserProvider($user)
    {
        $data = User::select('exclude_providers_users.provider_id', 'exclude_providers_users.currency_iso')
            ->join('exclude_providers_users', 'users.id', '=', 'exclude_providers_users.user_id')
            ->where('exclude_providers_users.user_id', $user)
            ->get();
        return $data;
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
        $agents = Agent::select('agents.user_id', 'users.username', 'exclude_providers_users.provider_id', 'exclude_providers_users.created_at', 'providers.name')
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
     * unblock agents and users
     *
     * @param array $agents Agent data
     * @return mixed
     */
    public function unBlockAgents($currencyIso, $providerId, $userId)
    {
        \DB::table('exclude_providers_users')->where('currency_iso', $currencyIso)->where('provider_id', $providerId)->where('user_id', $userId)->delete();
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
        $user->save();
    }
}

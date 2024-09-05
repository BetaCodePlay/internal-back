<?php

namespace App\Agents\Repositories;

use App\Agents\Entities\Agent;
use App\Role\Enums\OrderableColumns;
use App\Users\Entities\User;
use App\Users\Enums\ActionUser;
use App\Users\Enums\TypeUser;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Security\Enums\Roles;
use Dotworkers\Wallet\Wallet;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
     *
     */
    const ORDER_COLUMN_ACTION = 3;

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
            [
                'category'     => $category,
                'user_id'      => $user,
                'currency_iso' => $currency
            ],
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
        $agents = \DB::table('exclude_makers_users')->where('currency_iso', $currency)->where(
            'category',
            $category
        )->where('user_id', $user)->update($data);
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
        $user         = User::find($userId);
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
            ->select(
                'users.id',
                'users.username',
                'users.status',
                'profiles.timezone',
                'agents.id AS agent',
                'users.referral_code',
                'agents.master',
                'agents.owner_id as owner',
                'profiles.country_iso',
                'agent_currencies.balance',
                'agent_currencies.currency_iso'
            )
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
            ->select(
                'users.id',
                'users.created_at as created',
                'users.email',
                'users.username',
                'users.status',
                'users.action',
                'users.type_user',
                'profiles.timezone',
                'agents.id AS agent',
                'users.referral_code',
                'agents.master',
                'agents.owner_id as owner',
                'agents.user_id as owner_id',
                'agents.percentage',
                'profiles.country_iso',
                'agent_currencies.balance',
                'agent_currencies.currency_iso',
            )
            ->join('agent_currencies', 'agents.id', '=', 'agent_currencies.agent_id')
            ->join('users', 'agents.user_id', '=', 'users.id')
            ->join('profiles', 'users.id', '=', 'profiles.user_id')
            ->where([
                'agent_currencies.currency_iso' => $currency,
                'users.id'                      => $user
            ])
            ->first();
    }

    /**
     * @param int $user
     * @param string $currency
     * @return string
     */
    public function findByUserIdAndCurrency2(int $user, string $currency)
    {
        return Agent::on('replica')
            ->select(
                'users.id',
                'users.created_at as created',
                'users.email',
                'users.username',
                'users.status',
                'users.action',
                'users.type_user',
                'profiles.timezone',
                'agents.id AS agent',
                'users.referral_code',
                'agents.master',
                'agents.owner_id as owner',
                'profiles.country_iso',
                'agent_currencies.balance',
                'agent_currencies.currency_iso'
            )
            ->join('agent_currencies', 'agents.id', '=', 'agent_currencies.agent_id')
            ->join('users', 'agents.user_id', '=', 'users.id')
            ->join('profiles', 'users.id', '=', 'profiles.user_id')
            ->where('agent_currencies.currency_iso', $currency)
            ->where('users.id', $user)
            ->toSql();
    }

    /**
     * @param int $user
     * @return mixed
     */
    public function statusActionByUser(int $user)
    {
        return User::select('username', 'status', 'action', 'type_user')
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
    public function getAndUpdateBalance($currency, $userDebit, $userCredit, $amount, $idWolf)
    {
        $result = DB::select('SELECT * FROM site.get_and_update_balance_for_agent(?,?,?,?,?)', [
            $currency,
            $userDebit,
            $userCredit,
            $amount,
            $idWolf
        ]);
        return $result;
    }

    /**
     * Fin user profile
     * @param int|null $user User Id
     * @param string|null $currency Currency ISO
     * @return mixed
     */
    public function findUserProfile(int|null $user, string|null $currency)
    : mixed {
        return User::join('agents', 'users.id', '=', 'agents.user_id')
            ->join('agent_currencies', 'agents.id', '=', 'agent_currencies.agent_id')
            ->join('profiles', 'users.id', '=', 'profiles.user_id')
            ->where('agent_currencies.currency_iso', $currency)
            ->where('users.id', $user)
            ->limit(1)
            ->first([
                'users.id',
                'users.id as user_id',
                'users.created_at as created',
                'users.email',
                'users.username',
                'users.status',
                'users.action',
                'profiles.timezone',
                'users.type_user',
                'agents.id as agent',
                'users.referral_code',
                'agents.master',
                'agents.owner_id as owner',
                'agents.percentage',
                'profiles.country_iso',
                'agent_currencies.balance',
                'agent_currencies.currency_iso',
                'agents.master_quantity as masterQuantity',
                'agents.cashier_quantity as cashierQuantity',
                'agents.player_quantity as playerQuantity',
            ]);
    }

    /**
     * @param string|int $userId
     * @return mixed
     */
    public function findUser(string|int $userId)
    : mixed {
        return Agent::on('replica')
            ->select([
                'users.id',
                'users.created_at as created',
                'users.email',
                'users.username',
                'users.status',
                'users.action',
                'users.uuid',
                'users.type_user',
                'profiles.timezone',
                'agents.user_id as owner_id',
                'agent_user.agent_id as owner',
                'users.referral_code',
            ])
            ->join('agent_user', 'agents.id', '=', 'agent_user.agent_id')
            ->join('users', 'agent_user.user_id', '=', 'users.id')
            ->join('profiles', 'users.id', '=', 'profiles.user_id')
            ->where('agent_user.user_id', $userId)
            ->first();
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
        $agents = Agent::select(
            'agents.user_id',
            'users.username',
            'exclude_providers_users.provider_id',
            'exclude_providers_users.makers',
            'exclude_providers_users.created_at',
            'providers.name'
        )
            ->join('users', 'agents.user_id', '=', 'users.id')
            ->join('exclude_providers_users', 'users.id', '=', 'exclude_providers_users.user_id')
            ->join('providers', 'exclude_providers_users.provider_id', '=', 'providers.id')
            ->where('exclude_providers_users.currency_iso', $currency)
            ->where('users.whitelabel_id', $whitelabel);

        if (! empty($provider)) {
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
        $agents = Agent::select(
            'agents.user_id',
            'users.username',
            'exclude_makers_users.makers',
            'exclude_makers_users.category'
        )
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
            ->select(
                'agents.*',
                'users.username',
                'agent_currencies.balance',
                'users.referral_code',
                'users.status'
            )
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
        return DB::select(
            'SELECT u.id,u.id AS user_id,u.username FROM site.users AS u
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
                    ) SELECT user_id FROM all_agents) ORDER BY username ASC',
            [
                $whitelabel,
                $owner,
                $currency,
                $currency
            ]
        );
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
     * Retrieve agent information along with its currency data.
     *
     * @param int $userId The ID of the user.
     * @param string $currency The currency ISO code.
     * @return Agent|null The agent information or null if not found.
     */
    public function getAgentInfoWithCurrency(int $userId, string $currency)
    : ?Agent {
        return Agent::join('agent_currencies', 'agents.id', '=', 'agent_currencies.agent_id')
            ->where('agents.user_id', $userId)
            ->where('agent_currencies.currency_iso', $currency)
            ->select('agents.*')
            ->first();
    }

    /**
     * Update the quantities for a specific agent.
     *
     * @param Agent $agentInfo The agent instance.
     * @param int $masterCount The count of master agents.
     * @param int $cashierCount The count of cashier agents.
     * @param int $playerCount The count of player agents.
     * @return bool True if the update was successful, otherwise false.
     */
    public function updateAgentQuantities(Agent $agentInfo, int $masterCount, int $cashierCount, int $playerCount): bool
    {
        $agentInfo->master_quantity  = $masterCount;
        $agentInfo->cashier_quantity = $cashierCount;
        $agentInfo->player_quantity  = $playerCount;
        return $agentInfo->save();
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
        $response = DB::select('SELECT * FROM site.get_users_agents_son(?,?,?)', [
            $owner,
            $currency,
            $whitelabel
        ]);
        $treeItem = [];
        foreach ($response as $item => $value) {
            $icon   = $value->type_user == 1 ? 'star' : ($value->type_user == 2 ? 'users' : 'user');
            $type   = $value->type_user == 5 ? 'user' : 'agent';
            $datTmp = [
                'id'      => $value->user_id,
                'text'    => $value->username,
                'status'  => $value->status,
                'icon'    => "fa fa-{$icon}",
                'li_attr' => [
                    'data_type' => $type,
                    'class'     => 'init_' . $type
                ],
                //'children'=>[]
            ];
            if (
                in_array($value->type_user, [
                    TypeUser::$agentMater,
                    TypeUser::$agentCajero
                ])
            ) {
                $datTmp['children'] = $this->getChildrenByOwner($value->user_id, $currency, $whitelabel);
            }
            $treeItem[] = $datTmp;
        }

        return $treeItem;
    }

    /**
     * Find Agent (not admin)
     * @param int $userId User ID
     * @param int $whitelabelId Whitelabel ID
     * @return int
     */
    public function findAgent(int $userId, int $whitelabelId)
    : int {
        $agentId = User::join('role_user', 'users.id', '=', 'role_user.user_id')
            ->where('role_user.role_id', Roles::$admin_Beet_sweet)
            ->where('users.username', '!=', 'admin')
            ->where('users.whitelabel_id', $whitelabelId)
            ->where('users.id', $userId)
            ->value('users.id');

        return (int)$agentId;
    }

    /**
     * Get Tree Sql Levels
     * @param int $userAuthId
     * @param string $currency
     * @param int $whitelabelId
     * @return array
     */
    public function getTreeSqlLevels(int $userAuthId, string $currency, int $whitelabelId)
    : array {
        return DB::select(
            'WITH RECURSIVE all_agents AS (SELECT agents.id, agents.user_id, agents.owner_id, 0 AS level
                              FROM site.agents AS agents
                              WHERE agents.user_id = ?
                              UNION
                              SELECT agents.id, agents.user_id, agents.owner_id, level + 1 AS level
                              FROM site.agents AS agents
                                       JOIN all_agents ON agents.owner_id = all_agents.user_id)

                    SELECT u.id, u.username, a.owner_id, u.type_user, ac.currency_iso as currency, u.status, level
                    FROM site.users u
                             INNER JOIN all_agents a on u.id = a.user_id
                             INNER JOIN site.agent_currencies AS ac ON a.id = ac.agent_id
                    WHERE u.whitelabel_id = ?
                      AND ac.currency_iso = ?
                    UNION
                    SELECT u.id, u.username, a.user_id, u.type_user, ac.currency_iso as currency, u.status, level + 1 as level
                    FROM site.agent_user AS au
                             INNER JOIN site.users AS u ON u.id = au.user_id
                             INNER JOIN all_agents a on au.agent_id = a.id
                             INNER JOIN site.agent_currencies AS ac ON a.id = ac.agent_id
                             inner join site.user_currencies uc on uc.user_id=u.id
                    WHERE u.whitelabel_id = ? and uc.currency_iso=ac.currency_iso
                      AND ac.currency_iso = ?
                    ORDER BY type_user, username',
            [
                $userAuthId,
                $whitelabelId,
                $currency,
                $whitelabelId,
                $currency,
            ],
        );
    }

    /**
     * @param Request $request
     * @param string $currency
     * @param int $whitelabelId
     * @return array
     */
    public function getDirectChildren(Request $request, string $currency, int $whitelabelId)
    : array {
        $draw        = $request->input('draw', 1);
        $start       = $request->input('start', 0);
        $length      = $request->input('length', 10);
        $searchValue = $request->input('search.value');
        $orderColumn = $request->input('order.0.column');
        $orderDir    = $request->input('order.0.dir');
        $userId      = getUserIdByUsernameOrCurrent($request, $whitelabelId);
        $agentQuery  = $this->getUserAgentQuery($userId, $currency, $whitelabelId);

        if (! is_null($searchValue)) {
            $agentQuery->where(function ($query) use ($searchValue) {
                $query->where('users.username', 'like', "%$searchValue%")
                    ->orWhere('agent_currencies.balance', 'like', "%$searchValue%");
            });
        }

        $orderableColumns = OrderableColumns::getOrderableColumns();

        Log::alert('orderableColumns', [
            'orderColumn' => $orderColumn,
            'condition1' => $orderColumn !== self::ORDER_COLUMN_ACTION,
            'condition2' => $orderColumn !== self::ORDER_COLUMN_ACTION ? $orderDir : 'asc',
            'orderDir' => $orderDir,
        ]);

        $orderColumn = ! is_null($orderColumn) ? $orderColumn: 0;

        $agentQuery->orderBy(
            array_key_exists($orderColumn, $orderableColumns) && $orderColumn !== self::ORDER_COLUMN_ACTION
                ? $orderableColumns[$orderColumn]
                : 'users.username',
            $orderColumn !== self::ORDER_COLUMN_ACTION ? $orderDir : 'asc'
        );

        $playerQuery = $this->getPlayerQuery($userId, $currency, $whitelabelId);

        if (! is_null($searchValue)) {
            $playerQuery->where(function ($query) use ($searchValue) {
                $query->where('users.username', 'like', "%$searchValue%");
            });
        }

        $orderKey = array_key_exists($orderColumn, $orderableColumns) && $orderColumn !== self::ORDER_COLUMN_ACTION
            ? $orderableColumns[$orderColumn]
            : 'users.username';

        $orderDir = $orderColumn !== self::ORDER_COLUMN_ACTION ? $orderDir : 'asc';
        $playerQuery->orderBy($orderKey, $orderDir);

        $combinedResults = array_merge($agentQuery->get()->toArray(), $playerQuery->get()->toArray());
        $combinedResults = array_map(function ($item) {
            $item['actionString'] = isset($item['action'])
                ? ActionUser::getName($item['action'])
                : null;

            return $item;
        }, $combinedResults);

        $resultCount = count($combinedResults);

        if ($orderColumn == self::ORDER_COLUMN_ACTION) {
            $this->sortByActionString($combinedResults, $orderDir);
        }

        $slicedResults    = array_slice($combinedResults, $start, $length);
        $bonus            = Configurations::getBonus();
        $formattedResults = $this->formatUserResults($slicedResults, $currency, $bonus);

        return [
            'draw'            => (int)$draw,
            'recordsTotal'    => $resultCount,
            'recordsFiltered' => $resultCount,
            'data'            => $formattedResults,
        ];
    }


    /**
     * @param $userId
     * @param $currency
     * @param $whitelabelId
     * @param array|null $select
     * @return mixed
     */
    function getUserAgentQuery($userId, $currency, $whitelabelId, ?array $select = null)
    : mixed {
        $defaultSelect = [
            'users.username',
            'users.type_user',
            'users.type_user as typeId',
            'users.id',
            'users.action',
            'users.status',
            'agent_currencies.balance',
        ];

        $select = $select ?? $defaultSelect;

        return User::select($select)
            ->join('agents', 'users.id', '=', 'agents.user_id')
            ->join('agent_currencies', 'agents.id', '=', 'agent_currencies.agent_id')
            ->leftJoin('agent_user', 'users.id', '=', 'agent_user.user_id')
            ->orderBy('users.created_at', 'desc')
            ->where([
                'agents.owner_id'               => $userId,
                'agent_currencies.currency_iso' => $currency,
                'users.whitelabel_id'           => $whitelabelId,
            ]);
    }

    /**
     * @param $userId
     * @param $currency
     * @param $whitelabelId
     * @param array|null $select
     * @return mixed
     */
    function getPlayerQuery($userId, $currency, $whitelabelId, ?array $select = null)
    : mixed {
        $defaultSelect = [
            'users.username',
            'users.type_user',
            'users.type_user as typeId',
            'users.id',
            'users.action',
            'users.status',
            'agent_currencies.balance',
        ];

        $select = $select ?? $defaultSelect;

        return User::select($select)
            ->join('agent_user', 'users.id', '=', 'agent_user.user_id')
            ->join('agents', 'agent_user.agent_id', '=', 'agents.id')
            ->leftJoin('agent_currencies', 'agents.id', '=', 'agent_currencies.agent_id')
            ->leftJoin('user_currencies', 'users.id', '=', 'user_currencies.user_id')
            ->orderBy('users.created_at', 'desc')
            ->where([
                'agents.user_id'                => $userId,
                'agent_currencies.currency_iso' => $currency,
                'user_currencies.currency_iso'  => $currency,
                'users.whitelabel_id'           => $whitelabelId,
            ]);
    }

    /**
     * @param array $combinedResults
     * @param string $orderDir
     * @return void
     */
    function sortByActionString(array &$combinedResults, string $orderDir)
    : void {
        usort($combinedResults, function ($a, $b) use ($orderDir) {
            $aActionString = $a['actionString'] ?? null;
            $bActionString = $b['actionString'] ?? null;

            $compareActions = function ($aAction, $bAction) use ($orderDir) {
                return $orderDir === 'asc' ? strcasecmp($aAction, $bAction) : strcasecmp($bAction, $aAction);
            };

            return $aActionString === null
                ? ($bActionString === null ? 0 : ($orderDir === 'asc' ? 1 : -1))
                : ($bActionString === null
                    ? ($orderDir === 'asc' ? -1 : 1)
                    : $compareActions(
                        $aActionString,
                        $bActionString
                    ));
        });
    }

    /**
     * @param $userId
     * @param $currency
     * @param $whitelabelId
     * @return array
     */
    public function getChildrenIdsWithParentAuth($userId, $currency, $whitelabelId)
    : array {
        $agentQuery    = $this->getUserAgentQuery($userId, $currency, $whitelabelId, ['users.id']);
        $playerQuery   = $this->getPlayerQuery($userId, $currency, $whitelabelId, ['users.id']);
        $combinedIds   = Arr::collapse([$agentQuery->pluck('id')->toArray(), $playerQuery->pluck('id')->toArray()]);
        $combinedIds[] = $userId;

        return $combinedIds;
    }

    /**
     * @param array $results
     * @param string $currency
     * @param $bonus
     * @return array
     */
    private function formatUserResults(array $results, string $currency, $bonus)
    : array {
        return array_map(function ($item) use ($currency, $bonus) {
            $balance = $item['balance'];
            $userId  = $item['id'];

            if ($item['typeId'] == TypeUser::$player) {
                $wallet = Wallet::getByClient($userId, $currency, $bonus);

                if (is_array($wallet->data)) {
                    Log::info("Error in user wallet array {$userId}", [$wallet]);
                }

                $balance = ! is_array($wallet->data)
                    ? $wallet?->data?->wallet?->balance
                    : 0;
            }

            $actionItem = $item['action'];
            $action     = ActionUser::getName($actionItem);
            $isBlocked  = ActionUser::isBlocked($actionItem);

            return [
                $item['username'],
                [$item['type_user'], $item['typeId']],
                $userId,
                [$action, $isBlocked, $actionItem],
                formatAmount($balance),
                $item['status'],
            ];
        }, $results);
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
     * Get agents dependency
     *
     * @param int $user Agent user ID
     * @param string $currency Currency ISO
     * @return mixed
     */
    public function getAgentsDependency($user, $currency)
    {
        return \DB::connection('replica')
            ->select(
                "WITH RECURSIVE all_agents AS (
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
        FROM all_agents",
                [
                    $user,
                    $currency,
                    $currency
                ]
            );
    }

    /**
     * Get exclude user provider
     *
     * @param int $user User ID
     * @return mixed
     */
    public function getExcludeUserProvider($user)
    {
        $data = User::select(
            'exclude_providers_users.provider_id',
            'exclude_providers_users.makers',
            'exclude_providers_users.currency_iso'
        )
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
        $data = User::select(
            'exclude_makers_users.category',
            'exclude_makers_users.makers',
            'exclude_makers_users.currency_iso'
        )
            ->join('exclude_makers_users', 'users.id', '=', 'exclude_makers_users.user_id')
            ->where('exclude_makers_users.user_id', $user)
            ->get();
        return $data;
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
            ->select(
                'users.id',
                'users.username',
                'users.status',
                'profiles.timezone',
                'agent_user.agent_id as owner',
                'users.referral_code'
            )
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
            ->select(
                'users.id',
                'users.username',
                'users.status',
                'profiles.timezone',
                'agent_user.agent_id as owner',
                'users.referral_code'
            )
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
            ->select(
                'users.id',
                'users.username',
                'users.status',
                'profiles.timezone',
                'agent_user.agent_id as owner',
                'users.referral_code'
            )
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
     *
     * @param $user
     * @return mixed
     */
    public function iAgent($user)
    {
        $iAgent = DB::select(
            'SELECT a.percentage
                 FROM site.agents a
                 WHERE user_id = ?',
            [$user]
        );
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
        return DB::select(
            'UPDATE site.users
                                    SET action = 10
                                    FROM site.role_user ru
                                    WHERE site.users.id = ru.user_id
                                      AND ru.role_id = 19'
        );
    }

    /**
     * Consult Percentage By Currency
     * @param int $user User Id
     * @param string $currency Currency Iso
     * @return mixed
     */
    public function myPercentageByCurrency(int $user, string $currency)
    {
        return DB::select('SELECT * FROM get_my_percentage_by_currency(?,?)', [$user, $currency]);
    }

    /**
     * Search for agents and users by username in a hierarchical tree structure.
     *
     * This method performs a recursive search for users and agents whose usernames match
     * the provided pattern in a hierarchical tree structure. It returns a list of matching users
     * and agents along with additional information such as user type, currency, and status.
     *
     * @param int $userAuthId The authenticated user's ID.
     * @param string $currency The desired currency.
     * @param int $whitelabelId The whitelabel ID.
     * @param string $username The username pattern to search for.
     *
     * @return array An array containing the matching users and agents.
     *
     * @throws Exception If an error occurs during the database query.
     */
    public function searchAgentsAndUsersInTree(
        int $userAuthId,
        string $currency,
        int $whitelabelId,
        string $username
    )
    : array {
        $usernameLike = '%' . $username . '%';

        return DB::select(
            'WITH RECURSIVE all_agents AS (SELECT agents.id, agents.user_id, agents.owner_id, 0 AS level
                              FROM site.agents AS agents
                              WHERE agents.user_id = ?
                              UNION
                              SELECT agents.id, agents.user_id, agents.owner_id, level + 1 AS level
                              FROM site.agents AS agents
                                       JOIN all_agents ON agents.owner_id = all_agents.user_id)

                    SELECT u.id, u.username, a.owner_id, u.type_user, ac.currency_iso as currency, u.status, level
                    FROM site.users u
                             INNER JOIN all_agents a on u.id = a.user_id
                             INNER JOIN site.agent_currencies AS ac ON a.id = ac.agent_id
                    WHERE u.whitelabel_id = ?
                      AND ac.currency_iso = ?
                      AND u.username like ?
                    UNION
                    SELECT u.id, u.username, a.user_id, u.type_user, ac.currency_iso as currency, u.status, level + 1 as level
                    FROM site.agent_user AS au
                             INNER JOIN site.users AS u ON u.id = au.user_id
                             INNER JOIN all_agents a on au.agent_id = a.id
                             INNER JOIN site.agent_currencies AS ac ON a.id = ac.agent_id
                             inner join site.user_currencies uc on uc.user_id=u.id
                    WHERE u.whitelabel_id = ? and uc.currency_iso=ac.currency_iso
                      AND ac.currency_iso = ?
                      AND u.username like ?
                    ORDER BY type_user, username',
            [
                $userAuthId,
                $whitelabelId,
                $currency,
                $usernameLike,
                $whitelabelId,
                $currency,
                $usernameLike,
            ],
        );
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
        \DB::table('exclude_makers_users')->where('currency_iso', $currencyIso)->where(
            'category',
            $category
        )->where('user_id', $userId)->delete();
    }

    /**
     * Unblock users
     *
     * @param int $user User ID
     * @return mixed
     */
    public function unBlockUsers($userId)
    {
        $user         = User::find($userId);
        $user->status = true;
        $user->action = ActionUser::$active;
        $user->save();
    }
}

<?php

namespace App\Agents\Services;

use App\Agents\Collections\AgentsCollection;
use App\Agents\Repositories\AgentsRepo;
use App\Core\Repositories\RolesRepo;
use App\Core\Services\BaseService;
use App\Users\Enums\TypeUser;
use App\Users\Repositories\UsersRepo;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Configurations\Utils;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class AgentService extends BaseService
{
    /**
     * Constructor for the AgentService class.
     *
     * @param AgentsCollection $agentsCollection
     * @param AgentsRepo $agentsRepo
     * @param RolesRepo $rolesRepo
     * @param UsersRepo $usersRepo
     */
    public function __construct(
        private AgentsCollection $agentsCollection,
        private AgentsRepo $agentsRepo,
        private RolesRepo $rolesRepo,
        private UsersRepo $usersRepo,
    ) {
    }

    /**
     * Search for a user by their username.
     *
     * This method searches for a user by their username and returns a Response containing
     * the results. The search can be influenced by the configuration settings, such as
     * the status of agents and user type.
     *
     * @param Request $request The HTTP request object containing user input.
     *
     * @return Response A JSON response containing the search results.
     *
     * @throws Exception If an error occurs during the search process.
     */
    public function searchUserByUsername(Request $request)
    : Response {
        $username = Str::lower($request->get('user'));

        if (! Configurations::getAgents()?->active && $request->has('type')) {
            $users = $this->usersRepo->search($username, TypeUser::$agentMater);

            return Utils::successResponse(
                ['agents' => $this->agentsCollection->formatUsersSelect($users, $this->rolesRepo)],
            );
        }

        return Utils::successResponse([
            'agents' => $this->agentsRepo->searchAgentsAndUsersInTree(
                $request->user()->id,
                session('currency'),
                Configurations::getWhitelabel(),
                $username,
            ),
        ]);
    }

    /**
     * Updates the agent quantities from the tree structure.
     *
     * This method retrieves the current authenticated user's ID and currency from the session,
     * fetches the children tree and agents associated with the user, and updates the quantities
     * of different types of agents for each agent. If an error occurs during the process, it is
     * logged, and an appropriate error message is returned.
     *
     * @return array An array containing a message indicating the result of the operation.
     *
     * @throws Exception If there is an error while updating agent quantities, it is caught and logged.
     */
    public function updateAgentQuantitiesFromTree()
    : array
    {
        $authUserId   = auth()->id();
        $currency     = session('currency');
        $childrenTree = collect($this->agentsCollection->childrenTreeSql($authUserId));

        try {
            $agents = $this->agentsRepo->getAgentsAllByOwner(
                $authUserId,
                $currency,
                Configurations::getWhitelabel()
            );

            $totalMasterAgents = 0;
            $totalCashierAgents = 0;
            $totalPlayers = 0;

            foreach ($agents as $agent) {
                $childAgents = $childrenTree->where('owner_id', $agent->user_id);

                $masterCount  = $childAgents->where('type_user', TypeUser::$agentMater)->count();
                $cashierCount = $childAgents->where('type_user', TypeUser::$agentCajero)->count();
                $playerCount  = $childAgents->where('type_user', TypeUser::$player)->count();

                $totalMasterAgents += $masterCount;
                $totalCashierAgents += $cashierCount;
                $totalPlayers += $playerCount;

                $agentInfo = $this->agentsRepo->getAgentInfoWithCurrency($agent->user_id, $currency);

                if ($agentInfo) {
                    $this->agentsRepo->updateAgentQuantities($agentInfo, $masterCount, $cashierCount, $playerCount);
                }
            }

            return [
                'totalMasterAgents' => $totalMasterAgents,
                'totalCashierAgents' => $totalCashierAgents,
                'totalPlayers' => $totalPlayers,
                'tree' => $childrenTree,
            ];
        } catch (Exception $e) {
            Log::error('Error updating agent quantities', [
                'user_id'   => $authUserId,
                'currency'  => $currency,
                'exception' => $e->getMessage(),
            ]);

            return ['message' => 'An error occurred while updating agent quantities'];
        }
    }

    public function updateAgentQuantitiesForUser(int $userId)
    {
        $currency     = session('currency');

        $childrenTree = collect($this->agentsCollection->childrenTreeSql($userId));

        try {
            $agent = $this->agentsRepo->getAgentsAllByOwner($userId, $currency, Configurations::getWhitelabel());

            $childAgents = $childrenTree->where('owner_id', $userId);

            $totalMasterAgents = 0;
            $totalCashierAgents = 0;
            $totalPlayers = 0;

            if (! $agent) {
                return response()->json(['message' => 'Agent not found'], 404);
            }

            $masterCount  = $childAgents->where('type_user', TypeUser::$agentMater)->count();
            $cashierCount = $childAgents->where('type_user', TypeUser::$agentCajero)->count();
            $playerCount  = $childAgents->where('type_user', TypeUser::$player)->count();

            $totalMasterAgents += $masterCount;
            $totalCashierAgents += $cashierCount;
            $totalPlayers += $playerCount;

            dd($totalMasterAgents, $totalCashierAgents, $totalPlayers);

        } catch (Exception $e) {
            Log::error('Error updating agent quantities for user', [
                'user_id'   => $userId,
                'currency'  => $currency,
                'exception' => $e->getMessage(),
            ]);

            return response()->json(['message' => 'An error occurred while updating agent quantities'], 500);
        }


        return $childrenTree;
    }
}

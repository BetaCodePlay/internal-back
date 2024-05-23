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
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
    public function searchUserByUsername(Request $request): Response
    {
        $username = Str::lower($request->get('user'));

        if (!Configurations::getAgents()?->active && $request->has('type')) {
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

    public function updateAgentQuantitiesFromTree()
    : array
    {
        $authUserId   = auth()->id();
        $currency     = session('currency');
        $childrenTree = collect($this->agentsCollection->childrenTreeSql($authUserId));
        $agents       = $this->agentsRepo->getAgentsAllByOwner(
            $authUserId,
            $currency,
            Configurations::getWhitelabel()
        );

        foreach ($agents as $agent) {
            $childAgents = $childrenTree->where('owner_id', $agent->user_id);

            $masterCount  = $childAgents->where('type_user', TypeUser::$agentMater)->count();
            $cashierCount = $childAgents->where('type_user', TypeUser::$agentCajero)->count();
            $playerCount  = $childAgents->where('type_user', TypeUser::$player)->count();

            $agentInfo = $this->agentsRepo->getAgentInfoWithCurrency($agent->user_id, $currency);

            if ($agentInfo) {
                $this->agentsRepo->updateAgentQuantities($agentInfo, $masterCount, $cashierCount, $playerCount);
            }
        }

        return ['message' => 'Agent quantities updated successfully'];
    }
}

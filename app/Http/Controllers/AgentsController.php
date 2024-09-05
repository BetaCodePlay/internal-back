<?php

namespace App\Http\Controllers;

use App\Agents\Collections\AgentsCollection;
use App\Agents\Enums\AgentType;
use App\Agents\Repositories\AgentCurrenciesRepo;
use App\Agents\Repositories\AgentsRepo;
use App\Agents\Services\AgentService;
use App\Audits\Enums\AuditTypes;
use App\Audits\Repositories\AuditsRepo;
use App\Core\Collections\TransactionsCollection;
use App\Core\Repositories\CountriesRepo;
use App\Core\Repositories\CurrenciesRepo;
use App\Core\Repositories\GamesRepo;
use App\Core\Repositories\ProvidersRepo;
use App\Core\Repositories\ProvidersTypesRepo;
use App\Core\Repositories\TransactionsRepo;
use App\Core\Repositories\WhitelabelsGamesRepo;
use App\Http\Requests\TransactionRequest;
use App\Reports\Collections\ReportsCollection;
use App\Reports\Repositories\ClosuresUsersTotals2023Repo;
use App\Reports\Repositories\ClosuresUsersTotalsRepo;
use App\Reports\Repositories\ReportAgentRepo;
use App\Reports\Repositories\ReportRepo;
use App\Security\Repositories\RolesRepo;
use App\Transactions\Services\UserTransactionService;
use App\Users\Collections\UsersCollection;
use App\Users\Enums\ActionUser;
use App\Users\Enums\TypeUser;
use App\Users\Repositories\UserCurrenciesRepo;
use App\Users\Repositories\UsersRepo;
use App\Users\Repositories\UsersTempRepo;
use App\Users\Rules\Email;
use App\Users\Rules\Password;
use App\Users\Rules\Username;
use App\Users\Users as GenerateReferenceCode;
use App\Whitelabels\Repositories\WhitelabelsRepo;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Dotworkers\Audits\Audits;
use Dotworkers\Bonus\Bonus;
use Dotworkers\Bonus\Enums\AllocationCriteria;
use Dotworkers\Bonus\Repositories\CampaignParticipationRepo;
use Dotworkers\Bonus\Repositories\CampaignsRepo;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Configurations\Enums\Codes;
use Dotworkers\Configurations\Enums\Providers;
use Dotworkers\Configurations\Enums\ProviderTypes;
use Dotworkers\Configurations\Enums\Status;
use Dotworkers\Configurations\Enums\TransactionStatus;
use Dotworkers\Configurations\Enums\TransactionTypes;
use Dotworkers\Configurations\Utils;
use Dotworkers\Security\Enums\Permissions;
use Dotworkers\Security\Enums\Roles;
use Dotworkers\Security\Security;
use Dotworkers\Sessions\Sessions;
use Dotworkers\Store\Store;
use Dotworkers\Wallet\Wallet;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Ixudra\Curl\Facades\Curl;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\Response;
use Xinax\LaravelGettext\Facades\LaravelGettext;

use function GuzzleHttp\Promise\all;

/**
 * Class AgentsController
 *
 * This class allows to manage agents requests
 *
 * @package App\Http\Controllers
 * @author  Eborio Linarez
 * @author Carlos Hurtado
 */
class AgentsController extends Controller
{
    /**
     * AgentsRepo
     *
     * @var AgentsRepo
     */
    private $agentsRepo;

    /**
     * AgentsCollection
     *
     * @var AgentsCollection
     */
    private $agentsCollection;

    /**
     * TransactionsCollection
     *
     * @var TransactionsCollection
     */
    private $transactionsCollection;

    /**
     * ReportAgentRepo
     *
     * @var ReportAgentRepo
     */
    private $reportAgentRepo;

    /**
     * TransactionsRepo
     *
     * @var TransactionsRepo
     */
    private $transactionsRepo;

    /**
     * GamesRepo
     *
     * @var GamesRepo
     */
    private $gamesRepo;

    /**
     * UsersRepo
     *
     * @var UsersRepo
     */
    private $usersRepo;

    /**
     * WhitelabelsGamesRepo
     *
     * @var WhitelabelsGamesRepo
     */
    private $whitelabelsGamesRepo;

    /**
     * AgentCurrenciesRepo
     *
     * @var AgentCurrenciesRepo
     */
    private $agentCurrenciesRepo;

    /**
     * GenerateReferenceCode
     *
     * @var GenerateReferenceCode
     */
    private $generateReferenceCode;

    /**
     * WhitelabelsRepo
     *
     * @var WhitelabelsRepo
     */
    private $whitelabelsRepo;

    /**
     * CurrenciesRepo
     *
     * @var CurrenciesRepo
     */
    private $currenciesRepo;

    /**
     * RolesRepo
     *
     * @var RolesRepo
     */
    private $rolesRepo;

    /**
     * UsersCollection
     *
     * @var UsersCollection
     */
    private $usersCollection;

    /**
     * $closuresUsersTotals2023Repo
     *
     * @var ClosuresUsersTotals2023Repo
     */
    private $closuresUsersTotals2023Repo;

    /**
     * @var TransactionService
     */
    private TransactionService $transactionService;


    /**
     * @var userTransactionService
     */
    private UserTransactionService $userTransactionService;

    /**
     * @var AgentService
     */
    private AgentService $agentService;

    /**
     * $CampaignsRepo
     *
     * @var CampaignsRepo
     */
    private $campaignsRepo;

    /**
     * $CampaignParticipationRepo
     *
     * @var CampaignParticipationRepo
     */
    private $campaignParticipationRepo;

    /**
     * @var ReportRepo
     */
    private ReportRepo $reportRepo;


    /***
     * AgentsController constructor.
     *
     * @param ReportAgentRepo $reportAgentRepo
     * @param ClosuresUsersTotals2023Repo $closuresUsersTotals2023Repo
     * @param TransactionsCollection $transactionsCollection
     * @param AgentsRepo $agentsRepo
     * @param AgentsCollection $agentsCollection
     * @param UsersRepo $usersRepo
     * @param TransactionsRepo $transactionsRepo
     * @param WhitelabelsGamesRepo $whitelabelsGamesRepo
     * @param AgentCurrenciesRepo $agentCurrenciesRepo
     * @param GenerateReferenceCode $generateReferenceCode
     * @param WhitelabelsRepo $whitelabelsRepo
     * @param CurrenciesRepo $currenciesRepo
     * @param UsersCollection $usersCollection
     * @param GamesRepo $gamesRepo
     * @param RolesRepo $rolesRepo
     * @param TransactionService $transactionService
     * @param AgentService $agentService
     * @param AgentService $agentService
     * @param CampaignsRepo $campaignsRepo
     * @param CampaignParticipationRepo $campaignParticipationRepo
     */
    public function __construct(
        ReportAgentRepo $reportAgentRepo,
        ClosuresUsersTotals2023Repo $closuresUsersTotals2023Repo,
        TransactionsCollection $transactionsCollection,
        AgentsRepo $agentsRepo,
        AgentsCollection $agentsCollection,
        UsersRepo $usersRepo,
        TransactionsRepo $transactionsRepo,
        WhitelabelsGamesRepo $whitelabelsGamesRepo,
        AgentCurrenciesRepo $agentCurrenciesRepo,
        GenerateReferenceCode $generateReferenceCode,
        WhitelabelsRepo $whitelabelsRepo,
        CurrenciesRepo $currenciesRepo,
        UsersCollection $usersCollection,
        GamesRepo $gamesRepo,
        RolesRepo $rolesRepo,
        AgentService $agentService,
        CampaignsRepo $campaignsRepo,
        CampaignParticipationRepo $campaignParticipationRepo,
        UserTransactionService $userTransactionService,
        AuditsRepo $auditsRepo,
        ReportRepo $reportRepo
    ) {
        $this->closuresUsersTotals2023Repo = $closuresUsersTotals2023Repo;
        $this->agentsRepo                  = $agentsRepo;
        $this->agentsCollection            = $agentsCollection;
        $this->reportAgentRepo             = $reportAgentRepo;
        $this->usersRepo                   = $usersRepo;
        $this->transactionsRepo            = $transactionsRepo;
        $this->agentCurrenciesRepo         = $agentCurrenciesRepo;
        $this->generateReferenceCode       = $generateReferenceCode;
        $this->whitelabelsRepo             = $whitelabelsRepo;
        $this->gamesRepo                   = $gamesRepo;
        $this->whitelabelsGamesRepo        = $whitelabelsGamesRepo;
        $this->currenciesRepo              = $currenciesRepo;
        $this->usersCollection             = $usersCollection;
        $this->transactionsCollection      = $transactionsCollection;
        $this->rolesRepo                   = $rolesRepo;
        $this->agentService                = $agentService;
        $this->campaignsRepo               = $campaignsRepo;
        $this->campaignParticipationRepo   = $campaignParticipationRepo;
        $this->userTransactionService      = $userTransactionService;
        $this->auditsRepo                  = $auditsRepo;
        $this->reportRepo                  = $reportRepo;
    }

    /**
     * Balance adjustment
     *
     * @return Application|Factory|View
     */
    public function balanceAdjustment()
    {
    }

    /**
     * Show report management
     *
     * @return Application|Factory|View
     */
    public function reportManagement()
    {
        try {
            return view('back.agents.reports.management');
        } catch (Exception $ex) {
            Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    /**
     * Show add users
     *
     * @return Application|Factory|View
     */
    public function addUsers()
    {
        $user           = auth()->user()->id;
        $currency       = session('currency');
        $agentsData     = $this->agentsRepo->getAgentsByOwner($user, $currency);
        $agents         = $this->agentsCollection->childAgents($agentsData, $currency);
        $data['agents'] = $agents;
        $data['title']  = _i('Add users');
        return view('back.agents.add-users', $data);
    }

    /**
     *  Add users to agent
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function addUsersData(Request $request)
    {
        $this->validate($request, [
            'username' => ['required', new Username()],
            'agent'    => 'required'
        ]);

        try {
            $username   = strtolower(trim($request->username));
            $agent      = $request->agent;
            $currency   = session('currency');
            $whitelabel = Configurations::getWhitelabel();
            $userData   = $this->usersRepo->getUsernameByCurrency($username, $currency, $whitelabel);
            if (! is_null($userData)) {
                $agentUserData = $this->agentsRepo->existAgent($userData->id);
                if (is_null($agentUserData)) {
                    $agentUser = $this->agentsRepo->findUser($userData->id);
                    if (is_null($agentUser)) {
                        $add  = $this->agentsRepo->addUser($agent, $userData->id);
                        $data = [
                            'title'   => _i('Added user'),
                            'message' => _i('The user has been successfully added'),
                            'close'   => _i('Close'),
                        ];
                        return Utils::successResponse($data);
                    } else {
                        $data = [
                            'title'   => _i('Assigned user'),
                            'message' => _i("The user is already assigned to an agent"),
                            'close'   => _i('Close')
                        ];
                        return Utils::errorResponse(Codes::$forbidden, $data);
                    }
                } else {
                    $data = [
                        'title'   => _i('Agent user'),
                        'message' => _i("The user is an agent, it cannot be associated to another agent."),
                        'close'   => _i('Close')
                    ];
                    return Utils::errorResponse(Codes::$forbidden, $data);
                }
            } else {
                $data = [
                    'title'   => _i('The user does not exist'),
                    'message' => _i("Please check and try again"),
                    'close'   => _i('Close')
                ];
                return Utils::errorResponse(Codes::$forbidden, $data);
            }
        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Find agents and users
     *
     * @param Request $request
     * @return Response
     */
    public function findUser(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|exists:users,id',
        ]);

        try {
            if (session('admin_id')) {
                $userId      = session('admin_id');
                $agentPlayer = false;
            } else {
                $userId      = auth()->user()->id;
                $agentPlayer = true;
            }
            $currency  = session('currency');
            $id        = $request->id;
            $walletId  = null;
            $userAgent = $this->agentsRepo->findByUserIdAndCurrency($id, $currency);
            $user      = $this->agentsRepo->findUser($id);

            if (! is_null($userAgent)) {
                $father  = $this->usersRepo->findUsername($userAgent->owner);
                $user    = $userAgent;
                $balance = $userAgent->balance;
                $master  = $userAgent->master;
                $agent   = true;
                $myself  = $userId == $userAgent->id;
                $type    = 'agent';
            } else {
                $father   = $this->usersRepo->findUsername($user->owner_id);
                $user     = $this->agentsRepo->findUser($id);
                $master   = false;
                $wallet   = Wallet::getByClient($id, $currency);
                $balance  = $wallet->data->wallet->balance;
                $agent    = false;
                $walletId = $wallet->data->wallet->id;
                $myself   = false;
                $type     = 'user';
            }
            $this->agentsCollection->formatAgent($user);
            $data = [
                'user'         => $user,
                'balance'      => number_format($balance, 2),
                'master'       => $master,
                'father'       => $father->username ?? '---',
                'fathers'      => [],
                'agent'        => $agent,
                'wallet'       => $walletId,
                'type'         => $type,
                'myself'       => $myself,
                'agent_player' => $agentPlayer
            ];
            return Utils::successResponse($data);
        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'Request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     * Agent sub agents
     *
     * @param int $user User ID
     * @return Response
     */
    public function agents($user)
    {
        try {
            $currency   = session('currency');
            $agents     = $this->agentsRepo->getAgentsByOwner($user, $currency);
            $agentsData = $this->agentsCollection->formatAgents($agents);
            return Utils::successResponse($agentsData);
        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Show agents balances report
     *
     * @return Application|Factory|View
     */
    public function agentsBalances()
    {
        $data['title'] = _i('Agents balances');
        return view('back.agents.reports.agents-balances', $data);
    }

    /**
     * Agents balances data
     *
     * @return Response
     */
    public function agentsBalancesData()
    {
        try {
            if (session('admin_id')) {
                $user = session('admin_id');
            } else {
                $user = auth()->user()->id;
            }
            $currency   = session('currency');
            $agents     = $this->agentsRepo->getAgentsByOwner($user, $currency);
            $agentsData = $this->agentsCollection->formatAgents($agents);
            return Utils::successResponse($agentsData);
        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Show agents payments
     *
     * @return Application|Factory|View
     */
    public function agentsPayments(
        CountriesRepo $countriesRepo,
        ProvidersRepo $providersRepo,
        ClosuresUsersTotalsRepo $closuresUsersTotalsRepo,
        ReportsCollection $reportsCollection
    ) {
        try {
            if (session('admin_id')) {
                $user = session('admin_id');
            } else {
                $user = auth()->user()->id;
                if (Auth::user()->username == 'romeo') {
                    $userTmp = $this->usersRepo->findUserCurrencyByWhitelabel(
                        'wolf',
                        session('currency'),
                        Configurations::getWhitelabel()
                    );

                    $user = isset($userTmp[0]->id) ? $userTmp[0]->id : null;
                    if (is_null($user)) {
                        //\Log::notice('AgentsController::index',['0'=>$userTmp,'currency'=>session('currency'),Configurations::getWhitelabel()]);
                    }
                }
            }
            $whitelabel = Configurations::getWhitelabel();
            $currency   = session('currency');
            $agent      = $this->agentsRepo->findByUserIdAndCurrency($user, $currency);
            //return [$agent,$user,$currency];
            $agents             = $this->agentsRepo->getAgentsByOwner($user, $currency);
            $users              = $this->agentsRepo->getUsersByAgent($agent->agent, $currency);
            $tree               = $this->agentsCollection->dependencyTree($agent, $agents, $users);
            $agentAndSubAgents  = $this->agentsCollection->formatAgentandSubAgents($agents);
            $providerTypes      = [
                ProviderTypes::$casino,
                ProviderTypes::$live_casino,
                ProviderTypes::$casino,
                ProviderTypes::$virtual,
                ProviderTypes::$sportbook,
                ProviderTypes::$racebook,
                ProviderTypes::$live_games,
                ProviderTypes::$poker
            ];
            $providers          = $providersRepo->getByWhitelabelAndTypes($whitelabel, $currency, $providerTypes);
            $data['currencies'] = Configurations::getCurrencies();
            $data['countries']  = $countriesRepo->all();
            $data['timezones']  = \DateTimeZone::listIdentifiers();
            $data['providers']  = $providers;
            $data['agent']      = $agent;
            $data['agents']     = $agentAndSubAgents;
            $data['tree']       = $tree;
            $data['title']      = _i('Agents Payments');

            return view('back.agents.reports.payments', $data);
        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    /**
     * Agents transactions
     *
     * @param int $agent User ID
     * @return Response
     */
    public function agentsTransactions($agent)
    {
        try {
            $currency     = session('currency');
            $providers    = [Providers::$agents, Providers::$agents_users];
            $transactions = $this->transactionsRepo->getByUserAndProviders($agent, $providers, $currency);
            $transactions = $this->agentsCollection->formatAgentTransactions($transactions);
            $data         = [
                'transactions' => $transactions
            ];
            return Utils::successResponse($data);
        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Show agents transactions by dates
     *
     * @return Application|Factory|View
     */
    public function agentsTransactionsByDates()
    {
        $data['title'] = _i('Agents transactions');
        return view('back.agents.reports.agents-transactions', $data);
    }

    /**
     * Agents transactions by dates data
     *
     * @param null $startDate
     * @param null $endDate
     * @return Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function agentsTransactionsByDatesData($startDate = null, $endDate = null)
    {
        try {
            if (! is_null($startDate) && ! is_null($endDate)) {
                $currency = session('currency');
                if (session('admin_id')) {
                    $user = session('admin_id');
                } else {
                    $user = auth()->user()->id;
                }
                $providers    = [Providers::$agents, Providers::$agents_users];
                $startDate    = Utils::startOfDayUtc($startDate);
                $endDate      = Utils::endOfDayUtc($endDate);
                $transactions = $this->transactionsRepo->getAgentsTransactions(
                    $user,
                    $providers,
                    $currency,
                    $startDate,
                    $endDate
                );
            } else {
                $transactions = [];
            }
            $financial = $this->agentsCollection->formatAgentsTransactionsTotals($transactions);
            return Utils::successResponse($financial);
        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Agents transactions Paginate
     *
     * @param int $agent User ID
     * @return Response
     */
    public function agentsTransactionsPaginate($agent, Request $request)
    {
        try {
            $offset = $request->has('start') ? $request->get('start') : 0;
            $limit  = $request->has('length') ? $request->get('length') : 2000;

            $startDate = Utils::startOfDayUtc($request->has('startDate') ? $request->get('startDate') : date('Y-m-d'));
            $endDate   = Utils::endOfDayUtc($request->has('endDate') ? $request->get('endDate') : date('Y-m-d'));
            $username  = $request->get('search')['value'] ?? null;
            $typeUser  = $request->has('typeUser') ? $request->get('typeUser') : 'all';

            $typeTransaction = 'credit';
            if (Gate::allows('access', Permissions::$users_search)) {
                $typeTransaction = $request->has('typeTransaction') ? $request->get('typeTransaction') : 'all';
            }
            $orderCol = [
                'column' => 'date',
                'order'  => 'asc',
            ];
            if ($request->has('order') && ! empty($request->get('order'))) {
                $orderCol = [
                    'column' => $request->get('columns')[$request->get('order')[0]['column']]['data'],
                    'order'  => $request->get('order')[0]['dir']
                ];
            }

            $currency  = session('currency');
            $providers = [Providers::$agents, Providers::$agents_users];

            //            $user = auth()->user()->id ? Auth::id() : null;
            //            if (is_null(Auth::user()->username) == 'romeo') {
            //                $userTmp = $this->usersRepo->findUserCurrencyByWhitelabel('wolf', session('currency'), Configurations::getWhitelabel());
            //                $user = isset($userTmp[0]->id) ? $userTmp[0]->id : null;
            //            }

            //TODO get ids children from father
            $arraySonIds = $this->reportAgentRepo->getIdsChildrenFromFather(
                $agent,
                session('currency'),
                Configurations::getWhitelabel()
            );

            //TODO get transactions with filter
            $transactions = $this->transactionsRepo->getByUserAndProvidersPaginate(
                $agent,
                $providers,
                $currency,
                $startDate,
                $endDate,
                $limit,
                $offset,
                $username,
                $typeUser,
                $arraySonIds,
                $orderCol,
                $typeTransaction
            );

            //TODO draw table in collection
            $data = $this->agentsCollection->formatAgentTransactionsPaginate(
                $transactions[0],
                $transactions[1],
                $request
            );

            return response()->json($data);
        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Retrieve and format paginated transactions for a specific agent.
     *
     * Retrieves paginated transaction data for a specified agent using the provided request parameters.
     * The transaction data is then formatted using the agents collection service based on the specified timezone.
     *
     * @param Request $request The HTTP request object containing parameters for filtering and formatting.
     * @param string|int $agent The ID or identifier of the agent whose transactions are to be retrieved.
     * @return JsonResponse|Response A JSON response containing the formatted transaction data or an error message.
     */
    public function transactions(Request $request, string|int $agent)
    : JsonResponse|Response {
        try {
            $transactions = $this->transactionsRepo->getUserProviderTransactionsPaginated($request, $agent);

            return response()->json(
                $this->agentsCollection->formatAgentTransactionsPaginated($transactions, $request->input('timezone'))
            );
        } catch (Exception|NotFoundExceptionInterface|ContainerExceptionInterface $ex) {
            Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Get agents transactions ticket
     *
     * @param int $ticketId Ticket ID
     */
    public function agentsTransactionsTicket($ticketId)
    {
        try {
            $whitelabel = Configurations::getWhitelabel();
            $ticket     = $this->transactionsRepo->ticketTransactionsUser($ticketId, $whitelabel);
            $this->agentsCollection->ticketFormatter($ticket);
            $filename = \Carbon\Carbon::now()->format('dmY-His');
            $pdf      = PDF::loadView('back.agents.ticket.ticket', ['ticket' => $ticket, 'filename' => $filename]);
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream();
        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'ticket_id' => $ticketId]);
            return Utils::failedResponse();
        }
    }

    /**
     * Agents transactions Totals
     *
     * @param int $agent User ID
     * @return Response
     */
    public function agentsTransactionsTotals($agent, Request $request)
    {
        try {
            $startDate = Utils::startOfDayUtc($request->has('startDate') ? $request->get('startDate') : date('Y-m-d'));
            $endDate   = Utils::endOfDayUtc($request->has('endDate') ? $request->get('endDate') : date('Y-m-d'));
            $typeUser  = $request->has('typeUser') ? $request->get('typeUser') : 'all';

            $currency  = session('currency');
            $providers = [Providers::$agents, Providers::$agents_users];
            $totals    = $this->transactionsRepo->getByUserAndProvidersTotales(
                $agent,
                $providers,
                $currency,
                $startDate,
                $endDate,
                $typeUser
            );

            return response()->json($this->agentsCollection->formatAgentTransactionsTotals($totals[0], $totals[1]));
        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Agents Tree Filter
     *
     * @param bool $status Status
     * @return Response
     */
    public function agentsTreeFilter($status)
    {
        try {
            if (session('admin_id')) {
                $user = session('admin_id');
            } else {
                $user = auth()->user()->id;
            }
            $currency = session('currency');
            $agent    = $this->agentsRepo->findByUserIdAndCurrency($user, $currency);
            $agents   = $this->agentsRepo->getAgentsByOwner($user, $currency);
            $users    = $this->agentsRepo->getUsersByAgent($agent->agent, $currency);

            $tree = $this->agentsCollection->dependencyTreeFilter($agent, $agents, $users, $status);
            return Utils::successResponse($tree);
        } catch (Exception $ex) {
        }
    }

    /**
     * Block Agents Data
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     * @deprecated
     */
    public function blockAgentsData(Request $request)
    {
        $this->validate($request, [
            'maker'       => ['required_if:lock_users,false'],
            'description' => ['required_if:lock_users,true'],
        ]);

        try {
            $user      = $request->user;
            $currency  = session('currency');
            $maker     = $request->maker;
            $category  = $request->category;
            $type      = $request->type;
            $lockUsers = $request->lock_users;

            $agent     = $this->agentsRepo->findByUserIdAndCurrency($user, $currency);
            $subAgents = $this->agentsRepo->getAgentsByOwner($user, $currency);
            if (! is_null($agent)) {
                $users = $this->agentsRepo->getUsersByAgent($agent->agent, $currency);
            } else {
                $users[] = [
                    'id' => $user
                ];
            }
            $usersToUpdate = $this->agentsCollection->formatDataLock(
                $lockUsers,
                $subAgents,
                $users,
                $agent,
                $currency,
                $category,
                $maker
            );
            $newStatus     = (bool)$request->type;
            $oldStatus     = ! $newStatus;
            if ($lockUsers == 'false') {
                if ($type == 'true') {
                    foreach ($usersToUpdate as $userToUpdate) {
                        $user     = $userToUpdate['user_id'];
                        $category = $userToUpdate['category'];
                        $data     = [
                            'currency_iso' => $userToUpdate['currency_iso'],
                            'category'     => $userToUpdate['category'],
                            'makers'       => $userToUpdate['makers'],
                            'user_id'      => $user,
                            'created_at'   => $userToUpdate['created_at'],
                            'updated_at'   => $userToUpdate['updated_at']
                        ];
                        $this->agentsRepo->updateBlockAgents($currency, $category, $user, $data);
                    }
                    $data = [
                        'title'   => _i('Locked provider'),
                        'message' => _i('The provider was locked to the agent and his entire tree'),
                        'close'   => _i('Close')
                    ];
                }

                if ($type == 'false') {
                    foreach ($usersToUpdate as $userToUpdate) {
                        $currencyIso  = $userToUpdate['currency_iso'];
                        $category     = $userToUpdate['category'];
                        $userId       = $userToUpdate['user_id'];
                        $makers       = json_decode($userToUpdate['makers']);
                        $unBlockMaker = array_values(array_diff($makers, [$maker]));
                        if (empty($unBlockMaker)) {
                            $this->agentsRepo->unBlockAgents($currencyIso, $category, $userId);
                        } else {
                            $data['makers'] = json_encode($unBlockMaker);
                            $this->agentsRepo->unBlockAgentsMaker($currencyIso, $category, $userId, $data);
                        }
                    }
                    $data = [
                        'title'   => _i('Unlocked provider'),
                        'message' => _i('The provider was unlocked to the agent and his entire tree'),
                        'close'   => _i('Close')
                    ];
                }
            }
            if ($lockUsers == 'true') {
                if ($type == 'true') {
                    foreach ($usersToUpdate as $userToUpdate) {
                        $user = $userToUpdate['user_id'];
                        $this->agentsRepo->blockUsers($user);
                        Sessions::deleteByUser($user);
                        $auditData = [
                            'ip'          => Utils::userIp(),
                            'user_id'     => auth()->user()->id,
                            'username'    => auth()->user()->username,
                            'old_status'  => $oldStatus,
                            'new_status'  => $newStatus,
                            'description' => $request->description
                        ];
                        Audits::store(
                            $user,
                            AuditTypes::$agent_user_status,
                            Configurations::getWhitelabel(),
                            $auditData
                        );
                    }
                    $data = [
                        'title'   => _i('Locked users'),
                        'message' => _i('The agent and his entire tree was locked'),
                        'close'   => _i('Close')
                    ];
                }
                if ($type == 'false') {
                    foreach ($usersToUpdate as $userToUpdate) {
                        $user     = $userToUpdate['user_id'];
                        $userData = $this->agentsRepo->statusActionByUser($user);
                        if (isset($userData->action) && $userData->action == ActionUser::$locked_higher) {
                            $data = [
                                'title'   => ActionUser::getName($userData->action),
                                'message' => _i('Contact your superior...'),
                                'close'   => _i('Close')
                            ];
                            return Utils::errorResponse(Codes::$not_found, $data);
                        }
                        $this->agentsRepo->unBlockUsers($user);

                        $auditData = [
                            'ip'          => Utils::userIp(),
                            'user_id'     => auth()->user()->id,
                            'username'    => auth()->user()->username,
                            'old_status'  => $oldStatus,
                            'new_status'  => $newStatus,
                            'description' => $request->description
                        ];
                        Audits::store(
                            $user,
                            AuditTypes::$agent_user_status,
                            Configurations::getWhitelabel(),
                            $auditData
                        );
                    }
                    $data = [
                        'title'   => _i('Unlocked users'),
                        'message' => _i('The agent and his entire tree was unlocked'),
                        'close'   => _i('Close')
                    ];
                }
            }
            return Utils::successResponse($data);
        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function lockProfile(Request $request)
    {
        $this->validate($request, [
            'maker'    => ['required_if:lock_users,false'],
            'lockType' => ['required'],
        ]);

        try {
            $userId   = $request->input('userId');
            $currency = session('currency');
            $maker    = $request->input('maker');
            $category = $request->input('category');
            $lockType = $request->boolean('lockType');

            $description = $lockType
                ? $request->input('descriptionLock')
                : $request->input('descriptionUnlock');

            $agent     = $this->agentsRepo->findByUserIdAndCurrency($userId, $currency);
            $subAgents = $this->agentsRepo->getAgentsByOwner($userId, $currency);
            $users     = ! is_null($agent) ? $this->agentsRepo->getUsersByAgent(
                $agent->agent,
                $currency
            ) : [['id' => $userId]];

            $usersToUpdate = $this->agentsCollection->formatDataLock(
                true,
                $subAgents,
                $users,
                $agent,
                $currency,
                $category,
                $maker
            );
            $newStatus     = $lockType;
            $oldStatus     = ! $newStatus;
            $authUser      = auth()->user();

            if ($lockType) {
                foreach ($usersToUpdate as $userToUpdate) {
                    $user = $userToUpdate['user_id'];
                    $this->agentsRepo->blockUsers($user);
                    Sessions::deleteByUser($user);
                    $auditData = [
                        'ip'          => Utils::userIp(),
                        'user_id'     => $authUser->id,
                        'username'    => $authUser->username,
                        'old_status'  => $oldStatus,
                        'new_status'  => $newStatus,
                        'description' => $description
                    ];
                    Audits::store(
                        $user,
                        AuditTypes::$agent_user_status,
                        Configurations::getWhitelabel(),
                        $auditData
                    );
                }
                $data = [
                    'title'   => _i('Locked users'),
                    'message' => _i('The agent and his entire tree was locked'),
                    'close'   => _i('Close')
                ];
            } else {
                foreach ($usersToUpdate as $userToUpdate) {
                    $user     = $userToUpdate['user_id'];
                    $userData = $this->agentsRepo->statusActionByUser($user);
                    if (isset($userData->action) && $userData->action == ActionUser::$locked_higher) {
                        $data = [
                            'title'   => ActionUser::getName($userData->action),
                            'message' => _i('Contact your superior...'),
                            'close'   => _i('Close')
                        ];
                        return Utils::errorResponse(Codes::$not_found, $data);
                    }
                    $this->agentsRepo->unBlockUsers($user);

                    $auditData = [
                        'ip'          => Utils::userIp(),
                        'user_id'     => $authUser->id,
                        'username'    => $authUser->username,
                        'old_status'  => $oldStatus,
                        'new_status'  => $newStatus,
                        'description' => $description
                    ];
                    Audits::store(
                        $user,
                        AuditTypes::$agent_user_status,
                        Configurations::getWhitelabel(),
                        $auditData
                    );
                }
                $data = [
                    'title'   => _i('Unlocked users'),
                    'message' => _i('The agent and his entire tree was unlocked'),
                    'close'   => _i('Close')
                ];
            }

            return Utils::successResponse($data);
        } catch (Exception $ex) {
            Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function blockAgent(Request $request)
    : Response {
        try {
            $rules = [
                'userId'   => ['required', 'exists:users,id'],
                'lockType' => ['required'],
            ];

            $userId      = $request->input('userId');
            $lockType    = $request->boolean('lockType');
            $description = $lockType
                ? $request->input('descriptionLock')
                : $request->input('descriptionUnlock');

            $validator = Validator::make([
                'userId'      => $userId,
                'lockType'    => $lockType,
                'description' => $description
            ], $rules, $this->custom_message());

            if ($validator->fails()) {
                return Utils::errorResponse(Codes::$forbidden, [
                    'title'   => __('Wrong Parameters'),
                    'message' => __('You need to fill in all the required fields'),
                    'data'    => $validator->errors()->getMessages(),
                    'close'   => _i('Close'),
                    'type'    => 'info'
                ]);
            }

            $statusUpdate = false;
            $data         = [];
            $authUser     = auth()->user();
            if ($lockType) {
                $data         = [
                    'action' => ActionUser::$locked_higher,
                    'status' => false,
                ];
                $statusUpdate = true;
            } else {
                $typeAudit = $this->auditsRepo->lastByType(
                    $userId,
                    AuditTypes::$agent_user_status,
                    Configurations::getWhitelabel()
                );
                $father    = false;
                if (! is_null($typeAudit) && isset($typeAudit->data->user_id)) {
                    $father = $this->usersCollection->treeFatherValidate($typeAudit->data->user_id, $authUser->id);
                    if ($typeAudit->data->user_id == Auth::id()) {
                        $father = true;
                    }
                }

                if ($father) {
                    $data         = [
                        'action' => ActionUser::$active,
                        'status' => true,
                    ];
                    $statusUpdate = true;
                }
            }

            if ($statusUpdate) {
                $this->usersRepo->update($userId, $data);

                Audits::store($userId, AuditTypes::$agent_user_status, Configurations::getWhitelabel(), [
                    'ip'          => Utils::userIp(),
                    'user_id'     => $authUser->id,
                    'username'    => $authUser->username,
                    'new_action'  => $lockType,
                    'description' => $description
                ]);

                return Utils::successResponse([
                    'title'   => _i('Status updated'),
                    'message' => _i('User status was updated successfully'),
                    'close'   => _i('Close')
                ]);
            }

            return Utils::errorResponse(Codes::$forbidden, [
                'title'   => ActionUser::getName(ActionUser::$locked_higher),
                'message' => __('This process requires superior access'),
                'close'   => _i('Close'),
                'type'    => 'info'
            ]);
        } catch (Exception $ex) {
            Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    public function custom_message()
    : array
    {
        return [
            'description.required' => __('Description is required'),
            'lock_type.required'   => __('Lock type is required'),
            'user_id.required'     => __('ID user is required'),
            'user_id.exists'       => __('ID user does not exist'),
        ];
    }

    /**
     * Create agent balance
     *
     * @param object $agent Agent data
     * @param string $currency Currency ISO
     * @param float $balance Agent balance
     */
    private function createAgentBalance($agent, $currency, $balance = 0)
    {
        $agentData = [
            'agent_id'     => $agent,
            'currency_iso' => $currency,
        ];
        $balance   = [
            'balance' => $balance
        ];
        $this->agentCurrenciesRepo->store($agentData, $balance);
    }

    /**
     * Show cash flow by dates
     *
     * @return Application|Factory|View
     */
    public function cashFlowByDates()
    {
        $data['title'] = _i('Cash flow');
        return view('back.agents.reports.cash-flow', $data);
    }

    /**
     * Agents cash flow by dates data
     *
     * @param TransactionsCollection $transactionsCollection
     * @param null|string $startDate Start date to filter
     * @param null|string $endDate End date to filter
     * @return Response
     */
    public function cashFlowByDatesData(
        TransactionsCollection $transactionsCollection,
        $startDate = null,
        $endDate = null
    ) {
        try {
            $currency   = session('currency');
            $whitelabel = Configurations::getWhitelabel();

            if (session('admin_id')) {
                $user     = session('admin_id');
                $username = session('admin_agent_username');
            } else {
                $user     = auth()->user()->id;
                $username = auth()->user()->username;
            }
            $startDate = Utils::startOfDayUtc(! is_null($startDate) ? $startDate : date('Y-m-d'));
            $endDate   = Utils::endOfDayUtc(! is_null($endDate) ? $endDate : date('Y-m-d'));

            $agents    = $this->agentsRepo->getAgentsByOwner($user, $currency);
            $agentsIds = [];

            foreach ($agents as $agent) {
                $agentsIds[] = $agent->user_id;
            }
            $financialDataTest = [];
            if (count($agentsIds) > 0) {
                $financialDataTest = $this->transactionsRepo->getCashFlowTransactionsNew(
                    $username,
                    $agentsIds,
                    $whitelabel,
                    $currency,
                    $startDate,
                    $endDate
                );
                //$financialData = $this->transactionsRepo->getCashFlowTransactions($username, $agentsIds, $whitelabel, $currency, $startDate, $endDate);
            }

            $financial = $transactionsCollection->formatCashFlowDataByUsers(
                $financialDataTest,
                $whitelabel,
                $currency,
                $startDate,
                $endDate
            );

            return Utils::successResponse($financial);
        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Sql temp change action by agent
     */
    public function changeActionByAgent()
    {
        try {
            $this->agentsRepo->updateActionTemp();

            $data = [
                'title'   => 'Campo Accion Cambiado',
                'message' => 'Campo accion cambiado por id 10 solo los agentes...',
                'close'   => _i('Close'),
            ];
            return Utils::successResponse($data);
        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Change agent type
     *
     * @param int $agent Agent ID
     * @return Response
     */
    public function changeAgentType($agent)
    {
        try {
            $agentData = [
                'master' => true
            ];
            $agentDb   = $this->agentsRepo->update($agent, $agentData);
            if (isset($agentDb->user_id)) {
                $userData = [
                    'type_user' => TypeUser::$agentMater
                ];
                $this->usersRepo->update($agentDb->user_id, $userData);
            }

            //$user = $this->agentsRepo->findAgentCashier($agent);
            //$this->agentsCollection->formatChangeAgentType($user);
            $data = [
                'title'   => _i('Agent type changed'),
                'message' => _i('Agent type was successfully changed'),
                'close'   => _i('Close'),
                //'user_type' => $user->type,
            ];
            return Utils::successResponse($data);
        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Change Type User Not Available
     * @param Request $request
     * @return array
     */
    public function changeTypeUser(Request $request)
    {
        //        $users = $this->usersRepo->sqlShareTmp('users');
        //        foreach ($users as $value) {
        //            $this->usersRepo->sqlShareTmp('update_rol', $value->id);
        //        }
        //
        //        return $users;
        //return 'no disponible';

        //TODO CHANGE TYPE USER
        $users = $this->usersRepo->sqlShareTmp('users');
        foreach ($users as $value) {
            $value->type_user = null;
            $agentTmp         = $this->usersRepo->sqlShareTmp('agent', $value->id)[0] ?? null;
            if (! is_null($agentTmp)) {
                $value->type_user = TypeUser::$agentCajero;
                if (isset($agentTmp->master) && $agentTmp->master) {
                    $value->type_user = TypeUser::$agentMater;
                }
            }

            $playerTmp = $this->usersRepo->sqlShareTmp('agent_user', $value->id)[0] ?? null;
            if (! is_null($playerTmp) && isset($playerTmp->agent_id)) {
                $value->type_user = TypeUser::$player;
            }
            //TODO UPDATE
            if (! is_null($value->type_user)) {
                $this->usersRepo->sqlShareTmp('update', $value->id, $value->type_user);
            }
        }

        return $users;
    }

    /**
     * Update Owner User
     * @param Request $request
     * @return array
     */
    public function updateOwnerUser(Request $request)
    {
        return 'Update Owner in Agents';
        $users = $this->usersRepo->sqlOwnerTmp('users', 'wolf');
        foreach ($users as $value) {
            $romeos = $this->usersRepo->sqlOwnerTmp('users', 'romeo', $value->whitelabel_id);
            foreach ($romeos as $romeo) {
                $user = $this->usersRepo->sqlOwnerTmp('update_agent', null, null, $value->id, $romeo->id);
            }
        }

        return $users;
    }

    /**
     * Consult Balance by Type
     * @param Request $request
     * @return JsonResponse
     */
    public function consultBalanceByType(Request $request)
    {
        try {
            $id       = $request->has('user') ? $request->get('user') : Auth::id();
            $type     = $request->has('type') ? $request->get('type') : 'agent';
            $currency = session('currency');
            $balance  = 0;
            if ($type == 'agent') {
                $agent   = $this->agentsRepo->balanceCurrentAgent($id, $currency);
                $balance = isset($agent->balance) ? number_format($agent->balance, 2) : 0;
            } /* else {
                $user = $this->agentsRepo->findUser($id);
                $master = false;
                $wallet = Wallet::getByClient($id, $currency);
                $balance = $wallet->data->wallet->balance;
                $agent = false;
                $walletId = $wallet->data->wallet->id;
                $myself = false;
            }*/

            $json_data = array(
                "status"  => true,
                "balance" => $balance
            );
        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            $json_data = array(
                "status" => false,
            );
        }

        return response()->json($json_data);
    }

    //TODO MODIFICAR SUPPORGL DEBAJO DE WOLF Y POR ECNIMA DE ADMIN

    /**
     * @param $usersRepo
     * @param $agentRepo
     * @param $agentCurrenciesRepo
     * @param $rolesRepo
     * @return string[]
     */
    public static function changeUserGlTmp($usersRepo, $agentRepo, $agentCurrenciesRepo, $rolesRepo)
    {
        //TODO buscar supportgl
        $gls = $usersRepo->sqlShareTmp('user_gl');
        foreach ($gls as $index => $value) {
            //TODO SI ES LA WL Y SI TIENE AGENTS ACTIVADO
            if ($value->active_agent) {
                //if(in_array($value->whitelabel_id,$arrayWl) && $value->active_agent){

                //TODO VER SI YA EXISTE EL USUARIO SUPPORTGL COMO AGENTE
                $existAgentGl = $agentRepo->existAgent($value->id);
                if (! isset($existAgentGl->id)) {
                    $adms = $usersRepo->sqlShareTmp('user_admin', null, null, $value->whitelabel_id);
                    foreach ($adms as $a => $admin) {
                        //TODO SI EXISTE RELACION DE AGENTE
                        $agentAdminExist = $agentRepo->existAgent($admin->id);
                        //                       Log::debug('changeUserGlTmp',[
                        //                           'userGl'=>$value,
                        //                           'userAdmin'=>$admin,
                        //                           'UserAdmin-Agent'=>$agentAdminExist
                        //                       ]);
                        //TODO GUARDANDO EL ID WOLF
                        if (! empty($agentAdminExist) && ! is_null($agentAdminExist->owner_id)) {
                            $wolf_id = $agentAdminExist->owner_id;

                            if (isset($agentAdminExist->id)) {
                                //TODO supportgl new agent de bajo del wolf
                                $newAgent = $agentRepo->store([
                                    'user_id'  => $value->id,
                                    'owner_id' => $wolf_id,
                                    'master'   => true
                                ]);
                                //TODO gent admin por debajo de supportgl
                                $agentRepo->update($agentAdminExist->id, [
                                    'owner_id' => $value->id
                                ]);
                                $currencies = Configurations::getCurrencies();
                                //TODO SALDO 0 por ser supportgl
                                foreach ($currencies as $itemCurrency => $cu) {
                                    $agentData = [
                                        'agent_id'     => $newAgent->id,
                                        'currency_iso' => $cu,
                                    ];
                                    $agentCurrenciesRepo->store($agentData, ['balance' => 0]);
                                }

                                $rolesRepo->deleteRoles(['user_id' => $value->id]);
                                $rolesRepo->assignRole(
                                    ['user_id' => $value->id, 'role_id' => Roles::$admin_Beet_sweet]
                                );
                            }
                        }
                    }
                }
            }
        }
        return ['OK'];
    }

    /**
     * Data Example Sql and Datatable
     * Data Of Example
     * @param Request $request
     * @return JsonResponse
     */
    public function dataTmp(Request $request)
    {
        return 'dataTmp->changeUserGlTmp';
        return AgentsController::changeUserGlTmp(
            $this->usersRepo,
            $this->agentsRepo,
            $this->agentCurrenciesRepo,
            $this->rolesRepo
        );

        $currency = session('currency');
        return $agent = 76;
    }

    /**
     * Consult Data of Transactions TimeLine
     * @param Request $request
     * @return Response
     */
    public function dataTransactionTimeline(Request $request)
    {
        try {
            $currency   = session('currency');
            $whitelabel = Configurations::getWhitelabel();
            $providers  = [Providers::$agents, Providers::$agents_users];
            //TODO CONVERSION OF ARRAY '{16,25}';
            $providers = '{' . implode(', ', $providers) . '}';
            $startDate = Utils::startOfDayUtc(
                $request->has('start_date') ? $request->get('start_date') : date('Y-m-d')
            );
            $endDate   = Utils::endOfDayUtc($request->has('end_date') ? $request->get('end_date') : date('Y-m-d'));
            $timezone  = session('timezone');

            $offset = $request->has('start') ? $request->get('start') : 0;
            $limit  = $request->has('length') ? $request->get('length') : 2000;
            //$user = $request->has('user_id')?$request->get('user_id'):Auth::id();
            $user = Auth::id();

            //TODO TEST ARRAYS OF IDS
            //CHANGE BY FUNCTION SQL DATABASE
            $agent  = $this->agentsRepo->findByUserIdAndCurrency($user, $currency);
            $agents = $this->agentsRepo->getAgentsByOwner($user, $currency);
            $users  = $this->agentsRepo->getUsersByAgent($agent->agent, $currency);
            $trees  = $this->agentsCollection->dependencyTreeIds($agent, $agents, $users);
            $trees  = '{' . implode(', ', $trees) . '}';

            //TODO Return View Data
            $transactions = $this->transactionsRepo->getTransactionsTimelinePage(
                $whitelabel,
                $currency,
                $startDate,
                $endDate,
                $providers,
                $trees,
                $limit,
                $offset
            );
            //$transactions = $this->transactionsRepo->getTransactionsTimelinePage($whitelabel, $currency, $startDate,$endDate,$providers,$user,$limit,$offset);

            $data = $this->transactionsCollection->formatTransactionTimeline(
                $transactions,
                $timezone,
                $request,
                $currency
            );

            return response()->json($data);
        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Deposits withdrawals Provider
     *
     * @return Application|Factory|View
     */
    public function depositsWithdrawalsProvider()
    {
        $data['title'] = _i('Deposits withdrawals provider by report');
        return view('back.agents.reports.deposits-withdrawals-by-provider', $data);
    }

    /**
     * Deposits withdrawals provider data
     *
     * @param Request $request
     * @return Response
     */
    public function depositsWithdrawalsProviderData(TransactionsCollection $transactionsCollection, Request $request)
    {
        try {
            $startDate = $request->start_date;
            $endDate   = $request->end_date;
            if (! is_null($startDate) && ! is_null($endDate)) {
                $transactionType = $request->transaction_type;
                $currency        = $request->currency;
                $whitelabel      = Configurations::getWhitelabel();
                $startDate       = Utils::startOfDayUtc($startDate);
                $endDate         = Utils::endOfDayUtc($endDate);
                $transactions    = $this->transactionsRepo->getDeposistsWithdrawalsProvider(
                    $currency,
                    $startDate,
                    $endDate,
                    $transactionType,
                    $whitelabel
                );
            } else {
                $transactions = [];
            }
            $data = $transactionsCollection->formatDeposistsWithdrawalsProvider($transactions);
            return Utils::successResponse($data);
        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     * Get exclude provider agent delete
     *
     * @param int $user User
     * @param string $category Provider
     * @param string $currency Currency ISO
     * @return Factory|View
     */
    public function excludeProviderAgentsDelete($user, $category, $currency)
    {
        try {
            $user = (int)$user;
            $this->agentsRepo->unBlockAgents($currency, $category, $user);
            $data = [
                'title'   => _i('User activated'),
                'message' => _i('User was activated correctly'),
                'close'   => _i('Close')
            ];
            return Utils::successResponse($data);
        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    /**
     * Get exclude provider agent list
     *
     * @param int $whitelabel Whitelabel
     * @return Factory|View
     */
    public function excludeProviderAgentsList(
        Request $request,
        $startDate = null,
        $endDate = null,
        $category = null,
        $maker = null,
        $currency = null
    ) {
        try {
            if (! is_null($startDate) && ! is_null($endDate)) {
                $agentsBlocked = [];
                $user          = auth()->user()->id;
                $currency_iso  = session('currency');
                $whitelabel    = Configurations::getWhitelabel();
                $agents        = $this->usersRepo->arraySonIds($user, $currency_iso, $whitelabel);
                $startDate     = Utils::startOfDayUtc($startDate);
                $endDate       = Utils::endOfDayUtc($endDate);
                $category      = $request->category;
                $maker         = $request->maker;
                $currency      = $request->currency;
                $users         = $this->usersRepo->getExcludeProviderUserByDates(
                    $currency,
                    $category,
                    $maker,
                    $whitelabel,
                    $startDate,
                    $endDate
                );
                foreach ($users as $user) {
                    if (in_array($user->user_id, $agents)) {
                        $agentsBlocked[] = $user;
                    }
                }
                if (! is_null($agentsBlocked)) {
                    $this->agentsCollection->formatExcludeMakersUser($agentsBlocked);
                    $data = [
                        'agents' => $agentsBlocked
                    ];
                } else {
                    $data = [
                        'agents' => []
                    ];
                }
            } else {
                $data = [
                    'agents' => []
                ];
            }
            return Utils::successResponse($data);
        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    /**
     * Show exclude providers agents
     * @param ProvidersRepo $providersRepo
     * @return \Illuminate\Contracts\Foundation\Application|Factory|View
     */
    public function excludeProvidersAgents(ProvidersRepo $providersRepo)
    {
        $currency                = session('currency');
        $whitelabel              = Configurations::getWhitelabel();
        $providers               = $providersRepo->getByWhitelabel($whitelabel, $currency);
        $categories              = $this->gamesRepo->getCategories();
        $makers                  = $this->gamesRepo->getMakers();
        $data['currency_client'] = Configurations::getCurrenciesByWhitelabel($whitelabel);
        $data['providers']       = $providers;
        $data['whitelabel']      = $whitelabel;
        $data['categories']      = $categories;
        $data['makers']          = $makers;
        $data['title']           = _i('Exclude agents from providers');
        return view('back.agents.reports.exclude-providers-agents', $data);
    }

    /**
     *  Exclude providers agents data
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws ValidationException
     */
    public function excludeProvidersAgentsData(Request $request)
    {
        $this->validate($request, [
            'username' => 'required',
            'currency' => 'required',
            'maker'    => 'required',
        ]);

        try {
            $usersData  = [];
            $user       = auth()->user()->id;
            $username   = strtolower(trim($request->username));
            $category   = $request->category;
            $maker      = $request->maker;
            $whitelabel = Configurations::getWhitelabel();
            $currency   = $request->currency;
            $userData   = $this->usersRepo->getByUsername($username, $whitelabel);
            $agents     = $this->usersRepo->arraySonIds($user, $currency, $whitelabel);

            if (! is_null($userData)) {
                $date       = Carbon::now('UTC')->format('Y-m-d H:i:s');
                $makers     = [$maker];
                $categories = [];
                if (in_array($userData->id, $agents)) {
                    if (is_null($category)) {
                        $categories = $this->gamesRepo->getCategoriesByMaker($maker);
                        $categories = array_column($categories->toArray(), 'category');
                    } else {
                        $categories[] = $category;
                    }
                    foreach ($categories as $category) {
                        $excludeUser   = $this->usersRepo->findExcludeMakerUser($category, $userData->id, $currency);
                        $makersExclude = isset($excludeUser->makers) ? json_decode($excludeUser->makers) : [];
                        $dataMakers    = array_merge($makers, $makersExclude);
                        $makersArray   = array_values(array_filter(array_unique($dataMakers)));
                        $usersData[]   = [
                            'category'     => $category,
                            'makers'       => json_encode($makersArray),
                            'currency_iso' => $currency
                        ];
                    }
                    foreach ($usersData as $userToUpdate) {
                        $data = [
                            'currency_iso' => $userToUpdate['currency_iso'],
                            'category'     => $userToUpdate['category'],
                            'makers'       => $userToUpdate['makers'],
                            'user_id'      => $userData->id,
                            'created_at'   => $date,
                            'updated_at'   => $date
                        ];
                        $this->agentsRepo->updateBlockAgents(
                            $currency,
                            $userToUpdate['category'],
                            $userData->id,
                            $data
                        );
                    }
                    $data = [
                        'title'   => _i('Excluded user'),
                        'message' => _i('The user has been successfully excluded'),
                        'close'   => _i('Close'),
                    ];
                    return Utils::successResponse($data);
                } else {
                    $data = [
                        'title'   => _i('You cannot block providers for this user'),
                        'message' => _i("Please check and try again"),
                        'close'   => _i('Close')
                    ];
                    return Utils::errorResponse(Codes::$forbidden, $data);
                }
            } else {
                $data = [
                    'title'   => _i('The user does not exist'),
                    'message' => _i("Please check and try again"),
                    'close'   => _i('Close')
                ];
                return Utils::errorResponse(Codes::$forbidden, $data);
            }
        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Data Financial State New "for support"
     * @param ProvidersRepo $providersRepo
     * @param $user
     * @param $startDate
     * @param $endDate
     * @return Response
     * @deprecated
     */
    public function financialStateData(
        Request $request,
        ProvidersRepo $providersRepo,
        $user = null,
        $startDate = null,
        $endDate = null
    ) {
        //try {
        if (is_null($user)) {
            $user = Auth::id();
        }

        //if(in_array(Roles::$admin_Beet_sweet, session('roles'))){
        $percentage = $this->agentsRepo->myPercentageByCurrency($user, session('currency'));
        $percentage = ! empty($percentage) ? $percentage[0]->percentage : null;
        //}

        if (Auth::user()->username == 'romeo') {
            $userTmp = $this->usersRepo->findUserCurrencyByWhitelabel(
                'wolf',
                session('currency'),
                Configurations::getWhitelabel()
            );

            $user       = isset($userTmp[0]->id) ? $userTmp[0]->id : null;
            $percentage = null;
        }

        $sons = $this->closuresUsersTotals2023Repo->getUsersAgentsSon(
            Configurations::getWhitelabel(),
            session('currency'),
            $user
        );
        $data = [
            'table' => $this->agentsCollection->closuresTotalsByAgentGroupProviderHour(
                $sons,
                Configurations::getWhitelabel(),
                session('currency'),
                Utils::startOfDayUtc($startDate),
                Utils::endOfDayUtc($endDate),
                $percentage
            )
        ];

        return Utils::successResponse($data);
        //        } catch (\Exception $ex) {
        //            Log::error(__METHOD__, ['exception' => $ex, 'start_date' => $startDate, 'end_date' => $endDate]);
        //            return Utils::failedResponse();
        //        }

    }

    /**
     * @param string|null $userId
     * @param string|null $startDate
     * @param string|null $endDate
     * @return Application|Factory|\Illuminate\Contracts\View\View
     */
    public function showFinancialStatement(string $userId = null, string $startDate = null, string $endDate = null)
    {
        $userId     = is_null($userId) ? Auth::id() : $userId;
        $currency   = session('currency');
        $percentage = $this->agentsRepo->myPercentageByCurrency($userId, $currency);

        $percentage   = $percentage[0]->percentage ?? null;
        $whitelabelId = Configurations::getWhitelabel();

        if (Auth::user()->username === \App\Agents\Enums\Username::ROMEO) {
            $userTmp    = $this->usersRepo->findUserCurrencyByWhitelabel(AgentType::WOLF, $currency, $whitelabelId);
            $userId     = $userTmp[0]->id ?? null;
            $percentage = null;
        }

        $userSonData = $this->closuresUsersTotals2023Repo->getUsersAgentsSon($whitelabelId, $currency, $userId);

        /*$this->agentsCollection->hourlyAgentGroupTotals(
            $userSonData,
            $whitelabelId,
            $currency,
            $startDate,
            $endDate,
            $percentage
        );*/

        $userIds = array_map(function ($item) {
            return $item->user_id;
        }, $userSonData);

        return view('back.reports.financial.statement', [
            'items' => $this->closuresUsersTotals2023Repo->getClosureReport(
                $userIds,
                $whitelabelId,
                $currency,
                $startDate,
                $endDate
            )
        ]);
    }

    /**
     * Data Financial State Details
     * @param Request $request
     * @param $user
     * @param $startDate
     * @param $endDate
     * @return Response
     */
    public function financialStateDataDetails(Request $request, $user = null, $startDate = null, $endDate = null)
    {
        try {
            if (is_null($user)) {
                $user = Auth::id();
            }
            $percentage = null;
            $username   = null;
            if (
                $request->has('username_like') && ! is_null($request->get('username_like'))
                && $request->get(
                    'username_like'
                ) != 'null'
            ) {
                $username = $request->get('username_like');
            }
            $provider = null;
            if (
                $request->has('provider_id') && ! is_null($request->get('provider_id'))
                && $request->get(
                    'provider_id'
                ) != 'null'
            ) {
                $provider = $request->get('provider_id');
            }

            if (! in_array(Roles::$admin_Beet_sweet, session('roles'))) {
                //TODO TODOS => EJE:SUPPORT
                $table = $this->closuresUsersTotals2023Repo->getClosureTotalsByProviderAndMaker(
                    Configurations::getWhitelabel(),
                    session('currency'),
                    Utils::startOfDayUtc($startDate),
                    Utils::endOfDayUtc($endDate),
                    $provider,
                    '%' . $username . '%'
                );
            } else {
                //                $percentage = $this->agentsRepo->myPercentageByCurrency(Auth::id(),session('currency'));
                //                $percentage = !empty($percentage) ? $percentage[0]->percentage:null;
                $table = $this->closuresUsersTotals2023Repo->getClosureTotalsByProviderAndMakerWithSon(
                    Configurations::getWhitelabel(),
                    session('currency'),
                    Utils::startOfDayUtc($startDate),
                    Utils::endOfDayUtc($endDate),
                    $user,
                    $provider,
                    '%' . $username . '%'
                );
            }
            $data = [
                'table' => $this->agentsCollection->closuresTotalsProviderAndMaker($table, $percentage)
            ];
            //            $dataTmp = [
            //                $table, $user, Configurations::getWhitelabel(), session('currency'), Utils::startOfDayUtc($startDate), Utils::endOfDayUtc($endDate)
            //            ];
            return Utils::successResponse($data);
        } catch (Exception $ex) {
            Log::error(__METHOD__, ['exception' => $ex, 'start_date' => $startDate, 'end_date' => $endDate]);
            return Utils::failedResponse();
        }
    }

    /**
     * Data Financial State Makers
     * @param Request $request
     * @param $startDate
     * @param $endDate
     * @param $currency_iso
     * @param $provider_id
     * @param $whitelabel_id
     * @return Response
     */
    public function financialStateDataMakers(
        Request $request,
        $startDate = null,
        $endDate = null,
        $currency_iso = null,
        $provider_id = null,
        $whitelabel_id = null
    ) {
        try {
            // dd([$request->currency_iso, $request->whitelabel_id]);
            if (! in_array(Roles::$admin_Beet_sweet, session('roles'))) {
                //TODO TODOS => EJE:SUPPORT
                $table = $this->closuresUsersTotals2023Repo->getClosureTotalsByProviderAndMakerGlobal(
                    Utils::startOfDayUtc($startDate),
                    Utils::endOfDayUtc($endDate),
                    $request->currency_iso,
                    $request->provider_id,
                    $request->whitelabel_id
                );
            } else {
                $table = $this->closuresUsersTotals2023Repo->getClosureTotalsByProviderAndMakerGlobal(
                    Utils::startOfDayUtc($startDate),
                    Utils::endOfDayUtc($endDate),
                    $request->currency_iso,
                    $request->provider_id,
                    $request->whitelabel_id
                );
            }
            $data = [
                'table' => $this->agentsCollection->closuresTotalsProviderAndMakerGlobal($table)
            ];

            return Utils::successResponse($data);
        } catch (Exception $ex) {
            Log::error(__METHOD__, ['exception' => $ex, 'start_date' => $startDate, 'end_date' => $endDate]);
            return Utils::failedResponse();
        }
    }

    /**
     * Data Financial State Makers Totals
     *
     * @param int $agent User ID
     * @return Response
     */
    public function financialStateDataMakersTotals(Request $request, $startDate = null, $endDate = null)
    {
        try {
            $startDate = Utils::startOfDayUtc($request->has('startDate') ? $request->get('startDate') : date('Y-m-d'));
            $endDate   = Utils::endOfDayUtc($request->has('endDate') ? $request->get('endDate') : date('Y-m-d'));
            // $currency = session('currency');
            // $providers = [Providers::$agents, Providers::$agents_users];
            $totals = $this->transactionsRepo->getFinancialDataMakersTotals(
                $startDate,
                $endDate,
                $request->currency_iso,
                $request->provider_id,
                $request->whitelabel_id
            );

            return response()->json($this->agentsCollection->formatAgentDataMakersTotals($totals));
        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * @param ProvidersRepo $providersRepo
     * @param $user
     * @param $startDate
     * @param $endDate
     * @return Response
     */
    public function financialStateDataRow2(
        ProvidersRepo $providersRepo,
        $user = null,
        $startDate = null,
        $endDate = null
    ) {
        try {
            $timezone        = session('timezone');
            $today           = Carbon::now()->setTimezone($timezone);
            $endDateOriginal = $endDate;
            $startDate       = Utils::startOfDayUtc($startDate);
            $endDate         = Utils::endOfDayUtc($endDate);
            $currency        = session('currency');
            $whitelabel      = Configurations::getWhitelabel();
            $providerTypes   = [
                ProviderTypes::$casino,
                ProviderTypes::$live_casino,
                ProviderTypes::$virtual,
                ProviderTypes::$sportbook,
                ProviderTypes::$racebook,
                ProviderTypes::$live_games,
                ProviderTypes::$poker
            ];
            $providers       = $providersRepo->getByWhitelabelAndTypes($whitelabel, $currency, $providerTypes);
            $agent           = $this->agentsRepo->findByUserIdAndCurrency($user, $currency);
            $agents          = $this->agentsRepo->getAgentsByOwner($user, $currency);
            $users           = $this->agentsRepo->getUsersByAgent($agent->agent, $currency);
            $table           = $this->agentsCollection->financialStateRow2(
                $whitelabel,
                $agents,
                $users,
                $currency,
                $providers,
                $startDate,
                $endDate,
                $endDateOriginal,
                $today
            );
            $data            = [
                'table' => $table
            ];
            return Utils::successResponse($data);
        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'start_date' => $startDate, 'end_date' => $endDate]);
            return Utils::failedResponse();
        }
    }

    /**
     * Financial state data Consult Old
     * @param ProvidersRepo $providersRepo
     * @param null|int $user User ID
     * @param null|string $startDate
     * @param null|string $endDate
     * @return Response
     */
    public function financialStateData_original(
        ProvidersRepo $providersRepo,
        $user = null,
        $startDate = null,
        $endDate = null
    ) {
        try {
            $timezone        = session('timezone');
            $today           = Carbon::now()->setTimezone($timezone);
            $endDateOriginal = $endDate;
            //            $startDate = Utils::startOfDayUtc($startDate);
            //            $endDate = Utils::endOfDayUtc($endDate);

            $startDate = Utils::startOfDayUtc($startDate);
            $endDate   = Utils::endOfDayUtc($endDate);

            $currency      = session('currency');
            $whitelabel    = Configurations::getWhitelabel();
            $providerTypes = [
                ProviderTypes::$casino,
                ProviderTypes::$live_casino,
                ProviderTypes::$virtual,
                ProviderTypes::$sportbook,
                ProviderTypes::$racebook,
                ProviderTypes::$live_games,
                ProviderTypes::$poker
            ];
            $providers     = $providersRepo->getByWhitelabelAndTypes($whitelabel, $currency, $providerTypes);
            $agent         = $this->agentsRepo->findByUserIdAndCurrency($user, $currency);
            $agents        = $this->agentsRepo->getAgentsByOwner($user, $currency);
            $users         = $this->agentsRepo->getUsersByAgent($agent->agent, $currency);
            $table         = $this->agentsCollection->financialState(
                $whitelabel,
                $agents,
                $users,
                $currency,
                $providers,
                $startDate,
                $endDate,
                $endDateOriginal,
                $today
            );
            $data          = [
                'table' => $table
            ];
            return Utils::successResponse($data);
        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'start_date' => $startDate, 'end_date' => $endDate]);
            return Utils::failedResponse();
        }
    }

    /**
     * View Financial State
     * @param ClosuresUsersTotalsRepo $closuresUsersTotalsRepo
     * @param ReportsCollection $reportsCollection
     * @return Application|Factory|\Illuminate\Contracts\View\View
     */
    public function financialState(
        ClosuresUsersTotalsRepo $closuresUsersTotalsRepo,
        ReportsCollection $reportsCollection
    ) {
        $currency   = session('currency');
        $whitelabel = Configurations::getWhitelabel();
        if (session('admin_id')) {
            $data['user'] = session('admin_id');
        } else {
            $data['user'] = auth()->user()->id;
        }
        $data['title'] = _i('Financial state report');
        return view('back.agents.reports.financial-state', $data);
    }

    /**
     * Data Financial State By Provider
     * @param ProvidersRepo $providersRepo
     * @param ProvidersTypesRepo $providersTypesRepo
     * @param $user
     * @param $startDate
     * @param $endDate
     * @return Response
     */
    public function financialStateData_provider(
        Request $request,
        ProvidersRepo $providersRepo,
        ProvidersTypesRepo $providersTypesRepo,
        $user = null,
        $startDate = null,
        $endDate = null
    ) {
        //return [Roles::$support,session('roles'),in_array(Roles::$support, session('roles')),Auth::user()->username];
        //        try {
        $percentage = null;
        if (in_array(Roles::$support, session('roles'))) {
            //TODO TODOS => EJE:SUPPORT
            $table = $this->closuresUsersTotals2023Repo->getClosureByProviders(
                Configurations::getWhitelabel(),
                session('currency'),
                Utils::startOfDayUtc($startDate),
                Utils::endOfDayUtc($endDate),
                null,
                2000,
                0
            );
            //$table = $this->closuresUsersTotals2023Repo->getClosureTotalsByWhitelabelAndProviders(Configurations::getWhitelabel(), session('currency'), Utils::startOfDayUtc($startDate), Utils::endOfDayUtc($endDate));

        } else {
            //TODO FALTA EL TOTAL DE ITEMS

            //                $closureRepo = new ClosuresUsersTotals2023Repo();
            //                //TODO STATUS OF PROVIDERS IN PROD
            //                $arrayProviderTmp = array_map(function ($val) {
            //                    return $val->id;
            //                }, $closureRepo->getProvidersActiveByCredentials(true, session('currency'), Configurations::getWhitelabel()));
            //
            //                $providersString = '{' . implode(',', $arrayProviderTmp) . '}';
            //
            //                $percentage = $this->agentsRepo->myPercentageByCurrency(Auth::id(), session('currency'));
            //                $percentage = !empty($percentage) ? $percentage[0]->percentage : null;
            $table = $this->closuresUsersTotals2023Repo->getClosureByProviders(
                Configurations::getWhitelabel(),
                session('currency'),
                Utils::startOfDayUtc($startDate),
                Utils::endOfDayUtc($endDate),
                Auth::id(),
                1000,
                0
            );
        }
        $total_items = isset($table[0]->total_items) ? $table[0]->total_items : 0;
        $data        = $this->agentsCollection->formatClosuresTotalsProviderPaginate(
            $table,
            $total_items,
            $percentage,
            $request
        );

        return response()->json($data);

        //        } catch (\Exception $ex) {
        //            \Log::error(__METHOD__, ['exception' => $ex, 'start_date' => $startDate, 'end_date' => $endDate]);
        //            return Utils::failedResponse();
        //        }
    }

    /**
     * Data Financial State By Username
     * @param Request $request
     * @param ProvidersRepo $providersRepo
     * @param ProvidersTypesRepo $providersTypesRepo
     * @param int $user User Id
     * @param $startDate
     * @param $endDate
     * @return Response
     */
    public function financialStateData_username(
        Request $request,
        ProvidersRepo $providersRepo,
        ProvidersTypesRepo $providersTypesRepo,
        $user = null,
        $startDate = null,
        $endDate = null
    ) {
        //        try {
        //             TODO NEW CONSULT para el reporte
        //            $username = $request->has('username_like') ? '%' . $request->get('username_like') . '%':null;
        //            $table = $this->closuresUsersTotals2023Repo->getClosureByUsername(Configurations::getWhitelabel(), session('currency'), Utils::startOfDayUtc($startDate), Utils::endOfDayUtc($endDate), Auth::user()->id,$username,1000,0);
        //
        //            $percentage=null;
        //            $total_items = isset($table[0]->total_items)?$table[0]->total_items:0;
        //            $data = [
        //                'table' => $this->agentsCollection->closuresByUsername($table, $total_items,$percentage, $request),
        //            ];
        //
        //            return response()->json($data);


        $percentage = null;
        if (in_array(Roles::$support, session('roles'))) {
            //TODO TODOS => EJE:SUPPORT
            if ($request->has('username_like') && ! is_null($request->get('username_like'))) {
                $table = $this->closuresUsersTotals2023Repo->getClosureTotalsByUsername(
                    Configurations::getWhitelabel(),
                    session('currency'),
                    Utils::startOfDayUtc($startDate),
                    Utils::endOfDayUtc($endDate),
                    '%' . $request->get('username_like') . '%'
                );
            } else {
                $table = $this->closuresUsersTotals2023Repo->getClosureTotalsLimit(
                    Configurations::getWhitelabel(),
                    session('currency'),
                    Utils::startOfDayUtc($startDate),
                    Utils::endOfDayUtc($endDate)
                );
            }
        } else {
            $percentage = $this->agentsRepo->myPercentageByCurrency(Auth::id(), session('currency'));
            $percentage = ! empty($percentage) ? $percentage[0]->percentage : null;

            //TODO ADMIN_BEET_SWEET
            if ($request->has('username_like') && ! is_null($request->get('username_like'))) {
                //TODO validar user_id para tener dinamismo
                $table = $this->closuresUsersTotals2023Repo->getClosureTotalsByUsernameWithSon(
                    Configurations::getWhitelabel(),
                    session('currency'),
                    Utils::startOfDayUtc($startDate),
                    Utils::endOfDayUtc($endDate),
                    '%' . $request->get('username_like') . '%',
                    Auth::user()->id
                );
                //$table = $this->closuresUsersTotals2023Repo->getClosureTotalsHourByUsernameWithSon(Configurations::getWhitelabel(), session('currency'), Utils::startOfDayUtc($startDate), Utils::endOfDayUtc($endDate), '%' . $request->get('username_like') . '%', Auth::user()->id);
            } else {
                $table = $this->closuresUsersTotals2023Repo->getClosureTotalsWithSon(
                    Configurations::getWhitelabel(),
                    session('currency'),
                    Utils::startOfDayUtc($startDate),
                    Utils::endOfDayUtc($endDate),
                    Auth::user()->id
                );
                //$table = $this->closuresUsersTotals2023Repo->getClosureTotalsHourWithSon(Configurations::getWhitelabel(), session('currency'), Utils::startOfDayUtc($startDate), Utils::endOfDayUtc($endDate), Auth::user()->id);
            }
        }

        $data = [
            'table' => $this->agentsCollection->closuresTotalUsername($table, $percentage)
        ];

        return Utils::successResponse($data);

        //        } catch (\Exception $ex) {
        //            \Log::error(__METHOD__, ['exception' => $ex, 'start_date' => $startDate, 'end_date' => $endDate]);
        //            return Utils::failedResponse();
        //        }
    }

    /**
     * View Financial State Details
     * @return Application|Factory|\Illuminate\Contracts\View\View
     */
    public function financialStateDetails()
    {
        $currency   = session('currency');
        $whitelabel = Configurations::getWhitelabel();
        if (session('admin_id')) {
            $data['user'] = session('admin_id');
        } else {
            $data['user'] = auth()->user()->id;
        }

        $data['providers'] = $this->closuresUsersTotals2023Repo->getProvidersActiveByCredentials(
            true,
            $currency,
            $whitelabel
        );
        $data['title']     = _i('Financial statement report details');
        return view('back.agents.reports.financial-state-details', $data);
    }

    /**
     * View Financial State Makers
     * @return Application|Factory|\Illuminate\Contracts\View\View
     */
    public function financialStateMakers()
    {
        $whitelabel = null;
        $currency   = null;
        if (session('admin_id')) {
            $data['user'] = session('admin_id');
        } else {
            $data['user'] = auth()->user()->id;
        }
        $data['currencies'] = Configurations::getCurrencies();
        // $data['currencies'] = $this->currenciesRepo->all();
        // $data['whitelabels'] = $this->whitelabelsGamesRepo->all();
        $data['providers'] = $this->closuresUsersTotals2023Repo->getProvidersByCurrencyAndCredentials(
            true,
            $currency,
            $whitelabel
        );
        $data['title']     = _i('Financial statement report makers');
        return view('back.agents.reports.financial-state-makers', $data);
    }

    /**
     * View Financial State Makers Details
     * @return Application|Factory|\Illuminate\Contracts\View\View
     */
    public function financialStateMakersDetails()
    {
        $whitelabel = null;
        $currency   = null;
        if (session('admin_id')) {
            $data['user'] = session('admin_id');
        } else {
            $data['user'] = auth()->user()->id;
        }
        $data['currencies'] = Configurations::getCurrencies();
        // $data['currencies'] = $this->currenciesRepo->all();
        $data['whitelabel'] = $this->whitelabelsGamesRepo->all();
        // dd($data);
        $data['providers'] = $this->closuresUsersTotals2023Repo->getProvidersByCurrencyAndCredentials(
            true,
            $currency,
            $whitelabel
        );
        $data['title']     = _i('Financial statement report makers details');
        return view('back.agents.reports.financial-state-makers-details', $data);
    }

    /**
     * View Financial State By Provider
     * @param ClosuresUsersTotalsRepo $closuresUsersTotalsRepo
     * @param ReportsCollection $reportsCollection
     * @return Application|Factory|\Illuminate\Contracts\View\View
     */
    public function financialStateProvider(
        ClosuresUsersTotalsRepo $closuresUsersTotalsRepo,
        ReportsCollection $reportsCollection
    ) {
        if (session('admin_id')) {
            $data['user'] = session('admin_id');
        } else {
            $data['user'] = auth()->user()->id;
        }
        $data['title'] = _i('Financial state report') . ' (' . _i('Provider') . ')';
        return view('back.agents.reports.financial-state-provider', $data);
    }

    /**
     * Financial state summary bonus data
     *
     * @param null|int $user User ID
     * @param null|string $startDate
     * @param null|string $endDate
     * @return Response
     */
    public function financialStateSummaryBonusData($user = null, $startDate = null, $endDate = null)
    {
        try {
            $startDate  = Utils::startOfDayUtc($startDate);
            $endDate    = Utils::endOfDayUtc($endDate);
            $currency   = session('currency');
            $whitelabel = Configurations::getWhitelabel();
            $agent      = $this->agentsRepo->findByUserIdAndCurrency($user, $currency);
            $agents     = $this->agentsRepo->getAgentsByOwner($user, $currency);
            $users      = $this->agentsRepo->getUsersByAgent($agent->agent, $currency);
            $table      = $this->agentsCollection->financialStateSummaryBonus(
                $whitelabel,
                $agents,
                $users,
                $currency,
                $startDate,
                $endDate
            );
            $data       = [
                'table' => $table
            ];
            return Utils::successResponse($data);
        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'start_date' => $startDate, 'end_date' => $endDate]);
            return Utils::failedResponse();
        }
    }

    /**
     * Financial state summary bonus
     *
     * @return Application|Factory|View
     */
    public function financialStateSummaryBonus()
    {
        $data['user'] = auth()->user()->id;
        if (session('admin_id')) {
            $data['user'] = session('admin_id');
        } else {
            $data['user'] = auth()->user()->id;
        }
        $data['title'] = _i('Financial state report - Summary (Include bonuses)');
        return view('back.agents.reports.financial-state-summary-bonus', $data);
    }

    /**
     * Financial state summary data
     *
     * @param null|int $user User ID
     * @param null|string $startDate
     * @param null|string $endDate
     * @return Response
     */
    public function financialStateSummaryData($user = null, $startDate = null, $endDate = null)
    {
        try {
            $startDate  = Utils::startOfDayUtc($startDate);
            $endDate    = Utils::endOfDayUtc($endDate);
            $currency   = session('currency');
            $whitelabel = Configurations::getWhitelabel();
            //TODO PERCENTAGE AGENT
            $iAgent = $this->agentsRepo->iAgent($user);

            $agent  = $this->agentsRepo->findByUserIdAndCurrency($user, $currency);
            $agents = $this->agentsRepo->getAgentsByOwner($user, $currency);
            $users  = $this->agentsRepo->getUsersByAgent($agent->agent, $currency);
            $table  = $this->agentsCollection->financialStateSummary(
                $whitelabel,
                $agents,
                $users,
                $currency,
                $startDate,
                $endDate,
                $iAgent
            );
            $data   = [
                'table' => $table
            ];
            return Utils::successResponse($data);
        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'start_date' => $startDate, 'end_date' => $endDate]);
            return Utils::failedResponse();
        }
    }

    /**
     * Financial state summary
     *
     * @return Application|Factory|View
     */
    public function financialStateSummary()
    {
        $data['user'] = auth()->user()->id;
        if (session('admin_id')) {
            $data['user'] = session('admin_id');
        } else {
            $data['user'] = auth()->user()->id;
        }
        $data['title'] = _i('Financial state report - Summary');
        return view('back.agents.reports.financial-state-summary', $data);
    }

    /**
     * Summary State Financial New
     * @param $user
     * @param $startDate
     * @param $endDate
     * @return Response
     */
    public function financialStateSummaryDataNew(Request $request, $user = null, $startDate = null, $endDate = null)
    {
        try {
            if (is_null($user)) {
                $user = Auth::id();
            }
            //TODO REVISAR DATA TOTALES
            //            $percentage = null;
            //            if (!in_array(Roles::$admin_Beet_sweet, session('roles'))) {
            //                //TODO TODOS => EJE:SUPPORT
            //                $table = $this->closuresUsersTotals2023Repo->getClosureTotalsByWhitelabel(Configurations::getWhitelabel(), session('currency'),Utils::startOfDayUtc($startDate) ,Utils::endOfDayUtc($endDate));
            //            }else{
            $percentage = $this->agentsRepo->myPercentageByCurrency($user, session('currency'));
            $percentage = ! empty($percentage) ? $percentage[0]->percentage : null;

            //$table = $this->closuresUsersTotals2023Repo->getClosureTotalsByWhitelabelWithSon(Configurations::getWhitelabel(), session('currency'), Utils::startOfDayUtc($startDate), Utils::endOfDayUtc($endDate), $user);

            //TODO ENVIAR CAMPO _hour para consultar la otra tabla
            // if ($request->has('_hour') && !empty($request->get('_hour')) && $request->get('_hour') == '_hour') {
            //                Log::debug('financialStateSummaryDataNew:field _hour',[
            //                    Utils::startOfDayUtc($startDate), Utils::endOfDayUtc($endDate)
            //                ]);
            $table = $this->closuresUsersTotals2023Repo->getClosureTotalsByWhitelabelWithSonHour(
                Configurations::getWhitelabel(),
                session('currency'),
                Utils::startOfDayUtc($startDate),
                Utils::endOfDayUtc($endDate),
                $user
            );

            //  }

            //            }
            //TODO AGENT
            //return $table;
            $data = [
                //'table' => $this->agentsCollection->closuresTotalsByWhitelabels($table,$percentage),
                'table' => $this->agentsCollection->closuresTotalsByWhitelabelsSymple($table, $percentage)
            ];
            return Utils::successResponse($data);
        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'start_date' => $startDate, 'end_date' => $endDate]);
            return Utils::failedResponse();
        }
    }

    /**
     * View Financial State By Username
     * @param ClosuresUsersTotalsRepo $closuresUsersTotalsRepo
     * @param ReportsCollection $reportsCollection
     * @return Application|Factory|\Illuminate\Contracts\View\View
     */
    public function financialStateUsername(
        ClosuresUsersTotalsRepo $closuresUsersTotalsRepo,
        ReportsCollection $reportsCollection
    ) {
        if (session('admin_id')) {
            $data['user'] = session('admin_id');
        } else {
            $data['user'] = auth()->user()->id;
        }

        $data['title'] = _i('Financial state report') . ' (' . _i('User') . ')';
        return view('back.agents.reports.financial-state-username', $data);
    }

    /**
     * View Financial State
     * @param ClosuresUsersTotalsRepo $closuresUsersTotalsRepo
     * @param ReportsCollection $reportsCollection
     * @return Application|Factory|\Illuminate\Contracts\View\View
     */
    public function financialState_view1(
        ClosuresUsersTotalsRepo $closuresUsersTotalsRepo,
        ReportsCollection $reportsCollection
    ) {
        //        $currency = session('currency');
        //        $whitelabel = Configurations::getWhitelabel();
        if (session('admin_id')) {
            $data['user'] = session('admin_id');
        } else {
            $data['user'] = auth()->user()->id;
        }
        $data['title'] = _i('Financial state report') . ' (-View1-)';
        return view('back.agents.reports.financial-state_view1', $data);
    }

    /**
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function find(Request $request)
    : Response {
        $this->validate($request, [
            'id'   => 'required|exists:users,id',
            'type' => 'required',
        ]);

        try {
            $authUserId          = session('admin_id') ?? auth()->id();
            $agent_player        = ! session('admin_id');
            $currency            = session('currency');
            $whitelabel          = Configurations::getWhitelabel();
            $bonus               = Configurations::getBonus();
            $lang                = LaravelGettext::getLocale();
            $userId              = $request->input('id');
            $balanceBonus        = 0.00;
            $campaignDescription = _i('Without description...');
            $type                = $request->input('type');
            $walletId            = null;
            if ($type == 'agent') {
                $user    = $this->agentsRepo->findByUserIdAndCurrency($userId, $currency);
                $father  = $this->usersRepo->findUsername($user->owner);
                $balance = $user->balance;
                $master  = $user->master;
                $agent   = true;
                $myself  = $authUserId == $user->id;
            } else {
                $user     = $this->agentsRepo->findUser($userId);
                $father   = (! is_null($user)) ? $this->usersRepo->findUsername($user->owner_id) : null;
                $master   = false;
                $wallet   = Wallet::getByClient($userId, $currency, $bonus);
                $balance  = $wallet->data->wallet->balance;
                $agent    = false;
                $walletId = $wallet->data->wallet->id;
                $myself   = false;
                if ($bonus) {
                    $balanceBonus = (property_exists($wallet->data, 'bonus')) ? $wallet->data->bonus[0]->balance : 0.00;
                    $campaign     = $this->campaignsRepo->findCampaignActive($whitelabel, $currency);
                    if ($campaign) {
                        $campaignDescription = $campaign->translations->$lang->description;
                    }
                }
            }
            $this->agentsCollection->formatAgent($user);
            $user->created = date('Y-m-d', strtotime($user->created));
            return Utils::successResponse([
                'cant_agents'         => 0,
                'cant_players'        => 0,
                'father'              => $father->username ?? '---',
                'fathers'             => [],
                'user'                => $user,
                'balance'             => number_format($balance, 2),
                'balance_bonus'       => number_format($balanceBonus, 2),
                'master'              => $master,
                'agent'               => $agent,
                'wallet'              => $walletId,
                'type'                => $type,
                'myself'              => $myself,
                'agent_player'        => $agent_player,
                'campaignDescription' => $campaignDescription
            ]);
        } catch (Exception $ex) {
            Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     * User find
     *
     * @param int $user User ID
     * @return Response
     */
    public function userFind($user)
    {
        try {
            $userData   = $this->usersRepo->find($user);
            $agentsRepo = new AgentsRepo();
            $agent      = ($userData->type_user == 'agent')
                ? $agentsRepo->findByUserIdAndCurrency($userData->id, session('currency'))
                : $agentsRepo->findUser($userData->id);
            $this->agentsCollection->formatUserFind($agent);
            $usersData = [
                'userData' => $agent,
            ];
            return Utils::successResponse($usersData);
        } catch (Exception $ex) {
            Log::error(__METHOD__, ['exception' => $ex, 'user' => $user]);
            return Utils::failedResponse();
        }
    }


    /**
     * Find tree agents father
     *
     * @param Request $request
     * @return Response
     */
    public function getFatherAndCant(Request $request)
    {
        try {
            $currency = session('currency');
            $id       = $request->id;
            $type     = $request->type;
            $cant     = null;

            if ($type == 'agent') {
                $cant    = $this->usersRepo->numberChildren($id, $currency);
                $fathers = $this->usersRepo->getParentsFromChild($id, $currency, Auth::user()->id, $type);
            } else {
                $cant    = $this->usersRepo->numberChildren($id, $currency);
                $fathers = $this->usersRepo->getParentsFromChild($id, $currency, Auth::user()->id, $type);
            }

            $data = [
                'cant_agents'  => 0,
                'cant_players' => 0,
                'fathers'      => [],
            ];

            if (! is_null($cant)) {
                $data['cant_agents']  = $cant['agents'];
                $data['cant_players'] = $cant['players'];
                $data['fathers']      = $fathers;
            }

            return Utils::successResponse($data);
        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     * Show agents payments transactions by dates
     *
     * @param null $startDate
     * @param null $endDate
     * @param null $user_id
     * @return Response
     */
    public function findUserPayment($startDate = null, $endDate = null, $user = null)
    {
        try {
            $startDateClosure = $startDate;
            $endDateClosure   = $endDate;

            $startDate = Carbon::now()->subDays(30);
            $endDate   = Carbon::now();
            if (session('admin_id')) {
                $userId      = session('admin_id');
                $agentPlayer = false;
            } else {
                $userId      = auth()->user()->id;
                $agentPlayer = true;
            }
            $user         = intval($user);
            $whitelabel   = Configurations::getWhitelabel();
            $providers    = [Providers::$agents, Providers::$agents_users];
            $percentage   = $this->agentsRepo->myPercentageByCurrency($user, session('currency'));
            $transactions = $this->transactionsRepo->getAgentsTransactions(
                $user,
                $providers,
                session('currency'),
                $startDate,
                $endDate
            );

            $closureRepo      = new ClosuresUsersTotals2023Repo();
            $typeUser         = $this->usersRepo->findTypeUser($user);
            $arrayProviderTmp = array_map(function ($val) {
                return $val->id;
            }, $closureRepo->getProvidersActiveByCredentials(true, session('currency'), $whitelabel));


            $providersString = '{' . implode(',', $arrayProviderTmp) . '}';
            //\Log::debug(['Params Closures:', $whitelabel, session('currency'), $startDateClosure, $endDateClosure, $user,$providersString]);
            if (in_array($typeUser->type_user, [TypeUser::$agentMater, TypeUser::$agentCajero])) {
                //$closures = $closureRepo->getClosureTotalsByWhitelabelAndProvidersWithSon($whitelabel, session('currency'),'2023-01-03', $endDateClosure, $user,$providersString);
                //TODO USER = OWNER
                $closures = $closureRepo->getTotalsClosurePaymentsByOwner(
                    $whitelabel,
                    session('currency'),
                    $startDate,
                    $endDateClosure,
                    $user,
                    $providersString
                );
            } else {
                $closures = $closureRepo->getTotalsClosurePaymentsByUser(
                    $whitelabel,
                    session('currency'),
                    $startDate,
                    $endDateClosure,
                    $user,
                    $providersString
                );
                //$closures = $closureRepo->getClosureTotalsByWhitelabelAndProvidersAndUser($whitelabel, session('currency'), '2023-01-03', $endDateClosure, $user,$providersString);
            }
            //\Log::debug('closures',[$closures] );
            $data = [
                'payments' => [
                    'username'   => 'qwerty',
                    'loads'      => $transactions,
                    'downloads'  => $transactions['debit'],
                    'total'      => '1231',
                    'comission'  => '1232',
                    'payment'    => '35645',
                    'receivable' => '33243'
                ]
            ];
            return Utils::successResponse($data);
        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    /**
     * Get Tree Users
     *
     */
    public function getTreeUsers()
    {
        try {
            return Utils::successResponse([
                'tree'   => $this->agentsCollection->childrenTreeSql(Auth::id()),
                'makers' => $this->gamesRepo->getMakers()
            ]);
        } catch (Exception $ex) {
            Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    public function updateAgentQuantitiesFromTree()
    : JsonResponse
    {
        return response()->json($this->agentService->updateAgentQuantitiesFromTree());
    }


    /**
     * @param Request $request
     * @return array
     */
    public function getDirectChildren(Request $request)
    : array {
        return $this->agentsRepo->getDirectChildren($request, session('currency'), Configurations::getWhitelabel());
    }

    /**
     * Show dashboard
     *
     * @param CountriesRepo $countriesRepo
     * @param ProvidersRepo $providersRepo
     * @param ClosuresUsersTotalsRepo $closuresUsersTotalsRepo
     * @param ReportsCollection $reportsCollection
     * @return Application|Factory|View
     */
    public function index()
    {
        try {
            //ProvidersRepo $providersRepo
            //            if (session('admin_id')) {
            //                $user = session('admin_id');
            //            } else {
            //                $user = auth()->user()->id ? Auth::id() : null;
            //                if (is_null(Auth::user()->username) == 'romeo') {
            //                    $userTmp = $this->usersRepo->findUserCurrencyByWhitelabel('wolf', session('currency'), Configurations::getWhitelabel());
            //                    $user = isset($userTmp[0]->id) ? $userTmp[0]->id : null;
            //                }
            //
            //            }
            //            $whitelabel = Configurations::getWhitelabel();
            //            $currency = session('currency');
            //            $agent = $this->agentsRepo->findByUserIdAndCurrency($user, $currency);
            //            //TODO MOSTRAR EL AGENTE LOGUEADO
            //            $agent->user_id = $agent->id;
            //
            //            //$agentAndSubAgents = $this->agentsCollection->formatAgentandSubAgents([$agent]);
            //            $agentAndSubAgents = $this->agentsCollection->formatAgentandSubAgentsNew($this->agentsRepo, $currency, [$agent]);
            //
            //            $providerTypes = [ProviderTypes::$casino, ProviderTypes::$live_casino, ProviderTypes::$casino, ProviderTypes::$virtual, ProviderTypes::$sportbook, ProviderTypes::$racebook, ProviderTypes::$live_games, ProviderTypes::$poker];
            //            $providers = $providersRepo->getByWhitelabelAndTypes($whitelabel, $currency, $providerTypes);
            //            $data['currencies'] = Configurations::getCurrencies();
            //            $data['countries'] = []; //$countriesRepo->all();
            //            $data['timezones'] = []; //\DateTimeZone::listIdentifiers();
            //            $data['providers'] = $providers;
            //            $data['agent'] = $agent;
            //            $data['makers'] = $this->gamesRepo->getMakers();
            //            $data['agents'] = $agentAndSubAgents;
            //            $data['tree'] = $this->agentsCollection->childrenTree($agent, $user);
            //            //$data['tree'] = json_encode($this->agentsCollection->childrenTreeSql($user));
            //            $data['action'] = Auth::user()->action;
            //            $data['iagent'] = $this->agentsRepo->findAgent($user,$whitelabel);

            //EN CASO DE ROMEO ENTRA COMO WOLF
            $user = Auth::user()->id;
            //            if (Auth::user()->username == 'romeo' || Auth::user()->username == 'develop') {
            //                $userTmp = $this->usersRepo->findUserCurrencyByWhitelabel('wolf', session('currency'), Configurations::getWhitelabel());
            //                $user = isset($userTmp[0]->id) ? $userTmp[0]->id : null;
            //            }

            $whitelabel = Configurations::getWhitelabel();
            $agentUser  = $this->agentsRepo->findAgent($user, $whitelabel);
            $userData   = $this->usersRepo->getUsers($user);
            foreach ($userData as $users) {
                $confirmation = $users->confirmation_email;
            }

            $data['agent']              = $this->agentsRepo->findUserProfile($user, session('currency') ?? '');
            $data['makers']             = [];
            $data['agents']             = json_decode(
                json_encode(
                    $this->agentsRepo->getAgentsAllByOwner($user, session('currency'), Configurations::getWhitelabel())
                ),
                true
            );
            $data['action']             = auth()->user()->action;
            $data['iagent']             = $agentUser;
            $data['confirmation_email'] = $confirmation;
            $data['title']              = _i('Agents module');

            return view('back.agents.index', $data);
        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    /**
     * Show locked providers report
     *
     * @return Application|Factory|View
     */
    public function lockedProviders()
    {
        $data['title'] = _i('Locked providers');
        return view('back.agents.reports.locked-providers', $data);
    }

    /**
     * Get locked providers data
     *
     * @param Request $request
     * @return Response
     */
    public function lockedProvidersData(Request $request)
    {
        try {
            $currency = $request->currency;
            if (! is_null($currency)) {
                $whitelabel = Configurations::getWhitelabel();
                $provider   = $request->provider;
                $agents     = $this->agentsRepo->getAgentLockByProvider($currency, $provider, $whitelabel);
            } else {
                $agents = [];
            }
            $this->agentsCollection->formatAgentLockByProvider($agents);
            $data = [
                'agents' => $agents
            ];
            return Utils::successResponse($data);
        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     * Show main agents
     *
     *
     * @return Application|Factory|View
     */
    public function mainAgents()
    {
        $data['title']       = _i('Create main agents');
        $data['whitelabels'] = $this->whitelabelsRepo->all();
        return view('back.agents.main-agents', $data);
    }

    /**
     * Show manual transaction
     *
     *
     * @return Application|Factory|View
     */
    public function manualTransactions()
    {
        $data['title'] = _i('Manual transaction');
        return view('back.agents.reports.manual-transactions', $data);
    }

    /**
     * Get manual transactions
     *
     * @param string $startDate Start date to filter
     * @param string $endDate End date to filter
     * @param string $currency Currency ISO
     * @return Response
     */
    public function manualTransactionsData($startDate = null, $endDate = null, $currency = null)
    {
        try {
            if (! is_null($startDate) || ! is_null($endDate) && ! is_null($currency)) {
                if (session('admin_id')) {
                    $user = session('admin_id');
                } else {
                    $user = auth()->user()->id;
                }
                $agents = $this->agentsRepo->getAgentsByOwner($user, $currency);
                $users  = $this->agentsCollection->formatAgentsId($agents, $currency);
                if (! in_array($user, $users)) {
                    $users[] = $user;
                }
                $startDate    = Utils::startOfDayUtc($startDate);
                $endDate      = Utils::endOfDayUtc($endDate);
                $providers    = [Providers::$agents, Providers::$agents_users];
                $whitelabel   = Configurations::getWhitelabel();
                $transactions = $this->transactionsRepo->getManualTransactionsFromAgents(
                    $users,
                    $startDate,
                    $endDate,
                    $providers,
                    $currency,
                    $whitelabel
                );
                $this->agentsCollection->formatAgentTransactionsReport($transactions);
            } else {
                $transactions = [];
            }

            $data = [
                'transactions' => $transactions
            ];
            return Utils::successResponse($data);
        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'currency' => $currency]);
            return Utils::failedResponse();
        }
    }

    /**
     * Move agent
     *
     * @param Request $request
     * @return Response
     */
    public function moveAgent(Request $request)
    {
        $this->validate($request, [
            'agent' => 'required',
        ]);

        try {
            $userAgent = $request->user;
            $agentId   = $request->agent;
            $agent     = $this->agentsRepo->existAgent($userAgent);
            $userData  = $this->agentsRepo->findByUserIdAndCurrency($userAgent, session('currency'));
            if ($userData->action == ActionUser::$locked_higher) {
                $data = [
                    'title'   => ActionUser::getName($userData->action),
                    'message' => _i('Contact your superior...'),
                    'close'   => _i('Close')
                ];
                return Utils::errorResponse(Codes::$not_found, $data);
            }
            if (is_null($agent)) {
                $data = [
                    'title'   => _i('Agent moved'),
                    'message' => _i('Agent not displaced'),
                    'close'   => _i('Close')
                ];
                return Utils::errorResponse(Codes::$forbidden, $data);
            }
            $agentData = [
                'owner_id' => $agentId,
                'user_id'  => $userAgent,
            ];

            //TODO CONSULTAR USUARIO PADRE
            $agentStatus = $this->agentsRepo->getUserBlocked($agentId);
            if (isset($agentStatus->id) && $agentStatus->status == false) {
                //TODO BLOQUEAR SI EL PADRE ESTA BLOQUEADO
                $this->agentsRepo->blockUsers($userAgent);
            }

            $this->agentsRepo->update($agent->id, $agentData);
            $data = [
                'title'   => _i('Agent moved'),
                'message' => _i('Agent moved successfully'),
                'close'   => _i('Close')
            ];
            return Utils::successResponse($data);
        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     * Move agent user
     *
     * @param Request $request
     * @return Response
     */
    public function moveAgentUser(Request $request)
    {
        $this->validate($request, [
            'agent' => 'required',
        ]);
        try {
            $userAgent = $request->user;
            $agentId   = $request->agent;

            $agent    = $this->agentsRepo->existAgent($agentId);
            $userData = $this->agentsRepo->statusActionByUser($userAgent);
            if (isset($userData->action) && $userData->action == ActionUser::$locked_higher || isset($userData->status) && $userData->status == false) {
                $data = [
                    'title'   => ActionUser::getName($userData->action),
                    'message' => _i('Contact your superior...'),
                    'close'   => _i('Close')
                ];
                return Utils::errorResponse(Codes::$not_found, $data);
            }
            //TODO CONSULTAR USUARIO PADRE
            $agentStatus = $this->agentsRepo->getUserBlocked($agentId);
            if (isset($agentStatus->id) && $agentStatus->status == false) {
                //TODO BLOQUEAR SI EL PADRE ESTA BLOQUEADO
                $this->agentsRepo->blockUsers($userAgent);
            }

            $this->agentsRepo->moveAgentFromUser($agent, $userAgent);
            $data = [
                'title'   => _i('User moved'),
                'message' => _i('User moved successfully'),
                'close'   => _i('Close')
            ];
            return Utils::successResponse($data);
        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     * @param TransactionRequest $request
     * @return Response
     */
    public function performTransactions(TransactionRequest $request)
    : Response {
        try {
            return $this->userTransactionService->manageCreditDebitTransactions($request);
        } catch (Exception $ex) {
            return $this->userTransactionService->handleAndRespondToError($request, $ex);
        }
    }

    public function performTransactionsOld(Request $request)
    {
        $this->validate($request, [
            'amount'           => 'required|numeric|gt:0',
            'user'             => 'required|exists:users,id',
            'type'             => 'required',
            'transaction_type' => 'required',
        ]);
        try {
            $id                   = auth()->user()->id;
            $currency             = session('currency');
            $type                 = $request->get('type');
            $user                 = $request->get('user');
            $amount               = $request->get('amount');
            $transactionType      = $request->get('transaction_type');
            $ownerAgent           = $this->agentsRepo->findByUserIdAndCurrency($id, $currency);
            $ownerBalanceFinal    = $ownerAgent->balance;
            $transactionIdCreated = null;

            /* If the logged in user is different from the user that the balance is added to*/
            if ($id != $user) {
                /*Insufficient balance */
                if ($transactionType == TransactionTypes::$credit && $amount > $ownerAgent->balance && $ownerAgent->username != 'wolf') {
                    $data = [
                        'title'   => _i('Insufficient balance'),
                        'message' => _i("The agents's operational balance is insufficient to perform the transaction"),
                        'close'   => _i('Close')
                    ];
                    return Utils::errorResponse(Codes::$forbidden, $data);
                }

                /* User Type: User */
                if ($type == 'user') {
                    $wallet = $request->wallet;

                    $userData = $this->agentsRepo->findUser($user);
                    if ($userData->action == ActionUser::$locked_higher) {
                        $data = [
                            'title'   => _i('Blocked by a superior!'),
                            'message' => _i('Contact your superior...'),
                            'close'   => _i('Close')
                        ];
                        return Utils::errorResponse(Codes::$not_found, $data);
                    }

                    $walletData = Wallet::getByClient($userData->id, $currency);
                    if (empty($walletData)) {
                        Log::error('error data, wallet getByClient', [
                            'userData' => $userData,
                            'currency' => $currency,
                            $request->all(),
                            Auth::user()->id
                        ]);
                        $data = [
                            'title'   => _i('An error occurred'),
                            'message' => _i("please contact support"),
                            'close'   => _i('Close')
                        ];
                        return Utils::errorResponse(Codes::$forbidden, $data);
                    }
                    if ($transactionType == TransactionTypes::$credit) {
                        $uuid           = Str::uuid()->toString();
                        $additionalData = [
                            'provider_transaction' => $uuid,
                            'from'                 => $ownerAgent->username,
                            'to'                   => $userData->username
                        ];

                        if (Configurations::getWhitelabel() == 1) {
                            function microtime_float()
                            {
                                [$usec, $sec] = explode(" ", microtime());
                                return ((float)$usec + (float)$sec);
                            }

                            $time_start = microtime_float();

                            $transaction = Wallet::creditManualTransactions(
                                $amount,
                                Providers::$agents_users,
                                $additionalData,
                                $wallet
                            );

                            $time_end = microtime_float();

                            $time = $time_end - $time_start;

                            Log::debug('Tiempo que tarda', [
                                'segundos' => $time
                            ]);
                        } else {
                            function microtime_float()
                            {
                                [$usec, $sec] = explode(" ", microtime());
                                return ((float)$usec + (float)$sec);
                            }

                            $time_start = microtime_float();

                            $transaction = Wallet::creditManualTransactions(
                                $amount,
                                Providers::$agents_users,
                                $additionalData,
                                $wallet
                            );
                        }

                        if (empty($transaction) || empty($transaction->data)) {
                            $time_end = microtime_float();

                            $time = $time_end - $time_start;

                            Log::debug('Tiempo que tarda', [
                                'segundos' => $time
                            ]);
                            Log::debug('error data, wallet credit', [
                                'transaction'    => $transaction,
                                'additionalData' => $additionalData,
                                'request'        => $request->all(),
                                'Auth'           => Auth::user()->id
                            ]);

                            $data = [
                                'title'   => _i('An error occurred'),
                                'message' => _i("please contact support"),
                                'close'   => _i('Close')
                            ];
                            return Utils::errorResponse(Codes::$forbidden, $data);
                        }
                        //new TransactionNotAllowed($amount, $user, Providers::$agents_users, $transactionType);
                        $ownerBalance      = $ownerAgent->balance - $amount;
                        $agentBalanceFinal = $walletData->data->wallet->balance;
                    } else {
                        $agentBalanceFinal = $walletData->data->wallet->balance;
                        if ($amount > $walletData->data->wallet->balance) {
                            $data = [
                                'title'   => _i('Insufficient balance'),
                                'message' => _i("The user's balance is insufficient to perform the transaction"),
                                'close'   => _i('Close')
                            ];
                            return Utils::errorResponse(Codes::$forbidden, $data);
                        }

                        $uuid           = Str::uuid()->toString();
                        $additionalData = [
                            'provider_transaction' => $uuid,
                            'from'                 => $ownerAgent->username,
                            'to'                   => $userData->username
                        ];
                        $transaction    = Wallet::debitManualTransactions(
                            $amount,
                            Providers::$agents_users,
                            $additionalData,
                            $wallet
                        );
                        if (empty($transaction) || empty($transaction->data)) {
                            Log::debug('error data, wallet debit', [
                                'transaction'    => $transaction,
                                'additionalData' => $additionalData,
                                'request'        => $request->all(),
                                'Auth'           => Auth::user()->id
                            ]);

                            $data = [
                                'title'   => _i('An error occurred'),
                                'message' => _i("please contact support"),
                                'close'   => _i('Close')
                            ];
                            return Utils::errorResponse(Codes::$forbidden, $data);
                        }
                        //new TransactionNotAllowed($amount, $user, Providers::$agents_users, $transactionType);
                        $ownerBalance = $ownerAgent->balance + $amount;
                    }

                    $balance                                  = $transaction->data->wallet->balance;
                    $status                                   = $transaction->status;
                    $userAdditionalData                       = $additionalData;
                    $userAdditionalData['wallet_transaction'] = $transaction->data->transaction->id;

                    $transactionData = [
                        //'id' => $transactionID,
                        'user_id'               => $user,
                        'amount'                => $amount,
                        'currency_iso'          => $currency,
                        'transaction_type_id'   => $transactionType,
                        'transaction_status_id' => TransactionStatus::$approved,
                        'provider_id'           => Providers::$agents_users,
                        'data'                  => $userAdditionalData,
                        'whitelabel_id'         => Configurations::getWhitelabel()
                    ];
                    $ticket          = $this->transactionsRepo->store($transactionData, TransactionStatus::$approved, []
                    );
                    if (empty($ticket)) {
                        Log::error('error data, TransactionsRepo Store ', [
                            '$transactionData' => $transactionData,
                            'approved'         => TransactionStatus::$approved,
                            $request->all(),
                            Auth::user()->id
                        ]);
                        $data = [
                            'title'   => _i('An error occurred'),
                            'message' => _i("please contact support"),
                            'close'   => _i('Close')
                        ];
                        return Utils::errorResponse(Codes::$forbidden, $data);
                    }
                    $transactionIdCreated = $ticket->id;
                    $button               = sprintf(
                        '<a class="btn u-btn-3d u-btn-blue btn-block" id="ticket" href="%s" target="_blank">%s</a>',
                        route('agents.ticket', [$ticket->id]),
                        _i('Print ticket')
                    );
                } /* User Type: Agent */ else {
                    /*We consulted the agent to recharge balance*/
                    $agent = $this->agentsRepo->findByUserIdAndCurrency($user, $currency);
                    if ($agent->action == ActionUser::$locked_higher) {
                        $data = [
                            'title'   => _i('Blocked by a superior!'),
                            'message' => _i('Contact your superior...'),
                            'close'   => _i('Close')
                        ];
                        return Utils::errorResponse(Codes::$not_found, $data);
                    }

                    /* Agent Balance */
                    $agentBalance = round($agent->balance, 2);

                    // $agentBalanceFinal = $agent->balance;

                    /*If the transaction is credit type */
                    if ($transactionType == TransactionTypes::$credit) {
                        /*Valid status */
                        $status = Status::$ok;
                        /*$balance: is the sum of the agent's current balance and the amount to be credited */
                        $balance = $agentBalance + $amount;
                        /*$agentBalanceFinal: is the sum of the agent's current balance and the amount to be credited */
                        $agentBalanceFinal = $agent->balance + $amount;
                        /*$agentData: agent id and selected currency are saved */
                        $agentData = [
                            'agent_id'     => $agent->agent,
                            'currency_iso' => $currency
                        ];
                        /*$balanceData: balance is saved */
                        $balanceData = [
                            'balance' => $balance
                        ];
                        /*The balance field of that agent is added or modified in the agent_currencies table */
                        if ($agent->username != 'wolf') {
                            $this->agentCurrenciesRepo->store($agentData, $balanceData);
                        }
                        /* $ownerBalance: Balance from which the transaction was generated minus the amount to be credited */
                        $ownerBalance = $ownerAgent->balance - $amount;
                        /*$additionalData: This is what is stored in the data field of the transactions table  */
                        $additionalData = [
                            'from'    => $ownerAgent->username,
                            'to'      => $agent->username,
                            'balance' => $balance
                        ];
                    } /*If the transaction is debit type */ else {
                        if ($amount <= $agentBalance) {
                            /*Valid status */
                            $status = Status::$ok;
                            /*$balance: is the sum of the agent's current balance and the amount to be debited */
                            $balance = $agentBalance - $amount;
                            /*$agentBalanceFinal: is the sum of the agent's current balance and the amount to be debited */
                            $agentBalanceFinal = $agent->balance;

                            /*$agentData: agent id and selected currency are saved */
                            $agentData = [
                                'agent_id'     => $agent->agent,
                                'currency_iso' => $currency
                            ];

                            /*$balanceData: balance is saved */
                            $balanceData = [
                                'balance' => $balance
                            ];
                            /*The balance field of that agent is added or modified in the agent_currencies table */
                            if ($agent->username != 'wolf') {
                                $this->agentCurrenciesRepo->store($agentData, $balanceData);
                            }
                            /* $ownerBalance: Balance from which the transaction was generated minus the amount to be credited */
                            $ownerBalance = $ownerAgent->balance + $amount;
                            /*$additionalData: This is what is stored in the data field of the transactions table  */
                            $additionalData = [
                                'from'    => $ownerAgent->username,
                                'to'      => $agent->username,
                                'balance' => $balance
                            ];
                        } else {
                            $balance = $agentBalance;
                            $status  = Status::$failed;
                        }
                    }
                    /*If valid status equals true*/
                    if ($status == Status::$ok) {
                        /*$transactionData: This is the data of the first transaction that is generated. */
                        $transactionData = [
                            'user_id'               => $agent->id,
                            'amount'                => $amount,
                            'currency_iso'          => $currency,
                            'transaction_type_id'   => $transactionType,
                            'transaction_status_id' => TransactionStatus::$approved,
                            'provider_id'           => Providers::$agents,
                            'data'                  => $additionalData,
                            'whitelabel_id'         => Configurations::getWhitelabel()
                        ];
                        /* $ticket: here the first transaction in the table is generated.*/
                        $ticket = $this->transactionsRepo->store($transactionData, TransactionStatus::$approved, []);
                        if (empty($ticket)) {
                            Log::error('error data, TransactionsRepo Store', [
                                'transactionData' => $transactionData,
                                'approved'        => TransactionStatus::$approved,
                                $request->all(),
                                Auth::user()->id
                            ]);
                            $data = [
                                'title'   => _i('An error occurred'),
                                'message' => _i("please contact support"),
                                'close'   => _i('Close')
                            ];
                            return Utils::errorResponse(Codes::$forbidden, $data);
                        }
                        $transactionIdCreated = $ticket->id;

                        //  new TransactionNotAllowed($amount, $agent->id, Providers::$agents, $transactionType);
                        $button = sprintf(
                            '<a class="btn u-btn-3d u-btn-blue btn-block" id="ticket" href="%s" target="_blank">%s</a>',
                            route('agents.ticket', [$ticket->id]),
                            _i('Print ticket')
                        );
                    }
                }
                /*If valid status equals true*/
                if ($status == Status::$ok) {
                    /*$agentData: agent id and selected currency are saved */
                    $agentData = [
                        'agent_id'     => $ownerAgent->agent,
                        'currency_iso' => $currency
                    ];
                    /*$balanceData: balance is saved */
                    $balanceData = [
                        'balance' => $ownerBalance
                    ];
                    /*The balance field of that agent is added or modified in the agent_currencies table */
                    if ($ownerAgent->username != 'wolf') {
                        $this->agentCurrenciesRepo->store($agentData, $balanceData);
                    }
                    /* if $type equals user */
                    if ($type == 'user') {
                        /*I assign the balance */
                        $additionalData['balance'] = $ownerBalance;
                    }
                    /* If the logged in user is different from wolf */
                    if ($ownerAgent->username != 'wolf') {
                        /*I assign the balance */
                        $additionalData['balance'] = $ownerBalance;
                    } else {
                        /*I assign the balance */
                        $additionalData['balance'] = 0;
                    }
                    /*it is assigned the id of the transaction created first */
                    $additionalData['transaction_id'] = $transactionIdCreated;

                    $additionalData['second_balance'] = $transactionType == TransactionTypes::$credit ? round(
                        $agentBalanceFinal,
                        2
                    ) : round($agentBalanceFinal, 2) - $amount;

                    $transactionData = [
                        //'id' => $transactionID,
                        'user_id'               => $id,
                        'amount'                => $amount,
                        'currency_iso'          => $currency,
                        'transaction_type_id'   => $transactionType == TransactionTypes::$credit ? TransactionTypes::$debit : TransactionTypes::$credit,
                        'transaction_status_id' => TransactionStatus::$approved,
                        'provider_id'           => Providers::$agents,
                        'data'                  => $additionalData,
                        'whitelabel_id'         => Configurations::getWhitelabel()
                    ];

                    $transactionFinal = $this->transactionsRepo->store(
                        $transactionData,
                        TransactionStatus::$approved,
                        []
                    );
                    if (empty($transactionFinal)) {
                        Log::error('error data, TransactionsRepo Store', [
                            'transactionData' => $transactionData,
                            'approved'        => TransactionStatus::$approved,
                            $request->all(),
                            Auth::user()->id
                        ]);
                        $data = [
                            'title'   => _i('An error occurred'),
                            'message' => _i("please contact support"),
                            'close'   => _i('Close')
                        ];
                        return Utils::errorResponse(Codes::$forbidden, $data);
                    }
                    //new TransactionNotAllowed($amount, $id, Providers::$agents, $transactionType);
                    $transactionUpdate = $this->transactionsRepo->updateData(
                        $transactionIdCreated,
                        $transactionFinal->id,
                        $transactionType == TransactionTypes::$credit
                            ? round($ownerBalanceFinal, 2) - $amount
                            : round(
                                  $ownerBalanceFinal,
                                  2
                              ) + $amount
                    );
                    if (empty($transactionUpdate)) {
                        Log::error('error data, TransactionsRepo Store', [
                            'transactionIdCreated' => $transactionIdCreated,
                            'transactionFinal'     => $transactionFinal,
                            'approved'             => TransactionStatus::$approved,
                            'transactionType'      => $transactionType,
                            $request->all(),
                            Auth::user()->id
                        ]);
                        $data = [
                            'title'   => _i('An error occurred'),
                            'message' => _i("please contact support"),
                            'close'   => _i('Close')
                        ];
                        return Utils::errorResponse(Codes::$forbidden, $data);
                    }
                    $data = [
                        'title'   => _i('Transaction performed'),
                        'message' => _i('The transaction was successfully made to the user'),
                        'close'   => _i('Close'),
                        'balance' => number_format($balance, 2),
                        'button'  => $button
                    ];
                    return Utils::successResponse($data);
                } else {
                    $data = [
                        'title'   => _i('Insufficient balance'),
                        'message' => _i("The user's balance is insufficient to perform the transaction"),
                        'close'   => _i('Close')
                    ];
                    return Utils::errorResponse(Codes::$forbidden, $data);
                }
            } else {
                $data = [
                    'title'   => _i('Error'),
                    'message' => _i('You cannot make transactions to yourself'),
                    'close'   => _i('Close')
                ];
                return Utils::errorResponse(Codes::$forbidden, $data);
            }
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     * Validate email
     *
     * @param string $email Email to validate
     * @return bool
     */
    private function validateEmail($email)
    {
        $data     = [
            'address' => strtolower($email)
        ];
        $curl     = Curl::to(env('MAILGUN_VALIDATION_URL'))
            ->withOption('HTTPAUTH', CURLAUTH_BASIC)
            ->withOption('USERPWD', 'api:' . env('MAILGUN_SECRET'))
            ->withData($data)
            ->post();
        $response = json_decode($curl);
        $result   = true;
        if (! isset($response->result) || $response->result != 'deliverable') {
            Log::debug('validateEmail', [$response]);
            $result = false;
        }
        return $result;
    }

    /**
     * Store agents
     *
     * @param Request $request
     * @param UsersTempRepo $usersTempRepo
     * @param UserCurrenciesRepo $userCurrenciesRepo
     * @return Response
     * @throws ValidationException
     * @deprecated
     */
    public function store(Request $request, UsersTempRepo $usersTempRepo, UserCurrenciesRepo $userCurrenciesRepo)
    {
        $this->validate($request, [
            'username'   => ['required', new Username()],
            'password'   => ['required', new Password()],
            //'email' => ['required', new Email()],
            'balance'    => 'required',
            'percentage' => 'required|numeric|between:1,99',
            'timezone'   => 'required'
        ]);

        try {
            //            $email = strtolower($request->get('email'));
            //            $uniqueEmail = $this->usersRepo->uniqueEmail($email);
            //            if (!is_null($uniqueEmail)) {
            //                $data = [
            //                    'title' => _i('Email in use'),
            //                    'message' => _i('The indicated email is already in use'),
            //                    'close' => _i('Close'),
            //                ];
            //                return Utils::errorResponse(Codes::$forbidden, $data);
            //
            //            }
            //            if (!$this->validateEmail($email)) {
            //                $data = [
            //                    'title' => _i('Invalid email'),
            //                    'message' => _i('The email entered is invalid or does not exist'),
            //                    'close' => _i('Close'),
            //                ];
            //                return Utils::errorResponse(Codes::$forbidden, $data);
            //            }


            $whitelabel = Configurations::getWhitelabel();
            $uuid       = Str::uuid()->toString();
            $owner      = auth()->user()->id;
            $currency   = session('currency');
            $username   = strtolower($request->username);
            $password   = $request->password;
            $timezone   = $request->timezone;
            $balance    = $request->balance;
            $master     = $request->master;
            $percentage = $request->percentage;
            $currencies = ! empty($request->currencies) ? $request->currencies : [$currency];
            //TODO NEW WAY TO SAVE EMAIL
            $domain = Configurations::getDomain();
            $email  = "$username@$domain";

            $uniqueUsername     = $this->usersRepo->uniqueUsername($username);
            $uniqueTempUsername = $usersTempRepo->uniqueUsername($username);
            $userExclude        = $this->agentsRepo->getExcludeUserMaker($owner);

            if (! is_null($uniqueUsername) || ! is_null($uniqueTempUsername)) {
                $data = [
                    'title'   => _i('Username in use'),
                    'message' => _i('The indicated username is already in use'),
                    'close'   => _i('Close'),
                ];
                return Utils::errorResponse(Codes::$forbidden, $data);
            }

            $ownerAgent = $this->agentsRepo->findByUserIdAndCurrency($owner, $currency);

            if ($balance > $ownerAgent->balance) {
                $data = [
                    'title'   => _i('Insufficient balance'),
                    'message' => _i("The agents's operational balance is insufficient to perform the transaction"),
                    'close'   => _i('Close')
                ];
                return Utils::errorResponse(Codes::$forbidden, $data);
            }

            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ipForwarded = $_SERVER['HTTP_X_FORWARDED_FOR'];
                $ip          = explode(',', $ipForwarded)[0];
            } else {
                $ip = $request->getClientIp();
            }

            $store       = Configurations::getStore()->active;
            $userData    = [
                'username'          => $username,
                'email'             => $email,
                'password'          => $password,
                'uuid'              => $uuid,
                'ip'                => $ip,
                'status'            => true,
                'whitelabel_id'     => $whitelabel,
                'web_register'      => false,
                'register_currency' => $currency,
                'type_user'         => $master == 'true' ? TypeUser::$agentMater : TypeUser::$agentCajero,
                'action'            => Configurations::getResetMainPassword(
                ) ? ActionUser::$changed_password : ActionUser::$update_email,
            ];
            $profileData = [
                'country_iso' => $ownerAgent->country_iso,
                'timezone'    => $timezone,
                'level'       => 1
            ];
            $user        = $this->usersRepo->store($userData, $profileData);
            $this->generateReferenceCode->generateReferenceCode($user->id);

            foreach ($currencies as $key => $agentCurrency) {
                $excludedUser = $this->agentsCollection->formatExcluderProvidersUsers(
                    $user->id,
                    $userExclude,
                    $agentCurrency
                );
                $this->agentsRepo->blockAgentsMakers($excludedUser);
                $wallet     = Wallet::store(
                    $user->id,
                    $user->username,
                    $uuid,
                    $agentCurrency,
                    $whitelabel,
                    session('wallet_access_token')
                );
                $userData   = [
                    'user_id'      => $user->id,
                    'currency_iso' => $agentCurrency
                ];
                $walletData = [
                    'wallet_id' => $wallet->data->wallet->id,
                    'default'   => $key == 0
                ];
                $userCurrenciesRepo->store($userData, $walletData);
                if ($store) {
                    Store::storeWallet($user->id, $currency);
                }
            }

            $agentData = [
                'user_id'    => $user->id,
                'owner_id'   => $owner,
                'master'     => $master,
                'percentage' => $percentage,
            ];
            $agent     = $this->agentsRepo->store($agentData);

            foreach ($currencies as $key => $agentCurrency) {
                if ($currency == $agentCurrency) {
                    $this->createAgentBalance($agent->id, $currency, $balance);
                } else {
                    $this->createAgentBalance($agent->id, $agentCurrency);
                }
            }

            if ($balance > 0 && in_array($currency, $currencies)) {
                $ownerBalance = $ownerAgent->balance - $balance;
                $agentData    = [
                    'agent_id'     => $ownerAgent->agent,
                    'currency_iso' => $currency
                ];
                $balanceData  = [
                    'balance' => $ownerBalance
                ];
                $this->agentCurrenciesRepo->store($agentData, $balanceData);

                $additionalData   = [
                    'from'    => $ownerAgent->username,
                    'to'      => $user->username,
                    'balance' => $ownerBalance
                ];
                $transactionData1 = [
                    'user_id'               => $ownerAgent->id,
                    'amount'                => $balance,
                    'currency_iso'          => $currency,
                    'transaction_type_id'   => TransactionTypes::$debit,
                    'transaction_status_id' => TransactionStatus::$approved,
                    'provider_id'           => Providers::$agents,
                    'data'                  => $additionalData,
                    'whitelabel_id'         => Configurations::getWhitelabel()
                ];
                $transactionTmp1  = $this->transactionsRepo->store($transactionData1, TransactionStatus::$approved, []);

                $additionalData['balance']        = $balance;
                $additionalData['transaction_id'] = $transactionTmp1->id;
                $transactionData2                 = [
                    'user_id'               => $user->id,
                    'amount'                => $balance,
                    'currency_iso'          => $currency,
                    'transaction_type_id'   => TransactionTypes::$credit,
                    'transaction_status_id' => TransactionStatus::$approved,
                    'provider_id'           => Providers::$agents,
                    'data'                  => $additionalData,
                    'whitelabel_id'         => Configurations::getWhitelabel()
                ];
                $transactionTmp2                  = $this->transactionsRepo->store(
                    $transactionData2,
                    TransactionStatus::$approved,
                    []
                );

                //TODO AUDIT 2 CREDIT
                $auditDataTransaction2 = [
                    'ip'               => Utils::userIp(),
                    'user_id'          => auth()->user()->id,
                    'username'         => auth()->user()->username,
                    'transaction_data' => $transactionData2
                ];
                Audits::store(
                    $user->id,
                    AuditTypes::$transaction_credit,
                    Configurations::getWhitelabel(),
                    $auditDataTransaction2
                );

                //TODO UPDATE TRANSACTION 1 DATA = transaction_id
                $transactionData1_updated = $this->transactionsRepo->updateData(
                    $transactionTmp1->id,
                    $transactionTmp2->id
                );

                //TODO AUDIT 1 DEBIT
                $auditDataTransaction1 = [
                    'ip'               => Utils::userIp(),
                    'user_id'          => auth()->user()->id,
                    'username'         => auth()->user()->username,
                    'transaction_data' => $transactionData1_updated
                ];
                Audits::store(
                    $user->id,
                    AuditTypes::$transaction_debit,
                    Configurations::getWhitelabel(),
                    $auditDataTransaction1
                );
            } else {
                $ownerBalance = $ownerAgent->balance;
            }

            //Security::assignRole($user->id, 3); //TODO ROL AGENTS DEFAULT
            Security::assignRole($user->id, Roles::$admin_assiria); //TODO NUEVO ROL OF AGENT

            $data = [
                'title'   => _i('Agent created'),
                'message' => _i('Agent created successfully'),
                'close'   => _i('Close'),
                'balance' => number_format($ownerBalance, 2),
                'route'   => route('agents.index'),
            ];
            return Utils::successResponse($data);
        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }


    /**
     * @param Request $request
     * @param UsersTempRepo $usersTempRepo
     * @param UserCurrenciesRepo $userCurrenciesRepo
     * @return Response
     * @throws ValidationException
     */
    public function storeRol(Request $request, UsersTempRepo $usersTempRepo, UserCurrenciesRepo $userCurrenciesRepo)
    {
        $this->validate($request, [
            'username'   => ['required', new Username()],
            'password'   => ['required', new Password()],
            'percentage' => 'required|numeric|between:1,99',
            'master'     => 'required'
        ]);
        try {
            $username           = $request->input('username');
            $password           = $request->input('password');
            $uniqueUsername     = $this->usersRepo->uniqueUsername($username);
            $uniqueTempUsername = $usersTempRepo->uniqueUsername($username);

            if (! is_null($uniqueUsername) || ! is_null($uniqueTempUsername)) {
                return Utils::errorResponse(Codes::$forbidden, [
                    'title'   => _i('Username in use'),
                    'message' => _i('The indicated username is already in use'),
                    'close'   => _i('Close'),
                ]);
            }

            // $ip         = '172.70.255.200';

            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ipForwarded = $_SERVER['HTTP_X_FORWARDED_FOR'];
                $ip          = explode(',', $ipForwarded)[0];
            } else {
                $ip = $request->getClientIp();
            }

            $domain     = Configurations::getDomain();
            $whitelabel = Configurations::getWhitelabel();
            $currency   = session('currency');
            $username   = strtolower($request->username);
            $owner      = ! is_null($request->dependence) ? $request->dependence : auth()->user()->id;

            $ownerAgent = $this->agentsRepo->findByUserIdAndCurrency($owner, $currency);
            $master     = $request->input('master');
            $uuid       = Str::uuid()->toString();

            $userData    = [
                'username'          => $username,
                'email'             => "$username@$domain",
                'password'          => $password,
                'uuid'              => $uuid,
                'ip'                => $ip,
                'status'            => true,
                'whitelabel_id'     => $whitelabel,
                'web_register'      => false,
                'register_currency' => $currency,
                'type_user'         => $master == 'true' ? TypeUser::$agentMater : TypeUser::$agentCajero,
                'action'            => Configurations::getResetMainPassword(
                ) ? ActionUser::$changed_password : ActionUser::$update_email,
            ];
            $profileData = [
                'country_iso' => $ownerAgent->country_iso,
                'timezone'    => session('timezone'),
                'level'       => 1
            ];

            $user = $this->usersRepo->store($userData, $profileData);
            $this->generateReferenceCode->generateReferenceCode($user->id);
            $currencies  = [$currency];
            $userExclude = $this->agentsRepo->getExcludeUserMaker($owner);
            $agent       = $this->agentsRepo->store([
                'user_id'    => $user->id,
                'owner_id'   => $owner,
                'master'     => $master,
                'percentage' => $request->input('percentage'),
            ]);

            foreach ($currencies as $key => $agentCurrency) {
                $excludedUser = $this->agentsCollection->formatExcluderProvidersUsers(
                    $user->id,
                    $userExclude,
                    $agentCurrency
                );
                $this->agentsRepo->blockAgentsMakers($excludedUser);
                $wallet = Wallet::store(
                    $user->id,
                    $user->username,
                    $uuid,
                    $agentCurrency,
                    $whitelabel,
                    session('wallet_access_token')
                );

                if (empty($wallet)) {
                    Log::error(__METHOD__ . __LINE__, [
                        'user_id'       => $user->id,
                        'username'      => $user->username,
                        'uuid'          => $uuid,
                        'agentCurrency' => $agentCurrency,
                        'whitelabel'    => $whitelabel,
                        'msg'           => 'Failed to create wallet for user',
                    ]);

                    return Utils::failedResponse();
                }

                $userData   = [
                    'user_id'      => $user->id,
                    'currency_iso' => $agentCurrency
                ];
                $walletData = [
                    'wallet_id' => $wallet->data->wallet->id,
                    'default'   => $key == 0
                ];
                $userCurrenciesRepo->store($userData, $walletData);
                if (Configurations::getStore()->active) {
                    Store::storeWallet($user->id, $currency);
                }

                if ($currency == $agentCurrency) {
                    $this->createAgentBalance($agent->id, $currency, 0);
                } else {
                    $this->createAgentBalance($agent->id, $agentCurrency);
                }
            }

            Security::assignRole($user->id, Roles::$admin_assiria);

            return Utils::successResponse([
                'title'   => _i('Agent created'),
                'message' => _i('Agent created successfully'),
                'close'   => _i('Close')
            ]);
        } catch (Exception $ex) {
            Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Store rol user
     *
     * @param Request $request
     * @param UsersTempRepo $usersTempRepo
     * @param UserCurrenciesRepo $userCurrenciesRepo
     * @return Response
     * @throws ValidationException
     */
    public function storeRolUser(Request $request, UsersTempRepo $usersTempRepo, UserCurrenciesRepo $userCurrenciesRepo)
    {
        $rules = [
            'username' => ['required', new Username()],
            'password' => ['required', new Password()],
        ];

        $this->validate($request, $rules);

        $email       = strtolower($request->get('email'));
        $uniqueEmail = $this->usersRepo->uniqueEmail($email);
        if (! is_null($uniqueEmail)) {
            $data = [
                'title'   => _i('Email in use'),
                'message' => _i('The indicated email is already in use'),
                'close'   => _i('Close'),
            ];
            return Utils::errorResponse(Codes::$forbidden, $data);
        }

        try {
            //Moneda
            $currency = session('currency');
            //Whitelabel id
            $whitelabel = Configurations::getWhitelabel();
            $bonus      = Configurations::getBonus($whitelabel);
            $uuid       = Str::uuid()->toString();
            if (! is_null($request->dependence)) {
                $owner = $request->dependence;
            } else {
                $owner = auth()->user()->id;
            }
            $username = strtolower($request->username);
            $password = $request->password;
            //$email = $request->email;
            $balance            = 0;
            $uniqueUsername     = $this->usersRepo->uniqueUsername($username);
            $uniqueTempUsername = $usersTempRepo->uniqueUsername($username);
            $userExclude        = $this->agentsRepo->getExcludeUserMaker($owner);

            if (! is_null($uniqueUsername) || ! is_null($uniqueTempUsername)) {
                $data = [
                    'title'   => _i('Username in use'),
                    'message' => _i('The indicated username is already in use'),
                    'close'   => _i('Close'),
                ];
                return Utils::errorResponse(Codes::$forbidden, $data);
            }

            if (is_null($request->email)) {
                $domain = strtolower($_SERVER['HTTP_HOST']);
                $domain = str_replace('www.', '', $domain);
                $email  = "$username@$domain";
            }

            $ownerAgent = $this->agentsRepo->findByUserIdAndCurrency($owner, $currency);

            if ($balance > $ownerAgent->balance) {
                $data = [
                    'title'   => _i('Insufficient balance'),
                    'message' => _i("The agents's operational balance is insufficient to perform the transaction"),
                    'close'   => _i('Close')
                ];
                return Utils::errorResponse(Codes::$forbidden, $data);
            }

            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ipForwarded = $_SERVER['HTTP_X_FORWARDED_FOR'];
                $ip          = explode(',', $ipForwarded)[0];
            } else {
                $ip = $request->getClientIp();
            }


            $userData    = [
                'username'          => $username,
                'email'             => $email,
                'password'          => $password,
                'uuid'              => $uuid,
                'ip'                => $ip,
                'status'            => true,
                'whitelabel_id'     => $whitelabel,
                'web_register'      => false,
                'register_currency' => $currency,
                'type_user'         => TypeUser::$player,
                'action'            => ActionUser::$active
            ];
            $profileData = [
                'country_iso' => $ownerAgent->country_iso,
                'timezone'    => session('timezone'),
                'level'       => 1
            ];
            $user        = $this->usersRepo->store($userData, $profileData);
            $auditData   = [
                'ip'           => Utils::userIp(),
                'user_id'      => auth()->user()->id,
                'username'     => auth()->user()->username,
                'player_data'  => $userData,
                'profile_data' => $profileData
            ];
            Audits::store($user->id, AuditTypes::$user_creation, Configurations::getWhitelabel(), $auditData);

            $excludedUser = $this->agentsCollection->formatExcluderProvidersUsers($user->id, $userExclude, $currency);
            $this->agentsRepo->blockAgentsMakers($excludedUser);
            $wallet = Wallet::store(
                $user->id,
                $user->username,
                $uuid,
                $currency,
                $whitelabel,
                session('wallet_access_token')
            );
            $this->generateReferenceCode->generateReferenceCode($user->id);
            $userData   = [
                'user_id'      => $user->id,
                'currency_iso' => $currency
            ];
            $walletData = [
                'wallet_id' => $wallet->data->wallet->id,
                'default'   => true
            ];
            $userCurrenciesRepo->store($userData, $walletData);
            $this->agentsRepo->addUser($ownerAgent->agent, $user->id);
            $store = Configurations::getStore()->active;
            if ($store) {
                Store::storeWallet($user->id, $currency);
            }

            // Bonus registration
            if ($bonus) {
                // Fetch campaigns
                $campaigns = $this->campaignsRepo->findCampaign(
                    $whitelabel,
                    $currency,
                    AllocationCriteria::$welcome_bonus_without_deposit
                );


                // Check if $campaigns is not empty before proceeding
                if (! empty($campaigns)) {
                    // Create a wallet for bonuses
                    $walletBonus = Wallet::store(
                        $user->id,
                        $user->username,
                        $uuid,
                        $currency,
                        $whitelabel,
                        session('wallet_access_token'),
                        $bonus,
                        null,
                        $campaigns->id
                    );

                    // Participate in the welcome bonus program
                    $participation = Bonus::welcomeRegister(
                        $whitelabel,
                        $currency,
                        $user->id,
                        $walletBonus->data->bonus[0]->id,
                        session('wallet_access_token'),
                        1,
                        $balance
                    );
                } else {
                    // Create a wallet without a campaign
                    $walletBonus = Wallet::store(
                        $user->id,
                        $user->username,
                        $uuid,
                        $currency,
                        $whitelabel,
                        session('wallet_access_token'),
                        $bonus,
                        null,
                        null
                    );
                }
            }


            if ($balance > 0) {
                $providerTransaction = Str::uuid()->toString();
                $additionalData      = [
                    'provider_transaction' => $providerTransaction,
                    'from'                 => $ownerAgent->username,
                    'to'                   => $user->username
                ];
                Wallet::creditManualTransactions(
                    $balance,
                    Providers::$agents,
                    $additionalData,
                    $wallet->data->wallet->id
                );

                $ownerBalance = $ownerAgent->balance - $balance;
                $agentData    = [
                    'agent_id'     => $ownerAgent->agent,
                    'currency_iso' => $currency
                ];
                $balanceData  = [
                    'balance' => $ownerBalance
                ];
                $this->agentCurrenciesRepo->store($agentData, $balanceData);

                $additionalData['balance'] = $ownerBalance;
                $transactionData           = [
                    'user_id'               => $ownerAgent->id,
                    'amount'                => $balance,
                    'currency_iso'          => $currency,
                    'transaction_type_id'   => TransactionTypes::$debit,
                    'transaction_status_id' => TransactionStatus::$approved,
                    'provider_id'           => Providers::$agents,
                    'data'                  => $additionalData,
                    'whitelabel_id'         => Configurations::getWhitelabel()
                ];
                $this->transactionsRepo->store($transactionData, TransactionStatus::$approved, []);
            } else {
                $ownerBalance = $ownerAgent->balance;
            }

            $data = [
                'title'   => _i('Player created'),
                'message' => _i('Player created successfully'),
                'close'   => _i('Close')
            ];
            return Utils::successResponse($data);
        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Provider currency agent
     *
     *
     * @return Application|Factory|View
     */
    public function providerCurrency(ProvidersRepo $providersRepo, $currency)
    {
        try {
            $whitelabel    = Configurations::getWhitelabel();
            $providerTypes = [
                ProviderTypes::$casino,
                ProviderTypes::$live_casino,
                ProviderTypes::$casino,
                ProviderTypes::$virtual,
                ProviderTypes::$sportbook,
                ProviderTypes::$racebook,
                ProviderTypes::$live_games,
                ProviderTypes::$poker
            ];
            $providers     = $providersRepo->getByWhitelabelAndTypes($whitelabel, $currency, $providerTypes);
            return Utils::successResponse($providers);
        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'currency' => $currency]);
            return Utils::failedResponse();
        }
    }

    /**
     * Relocation agents
     *
     * @param int $agent Agent ID
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws ValidationException
     */
    public function relocationAgentsData($agentMoveId = null)
    {
        try {
            if (! is_null($agentMoveId)) {
                $user       = auth()->user()->id;
                $currency   = session('currency');
                $agent      = $this->agentsRepo->findByUserIdAndCurrency($user, $currency);
                $whitelabel = Configurations::getWhitelabel();

                $agents = $this->agentsRepo->getSearchAgentsByOwner($currency, $user, $whitelabel);

                $selectUsers = $this->agentsCollection->formatRelocationAgents(
                    $agent,
                    $agents,
                    $currency,
                    $agentMoveId
                );
            } else {
                $selectUsers = [];
            }

            $data = [
                'agents' => $selectUsers
            ];
            return Utils::successResponse($data);
        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'agent' => $agent]);
            return Utils::failedResponse();
        }
    }


    /**
     * Show role
     * @param string $username
     * @return Application|Factory|\Illuminate\Contracts\View\View|RedirectResponse|void
     */
    public function role(string $username = '')
    {
        try {
            $authUser       = auth()->user();
            $authUserId     = $authUser->id;
            $whitelabelId   = Configurations::getWhitelabel();
            $bonus          = Configurations::getBonus();
            $agentUser      = $authUser->agent;
            $userData       = $this->usersRepo->getUsers($authUserId);
            $confirmation   = $userData->pluck('confirmation_email')->first();
            $customUsername = ! empty($username) ? $username : $authUser->username;
            $currency       = session('currency');
            $agentsData     = $this->agentsRepo->getAgentsByOwner($authUserId, $currency);
            $dependence     = $this->agentsCollection->childAgents($agentsData, $currency);

            $user = ! empty($username) ? $this->usersRepo->getByUsername(
                $username,
                $whitelabelId
            ) : $authUser;

            if (empty($user)) {
                return redirect()->back();
            }

            $userId     = $user?->id;
            $percentage = null;
            $agentsRepo = new AgentsRepo();

            $agent = ($user->type_user == 'agent')
                ? $agentsRepo->findByUserIdAndCurrency($user->id, session('currency'))
                : $agentsRepo->findUser($user->id);

            if ($user->type_user == 'player') {
                $wallet = Wallet::getByClient($user->id, $currency, $bonus);
                if (is_array($wallet->data)) {
                    Log::info("Error in user wallet array {$user->id}", [$wallet]);
                }
                $balance       = ! is_array($wallet->data)
                    ? $wallet?->data?->wallet?->balance
                    : 0;
                $usernameOwner = $agent?->ownerAgent?->username ?? '';
            } else {
                $userOwner     = $this->usersRepo->getTokenByUser($agent->owner);
                $percentage    = $agent->percentage;
                $usernameOwner = $userOwner->username ?? '';
                $balance       = ($user->type_user == 'agent') ? $agent?->balance : $agent?->wallet?->balance;
            }

            if (empty($usernameOwner)) {
                return redirect()->back();
            }

            $agentsCollection = new AgentsCollection();
            $userAgent        = $agentsCollection->formatRole($usernameOwner, $user, $balance, $percentage);

            $profit = $this->transactionsRepo->calculateChildrenTotalProfitForCurrentMonthToDate(
                $userId,
                $currency,
                $whitelabelId
            );

            return view('back.agents.role', [
                'agent'              => $this->agentsRepo->findUserProfile($user->id, $currency ?? ''),
                'makers'             => [],
                /*   'agents'             => $this->agentsRepo->getAgentsAllByOwner(
                       $authUserId,
                       $currency,
                       $whitelabelId
                   ),*/
                'action'             => $authUser->action,
                'iagent'             => $agentUser,
                'confirmation_email' => $confirmation,
                'title'              => _i('Agents module'),
                'authUser'           => $userAgent,
                'username'           => $customUsername,
                'dependencies'       => $dependence,
                'profit'             => formatAmount($profit),
            ]);
        } catch (Exception $ex) {
            Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }


    /**
     * Show role dashboard
     *
     * @return Application|Factory|View
     */
    public function dashboard()
    {
        try {
            return view('back.agents.role-dashboard', [
                'dashboard' => $this->reportRepo->dashboard()
            ]);
        } catch (Exception $ex) {
            Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }


    /**
     * Search username
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function searchAgent(Request $request)
    {
        try {
            $user        = auth()->user()->id;
            $currency    = session('currency');
            $whitelabel  = Configurations::getWhitelabel();
            $name        = strtolower($request->user);
            $agents      = $this->agentsRepo->getSearchAgentsByOwner($currency, $user, $whitelabel);
            $status      = false;
            $selectUsers = $this->agentsCollection->dependencySelect($name, $agents, [], $whitelabel, $status);

            $data = [
                'agents' => $selectUsers
            ];
            return Utils::successResponse($data);
        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
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
        try {
            return $this->agentService->searchUserByUsername($request);
        } catch (Exception $ex) {
            return $this->agentService->handleAndRespondToError($request, $ex);
        }
    }

    /**
     * Store main agents
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function storeMainAgents(Request $request)
    {
        $this->validate($request, [
            'whitelabel' => 'required'
        ]);

        try {
            $whitelabel     = $request->whitelabel;
            $admin          = 'admin';
            $support        = 'wolf';
            $supportgl      = 'supportgl';
            $romeoAgent     = null;
            $supportAgent   = null;
            $supportAgl     = null;
            $adminAgent     = null;
            $supportAgentgl = null;
            $supportUser    = $this->usersRepo->getByUsername($support, $whitelabel);
            $adminUser      = $this->usersRepo->getByUsername($admin, $whitelabel);
            $supportglUser  = $this->usersRepo->getByUsername($supportgl, $whitelabel);

            if (is_null($supportUser)) {
                $data = [
                    'title'   => _i('User %s does not exist', [$support]),
                    'message' => _i('The %s user has not yet been created. Please create it first', [$support]),
                    'close'   => _i('Close')
                ];
                return Utils::errorResponse(Codes::$forbidden, $data);
            }

            if (is_null($supportglUser)) {
                $data = [
                    'title'   => _i('User %s does not exist', [$supportgl]),
                    'message' => _i('The %s user has not yet been created. Please create it first', [$supportgl]),
                    'close'   => _i('Close')
                ];
                return Utils::errorResponse(Codes::$forbidden, $data);
            }

            if (is_null($adminUser)) {
                $data = [
                    'title'   => _i('User %s does not exist', [$admin]),
                    'message' => _i('The %s user has not yet been created. Please create it first', [$admin]),
                    'close'   => _i('Close')
                ];
                return Utils::errorResponse(Codes::$forbidden, $data);
            }

            $supportAgent = $this->agentsRepo->existAgent($supportUser->id);
            $supportAgl   = $this->agentsRepo->existAgent($supportglUser->id);
            $adminAgent   = $this->agentsRepo->existAgent($adminUser->id);
            $currencies   = Configurations::getCurrenciesByWhitelabel($whitelabel);

            if (is_null($supportAgent)) {
                $supportAgentData = [
                    'user_id'  => $supportUser->id,
                    'owner_id' => null,
                    'master'   => true
                ];
                $supportAgent     = $this->agentsRepo->store($supportAgentData);
            }

            if (is_null($supportAgl)) {
                $supportAgentData = [
                    'user_id'  => $supportglUser->id,
                    'owner_id' => $supportUser->id,
                    'master'   => true
                ];
                $supportAgentgl   = $this->agentsRepo->store($supportAgentData);
            }

            if (is_null($adminAgent)) {
                $adminAgentData = [
                    'user_id'  => $adminUser->id,
                    'owner_id' => $supportglUser->id,
                    'master'   => true
                ];
                $adminAgent     = $this->agentsRepo->store($adminAgentData);
                Security::deleteAssignRole($adminUser->id);
                Security::assignRole($adminUser->id, Roles::$admin_assiria);
                $auditData = [
                    'ip'          => Utils::userIp(),
                    'user_id'     => auth()->user()->id,
                    'username'    => auth()->user()->username,
                    'agents_data' => [
                        'type' => $admin,
                        'data' => $adminAgentData
                    ]
                ];
                //Audits::store($user, AuditTypes::$agent_creation, Configurations::getWhitelabel(), $auditData);
            }

            foreach ($currencies as $currency) {
                $balance = [
                    'balance' => 0
                ];

                if (! is_null($romeoAgent)) {
                    $romeoAgentCurrencyData = [
                        'agent_id'     => $romeoAgent->id,
                        'currency_iso' => $currency,
                    ];
                    $this->agentCurrenciesRepo->store($romeoAgentCurrencyData, $balance);
                }

                if (! is_null($supportAgent)) {
                    $supportAgentCurrencyData = [
                        'agent_id'     => $supportAgent->id,
                        'currency_iso' => $currency,
                    ];
                    $this->agentCurrenciesRepo->store($supportAgentCurrencyData, $balance);
                }
                if (! is_null($supportAgentgl)) {
                    $supportAgentCurrencyData = [
                        'agent_id'     => $supportAgentgl->id,
                        'currency_iso' => $currency,
                    ];
                    $this->agentCurrenciesRepo->store($supportAgentCurrencyData, $balance);
                }

                if (! is_null($adminAgent)) {
                    $adminAgentCurrencyData = [
                        'agent_id'     => $adminAgent->id,
                        'currency_iso' => $currency,
                    ];
                    $this->agentCurrenciesRepo->store($adminAgentCurrencyData, $balance);
                }
            }

            //TODO VALIDATE CURRENCY
            if ($request->has('update_currency') && $request->get('update_currency') == 'true') {
                if (Configurations::getAgents()->active == true) {
                    $userId = Auth::user()->id;
                    if (Auth::user()->username == 'romeo' || Auth::user()->username == 'develop') {
                        $userTmp = $this->usersRepo->findUserCurrencyByWhitelabel(
                            'wolf',
                            session('currency'),
                            Configurations::getWhitelabel()
                        );
                        $userId  = isset($userTmp[0]->id) ? $userTmp[0]->id : null;
                    }

                    $users = $this->agentsCollection->childrenTreeSql($userId);
                    foreach ($users as $index => $value) {
                        //TODO TYPE USER AGENT
                        if (in_array($value->type_user, [TypeUser::$agentMater, TypeUser::$agentCajero])) {
                            foreach ($currencies as $currency) {
                                //TODO NO EXISTE LA RELACION CURRENCY => CREARLA
                                $issetUserRol = $this->usersRepo->findCurrencyAgent($currency, $value->id_agent);
                                if (! isset($issetUserRol->id)) {
                                    $romeoAgentCurrencyData = [
                                        'agent_id'     => $value->id_agent,
                                        'currency_iso' => $currency,
                                    ];
                                    $this->agentCurrenciesRepo->store($romeoAgentCurrencyData, ['balance' => 0]);
                                }
                            }
                        }
                    }
                }
            }

            $data = [
                'title'   => _i('Agents created'),
                'message' => _i('Agents were created successfully'),
                'close'   => _i('Close'),
            ];
            return Utils::successResponse($data);
        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Store users
     *
     * @param Request $request
     * @param UsersTempRepo $usersTempRepo
     * @param UserCurrenciesRepo $userCurrenciesRepo
     * @return Response
     * @throws ValidationException
     */
    public function storeUser(Request $request, UsersTempRepo $usersTempRepo, UserCurrenciesRepo $userCurrenciesRepo)
    {
        $rules = [
            'username' => ['required', new Username()],
            'password' => ['required', new Password()],
            'balance'  => 'required',
            'country'  => 'required',
            'timezone' => 'required'
        ];
        if (! is_null($request->email)) {
            $rules['email'] = ['required', new Email()];
        }

        $this->validate($request, $rules);

        $email       = strtolower($request->get('email'));
        $uniqueEmail = $this->usersRepo->uniqueEmail($email);
        if (! is_null($uniqueEmail)) {
            $data = [
                'title'   => _i('Email in use'),
                'message' => _i('The indicated email is already in use'),
                'close'   => _i('Close'),
            ];
            return Utils::errorResponse(Codes::$forbidden, $data);
        }

        try {
            //Moneda
            $currency = session('currency');
            //Whitelabel id
            $whitelabel = Configurations::getWhitelabel();
            $bonus      = Configurations::getBonus($whitelabel);
            $uuid       = Str::uuid()->toString();
            $owner      = auth()->user()->id;
            $username   = strtolower($request->username);
            $password   = $request->password;
            //$email = $request->email;
            $balance            = $request->balance;
            $country            = $request->country;
            $timezone           = $request->timezone;
            $uniqueUsername     = $this->usersRepo->uniqueUsername($username);
            $uniqueTempUsername = $usersTempRepo->uniqueUsername($username);
            $userExclude        = $this->agentsRepo->getExcludeUserMaker($owner);

            if (! is_null($uniqueUsername) || ! is_null($uniqueTempUsername)) {
                $data = [
                    'title'   => _i('Username in use'),
                    'message' => _i('The indicated username is already in use'),
                    'close'   => _i('Close'),
                ];
                return Utils::errorResponse(Codes::$forbidden, $data);
            }

            if (is_null($request->email)) {
                $domain = strtolower($_SERVER['HTTP_HOST']);
                $domain = str_replace('www.', '', $domain);
                $email  = "$username@$domain";
            }

            $ownerAgent = $this->agentsRepo->findByUserIdAndCurrency($owner, $currency);

            if ($balance > $ownerAgent->balance) {
                $data = [
                    'title'   => _i('Insufficient balance'),
                    'message' => _i("The agents's operational balance is insufficient to perform the transaction"),
                    'close'   => _i('Close')
                ];
                return Utils::errorResponse(Codes::$forbidden, $data);
            }

            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ipForwarded = $_SERVER['HTTP_X_FORWARDED_FOR'];
                $ip          = explode(',', $ipForwarded)[0];
            } else {
                $ip = $request->getClientIp();
            }


            $userData    = [
                'username'          => $username,
                'email'             => $email,
                'password'          => $password,
                'uuid'              => $uuid,
                'ip'                => $ip,
                'status'            => true,
                'whitelabel_id'     => $whitelabel,
                'web_register'      => false,
                'register_currency' => $currency,
                'type_user'         => TypeUser::$player,
                'action'            => ActionUser::$active
            ];
            $profileData = [
                'country_iso' => $country,
                'timezone'    => $timezone,
                'level'       => 1
            ];
            $user        = $this->usersRepo->store($userData, $profileData);
            $auditData   = [
                'ip'           => Utils::userIp(),
                'user_id'      => auth()->user()->id,
                'username'     => auth()->user()->username,
                'player_data'  => $userData,
                'profile_data' => $profileData
            ];
            Audits::store($user->id, AuditTypes::$user_creation, Configurations::getWhitelabel(), $auditData);

            $excludedUser = $this->agentsCollection->formatExcluderProvidersUsers($user->id, $userExclude, $currency);
            $this->agentsRepo->blockAgentsMakers($excludedUser);
            $wallet = Wallet::store(
                $user->id,
                $user->username,
                $uuid,
                $currency,
                $whitelabel,
                session('wallet_access_token')
            );
            $this->generateReferenceCode->generateReferenceCode($user->id);
            $userData   = [
                'user_id'      => $user->id,
                'currency_iso' => $currency
            ];
            $walletData = [
                'wallet_id' => $wallet->data->wallet->id,
                'default'   => true
            ];
            $userCurrenciesRepo->store($userData, $walletData);
            $this->agentsRepo->addUser($ownerAgent->agent, $user->id);
            $store = Configurations::getStore()->active;
            if ($store) {
                Store::storeWallet($user->id, $currency);
            }

            // Bonus registration
            if ($bonus) {
                // Fetch campaigns
                $campaigns = $this->campaignsRepo->findCampaign(
                    $whitelabel,
                    $currency,
                    AllocationCriteria::$welcome_bonus_without_deposit
                );


                // Check if $campaigns is not empty before proceeding
                if (! empty($campaigns)) {
                    // Create a wallet for bonuses
                    $walletBonus = Wallet::store(
                        $user->id,
                        $user->username,
                        $uuid,
                        $currency,
                        $whitelabel,
                        session('wallet_access_token'),
                        $bonus,
                        null,
                        $campaigns->id
                    );

                    // Participate in the welcome bonus program
                    $participation = Bonus::welcomeRegister(
                        $whitelabel,
                        $currency,
                        $user->id,
                        $walletBonus->data->bonus[0]->id,
                        session('wallet_access_token'),
                        1,
                        $balance
                    );
                } else {
                    // Create a wallet without a campaign
                    $walletBonus = Wallet::store(
                        $user->id,
                        $user->username,
                        $uuid,
                        $currency,
                        $whitelabel,
                        session('wallet_access_token'),
                        $bonus,
                        null,
                        null
                    );
                }
            }


            if ($balance > 0) {
                $providerTransaction = Str::uuid()->toString();
                $additionalData      = [
                    'provider_transaction' => $providerTransaction,
                    'from'                 => $ownerAgent->username,
                    'to'                   => $user->username
                ];
                Wallet::creditManualTransactions(
                    $balance,
                    Providers::$agents,
                    $additionalData,
                    $wallet->data->wallet->id
                );

                $ownerBalance = $ownerAgent->balance - $balance;
                $agentData    = [
                    'agent_id'     => $ownerAgent->agent,
                    'currency_iso' => $currency
                ];
                $balanceData  = [
                    'balance' => $ownerBalance
                ];
                $this->agentCurrenciesRepo->store($agentData, $balanceData);

                $additionalData['balance'] = $ownerBalance;
                $transactionData           = [
                    'user_id'               => $ownerAgent->id,
                    'amount'                => $balance,
                    'currency_iso'          => $currency,
                    'transaction_type_id'   => TransactionTypes::$debit,
                    'transaction_status_id' => TransactionStatus::$approved,
                    'provider_id'           => Providers::$agents,
                    'data'                  => $additionalData,
                    'whitelabel_id'         => Configurations::getWhitelabel()
                ];
                $this->transactionsRepo->store($transactionData, TransactionStatus::$approved, []);
            } else {
                $ownerBalance = $ownerAgent->balance;
            }

            $data = [
                'title'   => _i('Player created'),
                'message' => _i('Player created successfully'),
                'close'   => _i('Close'),
                'balance' => number_format($ownerBalance, 2),
                'route'   => route('agents.index')
            ];
            return Utils::successResponse($data);
        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Update percentage
     *
     * @param Request $request
     * @return Response
     */
    public function updatePercentage(Request $request)
    {
        try {
            $agentData = [
                'percentage' => $request->percentage
            ];
            $agentId   = $request->agent_id;
            $this->agentsRepo->update($agentId, $agentData);

            $data = [
                'title'   => _i('Percentage updated'),
                'message' => _i('Percentage of agent successfully updated'),
                'close'   => _i('Close'),
            ];
            return Utils::successResponse($data);
        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Update Rol
     *
     * @param Request $request
     * @return Response
     */
    public function updateRol(Request $request)
    {
        $this->validate($request, [
            'dependence' => ['required'],
            'type'       => ['required'],
            'user_id'    => ['required'],
        ]);
        try {
            $type     = $request->input('type');
            $userId   = $request->input('user_id');
            $ownerId  = $request->input('dependence');
            $currency = session('currency');
            if ($type != 5) {
                $agentData = [
                    'percentage' => $request->input('percentage'),
                    'owner_id'   => $ownerId,
                ];
                $agent     = $this->agentsRepo->findByUserIdAndCurrency($userId, $currency);
                $this->agentsRepo->update($agent->agent, $agentData);
                $data = [
                    'title'   => _i('Agent updated'),
                    'message' => _i('agent successfully updated'),
                    'close'   => _i('Close'),
                ];
                return Utils::successResponse($data);
            } else {
                $agent     = $this->agentsRepo->findByUserIdAndCurrency($ownerId, $currency);
                $agentData = $this->agentsRepo->findAgentCashier($agent->agent);
                $this->agentsRepo->moveAgentFromUser($agentData, $userId);
                $data = [
                    'title'   => _i('Player updated'),
                    'message' => _i('Player successfully updated'),
                    'close'   => _i('Close'),
                ];
                return Utils::successResponse($data);
            }
        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Agents users
     *
     * @param int $user User ID
     * @return Response
     */
    public function users($user)
    {
        try {
            $currency = session('currency');
            $agent    = $this->agentsRepo->findByUserIdAndCurrency($user, $currency);
            $users    = $this->agentsRepo->getUsersByAgent($agent->agent, $currency);
            $this->agentsCollection->formatUsers($users, $currency);
            $data = [
                'users' => $users
            ];
            return Utils::successResponse($data);
        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Get users balances report data
     *
     * @param null|string $startDate Start date to filter
     * @param null|string $endDate End date to filter
     * @return Response
     */
    public function usersBalancesData()
    {
        try {
            if (session('admin_id')) {
                $user = session('admin_id');
            } else {
                $user = auth()->user()->id;
            }
            $currency = session('currency');
            $agent    = $this->agentsRepo->findByUserIdAndCurrency($user, $currency);
            $users    = $this->agentsRepo->getUsersByAgent($agent->agent, $currency);
            $usersIds = [];

            foreach ($users as $user) {
                $usersIds[] = $user->id;
            }

            if (! empty($usersIds)) {
                $wallets     = Wallet::getUsersBalancesByIds($usersIds, $currency);
                $walletsData = $wallets->data->wallets;
                $usersData   = $this->agentsCollection->usersBalances($walletsData);
            } else {
                $usersData = [
                    'users'          => [],
                    'total_balances' => '0.00'
                ];
            }
            return Utils::successResponse($usersData);
        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     *  Show view users balance report
     *
     * @return Factory|View
     */
    public function usersBalances()
    {
        $data['title'] = _i('Users balances');
        return view('back.agents.reports.users-balances', $data);
    }

    /**
     * View Create Agent
     * @param CountriesRepo $countriesRepo
     * @param ProvidersRepo $providersRepo
     * @param ClosuresUsersTotalsRepo $closuresUsersTotalsRepo
     * @param ReportsCollection $reportsCollection
     * @return Application|Factory|\Illuminate\Contracts\View\View|void
     */
    public function viewCreateAgent(
        CountriesRepo $countriesRepo,
        ProvidersRepo $providersRepo,
        ClosuresUsersTotalsRepo $closuresUsersTotalsRepo,
        ReportsCollection $reportsCollection
    ) {
        try {
            $id = auth()->user()->id;
            if (Auth::user()->username == 'romeo' || Auth::user()->username == 'develop') {
                $userTmp = $this->usersRepo->findUserCurrencyByWhitelabel(
                    'wolf',
                    session('currency'),
                    Configurations::getWhitelabel()
                );
                $id      = isset($userTmp[0]->id) ? $userTmp[0]->id : auth()->user()->id;
            }

            $data['agent']     = $this->agentsRepo->findByUserIdAndCurrency($id, session('currency'));
            $data['countries'] = $countriesRepo->all();
            $data['timezones'] = \DateTimeZone::listIdentifiers();

            $data['title'] = _i('Create agent');

            return view('back.agents.user-and-agent.create-agent', $data);
        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    /**
     * View Create User
     * @param CountriesRepo $countriesRepo
     * @param ProvidersRepo $providersRepo
     * @param ClosuresUsersTotalsRepo $closuresUsersTotalsRepo
     * @param ReportsCollection $reportsCollection
     * @return Application|Factory|\Illuminate\Contracts\View\View|void
     */
    public function viewCreateUser(
        CountriesRepo $countriesRepo,
        ProvidersRepo $providersRepo,
        ClosuresUsersTotalsRepo $closuresUsersTotalsRepo,
        ReportsCollection $reportsCollection
    ) {
        try {
            $id = auth()->user()->id;
            if (Auth::user()->username == 'romeo' || Auth::user()->username == 'develop') {
                $userTmp = $this->usersRepo->findUserCurrencyByWhitelabel(
                    'wolf',
                    session('currency'),
                    Configurations::getWhitelabel()
                );
                $id      = isset($userTmp[0]->id) ? $userTmp[0]->id : auth()->user()->id;
            }

            $data['agent']     = $this->agentsRepo->findByUserIdAndCurrency($id, session('currency'));
            $data['countries'] = $countriesRepo->all();
            $data['timezones'] = \DateTimeZone::listIdentifiers();
            $data['title']     = _i('Create player');

            return view('back.agents.user-and-agent.create-user', $data);
        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    /**
     * View Test of pagination
     * @return Application|Factory|\Illuminate\Contracts\View\View
     */
    public function viewTmp(Request $request)
    {
        $data['title'] = _i('reports') . ' Tmp';

        return view('back.agents.reports.tmp', $data);
    }

    /**
     * View Transaction Timeline
     * @return Application|Factory|\Illuminate\Contracts\View\View
     */
    public function viewTransactionTimeline(Request $request)
    {
        $data['title'] = _i('Transaction Timeline');

        return view('back.agents.reports.transaction-timeline', $data);
    }
}

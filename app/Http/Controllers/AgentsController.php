<?php

namespace App\Http\Controllers;

use App\Agents\Collections\AgentsCollection;
use App\Agents\Repositories\AgentCurrenciesRepo;
use App\Agents\Repositories\AgentsRepo;
use App\Core\Collections\TransactionsCollection;
use App\Core\Repositories\CountriesRepo;
use App\Core\Repositories\CurrenciesRepo;
use App\Core\Repositories\ProvidersRepo;
use App\Core\Repositories\ProvidersTypesRepo;
use App\Core\Repositories\TransactionsRepo;
use App\Reports\Collections\ReportsCollection;
use App\Reports\Repositories\ClosuresUsersTotalsRepo;
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
use Dotworkers\Configurations\Configurations;
use Dotworkers\Configurations\Enums\Codes;
use Dotworkers\Configurations\Enums\Providers;
use Dotworkers\Configurations\Enums\ProviderTypes;
use Dotworkers\Configurations\Enums\Status;
use Dotworkers\Configurations\Enums\TransactionStatus;
use Dotworkers\Configurations\Enums\TransactionTypes;
use Dotworkers\Configurations\Utils;
use Dotworkers\Security\Enums\Roles;
use Dotworkers\Security\Security;
use Dotworkers\Sessions\Sessions;
use Dotworkers\Store\Store;
use Dotworkers\Wallet\Wallet;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

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
     * UsersRepo
     *
     * @var UsersRepo
     */
    private $usersRepo;

    /**
     * TransactionsRepo
     *
     * @var TransactionsRepo
     */
    private $transactionsRepo;

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
     * UsersCollection
     *
     * @var UsersCollection
     */
    private $usersCollection;


    /***
     * AgentsController constructor.
     *
     * @param UsersRepo $usersRepo
     * @param AgentsRepo $agentsRepo
     * @param AgentsCollection $agentsCollection
     * @param CountriesRepo $countriesRepo
     * @param TransactionsRepo $transactionsRepo
     * @param UsersTempRepo $usersTempRepo
     * @param AgentCurrenciesRepo $agentCurrenciesRepo
     * @param GenerateReferenceCode $generateReferenceCode
     * @param WhitelabelsRepo $whitelabelsRepo
     * @param CurrenciesRepo $currenciesRepo
     * @param UsersCollection $usersCollection
     */
    public function __construct(AgentsRepo $agentsRepo, AgentsCollection $agentsCollection, UsersRepo $usersRepo, TransactionsRepo $transactionsRepo, AgentCurrenciesRepo $agentCurrenciesRepo, GenerateReferenceCode $generateReferenceCode, WhitelabelsRepo $whitelabelsRepo, CurrenciesRepo $currenciesRepo, UsersCollection $usersCollection)
    {
        $this->agentsRepo = $agentsRepo;
        $this->agentsCollection = $agentsCollection;
        $this->usersRepo = $usersRepo;
        $this->transactionsRepo = $transactionsRepo;
        $this->agentCurrenciesRepo = $agentCurrenciesRepo;
        $this->generateReferenceCode = $generateReferenceCode;
        $this->whitelabelsRepo = $whitelabelsRepo;
        $this->currenciesRepo = $currenciesRepo;
        $this->usersCollection = $usersCollection;
    }

    /**
     * Show add users
     *
     * @return Application|Factory|View
     */
    public function addUsers()
    {
        $user = auth()->user()->id;
        $currency = session('currency');
        $agentsData = $this->agentsRepo->getAgentsByOwner($user, $currency);
        $agents = $this->agentsCollection->childAgents($agentsData, $currency);
        $data['agents'] = $agents;
        $data['title'] = _i('Add users');
        return view('back.agents.add-users', $data);
    }

    /**
     *  Add users to agent
     *
     * @param Request $request
     * @return Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function addUsersData(Request $request)
    {
        $this->validate($request, [
            'username' => ['required', new Username()],
            'agent' => 'required'
        ]);

        try {
            $username = strtolower(trim($request->username));
            $agent = $request->agent;
            $currency = session('currency');
            $whitelabel = Configurations::getWhitelabel();
            $userData = $this->usersRepo->getUsernameByCurrency($username, $currency, $whitelabel);
            if (!is_null($userData)) {
                $agentUserData = $this->agentsRepo->existAgent($userData->id);
                if (is_null($agentUserData)) {
                    $agentUser = $this->agentsRepo->findUser($userData->id);
                    if (is_null($agentUser)) {
                        $add = $this->agentsRepo->addUser($agent, $userData->id);
                        $data = [
                            'title' => _i('Added user'),
                            'message' => _i('The user has been successfully added'),
                            'close' => _i('Close'),
                        ];
                        return Utils::successResponse($data);
                    } else {
                        $data = [
                            'title' => _i('Assigned user'),
                            'message' => _i("The user is already assigned to an agent"),
                            'close' => _i('Close')
                        ];
                        return Utils::errorResponse(Codes::$forbidden, $data);
                    }
                } else {
                    $data = [
                        'title' => _i('Agent user'),
                        'message' => _i("The user is an agent, it cannot be associated to another agent."),
                        'close' => _i('Close')
                    ];
                    return Utils::errorResponse(Codes::$forbidden, $data);
                }

            } else {
                $data = [
                    'title' => _i('The user does not exist'),
                    'message' => _i("Please check and try again"),
                    'close' => _i('Close')
                ];
                return Utils::errorResponse(Codes::$forbidden, $data);
            }
        } catch (\Exception $ex) {
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
        try {
            if (session('admin_id')) {
                $userId = session('admin_id');
                $agent_player = false;
            } else {
                $userId = auth()->user()->id;
                $agent_player = true;
            }
            $currency = session('currency');
            $id = $request->id;
            $walletId = null;
            $userAgent = $this->agentsRepo->findByUserIdAndCurrency($id, $currency);
            $user = $this->agentsRepo->findUser($id);
            if (!is_null($userAgent)) {
                $user = $userAgent;
                $balance = $userAgent->balance;
                $master = $userAgent->master;
                $agent = true;
                $myself = $userId == $userAgent->id;
                $type = 'agent';
            } else {
                $user = $this->agentsRepo->findUser($id);
                $master = false;
                $wallet = Wallet::getByClient($id, $currency);
                $balance = $wallet->data->wallet->balance;
                $agent = false;
                $walletId = $wallet->data->wallet->id;
                $myself = false;
                $type = 'user';
            }
            $this->agentsCollection->formatAgent($user);
            $data = [
                'user' => $user,
                'balance' => number_format($balance, 2),
                'master' => $master,
                'agent' => $agent,
                'wallet' => $walletId,
                'type' => $type,
                'myself' => $myself,
                'agent_player' => $agent_player
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
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
            $currency = session('currency');
            $agents = $this->agentsRepo->getAgentsByOwner($user, $currency);
            $agentsData = $this->agentsCollection->formatAgents($agents);
            return Utils::successResponse($agentsData);
        } catch (\Exception $ex) {
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
            $currency = session('currency');
            $agents = $this->agentsRepo->getAgentsByOwner($user, $currency);
            $agentsData = $this->agentsCollection->formatAgents($agents);
            return Utils::successResponse($agentsData);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
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
            $currency = session('currency');
            $providers = [Providers::$agents, Providers::$agents_users];
            $transactions = $this->transactionsRepo->getByUserAndProviders($agent, $providers, $currency);
            $this->agentsCollection->formatAgentTransactions($transactions);
            $data = [
                'transactions' => $transactions
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
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
            if (!is_null($startDate) && !is_null($endDate)) {
                $currency = session('currency');
                if (session('admin_id')) {
                    $user = session('admin_id');
                } else {
                    $user = auth()->user()->id;
                }
                $providers = [Providers::$agents, Providers::$agents_users];
                $startDate = Utils::startOfDayUtc($startDate);
                $endDate = Utils::endOfDayUtc($endDate);
                $transactions = $this->transactionsRepo->getAgentsTransactions($user, $providers, $currency, $startDate, $endDate);
            } else {
                $transactions = [];
            }
            $financial = $this->agentsCollection->formatAgentsTransactionsTotals($transactions);
            return Utils::successResponse($financial);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
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
            $ticket = $this->transactionsRepo->ticketTransactionsUser($ticketId, $whitelabel);
            $this->agentsCollection->ticketFormatter($ticket);
            $filename = \Carbon\Carbon::now()->format('dmY-His');
            $pdf = PDF::loadView('back.agents.ticket.ticket', ['ticket' => $ticket, 'filename' => $filename]);
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream();
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'ticket_id' => $ticketId]);
            return Utils::failedResponse();
        }
    }

    /**
     * Agents tree filter
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
            $agent = $this->agentsRepo->findByUserIdAndCurrency($user, $currency);
            $agents = $this->agentsRepo->getAgentsByOwner($user, $currency);
            $users = $this->agentsRepo->getUsersByAgent($agent->agent, $currency);

            $tree = $this->agentsCollection->dependencyTreeFilter($agent, $agents, $users, $status);
            return Utils::successResponse($tree);
        } catch (\Exception $ex) {

        }
    }

    /**
     * block agents data
     *
     * @param Request $request
     * @return Response
     */
    public function blockAgentsData(Request $request)
    {
        $this->validate($request, [
            'provider' => ['required_if:lock_users,false'],
            'description' => ['required_if:lock_users,true'],
        ]);

        try {
            $user = $request->user;
            $currency = session('currency');
            $provider = $request->provider;
            $type = $request->type;
            $lockUsers = $request->lock_users;

            $agent = $this->agentsRepo->findByUserIdAndCurrency($user, $currency);
            $subAgents = $this->agentsRepo->getAgentsByOwner($user, $currency);
            if (!is_null($agent)) {
                $users = $this->agentsRepo->getUsersByAgent($agent->agent, $currency);
            } else {
                $users[] = [
                    'id' => $user
                ];
            }
            $usersToUpdate = $this->agentsCollection->formatDataLock($subAgents, $users, $agent, $currency, $provider);

            $newStatus = (bool)$request->type;
            $oldStatus = !$newStatus;
            if ($lockUsers == 'false') {
                if ($type == 'true') {
                    $this->agentsRepo->blockAgents($usersToUpdate);
                    $data = [
                        'title' => _i('Locked provider'),
                        'message' => _i('The provider was locked to the agent and his entire tree'),
                        'close' => _i('Close')
                    ];
                }

                if ($type == 'false') {
                    foreach ($usersToUpdate as $userToUpdate) {
                        $currencyIso = $userToUpdate['currency_iso'];
                        $providerId = $userToUpdate['provider_id'];
                        $userId = $userToUpdate['user_id'];
                        $this->agentsRepo->unBlockAgents($currencyIso, $providerId, $userId);
                    }
                    $data = [
                        'title' => _i('Unlocked provider'),
                        'message' => _i('The provider was unlocked to the agent and his entire tree'),
                        'close' => _i('Close')
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
                            'ip' => Utils::userIp(),
                            'user_id' => auth()->user()->id,
                            'username' => auth()->user()->username,
                            'old_status' => $oldStatus,
                            'new_status' => $newStatus,
                            'description' => $request->description
                        ];
                        //Audits::store($user, AuditTypes::$agent_user_status, Configurations::getWhitelabel(), $auditData);
                    }
                    $data = [
                        'title' => _i('Locked users'),
                        'message' => _i('The agent and his entire tree was locked'),
                        'close' => _i('Close')
                    ];
                }

                if ($type == 'false') {
                    foreach ($usersToUpdate as $userToUpdate) {
                        $user = $userToUpdate['user_id'];
                        $this->agentsRepo->unBlockUsers($user);

                        $auditData = [
                            'ip' => Utils::userIp(),
                            'user_id' => auth()->user()->id,
                            'username' => auth()->user()->username,
                            'old_status' => $oldStatus,
                            'new_status' => $newStatus,
                            'description' => $request->description
                        ];
                        //Audits::store($user, AuditTypes::$agent_user_status, Configurations::getWhitelabel(), $auditData);
                    }
                    $data = [
                        'title' => _i('Unlocked users'),
                        'message' => _i('The agent and his entire tree was unlocked'),
                        'close' => _i('Close')
                    ];
                }
            }
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
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
    public function cashFlowByDatesData(TransactionsCollection $transactionsCollection, $startDate = null, $endDate = null)
    {
        try {
            $currency = session('currency');
            $whitelabel = Configurations::getWhitelabel();

            if (!is_null($startDate) && !is_null($endDate)) {
                if (session('admin_id')) {
                    $user = session('admin_id');
                    $username = session('admin_agent_username');
                } else {
                    $user = auth()->user()->id;
                    $username = auth()->user()->username;
                }

                $startDate = Utils::startOfDayUtc($startDate);
                $endDate = Utils::endOfDayUtc($endDate);
                $agents = $this->agentsRepo->getAgentsByOwner($user, $currency);
                $agentsIds = [];

                foreach ($agents as $agent) {
                    $agentsIds[] = $agent->user_id;
                }
                $financialData = $this->transactionsRepo->getCashFlowTransactions($username, $agentsIds, $whitelabel, $currency, $startDate, $endDate);
            } else {
                $financialData = [];
            }
            $financial = $transactionsCollection->formatCashFlowDataByUsers($financialData, $whitelabel, $currency, $startDate, $endDate);
            return Utils::successResponse($financial);
        } catch (\Exception $ex) {
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
            $this->agentsRepo->update($agent, $agentData);
            //$user = $this->agentsRepo->findAgentCashier($agent);
            //$this->agentsCollection->formatChangeAgentType($user);
            $data = [
                'title' => _i('Agent type changed'),
                'message' => _i('Agent type was successfully changed'),
                'close' => _i('Close'),
                //'user_type' => $user->type,
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * @param Request $request
     * @return array
     */
    public function changeTypeUser(Request $request)
    {
        $users = $this->usersRepo->sqlShareTmp('users');
        foreach ($users as $value) {
            $value->type_user = null;
            $agentTmp = $this->usersRepo->sqlShareTmp('agent', $value->id)[0] ?? null;
            if (!is_null($agentTmp)) {
                $value->type_user = TypeUser::$agentCajero;
                if (isset($agentTmp->master) && $agentTmp->master) {
                    $value->type_user = TypeUser::$agentMater;
                }
            }

            $playerTmp = $this->usersRepo->sqlShareTmp('agent_user', $value->id)[0] ?? null;
            if (!is_null($playerTmp) && isset($playerTmp->agent_id)) {
                $value->type_user = TypeUser::$player;
            }
            //TODO UPDATE
            if (!is_null($value->type_user)) {
                $this->usersRepo->sqlShareTmp('update', $value->id, $value->type_user);
            }
        }

        return $users;
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
            $endDate = $request->end_date;
            if (!is_null($startDate) && !is_null($endDate)) {
                $transactionType = $request->transaction_type;
                $currency = $request->currency;
                $whitelabel = Configurations::getWhitelabel();
                $startDate = Utils::startOfDayUtc($startDate);
                $endDate = Utils::endOfDayUtc($endDate);
                $transactions = $this->transactionsRepo->getDeposistsWithdrawalsProvider($currency, $startDate, $endDate, $transactionType, $whitelabel);
            } else {
                $transactions = [];
            }
            $data = $transactionsCollection->formatDeposistsWithdrawalsProvider($transactions);
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     * Financial state data
     *
     * @param ProvidersRepo $providersRepo
     * @param null|int $user User ID
     * @param null|string $startDate
     * @param null|string $endDate
     * @return Response
     */
    public function financialStateData(ProvidersRepo $providersRepo, $user = null, $startDate = null, $endDate = null)
    {
        try {
            $timezone = session('timezone');
            $today = Carbon::now()->setTimezone($timezone);
            $endDateOriginal = $endDate;
//            $startDate = Utils::startOfDayUtc($startDate);
//            $endDate = Utils::endOfDayUtc($endDate);

            $startDate = Utils::startOfDayUtc($startDate);
            $endDate = Utils::endOfDayUtc($endDate);


            $currency = session('currency');
            $whitelabel = Configurations::getWhitelabel();
            $providerTypes = [ProviderTypes::$casino, ProviderTypes::$live_casino, ProviderTypes::$virtual, ProviderTypes::$sportbook, ProviderTypes::$racebook, ProviderTypes::$live_games, ProviderTypes::$poker];
            $providers = $providersRepo->getByWhitelabelAndTypes($whitelabel, $currency, $providerTypes);
            $agent = $this->agentsRepo->findByUserIdAndCurrency($user, $currency);
            $agents = $this->agentsRepo->getAgentsByOwner($user, $currency);
            $users = $this->agentsRepo->getUsersByAgent($agent->agent, $currency);
            $table = $this->agentsCollection->financialState($whitelabel, $agents, $users, $currency, $providers, $startDate, $endDate, $endDateOriginal, $today);
            $data = [
                'table' => $table
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'start_date' => $startDate, 'end_date' => $endDate]);
            return Utils::failedResponse();
        }
    }

    public function financialStateDataRow2(ProvidersRepo $providersRepo, $user = null, $startDate = null, $endDate = null)
    {
        try {
            $timezone = session('timezone');
            $today = Carbon::now()->setTimezone($timezone);
            $endDateOriginal = $endDate;
            $startDate = Utils::startOfDayUtc($startDate);
            $endDate = Utils::endOfDayUtc($endDate);
            $currency = session('currency');
            $whitelabel = Configurations::getWhitelabel();
            $providerTypes = [ProviderTypes::$casino, ProviderTypes::$live_casino, ProviderTypes::$virtual, ProviderTypes::$sportbook, ProviderTypes::$racebook, ProviderTypes::$live_games, ProviderTypes::$poker];
            $providers = $providersRepo->getByWhitelabelAndTypes($whitelabel, $currency, $providerTypes);
            $agent = $this->agentsRepo->findByUserIdAndCurrency($user, $currency);
            $agents = $this->agentsRepo->getAgentsByOwner($user, $currency);
            $users = $this->agentsRepo->getUsersByAgent($agent->agent, $currency);
            $table = $this->agentsCollection->financialStateRow2($whitelabel, $agents, $users, $currency, $providers, $startDate, $endDate, $endDateOriginal, $today);
            $data = [
                'table' => $table
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'start_date' => $startDate, 'end_date' => $endDate]);
            return Utils::failedResponse();
        }
    }

    /**
     * Financial state
     *
     * @param ClosuresUsersTotalsRepo $closuresUsersTotalsRepo
     * @param ReportsCollection $reportsCollection
     * @return Application|Factory|View
     */
    public function financialState(ClosuresUsersTotalsRepo $closuresUsersTotalsRepo, ReportsCollection $reportsCollection)
    {
        $currency = session('currency');
        $whitelabel = Configurations::getWhitelabel();
        if (session('admin_id')) {
            $data['user'] = session('admin_id');
        } else {
            $data['user'] = auth()->user()->id;
        }
        $data['title'] = _i('Financial state report');
        return view('back.agents.reports.financial-state', $data);
    }

    public function financialStateData_view1(ProvidersRepo $providersRepo, ProvidersTypesRepo $providersTypesRepo, $user = null, $startDate = null, $endDate = null)
    {
        //try {
        $timezone = session('timezone');
       // $today = Carbon::now()->setTimezone($timezone);
        $startDateOriginal = $startDate;
        $endDateOriginal = $endDate;
        $startDate = Utils::startOfDayUtc($startDate);
        $endDate = Utils::endOfDayUtc($endDate);
        $currency = session('currency');
        $whitelabel = Configurations::getWhitelabel();

        //TODO Providers
        // 171:Bet Connections Slots
        $providerArrayTmp = [171];

        $treeUsers = $this->usersRepo->treeSqlByUser(auth()->user()->id, session('currency'), Configurations::getWhitelabel());

        $table = $this->agentsCollection->financialState_view1($whitelabel, $currency, $startDate, $endDate, $treeUsers);
        $data = [
            'table' => $table[0]
        ];
        return Utils::successResponse($data);
//        } catch (\Exception $ex) {
//            \Log::error(__METHOD__, ['exception' => $ex, 'start_date' => $startDate, 'end_date' => $endDate]);
//            return Utils::failedResponse();
//        }
    }

    public function financialState_view1(ClosuresUsersTotalsRepo $closuresUsersTotalsRepo, ReportsCollection $reportsCollection)
    {
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
            $startDate = Utils::startOfDayUtc($startDate);
            $endDate = Utils::endOfDayUtc($endDate);
            $currency = session('currency');
            $whitelabel = Configurations::getWhitelabel();
            $agent = $this->agentsRepo->findByUserIdAndCurrency($user, $currency);
            $agents = $this->agentsRepo->getAgentsByOwner($user, $currency);
            $users = $this->agentsRepo->getUsersByAgent($agent->agent, $currency);
            $table = $this->agentsCollection->financialStateSummaryBonus($whitelabel, $agents, $users, $currency, $startDate, $endDate);
            $data = [
                'table' => $table
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
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
            $startDate = Utils::startOfDayUtc($startDate);
            $endDate = Utils::endOfDayUtc($endDate);
            $currency = session('currency');
            $whitelabel = Configurations::getWhitelabel();
            $agent = $this->agentsRepo->findByUserIdAndCurrency($user, $currency);
            $agents = $this->agentsRepo->getAgentsByOwner($user, $currency);
            $users = $this->agentsRepo->getUsersByAgent($agent->agent, $currency);
            $table = $this->agentsCollection->financialStateSummary($whitelabel, $agents, $users, $currency, $startDate, $endDate);
            $data = [
                'table' => $table
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
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
     * Find agents and users
     *
     * @param Request $request
     * @return Response
     */
    public function find(Request $request)
    {
        try {
            if (session('admin_id')) {
                $userId = session('admin_id');
                $agent_player = false;
            } else {
                $userId = auth()->user()->id;
                $agent_player = true;
            }
            $currency = session('currency');
            $id = $request->id;
            $type = $request->type;
            $walletId = null;
            if ($type == 'agent') {
                $user = $this->agentsRepo->findByUserIdAndCurrency($id, $currency);
                $balance = $user->balance;
                $master = $user->master;
                $agent = true;
                $myself = $userId == $user->id;
            } else {
                $user = $this->agentsRepo->findUser($id);
                $master = false;
                $wallet = Wallet::getByClient($id, $currency);
                $balance = $wallet->data->wallet->balance;
                $agent = false;
                $walletId = $wallet->data->wallet->id;
                $myself = false;
            }

            $this->agentsCollection->formatAgent($user);
            $data = [
                'user' => $user,
                'balance' => number_format($balance, 2),
                'master' => $master,
                'agent' => $agent,
                'wallet' => $walletId,
                'type' => $type,
                'myself' => $myself,
                'agent_player' => $agent_player
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
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
    public function index(CountriesRepo $countriesRepo, ProvidersRepo $providersRepo, ClosuresUsersTotalsRepo $closuresUsersTotalsRepo, ReportsCollection $reportsCollection)
    {
        try {
            if (session('admin_id')) {
                $user = session('admin_id');
            } else {
                $user = auth()->user()->id;
            }
            $whitelabel = Configurations::getWhitelabel();
            $currency = session('currency');
            $agent = $this->agentsRepo->findByUserIdAndCurrency($user, $currency);
            //return [$agent,$user,$currency];
            $agents = $this->agentsRepo->getAgentsByOwner($user, $currency);
            $users = $this->agentsRepo->getUsersByAgent($agent->agent, $currency);
            $tree = $this->agentsCollection->dependencyTree($agent, $agents, $users);
            $agentAndSubAgents = $this->agentsCollection->formatAgentandSubAgents($agents);
            $providerTypes = [ProviderTypes::$casino, ProviderTypes::$live_casino, ProviderTypes::$casino, ProviderTypes::$virtual, ProviderTypes::$sportbook, ProviderTypes::$racebook, ProviderTypes::$live_games, ProviderTypes::$poker];
            $providers = $providersRepo->getByWhitelabelAndTypes($whitelabel, $currency, $providerTypes);
            $data['currencies'] = Configurations::getCurrencies();
            $data['countries'] = $countriesRepo->all();
            $data['timezones'] = \DateTimeZone::listIdentifiers();
            $data['providers'] = $providers;
            $data['agent'] = $agent;
            $data['agents'] = $agentAndSubAgents;
            $data['tree'] = $tree;
            $data['title'] = _i('Agents module');
            return view('back.agents.index', $data);
        } catch (\Exception $ex) {
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
            if (!is_null($currency)) {
                $whitelabel = Configurations::getWhitelabel();
                $provider = $request->provider;
                $agents = $this->agentsRepo->getAgentLockByProvider($currency, $provider, $whitelabel);
            } else {
                $agents = [];
            }
            $this->agentsCollection->formatAgentLockByProvider($agents);
            $data = [
                'agents' => $agents
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
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
        $data['title'] = _i('Create main agents');
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
            if (!is_null($startDate) || !is_null($endDate) && !is_null($currency)) {
                if (session('admin_id')) {
                    $user = session('admin_id');
                } else {
                    $user = auth()->user()->id;
                }
                $agents = $this->agentsRepo->getAgentsByOwner($user, $currency);
                $users = $this->agentsCollection->formatAgentsId($agents, $currency);
                if (!in_array($user, $users)) {
                    $users[] = $user;
                }
                $startDate = Utils::startOfDayUtc($startDate);
                $endDate = Utils::endOfDayUtc($endDate);
                $providers = [Providers::$agents, Providers::$agents_users];
                $whitelabel = Configurations::getWhitelabel();
                $transactions = $this->transactionsRepo->getManualTransactionsFromAgents($users, $startDate, $endDate, $providers, $currency, $whitelabel);
                $this->agentsCollection->formatAgentTransactionsReport($transactions);
            } else {
                $transactions = [];
            }

            $data = [
                'transactions' => $transactions
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
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
            $agentId = $request->agent;
            $agent = $this->agentsRepo->existAgent($userAgent);
            if (is_null($agent)) {
                $data = [
                    'title' => _i('Agent moved'),
                    'message' => _i('Agent not displaced'),
                    'close' => _i('Close')
                ];
                return Utils::errorResponse(Codes::$forbidden, $data);
            }
            $agentData = [
                'owner_id' => $agentId,
                'user_id' => $userAgent,
            ];
            $this->agentsRepo->update($agent->id, $agentData);
            $data = [
                'title' => _i('Agent moved'),
                'message' => _i('Agent moved successfully'),
                'close' => _i('Close')
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
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
            $agent = $request->agent;

            $agent = $this->agentsRepo->existAgent($agent);
            $this->agentsRepo->moveAgentFromUser($agent, $userAgent);
            $data = [
                'title' => _i('User moved'),
                'message' => _i('User moved successfully'),
                'close' => _i('Close')
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     * Perform transactions
     *
     * @param Request $request
     * @return Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function performTransactions(Request $request, TransactionsRepo $transactionsRepo)
    {
        $this->validate($request, [
            'amount' => 'required|numeric|gt:0'
        ]);
        try {
            $id = auth()->user()->id;
            $currency = session('currency');
            $type = $request->type;
            $user = $request->user;
            $amount = $request->amount;
            $transactionType = $request->transaction_type;
            $ownerAgent = $this->agentsRepo->findByUserIdAndCurrency($id, $currency);
            $transactionID = $transactionsRepo->getNextValue();
            if ($id != $user) {
                if ($transactionType == TransactionTypes::$credit && $amount > $ownerAgent->balance && $ownerAgent->username != 'support') {
                    $data = [
                        'title' => _i('Insufficient balance'),
                        'message' => _i("The agents's operational balance is insufficient to perform the transaction"),
                        'close' => _i('Close')
                    ];
                    return Utils::errorResponse(Codes::$forbidden, $data);
                }

                if ($type == 'user') {
                    $wallet = $request->wallet;

                    $userData = $this->agentsRepo->findUser($user);

                    if ($transactionType == TransactionTypes::$credit) {
                        $uuid = Str::uuid()->toString();
                        $additionalData = [
                            'provider_transaction' => $uuid,
                            'from' => $ownerAgent->username,
                            'to' => $userData->username
                        ];
                        $transaction = Wallet::creditManualTransactions($amount, Providers::$agents_users, $additionalData, $wallet);
                        $ownerBalance = $ownerAgent->balance - $amount;
                    } else {
                        $walletData = Wallet::getByClient($userData->id, $currency);

                        if ($amount > $walletData->data->wallet->balance) {
                            $data = [
                                'title' => _i('Insufficient balance'),
                                'message' => _i("The user's balance is insufficient to perform the transaction"),
                                'close' => _i('Close')
                            ];
                            return Utils::errorResponse(Codes::$forbidden, $data);
                        }

                        $uuid = Str::uuid()->toString();
                        $additionalData = [
                            'provider_transaction' => $uuid,
                            'from' => $userData->username,
                            'to' => $ownerAgent->username
                        ];
                        $transaction = Wallet::debitManualTransactions($amount, Providers::$agents_users, $additionalData, $wallet);
                        $ownerBalance = $ownerAgent->balance + $amount;
                    }
                    $balance = $transaction->data->wallet->balance;
                    $status = $transaction->status;
                    $userAdditionalData = $additionalData;
                    $userAdditionalData['wallet_transaction'] = $transaction->data->transaction->id;

                    $transactionData = [
                        'id' => $transactionID,
                        'user_id' => $user,
                        'amount' => $amount,
                        'currency_iso' => $currency,
                        'transaction_type_id' => $transactionType,
                        'transaction_status_id' => TransactionStatus::$approved,
                        'provider_id' => Providers::$agents_users,
                        'data' => $userAdditionalData,
                        'whitelabel_id' => Configurations::getWhitelabel()
                    ];
                    $ticket = $this->transactionsRepo->store($transactionData, TransactionStatus::$approved, []);

                    $button = sprintf(
                        '<a class="btn u-btn-3d u-btn-blue btn-block" id="ticket" href="%s" target="_blank">%s</a>',
                        route('agents.ticket', [$ticket->id]),
                        _i('Print ticket')
                    );
                } else {
                    $agent = $this->agentsRepo->findByUserIdAndCurrency($user, $currency);
                    $agentBalance = round($agent->balance, 2);

                    if ($transactionType == TransactionTypes::$credit) {
                        $status = Status::$ok;
                        $balance = $agentBalance + $amount;
                        $agentData = [
                            'agent_id' => $agent->agent,
                            'currency_iso' => $currency
                        ];
                        $balanceData = [
                            'balance' => $balance
                        ];
                        if ($agent->username != 'support') {
                            $this->agentCurrenciesRepo->store($agentData, $balanceData);
                        }
                        $ownerBalance = $ownerAgent->balance - $amount;

                        $additionalData = [
                            'from' => $ownerAgent->username,
                            'to' => $agent->username,
                            'balance' => $balance
                        ];
                    } else {
                        if ($amount <= $agentBalance) {
                            $status = Status::$ok;
                            $balance = $agentBalance - $amount;
                            $agentData = [
                                'agent_id' => $agent->agent,
                                'currency_iso' => $currency
                            ];
                            $balanceData = [
                                'balance' => $balance
                            ];
                            if ($agent->username != 'support') {
                                $this->agentCurrenciesRepo->store($agentData, $balanceData);
                            }
                            $ownerBalance = $ownerAgent->balance + $amount;

                            $additionalData = [
                                'from' => $agent->username,
                                'to' => $ownerAgent->username,
                                'balance' => $balance
                            ];
                        } else {
                            $balance = $agentBalance;
                            $status = Status::$failed;
                        }
                    }

                    if ($status == Status::$ok) {
                        $transactionData = [
                            'user_id' => $agent->id,
                            'amount' => $amount,
                            'currency_iso' => $currency,
                            'transaction_type_id' => $transactionType,
                            'transaction_status_id' => TransactionStatus::$approved,
                            'provider_id' => Providers::$agents,
                            'data' => $additionalData,
                            'whitelabel_id' => Configurations::getWhitelabel()
                        ];
                        $ticket = $this->transactionsRepo->store($transactionData, TransactionStatus::$approved, []);

                        $button = sprintf(
                            '<a class="btn u-btn-3d u-btn-blue btn-block" id="ticket" href="%s" target="_blank">%s</a>',
                            route('agents.ticket', [$ticket->id]),
                            _i('Print ticket')
                        );
                    }
                }

                if ($status == Status::$ok) {
                    $agentData = [
                        'agent_id' => $ownerAgent->agent,
                        'currency_iso' => $currency
                    ];
                    $balanceData = [
                        'balance' => $ownerBalance
                    ];
                    if ($ownerAgent->username != 'support') {
                        $this->agentCurrenciesRepo->store($agentData, $balanceData);
                    }

                    if ($type == 'user') {
                        $additionalData['balance'] = $ownerBalance;
                    }

                    if ($ownerAgent->username != 'support') {
                        $additionalData['balance'] = $ownerBalance;
                    } else {
                        $additionalData['balance'] = 0;
                    }

                    $transactionData = [
                        'id' => $transactionID,
                        'user_id' => $id,
                        'amount' => $amount,
                        'currency_iso' => $currency,
                        'transaction_type_id' => $transactionType == TransactionTypes::$credit ? TransactionTypes::$debit : TransactionTypes::$credit,
                        'transaction_status_id' => TransactionStatus::$approved,
                        'provider_id' => Providers::$agents,
                        'data' => $additionalData,
                        'whitelabel_id' => Configurations::getWhitelabel()
                    ];
                    $this->transactionsRepo->store($transactionData, TransactionStatus::$approved, []);

                    $data = [
                        'title' => _i('Transaction performed'),
                        'message' => _i('The transaction was successfully made to the user'),
                        'close' => _i('Close'),
                        'balance' => number_format($balance, 2),
                        'button' => $button
                    ];
                    return Utils::successResponse($data);
                } else {
                    $data = [
                        'title' => _i('Insufficient balance'),
                        'message' => _i("The user's balance is insufficient to perform the transaction"),
                        'close' => _i('Close')
                    ];
                    return Utils::errorResponse(Codes::$forbidden, $data);
                }
            } else {
                $data = [
                    'title' => _i('Error'),
                    'message' => _i('You cannot make transactions to yourself'),
                    'close' => _i('Close')
                ];
                return Utils::errorResponse(Codes::$forbidden, $data);
            }
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     * Store agents
     *
     * @param Request $request
     * @param UsersTempRepo $usersTempRepo
     * @param UserCurrenciesRepo $userCurrenciesRepo
     * @return Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, UsersTempRepo $usersTempRepo, UserCurrenciesRepo $userCurrenciesRepo)
    {

        $this->validate($request, [
            'username' => ['required', new Username()],
            'password' => ['required', new Password()],
            'balance' => 'required',
            'percentage' => 'required|numeric|between:1,99',
            'timezone' => 'required'
        ]);

        try {
            $uuid = Str::uuid()->toString();
            $owner = auth()->user()->id;
            $currency = session('currency');
            $username = strtolower($request->username);
            $password = $request->password;
            $timezone = $request->timezone;
            $balance = $request->balance;
            $master = $request->master;
            $percentage = $request->percentage;
            $currencies = !empty($request->currencies) ? $request->currencies : [$currency];
            $domain = Configurations::getDomain();
            $email = "$username@$domain";
            $uniqueUsername = $this->usersRepo->uniqueUsername($username);
            $uniqueTempUsername = $usersTempRepo->uniqueUsername($username);
            $userExclude = $this->agentsRepo->getExcludeUserProvider($owner);

            if (!is_null($uniqueUsername) || !is_null($uniqueTempUsername)) {
                $data = [
                    'title' => _i('Username in use'),
                    'message' => _i('The indicated username is already in use'),
                    'close' => _i('Close'),
                ];
                return Utils::errorResponse(Codes::$forbidden, $data);
            }

            $ownerAgent = $this->agentsRepo->findByUserIdAndCurrency($owner, $currency);

            if ($balance > $ownerAgent->balance) {
                $data = [
                    'title' => _i('Insufficient balance'),
                    'message' => _i("The agents's operational balance is insufficient to perform the transaction"),
                    'close' => _i('Close')
                ];
                return Utils::errorResponse(Codes::$forbidden, $data);
            }

            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ipForwarded = $_SERVER['HTTP_X_FORWARDED_FOR'];
                $ip = explode(',', $ipForwarded)[0];
            } else {
                $ip = $request->getClientIp();
            }

            $whitelabel = Configurations::getWhitelabel();
            $store = Configurations::getStore()->active;
            $userData = [
                'username' => $username,
                'email' => $email,
                'password' => $password,
                'uuid' => $uuid,
                'ip' => $ip,
                'status' => true,
                'whitelabel_id' => $whitelabel,
                'web_register' => false,
                'register_currency' => $currency,
                'type_user' => $master == 'true' ? TypeUser::$agentMater : TypeUser::$agentCajero,
                'action' => ActionUser::$active,
            ];
            $profileData = [
                'country_iso' => $ownerAgent->country_iso,
                'timezone' => $timezone,
                'level' => 1
            ];
            $user = $this->usersRepo->store($userData, $profileData);
            $this->generateReferenceCode->generateReferenceCode($user->id);

            foreach ($currencies as $key => $agentCurrency) {
                $excludedUser = $this->agentsCollection->formatExcluderProvidersUsers($user->id, $userExclude, $agentCurrency);
                $this->agentsRepo->blockAgents($excludedUser);
                $wallet = Wallet::store($user->id, $user->username, $uuid, $agentCurrency, $whitelabel, session('wallet_access_token'));
                $userData = [
                    'user_id' => $user->id,
                    'currency_iso' => $agentCurrency
                ];
                $walletData = [
                    'wallet_id' => $wallet->data->wallet->id,
                    'default' => $key == 0
                ];
                $userCurrenciesRepo->store($userData, $walletData);
                if ($store) {
                    Store::storeWallet($user->id, $currency);
                }
            }

            $agentData = [
                'user_id' => $user->id,
                'owner_id' => $owner,
                'master' => $master,
                'percentage' => $percentage,
            ];
            $agent = $this->agentsRepo->store($agentData);

            foreach ($currencies as $key => $agentCurrency) {
                if ($currency == $agentCurrency) {
                    $this->createAgentBalance($agent->id, $currency, $balance);
                } else {
                    $this->createAgentBalance($agent->id, $agentCurrency);
                }
            }

            if ($balance > 0 && in_array($currency, $currencies)) {
                $ownerBalance = $ownerAgent->balance - $balance;
                $agentData = [
                    'agent_id' => $ownerAgent->agent,
                    'currency_iso' => $currency
                ];
                $balanceData = [
                    'balance' => $ownerBalance
                ];
                $this->agentCurrenciesRepo->store($agentData, $balanceData);

                $additionalData = [
                    'from' => $ownerAgent->username,
                    'to' => $user->username,
                    'balance' => $ownerBalance
                ];
                $transactionData = [
                    'user_id' => $ownerAgent->id,
                    'amount' => $balance,
                    'currency_iso' => $currency,
                    'transaction_type_id' => TransactionTypes::$debit,
                    'transaction_status_id' => TransactionStatus::$approved,
                    'provider_id' => Providers::$agents,
                    'data' => $additionalData,
                    'whitelabel_id' => Configurations::getWhitelabel()
                ];
                $this->transactionsRepo->store($transactionData, TransactionStatus::$approved, []);
                $auditData = [
                    'ip' => Utils::userIp(),
                    'user_id' => auth()->user()->id,
                    'username' => auth()->user()->username,
                    'transaction_data' => $transactionData
                ];
                //Audits::store($user, AuditTypes::$transaction_debit, Configurations::getWhitelabel(), $auditData);

                $additionalData['balance'] = $balance;
                $transactionData = [
                    'user_id' => $user->id,
                    'amount' => $balance,
                    'currency_iso' => $currency,
                    'transaction_type_id' => TransactionTypes::$credit,
                    'transaction_status_id' => TransactionStatus::$approved,
                    'provider_id' => Providers::$agents,
                    'data' => $additionalData,
                    'whitelabel_id' => Configurations::getWhitelabel()
                ];
                $this->transactionsRepo->store($transactionData, TransactionStatus::$approved, []);
                $auditData = [
                    'ip' => Utils::userIp(),
                    'user_id' => auth()->user()->id,
                    'username' => auth()->user()->username,
                    'transaction_data' => $transactionData
                ];
                //Audits::store($user, AuditTypes::$transaction_credit, Configurations::getWhitelabel(), $auditData);
            } else {
                $ownerBalance = $ownerAgent->balance;
            }

            //Security::assignRole($user->id, 3); //TODO ROL AGENTS DEFAULT
            Security::assignRole($user->id, Roles::$admin_Beet_sweet); //TODO NUEVO ROL OF AGENT

            $data = [
                'title' => _i('Agent created'),
                'message' => _i('Agent created successfully'),
                'close' => _i('Close'),
                'balance' => number_format($ownerBalance, 2),
                'route' => route('agents.index'),
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
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
            'agent_id' => $agent,
            'currency_iso' => $currency,
        ];
        $balance = [
            'balance' => $balance
        ];
        $this->agentCurrenciesRepo->store($agentData, $balance);
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
            $whitelabel = Configurations::getWhitelabel();
            $providerTypes = [ProviderTypes::$casino, ProviderTypes::$live_casino, ProviderTypes::$casino, ProviderTypes::$virtual, ProviderTypes::$sportbook, ProviderTypes::$racebook, ProviderTypes::$live_games, ProviderTypes::$poker];
            $providers = $providersRepo->getByWhitelabelAndTypes($whitelabel, $currency, $providerTypes);
            return Utils::successResponse($providers);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'currency' => $currency]);
            return Utils::failedResponse();
        }
    }

    /**
     * Relocation agents
     *
     * @param int $agent Agent ID
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function relocationAgentsData($agentMoveId = null)
    {
        try {
            if (!is_null($agentMoveId)) {
                $user = auth()->user()->id;
                $currency = session('currency');
                $agent = $this->agentsRepo->findByUserIdAndCurrency($user, $currency);
                $whitelabel = Configurations::getWhitelabel();

                $agents = $this->agentsRepo->getSearchAgentsByOwner($currency, $user, $whitelabel);

                $selectUsers = $this->agentsCollection->formatRelocationAgents($agent, $agents, $currency, $agentMoveId);
            } else {
                $selectUsers = [];
            }

            $data = [
                'agents' => $selectUsers
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'agent' => $agent]);
            return Utils::failedResponse();
        }
    }

    /**
     * Search username
     *
     * @param Request $request
     * @return Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function searchAgent(Request $request)
    {
        try {
            $user = auth()->user()->id;
            $currency = session('currency');
            $whitelabel = Configurations::getWhitelabel();
            $name = strtolower($request->user);
            $agents = $this->agentsRepo->getSearchAgentsByOwner($currency, $user, $whitelabel);
            $status = false;
            $selectUsers = $this->agentsCollection->dependencySelect($name, $agents, [], $whitelabel, $status);

            $data = [
                'agents' => $selectUsers
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     * Search username
     *
     * @param Request $request
     * @return Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function searchUsername(Request $request)
    {
        try {
            $user = auth()->user()->id;
            $currency = session('currency');
            $agent = $this->agentsRepo->findByUserIdAndCurrency($user, $currency);
            $whitelabel = Configurations::getWhitelabel();
            $name = strtolower($request->user);

            $agents = $this->agentsRepo->getSearchAgentsByOwner($currency, $user, $whitelabel);
            $users = $this->agentsRepo->getSearchUsersByAgent($currency, $agent->agent, $whitelabel);
            $status = true;
            $selectUsers = $this->agentsCollection->dependencySelect($name, $agents, $users, $whitelabel, $status);

            $data = [
                'agents' => $selectUsers
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     * Store main agents
     *
     * @param Request $request
     * @return Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function storeMainAgents(Request $request)
    {
        $this->validate($request, [
            'whitelabel' => 'required'
        ]);
        try {
            $whitelabel = $request->whitelabel;
            $admin = 'admin';
            $support = 'support';
            $supportAgent = null;
            $adminAgent = null;
            $supportUser = $this->usersRepo->getByUsername($support, $whitelabel);
            $adminUser = $this->usersRepo->getByUsername($admin, $whitelabel);

            if (is_null($supportUser)) {
                $data = [
                    'title' => _i('User %s does not exist', [$support]),
                    'message' => _i('The %s user has not yet been created. Please create it first', [$support]),
                    'close' => _i('Close')
                ];
                return Utils::errorResponse(Codes::$forbidden, $data);
            }

            if (is_null($adminUser)) {
                $data = [
                    'title' => _i('User %s does not exist', [$admin]),
                    'message' => _i('The %s user has not yet been created. Please create it first', [$admin]),
                    'close' => _i('Close')
                ];
                return Utils::errorResponse(Codes::$forbidden, $data);
            }

            $supportAgent = $this->agentsRepo->existAgent($supportUser->id);
            $adminAgent = $this->agentsRepo->existAgent($adminUser->id);
            $currencies = Configurations::getCurrenciesByWhitelabel($whitelabel);

            if (is_null($supportAgent)) {
                $supportAgentData = [
                    'user_id' => $supportUser->id,
                    'master' => true
                ];
                $supportAgent = $this->agentsRepo->store($supportAgentData);
                $auditData = [
                    'ip' => Utils::userIp(),
                    'user_id' => auth()->user()->id,
                    'username' => auth()->user()->username,
                    'agents_data' => [
                        'type' => $support,
                        'data' => $supportAgentData
                    ]
                ];
                //Audits::store($user, AuditTypes::$agent_creation, Configurations::getWhitelabel(), $auditData);
            }

            if (is_null($adminAgent)) {
                $adminAgentData = [
                    'user_id' => $adminUser->id,
                    'owner_id' => $supportUser->id,
                    'master' => true
                ];
                $adminAgent = $this->agentsRepo->store($adminAgentData);
                $auditData = [
                    'ip' => Utils::userIp(),
                    'user_id' => auth()->user()->id,
                    'username' => auth()->user()->username,
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

                if (!is_null($supportAgent)) {
                    $supportAgentCurrencyData = [
                        'agent_id' => $supportAgent->id,
                        'currency_iso' => $currency,
                    ];
                    $this->agentCurrenciesRepo->store($supportAgentCurrencyData, $balance);
                }

                if (!is_null($adminAgent)) {
                    $adminAgentCurrencyData = [
                        'agent_id' => $adminAgent->id,
                        'currency_iso' => $currency,
                    ];
                    $this->agentCurrenciesRepo->store($adminAgentCurrencyData, $balance);
                }
            }
            $data = [
                'title' => _i('Agents created'),
                'message' => _i('Agents were created successfully'),
                'close' => _i('Close'),
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
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
     * @throws \Illuminate\Validation\ValidationException
     */
    public function storeUser(Request $request, UsersTempRepo $usersTempRepo, UserCurrenciesRepo $userCurrenciesRepo)
    {
        $rules = [
            'username' => ['required', new Username()],
            'password' => ['required', new Password()],
            'balance' => 'required',
            'country' => 'required',
            'timezone' => 'required'
        ];
        if (!is_null($request->email)) {
            $rules['email'] = ['required', new Email()];
        }

        $this->validate($request, $rules);

        try {
            $uuid = Str::uuid()->toString();
            $owner = auth()->user()->id;
            $currency = session('currency');
            $username = strtolower($request->username);
            $password = $request->password;
            $email = $request->email;
            $balance = $request->balance;
            $country = $request->country;
            $timezone = $request->timezone;
            $uniqueUsername = $this->usersRepo->uniqueUsername($username);
            $uniqueTempUsername = $usersTempRepo->uniqueUsername($username);
            $userExclude = $this->agentsRepo->getExcludeUserProvider($owner);

            if (!is_null($uniqueUsername) || !is_null($uniqueTempUsername)) {
                $data = [
                    'title' => _i('Username in use'),
                    'message' => _i('The indicated username is already in use'),
                    'close' => _i('Close'),
                ];
                return Utils::errorResponse(Codes::$forbidden, $data);
            }

            if (is_null($email)) {
                $domain = strtolower($_SERVER['HTTP_HOST']);
                $domain = str_replace('www.', '', $domain);
                $email = "$username@$domain";
            } else {
                $uniqueEmail = $this->usersRepo->uniqueEmail($email);
                $uniqueTempEmail = $usersTempRepo->uniqueEmail($email);

                if (!is_null($uniqueEmail) || !is_null($uniqueTempEmail)) {
                    $data = [
                        'title' => _i('Email in use'),
                        'message' => _i('The indicated email is already in use'),
                        'close' => _i('Close'),
                    ];
                    return Utils::errorResponse(Codes::$forbidden, $data);
                }
            }

            $ownerAgent = $this->agentsRepo->findByUserIdAndCurrency($owner, $currency);

            if ($balance > $ownerAgent->balance) {
                $data = [
                    'title' => _i('Insufficient balance'),
                    'message' => _i("The agents's operational balance is insufficient to perform the transaction"),
                    'close' => _i('Close')
                ];
                return Utils::errorResponse(Codes::$forbidden, $data);
            }

            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ipForwarded = $_SERVER['HTTP_X_FORWARDED_FOR'];
                $ip = explode(',', $ipForwarded)[0];
            } else {
                $ip = $request->getClientIp();
            }

            $whitelabel = Configurations::getWhitelabel();
            $userData = [
                'username' => $username,
                'email' => $email,
                'password' => $password,
                'uuid' => $uuid,
                'ip' => $ip,
                'status' => true,
                'whitelabel_id' => $whitelabel,
                'web_register' => false,
                'register_currency' => $currency,
                'type_user' => TypeUser::$player,
                'action' => ActionUser::$active,
            ];
            $profileData = [
                'country_iso' => $country,
                'timezone' => $timezone,
                'level' => 1
            ];
            $user = $this->usersRepo->store($userData, $profileData);
            $auditData = [
                'ip' => Utils::userIp(),
                'user_id' => auth()->user()->id,
                'username' => auth()->user()->username,
                'player_data' => $userData,
                'profile_data' => $profileData
            ];
            //Audits::store($user, AuditTypes::$player_creation, Configurations::getWhitelabel(), $auditData);
            $excludedUser = $this->agentsCollection->formatExcluderProvidersUsers($user->id, $userExclude, $currency);
            $this->agentsRepo->blockAgents($excludedUser);
            $wallet = Wallet::store($user->id, $user->username, $uuid, $currency, $whitelabel, session('wallet_access_token'));
            $this->generateReferenceCode->generateReferenceCode($user->id);
            $userData = [
                'user_id' => $user->id,
                'currency_iso' => $currency
            ];
            $walletData = [
                'wallet_id' => $wallet->data->wallet->id,
                'default' => true
            ];
            $userCurrenciesRepo->store($userData, $walletData);
            $this->agentsRepo->addUser($ownerAgent->agent, $user->id);
            $store = Configurations::getStore()->active;
            if ($store) {
                Store::storeWallet($user->id, $currency);
            }

            if ($balance > 0) {
                $providerTransaction = Str::uuid()->toString();
                $additionalData = [
                    'provider_transaction' => $providerTransaction,
                    'from' => $ownerAgent->username,
                    'to' => $user->username
                ];
                Wallet::creditManualTransactions($balance, Providers::$agents, $additionalData, $wallet->data->wallet->id);

                $ownerBalance = $ownerAgent->balance - $balance;
                $agentData = [
                    'agent_id' => $ownerAgent->agent,
                    'currency_iso' => $currency
                ];
                $balanceData = [
                    'balance' => $ownerBalance
                ];
                $this->agentCurrenciesRepo->store($agentData, $balanceData);

                $additionalData['balance'] = $ownerBalance;
                $transactionData = [
                    'user_id' => $ownerAgent->id,
                    'amount' => $balance,
                    'currency_iso' => $currency,
                    'transaction_type_id' => TransactionTypes::$debit,
                    'transaction_status_id' => TransactionStatus::$approved,
                    'provider_id' => Providers::$agents,
                    'data' => $additionalData,
                    'whitelabel_id' => Configurations::getWhitelabel()
                ];
                $this->transactionsRepo->store($transactionData, TransactionStatus::$approved, []);
            } else {
                $ownerBalance = $ownerAgent->balance;
            }

            $data = [
                'title' => _i('Player created'),
                'message' => _i('Player created successfully'),
                'close' => _i('Close'),
                'balance' => number_format($ownerBalance, 2),
                'route' => route('agents.index')
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
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
            $agentId = $request->agent_id;
            $this->agentsRepo->update($agentId, $agentData);

            $data = [
                'title' => _i('Percentage updated'),
                'message' => _i('Percentage of agent successfully updated'),
                'close' => _i('Close'),
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
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
            $agent = $this->agentsRepo->findByUserIdAndCurrency($user, $currency);
            $users = $this->agentsRepo->getUsersByAgent($agent->agent, $currency);
            $this->agentsCollection->formatUsers($users, $currency);
            $data = [
                'users' => $users
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
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
            $agent = $this->agentsRepo->findByUserIdAndCurrency($user, $currency);
            $users = $this->agentsRepo->getUsersByAgent($agent->agent, $currency);
            $usersIds = [];

            foreach ($users as $user) {
                $usersIds[] = $user->id;
            }

            if (!empty($usersIds)) {
                $wallets = Wallet::getUsersBalancesByIds($usersIds, $currency);
                $walletsData = $wallets->data->wallets;
                $usersData = $this->agentsCollection->usersBalances($walletsData);
            } else {
                $usersData = [
                    'users' => [],
                    'total_balances' => '0.00'
                ];
            }
            return Utils::successResponse($usersData);
        } catch (\Exception $ex) {
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

}

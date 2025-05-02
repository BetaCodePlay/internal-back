<?php

namespace App\Http\Controllers;

use App\Agents\Repositories\AgentsRepo;
use App\Audits\Enums\AuditTypes;
use App\Audits\Repositories\AuditsRepo;
use App\BetPay\Collections\AccountsCollection;
use App\BonusSystem\Repositories\CampaignsRepo;
use App\Core\Core;
use App\Core\Repositories\CountriesRepo;
use App\Core\Repositories\CurrenciesRepo;
use App\Core\Repositories\GamesRepo;
use App\Core\Repositories\ProvidersRepo;
use App\Core\Repositories\ProvidersTypesRepo;
use App\Core\Repositories\TransactionsRepo;
use App\CRM\Collections\SegmentsCollection;
use App\CRM\Repositories\SegmentsRepo;
use App\Reports\Collections\ReportsCollection;
use App\Reports\Repositories\ClosuresUsersTotalsRepo;
use App\Users\Collections\UsersCollection;
use App\Users\Enums\ActionUser;
use App\Users\Enums\DocumentStatus;
use App\Users\Enums\TypeUser;
use App\Users\Import\TransactionsByLotImport;
use App\Users\Mailers\Activate;
use App\Users\Mailers\Users;
use App\Users\Mailers\Validate;
use App\Users\Repositories\AutoLockUsersRepo;
use App\Users\Repositories\ProfilesRepo;
use App\Users\Repositories\UserCurrenciesRepo;
use App\Users\Repositories\UserDocumentsRepo;
use App\Users\Repositories\UsersRepo;
use App\Users\Repositories\UsersTempRepo;
use App\Users\Rules\Age;
use App\Users\Rules\DNI;
use App\Users\Rules\Email;
use App\Users\Rules\Password;
use App\Users\Rules\Username;
use App\Wallets\Collections\WalletsCollection;
use App\Whitelabels\Repositories\OperationalBalancesRepo;
use App\Whitelabels\Repositories\OperationalBalancesTransactionsRepo;
use App\Whitelabels\Repositories\WhitelabelsRepo;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Dotworkers\Audits\Audits;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Configurations\Enums\Codes;
use Dotworkers\Configurations\Enums\Components;
use Dotworkers\Configurations\Enums\EmailTypes;
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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Ixudra\Curl\Facades\Curl;
use Jenssegers\Agent\Agent;
use Symfony\Component\HttpFoundation\Response;
use Exception;

/**
 * Class UsersController
 *
 * This class allows to manage users requests
 *
 * @package App\Http\Controllers
 * @author  Eborio Linarez
 * @author  Genesis Perez
 */
class UsersController extends Controller
{
    /**
     * UsersRepo
     *
     * @var UsersRepo
     */
    private $usersRepo;

    /**
     * UsersCollection
     *
     * @var UsersCollection
     */
    private $usersCollection;

    /**
     * ProfilesRepo
     *
     * @var ProfilesRepo
     */
    private $profilesRepo;

    /**
     * CountriesRepo
     *
     * @var CountriesRepo
     */
    private $countriesRepo;

    /**
     * GamesRepo
     *
     * @var GamesRepo
     */
    private $gamesRepo;

    /**
     * @var UsersTempRepo
     */
    private $usersTempRepo;

    /**
     * BetPay server URL
     *
     * @var string
     */
    private $betPayURL;

    /**
     * Closures users totals
     *
     * @var ClosuresUsersTotalsRepo
     */
    private $closuresUsersTotalsRepo;

    /**
     * @var AgentsRepo
     */
    private $agentsRepo;

    /**
     * @var AuditsRepo
     */
    private $auditsRepo;

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
     * ProvidersRepo
     *
     * @var ProvidersRepo
     */
    private $providersRepo;

     /**
     * ProvidersTypesRepo
     *
     * @var ProvidersTypesRepo
     */
    private $providersTypesRepo;

    /**
     * ReportsCollection
     *
     * @var ReportsCollection
     */
    private $reportsCollection;

    /**
     * UserDocumentsRepo
     *
     * @var $userDocumentsRepo
     */
    private $userDocumentsRepo;

    /**
     * SegmentsRepo
     *
     * @var $segmentsRepo
     */
    private $segmentsRepo;

    /**
     * SegmentsCollection
     *
     * @var $segmentsCollection
     */
    private $segmentsCollection;

    /**
     * FilePath
     *
     * @var string
     */
    private $filePath;

    /**
     * AutoLockUsersRepo
     *
     * @var $autoLockUsersRepo
     */
    private $autoLockUsersRepo;

    /**
     * UsersController constructor.
     *
     * @param UsersRepo $usersRepo
     * @param UsersCollection $usersCollection
     * @param ProfilesRepo $profilesRepo
     * @param CountriesRepo $countriesRepo
     * @param GamesRepo $GamesRepo
     * @param UsersTempRepo $usersTempRepo
     * @param ClosuresUsersTotalsRepo $closuresUsersTotalsRepo
     * @param ReportsCollection $reportsCollection
     *
     * @param AgentsRepo $agentsRepo
     * @param AuditsRepo $auditsRepo
     * @param WhitelabelsRepo $whitelabelsRepo
     * @param CurrenciesRepo $currenciesRepo
     * @param ProvidersRepo $providersRepo
     * @param ProvidersTypesRepo $providersTypesRepo
     * @param UserDocumentsRepo $userDocumentsRepo
     * @param SegmentsRepo $segmentsRepo
     * @param SegmentsCollection $segmentsCollection
     * @param AutoLockUsersRepo $autoLockUsersRepo
     */
    public function __construct(
        UsersRepo $usersRepo,
        UsersCollection $usersCollection,
        ProfilesRepo $profilesRepo,
        CountriesRepo $countriesRepo,
        GamesRepo $gamesRepo,
        UsersTempRepo $usersTempRepo,
        ClosuresUsersTotalsRepo $closuresUsersTotalsRepo,
        AgentsRepo $agentsRepo,
        AuditsRepo $auditsRepo,
        WhitelabelsRepo $whitelabelsRepo,
        CurrenciesRepo $currenciesRepo,
        ProvidersRepo $providersRepo,
        ProvidersTypesRepo $providersTypesRepo,
        ReportsCollection $reportsCollection,
        UserDocumentsRepo $userDocumentsRepo,
        SegmentsRepo $segmentsRepo,
        SegmentsCollection $segmentsCollection,
        AutoLockUsersRepo $autoLockUsersRepo
    ) {
        $this->usersRepo = $usersRepo;
        $this->usersCollection = $usersCollection;
        $this->reportsCollection = $reportsCollection;
        $this->profilesRepo = $profilesRepo;
        $this->countriesRepo = $countriesRepo;
        $this->gamesRepo = $gamesRepo;
        $this->usersTempRepo = $usersTempRepo;
        $this->betPayURL = env('BETPAY_SERVER') . '/api';
        $this->closuresUsersTotalsRepo = $closuresUsersTotalsRepo;

        $this->agentsRepo = $agentsRepo;
        $this->auditsRepo = $auditsRepo;
        $this->whitelabelsRepo = $whitelabelsRepo;
        $this->currenciesRepo = $currenciesRepo;
        $this->providersRepo = $providersRepo;
        $this->providersTypesRepo = $providersTypesRepo;
        $this->userDocumentsRepo = $userDocumentsRepo;
        $this->segmentsRepo = $segmentsRepo;
        $this->segmentsCollection = $segmentsCollection;
        $s3Directory = Configurations::getS3Directory();
        $this->filePath = "$s3Directory/documents/";
        $this->autoLockUsersRepo = $autoLockUsersRepo;
    }

    /**
     * Activation users temp
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function activationTemp(Request $request)
    {
        $activateURL = null;
        $curl = null;

        try {
            $username = $request->user_name;
            $whitelabel = Configurations::getWhitelabel();
            $userTemp = $this->usersTempRepo->getUserByUsername($whitelabel, $username);
            $domain = Configurations::getDomain();
            $activateURL = "https://$domain/api/users/activate/$userTemp->token";
            $curl = Curl::to($activateURL)
                ->get();
            $response = json_decode($curl);
            if ($response->status == Status::$ok) {
                $data = [
                    'users' => [],
                    'title' => _i('Account activate'),
                    'message' => _i('The account was successfully activate'),
                    'close' => _i('Close')
                ];
                return Utils::successResponse($data);
            } else {
                return Utils::failedResponse();
            }

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all(), 'url' => $activateURL, 'curl' => $curl]);
            return Utils::failedResponse();
        }
    }

    /**
     * Advanced search data
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function advancedSearchData(Request $request)
    {
        $id = $request->id;
        $wallet = $request->wallet;
        $email = strtolower($request->email);
        $validationRules = [];

        if (!empty($id)) {
            $validationRules['id'] = 'integer';
        }
        if (!empty($wallet)) {
            $validationRules['wallet'] = 'integer';
        }
        if (!empty($email)) {
            $validationRules['email'] = 'email';
        }
        $this->validate($request, $validationRules);

        try {
            $username = strtolower($request->username);
            $dni = strtolower($request->dni);
            $firstName = strtolower($request->first_name);
            $lastName = strtolower($request->last_name);
            $gender = $request->gender;
            $level = $request->level;
            $phone = $request->phone;
            $referralCode = strtoupper($request->code);

            if (empty($id) && empty($username) && empty($dni) && empty($email) && empty($firstName) && empty($lastName) && empty($gender) && empty($level) && empty($phone) && empty($wallet) && empty($referralCode)) {
                $data = [
                    'users' => []
                ];
            } else {
                $user = Auth::user()->id;
                if (Auth::user()->username == 'romeo') {
                    $userTmp = $this->usersRepo->findUserCurrencyByWhitelabel('wolf', session('currency'), Configurations::getWhitelabel());
                    $user = isset($userTmp[0]->id) ? $userTmp[0]->id : Auth::user()->id;
                }

                $idChildren = array_column($this->agentsRepo->getTreeSqlLevels($user,session('currency'),Configurations::getWhitelabel()),'id');

                $users = $this->usersRepo->advancedSearchTree($id, $username, $dni, $email, $firstName, $lastName, $gender, $level, $phone, $wallet, $referralCode, $idChildren);
                $this->usersCollection->formatSearch($users);

                $data = [
                    'users' => $users
                ];

            }
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     * Show advanced search
     *
     * @return Factory|View
     */
    public function advancedSearch()
    {
        try {
            $levels = Configurations::getLevels();
            $data['levels'] = $levels;
            $data['title'] = _i('Advanced user search');
            return view('back.users.advanced-search', $data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    /**
     * Audit Users
     *
     * @param Request $request
     * @param Agent $agent
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function auditUsers(Request $request, Agent $agent)
    {
        try {
            $user = $request->user;
            if ($agent->isMobile() || $agent->isPhone() || $agent->isTablet()) {
                $mobile = true;

            } else {
                $mobile = false;
            }
            $auditData = [
                'ip' => Utils::userIp($request),
                'user_id' => auth()->user()->id,
                'username' => auth()->user()->username,
                'mobile' => $mobile
            ];
            Audits::store($user, AuditTypes::$support_login, Configurations::getWhitelabel(), $auditData);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     * Store user
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, UsersTempRepo $usersTempRepo, UserCurrenciesRepo $userCurrenciesRepo)
    {
        $rules = [
            'username' => ['required', new Username()],
            'password' => ['required', new Password()],
            'country' => 'required',
            'timezone' => 'required',
            'currency' => 'required'
        ];

        $this->validate($request, $rules);

        try {
            $uuid = Str::uuid()->toString();
            $username = strtolower($request->username);
            $email = strtolower($request->email);
            if (is_null($email) || empty($email)) {
                $domain = strtolower($_SERVER['HTTP_HOST']);
                $domain = str_replace('www.', '', $domain);
                $email = "$username@$domain";
                $email = strtolower($email);
            }

            $currency = $request->get('currency', session()->get('currency'));
            $uniqueUsername = $this->usersRepo->uniqueUsername($username);
            $uniqueEmail = $this->usersRepo->uniqueEmail($email);
            $uniqueTempUsername = $usersTempRepo->uniqueUsername($username);
            $uniqueTempEmail = $usersTempRepo->uniqueEmail($email);

            if ($request->tester) {
                $tester = true;
            } else {
                $tester = false;
            }

            if (!is_null($uniqueUsername) || !is_null($uniqueTempUsername)) {
                $data = [
                    'title' => _i('Username in use'),
                    'message' => _i('The indicated username is already in use'),
                    'close' => _i('Close'),
                ];
                return Utils::errorResponse(Codes::$forbidden, $data);
            }

            if (!is_null($uniqueEmail) || !is_null($uniqueTempEmail)) {
                $data = [
                    'title' => _i('Email in use'),
                    'message' => _i('The indicated email is already in use'),
                    'close' => _i('Close'),
                ];
                return Utils::errorResponse(Codes::$forbidden, $data);
            }

            $ip = Utils::userIp($request);
            $whitelabel = Configurations::getWhitelabel();
            $userData = [
                'username' => $username,
                'email' => $email,
                'password' => $request->password,
                'tester' => $tester,
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
                'country_iso' => $request->get('country', session()->get('country_iso')),
                'timezone' => $request->get('timezone', session()->get('timezone')),
                'level' => 1
            ];
            $user = $this->usersRepo->store($userData, $profileData);
            $auditData = [
                'ip' => $ip,
                'user_id' => auth()->user()->id,
                'username' => auth()->user()->username,
                'currency_iso' => $currency,
                'user_data' => $userData,
                'profile_data' => $profileData
            ];
            Audits::store($user->id, AuditTypes::$user_creation, Configurations::getWhitelabel(), $auditData);

            $wallet = Wallet::store($user->id, $user->username, $uuid, $currency, $whitelabel, session('wallet_access_token'));
            Configurations::generateReferenceCode($user->id);
            $userData = [
                'user_id' => $user->id,
                'currency_iso' => $currency
            ];
            $walletData = [
                'wallet_id' => $wallet->data->wallet->id,
                'default' => true
            ];
            $userCurrenciesRepo->store($userData, $walletData);
            if (Configurations::getStore()->active) {
                Store::storeWallet($user->id, $currency);
            }

            $data = [
                'title' => _i('User created'),
                'message' => _i('User created successfully'),
                'close' => _i('Close')
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     * Get autoLocked users data
     *
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function autoLockedUsersData(Request $request, $startDate = null, $endDate = null)
    {
        try {
            if (!is_null($startDate) && !is_null($endDate)) {
                $startDate = Utils::startOfDayUtc($startDate);
                $endDate = Utils::endOfDayUtc($endDate);
                $month = $request->month;
                $whitelabel = Configurations::getWhitelabel();
                $users = $this->autoLockUsersRepo->autoLockedUsersTotals($whitelabel, $startDate, $endDate, $month);
            } else {
                $users = [];
            }
            $data = $this->usersCollection->autoLockedUsers($users);
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all(), 'start_date' => $startDate, 'end_date' => $endDate]);
            return Utils::failedResponse();
        }
    }

    /***
     * Show view autoLocked users
     *
     * @return Factory|View
     */
    public function autoLockedUsers()
    {
        $data['title'] = _i('Autolocked users');
        return view('back.users.autolocked-users', $data);
    }

    /**
     * @param $userId
     * @param $lockType
     * @param $fake
     * @param $description
     * @return Response
     */
    public function blockAgent($userId, $lockType, $fake, $description = null)
    {
        try {
            $rules = [
                'user_id'     => ['required', 'exists:users,id'],
                'lock_type'   => ['required', 'integer'],
                'description' => ['required'],
            ];

            $validator = Validator::make([
                'user_id'     => $userId,
                'lock_type'   => $lockType,
                'description' => $description
            ], $rules, $this->custom_message());

            if ($validator->fails()) {
                $response = [
                    'title'   => __('Wrong Parameters'),
                    'message' => __('You need to fill in all the required fields'),
                    'data'    => $validator->errors()->getMessages(),
                    'close'   => _i('Close'),
                    'type'    => 'info'
                ];

                return Utils::errorResponse(Codes::$forbidden, $response);
            }

            $statusUpdate = false;
            $data         = [];
            $type         = $lockType;
            if ($type == ActionUser::$locked_higher) {
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
                    $father = $this->usersCollection->treeFatherValidate($typeAudit->data->user_id, Auth::id());
                    if ($typeAudit->data->user_id == Auth::id()) {
                        $father = true;
                    }
                }

                if ($type == ActionUser::$active && $father) {
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
                    'user_id'     => auth()->user()->id,
                    'username'    => auth()->user()->username,
                    'new_action'  => $type,
                    'description' => $description
                ]);

                return Utils::successResponse([
                    'title'   => _i('Status updated'),
                    'message' => _i('User status was updated successfully'),
                    'close'   => _i('Close'),
                    'type'    => $fake
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

    /**
     * Perform bonus transactions
     *
     * @param Request $request
     * @param TransactionsRepo $transactionsRepo
     * @param OperationalBalancesRepo $operationalBalanceRepo
     * @param OperationalBalancesTransactionsRepo $operationalBalanceTransactionsRepo
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function bonusTransactions(Request $request, TransactionsRepo $transactionsRepo, OperationalBalancesRepo $operationalBalanceRepo, OperationalBalancesTransactionsRepo $operationalBalanceTransactionsRepo)
    {
        $this->validate($request, [
            'amount' => 'required|numeric|gt:0',
            'allocation_criteria' => 'required',
            'description' => 'required'
        ]);

        try {

            $user = $request->user;
            $wallet = $request->wallet;
            $amount = $request->amount;
            $description = $request->description;
            $allocationCriteria = $request->allocation_criteria;
            $operator = auth()->user()->username;
            $whitelabel = Configurations::getWhitelabel();
            $currency = session('currency');
            $operationalBalanceData = $operationalBalanceRepo->find($whitelabel, $currency);
            $provider = Providers::$bonus;
            $transactionID = $transactionsRepo->getNextValue();

            if (!is_null($operationalBalanceData) && $amount > $operationalBalanceData->balance) {
                $operationalBalance = number_format($operationalBalanceData->balance, 2);
                //new TransactionNotAllowed($amount, $user, $provider, TransactionTypes::$credit);
                $data = [
                    'title' => _i('Transaction not allowed'),
                    'message' => _i('The amount is higher than the operational balance. Available: %s %s', [$currency, $operationalBalance]),
                    'close' => _i('Close')
                ];
                return Utils::errorResponse(Codes::$forbidden, $data);

            } else {
                $uuid = Str::uuid()->toString();
                $additionalData = [
                    'provider_transaction' => $uuid,
                    'description' => $description,
                    'operator' => $operator,
                    'allocation_criteria' => $allocationCriteria
                ];
                $transaction = Wallet::creditManualTransactions($amount, $provider, $additionalData, $wallet);
                $transactionType = TransactionTypes::$credit;
                $transactionData = [
                    'id' => $transactionID,
                    'user_id' => $user,
                    'amount' => $amount,
                    'currency_iso' => $currency,
                    'transaction_type_id' => $transactionType,
                    'transaction_status_id' => TransactionStatus::$approved,
                    'provider_id' => Providers::$bonus,
                    'data' => $additionalData,
                    'whitelabel_id' => Configurations::getWhitelabel()
                ];
                // dd($transaction);
                $additionalData['wallet_transaction'] = $transaction->data->transaction->id;
                $detailsData = [
                    'data' => json_encode($additionalData)
                ];
                $transactionsRepo->store($transactionData, TransactionStatus::$approved, $detailsData);

                $operationalBalanceTransaction = [
                    'amount' => $amount,
                    'user_id' => $user,
                    'operator' => $operator,
                    'provider_id' => $provider,
                    'whitelabel_id' => $whitelabel,
                    'currency_iso' => $currency,
                    'transaction_type_id' => $transactionType,
                ];
                $operationalBalanceTransactionsRepo->store($operationalBalanceTransaction);
                $operationalBalanceRepo->decrement($whitelabel, $currency, $amount);

                $auditData = [
                    'ip' => Utils::userIp($request),
                    'user_id' => auth()->user()->id,
                    'username' => $operator,
                    'transaction' => [
                        'amount' => $amount,
                        'currency' => $currency,
                        'transaction_type_id' => $transactionType,
                        'description' => $description
                    ]
                ];
                Audits::store($user, AuditTypes::$bonus_transactions, Configurations::getWhitelabel(), $auditData);
                if ($transactionType == TransactionTypes::$credit) {
                    $userDate = [
                        'last_deposit' => Carbon::now(),
                        'last_deposit_amount' => $amount,
                        'last_deposit_currency' => $currency
                    ];
                } else {
                    $userDate = [
                        'last_debit' => Carbon::now(),
                        'last_debit_amount' => $amount,
                        'last_debit_currency' => $currency

                    ];
                }
                $this->usersRepo->update($user, $userDate);

                $data = [
                    'title' => _i('Transaction performed'),
                    'message' => _i('The transaction was successfully made to the user'),
                    'close' => _i('Close'),
                    'balance' => number_format($transaction->data->wallet->balance, 2)
                ];
                return Utils::successResponse($data);
            }
        } catch (\Exception $ex) {
            // dd($ex);
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /***
     * Change Email Agent
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     */
    public function changeEmailAgent(Request $request, $user, $action, $type, $description)
    {
        if (is_null($description)) {
            $data = [
                'title' => _i('The given data was invalid'),
                'message' => _i('The description field is required.'),
                'close' => _i('Close')
            ];
            return Utils::errorResponse(Codes::$forbidden, $data);
        } else {
            $newAction = ActionUser::$active;
                $userData = [
                    'action' => $newAction
                ];
                $this->usersRepo->update($user, $userData);
                if (!$newAction) {
                    Sessions::deleteByUser($user);
                }
                $autoLockUsersRepo = new AutoLockUsersRepo();
                $unlockUser = $autoLockUsersRepo->unlockUser($user);
                if ($newAction === ActionUser::$active && !is_null($unlockUser)) {
                    $autoLockUsersRepo->deleteAutoLockUser($unlockUser->id);
                }

                $auditData = [
                    'ip' => Utils::userIp($request),
                    'user_id' => auth()->user()->id,
                    'username' => auth()->user()->username,
                    'old_action' => $action,
                    'new_action' => $newAction,
                    'description' => $description
                ];

                Audits::store($user, AuditTypes::$user_modification, Configurations::getWhitelabel(), $auditData);
                $data = [
                    'title' => _i('Status changed'),
                    'message' => _i('User status was changed successfully'),
                    'close' => _i('Close'),
                    'action' => $newAction,
                    'type' => $type
                ];
                return Utils::successResponse($data);
        }
    }

    /**
     * Change user status
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function changeStatus(Request $request, $user, $status, $type, $description)
    {
        try {
            if (is_null($description)) {
                $data = [
                    'title' => _i('The given data was invalid'),
                    'message' => _i('The description field is required.'),
                    'close' => _i('Close')
                ];
                return Utils::errorResponse(Codes::$forbidden, $data);
            } else {
                $newStatus = (bool) !$status;
                $userData = [
                    'status' => $newStatus
                ];
                $this->usersRepo->update($user, $userData);
                if (!$newStatus) {
                    Sessions::deleteByUser($user);
                }
                $autoLockUsersRepo = new AutoLockUsersRepo();
                $unlockUser = $autoLockUsersRepo->unlockUser($user);
                if ($newStatus == true && !is_null($unlockUser)) {
                    $autoLockUsersRepo->deleteAutoLockUser($unlockUser->id);
                }

                $auditData = [
                    'ip' => Utils::userIp($request),
                    'user_id' => auth()->user()->id,
                    'username' => auth()->user()->username,
                    'old_status' => $status,
                    'new_status' => $newStatus,
                    'description' => $description
                ];

                Audits::store($user, AuditTypes::$user_status, Configurations::getWhitelabel(), $auditData);
                $data = [
                    'title' => _i('Status updated'),
                    'message' => _i('User status was updated successfully'),
                    'close' => _i('Close'),
                    'status' => $newStatus,
                    'type' => $type
                ];
                return Utils::successResponse($data);
            }
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'user' => $user, 'status' => $status, 'description' => $description]);
            return Utils::failedResponse();
        }
    }

    /**
     * Get completed profiles
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function completedProfiles()
    {
        try {
            $users = $this->usersRepo->getWithCompletedProfiles();
            $data = [
                'users' => number_format($users)
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /** Custom Message Validator
     * @return array
     */
    public function custom_message()
    {
        return [
            'description.required' => __('Description is required'),
            'lock_type.required' => __('Lock type is required'),
            'user_id.required' => __('ID user is required'),
            'user_id.exists' => __('ID user does not exist'),
        ];
    }

    /**
     * Dashboard graphic
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function dashboardGraphic()
    {
        $months = 6;
        $timezone = session('timezone');
        $endDate = Carbon::now()->setTimezone($timezone);
        $startDate = Carbon::now()->setTimezone($timezone)->subMonth($months);
        $newStartDate = $startDate->copy()->format('Y-m-d');
        $newEndDate = $endDate->copy()->format('Y-m-d');
        $period = CarbonPeriod::create($newStartDate, $newEndDate);
        $whitelabel = Configurations::getWhitelabel();
        $usersData = $this->usersRepo->getTotalRegisteredUsers($whitelabel, $startDate, $endDate);
        $users = $this->usersCollection->formatRegisteredGraphic($period, $usersData);
        return response()->json($users);
    }

    /**
     * Create users
     *
     * @return Factory|View
     */
    public function create()
    {
        try {
            $countries = $this->countriesRepo->all();
            $data['countries'] = $countries;
            $data['timezones'] = \DateTimeZone::listIdentifiers();
            $data['currencies'] = Configurations::getCurrencies();
            $data['title'] = _i('Create new user');
            return view('back.users.create', $data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    /**
     * Dashboard graphic login desktop or mobile
     *
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function dashboardGraphicDesktopMovil()
    {
        $days = 6;
        $timezone = session('timezone');
        $startDate = Carbon::now()->setTimezone($timezone)->subDays($days)->format('Y-m-d H:i:s');
        $endDate = Carbon::now()->setTimezone($timezone)->format('Y-m-d H:i:s');
        $startDate = Carbon::parse($startDate)->startOfDay();
        $endDate = Carbon::parse($endDate)->endOfDay();
        $period = CarbonPeriod::create($startDate, $days + 1);
        $whitelabel = Configurations::getWhitelabel();
        $auditsData = $this->usersRepo->getTotalDesktopOrMobileLogin($startDate, $endDate, $whitelabel);
        $audits = $this->usersCollection->formatLoginGraphic($auditsData, $period);
        return response()->json($audits);
    }

    /**
     * Dashboard graphic gender
     *
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function dashboardGraphicGender()
    {
        $whitelabel = Configurations::getWhitelabel();
        $genderData = $this->usersRepo->getTotalGender($whitelabel);
        $data = $this->usersCollection->formatGender($genderData);
        return response()->json($data);
    }

    /**
     * Delete user temp
     *
     * @param int $username Username
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteTemp($username)
    {
        try {
            $this->usersTempRepo->delete($username);
            $data = [
                'title' => _i('User removed'),
                'message' => _i('The user was successfully removed'),
                'close' => _i('Close')
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Show user details
     *
     * @param WalletsCollection $walletsCollection
     * @param TransactionsRepo $transactionsRepo
     * @param ReportsCollection $reportsCollection
     * @param int $id User ID
     * @param string|null $currency Currency ISO
     * @return mixed
     */
    public function details(
        WalletsCollection $walletsCollection,
        TransactionsRepo $transactionsRepo,
        ReportsCollection $reportsCollection,
        CampaignsRepo $campaignsRepo,
        $id,
        $currency = null
    ) {
        $user = $this->usersRepo->find($id);
        $userCurrencies = $this->usersRepo->getCurrencyUser($id);
        $currencies = [];

        foreach ($userCurrencies as $userCurrency) {
            $currencies[] = $userCurrency->currency_iso;
        }

        if (!is_null($user)) {
            try {
                if (empty($currencies)) {
                    return redirect()->route('wallets.create', [$user->id, $user->username, $user->uuid, $user->register_currency]);
                }

                if (!is_null($currency)) {
                    Core::changeCurrency($currency);
                    return redirect()->route('users.details', [$id]);
                }
                $currency = session('currency');
                $wallet = Wallet::getByClient($id, $currency, false);
                if ($wallet->code == Codes::$not_found) {
                    $data['user'] = $user;
                    $data['currency'] = $currency;
                    $data['title'] = _i('Wallet not found');
                    return view('back.users.wallet-not-found', $data);

                } else {
                    $whitelabel = Configurations::getWhitelabel();
                    $levels = Configurations::getLevels();
                    $domain = Configurations::getDomain();
                    $loginURL = "https://$domain/auth/dotpanel-login/$user->uuid";
                    $transactionsTotals = $transactionsRepo->getTotalsByProviderTypes($user->id, $currency, [ProviderTypes::$payment]);
                    $manualTransactionsTotals = $transactionsRepo->getTotalsByProviderTypes($user->id, $currency, [ProviderTypes::$dotworkers]);
                    $bonus = $transactionsRepo->getBonusTotalByUser($user->id, $currency);
                    $this->usersCollection->formatDetails($user, $transactionsTotals, $manualTransactionsTotals, $bonus);
                    $userAccounts = $this->userAccounts($user->id);
                    $countries = $this->countriesRepo->all();
                    $wallets = Wallet::getByUserAndCurrencies($id, $currencies);
//                    $agent = $this->agentsRepo->existsUser($user->id);
//                    $this->usersCollection->formatAgent($agent);

                    //$treeFather = $this->usersCollection->treeFatherValidate($user->id, Auth::user()->id);
                    $treeFather = $this->usersCollection->treeFatherFormat($user->id, Auth::user()->id);

                    $walletData = $wallet->data->wallet;
                    $walletsCollection->formatWallet($walletData);
                    $walletsData = $wallets->data->wallets;
                    $walletsCollection->formatWallets($walletsData);
                    $store = Configurations::getStore()->active;
                    $documentVerification = Configurations::getDocumentVerification();
                    //$segments = $this->segmentsRepo->allByWhitelabel();
                    $bonus = Configurations::getBonus();

                    if ($bonus) {
                        $campaigns = $campaignsRepo->allByVersion();
                        $walletBonus = Wallet::getByClient($user->id, $currency, $bonus);
                        $formatWalletsBonuses = $walletsCollection->formatWalletsBonuses($walletBonus);
                        $data['campaigns'] = $campaigns;
                        $data['wallets_bonuses'] = $formatWalletsBonuses;
                    }

                    if ($store) {
                        $pointsWallet = Store::getWallet($user->id, $currency);

                        if (is_null($pointsWallet)) {
                            $pointsWallet = Store::storeWallet($user->id, $currency);
                        }
                        $data['points'] = number_format($pointsWallet->balance, 2);
                    }

//                    if (!is_null($agent)) {
//                        $data['agent'] = $agent->username;
                    $data['agent'] = '<ol reversed>' . $treeFather . '<ol/>';
//                    }

                    $data['login_user'] = $loginURL;
                    $data['user_accounts'] = $userAccounts;
                    $data['user'] = $user;
                    $data['wallet'] = $walletData;
                    $data['wallets'] = $walletsData;
                    $data['store'] = $store;
                    $data['levels'] = $levels;
                    $data['countries'] = $countries;
                    $data['timezones'] = \DateTimeZone::listIdentifiers();
                    $data['title'] = $user->full_name;
                    //$data['segments'] = $segments;
                    $data['document_verification'] = $documentVerification;
                    $data['bonus'] = $bonus;
                    $data['payments'] = !isset(config('whitelabels.configurations')[Components::$services - 1]->data->payments) ? false : config('whitelabels.configurations')[Components::$services - 1]->data->payments;
                    //return $data;
                    return view('back.users.details', $data);
                }

            } catch (\Exception $ex) {
                \Log::error(__METHOD__, ['exception' => $ex, 'user' => $id]);
                abort(500);
            }
        } else {
            abort(404);
        }
    }

    /**
     * Get user accounts
     *
     * @param int $user User ID
     * @return array
     */
    private function userAccounts(int $user): array
    {
        try {
            $payments = Configurations::getPayments();
            $requestData = null;
            $curl = null;

            if ($payments) {
                $accountsCollection = new AccountsCollection();
                $betPayToken = session('betpay_client_access_token');
                $url = "{$this->betPayURL}/users/accounts/client";

                if (!is_null($betPayToken)) {
                    $requestData = [
                        'currency' => session('currency'),
                        'user' => $user
                    ];
                    $curl = Curl::to($url)
                        ->withData($requestData)
                        ->withHeader('Accept: application/json')
                        ->withHeader("Authorization: Bearer $betPayToken")
                        ->get();
                    $response = json_decode($curl);

                    if ($response->status == Status::$ok) {
                        $accountsCollection->formatUserAccounts($response->data->accounts);
                        $accounts = $response->data->accounts;

                    } else {
                        \Log::error(__METHOD__, ['curl' => $curl, 'curl_request' => $requestData]);
                        $accounts = [];
                    }
                } else {
                    $accounts = [];
                }
            } else {
                $accounts = [];
            }
            return $accounts;

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'curl' => $curl, 'curl_request' => $requestData]);
            return [];
        }
    }

    /**
     * Document action
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function documentAction(Request $request)
    {
        try {
            $whitelabel = Configurations::getWhitelabel();
            if (isset($request->status)) {
                $user = $request->user;
                $type = $request->type;
                $status = $request->status;
                $document = $request->document_id;
            } else {
                $user = $request->user_id;
                $type = $request->type_id;
                $status = $request->status_id;
                $document = $request->id_document;
                $file = $request->file_document;
            }
            $documentData = [
                'status' => $status
            ];
            if ($status == DocumentStatus::$rejected) {
                $path = "{$this->filePath}{$file}";
                Storage::delete($path);
                $this->userDocumentsRepo->delete($document);
            } else {
                $this->userDocumentsRepo->update($document, $documentData);
            }
            $typeName = $this->userDocumentsRepo->documentTypeName($type);
            $statusName = DocumentStatus::getName($status);
            $userData = $this->usersRepo->find($user);
            //Mail::to($userData->email)->send(new Document($userData->username, $typeName->name, $statusName, null));
            $auditData = [
                'ip' => Utils::userIp($request),
                'user_id' => auth()->user()->id,
                'username' => auth()->user()->username,
                'user_data' => [
                    'user_id' => $userData->id,
                    'username' => $userData->username
                ],
                'status' => $statusName
            ];
            //Audits::store($user, AuditTypes::$document_verification, Configurations::getWhitelabel(), $auditData);

            $data = [
                'title' => _i('Success'),
                'message' => _i('The document has been %s and an email has been sent to the user', [$statusName]),
                'close' => _i('Close'),
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Document edit
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function documentEdit(Request $request)
    {
        try {
            $document = $request->document_id_edit;
            $documentData = $this->userDocumentsRepo->find($document);

            if (!is_null($documentData)) {
                $path = "{$this->filePath}{$documentData->name}";
                Storage::delete($path);
                $image = $request->file('image');
                $extension = $image->getClientOriginalExtension();
                $originalName = str_replace(".$extension", '', $image->getClientOriginalName());
                $name = Str::slug($originalName) . time() . '.' . $extension;
                $pathEdit = "{$this->filePath}{$name}";
                Storage::put($pathEdit, file_get_contents($image->getRealPath()), 'public');

                $documentData = [
                    'name' => $name
                ];
                $this->userDocumentsRepo->update($document, $documentData);
                $data = [
                    'title' => _i('Success'),
                    'message' => _i('The document has been successfully edited'),
                    'close' => _i('Close'),
                ];
                return Utils::successResponse($data);
            }

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Get document for user
     *
     * @param int $user User ID
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function documentsUser($user = null)
    {
        try {
            if (!is_null($user)) {
                $whitelabel = Configurations::getWhitelabel();
                $documents = $this->userDocumentsRepo->documentByUser($whitelabel, $user);
                $this->usersCollection->formatDocumentByUser($documents);
                $data = [
                    'documents' => $documents
                ];
            } else {
                $data = [
                    'documents' => []
                ];
            }
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'user' => $user]);
            return Utils::failedResponse();
        }
    }

    /***
     * Show view documents verifications
     *
     * @return Factory|View
     */
    public function documentsVerifications()
    {
        $data['title'] = _i('Documents verifications');
        return view('back.users.documents-verifications', $data);
    }

    /**
     * Get documents in verifications
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function documentsVerificationsData()
    {
        try {
            $whitelabel = Configurations::getWhitelabel();
            $documents = $this->userDocumentsRepo->pending($whitelabel);
            if (!is_null($documents)) {
                $documentsData = $this->usersCollection->formatDocument($documents);
            } else {
                $documentsData = [];
            }
            return Utils::successResponse($documentsData);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Get exclude provider user delete
     *
     * @param int $user User
     * @param string $category name
     * @param string $currency Currency ISO
     * @return Factory|View
     */
    public function excludeProviderUserDelete($user, $category, $currency)
    {
        try {
            $user = (int) $user;
            $this->usersRepo->deleteExcludeMakersUser($category, $user, $currency);
            $data = [
                'title' => _i('User activated'),
                'message' => _i('User was activated correctly'),
                'close' => _i('Close')
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    /**
     * Get exclude provider user list
     *
     * @param int $whitelabel Whitelabel
     * @return Factory|View
     */
    public function excludeProviderUserList(Request $request, $startDate = null, $endDate = null, $category = null, $maker = null, $currency = null)
    {
        try {
            if (!is_null($startDate) && !is_null($endDate)) {
                $startDate = Utils::startOfDayUtc($startDate);
                $endDate = Utils::endOfDayUtc($endDate);
                $category = $request->category;
                $maker = $request->maker;
                $currency = $request->currency;
                $whitelabel = Configurations::getWhitelabel();
                $users = $this->usersRepo->getExcludeProviderUserByDates($currency, $category, $maker, $whitelabel, $startDate, $endDate);
                if (!is_null($users)) {
                    $this->usersCollection->formatExcludeMakersUser($users);
                    $data = [
                        'users' => $users
                    ];
                } else {
                    $data = [
                        'users' => []
                    ];
                }
            } else {
                $data = [
                    'users' => []
                ];
            }
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    /**
     * Show exclude providers users
     *
     * @return \Illuminate\Contracts\Foundation\Application|Factory|View
     */
    public function excludeProvidersUsers()
    {
        $currency = session('currency');
        $whitelabel = Configurations::getWhitelabel();
        $makers = $this->gamesRepo->getMakers();
        $categories = $this->gamesRepo->getCategories();
        $data['currency_client'] = Configurations::getCurrenciesByWhitelabel($whitelabel);
        $data['categories'] = $categories;
        $data['whitelabel'] = $whitelabel;
        $data['makers'] = $makers;
        $data['title'] = _i('Exclude users from providers');
        return view('back.users.exclude-providers-users', $data);
    }

    /**
     *  Exclude providers users data
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function excludeProvidersUsersData(Request $request)
    {
        $this->validate($request, [
            'username' => 'required',
            'currency' => 'required',
            'category' => 'required',
            'maker' => ['required_if:category,*'],
        ]);

        try {
            $username = strtolower(trim($request->username));
            $category = $request->category;
            $maker = $request->maker;
            $whitelabel = Configurations::getWhitelabel();
            $currency = $request->currency;
            $userData = $this->usersRepo->getByUsername($username, $whitelabel);

            if (!is_null($userData)) {
                $date = Carbon::now('UTC')->format('Y-m-d H:i:s');
                $makers[] = $maker;
                if ($category == "*") {
                    $categories = $this->gamesRepo->getCategoriesByMaker($maker);
                    $categories = array_column($categories->toArray(), 'category');
                } else {
                    $categories[] = $category;
                }
                foreach ($categories as $category) {
                    $excludeUser = $this->usersRepo->findExcludeMakerUser($category, $userData->id, $currency);
                    $makersExclude = isset($excludeUser->makers) ? json_decode($excludeUser->makers) : [];
                    $dataMakers = array_merge($makers, $makersExclude);
                    $makersArray = array_values(array_filter(array_unique($dataMakers)));
                    $usersData[] = [
                        'category' => $category,
                        'makers' => json_encode($makersArray),
                        'currency_iso' => $currency
                    ];
                }
                foreach ($usersData as $userToUpdate) {
                    $data = [
                        'currency_iso' => $userToUpdate['currency_iso'],
                        'category' => $userToUpdate['category'],
                        'makers' => $userToUpdate['makers'],
                        'user_id' => $userData->id,
                        'created_at' => $date,
                        'updated_at' => $date
                    ];
                    $this->usersRepo->updateExcludeMakersUser($currency, $userToUpdate['category'], $userData->id, $data);
                }
                $auditData = [
                    'ip' => Utils::userIp($request),
                    'user_id' => auth()->user()->id,
                    'username' => auth()->user()->username,
                    'user_data' => [
                        'user_id' => $userData->id,
                        'category' => $category,
                        'makers' => $makersArray,
                        'currency_iso' => $currency
                    ]
                ];
                //Audits::store($userData->id, AuditTypes::$exclude_provider, Configurations::getWhitelabel(), $auditData);
                $data = [
                    'title' => _i('Excluded user'),
                    'message' => _i('The user has been successfully excluded'),
                    'close' => _i('Close'),
                ];
                return Utils::successResponse($data);
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
     * Get users by username
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getUsersByUsername(Request $request)
    {
        try {
            $name = strtolower($request->user);
            $users = $this->usersRepo->search($name);
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
     * Search users, Option by agent or not.
     *
     * @param Request $request
     * @return Factory|View
     */
    public function search(Request $request)
    {

        try {
            $username = strtolower($request->username);
            if(Auth::user()->username == 'wolf'){
                $users = $this->usersRepo->search($username);
            }else{
                if(Configurations::getAgents()->active == true){
                    $user = Auth::user()->id;
                    if (Auth::user()->username == 'romeo') {
                        $userTmp = $this->usersRepo->findUserCurrencyByWhitelabel('wolf', session('currency'), Configurations::getWhitelabel());
                        $user = isset($userTmp[0]->id) ? $userTmp[0]->id : Auth::user()->id;
                    }
                    $idChildren = array_column($this->agentsRepo->getTreeSqlLevels($user,session('currency'),Configurations::getWhitelabel()),'id');

                    $users = $this->usersRepo->searchTree($username, $idChildren);
                }else{
                    $users = $this->usersRepo->search($username);
                }
            }

            $this->usersCollection->formatSearch($users);
            $data['username'] = $username;
            $data['users'] = $users;
            $data['title'] = _i('Users search');

            return view('back.users.search', $data);

        } catch (\Exception $ex) {
            Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            abort(500);
        }
    }

    /**
     * Get incomplete profiles
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function incompleteProfiles()
    {
        try {
            $users = $this->usersRepo->getWithIncompleteProfiles();
            $data = [
                'users' => number_format($users)
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Show main users
     *
     *
     * @return \Illuminate\Contracts\Foundation\Application|Factory|View
     */
    public function mainUsers()
    {
        $data['title'] = _i('Create main users');
        $data['countries'] = $countries = $this->countriesRepo->all();
        $data['timezones'] = \DateTimeZone::listIdentifiers();
        $data['whitelabels'] = $this->whitelabelsRepo->all();
        $data['password'] = Configurations::generateRandPassword();
        return view('back.users.main-users', $data);
    }

    /**
     * Perform manual adjustments
     *
     * @param Request $request
     * @param TransactionsRepo $transactionsRepo
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function manualAdjustments(Request $request, TransactionsRepo $transactionsRepo)
    {
        $this->validate($request, [
            'amount' => 'required|numeric|gt:0',
            'transaction_type' => 'required',
            'description' => 'required'
        ]);

        try {
            $user = $request->user;
            $wallet = $request->wallet;
            $amount = $request->amount;
            $transactionType = $request->transaction_type;
            $description = $request->description;
            $operator = auth()->user()->username;
            $currency = session('currency');
            $transactionID = $transactionsRepo->getNextValue();
            $uuid = Str::uuid()->toString();
            $additionalData = [
                'provider_transaction' => $uuid,
                'description' => $description,
                'operator' => $operator
            ];
            \Log::debug(__METHOD__, ['request' => $request->all()]);
            if ($transactionType == TransactionTypes::$credit) {
                $transaction = Wallet::creditManualTransactions($amount, Providers::$manual_adjustments, $additionalData, $wallet);
                \Log::debug(__METHOD__, ['$transaction' => $transaction]);
                //new TransactionNotAllowed($amount, $user, Providers::$manual_adjustments, $transactionType);
                // dd($transaction);
            } else {
                $transaction = Wallet::debitManualTransactions($amount, Providers::$manual_adjustments, $additionalData, $wallet);
                //new TransactionNotAllowed($amount, $user, Providers::$manual_adjustments, $transactionType);
            }

            if ($transaction->status == Status::$ok) {
                $transactionData = [
                    'id' => $transactionID,
                    'user_id' => $user,
                    'amount' => $amount,
                    'currency_iso' => $transaction->data->wallet->currency_iso,
                    'transaction_type_id' => $transactionType,
                    'transaction_status_id' => TransactionStatus::$approved,
                    'provider_id' => Providers::$manual_adjustments,
                    'data' => $additionalData,
                    'whitelabel_id' => Configurations::getWhitelabel()
                ];
                $additionalData['wallet_transaction'] = $transaction->data->transaction->id;
                $detailsData = [
                    'data' => json_encode($additionalData)
                ];
                $transactionsRepo->store($transactionData, TransactionStatus::$approved, $detailsData);
                $auditUserData = [];
                $auditData = [
                    'ip' => Utils::userIp(),
                    'user_id' => auth()->user()->id,
                    'username' => auth()->user()->username,
                    'transaction' => [
                        'amount' => $amount,
                        'currency' => $currency,
                        'transaction_type_id' => $transactionType,
                        'description' => $description
                    ]
                ];
                $auditUserData['details'] = $transactionData;
                $auditData['user_data'] = $auditUserData;
                Audits::store($user, AuditTypes::$manual_adjustments, Configurations::getWhitelabel(), $auditData);
                if ($transactionType == TransactionTypes::$credit) {
                    $userDate = [
                        'last_deposit' => Carbon::now(),
                        'last_deposit_amount' => $amount,
                        'last_deposit_currency' => $currency
                    ];
                } else {
                    $userDate = [
                        'last_debit' => Carbon::now(),
                        'last_debit_amount' => $amount,
                        'last_debit_currency' => $currency
                    ];
                }
                $this->usersRepo->update($user, $userDate);
                $data = [
                    'title' => _i('Transaction performed'),
                    'message' => _i('The transaction was successfully made to the user'),
                    'close' => _i('Close'),
                    'balance' => number_format($transaction->data->wallet->balance, 2)
                ];
                return Utils::successResponse($data);

            } else {
                return Utils::failedResponse();
            }

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     * Perform manual transactions
     *
     * @param Request $request
     * @param TransactionsRepo $transactionsRepo
     * @param OperationalBalancesRepo $operationalBalanceRepo
     * @param OperationalBalancesTransactionsRepo $operationalBalanceTransactionsRepo
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function manualTransactions(Request $request, TransactionsRepo $transactionsRepo, OperationalBalancesRepo $operationalBalanceRepo, OperationalBalancesTransactionsRepo $operationalBalanceTransactionsRepo)
    {
        $this->validate($request, [
            'amount' => 'required|numeric|gt:0',
            'transaction_type' => 'required',
            'description' => 'required'
        ]);

        try {
            $currency = session('currency');
            $user = $request->user;
            $wallet = $request->wallet;
            $amount = $request->amount;
            $transactionType = $request->transaction_type;
            $description = $request->description;
            $walletData = Wallet::getByClient($user, $currency);
            $operator = auth()->user()->username;
            $whitelabel = Configurations::getWhitelabel();
            $operationalBalanceData = $operationalBalanceRepo->find($whitelabel, $currency);
            $provider = Providers::$dotworkers;
            $userData = $this->usersRepo->find($user);
            $transactionID = $transactionsRepo->getNextValue();
            $uuid = Str::uuid()->toString();

            $additionalData = [
                'provider_transaction' => $uuid,
                'description' => $description,
                'operator' => $operator
            ];

            if ($transactionType == TransactionTypes::$credit) {
                if (!is_null($operationalBalanceData) && $amount > $operationalBalanceData->balance) {
                    $operationalBalance = number_format($operationalBalanceData->balance, 2);
                    //new TransactionNotAllowed($amount, $user, $provider, $transactionType);
                    $data = [
                        'title' => _i('Transaction not allowed'),
                        'message' => _i('The amount is higher than the operational balance. Available: %s %s', [$currency, $operationalBalance]),
                        'close' => _i('Close')
                    ];
                    return Utils::errorResponse(Codes::$forbidden, $data);

                } else {
                    $transaction = Wallet::creditManualTransactions($amount, $provider, $additionalData, $wallet);
                }
            } else {
                if ($amount <= $walletData->data->wallet->balance) {
                    $transaction = Wallet::debitManualTransactions($amount, $provider, $additionalData, $wallet);

                } else {
                    $data = [
                        'title' => _i('Insufficient balance'),
                        'message' => _i("The user's balance is insufficient to perform the transaction"),
                        'close' => _i('Close')
                    ];
                    return Utils::errorResponse(Codes::$forbidden, $data);
                }
            }

            if ($transaction->status == Status::$ok) {
                $transactionData = [
                    'id' => $transactionID,
                    'user_id' => $user,
                    'amount' => $amount,
                    'currency_iso' => $currency,
                    'transaction_type_id' => $transactionType,
                    'transaction_status_id' => TransactionStatus::$approved,
                    'provider_id' => $provider,
                    'data' => $additionalData,
                    'whitelabel_id' => Configurations::getWhitelabel()
                ];
                $additionalData['wallet_transaction'] = $transaction->data->transaction->id;
                $detailsData = [
                    'data' => json_encode($additionalData)
                ];
                $transactionsRepo->store($transactionData, TransactionStatus::$approved, $detailsData);

                $operationalBalanceTransaction = [
                    'amount' => $amount,
                    'user_id' => $user,
                    'operator' => $operator,
                    'provider_id' => $provider,
                    'whitelabel_id' => $whitelabel,
                    'currency_iso' => $currency,
                    'transaction_type_id' => $transactionType,
                ];
                $operationalBalanceTransactionsRepo->store($operationalBalanceTransaction);
                $operationalBalanceRepo->decrement($whitelabel, $currency, $amount);

                $auditData = [
                    'ip' => Utils::userIp(),
                    'user_id' => auth()->user()->id,
                    'username' => auth()->user()->username,
                    'transaction' => [
                        'amount' => $amount,
                        'currency' => $currency,
                        'transaction_type_id' => $transactionType,
                        'description' => $description
                    ]
                ];
                Audits::store($user, AuditTypes::$manual_transactions, Configurations::getWhitelabel(), $auditData);
                if ($transactionType == TransactionTypes::$credit) {
                    $userDates = [
                        'last_deposit' => Carbon::now(),
                        'last_deposit_amount' => $amount,
                        'last_deposit_currency' => $currency
                    ];
                    if (is_null($userData->first_deposit)) {
                        $userDates['first_deposit'] = Carbon::now();
                        $userDates['first_deposit_amount'] = $amount;
                        $userDates['first_deposit_currency'] = $currency;
                    }
                } else {
                    $userDates = [
                        'last_debit' => Carbon::now(),
                        'last_debit_amount' => $amount,
                        'last_debit_currency' => $currency
                    ];
                }
                $this->usersRepo->update($user, $userDates);

                $data = [
                    'title' => _i('Transaction performed'),
                    'message' => _i('The transaction was successfully made to the user'),
                    'close' => _i('Close'),
                    'balance' => number_format($transaction->data->wallet->balance, 2)
                ];
                return Utils::successResponse($data);

            } else {
                return Utils::failedResponse();
            }

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     * Get new users
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newUsers()
    {
        try {
            $whitelabel = Configurations::getWhitelabel();
            $timezone = session('timezone');
            $startDate = Carbon::now($timezone)->format('Y-m-d');
            $endDate = Carbon::now($timezone)->format('Y-m-d');
            $startDate = Utils::startOfDayUtc($startDate);
            $endDate = Utils::endOfDayUtc($endDate);
            $users = $this->usersRepo->getTotalRegisteredByDates($whitelabel, $startDate, $endDate, $webRegister = true);
            $data = [
                'users' => number_format($users)
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Perform points transactions
     *
     * @param Request $request
     * @param TransactionsRepo $transactionsRepo
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function pointsTransactions(Request $request)
    {
        $this->validate($request, [
            'amount' => 'required|numeric|gt:0',
            'transaction_type' => 'required',
            'description' => 'required'
        ]);

        try {
            $user = $request->user;
            $amount = $request->amount;
            $transactionType = $request->transaction_type;
            $whitelabel = Configurations::getWhitelabel();
            $provider = Providers::$store;
            $currency = session('currency');

            if ($transactionType == TransactionTypes::$credit) {
                $transaction = Store::credit($user, $currency, $whitelabel, $amount, $provider);

            } else {
                $transaction = Store::debit($user, $currency, $whitelabel, $amount, $provider);
            }

            $auditData = [
                'ip' => Utils::userIp($request),
                'user_id' => auth()->user()->id,
                'username' => auth()->user()->username,
                'transaction' => [
                    'amount' => $amount,
                    'currency' => $currency,
                    'transaction_type_id' => $transactionType,
                    'description' => $request->description
                ]
            ];
            Audits::store($user, AuditTypes::$points_transactions, Configurations::getWhitelabel(), $auditData);
            if ($transactionType == TransactionTypes::$credit) {
                $userDate = [
                    'last_deposit' => Carbon::now(),
                    'last_deposit_amount' => $amount,
                    'last_deposit_currency' => $currency
                ];
            } else {
                $userDate = [
                    'last_debit' => Carbon::now(),
                    'last_debit_amount' => $amount,
                    'last_debit_currency' => $currency

                ];
            }
            $this->usersRepo->update($user, $userDate);
            $data = [
                'title' => _i('Transaction performed'),
                'message' => _i('The transaction was successfully made to the user'),
                'close' => _i('Close'),
                'balance' => number_format($transaction->balance, 2)
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     * Get by date products users totals
     *
     * @param $user
     * @param $currency
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function productsUsersTotalsDate($user, $startDate = null, $endDate = null)
    {
        try {
            if (!is_null($startDate) && !is_null($endDate)) {
                $currency = session('currency');
                $whitelabel = Configurations::getWhitelabel();
                $startDate = Utils::startOfDayUtc($startDate);
                $endDate = Utils::endOfDayUtc($endDate);
                $totals = $this->closuresUsersTotalsRepo->getProductsTotalsByUser($whitelabel, $startDate, $endDate, $currency, $user);
            } else {
                $totals = [];
            }
            $data = $this->reportsCollection->productsTotalsByUser($totals);
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'user' => $user, 'start_date' => $startDate, 'end_date' => $endDate]);
            return Utils::failedResponse();
        }
    }

    /**
     * Resend activation email
     *
     * @param Request $request
     * @param null|string $startDate Start date to filter
     * @param null|string $endDate End date to filter
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function resendActivationEmail(Request $request)
    {
        $activateURL = null;
        $curl = null;

        try {
            $email = $request->email;
            $username = $request->username;
            $whitelabel = Configurations::getWhitelabel();
            $userTemp = $this->usersTempRepo->getUserByUsername($whitelabel, $username);
            $domain = Configurations::getDomain();
            $activateURL = "https://$domain/users/activate/$userTemp->token";
            $emailConfiguration = Configurations::getEmailContents($whitelabel, EmailTypes::$activate_account);
            Mail::to($email)->send(new Activate($activateURL, $username, $emailConfiguration));
            $data = [
                'title' => _i('Activate account'),
                'message' => _i('An email was sent with the activation link for the account.'),
                'close' => _i('Close'),
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all(), 'url' => $activateURL, 'curl' => $curl]);
            return Utils::failedResponse();
        }
    }

    /**
     * Validate email
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function resetEmail(Request $request)
    {
        $this->validate($request, [
            'email' => ['required', new Email()]
        ]);

        try {
            $user = auth()->user()->id;
            $tokenUser = $this->usersRepo->getUsers($user);
            foreach($tokenUser as $users){
                $token = $users->uuid;
                $username = $users->username;
            }
            $email = $request->email;
            $url = route('users.validate', [$token, $email]);
            $uniqueEmail = $this->usersRepo->uniqueEmail($email);
            if (!is_null($uniqueEmail)) {
                $data = [
                    'title' => _i('Email in use'),
                    'message' => _i('The indicated email is already in use'),
                    'close' => _i('Close'),
                ];
                return Utils::errorResponse(Codes::$forbidden, $data);

            }else {
                if (!$this->validateEmail($email)) {
                    $data = [
                        'title' => _i('Invalid email'),
                        'message' => _i('The email entered is invalid or does not exist'),
                        'close' => _i('Close'),
                    ];
                    return Utils::errorResponse(Codes::$forbidden, $data);
                }
            }
            $whitelabelId = Configurations::getWhitelabel();
            $emailConfiguration = Configurations::getEmailContents($whitelabelId, EmailTypes::$validate_email);
            Mail::to($email)->send(new Validate($whitelabelId, $url, $username, $emailConfiguration, EmailTypes::$validate_email));

            $data = [
                'title' => _i('Email validation'),
                'message' => _i('A message has been sent to activate your mail reset'),
                'close' => _i('Close')
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     * Reset password
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function resetPassword(Request $request)
    {
        $this->validate($request, [
            'password' => ['required', 'confirmed', new Password()],
            'password_confirmation' => 'required'
        ]);

        try {
            $user = $request->user;
            $userData = $this->agentsRepo->statusActionByUser($user);
            $roles = Security::getUserRoles($user);
            if (isset($userData->action) && $userData->action == ActionUser::$locked_higher || isset($userData->status) && $userData->status == false) {
                $data = [
                    'title' => ActionUser::getName($userData->action),
                    'message' => _i('Contact your superior...'),
                    'close' => _i('Close')
                ];
                return Utils::errorResponse(Codes::$not_found, $data);

            }

            $password = $request->password;
            if($userData->type_user == TypeUser::$player ) {
                $userData = [
                    'password' => $password,
                    'action' =>  ActionUser::$active
                ];
            } else {
                $userData = [
                    'password' => $password,
                    'action' => Configurations::getResetMainPassword() ? ActionUser::$changed_password:ActionUser::$active,
                ];
            }

            $this->usersRepo->update($user, $userData);

            $auditData = [
                'ip' => Utils::userIp($request),
                'user_id' => auth()->user()->id,
                'username' => auth()->user()->username,
                'password' => $password
            ];
            Audits::store($user, AuditTypes::$user_password, Configurations::getWhitelabel(), $auditData);
            $userTemp = $this->usersRepo->getUsers($user);
            $ip = Utils::userIp($request);
            foreach ($userTemp as $users) {
                $name = $users->username;
                $action = $users->action;
                $confirmation = $users->confirmation_email;
            }
            $url = route('core.dashboard');
            $whitelabelId = Configurations::getWhitelabel();
            if($action === ActionUser::$active && $confirmation == true){
                $emailConfiguration = Configurations::getEmailContents($whitelabelId, EmailTypes::$password_change_notification);
                Mail::to($userTemp)->send(new Users($whitelabelId, $url, $name, $emailConfiguration, EmailTypes::$password_change_notification, $ip));
            }
            $data = [
                'title' => _i('Password reset'),
                'message' => _i('Password was successfully reset'),
                'close' => _i('Close')
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     * Store main users
     *
     * @param Request $request
     * @param UserCurrenciesRepo $userCurrenciesRepo
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function storeMainUsers(Request $request, UserCurrenciesRepo $userCurrenciesRepo)
    {
        $this->validate($request, [
            'whitelabel' => 'required',
            'country' => 'required',
            'timezone' => 'required',
            'password' => 'required',
        ]);
        try {
            $whitelabel = $request->whitelabel;
            $whitelabelData = $this->whitelabelsRepo->find($whitelabel);
            $currencies = Configurations::getCurrenciesByWhitelabel($whitelabel);
            $store = Configurations::getStore()->active;
            $ip = Utils::userIp($request);
            $users = ['wolf','supportgl', 'admin', 'panther',   'supportnb', 'supportvj'];
            foreach ($users as $user) {
                $userData = $this->usersRepo->getByUsername($user, $whitelabel);

                if (is_null($userData)) {
                    if ($user == 'admin') {
                        $email = "admin@{$whitelabelData->domain}";
                        $password = $request->password;

                    } else {
                        $email = "{$user}@betsweet.com";

                        switch ($user) {
                            case 'wolf': {
                                    $password = env('MAIN_SUPPORT_PASSWORD');
                                    break;
                                }
                            case 'panther': {
                                    $password = env('DEVELOP_PASSWORD');
                                    break;
                                }
                            default: {
                                    $password = env('SUPPORT_PASSWORD');
                                    break;
                                }
                        }
                    }

                    $newUserData = [
                        'username' => $user,
                        'email' => $email,
                        'password' => $password,
                        'tester' => false,
                        'uuid' => Str::uuid()->toString(),
                        'ip' => $ip,
                        'status' => true,
                        'whitelabel_id' => $whitelabel,
                        'web_register' => false,
                        'main' => true,
                        'action'=>ActionUser::$active,
                        'type_user'=>TypeUser::$agentMater,
                    ];
                    $profileData = [
                        'country_iso' => $request->country,
                        'timezone' => $request->timezone,
                        'level' => 1
                    ];
                    $newUser = $this->usersRepo->store($newUserData, $profileData);
                    Security::assignRole($newUser->id, 1);

                    $wallet = Wallet::store($newUser->id, $newUser->username, $newUser->uuid, $currencies[0], $whitelabel, session('wallet_access_token'));
                    Configurations::generateReferenceCode($newUser->id);

                    $currencyData = [
                        'user_id' => $newUser->id,
                        'currency_iso' => $currencies[0]
                    ];
                    $walletData = [
                        'wallet_id' => $wallet->data->wallet->id,
                        'default' => 0
                    ];
                    $userCurrenciesRepo->store($currencyData, $walletData);

                    if ($store) {
                        Store::storeWallet($newUser->id, $currencies[0]);
                    }
                }
            }

            $data = [
                'title' => _i('Users created'),
                'message' => _i('Users were created successfully'),
                'close' => _i('Close'),
                'password' => Configurations::generateRandPassword()
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }

    }

    /**
     * Get total users
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function totalUsers()
    {
        try {
            $users = $this->usersRepo->getTotalRegistered();
            $data = [
                'users' => number_format($users)
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Show transactions by lot
     *
     * @return \Illuminate\Contracts\Foundation\Application|Factory|View
     */
    public function transactionsByLot()
    {
        $data['title'] = _i('Transactions by lot');
        return view('back.users.transactions-by-lot', $data);
    }

    /**
     * Transactions by lot file
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function transactionsByLotFile(Request $request)
    {
        $this->validate($request, [
            'transactions-by-lot-file' => 'mimes:csv,xlsx,xls,xlsm,xlsb',
            'description' => 'required',
        ]);

        try {
            $file = $request->file('transactions-by-lot-file');
            $ip = Utils::userIp($request);
            $description = $request->description;
            $import = new TransactionsByLotImport($ip, $description);
            $import->import($file);
            $failures = $import->failures();
            $transactions = $import->data;
            $data = $this->usersCollection->formatImportData($failures, $transactions);
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }

    }

    /**
     * Unlock balance
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function unlockBalance(Request $request)
    {
        try {
            $wallet = $request->unlock_wallet;
            $user = $request->user_id;
            $walletData = [
                'balance_locked' => 0
            ];
            $unlockBalance = Wallet::update($wallet, $walletData);
            if ($unlockBalance->status == Status::$ok) {
                $data = [
                    'title' => _i('Balance unlock'),
                    'message' => _i('The balance has been unlocked'),
                    'close' => _i('Close'),
                    'route' => route('users.details', [$user])
                ];
                return Utils::successResponse($data);
            } else {
                $data = [
                    'title' => _i('Wallet not found'),
                    'message' => _i('The wallet not found. Please check and try again'),
                    'close' => _i('Close'),
                    'route' => route('users.details', [$user])
                ];
                return Utils::errorResponse(Codes::$forbidden, $data);
            }

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Update user profile
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateProfile(Request $request)
    {
        $country = $request->country;
        $dni = $request->dni;
        $birthDate = $request->birth_date;

        $rules = [
            'email' => 'required|email'
        ];

        if (!is_null($dni)) {
            $countryData = $this->countriesRepo->find($country);
            $regex = $countryData->dni_regex;

            if (!is_null($regex)) {
                $rules['dni'] = [new DNI($regex)];
            }
        }

        if (!is_null($birthDate)) {
            $rules['birth_date'] = ['required', new Age()];
        }

        $this->validate($request, $rules);

        try {
            $id = $request->user;
            $email = strtolower($request->email);
            $firstName = !is_null($request->first_name) ? ucwords($request->first_name) : null;
            $lastName = !is_null($request->last_name) ? ucwords($request->last_name) : null;
            $gender = $request->gender;
            $level = $request->level;
            $timezone = $request->timezone;
            $phone = (int) $request->phone;
            $birthDate = is_null($request->birth_date) ? null : Carbon::createFromFormat('d-m-Y', $request->birth_date);
            $uniqueEmail = $this->usersRepo->uniqueEmailByID($id, $email);
            $state = $request->state;
            $city = $request->city;

            if (is_null($dni)) {
                $uniqueDNI = null;

            } else {
                $uniqueDNI = $this->usersRepo->uniqueDNIByID($id, $dni);
            }

            if (is_null($uniqueEmail)) {
                if (is_null($uniqueDNI)) {
                    $user = $this->usersRepo->find($id);
                    $profile = $this->profilesRepo->find($id);

                    $userData = [
                        'email' => $email
                    ];
                    $profileData = [
                        'dni' => $dni,
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'gender' => $gender,
                        'level' => $level,
                        'country_iso' => $country,
                        'timezone' => $timezone,
                        'phone' => $phone,
                        'birth_date' => $birthDate,
                        'state' => $state,
                        'city' => $city,

                    ];
                    $this->usersRepo->update($id, $userData);
                    $this->profilesRepo->update($id, $profileData);

                    $auditUserData = [];
                    $auditData = [
                        'ip' => Utils::userIp($request),
                        'user_id' => auth()->user()->id,
                        'username' => auth()->user()->username
                    ];

                    if ($email != $user->email) {
                        $auditUserData['email'] = $email;
                    }
                    if ($dni != $profile->dni) {
                        $auditUserData['dni'] = $dni;
                    }
                    if ($firstName != $profile->first_name) {
                        $auditUserData['first_name'] = $firstName;
                    }
                    if ($lastName != $profile->last_name) {
                        $auditUserData['last_name'] = $lastName;
                    }
                    if ($gender != $profile->gender) {
                        $auditUserData['gender'] = $gender;
                    }
                    if ($level != $profile->level) {
                        $auditUserData['level'] = $level;
                    }
                    if ($country != $profile->country_iso) {
                        $auditUserData['country_iso'] = $country;
                    }
                    if ($timezone != $profile->timezone) {
                        $auditUserData['timezone'] = $timezone;
                    }
                    if ($phone != $profile->phone) {
                        $auditUserData['phone'] = $phone;
                    }
                    if ($birthDate != $profile->birth_date) {
                        $auditUserData['birth_date'] = $birthDate;
                    }
                    if ($state != $profile->state) {
                        $auditUserData['state'] = $state;
                    }
                    if ($city != $profile->city) {
                        $auditUserData['city'] = $city;
                    }

                    $auditData['user_data'] = $auditUserData;
                    Audits::store($id, AuditTypes::$user_modification, Configurations::getWhitelabel(), $auditData);

                    $data = [
                        'title' => _i('Updated profile'),
                        'message' => _i('User data was saved correctly'),
                        'close' => _i('Close')
                    ];
                    return Utils::successResponse($data);
                } else {
                    $data = [
                        'title' => _i('DNI in use'),
                        'message' => _i('The DNI entered is in use by another user'),
                        'close' => _i('Close')
                    ];
                    return Utils::errorResponse(Codes::$forbidden, $data);
                }
            } else {
                $data = [
                    'title' => _i('Email in use'),
                    'message' => _i('The entered email is in use by another user'),
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
     * User list for credit charging point
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function userListCreditChargingPoint(Request $request)
    {
        try {
            $user = $request->user;
            if (!is_null($user)) {
                $currency = session('currency');
                $listUser = $this->usersRepo->userChargingPointFind($user, $currency);
                $usersData = $this->usersCollection->userListChargingPoint($listUser);

                $data = [
                    'user' => $usersData,
                ];
            } else {
                $data = [
                    'user' => []
                ];
            }
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     * Get users audit data
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function usersAuditData($user = null)
    {
        try {
            if (!is_null($user)) {
                $users = $this->auditsRepo->getUsersModified($user);
                $this->usersCollection->getTypeAudit($users);
                $data = [
                    'users' => $users
                ];
            } else {
                $data = [
                    'users' => []
                ];
            }

            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Get users ips data
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function usersIpsData($user = null)
    {
        try {
            if (!is_null($user)) {
                $ips = $this->auditsRepo->getUsersIps($user);
                $data = [
                    'ips' => $ips
                ];
            } else {
                $data = [
                    'ips' => []
                ];
            }
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
    public function userIpData(Request $request)
    : array {
        return $this->auditsRepo->getUserIp($request);
    }

    /**
     * Show users status
     *
     * @return \Illuminate\Contracts\Foundation\Application|Factory|View
     */
    public function usersStatus()
    {
        $data['title'] = _i('Users status');
        $data['whitelabels'] = $this->whitelabelsRepo->all();
        return view('back.users.users-status', $data);
    }

    /**
     * Get users status data
     *
     * @param $whitelabel
     * @param $status
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function usersStatusData($whitelabel = null, $status = null)
    {
        try {
            if (!is_null($whitelabel) && !is_null($status)) {

                if (in_array(Roles::$admin_Beet_sweet, session('roles'))) {
                    //TODO REPLICA DE TREE
                    $tree = $this->usersRepo->treeSqlByUser(Auth::id(), session('currency'), $whitelabel);
                    $users = $this->usersRepo->statusUsersTree($whitelabel, $status, $tree);
                } else {
                    $users = $this->usersRepo->statusUsers($whitelabel, $status);
                }

                $this->usersCollection->formatStatusUsers($users);
                $data = [
                    'users' => $users
                ];
            } else {
                $data = [
                    'users' => []
                ];
            }
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /***
     * Show view users temp
     *
     * @return Factory|View
     */
    public function usersTemp()
    {
        $data['title'] = _i('Temp users');
        return view('back.users.users-temp', $data);
    }

    /**
     * Get temp users
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function usersTempData()
    {
        try {
            $whitelabel = Configurations::getWhitelabel();
            $users = $this->usersTempRepo->getUsers($whitelabel);
            if (!is_null($users)) {
                $usersData = $this->usersCollection->formatUsersTemp($users);
            } else {
                $usersData = [];
            }
            return Utils::successResponse($usersData);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Get users theme data
     *
     * @param $theme
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function usersThemeData($theme)
    {
        try {
            $users = $this->usersRepo->themeUsers(Auth::id(), $theme);
            $data = [
                'theme' => $users->theme
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Validate email
     *
     * @param Request $request
     * @param string $token User activation token
     * @param string $email User activation email
     * @return Application|Factory|View
     */
    public function validateEmailByAgent(Request $request, $token, $email)
    {
            $user = $this->usersRepo->findByToken($token);
            if (!is_null($user)) {
                $userData = [
                    'email' => strtolower($email),
                    'action' => ActionUser::$active,
                    'confirmation_email' => true
                ];
                $this->usersRepo->update($user->id, $userData);
            }
            $route = route('auth.logout');

            return redirect()->to($route);
    }

    /**
     * Show view of list users
     */
    public function listMyUsers()
    {
        try {

            $description = Configurations::getWhitelabelDescription();
            $data['title'] = _i('List of users') . ' ' . $description;
            $data['roles'] = [];

            return view('back.users.list_users_by_owner', $data);

        } catch (\Exception $ex) {
            Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    /**
     * list users
     * @param Request $request
     * @return Response
     */
    public function getMyUsers(Request $request)
    {
        try {

            $currency = session('currency');
            $whitelabel = Configurations::getWhitelabel();

            $offset = $request->has('start') ? $request->get('start') : 0;
            $limit = $request->has('length') ? $request->get('length') : 2000;

            $users = $this->usersRepo->getMyUsers(Auth::id(),$whitelabel, $currency,$limit,$offset);

            $data = $this->usersCollection->formatMyUsers($request,$users);

            return response()->json($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
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
        $data = [
            'address' => strtolower($email)
        ];
        $curl = Curl::to(env('MAILGUN_VALIDATION_URL'))
            ->withOption('HTTPAUTH', CURLAUTH_BASIC)
            ->withOption('USERPWD', 'api:' . env('MAILGUN_SECRET'))
            ->withData($data)
            ->post();
        $response = json_decode($curl);
        $result = true;
        if (!isset($response->result) || $response->result != 'deliverable') {
            Log::debug('validateEmail', [$response]);
            $result = false;
        }
        return $result;


    }
}

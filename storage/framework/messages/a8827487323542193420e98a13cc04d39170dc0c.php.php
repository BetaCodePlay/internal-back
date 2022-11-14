<?php

namespace App\Http\Controllers;

use App\BetPay\BetPay;
use App\BetPay\Collections\PaymentMethodsCollection;
use App\BonusSystem\Collections\AllocationCriteriaCollection;
use App\BonusSystem\Collections\CampaignParticipationStatusCollection;
use App\BonusSystem\Collections\CampaignsCollection;
use App\BonusSystem\Version;
use App\BonusSystem\Repositories\AllocationCriteriaRepo;
use App\BonusSystem\Repositories\CampaignParticipationDetailsRepo;
use App\BonusSystem\Repositories\CampaignParticipationRepo;
use App\BonusSystem\Repositories\CampaignParticipationStatusRepo;
use App\BonusSystem\Repositories\CampaignsRepo;
use App\BonusSystem\Repositories\CampaignsUsersRepo;
use App\BonusSystem\Repositories\RolloversRepo;
use App\BonusSystem\Repositories\RolloversTypesRepo;
use App\Core\Repositories\ProvidersRepo;
use App\Core\Repositories\ProvidersTypesRepo;
use App\Core\Repositories\TransactionsRepo;
use App\CRM\Repositories\SegmentsRepo;
use App\Users\Import\ImportUsers;
use App\Users\Repositories\UsersRepo;
use Carbon\Carbon;
use Dotworkers\Bonus\Bonus;
use Dotworkers\Bonus\Enums\AllocationCriteria;
use Dotworkers\Bonus\Enums\CampaignParticipationStatus;
use Dotworkers\Bonus\Enums\RolloverStatus;
use Dotworkers\Bonus\Enums\Status;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Configurations\Enums\Codes;
use Dotworkers\Configurations\Enums\Providers;
use Dotworkers\Configurations\Enums\ProviderTypes;
use Dotworkers\Configurations\Enums\TransactionStatus;
use Dotworkers\Configurations\Enums\TransactionTypes;
use Dotworkers\Configurations\Utils;
use Dotworkers\Wallet\Wallet;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class BonusSystemController
 *
 * This class allows to manage BonusSystem requests
 *
 * @package App\Http\Controllers
 * @author  Damelys Espinoza
 */
class BonusSystemController extends Controller
{
    /**
     * CampaignsRepo
     *
     * @var CampaignsRepo
     */
    private $campaignsRepo;

    /**
     * AllocationCriteriaRepo
     *
     * @var AllocationCriteria
     */
    private $allocationCriteriaRepo;

    /**
     * CampaignsCollection
     *
     * @var CampaignsCollection
     */
    private $campaignsCollection;

    /**
     * ProvidersRepo
     *
     * @var ProvidersRepo
     */
    private $providersRepo;

    /**
     * AllocationCriteriaCollection
     *
     * @var AllocationCriteriaCollection
     */
    private $allocationCriteriaCollection;

    /**
     * ProvidersTypesRepo
     *
     * @var $providersTypesRepo
     */
    private $providersTypesRepo;

    /**
     * RolloversTypesRepo
     *
     * @var $rolloversTypesRepo
     */
    private $rolloversTypesRepo;

    /**
     * CampaignsUsersRepo
     *
     * @var $campaignsUsersRepo
     */
    private $campaignsUsersRepo;

    /**
     * UsersRepo
     * @var $usersRepo
     */
    private $usersRepo;

    /**
     * SegmentsRepo
     *
     * @var $segmentsRepo
     */
    private $segmentsRepo;

    /**
     * CampaignParticipationStatusRepo
     *
     * @var $campaignParticipationStatusRepo
     */
    private $campaignParticipationStatusRepo;

    /**
     * CampaignParticipationRepo
     *
     * @var $campaignParticipationRepo
     */
    private $campaignParticipationRepo;

    /**
     * CampaignParticipationDetailsRepo
     *
     * @var $campaignParticipationDetailsRepo
     */
    private $campaignParticipationDetailsRepo;

    /**
     * PaymentMethodsCollection
     *
     * @var $paymentMethodsCollection
     */
    private $paymentMethodsCollection;

    /**
     * BonusSystemController constructor.
     * @param CampaignsRepo $campaignsRepo
     * @param AllocationCriteriaRepo $allocationCriteriaRepo
     * @param CampaignsCollection $campaignsCollection
     * @param ProvidersRepo $providersRepo
     * @param AllocationCriteriaCollection $allocationCriteriaCollection
     * @param ProvidersTypesRepo $providersTypesRepo
     * @param RolloversTypesRepo $rolloversTypesRepo
     * @param CampaignsUsersRepo $campaignsUsersRepo
     * @param UsersRepo $usersRepo
     * @param SegmentsRepo $segmentsRepo
     * @param CampaignParticipationRepo $campaignParticipationRepo
     * @param CampaignParticipationStatusRepo $campaignParticipationStatusRepo
     * @param CampaignParticipationDetailsRepo $campaignParticipationDetailsRepo
     * @param PaymentMethodsCollection $paymentMethodsCollection
     */
    public function __construct(CampaignsRepo $campaignsRepo, AllocationCriteriaRepo $allocationCriteriaRepo, CampaignsCollection $campaignsCollection, ProvidersRepo $providersRepo, AllocationCriteriaCollection $allocationCriteriaCollection, ProvidersTypesRepo $providersTypesRepo, RolloversTypesRepo $rolloversTypesRepo, CampaignsUsersRepo $campaignsUsersRepo, UsersRepo $usersRepo, SegmentsRepo $segmentsRepo, CampaignParticipationRepo $campaignParticipationRepo, CampaignParticipationStatusRepo $campaignParticipationStatusRepo, CampaignParticipationDetailsRepo $campaignParticipationDetailsRepo, PaymentMethodsCollection $paymentMethodsCollection)
    {
        $this->campaignsRepo = $campaignsRepo;
        $this->allocationCriteriaRepo = $allocationCriteriaRepo;
        $this->campaignsCollection = $campaignsCollection;
        $this->providersRepo = $providersRepo;
        $this->allocationCriteriaCollection = $allocationCriteriaCollection;
        $this->providersTypesRepo = $providersTypesRepo;
        $this->rolloversTypesRepo = $rolloversTypesRepo;
        $this->campaignsUsersRepo = $campaignsUsersRepo;
        $this->usersRepo = $usersRepo;
        $this->segmentsRepo = $segmentsRepo;
        $this->campaignParticipationStatusRepo = $campaignParticipationStatusRepo;
        $this->campaignParticipationRepo = $campaignParticipationRepo;
        $this->campaignParticipationDetailsRepo = $campaignParticipationDetailsRepo;
        $this->paymentMethodsCollection = $paymentMethodsCollection;
    }

    /**
     * Add user at campaign
     *
     * @param Request $request
     * @return Response
     */
    public function addCamapignToUser(Request $request)
    {
        try {
            $campaign = $request->campaigns;
            $user = $request->user;
            $campaignData = $this->campaignsRepo->getById($campaign);
            $currentDate = Carbon::now();

            if ($campaignData->status) {
                if (!is_null($campaignData->start_date) && !is_null($campaignData->end_date)) {
                    if ($currentDate >= $campaignData->start_date && $currentDate <= $campaignData->end_date) {
                        $validDate = true;
                    } else {
                        $validDate = false;
                    }
                } else {
                    if (is_null($campaignData->start_date)) {
                        if ($currentDate <= $campaignData->end_date) {
                            $validDate = true;
                        } else {
                            $validDate = false;
                        }
                    }

                    if (is_null($campaignData->end_date)) {
                        if ($currentDate >= $campaignData->start_date) {
                            $validDate = true;
                        } else {
                            $validDate = false;
                        }
                    }
                }

                if ($validDate) {
                    $userData = $this->usersRepo->find($user);
                    $username = $userData->username;
                    $uuid = $userData->uuid;
                    $whitelabel = Configurations::getWhitelabel();
                    $currency = session('currency');
                    $walletAccessToken = session('wallet_access_token');

                    $campaignUserExist = $this->campaignsUsersRepo->getByCampaignAndUser($campaignData->id, $user);

                    if (is_null($campaignUserExist)) {
                        $data = [
                            'campaign_id' => $campaignData->id,
                            'user_id' => $user
                        ];
                        $this->campaignsUsersRepo->store($data);

                        if ($campaignData->data->rollovers) {
                            $providerTypes = [];

                            foreach ($campaignData->rolloverTypes as $rolloverType) {
                                $providerTypes[] = $rolloverType->provider_type_id;
                            }
                            $clientToken = Wallet::clientAccessToken();
                            $wallet = Wallet::store($user, $username, $uuid, $currency, $whitelabel, $clientToken->access_token, $bonus = true, $providerTypes);

                            foreach ($wallet->data->bonus as $bonusWallet) {
                                if ($bonusWallet->provider_type_id == $providerTypes[0]) {
                                    $this->creditBonus($campaignData, $whitelabel, $currency, $user, $bonusWallet->id, $walletAccessToken);
                                }
                            }

                        } else {
                            $wallet = Wallet::get($currency);
                            $this->creditBonus($campaignData, $whitelabel, $currency, $user, $wallet->data->wallet->id, $walletAccessToken);
                        }

                        $data = [
                            'title' => _i('Bonus claimed'),
                            'message' => _i('The Bonus was successfully activate'),
                            'route' => route('users.details', [$user])
                        ];
                        $response = Utils::successResponse($data);
                    } else {
                        $data = [
                            'title' => _i('Bonus active'),
                            'message' => _i('The user has a bonus already active'),
                            'close' => _i('Close')
                        ];
                        $response = Utils::errorResponse(Codes::$forbidden, $data);
                    }

                } else {
                    $data = [
                        'title' => _i('Bonus inactive'),
                        'message' => _i('The bonus you are trying to activate is inactive'),
                        'route' => route('users.details', [$user])
                    ];
                    $response = Utils::errorResponse(Codes::$forbidden, $data);
                }
            } else {
                $data = [
                    'title' => _i('Bonus inactive'),
                    'message' => _i('The bonus you are trying to activate is inactive'),
                    'route' => route('users.details', [$user])
                ];
                $response = Utils::errorResponse(Codes::$forbidden, $data);
            }
            return $response;

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Get all campaigns
     *
     * @return Response
     */
    public function all()
    {
        try {
            $campaigns = $this->campaignsRepo->allByVersion();
            $this->campaignsCollection->formatAll($campaigns);
            $data = [
                'campaigns' => $campaigns
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Type providers
     *
     * @param Request $request
     * @return Response
     */
    public function allocationCriteriaTypes(Request $request)
    {
        try {
            $allocationCriteria = $request->allocation_criteria;
            $currency = $request->currency;
            $campaignsData = $this->campaignsRepo->getStatusAndAllocationCriteriaAndCurrency($allocationCriteria, $currency);
            if (!is_null($campaignsData)) {
                $data = [
                    'campaigns_data' => $campaignsData
                ];
            } else {
                $data = [
                    'campaigns_data' => []
                ];
            }
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Show view of all campaign
     *
     * @return Application|Factory|\Illuminate\Contracts\View\Viewç
     */
    public function campaigns()
    {
        try {
            $allocationCriteria = $this->allocationCriteriaRepo->all();
            $this->allocationCriteriaCollection->formatAll($allocationCriteria);
            $campaignsData = $this->campaignsRepo->all();
            $data['allocation_criteria'] = $allocationCriteria;
            $data['campaigns_data'] = $campaignsData;
            $data['title'] = _i('Campaigns overview');
            return view('back.bonus-system.reports.campaigns', $data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    /**
     * Get campaigns
     *
     * @param Request $request
     * @return Response
     */
    public function campaignsData(Request $request): Response
    {
        \Log::debug('request', [$request->all()]);
        if (count($request->all()) > 1) {
            $this->validate($request, [
                'allocation_criteria' => 'required',
                'convert' => 'required_without:currency'
            ]);
        }

        try {
            $allocationCriteria = $request->allocation_criteria;
            $convert = $request->convert;
            $campaign = $request->campaign_data;
            $startDate = !is_null($request->start_date) ? Utils::startOfDayUtc($request->start_date) : null;
            $endDate = !is_null($request->end_date) ? Utils::endOfDayUtc($request->end_date) : null;
            $currency = $request->currency;
            if (!is_null($allocationCriteria)) {
                $whitelabel = Configurations::getWhitelabel();
                $campaignsData = $this->campaignsRepo->getByAllocationCriteria($whitelabel, $allocationCriteria, $campaign, $currency);
            } else {
                $campaignsData = [];
            }
            $campaigns = $this->campaignsCollection->formatCampaignByCriteria($campaignsData, $convert, $startDate, $endDate);
            return Utils::successResponse($campaigns);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     * Show view campaign user participation
     *
     * @param CampaignParticipationStatusCollection $campaignParticipationStatusCollection
     * @return Application|Factory|View
     */
    public function campaignUserParticipation(CampaignParticipationStatusCollection $campaignParticipationStatusCollection)
    {
        try {
            $allocationCriteria = $this->allocationCriteriaRepo->all();
            $this->allocationCriteriaCollection->formatAll($allocationCriteria);
            $status = $this->campaignParticipationStatusRepo->all();
            $campaignParticipationStatusCollection->formatStatus($status);
            $campaignsData = $this->campaignsRepo->all();
            $data['campaigns_status'] = $status;
            $data['allocation_criteria'] = $allocationCriteria;
            $data['campaigns_data'] = $campaignsData;
            $data['title'] = _i('Campaigns by user');
            return view('back.bonus-system.reports.participation-by-users', $data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    /**
     * Get campaigns user participation data
     *
     * @return Response
     */
    public function campaignUserParticipationData(Request $request, $startDate = null, $endDate = null)
    {
        if (count($request->all()) > 1) {
            $this->validate($request, [
                'status' => 'required',
                'convert' => 'required_without:currency'
            ]);
        }

        try {
            $convert = $request->convert;
            $currency = $request->currency;
            $criteria = $request->allocation_criteria;
            $status = $request->status;
            $campaign = $request->campaign_data;
            if (!is_null($startDate) && !is_null($endDate)) {
                $startDate = Utils::startOfDayUtc($startDate);
                $endDate = Utils::endOfDayUtc($endDate);
                $whitelabel = Configurations::getWhitelabel();
                $usersData = $this->campaignParticipationDetailsRepo->getCampaignParticipation($startDate, $endDate, $whitelabel, $criteria, $currency, $status, $campaign);
            } else {
                $usersData = [];
            }
            $campaigns = $this->campaignsCollection->formatCampaignParticipation($usersData, $convert);
            return Utils::successResponse($campaigns);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'start_date' => $startDate, 'end_date' => $endDate]);
            return Utils::failedResponse();
        }
    }

    /**
     * Credit bonus for type
     *
     * @param object $campaignData Campaign Data
     * @param int $whitelabel Whitelabel ID
     * @param string $currency Currency ISO
     * @param int $user User ID
     * @param object $wallet Wallet data
     * @param string $accessToken Wallet token
     */
    private function creditBonus($campaignData, $whitelabel, $currency, $user, $wallet, $accessToken)
    {
        switch ($campaignData->allocation_criteria_id) {
            case AllocationCriteria::$welcome_bonus_with_deposit:
            {
                $userData = $this->usersRepo->find($user);
                $deposit = $userData->first_deposit_amount;
                $operator = auth()->user()->username;
                Bonus::welcomeBonusWithDeposit($campaignData->id, $whitelabel, $currency, $user, $deposit, $wallet, $accessToken, $operator);
                break;
            }
            case AllocationCriteria::$welcome_bonus_without_deposit:
            {
                Bonus::welcomeBonusWithoutDeposit($whitelabel, $currency, $user, $wallet, $accessToken, $campaignData->id);
                break;
            }
            case AllocationCriteria::$bonus_code:
            {
                $code = $campaignData->data->code;
                //Bonus::bonusCode($whitelabel, $currency, $user, $code, $wallet, $accessToken);
                break;
            }
        }
    }

    /**
     * Show campaign view
     *
     * @param int $id Campaign ID
     * @return Factory|\Illuminate\View\View
     */
    public function edit(Request $request, $id)
    {
        if (is_null($request->versions)) {
            $campaign = $this->campaignsRepo->find($id);
        } else {
            $campaign = $this->campaignsRepo->find($request->versions);
        }
        if (!is_null($campaign)) {
            try {
                $allocationCriteria = $this->allocationCriteriaRepo->all();
                $this->allocationCriteriaCollection->formatAll($allocationCriteria);
                $segments = $this->segmentsRepo->allByWhitelabel();
                $this->campaignsCollection->formatDetails($campaign);
                $providersTypes = [ProviderTypes::$casino, ProviderTypes::$live_casino, ProviderTypes::$virtual, ProviderTypes::$sportbook, ProviderTypes::$racebook, ProviderTypes::$live_games, ProviderTypes::$poker];
                $providerTypesData = $this->providersTypesRepo->getByWhitelabel($campaign->whitelabel_id, $campaign->currency_iso, $providersTypes);
                $this->campaignsCollection->formatTypeProviders($providerTypesData);
                $paymentMethods = BetPay::getClientPaymentMethods($campaign->currency_iso);
                $paymentMethodsData = $this->paymentMethodsCollection->fomartByProviderAndCurrency($paymentMethods);
                if (is_null($campaign->original_campaign) && is_null($request->versions)) {
                    $campaignVersions = $this->campaignsRepo->getVersions($id);
                    $maxVersion = null;
                } elseif (is_null($campaign->original_campaign) && !is_null($request->versions)) {
                    $campaignVersions = $this->campaignsRepo->getVersions($request->versions);
                    $maxVersion = null;
                } else {
                    $campaignVersions = $this->campaignsRepo->getVersions($campaign->original_campaign);
                    $maxVersion = $this->campaignsRepo->getMaxByOriginalCampaign($campaign->original_campaign);
                }
                $campaignVersionsData = $this->campaignsCollection->formatVersion($campaignVersions);
                $data['versions'] = $campaignVersionsData;
                $data['provider_types'] = $providerTypesData;
                if (is_null($request->versions)) {
                    $data['rollovers'] = $this->rolloversTypesRepo->getByCampaign($id);
                } else {
                    $data['rollovers'] = $this->rolloversTypesRepo->getByCampaign($request->versions);
                }
                $data['allocation_criteria'] = $allocationCriteria;
                $data['segments'] = $segments;
                $data['campaign'] = $campaign;
                $data['payment_methods'] = $paymentMethodsData;
                $data['title'] = _i('Update campaign');
                if (is_null($request->versions)) {
                    return view('back.bonus-system.campaigns.edit', $data);
                } elseif (!is_null($maxVersion) && $maxVersion->id == $request->versions) {
                    return view('back.bonus-system.campaigns.edit', $data);
                } else {
                    return view('back.bonus-system.campaigns.view', $data);
                }

            } catch (\Exception $ex) {
                \Log::error(__METHOD__, ['exception' => $ex, 'slider' => $id]);
                abort(500);
            }
        } else {
            abort(404);
        }
    }

    /**
     * Show campaign view
     *
     * @param int $id Campaign ID
     * @return Factory|\Illuminate\View\View
     */
    public function editUsersSearch($id)
    {
        $campaign = $this->campaignsRepo->find($id);

        if (!is_null($campaign)) {
            try {
                $segments = $this->segmentsRepo->allByWhitelabel();
                $data['campaign'] = $campaign;
                $data['segments'] = $segments;
                $data['title'] = _i('Update users search');
                return view('back.bonus-system.campaigns.edit-users', $data);

            } catch (\Exception $ex) {
                \Log::error(__METHOD__, ['exception' => $ex, 'campaign' => $id]);
                abort(500);
            }
        } else {
            abort(404);
        }
    }

    /**
     * Add users of search
     *
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function addUsersSearch($id, Request $request)
    {
        try {
            $campaign = $this->campaignsRepo->find($id);
            $includeUsersAdd = $request->include_user;
            $excludeUsersAdd = $request->exclude_user;
            $searchType = $request->user_search_type;
            if (!is_null($campaign)) {
                $campaignData = $campaign->data;
                if (!is_null($includeUsersAdd)) {
                    $users = [];
                    foreach ($includeUsersAdd as $item) {
                        $users[] = (int)$item;
                    }
                    if (isset($campaignData->include_users) && !is_null($campaignData->include_users)) {
                        foreach ($campaignData->include_users as $includeUsersData) {
                            $users[] = $includeUsersData;
                        }
                    }
                    $includeUsers = $users;
                } else {
                    if (isset($campaignData->include_users) && !is_null($campaignData->include_users)) {
                        $includeUsers = $campaignData->include_users;
                    }
                }

                if (!is_null($excludeUsersAdd)) {
                    $usersIdsExclude = [];
                    foreach ($excludeUsersAdd as $item) {
                        $usersIdsExclude[] = (int)$item;
                    }
                    if (isset($campaignData->exclude_users) && !is_null($campaignData->exclude_users)) {
                        foreach ($campaignData->include_users as $excludeUsersData) {
                            $users[] = $excludeUsersData;
                        }
                    }
                    $excludeUsers = $usersIdsExclude;
                } else {
                    if (isset($campaignData->exclude_users) && !is_null($campaignData->exclude_users)) {
                        $excludeUsers = $campaignData->exclude_users;
                    }
                }
                $campaignData['include_users'] = $includeUsers;
                $campaignData['exclude_users'] = $excludeUsers;
                $campaignData['include_segments'] = null;
                $campaignData['exclude_segments'] = null;
                $campaignData['user_search_type'] = $searchType;
            }

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'id' => $id]);
            return Utils::failedResponse();
        }
    }

    /**
     * Remove users of campaign
     *
     * @param int $id Post ID
     * @return Response
     */
    public function removeUsersOfCampaign(Request $request, $id)
    {
        try {
            $campaign = $this->campaignsRepo->find($id);
            $includeUsersAdd = $request->include_user;
            $excludeUsersAdd = $request->exclude_user;
            $searchType = $request->user_search_type;
            if (!is_null($campaign)) {
                $campaignData = $campaign->data;
                if (!is_null($includeUsersAdd)) {
                    $users = [];
                    foreach ($includeUsersAdd as $item) {
                        $users[] = (int)$item;
                    }
                    if (isset($campaignData->include_users) && !is_null($campaignData->include_users)) {
                        foreach ($campaignData->include_users as $includeUsersData) {
                            if ($includeUsersData) {
                                $users[] = $includeUsersData;
                            }
                        }
                    }
                    $includeUsers = $users;
                } else {
                    if (isset($campaignData->include_users) && !is_null($campaignData->include_users)) {
                        $includeUsers = $campaignData->include_users;
                    }
                }

                if (!is_null($excludeUsersAdd)) {
                    $usersIdsExclude = [];
                    foreach ($excludeUsersAdd as $item) {
                        $usersIdsExclude[] = (int)$item;
                        if (isset($campaignData->exclude_users) && !is_null($campaignData->exclude_users)) {
                            foreach ($campaignData->include_users as $excludeUsersData) {
                                $users[] = $excludeUsersData;
                            }
                        }
                    }
                    $excludeUsers = $usersIdsExclude;
                } else {
                    if (isset($campaignData->exclude_users) && !is_null($campaignData->exclude_users)) {
                        $excludeUsers = $campaignData->exclude_users;
                    }
                }
                $campaignData['include_users'] = $includeUsers;
                $campaignData['exclude_users'] = $excludeUsers;
                $campaignData['include_segments'] = null;
                $campaignData['exclude_segments'] = null;
                $campaignData['user_search_type'] = $searchType;
            }


        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'id' => $id]);
            return Utils::failedResponse();
        }
    }

    /**
     * Get include users
     *
     * @param int $id Post ID
     * @return Response
     */
    public function getIncludeUsers($id)
    {
        try {
            $campaign = $this->campaignsRepo->find($id);
            $campaignData = $campaign->data;
            if (!is_null($campaignData->includeUsers)) {
                $usersData = $this->usersRepo->getByIDs($campaignData->includeUsers);
                $data = $this->campaignsCollection->formatUsers($usersData, $id);
                return Utils::successResponse($data);
            } else {
                $data = [];
            }
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'id' => $id]);
            return Utils::failedResponse();
        }
    }

    /**
     * Get segments of search
     *
     * @param int $id Post ID
     * @return Response
     */
    public function getIncludeSegments($id)
    {
        try {
            $campaign = $this->campaignsRepo->find($id);
            $campaignData = $campaign->data;
            if (!is_null($campaignData->include_segments)) {
                $segmentsData = $this->segmentsRepo->getByIDs($campaignData->include_segments);
                return Utils::successResponse($segmentsData);
            } else {
                $data = [];
            }
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'id' => $id]);
            return Utils::failedResponse();
        }
    }

    /**
     * Show create view
     *
     * @return Factory|\Illuminate\View\View
     */
    public function create()
    {
        try {
            $allocationCriteria = $this->allocationCriteriaRepo->all();
            $this->allocationCriteriaCollection->formatAll($allocationCriteria);
            $segments = $this->segmentsRepo->allByWhitelabel();
            $data['allocation_criteria'] = $allocationCriteria;
            $data['segments'] = $segments;
            $data['title'] = _i('New campaign');
            return view('back.bonus-system.campaigns.create', $data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    /**
     * Show create view
     *
     * @return Factory|\Illuminate\View\View
     */
    public function createNew()
    {
        try {
            $allocationCriteria = $this->allocationCriteriaRepo->all();
            $this->allocationCriteriaCollection->formatAll($allocationCriteria);
            $segments = $this->segmentsRepo->allByWhitelabel();
            $data['allocation_criteria'] = $allocationCriteria;
            $data['segments'] = $segments;
            $data['title'] = _i('New campaign');
            return view('back.bonus-system.campaigns.create-new', $data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    /**
     * Delete campaign
     *
     * @param int $id Post ID
     * @return Response
     */
    public function delete($id, RolloversRepo $rolloversRepo)
    {
        try {
            $campaignUsers = $this->campaignParticipationRepo->getByCampaign($id);
            $rolloversTypeData = $this->rolloversTypesRepo->getByCampaign($id);
            $campaign = $this->campaignsRepo->find($id);

            if (!is_null($campaign)) {
                if (!is_null($rolloversTypeData)) {
                    $users = [];
                    foreach ($campaignUsers as $user) {
                        $users[] = $user->user_id;
                    }

                    if (!empty($users)) {
                        $wallet = Wallet::clearWallets($users, $campaign->currency_iso, $rolloversTypeData->provider_type_id);

                        if ($wallet->status == \Dotworkers\Configurations\Enums\Status::$ok) {
                            $this->campaignsRepo->delete($id);
                            if (!is_null($campaign->original_campaign)) {
                                $statusData = [
                                    'status' => false,
                                    'deleted_at' => Carbon::now(),
                                ];
                                $this->campaignsRepo->updateStatusAndDelete($campaign->original_campaign, $statusData);
                                $this->campaignsRepo->deleteOriginal($campaign->original_campaign, $statusData);
                            }
                        }
                    } else {
                        $this->campaignsRepo->delete($id);
                        if (!is_null($campaign->original_campaign)) {
                            $statusData = [
                                'status' => false,
                                'deleted_at' => Carbon::now(),
                            ];
                            $this->campaignsRepo->updateStatusAndDelete($campaign->original_campaign, $statusData);
                            $this->campaignsRepo->deleteOriginal($campaign->original_campaign, $statusData);
                        }
                    }
                } else {
                    $this->campaignsRepo->delete($id);
                    if (!is_null($campaign->original_campaign)) {
                        $statusData = [
                            'status' => false,
                            'deleted_at' => Carbon::now(),
                        ];
                        $this->campaignsRepo->updateStatusAndDelete($campaign->original_campaign, $statusData);
                        $this->campaignsRepo->deleteOriginal($campaign->original_campaign, $statusData);
                    }
                }
                $data = [
                    'title' => _i('Campaign removed'),
                    'message' => _i('The campaign was successfully removed'),
                    'close' => _i('Close')
                ];
                return Utils::successResponse($data);
            } else {
                $data = [
                    'title' => _i('Campaign not found'),
                    'message' => _i('The campaign you are trying to deleted is not found'),
                    'close' => _i('Close')
                ];
                return Utils::errorResponse(Codes::$forbidden, $data);
            }

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'id' => $id]);
            return Utils::failedResponse();
        }
    }

    /**
     * Exclude providers
     *
     * @param Request $request
     * @return Response
     */
    public function excludeProviders(Request $request)
    {
        try {
            $providers = $request->providers;
            $excludeProviders = [];
            if (!is_null($providers)) {
                $excludeProviders = $this->providersRepo->getByTypes([$providers]);
            }
            $data = [
                'exclude_providers' => $excludeProviders
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Get payment methods by currency
     *
     * @param Request $request
     * @return Response
     */
    public function paymentMethds(Request $request)
    {
        try {
            $currency = $request->currency;
            $paymentMethods = BetPay::getClientPaymentMethods($currency);
            $paymentMethodsData = $this->paymentMethodsCollection->fomartByProviderAndCurrency($paymentMethods);
            $data = [
                'payment_methods' => $paymentMethodsData
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Type providers
     *
     * @param Request $request
     * @return Response
     */
    public function providerTypes(Request $request)
    {
        try {
            $currency = $request->currency;
            $whitelabel = Configurations::getWhitelabel();
            $providersTypes = [ProviderTypes::$casino, ProviderTypes::$live_casino, ProviderTypes::$virtual, ProviderTypes::$sportbook, ProviderTypes::$racebook, ProviderTypes::$live_games, ProviderTypes::$poker];
            $providerTypesData = $this->providersTypesRepo->getByWhitelabel($whitelabel, $currency, $providersTypes);
            $this->campaignsCollection->formatTypeProviders($providerTypesData);
            $data = [
                'provider_types' => $providerTypesData
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }


    /**
     * List campaign for user to remover
     *
     * @param int $user User ID
     * @param int $wllet Wallet ID
     * @return Response
     */
    public function removerUser($user, $wallet)
    {
        try {
            $campaigns = $this->campaignsUsersRepo->getByUser($user);
            $this->campaignsCollection->formatCampaignUser($campaigns, $user, $wallet);
            $data = [
                'campaigns' => $campaigns
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Remove user for campaign
     *
     * @param int $id Campaign ID
     * @param int $user User ID
     * @param RolloversRepo $rolloversRepo
     * @return Response
     */
    public function removerUserData($id, $user)
    {
        try {
            $campaign = $this->campaignsRepo->find($id);

            if (!is_null($campaign)) {
                $currency = session('currency');
                $whitelabel = Configurations::getWhitelabel();
                Rollovers::cancelRollovers($campaign->id, $user, $currency, RolloverStatus::$cancelled, CampaignParticipationStatus::$canceled_by_user, $whitelabel);
                $data = [
                    'title' => _i('Bonus canceled'),
                    'message' => _i('The bonus was successfully canceled'),
                    'close' => _i('Close')
                ];
                return Utils::successResponse($data);
            } else {
                $data = [
                    'title' => _i('Bonus not found'),
                    'message' => _i('The bonus you are trying to canceled is not found'),
                ];
                return Utils::errorResponse(Codes::$forbidden, $data);
            }

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Manual adjustments
     *
     * @param Request $request
     * @param TransactionsRepo $transactionsRepo
     * @return Response
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

            $additionalData = [
                'description' => $description,
                'operator' => $operator,
                'provider_transaction' => Str::uuid()->toString(),
            ];
            if ($transactionType == TransactionTypes::$credit) {
                $transaction = Wallet::creditManualTransactions($amount, Providers::$bonus, $additionalData, $wallet);
            } else {
                $transaction = Wallet::debitManualTransactions($amount, Providers::$bonus, $additionalData, $wallet);
            }

            if ($transaction->status == \Dotworkers\Configurations\Enums\Status::$ok) {
                $transactionData = [
                    'user_id' => $user,
                    'amount' => $amount,
                    'currency_iso' => $transaction->data->wallet->currency_iso,
                    'transaction_type_id' => $transactionType,
                    'transaction_status_id' => TransactionStatus::$approved,
                    'provider_id' => Providers::$bonus,
                    'data' => $additionalData,
                    'whitelabel_id' => Configurations::getWhitelabel()
                ];
                $additionalData['wallet_transaction'] = $transaction->data->transaction->id;
                $detailsData = [
                    'data' => json_encode($additionalData)
                ];
                $transactionsRepo->store($transactionData, TransactionStatus::$approved, $detailsData);
                $data = [
                    'title' => _i('Transaction performed'),
                    'message' => _i('The transaction was successfully made to the user'),
                    'close' => _i('Close'),
                    'route' => route('users.details', [$user])
                ];
                return Utils::successResponse($data);
            } else {
                return Utils::failedResponse();
            }

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Store campaign
     *
     * @param Request $request
     *
     * @return Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $rules = [
            'internal_name' => 'required',
            'start_date' => 'required',
            'complete_profile' => 'required',
            'currency' => 'required',
            'allocation_criteria' => 'required',
            'max_balance_convert' => 'nullable|numeric',
            'bonus' => [
                'nullable',
                'required_if:allocation_criteria,' . AllocationCriteria::$bonus_code,
                'required_if:allocation_criteria,' . AllocationCriteria::$welcome_bonus_without_deposit,
                'required_if:bonus_type,1',
                'numeric',
                'gt:0'
            ],
            'bonus_code' => [
                'nullable',
                'required_if:allocation_criteria,' . AllocationCriteria::$bonus_code,
                'required_if:allocation_criteria,' . AllocationCriteria::$bonus_code_with_deposit,
                'alpha_dash'
            ],
            'bonus_type' => [
                'required_if:allocation_criteria,' . AllocationCriteria::$bonus_code_with_deposit,
                'required_if:allocation_criteria,' . AllocationCriteria::$welcome_bonus_with_deposit,
                'required_if:allocation_criteria,' . AllocationCriteria::$next_deposit_bonus,
            ],
            'percentage' => 'required_if:bonus_type,2',
            'limit' => 'required_if:bonus_type,2',
            'min_deposit' => [
                'nullable',
                'required_if:allocation_criteria,' . AllocationCriteria::$bonus_code_with_deposit,
                'required_if:allocation_criteria,' . AllocationCriteria::$welcome_bonus_with_deposit,
                'required_if:allocation_criteria,' . AllocationCriteria::$next_deposit_bonus,
                'numeric',
                'gt:0'
            ],
//            'deposits_quantity' => [
//                'nullable',
//                'required_if:allocation_criteria,' . AllocationCriteria::$bonus_code_with_deposit,
//                'integer',
//                'gt:0'
//            ],
            'complete_rollovers' => 'required',
            'include_deposit' => 'required_if:complete_rollovers,true',
            'provider_type' => 'required_if:complete_rollovers,true',
            'multiplier' => [
                'nullable',
                'required_if:complete_rollovers,true',
                'integer',
                'gt:0'
            ],
            'days' => [
                'nullable',
                'required_if:complete_rollovers,true',
                'integer',
                'gt:0'
            ],
            'odd' => [
                'nullable',
                'required_if:provider_type,' . ProviderTypes::$sportbook,
                'numeric',
                'gt:0'
            ]
        ];
        $this->validate($request, $rules);

        try {
            $whitelabel = Configurations::getWhitelabel();
            $timezone = session('timezone');
            $configData['complete_profile'] = $request->complete_profile == 'true';
            $completeRollovers = $request->complete_rollovers == 'yes';
            $usersRestriction = $request->users_restriction_type;

            if (!is_null($request->bonus_code)) {
                $configData['code'] = strtoupper($request->bonus_code);
            }

            if (!is_null($request->bonus_type)) {
                $configData['bonus_type'] = $request->bonus_type;
            }

            if (!is_null($request->bonus)) {
                $configData['amount'] = (float)$request->bonus;
            }

            if (!is_null($request->percentage)) {
                $configData['percentage'] = $request->percentage / 100;
                $configData['limit'] = (float)$request->limit;
            }

            $configData['max_balance_convert'] = !is_null($request->max_balance_convert) ? (float)$request->max_balance_convert : null;

            if (!is_null($request->min_deposit)) {
                $configData['min'] = (float)$request->min_deposit;
            }

            if (!is_null($request->deposits_quantity)) {
                $configData['deposit_options'] = (string)$request->deposit_options;
                $configData['deposits_quantity'] = (int)$request->deposits_quantity;
            }

            $promoCodesData = [];
            $promoCodes = $request->promo_codes;
            $campaignsWithPromoCodes = $this->campaignsRepo->getWithPromoCodes($whitelabel);
            foreach ($promoCodes as $promoCode) {
                if (!is_null($promoCode['promo_code'])) {
                    $code = strtoupper($promoCode['promo_code']);

                    foreach ($campaignsWithPromoCodes as $campaign) {
                        foreach ($campaign->data->promo_codes as $campaignCode) {
                            if ($code == $campaignCode->promo_code) {
                                $data = [
                                    'title' => _i('The given data was invalid'),
                                    'message' => _i('The promo code %s is assigned to another campaign', $code),
                                    'close' => _i('Close')
                                ];
                                return Utils::errorResponse(Codes::$forbidden, $data);
                            }
                        }
                    }
                    $promoCode['promo_code'] = $code;
                    $promoCodesData[] = $promoCode;
                }
            }

            $configData['promo_codes'] = $promoCodesData;
            $configData['rollovers'] = $completeRollovers;

            $includeUsers = null;
            $excludeUsers = null;
            $includeSegments = null;
            $excludeSegments = null;

            if (!is_null($usersRestriction)) {
                $configData['users_restriction_type'] = $usersRestriction;

                switch ($usersRestriction) {
                    case 'users':
                    {
                        if (!is_null($request->include_users)) {
                            $includeUsers = array_map(function ($item) {
                                return (int)$item;
                            }, $request->include_users);
                        }

                        if (!is_null($request->exclude_users)) {
                            $excludeUsers = array_map(function ($item) {
                                return (int)$item;
                            }, $request->exclude_users);
                        }
                        $configData['include_users'] = $includeUsers;
                        $configData['exclude_users'] = $excludeUsers;
                        break;
                    }
                    case 'segments':
                    {
                        if (!is_null($request->include_segments)) {
                            $includeSegments = array_map(function ($item) {
                                return (int)$item;
                            }, $request->include_segments);
                        }

                        if (!is_null($request->exclude_segments)) {
                            $excludeSegments = array_map(function ($item) {
                                return (int)$item;
                            }, $request->exclude_segments);
                        }
                        $configData['include_segments'] = $includeSegments;
                        $configData['exclude_segments'] = $excludeSegments;
                        break;
                    }
                    case 'excel':
                    {
                        if (!is_null($request->include_excel)) {
                            $excel = $request->file('include_excel');
                            $import = new ImportUsers();
                            $import->import($excel);
                            $excelUsers = $import->data;

                            foreach ($excelUsers as $user) {
                                $username = strtolower($user->username);
                                $userData = $this->usersRepo->getByUsername($username, $whitelabel);
                                if (!is_null($userData)) {
                                    $includeUsers[] = $userData->id;
                                }
                            }
                        }

                        if (!is_null($request->exclude_excel)) {
                            $excel = $request->file('exclude_excel');
                            $import = new ImportUsers();
                            $import->import($excel);
                            $excelUsers = $import->data;

                            foreach ($excelUsers as $user) {
                                $username = strtolower($user->username);
                                $userData = $this->usersRepo->getByUsername($username, $whitelabel);
                                if (!is_null($userData)) {
                                    $excludeUsers[] = $userData->id;
                                }
                            }
                        }
                        $configData['include_users'] = $includeUsers;
                        $configData['exclude_users'] = $excludeUsers;
                        break;
                    }
                }
            }

            $includePaymentMethods = null;
            $excludePaymentMethods = null;
            if (!is_null($request->include_payment_methods) || !is_null($request->exclude_payment_methods)) {
                if (!is_null($request->include_payment_methods)) {
                    $includePaymentMethods = array_map(function ($item) {
                        return (int)$item;
                    }, $request->include_payment_methods);
                }

                if (!is_null($request->exclude_payment_methods)) {
                    $excludePaymentMethods = array_map(function ($item) {
                        return (int)$item;
                    }, $request->exclude_payment_methods);
                }
                $configData['include_payment_methods'] = $includePaymentMethods;
                $configData['exclude_payment_methods'] = $excludePaymentMethods;
            }

            if (!is_null($request->odd)) {
                if ($request->bet_type == 'both') {
                    $configData['simple'] = null;
                } else {
                    $configData['simple'] = $request->bet_type == 'simple';
                }
                $configData['odd'] = (float)$request->odd;
            }

            $campaignData = [
                'whitelabel_id' => $whitelabel,
                'name' => $request->internal_name,
                'data' => $configData,
                'device' => 1,
                'start_date' => Carbon::createFromFormat('d-m-Y h:i a', $request->start_date, $timezone)->setTimezone('UTC')->format('Y-m-d H:i:s'),
                'end_date' => !is_null($request->end_date) ? Carbon::createFromFormat('d-m-Y h:i a', $request->end_date, $timezone)->setTimezone('UTC')->format('Y-m-d H:i:s') : null,
                'currency_iso' => $request->currency,
                'status' => $request->status,
                'allocation_criteria_id' => $request->allocation_criteria,
                'translations' => json_decode($request->translations)
            ];
            $campaign = $this->campaignsRepo->store($campaignData);

            if ($completeRollovers) {
                $rolloverData = [
                    'multiplier' => (int)$request->multiplier,
                    'campaign_id' => $campaign->id,
                    'provider_type_id' => $request->provider_type,
                    'days' => (int)$request->days
                ];

                if ($request->include_deposit == 'both') {
                    $rolloverData['include_deposit'] = null;
                } else {
                    $rolloverData['include_deposit'] = $request->include_deposit == 'yes';
                }


                if (!is_null($request->exclude_providers)) {
                    foreach ($request->exclude_providers as $provider) {
                        $excludeProviders[] = (int)$provider;
                    }
                } else {
                    $excludeProviders = null;
                }
                $rolloverData['exclude_providers'] = $excludeProviders;
                $this->rolloversTypesRepo->store($rolloverData);
            }

            $data = [
                'title' => _i('Campaign published'),
                'message' => _i('The campaign was published correctly'),
                'close' => _i('Close')
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     * Show campaigns list
     *
     * @return Factory|\Illuminate\View\View
     */
    public function index()
    {
        $data['title'] = _i('List of campaigns');
        return view('back.bonus-system.campaigns.index', $data);
    }

    /**
     * Update campaign
     *
     * @param Request $request
     *
     * @return Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request)
    {
        $rules = [
            'internal_name' => 'required',
            'start_date' => 'required',
            'complete_profile' => 'required',
            'currency' => 'required',
            //'allocation_criteria' => 'required',
            'max_balance_convert' => 'nullable|numeric',
            'bonus' => [
                'nullable',
                'required_if:allocation_criteria,' . AllocationCriteria::$bonus_code,
                'required_if:allocation_criteria,' . AllocationCriteria::$welcome_bonus_without_deposit,
                //'required_if:bonus_type,1',
                'numeric',
                'gt:0'
            ],
            'bonus_code' => [
                'nullable',
                'required_if:allocation_criteria,' . AllocationCriteria::$bonus_code,
                'required_if:allocation_criteria,' . AllocationCriteria::$bonus_code_with_deposit,
                'alpha_dash'
            ],
            'bonus_type' => [
                'required_if:allocation_criteria,' . AllocationCriteria::$bonus_code_with_deposit,
                'required_if:allocation_criteria,' . AllocationCriteria::$welcome_bonus_with_deposit,
                'required_if:allocation_criteria,' . AllocationCriteria::$next_deposit_bonus,
            ],
            'percentage' => 'required_if:bonus_type,2',
            'limit' => 'required_if:bonus_type,2',
            'min_deposit' => [
                'nullable',
                'required_if:allocation_criteria,' . AllocationCriteria::$bonus_code_with_deposit,
                'required_if:allocation_criteria,' . AllocationCriteria::$welcome_bonus_with_deposit,
                'required_if:allocation_criteria,' . AllocationCriteria::$next_deposit_bonus,
                'numeric',
                'gt:0'
            ],
//            'deposits_quantity' => [
//                'nullable',
//                'required_if:allocation_criteria,' . AllocationCriteria::$bonus_code_with_deposit,
//                'integer',
//                'gt:0'
//            ],
            'complete_rollovers' => 'required',
            'include_deposit' => 'required_if:complete_rollovers,true',
            'provider_type' => 'required_if:complete_rollovers,true',
            'multiplier' => [
                'nullable',
                'required_if:complete_rollovers,true',
                'integer',
                'gt:0'
            ],
            'days' => [
                'nullable',
                'required_if:complete_rollovers,true',
                'integer',
                'gt:0'
            ],
            'odd' => [
                'nullable',
                'required_if:provider_type,' . ProviderTypes::$sportbook,
                'numeric',
                'gt:0'
            ]
        ];
        $this->validate($request, $rules);

        try {
            $version = Version::campaignVersion($request);
            Version::withoutVersion($request);
            $id = $request->id;
            $parent = $request->parent_campaign;
            $whitelabel = Configurations::getWhitelabel();
            $timezone = session('timezone');
            $configData['complete_profile'] = $request->complete_profile == 'true';
            $completeRollovers = $request->complete_rollovers == 'yes';
            $usersRestriction = $request->users_restriction_type;

            if (!is_null($request->bonus_code)) {
                $configData['code'] = strtoupper($request->bonus_code);
            }

            if (!is_null($request->bonus_type)) {
                $configData['bonus_type'] = $request->bonus_type;
            }

            if (!is_null($request->bonus)) {
                $configData['amount'] = (float)$request->bonus;
            }

            if (!is_null($request->percentage)) {
                $configData['percentage'] = $request->percentage / 100;
                $configData['limit'] = (float)$request->limit;
            }

            $configData['max_balance_convert'] = !is_null($request->max_balance_convert) ? (float)$request->max_balance_convert : null;

            if (!is_null($request->min_deposit)) {
                $configData['min'] = (float)$request->min_deposit;
            }

            if (!is_null($request->deposits_quantity)) {
                $configData['deposit_options'] = (string)$request->deposit_options;
                $configData['deposits_quantity'] = (int)$request->deposits_quantity;
            }

            $promoCodesData = [];
            $promoCodes = $request->promo_codes;
            if (!is_null($promoCodes)) {
                $campaignsWithPromoCodes = $this->campaignsRepo->getWithPromoCodes($whitelabel, $id, $parent);
                foreach ($promoCodes as $promoCode) {
                    if (!is_null($promoCode['promo_code'])) {
                        $code = strtoupper($promoCode['promo_code']);

                        foreach ($campaignsWithPromoCodes as $campaign) {
                            foreach ($campaign->data->promo_codes as $campaignCode) {
                                if ($code == $campaignCode->promo_code) {
                                    $data = [
                                        'title' => _i('The given data was invalid'),
                                        'message' => _i('The promo code %s is assigned to another campaign', $code),
                                        'close' => _i('Close')
                                    ];
                                    return Utils::errorResponse(Codes::$forbidden, $data);
                                }
                            }
                        }
                        $promoCode['promo_code'] = $code;
                        $promoCodesData[] = $promoCode;
                    }
                }
            }

            $configData['promo_codes'] = $promoCodesData;

            $configData['rollovers'] = $completeRollovers;

            $includeUsers = null;
            $excludeUsers = null;
            $includeSegments = null;
            $excludeSegments = null;

            if (!is_null($usersRestriction)) {
                $configData['users_restriction_type'] = $usersRestriction;

                switch ($usersRestriction) {
                    case 'users':
                    {
                        if (!is_null($request->include_users)) {
                            $includeUsers = array_map(function ($item) {
                                return (int)$item;
                            }, $request->include_users);
                        }

                        if (!is_null($request->exclude_users)) {
                            $excludeUsers = array_map(function ($item) {
                                return (int)$item;
                            }, $request->exclude_users);
                        }
                        $configData['include_users'] = $includeUsers;
                        $configData['exclude_users'] = $excludeUsers;
                        break;
                    }
                    case 'segments':
                    {
                        if (!is_null($request->include_segments)) {
                            $includeSegments = array_map(function ($item) {
                                return (int)$item;
                            }, $request->include_segments);
                        }

                        if (!is_null($request->exclude_segments)) {
                            $excludeSegments = array_map(function ($item) {
                                return (int)$item;
                            }, $request->exclude_segments);
                        }
                        $configData['include_segments'] = $includeSegments;
                        $configData['exclude_segments'] = $excludeSegments;
                        break;
                    }
                    case 'excel':
                    {
                        if (!is_null($request->include_excel)) {
                            $excel = $request->file('include_excel');
                            $import = new ImportUsers();
                            $import->import($excel);
                            $excelUsers = $import->data;

                            foreach ($excelUsers as $user) {
                                $username = strtolower($user->username);
                                $userData = $this->usersRepo->getByUsername($username, $whitelabel);
                                if (!is_null($userData)) {
                                    $includeUsers[] = $userData->id;
                                }
                            }
                        }

                        if (!is_null($request->exclude_excel)) {
                            $excel = $request->file('exclude_excel');
                            $import = new ImportUsers();
                            $import->import($excel);
                            $excelUsers = $import->data;

                            foreach ($excelUsers as $user) {
                                $username = strtolower($user->username);
                                $userData = $this->usersRepo->getByUsername($username, $whitelabel);
                                if (!is_null($userData)) {
                                    $excludeUsers[] = $userData->id;
                                }
                            }
                        }
                        $configData['include_users'] = $includeUsers;
                        $configData['exclude_users'] = $excludeUsers;
                        break;
                    }
                }
            }

            $includePaymentMethods = null;
            $excludePaymentMethods = null;
            if (!is_null($request->include_payment_methods) || !is_null($request->exclude_payment_methods)) {
                if (!is_null($request->include_payment_methods)) {
                    $includePaymentMethods = array_map(function ($item) {
                        return (int)$item;
                    }, $request->include_payment_methods);
                }

                if (!is_null($request->exclude_payment_methods)) {
                    $excludePaymentMethods = array_map(function ($item) {
                        return (int)$item;
                    }, $request->exclude_payment_methods);
                }
                $configData['include_payment_methods'] = $includePaymentMethods;
                $configData['exclude_payment_methods'] = $excludePaymentMethods;
            }

            if (!is_null($request->odd)) {
                if ($request->bet_type == 'both') {
                    $configData['simple'] = null;
                } else {
                    $configData['simple'] = (bool)$request->bet_type == 'simple';
                }
                $configData['odd'] = (float)$request->odd;
            }

            $campaignData = [
                'name' => $request->internal_name,
                'data' => $configData,
                'start_date' => Carbon::createFromFormat('d-m-Y h:i a', $request->start_date, $timezone)->setTimezone('UTC')->format('Y-m-d H:i:s'),
                'end_date' => !is_null($request->end_date) ? Carbon::createFromFormat('d-m-Y h:i a', $request->end_date, $timezone)->setTimezone('UTC')->format('Y-m-d H:i:s') : null,
                'currency_iso' => $request->currency,
                'status' => $request->status,
                'allocation_criteria_id' => $request->allocation_criteria_select,
                'translations' => json_decode($request->translations)
            ];

            if (!is_null($request->original_campaign)) {
                $campaignData['original_campaign'] = $request->original_campaign;
            } else {
                $campaignData['original_campaign'] = $id;
            }
            $campaignData['device'] = 1;
            $campaignData['whitelabel_id'] = Configurations::getWhitelabel();
            $newVersion = $request->version + 1;
            $campaignData['version'] = $newVersion;
            $campaignData['parent_campaign'] = $id;
            $versionDuplicate = $this->campaignsRepo->getVersionDuplicate($campaignData['original_campaign'], $newVersion);

            if ($version) {
                if (is_null($versionDuplicate)) {
                    $campaignData['parent_campaign'] = $id;
                    $campaignId = $this->campaignsRepo->store($campaignData);
                } else {
                    $campaignData['parent_campaign'] = $versionDuplicate->id;
                    $campaignData['version'] = $newVersion + 1;
                    $campaignId = $this->campaignsRepo->store($campaignData);
                }
            } else {
                $this->campaignsRepo->update($id, $campaignData);
            }

            if ($completeRollovers) {
                $rolloverData = [
                    'multiplier' => (int)$request->multiplier,
                    'provider_type_id' => $request->provider_type,
                    'days' => (int)$request->days
                ];

                if ($request->include_deposit == 'both') {
                    $rolloverData['include_deposit'] = null;
                } else {
                    $rolloverData['include_deposit'] = $request->include_deposit == 'yes';
                }

                $excludeProviders = null;
                if (!is_null($request->exclude_providers)) {
                    $excludeProviders = array_map(function ($item) {
                        return (int)$item;
                    }, $request->exclude_providers);
                }
                $rolloverData['exclude_providers'] = $excludeProviders;
                if ($version) {
                    $rolloverData['campaign_id'] = $campaignId->id;
                    $this->rolloversTypesRepo->store($rolloverData);
                } else {
                    $rolloverData['campaign_id'] = $id;
                    $this->rolloversTypesRepo->update($request->rollover_id, $rolloverData);
                }
            }

            $data = [
                'title' => _i('Campaign updated'),
                'message' => _i('The campaign data was updated correctly'),
                'close' => _i('Close'),
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }
}

<?php


namespace App\Http\Controllers;

use App\Core\Repositories\ProvidersRepo;
use App\Core\Repositories\ProvidersTypesRepo;
use App\Core\Collections\ProviderTypesCollection;
use App\Store\Collections\StoreCollection;
use App\Store\Collections\ActionsCollection;
use App\Store\Repositories\ActionsConfigurationsRepo;
use App\Store\Repositories\ActionsRepo;
use App\Store\Repositories\ActionsTypeRepo;
use App\Store\Repositories\StoreExchangesRepo;
use App\Store\Repositories\StoreRewardsRepo;
use App\Store\Repositories\RewardsCategoriesRepo;
use Carbon\Carbon;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Configurations\Enums\ProviderTypes;
use Dotworkers\Configurations\Utils;
use Dotworkers\Store\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Audits\Repositories\AuditsRepo;
use App\Audits\Enums\AuditTypes;
use Dotworkers\Audits\Audits;


/**
 * Class StoreController
 *
 * This class allows to manage store requests
 *
 * @package App\Http\Controllers
 * @author  Damelys Espinoza
 */
class StoreController extends Controller
{

    /**
     * @var AuditsRepo
     */
    private $auditsRepo;

    /**
     * File path
     *
     * @var string
     */
    private $filePath;

    /**
     * StoreRewardsRepo
     *
     * @var StoreRewardsRepo
     */
    private $storeRewardsRepo;

    /**
     * StoreExchangesRepo
     *
     * @var StoreExchangesRepo
     */
    private $storeExchangesRepo;

    /**
     * StoreCollection
     *
     * @var StoreCollection
     */
    private $storeCollection;

    /**
     * ActionsTypesRepo
     *
     * @var $actionsTypesRepo
     */
    private $actionsTypesRepo;

    /**
     * ActionsConfigurations
     * @var ActionsConfigurationsRepo
     */
    private $actionsConfigurationsRepo;

    /**
     * ActionsRepo
     * @var ActionsRepo
     */
    private $actionsRepo;

    /**
     * ProvidersRepo
     *
     * @var ProvidersRepo
     */
    private $providersRepo;

    /**
     * RewardsCategoriesRepo
     *
     * @var RewardsCategoriesRepo
     */
    private $rewardsCategoriesRepo;

    /**
     * ProvidersTypesRepo
     *
     * @var ProvidersTypesRepo
     */
    private $providersTypesRepo;

    /**
     * ProviderTypesCollection
     *
     * @var ProviderTypesCollection
     */
    private $providerTypesCollection;

    /**
     * ActionsCollection
     *
     * @var ActionsCollection
     */
    private $actionsCollection;

    /**
     * StoreController constructor.
     *
     * @param StoreRewardsRepo $storeRewardsRepo
     * @param StoreExchangesRepo $storeExchangesRepo
     * @param StoreCollection $storeCollection
     * @param ActionsTypeRepo $actionsTypeRepo
     * @param ActionsConfigurationsRepo $actionsConfigurationsRepo
     * @param ActionsRepo $actionsRepo
     * @param ProvidersRepo $providersRepo
     * @param RewardsCategoriesRepo $rewardsCategoriesRepo
     * @param ActionsCollection $actionsCollection
     */
    public function __construct(StoreRewardsRepo $storeRewardsRepo, StoreExchangesRepo $storeExchangesRepo, StoreCollection $storeCollection, ActionsTypeRepo $actionsTypeRepo, ActionsConfigurationsRepo $actionsConfigurationsRepo, ActionsRepo $actionsRepo, ProvidersRepo $providersRepo, RewardsCategoriesRepo $rewardsCategoriesRepo, ProvidersTypesRepo $providersTypesRepo, ProviderTypesCollection $providerTypesCollection, ActionsCollection $actionsCollection,  AuditsRepo $auditsRepo)
    {
        $s3Directory = Configurations::getS3Directory();
        $this->filePath = "$s3Directory/store-rewards/";
        $this->storeRewardsRepo = $storeRewardsRepo;
        $this->storeExchangesRepo = $storeExchangesRepo;
        $this->storeCollection = $storeCollection;
        $this->actionsTypesRepo = $actionsTypeRepo;
        $this->actionsConfigurationsRepo = $actionsConfigurationsRepo;
        $this->actionsRepo = $actionsRepo;
        $this->providersRepo = $providersRepo;
        $this->rewardsCategoriesRepo = $rewardsCategoriesRepo;
        $this->providersTypesRepo = $providersTypesRepo;
        $this->providerTypesCollection = $providerTypesCollection;
        $this->actionsCollection = $actionsCollection;
    }

    /**
     * Show actions list
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function actions()
    {
        $data['title'] = _i('List of configurations');
        return view('back.store.actions.index', $data);
    }

    /**
     * Get all actions configurations
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function allActions()
    {
        try {
            $actions = $this->actionsRepo->getAll();
            $this->actionsCollection->formatActionsType($actions);
            $data = [
                'actions' => $actions
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Get all categories
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function allCategories()
    {
        try {
            $whitelabel = Configurations::getWhitelabel();
            $categories = $this->rewardsCategoriesRepo->all($whitelabel);
            $this->storeCollection->formatSearch($categories);
            $data = [
                'categories' => $categories
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Get all rewards
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function allRewards()
    {
        try {
            $whitelabel = Configurations::getWhitelabel();
            $rewards = $this->storeRewardsRepo->getAllRewards($whitelabel);
            $this->storeCollection->formatAllRewards($rewards);
            $data = [
                'rewards' => $rewards
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Get claims by user
     *
     * @param int $user User ID
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function claims($user = null)
    {
        try {
            if(!is_null($user)){
                $currency = session('currency');
                $claims = Store::getUserExchanges($user, $currency);
                $this->storeCollection->formatClaims($claims);
                $data = [
                    'claims' => $claims
                ];
            } else {
                $data = [
                    'claims' => []
                ];
            }
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'user' => $user]);
            return Utils::failedResponse();
        }
    }

    /**
     * Show create rewards view
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createRewards()
    {
        try {
            $data['title'] = _i('New reward');
            $whitelabel = Configurations::getWhitelabel();
            $categories = $this->rewardsCategoriesRepo->all($whitelabel);
            $data['categories'] = $categories;
            return view('back.store.rewards.create', $data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    /**
     * Show create actions configurations view
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createActions()
    {
        try {
            $actions = $this->actionsRepo->getAll();
            $data['actions'] = $actions;
            $data['title'] = _i('New action configuration');
            return view('back.store.actions.create', $data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    /**
     * Delete action configuration
     *
     * @param int $id Post ID
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction($id)
    {
        try {
            $this->actionsConfigurationsRepo->delete($id);
            $data = [
                'title' => _i('Configuration removed'),
                'message' => _i('The configuration was successfully removed'),
                'close' => _i('Close')
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'id' => $id]);
            return Utils::failedResponse();
        }
    }

    /**
     * Delete Category
     *
     * @param int $id category ID
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteCategory($id)
    {
        try {
            $this->rewardsCategoriesRepo->deleteCategory($id);
            $data = [
                'title' => _i('Category removed'),
                'message' => _i('The category was successfully removed'),
                'close' => _i('Close')
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'id' => $id]);
            return Utils::failedResponse();
        }
    }

    /**
     * Delete rewards
     *
     * @param int $id rewards ID
     * @param string $file File name
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteRewards($id, $file)
    {
        try {
            $path = "{$this->filePath}{$file}";
            Storage::delete($path);
            $this->storeRewardsRepo->deleteReward($id);
            $data = [
                'title' => _i('Reward removed'),
                'message' => _i('The reward was successfully removed'),
                'close' => _i('Close')
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'id' => $id, 'file' => $file]);
            return Utils::failedResponse();
        }
    }

    /**
     * Show edit view
     *
     * @param int $id Slider ID
     * @param string $currency Currency ISO
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editActions($currency, $id)
    {
        $whitelabel = Configurations::getWhitelabel();
        $action = $this->actionsConfigurationsRepo->find($id, $currency, $whitelabel);
        $dataAction = $this->storeCollection->formatUpdateActionsConfigurations($action, $id);
        try {
            if (!is_null($action)) {
                $providers =  $this->providersRepo->getByTypes([$action->provider_type_id]);
            } else {
                $providers = [];
            }
            $data['providers'] = $providers;
            $data['action'] = $dataAction;
            $data['title'] = _i('Update actions configurations');
            return view('back.store.actions.edit', $data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'slider' => $id]);
            abort(500);
        }
    }

    /**
     * Show edit rewards view
     *
     * @param int $id Slider ID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editRewards($id)
    {
        $slider = $this->storeRewardsRepo->find($id);
        if (!is_null($slider)) {
            try {
                $this->storeCollection->formatDetails($slider);
                $whitelabel = Configurations::getWhitelabel();
                $categories = $this->rewardsCategoriesRepo->all($whitelabel);
                $data['categories'] = $categories;
                $data['reward'] = $slider;
                $data['title'] = _i('Update reward');
                return view('back.store.rewards.edit', $data);

            } catch (\Exception $ex) {
                \Log::error(__METHOD__, ['exception' => $ex, 'slider' => $id]);
                abort(500);
            }
        } else {
            abort(404);
        }
    }

    /**
     * Exclude providers
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function excludeProviders(Request $request)
    {
        try {
            $providers = $request->provider_type;
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
     * Show rewards list
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function rewards()
    {
        $data['title'] = _i('List of rewards');
        return view('back.store.rewards.index', $data);
    }

    /**
     * Show category
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function category()
    {
        $data['title'] = _i('Categories');
        return view('back.store.categories.index', $data);
    }

    /**
     * Get category details
     *
     * @param int $id Category
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function categoryDetails($id)
    {
        try {
            $id = (int)$id;
            $categories = $this->rewardsCategoriesRepo->find($id);
            $data = [
                'category' => $categories
            ];
            $data['title'] = _i('Category details');
            $data['category_id'] = $id;
            return view('back.store.categories.details', $data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    /**
     * Show view redeemed rewards
     *
     */
    public function redeemedRewards()
    {
        $data['title'] = _i('Redeemed rewards');
        return view('back.store.reports.redeemed-rewards', $data);
    }

    /**
     * Show view redeemed rewards
     *
     */
    public function redeemedRewardsData(Request $request)
    {
       try {
           if (!is_null($request->start_date) && !is_null($request->end_date)){
               $currency = $request->currency;
               $startDate = Utils::startOfDayUtc($request->start_date);
               $endDate = Utils::endOfDayUtc($request->end_date);
               $whitelabel = Configurations::getWhitelabel();
               $rewards = $this->storeExchangesRepo->getExchangeByDatesAndCurrency($currency, $whitelabel, $startDate, $endDate);
               $this->storeCollection->formatRedeemedRewards($rewards);
           } else {
               $rewards = [];
           }

           $data = [
               'rewards' => $rewards
           ];
           return Utils::successResponse($data);

       } catch (\Exception $ex) {
           \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
           abort(500);
       }
    }

    /**
     * Type providers
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function typeProviders(Request $request)
    {
        try {
            $currency = $request->currency;
            $whitelabel = Configurations::getWhitelabel();
            $providersTypes = [ProviderTypes::$casino, ProviderTypes::$live_casino, ProviderTypes::$virtual, ProviderTypes::$sportbook, ProviderTypes::$racebook, ProviderTypes::$live_games, ProviderTypes::$poker];
            $providerTypesData = [];
            if (!is_null($currency)) {
                $providerTypesData = $this->providersTypesRepo->getByWhitelabel($whitelabel, $currency, $providersTypes);
                $this->providerTypesCollection->formatProviderTypes($providerTypesData);
            }
            $data = [
                'type_providers' => $providerTypesData
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Configurations actions data
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function storeActions(Request $request)
    {
        $this->validate($request, [
            'currency' => 'required',
            'points_desk' => 'required_if:action,1,2,3',
            'points_mobile' => 'required_if:action,1,2,3',
            'amount_desk' => 'required_if:action,1,3',
            'amount_mobile' => 'required_if:action,1,3',
            'exclude_provider' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'provider_type' => 'required',
        ]);

        try {
            $id = $request->action;
            $whitelabel = Configurations::getWhitelabel();
            $timezone = session('timezone');
            $startDate =  Carbon::createFromFormat('d-m-Y h:i a', $request->start_date, $timezone)->setTimezone('UTC')->format('Y-m-d H:i:s');
            $endDate = Carbon::createFromFormat('d-m-Y h:i a', $request->end_date, $timezone)->setTimezone('UTC')->format('Y-m-d H:i:s');

            $exclude = [];
            foreach ($request->exclude_provider as $item) {
                $exclude[] = (float)$item;
            }

            switch ($id) {
                case 1:
                    $dataPoints = [
                            'min' => null,
                            'max' => null,
                            'amount' => $request->amount_desk,
                            'points' => $request->points_desk,
                            'mobile_amount' => $request->points_mobile,
                            'mobile_points' => $request->amount_mobile,
                            'start_date' => $startDate,
                            'end_date' => $endDate,
                        ];
                    break;
                case 2:
                    $dataPoints = [
                        'points' => $request->points_desk,
                        'mobile_points' => $request->points_mobile,
                        'start_date' => $startDate,
                        'end_date' => $endDate,
                    ];
                    break;
                case 3:
                    $dataPoints = [
                        'amount' => $request->amount_desk,
                        'points' =>$request->points_desk,
                        'mobile_amount' => $request->amount_mobile,
                        'mobile_points' => $request->points_mobile,
                        'start_date' => $startDate,
                        'end_date' => $endDate,
                    ];
                    break;
            }
            $configurationData = [
                'action_id' => $id,
                'currency_iso' => $request->currency,
                'whitelabel_id' => $whitelabel,
                'data' => $dataPoints,
                'provider_type_id' => $request->provider_type,
                'exclude_providers' => $exclude
            ];
            $this->actionsConfigurationsRepo->store($configurationData);
            $data = [
                'title' => _i('Configuration loaded'),
                'message' => _i('The configuration data was loaded correctly'),
                'close' => _i('Close'),
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     * Store categories
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function storeCategories(Request $request)
    {
        $this->validate($request, [
            'name' => 'required:unique'
        ]);

        try {
            $category = [
                'name' => $request->name,
                'whitelabel_id' => Configurations::getWhitelabel()
            ];

            $this->rewardsCategoriesRepo->store($category);
            $data = [
                'title' => _i('Saved category'),
                'message' => _i('The category data was saved correctly'),
                'close' => _i('Close')
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     * Store rewards
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function storeRewards(Request $request)
    {
        $this->validate($request, [
            'image' => 'required',
            'currency' => 'required',
            'name' => 'required',
            'description' => 'required',
            'points' => 'required',
            'language' => 'required',
            'amount' => 'required|numeric'
        ]);

        try {
            $image = $request->file('image');
            $startDate = !is_null($request->start_date) ? Utils::startOfDayUtc($request->start_date, $originalFormat = 'd-m-Y', $finalFormat = 'Y-m-d H:i:s') : null;
            $endDate = !is_null($request->end_date) ? Utils::endOfDayUtc($request->end_date, $originalFormat = 'd-m-Y', $finalFormat = 'Y-m-d H:i:s') : null;
            $extension = $image->getClientOriginalExtension();
            $originalName = str_replace(".$extension", '', $image->getClientOriginalName());
            $name = Str::slug($originalName) . time() . '.' . $extension;
            $path = "{$this->filePath}{$name}";
            Storage::put($path, file_get_contents($image->getRealPath()), 'public');

            $rewards = [
                'amount' => $request->amount,
            ];
            if ($request->category) {
                $category = $request->category;
            } else {
                $category = null;
            }
            $title = $request->name;
            $slug = Str::slug($title);
            $rewardsData = [
                'name' => $title,
                'description' => $request->description,
                'points' => $request->points,
                'quantity' => $request->quantity,
                'whitelabel_id' => Configurations::getWhitelabel(),
                'image' => $name,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'language' => $request->language,
                'currency_iso' => $request->currency,
                'status' => $request->status,
                'data' => $rewards,
                'category_id' => $category,
                'slug' => $slug
            ];

            $this->storeRewardsRepo->store($rewardsData);

            $user_id = auth()->user()->id;
            $auditData = [
                'ip' => Utils::userIp($request),
                'user_id' => $user_id,
                'username' => auth()->user()->username,
                'store_rewards_data' => $rewardsData
            ];

            //Audits::store($user_id, AuditTypes::$store_creation, Configurations::getWhitelabel(), $auditData);

            $data = [
                'title' => _i('Reward loaded'),
                'message' => _i('The reward was loaded correctly'),
                'close' => _i('Close')
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     * Get transactions by user
     *
     * @param int $user User ID
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function transactions($user = null)
    {
        try {
            if (!is_null($user)){
                $currency = session('currency');
                $transactions = Store::getUserTransactions($user, $currency);
                $this->storeCollection->formatTransactions($transactions);
                $data = [
                    'transactions' => $transactions
                ];
            } else {
                $data = [
                    'transactions' => []
                ];
            }
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'user' => $user]);
            return Utils::failedResponse();
        }
    }

    /**
     * Configurations actions data
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateActions(Request $request)
    {
        $this->validate($request, [
            'currency' => 'required',
            'points_desk' => 'required_if:action,1,2,3',
            'points_mobile' => 'required_if:action,1,2,3',
            'amount_desk' => 'required_if:action,1,3',
            'amount_mobile' => 'required_if:action,1,3',
            'exclude_provider' => 'required',
        ]);
        try {
            $timezone = session('timezone');
            $whitelabel = Configurations::getWhitelabel();
            $id = (int)$request->id;
            $exclude = [];
            foreach ($request->exclude_provider as $item) {
                $exclude[] = (float)$item;
            }

            if (isset($request->start_date) && isset($request->end_date)) {
                $start_date = Carbon::createFromFormat('d-m-Y h:i a', $request->start_date)->setTimezone($timezone)->format('Y-m-d H:i:s');
                $end_date = Carbon::createFromFormat('d-m-Y h:i a', $request->end_date)->setTimezone($timezone)->format('Y-m-d H:i:s');

            } else {
                $start_date =  $request->start_date_old;
                $end_date =  $request->end_date_old;
            }
            switch ($id) {
                case 1:
                    $dataPoints = [
                        'min' => null,
                        'max' => null,
                        'amount' => $request->amount_desk,
                        'points' => $request->points_desk,
                        'mobile_amount' => $request->points_mobile,
                        'mobile_points' => $request->amount_mobile,
                        'start_date' => $start_date,
                        'end_date' => $end_date
                    ];
                    break;
                case 2:
                    $dataPoints = [
                        'points' => $request->points_desk,
                        'mobile_points' => $request->points_mobile,
                        'start_date' => $start_date,
                        'end_date' => $end_date
                    ];
                    break;
                case 3:
                    $dataPoints = [
                        'amount' => $request->amount_desk,
                        'points' => $request->points_desk,
                        'mobile_amount' => $request->amount_mobile,
                        'mobile_points' => $request->points_mobile,
                        'start_date' => $start_date,
                        'end_date' => $end_date
                    ];
                    break;
            }
            if ($request->currency !== $request->currency_old) {
                $updateCurrency = $request->currency;
            } else {
                $updateCurrency = $request->currency_old;
            }
            $configurationData = [
                'action_id' => $id,
                'currency_iso' => $updateCurrency,
                'whitelabel_id' => $whitelabel,
                'data' => json_encode($dataPoints),
                'provider_type_id' => $request->provider_type,
                'exclude_providers' => json_encode($exclude)
            ];
            $currency = is_null($request->currency_old) ? $request->currency : $request->currency_old;
            $this->actionsConfigurationsRepo->update($id, $currency, $whitelabel, $configurationData);
            $data = [
                'title' => _i('Configuration updated'),
                'message' => _i('The configuration data was updated correctly'),
                'close' => _i('Close'),
                'route' => route('store.actions.index')
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     * Update category
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateCategory(Request $request)
    {

        $this->validate($request, [
            'name' => 'required:unique'
        ]);
        $id=$request->category_id;
        $categoryData = [
            'name' => $request->name,
            'whitelabel_id' => Configurations::getWhitelabel()

        ];
        $this->rewardsCategoriesRepo->update($id, $categoryData);

        $data = [
            'title' => _i('Updated category'),
            'message' => _i('The category data was updated correctly'),
            'close' => _i('Close')
        ];
        return Utils::successResponse($data);
    }

    /**
     * Update rewards
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateRewards(Request $request)
    {
        $this->validate($request, [
            'image' => 'required',
            'currency' => 'required',
            'name' => 'required',
            'description' => 'required',
            'points' => 'required',
            'language' => 'required',
            'amount' => 'required|numeric',
        ]);

        try {
            $id = $request->id;
            $file = $request->file;
            $image = $request->file('image');
            $startDate = !is_null($request->start_date) ? Utils::startOfDayUtc($request->start_date, $originalFormat = 'd-m-Y', $finalFormat = 'Y-m-d H:i:s') : null;
            $endDate = !is_null($request->end_date) ? Utils::endOfDayUtc($request->end_date, $originalFormat = 'd-m-Y', $finalFormat = 'Y-m-d H:i:s') : null;

            $rewards = [
                'amount' => $request->amount,
            ];
            if ($request->category) {
                $category = $request->category;
            } else {
                $category = null;
            }
            $rewardsData = [
                'name' => $request->name,
                'description' => $request->description,
                'points' => $request->points,
                'quantity' => $request->quantity,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'language' => $request->language,
                'currency_iso' => $request->currency,
                'status' => $request->status,
                'data' => $rewards,
                'category_id' => $category,
            ];

            if (!is_null($image)) {
                $extension = $image->getClientOriginalExtension();
                $originalName = str_replace(".$extension", '', $image->getClientOriginalName());
                $name = Str::slug($originalName) . time() . '.' . $extension;
                $newFilePath = "{$this->filePath}{$name}";
                $oldFilePath = "{$this->filePath}{$file}";
                Storage::put($newFilePath, file_get_contents($image->getRealPath()), 'public');
                Storage::delete($oldFilePath);
                $rewardsData['image'] = $name;
                $file = $name;
            }

            $this->storeRewardsRepo->update($id, $rewardsData);

            $user_id = auth()->user()->id;
            $auditData = [
                'ip' => Utils::userIp($request),
                'user_id' => $user_id,
                'username' => auth()->user()->username,
                'store_rewards_data' => [
                    'id' => $id,
                    'data' => $rewardsData
                ]
            ];

            //Audits::store($user_id, AuditTypes::$store_modification, Configurations::getWhitelabel(), $auditData);


            $data = [
                'title' => _i('Reward updated'),
                'message' => _i('The reward data was updated correctly'),
                'close' => _i('Close'),
                'file' => $file
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

}

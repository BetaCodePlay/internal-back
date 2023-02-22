<?php


namespace App\Http\Controllers;

use App\Core\Collections\CoreCollection;
use App\Core\Collections\CredentialsCollection;
use App\Core\Collections\GamesCollection;
use App\Core\Repositories\CredentialsRepo;
use App\Core\Repositories\CurrenciesRepo;
use App\Core\Repositories\GamesRepo;
use App\Core\Repositories\ProvidersRepo;
use App\Core\Repositories\ProvidersTypesRepo;
use App\CRM\Repositories\SegmentsRepo;
use App\DotSuite\Collections\DotSuiteCollection;
use App\DotSuite\Collections\LobbyDotSuiteGamesCollection;
use App\DotSuite\Enums\FreeSpinsStatus;
use App\DotSuite\Repositories\DotSuiteFreeSpinsRepo;
use App\DotSuite\Repositories\DotSuiteGamesRepo;
use App\DotSuite\Repositories\DotSuiteRepo;
use App\DotSuite\Repositories\LobbyDotSuiteGamesRepo;
use App\Users\Repositories\UsersRepo;
use App\Whitelabels\Repositories\WhitelabelsRepo;
use Carbon\Carbon;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Configurations\Enums\Codes;
use Dotworkers\Configurations\Enums\Providers;
use Dotworkers\Configurations\Enums\ProviderTypes;
use Dotworkers\Configurations\Enums\Status;
use Dotworkers\Configurations\Utils;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Ixudra\Curl\Facades\Curl;
use Dotworkers\Wallet\Wallet;
use Maatwebsite\Excel\Excel;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DotSuiteController
 *
 * This class allows to manage dot suite requests
 *
 * @package App\Http\Controllers
 * @author  Damelys Espinoza
 */
class DotSuiteController extends Controller
{
    /**
     * CurrenciesRepo
     *
     * @var CurrenciesRepo
     */
    private $currenciesRepo;

    /**
     * GamesRepo
     *
     * @var $gamesRepo
     */
    private $gamesRepo;

    /**
     * GamesCollection
     *
     * @var $gamesCollection
     */
    private $gamesCollection;

    /**
     * UrlFreeSpinsPragmatic
     *
     * @var $urlFreeSpinsPragmatic
     */
    private $urlFreeSpinsPragmatic;

    /**
     * DotSuiteFreeSpinsRepo
     *
     * @var dotSuiteFreeSpinsRepo
     */
    private $dotSuiteFreeSpinsRepo;

    /**
     * UsersRepo
     *
     * @var usersRepo
     */
    private $usersRepo;

    /**
     * DotSuiteCollection
     *
     * @var DotSuiteCollection
     */
    private $dotSuiteCollection;

    /**
     * LobbyDotSuiteGamesCollection
     *
     * @var LobbyDotSuiteGamesCollection
     */
    private $lobbyDotSuiteGamesCollection;

    /**
     *  SegmentsRepo
     *
     * @var SegmentsRepo
     */
    private $segmentsRepo;

    /**
     *  Excel
     *
     * @var Excel
     */
    private $excel;

    /**
     * ProvidersRepo
     *
     * @var ProvidersRepo
     */
    private $providersRepo;

    /**
     * DotSuiteRepo
     *
     * @var DotSuiteRepo
     */
    private $dotSuiteRepo;

    /**
     * DotSuiteGamesRepo
     *
     * @var DotSuiteGamesRepo
     */
    private $dotSuiteGamesRepo;

    /**
     *
     * @var CredentialsRepo
     */
    private $credentialsRepo;

    /**
     * @var CredentialsCollection
     */
    private $credentialsCollection;

    /**
     * CoreCollection
     *
     * @var CoreCollection
     */
    private $coreCollection;

    /**
     *  LobbyDotSuiteRepo
     *
     * @var LobbyDotSuiteGamesRepo
     */
    private $lobbyDotSuiteGamesRepo;

    /*
     * DotSuiteController constructor.
     *
     * @param CurrenciesRepo $currenciesRepo
     * @param GamesRepo $gamesRepo
     * @param GamesCollection $gamesCollection
     * @param DotSuiteFreeSpinsRepo $dotSuiteFreeSpinsRepo
     * @param UsersRepo $usersRepo
     * @param SegmentsRepo $segmentsRepo
     * @param ProvidersRepo $providersRepo
     * @param DotSuiteRepo $dotSuiteRepo
     * @param DotSuiteGamesRepo $dotSuiteGamesRepo
     */
    public function __construct(currenciesRepo $currenciesRepo, GamesRepo $gamesRepo, GamesCollection $gamesCollection,
                                DotSuiteFreeSpinsRepo $dotSuiteFreeSpinsRepo, UsersRepo $usersRepo,
                                DotSuiteCollection $dotSuiteCollection, SegmentsRepo $segmentsRepo, Excel $excel,
                                ProvidersRepo $providersRepo, DotSuiteRepo $dotSuiteRepo, DotSuiteGamesRepo $dotSuiteGamesRepo,
                                CredentialsRepo $credentialsRepo, CredentialsCollection $credentialsCollection, CoreCollection $coreCollection,
                                LobbyDotSuiteGamesRepo $lobbyDotSuiteGamesRepo, LobbyDotSuiteGamesCollection $lobbyDotSuiteGamesCollection)
    {
        $this->currenciesRepo = $currenciesRepo;
        $this->gamesRepo = $gamesRepo;
        $this->gamesCollection = $gamesCollection;
        $this->dotSuiteFreeSpinsRepo = $dotSuiteFreeSpinsRepo;
        $this->usersRepo = $usersRepo;
        $this->dotSuiteCollection = $dotSuiteCollection;
        $this->segmentsRepo = $segmentsRepo;
        $this->excel = $excel;
        $this->providersRepo = $providersRepo;
        $this->dotSuiteRepo = $dotSuiteRepo;
        $this->credentialsRepo = $credentialsRepo;
        $this->credentialsCollection = $credentialsCollection;
        $this->coreCollection = $coreCollection;
        $this->dotSuiteGamesRepo = $dotSuiteGamesRepo;
        $this->lobbyDotSuiteGamesRepo = $lobbyDotSuiteGamesRepo;
        $this->lobbyDotSuiteGamesCollection = $lobbyDotSuiteGamesCollection;
    }

    /**
     * Show view cancel caleta gaming
     *
     * @param $provider Provider ID
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function cancelCaletaGaming($provider)
    {
        try{
            $data['provider'] = $provider;
            $data['title'] = _i('Cancel free spins ');
            $whitelabel = Configurations::getWhitelabel();
            $currency = session('currency');
            $data['users'] = $this->usersRepo->getUsersByCurrency($currency);
            return view('back.dotsuite.caleta-gaming.cancel-free-spins', $data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    /**
     * Get all credentials
     *
     * @return Response
     */
    public function allCredentials(Request $request, $client = null)
    {
        try {
            if (!is_null($client)) {
                $provider = $request->providers;
                $currencies = explode(",", $request->currencies);
                $credentials = $this->credentialsRepo->getDotSuiteCredentials($client, $provider, $currencies);
            } else {
                $credentials = [];
            }
            $credentialsData = $this->credentialsCollection->formatDotSuite($credentials);
            return Utils::successResponse($credentialsData);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Get all dotsuite games
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function allDotSuiteGames(Request $request)
    {
        try {
            if (!is_null($request->provider)) {
                $provider = $request->provider;
            }
            if (!is_null($request->route)) {
                $route = $request->route;
            }
            if (!is_null($request->game)) {
                $game = $request->game;
            }
            $provider = $request->provider;
            $route = $request->route;
            $game = $request->game;
            $order = $request->order;
            $image = $request->image;
            $items = Configurations::getMenu();
            $category = 1;
            $whitelabel = Configurations::getWhitelabel();
            $games = $this->lobbyDotSuiteGamesRepo->getGamesDotsuiteWhitelabel($whitelabel, $category, $provider, $route, $order, $game, $image);
            $this->lobbyDotSuiteGamesCollection->formatAll($games, $items, $order, $request->image);
            $data = [
                'games' => $games
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Status credentials
     *
     * @param int $client Whitelabels ID
     * @param int $provider Provider ID
     * @param string $currency Currency ISO
     * @param bool $status
     * @return Response
     */
    public function statusCredentials($client, $provider, $currency, $status)
    {
        try {
            $dataCredentials = [
                'status' => $status
            ];
            $this->credentialsRepo->update($client, $currency, $provider, $dataCredentials);
            $data = [
                'title' => _i('Status change'),
                'message' => _i('Status updated successfully'),
                'close' => _i('Close')
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'client' => $client, 'provider' => $provider, 'currency' => $currency, 'status' => $status]);
            return Utils::failedResponse();
        }
    }

    /**
     * Show view cancel caleta gaming
     *
     * @param $provider Provider ID
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function cancelFreeSpinsData($code_reference, $provider, $id)
    {
        try {
            $url = "";
            switch ($provider){
                case Providers::$caleta_gaming:
                {
                    $url = env('CALETA_GAMING_FREE_URL'). "/cancel";
                    return $this->cancelFreeSpinsByProvider($code_reference, $provider, $id, $url);
                    break;
                }
                case Providers::$evo_play:
                {
                    $url = env('EVO_PLAY_FREE_URL'). "/cancel";
                    return $this->cancelFreeSpinsByProvider($code_reference, $provider, $id, $url);
                    break;
                }
                case Providers::$triple_cherry_original:
                {
                    $url = env('TRIPLE_CHERRY_FREE_URL'). "/cancel";
                    return $this->cancelFreeSpinsByProvider($code_reference, $provider, $id, $url);
                    break;
                }
            }
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'code_reference' => $code_reference, 'provider' => $provider, 'id' => $id]);
            abort(500);
        }
    }

    /**
     * Show view cancel triple cherry
     *
     * @param $provider Provider ID
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function cancelTripleCherry($provider)
    {
        try{
            $data['provider'] = $provider;
            $data['title'] = _i('Cancel free spins ');
            $whitelabel = Configurations::getWhitelabel();
            $currency = session('currency');
            $data['users'] = $this->usersRepo->getUsersByCurrency($currency);
            return view('back.dotsuite.triple-cherry.cancel-free-spins', $data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    /**
     * View for credentials
     *
     * @return \Illuminate\Contracts\Foundation\Application|Factory|\Illuminate\Contracts\View\View
     */
    public function createCredentials()
    {
        try {
            $whitelabelsRepo = new WhitelabelsRepo();
            $providersTypesRepo = new ProvidersTypesRepo();
            $data['title'] = _i('Credentials');
            $data['whitelabels'] = $whitelabelsRepo->all();
            $data['currency_client'] = $this->currenciesRepo->all();
            $data['providers_types'] = $providersTypesRepo->all();
            return view('back.dotsuite.credentials.create', $data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    /**
     * Show view create lobby dotsuite games
     *
     */
    public function createLobbyGamesDotsuite()
    {
        try {
            $route = Configurations::getMenu();
            $data['route'] = $this->coreCollection->formatWhitelabelMenu($route);
            $image = new \stdClass();
            $currency = session('currency');
            $whitelabel = Configurations::getWhitelabel();
            $provider = $this->credentialsRepo->searchByWhitelabelDotsuite($whitelabel, $currency);
            $games = $this->lobbyDotSuiteGamesRepo->searchGamesByWhitelabel($whitelabel);
            $data['image'] = $image;
            $data['providers'] = $provider;
            $data['games'] = $games;
            $data['title'] = _i('Create lobby dotsuite');
            return view('back.dotsuite.lobby.dotsuite-create', $data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }


    /**
     * Show credentials list
     *
     * @return Factory|\Illuminate\View\View
     */
    public function index()
    {
        $whitelabelsRepo = new WhitelabelsRepo();
        $providersTypesRepo = new ProvidersTypesRepo();
        $data['title'] = _i('List of credentials');
        $data['whitelabels'] = $whitelabelsRepo->all();
        $data['currency_client'] = $this->currenciesRepo->all();
        $data['providers_types'] = $providersTypesRepo->all();
        return view('back.dotsuite.credentials.index', $data);
    }

    /**
     * Store credencials
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function storeCredentials(Request $request)
    {
        $this->validate($request, [
            'client' => 'required',
            'currencies' => 'required',
            'providers' => 'required',
            'percentage' => 'required'
        ]);

        try {
            $client = (int)$request->client;
            $provider = (int)$request->providers;
            $currencies = $request->currencies;
            $percentage = $request->percentage / 100;
            $duplicate = 0;
            $notDuplicate = 0;

            foreach($currencies as $currency){
                $existCredentials = $this->credentialsRepo->searchByCredential($client, $provider, $currency);
                if (!is_null($existCredentials)) {
                    $duplicate++;

                } else {
                    $notDuplicate++;
                    if ($provider == Providers::$dot_suite) {
                        $credentials = [
                            'client_credentials_grant_secret' => $request->grant_secret,
                        ];
                    } else {
                        $credentials = [];
                    }
                    $credentialData = [
                        'client_id' => $client,
                        'provider_id' => $provider,
                        'currency_iso' => $currency,
                        'percentage' => $percentage,
                        'status' => true,
                        'data' => $credentials
                    ];
                    $this->credentialsRepo->store($credentialData);
                }
            }

            if($duplicate == 0){
                $data = [
                    'title' => _i('Saved credential'),
                    'message' => _i('Credential data was saved correctly'),
                    'close' => _i('Close')
                ];
                return Utils::successResponse($data);
            } else {
                $data = [
                    'title' => _i('Saved credential'),
                    'message' => _i('%s Credentials stored by currency. %s New credentials saved', [$duplicate, $notDuplicate]),
                    'close' => _i('Close')
                ];
                return Utils::successResponse($data);
            }

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            abort(500);
        }
    }

    /**
     * Show view create free rounds caleta gaming
     *
     * @param int $providor Provider ID
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function createCaletaGaming($provider)
    {
        try {
            $providerName = Providers::getName($provider);
            $whitelabel = Configurations::getWhitelabel();
            $currency = session('currency');
            $category = 'slots';
            $data['games'] = $this->dotSuiteGamesRepo->allGamesByProvider($provider, $category);
            $data['users'] = $this->usersRepo->getUsersByCurrency($currency);
            $data['segments'] = $this->segmentsRepo->allByWhitelabel();
            $data['provider_name'] = $providerName;
            $data['provider'] = $provider;
            $data['title'] = _i('Free spins ') . $providerName;
            return view('back.dotsuite.free-spins.create', $data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    /**
     * Show view create free rounds evo play
     *
     * @param int $providor Provider ID
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function createEvoPlay($provider)
    {
        try {
            $providerName = Providers::getName($provider);
            $whitelabel = Configurations::getWhitelabel();
            $currency = session('currency');
            $category = 'video-slots';
            $data['games'] = $this->dotSuiteGamesRepo->allGamesByProvider($provider, $category);
            $data['users'] = $this->usersRepo->getUsersByCurrency($currency);
            $data['segments'] = $this->segmentsRepo->allByWhitelabel();
            $data['provider_name'] = $providerName;
            $data['provider'] = $provider;
            $data['title'] = _i('Free spins ') . $providerName;
            return view('back.dotsuite.free-spins.create', $data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    /**
     * Show view create promotion triple cherry
     *
     * @param int $providor Provider ID
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function createTripleCherry($provider)
    {
        try {
            $currency = session('currency');
            $providerName = Providers::getName($provider);
            $whitelabel = Configurations::getWhitelabel();
            $category = 'slots';
            $data['games'] = $this->dotSuiteGamesRepo->allGamesByProvider($provider, $category);
            $data['users'] = $this->usersRepo->getUsersByCurrency($currency);
            $data['segments'] = $this->segmentsRepo->allByWhitelabel();
            $data['provider_name'] = $providerName;
            $data['provider'] = $provider;
            $data['title'] = _i('Free spins ') . $providerName;
            $data['option'] = 'promotion';
            return view('back.dotsuite.free-spins.create', $data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    /**
     * Show view free spins
     *
     * @param int $providor Provider ID
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function freeSpins()
    {
        try {
            $currency = session('currency');
            $whitelabel = Configurations::getWhitelabel();
            $category = 'slots';
            $providers = [Providers::$caleta_gaming, Providers::$evo_play, Providers::$triple_cherry_original];
            $data['users'] = $this->usersRepo->getUsersByCurrency($currency);
            $data['providers'] = $this->providersRepo->getByIDs($providers);
            $data['title'] = _i('Free spins ');
            return view('back.dotsuite.reports.free-spins', $data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    /**
     * Show view free spins
     *
     * @param int $providor Provider ID
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function freeSpinsData(Request $request)
    {
        if (isset($request->provider) && isset($request->status)) {
            $this->validate($request, [
                'provider' => 'required',
                'status' => 'required'
            ]);
        }

        try {
            $provider = $request->provider;
            $user = $request->user;
            $status = $request->status;
            if(!is_null($provider) && !is_null($status)){
                $reference = $request->reference;
                $currency = session('currency');
                $whitelabel = Configurations::getWhitelabel();
                $freeSpinsData = $this->dotSuiteFreeSpinsRepo->getFreeSpins($currency, $whitelabel, $provider, $reference, $status);
            }else {
                $freeSpinsData = [];
            }

            $freeSpins = $this->dotSuiteCollection->formatListFreeSpins($freeSpinsData, $user);
            $data = [
                'free_spins' => $freeSpins
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            abort(500);
        }
    }

    /**
     * List of free spins caleta gaming
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function freeSpinsListByProviders(Request $request)
    {
        try {
            $user = $request->user;
            if(!is_null($user)){
                $provider = $request->provider;
                $whitelabel = Configurations::getWhitelabel();
                $currency = session('currency');
                $caletaGamingData = $this->dotSuiteFreeSpinsRepo->getFreeRoundsCaletaGaming($currency, $whitelabel, $provider);
            } else {
                $caletaGamingData = [];
            }

            $list = $this->dotSuiteCollection->formatListCaletaGaming($caletaGamingData, $user);
            $data = [
                'free_spins' => $list
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            abort(500);
        }
    }

    /**
     * Cancel free spins by code reference caleta gaming
     *
     *  @param int $id Free spins ID
     *  @param int $provide Provider ID
     *  @param string $promoCode Promo code
     *  @param string $url Url
     *  @return \Symfony\Component\HttpFoundation\Response
     */
    public function cancelFreeSpinsByProvider($codeReference, $provider, $id, $url)
    {
        try{
            $whitelabel = Configurations::getWhitelabel();
            $currency = session('currency');
            $credentials = Utils::getCredentials($whitelabel, Providers::$dot_suite, $currency);
            $requestData = [
                'code_reference' => $codeReference,
                'client_secret' => $credentials->client_credentials_grant_secret,
            ];

            $curl = Curl::to($url)
                ->withData($requestData)
                ->post();
            $response = json_decode($curl);
            if($response->status == Status::$ok){
                $dataFreeSpins = [
                    'status' => FreeSpinsStatus::$disable
                ];
                $this->dotSuiteFreeSpinsRepo->update($id, $dataFreeSpins);

                $data = [
                    'title' => _i('Free spin'),
                    'message' => _i('The promotion spins have been cancel successfully.'),
                    'close' => _i('Close')
                ];
                return Utils::successResponse($data);
            }elseif($response->status == Status::$error) {
                $data = [
                    'title' => _i('Failed'),
                    'message' => _i('Please reload and try again'),
                    'close' => _i('Close'),
                ];
                return Utils::errorResponse(Codes::$forbidden, $data);
            }
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'codeReference' => $codeReference, 'provider' => $provider, 'freeSpindId' => $id]);
            abort(500);
        }
    }

    /**
     * Store slot by provider
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'expiration_date' => 'required',
            'game' => 'required',
            'users' => 'required_if:type_user,1',
            'segment' => 'required_if:type_user,2',
            'amount' => [
                'required',
                'numeric',
                'gt:0'
            ],
            'quantity' => [
                'required',
                'numeric',
                'gt:0'
            ]
        ];
        $this->validate($request, $rules);
        try {
            $provider = $request->provider;
            switch ($provider){
                case Providers::$caleta_gaming:
                {
                    $response = $this->storeCaletaGaming($request);
                    break;
                }
                case Providers::$evo_play:
                {
                    $response = $this->storeEvoPlay($request);
                    break;
                }
                case Providers::$triple_cherry_original:
                {
                    $response = $this->storeTripleCherry($request);
                    break;
                }
            }
            return $response;
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            abort(500);
        }
    }

    /**
     * Store slot caleta gaming
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws ValidationException
     */
    public function storeCaletaGaming($request)
    {
        try {
            $users = $request->users;
            $currency = session('currency');
            $walletUsers = [];
            $usersWallet = [];

            $whitelabel = Configurations::getWhitelabel();
            $credentials = Utils::getCredentials($whitelabel, Providers::$dot_suite, $currency);
            $timezone = session('timezone');
            $expirationDate = Carbon::createFromFormat('d-m-Y', $request->expiration_date)->setTimezone($timezone)->format('Y-m-d H:i:s');

            $typeUser = (int)$request->type_user;
            switch ($typeUser) {
                case 1:
                {
                    foreach ($users as $user) {
                        $walletData = Wallet::getByClient($user, $currency);
                        if (!is_null($walletData)) {
                            $usersWallet[] = [
                                'user' => $user,
                                'wallet' => $walletData->data->wallet->id
                            ];
                            $walletUsers[] = $walletData->data->wallet->id;
                        }
                    }
                    break;
                }
                case 2:
                {
                    $segmentId = $request->segment;
                    $segmentData = $this->segmentsRepo->getBySegmentId($segmentId);
                    $segmentUserId = $segmentData->data;
                    foreach ($segmentUserId as $segment) {
                        $walletData = Wallet::getByClient($segment, $currency);
                        if (!is_null($walletData)) {
                            $usersWallet[] = [
                                'user' => $walletData->data->wallet->user,
                                'wallet' => $walletData->data->wallet->id
                            ];
                            $walletUsers[] = $walletData->data->wallet->id;
                        }
                    }
                    break;
                }
            }
            $requestData = [
                'users' => $walletUsers,
                'currency' => $currency,
                'client_secret' => $credentials->client_credentials_grant_secret,
                'amount' => $request->amount,
                'quantity' => $request->quantity,
                'expirationDate' => $expirationDate,
                'game_code' => $request->game,
            ];

            $url = env('CALETA_GAMING_FREE_URL'). "/create";
            $curl = Curl::to($url)
                ->withData($requestData)
                ->post();

            $response = json_decode($curl);
            if ($response->status == Status::$ok) {
                foreach ($response->data as $data) {
                    foreach ($usersWallet as $userWallet) {
                        if ($userWallet['wallet'] == $data->user) {
                            $amount = $data->amount / 1000;
                            $additionalData = [
                                'amount' => $amount,
                                'expiration_date' => $expirationDate,
                                'code_reference' => $data->code_reference,
                                'operator' => auth()->user()->id,
                            ];

                            $dataFreeSpins = [
                                'users' => [$userWallet['user']],
                                'games_id' => [$request->game],
                                'currency_iso' => $currency,
                                'provider_id' => $request->provider,
                                'whitelabel_id' => $whitelabel,
                                'free_spins' => $data->quantity,
                                'data' => $additionalData,
                                'status' => FreeSpinsStatus::$enable
                            ];
                            $this->dotSuiteFreeSpinsRepo->store($dataFreeSpins);
                        }
                    }
                }

                $data = [
                    'title' => _i('Free spins'),
                    'message' => _i('The free spins have been awarded successfully'),
                    'close' => _i('Close')
                ];
                return Utils::successResponse($data);
            } else if($response->status == Status::$error) {
                $data = [
                    'title' => _i('Failed'),
                    'message' => _i('Please reload and try again'),
                    'close' => _i('Close'),
                ];
                return Utils::errorResponse(Codes::$forbidden, $data);
            } else if ($response->status == Status::$failed){
                $data = [
                    'title' => _i('Error'),
                    'message' => _i('Could not load save data'),
                    'close' => _i('Close'),
                ];
                return Utils::errorResponse(Codes::$forbidden, $data);
            }
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     * Store slot evo play
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws ValidationException
     */
    public function storeEvoPlay($request)
    {
        try {
            $users = $request->users;
            $currency = session('currency');
            $walletUsers = [];
            $usersWallet = [];

            $timezone = session('timezone');
            $expirationDate = Carbon::createFromFormat('d-m-Y', $request->expiration_date)->setTimezone($timezone)->format('Y-m-d H:i:s');
            $whitelabel = Configurations::getWhitelabel();
            $credentials = Utils::getCredentials($whitelabel, Providers::$dot_suite, $currency);

            $typeUser = (int)$request->type_user;
            switch ($typeUser) {
                case 1:
                {
                    foreach ($users as $user) {
                        $walletData = Wallet::getByClient($user, $currency);
                        if (!is_null($walletData)) {
                            $usersWallet[] = [
                                'user' => $user,
                                'wallet' => $walletData->data->wallet->id
                            ];
                            $walletUsers[] = $walletData->data->wallet->id;
                        }
                    }
                    break;
                }
                case 2:
                {
                    $segmentId = $request->segment;
                    $segmentData = $this->segmentsRepo->getBySegmentId($segmentId);
                    $segmentUserId = $segmentData->data;
                    foreach ($segmentUserId as $segment) {
                        $walletData = Wallet::getByClient($segment, $currency);
                        if (!is_null($walletData)) {
                            $usersWallet[] = [
                                'user' => $walletData->data->wallet->user,
                                'wallet' => $walletData->data->wallet->id
                            ];
                            $walletUsers[] = $walletData->data->wallet->id;
                        }
                    }
                    break;
                }
            }

            $requestData = [
                'users' => [$walletUsers],
                'currency' => $currency,
                'client_secret' => $credentials->client_credentials_grant_secret,
                'amount' => $request->amount,
                'quantity' => $request->quantity,
                'expirationDate' => $expirationDate,
                'game_code' => $request->game,
            ];

            $url = env('EVO_PLAY_FREE_URL'). "/create";
            $curl = Curl::to($url)
                ->withData($requestData)
                ->post();

            $response = json_decode($curl);
            if ($response->status == Status::$ok) {
                foreach ($response->data as $data) {
                    foreach ($usersWallet as $userWallet) {
                        if ($userWallet['wallet'] == $data->user) {
                            $amount = $data->amount / 1000;
                            $additionalData = [
                                'amount' => $amount,
                                'expiration_date' => $expirationDate,
                                'code_reference' => $data->code_reference,
                                'operator' => auth()->user()->id,
                            ];

                            $dataFreeSpins = [
                                'users' => [$userWallet['user']],
                                'games_id' => [$request->game],
                                'currency_iso' => $currency,
                                'provider_id' => $request->provider,
                                'whitelabel_id' => $whitelabel,
                                'free_spins' => $data->quantity,
                                'data' => $additionalData,
                                'status' => FreeSpinsStatus::$enable
                            ];
                            $this->dotSuiteFreeSpinsRepo->store($dataFreeSpins);
                        }
                    }
                }

                $data = [
                    'title' => _i('Free spins assigned'),
                    'message' => _i('The free spins have been awarded successfully'),
                    'close' => _i('Close')
                ];
                return Utils::successResponse($data);
            }else if($response->status == Status::$error) {
                $data = [
                    'title' => _i('Failed'),
                    'message' => _i('Please reload and try again'),
                    'close' => _i('Close'),
                ];
                return Utils::errorResponse(Codes::$forbidden, $data);
            } else if ($response->status == Status::$failed){
                $data = [
                    'title' => _i('Error'),
                    'message' => _i('Could not load save data'),
                    'close' => _i('Close'),
                ];
                return Utils::errorResponse(Codes::$forbidden, $data);
            }
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     * Store slot triple cherry
     * @param $request
     * @return Response
     *
     * @throws ValidationException
     */
    public function storeTripleCherry($request)
    {
        try {
            $users = $request->users;
            $currency = session('currency');
            $walletUsers = [];
            $usersWallet = [];

            $timezone = session('timezone');
            $expirationDate = Carbon::createFromFormat('d-m-Y', $request->expiration_date)->setTimezone($timezone)->format('Y-m-d H:i:s');
            $whitelabel = Configurations::getWhitelabel();
            $credentials = Utils::getCredentials($whitelabel, Providers::$dot_suite, $currency);

            $typeUser = (int)$request->type_user;
            switch ($typeUser) {
                case 1:
                {
                    foreach ($users as $user) {
                        $walletData = Wallet::getByClient($user, $currency);
                        if (!is_null($walletData)) {
                            $usersWallet[] = [
                                'user' => $user,
                                'wallet' => $walletData->data->wallet->id
                            ];
                            $walletUsers[] = $walletData->data->wallet->id;
                        }
                    }
                    break;
                }
                case 2:
                {
                    $segmentId = $request->segment;
                    $segmentData = $this->segmentsRepo->getBySegmentId($segmentId);
                    $segmentUserId = $segmentData->data;
                    foreach ($segmentUserId as $segment) {
                        $walletData = Wallet::getByClient($segment, $currency);
                        if (!is_null($walletData)) {
                            $usersWallet[] = [
                                'user' => $walletData->data->wallet->user,
                                'wallet' => $walletData->data->wallet->id
                            ];
                            $walletUsers[] = $walletData->data->wallet->id;
                        }
                    }
                    break;
                }
            }

            $requestData = [
                'users' => $walletUsers,
                'currency' => $currency,
                'client_secret' => $credentials->client_credentials_grant_secret,
                'amount' => $request->amount,
                'quantity' => $request->quantity,
                'expirationDate' => $expirationDate,
                'game_code' => $request->game,
            ];

            $url = env('TRIPLE_CHERRY_FREE_URL'). "/create";
            $curl = Curl::to($url)
                ->withData($requestData)
                ->post();

            $response = json_decode($curl);
            if ($response->status == Status::$ok) {
                foreach ($response->data as $freeSpinsData) {
                    foreach ($usersWallet as $wallet) {
                        if ($wallet['wallet'] == $freeSpinsData->user) {
                            $amount = $freeSpinsData->amount / 1000;
                            $additionalData = [
                                'amount' => $amount,
                                'expiration_date' => $expirationDate,
                                'code_reference' => $freeSpinsData->code_reference,
                                'operator' => auth()->user()->id,
                            ];

                            $dataFreeSpins = [
                                'users' => [$wallet['user']],
                                'games_id' => [$request->game],
                                'currency_iso' => $currency,
                                'provider_id' => $request->provider,
                                'whitelabel_id' => $whitelabel,
                                'free_spins' => $freeSpinsData->quantity,
                                'data' => $additionalData,
                                'status' => FreeSpinsStatus::$enable
                            ];
                            $this->dotSuiteFreeSpinsRepo->store($dataFreeSpins);
                        }
                    }
                }

                $data = [
                    'title' => _i('Free spins assigned'),
                    'message' => _i('The free spins have been awarded successfully'),
                    'close' => _i('Close')
                ];
                return Utils::successResponse($data);
            }else if($response->status == Status::$error) {
                $data = [
                    'title' => _i('Failed'),
                    'message' => _i('Please reload and try again'),
                    'close' => _i('Close'),
                ];
                return Utils::errorResponse(Codes::$forbidden, $data);
            } else if ($response->status == Status::$failed){
                $data = [
                    'title' => _i('Error'),
                    'message' => _i('Could not load save data'),
                    'close' => _i('Close'),
                ];
                return Utils::errorResponse(Codes::$forbidden, $data);
            }
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     * Provider currency
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function providerCurrency(Request $request, ProvidersRepo $providersRepo)
    {
        try {
            $currency = $request->currency;
            if (!is_null($currency)) {
                $whitelabel = Configurations::getWhitelabel();
                $providers = $providersRepo->getByTypesAndActives([ProviderTypes::$casino, ProviderTypes::$live_casino, ProviderTypes::$virtual, ProviderTypes::$sportbook, ProviderTypes::$racebook, ProviderTypes::$live_games, ProviderTypes::$poker], $whitelabel, $currency);
            } else {
                $providers = [];
            }

            $data = [
                'providers' => $providers,
            ];

            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'currency' => $currency]);
            return Utils::failedResponse();
        }
    }

    /**
     * Show view users totals report dotsuite
     *
     * @return Factory|View
     */
    public function usersTotals()
    {
        $data['title'] = _i('Users totals report');
        return view('back.dotsuite.reports.users-totals', $data);
    }

    /**
     * Get users report data dotsuite
     *
     * @param Request $request
     * @param null|string $startDate Start date to filter
     * @param null|string $endDate End date to filter
     * @param null|int $provider Provider ID
     * @return Response
     */
    public function usersTotalsData(Request $request, $startDate = null, $endDate = null)
    {
        if (count($request->all()) > 1) {
            $this->validate($request, [
                'currency' => 'required',
            ]);
        }

        try {
            $currency = $request->currency;
            if (!is_null($startDate) && !is_null($endDate)) {
                $provider = $request->provider;
                $whitelabel = Configurations::getWhitelabel();;
                $startDate = Utils::startOfDayUtc($startDate);
                $endDate = Utils::endOfDayUtc($endDate);
                if (!is_null($provider)) {
                    if (
                        $provider == Providers::$patagonia ||
                        $provider == Providers::$pg_soft ||
                        $provider == Providers::$booongo ||
                        $provider == Providers::$game_art ||
                        $provider == Providers::$booming_games ||
                        $provider == Providers::$kiron_interactive ||
                        $provider == Providers::$hacksaw_gaming ||
                        $provider == Providers::$triple_cherry ||
                        $provider == Providers::$espresso_games
                    ) {
                        $provider = Providers::$salsa_gaming;
                    }

                    if ($provider == Providers::$spinmatic) {
                        $provider = Providers::$golden_race;
                    }
                    $nowUsersTotals = $this->dotSuiteRepo->getUsersTotals($whitelabel, $startDate, $endDate, $currency, $provider);
                } else {
                    $nowUsersTotals = $this->dotSuiteRepo->getUsersTotals($whitelabel, $startDate, $endDate, $currency, $provider);
                }
            } else {
                $nowUsersTotals = [];
            }
            $totals = $this->dotSuiteCollection->usersTotals($nowUsersTotals, $currency);
            return Utils::successResponse($totals);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all(), 'start_date' => $startDate, 'end_date' => $endDate]);
            return Utils::failedResponse();
        }
    }

    /**
     * Show view most played games report
     *
     * @return Factory|View
     */
    public function mostPlayedGames()
    {
        $data['providers'] = $this->providersRepo->getByTypes([ProviderTypes::$casino, ProviderTypes::$live_casino, ProviderTypes::$virtual, ProviderTypes::$sportbook, ProviderTypes::$racebook, ProviderTypes::$live_games, ProviderTypes::$poker]);
        $data['title'] = _i('Most played games report');
        return view('back.dotsuite.reports.most-played-games', $data);
    }

    /**
     * Get most played report data
     *
     * @param Request $request
     * @param null|string $startDate Start date to filter
     * @param null|string $endDate End date to filter
     * @param null|int $provider Provider ID
     * @return Response
     */
    public function mostPlayedGamesData(Request $request, $startDate = null, $endDate = null, $provider = null)
    {
        try {
            if (!is_null($startDate) && !is_null($endDate)) {
                $startDate = Utils::startOfDayUtc($startDate);
                $endDate = Utils::endOfDayUtc($endDate);
                $whitelabel = Configurations::getWhitelabel();
                $currency = session('currency');
                if (!is_null($provider)) {
                    if (
                        $provider == Providers::$patagonia ||
                        $provider == Providers::$pg_soft ||
                        $provider == Providers::$booongo ||
                        $provider == Providers::$game_art ||
                        $provider == Providers::$booming_games ||
                        $provider == Providers::$kiron_interactive ||
                        $provider == Providers::$hacksaw_gaming ||
                        $provider == Providers::$triple_cherry ||
                        $provider == Providers::$espresso_games
                    ) {
                        $provider = Providers::$salsa_gaming;
                    }

                    if ($provider == Providers::$spinmatic) {
                        $provider = Providers::$golden_race;
                    }

                    $mostPlayedGames = $this->dotSuiteRepo->getMostPlayedGames($whitelabel, $startDate, $endDate, $currency, $provider);
                } else {
                    $mostPlayedGames = $this->dotSuiteRepo->getMostPlayedGames($whitelabel, $startDate, $endDate, $currency, $provider);
                }

            } else {
                $mostPlayedGames = [];
            }
            $this->dotSuiteCollection->mostPlayedGames($mostPlayedGames);
            $data = [
                'games' => []
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all(), 'start_date' => $startDate, 'end_date' => $endDate]);
            return Utils::failedResponse();
        }
    }

    /**
     * Show view games totals report
     *
     * @return Factory|View
     */
    public function gamesTotals()
    {
        $data['providers'] = $this->providersRepo->getByTypes([ProviderTypes::$casino, ProviderTypes::$live_casino, ProviderTypes::$virtual, ProviderTypes::$sportbook, ProviderTypes::$racebook, ProviderTypes::$live_games, ProviderTypes::$poker]);
        $data['title'] = _i('Game totals report');
        return view('back.dotsuite.reports.games-totals', $data);
    }

    /**
     * Get games report data
     *
     * @param Request $request
     * @param null|string $startDate Start date to filter
     * @param null|string $endDate End date to filter
     * @param null|int $provider Provider ID
     * @return Response
     */
    public function gamesTotalsData(Request $request, $startDate = null, $endDate = null, $provider = null)
    {
        try {
            $nowGamesTotals = null;

            if (!is_null($startDate) && !is_null($endDate)) {
                $whitelabel = Configurations::getWhitelabel();
                $currency = session('currency');
                $startDate = Utils::startOfDayUtc($startDate);
                $endDate = Utils::endOfDayUtc($endDate);

                if (!is_null($provider)) {
                    if (
                        $provider == Providers::$patagonia ||
                        $provider == Providers::$pg_soft ||
                        $provider == Providers::$booongo ||
                        $provider == Providers::$game_art ||
                        $provider == Providers::$booming_games ||
                        $provider == Providers::$kiron_interactive ||
                        $provider == Providers::$hacksaw_gaming ||
                        $provider == Providers::$triple_cherry ||
                        $provider == Providers::$espresso_games
                    ) {
                        $provider = Providers::$salsa_gaming;
                    }

                    if ($provider == Providers::$spinmatic) {
                        $provider = Providers::$golden_race;
                    }

                    $nowGamesTotals = $this->dotSuiteRepo->getGamesTotals($whitelabel, $startDate, $endDate, $currency, $provider);
                } else {
                    $nowGamesTotals = $this->dotSuiteRepo->getGamesTotals($whitelabel, $startDate, $endDate, $currency, $provider);
                }
                $totals = $this->dotSuiteCollection->gamesTotals($nowGamesTotals);
            } else {
                $totals = [
                    'games' => [],
                    'totals' => []
                ];
            }
            return Utils::successResponse($totals);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all(), 'start_date' => $startDate, 'end_date' => $endDate]);
            return Utils::failedResponse();
        }
    }

    /**
     * Providers
     *
     * @param Request $request
     * @return Response
     */
    public function providersData(Request $request)
    {
        try {
            $providerType = $request->type;
            $providers = [];
            if (!is_null($providerType)) {
                $providers = $this->providersRepo->getByTypes([$providerType]);
            }
            $data = [
                'providers' => $providers
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }
}

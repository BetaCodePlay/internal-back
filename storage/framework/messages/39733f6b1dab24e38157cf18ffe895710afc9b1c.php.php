<?php

namespace App\Http\Controllers;

use Dotworkers\Configurations\Enums\Providers;
use Dotworkers\Configurations\Enums\ProviderTypes;
use Dotworkers\Configurations\Enums\Codes;
use App\Core\Repositories\CredentialsRepo;
use App\Core\Repositories\CurrenciesRepo;
use App\Core\Repositories\ProvidersRepo;
use App\Core\Repositories\ProvidersTypesRepo;
use App\Core\Collections\CredentialsCollection;
use App\Core\Collections\ProvidersCollection;
use App\Whitelabels\Repositories\WhitelabelsRepo;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Configurations\Enums\Components;
use Dotworkers\Configurations\Utils;
use Illuminate\Http\Request;

/**
 * Class CoreController
 *
 * This class allows to manage configurations requests
 *
 * @package App\Http\Controllers
 * @author  Eborio Linarez
 */
class ConfigurationsController extends Controller
{
    /**
     * WhitelabelsRepo
     *
     * @var WhitelabelsRepo
     */
    private $whitelabelsRepo;

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
     * ProvidersCollection
     *
     * @var ProvidersCollection
     */
    private $providersCollection;

    /**
     * CurrenciesRepo
     *
     * @var CurrenciesRepo
     */
    private $currenciesRepo;

    /**
     * CredentialsRepo
     *
     * @var CredentialsRepo
     */
    private $credentialsRepo;

    /**
     * CredentialsCollection
     *
     * @var CredentialsCollection
     */
    private $credentialsCollection;

    /**
     * ConfigurationsController constructor
     *
     * @param WhitelabelsRepo $whitelabelsRepo
     * @param ProvidersRepo $providersRepo
     * @param ProvidersCollection $providersCollection
     * @param CurrenciesRepo $currenciesRepo
     * @param CredentialsRepo $credentialsRepo
     * @param CredentialsCollection $credentialsCollection
     */
    public function __construct(WhitelabelsRepo $whitelabelsRepo, ProvidersRepo $providersRepo, ProvidersCollection $providersCollection, CurrenciesRepo $currenciesRepo, CredentialsRepo $credentialsRepo, CredentialsCollection $credentialsCollection, ProvidersTypesRepo $providersTypesRepo)
    {
        $this->whitelabelsRepo = $whitelabelsRepo;
        $this->providersRepo = $providersRepo;
        $this->providersCollection = $providersCollection;
        $this->currenciesRepo = $currenciesRepo;
        $this->credentialsRepo = $credentialsRepo;
        $this->credentialsCollection = $credentialsCollection;
        $this->providersTypesRepo = $providersTypesRepo;
    }

    /**
     * Get credentials view
     *
     * @param int $provider Provider
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function viewCredentials()
    {
        try {
            $data['title'] = _i('Providers Credentials');
            $data['provider_types'] = $this->providersTypesRepo->all();
            $data['whitelabels'] = $this->whitelabelsRepo->all();
            $data['currency_client'] = $this->currenciesRepo->all();
            return view('back.configurations.credentials.providers-credentials', $data );
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    /**
     * Type providers
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function providerTypes(Request $request)
    {
        try {
            $currency = $request->currency;
            $whitelabel = Configurations::getWhitelabel();
            $providersTypes = [ProviderTypes::$casino, ProviderTypes::$live_casino, ProviderTypes::$virtual, ProviderTypes::$sportbook, ProviderTypes::$racebook, ProviderTypes::$live_games, ProviderTypes::$poker];
            $providerTypesData = $this->providersTypesRepo->getByWhitelabel($whitelabel, $currency, $providersTypes);
            $this->credentialsCollection->formatTypeProviders($providerTypesData);
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
     * Exclude providers
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
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

    public function credentialsProvidersDetails(){
        try {
            $data['title'] = _i('Providers Credentials');
            $data['whitelabels'] = $this->whitelabelsRepo->all();
            $data['currency_client'] = $this->currenciesRepo->all();
            return view('back.configurations.credentials.list-credentials', $data );
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

     /**
     * Get credentials view
     *
     * @param int $provider Provider
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function credentials($provider)
    {
        try {
            $data['whitelabels'] = $this->whitelabelsRepo->all();
            $data['currency_client'] = $this->currenciesRepo->all();
            switch ($provider) {
                case Providers::$ocb_slots:
                {
                    $data['title'] = _i('OCB Slots credentials');
                    $data['provider'] = Providers::$ocb_slots;
                    break;
                }
                case Providers::$betpay:
                {
                    $data['title'] = _i('BetPay credentials');
                    $data['provider'] = Providers::$betpay;
                    break;
                }
                case Providers::$ezugi:
                {
                    $data['title'] = _i('Ezugi credentials');
                    $data['provider'] = Providers::$ezugi;
                    break;
                }
                case Providers::$vivo_gaming:
                {
                    $data['title'] = _i('VivoGaming credentials');
                    $data['provider'] = Providers::$vivo_gaming;
                    break;
                }
                case Providers::$lucky_spins:
                {
                    $data['title'] = _i('Lucky Spins credentials');
                    $data['provider'] = Providers::$lucky_spins;
                    break;
                }
                case Providers::$wnet_games:
                {
                    $data['title'] = _i('WNet Games credentials');
                    $data['provider'] = Providers::$wnet_games;
                    break;
                }
                case Providers::$golden_race:
                {
                    $data['title'] = _i('Golden Race credentials');
                    $data['provider'] = Providers::$golden_race;
                    break;
                }
                case Providers::$spinmatic:
                {
                    $data['title'] = _i('Spinmatic credentials');
                    $data['provider'] = Providers::$spinmatic;
                    break;
                }
                case Providers::$caleta_gaming:
                {
                    $data['title'] = _i('Caleta Gaming credentials');
                    $data['provider'] = Providers::$caleta_gaming;
                    break;
                }
                case Providers::$sisvenprol:
                {
                    $data['title'] = _i('Sisvenprol credentials');
                    $data['provider'] = Providers::$sisvenprol;
                    break;
                }
                case Providers::$xlive:
                {
                    $data['title'] = _i('XLive credentials');
                    $data['provider'] = Providers::$xlive;
                    break;
                }
                case Providers::$tv_bet:
                {
                    $data['title'] = _i('TV Bet credentials');
                    $data['provider'] = Providers::$tv_bet;
                    break;
                }
                case Providers::$lega_jackpot:
                {
                    $data['title'] = _i('Lega Jackpot credentials');
                    $data['provider'] = Providers::$lega_jackpot;
                    break;
                }
                case Providers::$virtual_generation:
                {
                    $data['title'] = _i('Virtual Generation credentials');
                    $data['provider'] = Providers::$virtual_generation;
                    break;
                }
                case Providers::$iq_soft:
                {
                    $data['title'] = _i('IQSoft credentials');
                    $data['provider'] = Providers::$iq_soft;
                    break;
                }
                case Providers::$inmejorable:
                {
                    $data['title'] = _i('El Inmejorable credentials');
                    $data['provider'] = Providers::$inmejorable;
                    break;
                }
                case Providers::$ka_gaming:
                {
                    $data['title'] = _i('Ka Gaming credentials');
                    $data['provider'] = Providers::$ka_gaming;
                    break;
                }
                case Providers::$gamzix:
                {
                    $data['title'] = _i('Gamzix credentials');
                    $data['provider'] = Providers::$gamzix;
                    break;
                }
                case Providers::$center_horses:
                {
                    $data['title'] = _i('Center Horses credentials');
                    $data['provider'] = Providers::$center_horses;
                    break;
                }
                case Providers::$andes_sportbook:
                {
                    $data['title'] = _i('Andes SportBook credentials');
                    $data['provider'] = Providers::$andes_sportbook;
                    break;
                }
                case Providers::$vls:
                {
                    $data['title'] = _i('VLS credentials');
                    $data['provider'] = Providers::$vls;
                    break;
                }
                case Providers::$dlv:
                {
                    $data['title'] = _i('DLV credentials');
                    $data['provider'] = Providers::$dlv;
                    break;
                }
                case Providers::$color_spin:
                {
                    $data['title'] = _i('ColorSpin credentials');
                    $data['provider'] = Providers::$color_spin;
                    break;
                }
                case Providers::$sportbook:
                {
                    $data['title'] = _i('SportBook credentials');
                    $data['provider'] = Providers::$sportbook;
                    break;
                }
                case Providers::$salsa_gaming:
                {
                    $data['title'] = _i('Salsa Gaming credentials');
                    $data['provider'] = Providers::$salsa_gaming;
                    break;
                }
                case Providers::$patagonia:
                {
                    $data['title'] = _i('Patagonia credentials');
                    $data['provider'] = Providers::$patagonia;
                    break;
                }
                case Providers::$pg_soft:
                {
                    $data['title'] = _i('PG Soft credentials');
                    $data['provider'] = Providers::$pg_soft;
                    break;
                }
                case Providers::$booongo:
                {
                    $data['title'] = _i('Booongo credentials');
                    $data['provider'] = Providers::$booongo;
                    break;
                }
                case Providers::$game_art:
                {
                    $data['title'] = _i('GameArt credentials');
                    $data['provider'] = Providers::$game_art;
                    break;
                }
                case Providers::$booming_games:
                {
                    $data['title'] = _i('Booming Games credentials');
                    $data['provider'] = Providers::$booming_games;
                    break;
                }
                case Providers::$kiron_interactive:
                {
                    $data['title'] = _i('Kiron Interactive credentials');
                    $data['provider'] = Providers::$kiron_interactive;
                    break;
                }
                case Providers::$hacksaw_gaming:
                {
                    $data['title'] = _i('Hacksaw Gaming credentials');
                    $data['provider'] = Providers::$hacksaw_gaming;
                    break;
                }
                case Providers::$triple_cherry:
                {
                    $data['title'] = _i('Triple Cherry credentials');
                    $data['provider'] = Providers::$triple_cherry;
                    break;
                }
                case Providers::$espresso_games:
                {
                    $data['title'] = _i('Espresso Games credentials');
                    $data['provider'] = Providers::$espresso_games;
                    break;
                }
                case Providers::$pragmatic_play:
                {
                    $data['title'] = _i('Pragmatic Play credentials');
                    $data['provider'] = Providers::$pragmatic_play;
                    break;
                }
                case Providers::$mascot_gaming:
                {
                    $data['title'] = _i('Mascot Gaming credentials');
                    $data['provider'] = Providers::$mascot_gaming;
                    break;
                }
                case Providers::$event_bet:
                {
                    $data['title'] = _i('Event Bet credentials');
                    $data['provider'] = Providers::$event_bet;
                    break;
                }
                case Providers::$branka:
                {
                    $data['title'] = _i('Branka Team credentials');
                    $data['provider'] = Providers::$branka;
                    break;
                }
                case Providers::$branka_originals:
                {
                    $data['title'] = _i('Branka Originals credentials');
                    $data['provider'] = Providers::$branka_originals;
                    break;
                }
                case Providers::$pragmatic_play_live_casino:
                {
                    $data['title'] = _i('Pragmatic Play Live Casino credentials');
                    $data['provider'] = Providers::$pragmatic_play_live_casino;
                    break;
                }
                case Providers::$play_son:
                {
                    $data['title'] = _i('Playson');
                    $data['provider'] = Providers::$play_son;
                    break;
                }
                case Providers::$evolution:
                {
                    $data['title'] = _i('Evolution');
                    $data['provider'] = Providers::$evolution;
                    break;
                }
                case Providers::$triple_cherry_original:
                {
                    $data['title'] = _i('Triple Cherry Original');
                    $data['provider'] = Providers::$triple_cherry_original;
                    break;
                }
                case Providers::$mancala_gaming:
                {
                    $data['title'] = _i('Mancala Gaming');
                    $data['provider'] = Providers::$mancala_gaming;
                    break;
                }
                case Providers::$wazdan:
                {
                    $data['title'] = _i('Wazdan');
                    $data['provider'] = Providers::$wazdan;
                    break;
                }
                case Providers::$red_rake:
                {
                    $data['title'] = _i('Red Rake');
                    $data['provider'] = Providers::$red_rake;
                    break;
                }
                case Providers::$belatra:
                {
                    $data['title'] = _i('Belatra');
                    $data['provider'] = Providers::$belatra;
                    break;
                }
                case Providers::$sw3:
                {
                    $data['title'] = _i('SW3');
                    $data['provider'] = Providers::$sw3;
                    break;
                }
                case Providers::$telegram:
                {
                    $data['title'] = _i('Telegram');
                    $data['provider'] = Providers::$telegram;
                    break;
                }
                case Providers::$altenar:
                {
                    $data['title'] = _i('Altenar');
                    $data['provider'] = Providers::$altenar;
                    break;
                }
                case Providers::$live_player:
                {
                    $data['title'] = _i('Live Player');
                    $data['provider'] = Providers::$live_player;
                    break;
                }
                case Providers::$universal_soft:
                {
                    $data['title'] = _i('Universal Soft ');
                    $data['provider'] = Providers::$universal_soft;
                    break;
                }
                case Providers::$booongo_original:
                {
                    $data['title'] = _i('Booongo Original');
                    $data['provider'] = Providers::$booongo_original;
                    break;
                }
                case Providers::$evo_play:
                {
                    $data['title'] = _i('Evoplay');
                    $data['provider'] = Providers::$evo_play;
                    break;
                }
                case Providers::$i_soft_bet:
                {
                    $data['title'] = _i('ISoftBet');
                    $data['provider'] = Providers::$i_soft_bet;
                    break;
                }
                case Providers::$ortiz_gaming:
                {
                    $data['title'] = _i('Ortiz Gaming');
                    $data['provider'] = Providers::$ortiz_gaming;
                    break;
                }
                case Providers::$urgent_games:
                {
                    $data['title'] = _i('Urgent Games');
                    $data['provider'] = Providers::$urgent_games;
                    break;
                }
                case Providers::$mohio:
                {
                    $data['title'] = _i('Mohio');
                    $data['provider'] = Providers::$mohio;
                    break;
                }
                case Providers::$veneto_sportbook:
                {
                    $data['title'] = _i('Vgcsports');
                    $data['provider'] = Providers::$veneto_sportbook;
                    break;
                }
                case Providers::$evolution_slots:
                {
                    $data['title'] = _i('Evolution Slots credentials');
                    $data['provider'] = Providers::$evolution_slots;
                    break;
                }
                case Providers::$vibra:
                {
                    $data['title'] = _i('Vibra');
                    $data['provider'] = Providers::$vibra;
                    break;
                }
                case Providers::$kalamba:
                {
                    $data['title'] = _i('kalamba');
                    $data['provider'] = Providers::$kalamba;
                    break;
                }
                case Providers::$fbm_gaming:
                {
                    $data['title'] = _i('FBM Gaming');
                    $data['provider'] = Providers::$fbm_gaming;
                    break;
                }
                case Providers::$one_touch:
                {
                    $data['title'] = _i('One Touch');
                    $data['provider'] = Providers::$one_touch;
                    break;
                }
                case Providers::$greentube:
                {
                    $data['title'] = _i('GreenTube');
                    $data['provider'] = Providers::$greentube;
                    break;
                }
                case Providers::$platipus:
                {
                    $data['title'] = _i('Platipus');
                    $data['provider'] = Providers::$platipus;
                    break;
                }
                case Providers::$digitain:
                {
                    $data['title'] = _i('Digitain');
                    $data['provider'] = Providers::$digitain;
                    break;
                }
                case Providers::$beter:
                {
                    $data['title'] = _i('Beter');
                    $data['provider'] = Providers::$beter;
                    break;
                }
            }
            return view('back.configurations.credentials.credentials', $data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    /**
     * Get credentials data
     *
     * @param int $provider Provider
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function credentialsData($provider= null)
    {
        try {
            if (!is_null($provider)) {
                $provider = (int)$provider;
                $credentials = $this->credentialsRepo->searchByProvider($provider);
                if (!is_null($credentials)) {
                    $this->credentialsCollection->formatSearch($credentials, $provider);
                    $data = [
                        'credentials' => $credentials
                    ];
                } else {
                    $data = [
                        'credentials' => []
                    ];
                }
            } else {
                $data = [
                    'credentials' => []
                ];
            }
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    public function excludeProviderUserList($whitelabel= null)
    {
        try {
            if (!is_null($whitelabel)) {
                $whitelabel = (int)$whitelabel;
                $users =  $this->usersRepo->getExcludeProviderUser($whitelabel);
                if (!is_null($users)) {
                    $this->usersCollection->formatExcludeProviderUser($users);
                    $data = [
                        'users' =>  $users
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
     * Get credentials delete
     *
     * @param int $client Client
     * @param int $provider Provider
     * @param int $currency Currency
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function credentialsDelete($client, $provider, $currency)
    {
        try {
            $provider = (int)$provider;
            $client = (int)$client;
            $this->credentialsRepo->deleteCredential($client, $provider, $currency);
            $data = [
                'title' => _i('Deleted credential'),
                'message' => _i('Credential data was delete correctly'),
                'close' => _i('Close')
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    /**
     * Get credentials details
     *
     * @param int $client Client
     * @param int $provider Provider
     * @param int $currency Currency
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function credentialsDetails($client, $provider, $currency)
    {
        try {
            $provider = (int)$provider;
            $client = (int)$client;
            $credentials = $this->credentialsRepo->searchByCredential($client, $provider, $currency);
            $data = [
                'credentials' => $credentials
            ];
            $data['whitelabels'] = $this->whitelabelsRepo->find($client);
            $data['title'] = _i('Details credentials');
            $data['provider'] = $provider;
            $data['last_client'] = $client;
            $data['last_currency'] = $currency;
            return view('back.configurations.credentials.details', $data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    /**
     * Show levels configurations
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function levels()
    {
        try {
            $data['whitelabels'] = $this->whitelabelsRepo->all();
            $data['title'] = _i('Set user levels');
            return view('back.configurations.levels', $data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    /**
     * Get levels data
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function levelsData(Request $request)
    {
        try {
            $levels = Configurations::getLevels();
            $data = [
                'levels' => $levels
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     * Show main route configuration
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function mainRoute()
    {
        try {
            $data['whitelabels'] = $this->whitelabelsRepo->all();
            $data['title'] = _i('Configure main routes');
            return view('back.configurations.main-route', $data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    /**
     * Get main route data
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function mainRouteData(Request $request)
    {
        try {
            $desktop = Configurations::getMainRoute($mobile = false);
            $mobile = Configurations::getMainRoute($mobile = true);
            $data = [
                'desktop' => $desktop,
                'mobile' => $mobile
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     * Show providers list
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function providersList()
    {
        $data['title'] = _i('Providers list');
        return view('back.configurations.providers.index', $data);
    }

    /**
     * Get providers list data
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function providersListData()
    {
        try {
            $providers = $this->providersRepo->getProviders();
            $this->providersCollection->formatProviders($providers);
            $data = [
                'providers' => $providers
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }

    }

    /**
     * Show registration login configuration
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function registrationLogin()
    {
        try {
            $data['whitelabels'] = $this->whitelabelsRepo->all();
            $data['title'] = _i('Configure registration and login');
            return view('back.configurations.registration-login', $data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    /**
     * Get registration login data
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function registrationLoginData(Request $request)
    {
        try {
            $registration = Configurations::getRegistration();
            $login = Configurations::getLogin();
            $data = [
                'registration' => $registration,
                'login' => $login
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     * status Credentials
     *
     * @param Request $request
     * @return mixed
     */
    public function statusCredentials(Request $request)
    {
        try {
            if ($request->get('name') === 'status') {
                $status = ($request->get('value') === 'true' ? true : false);
                $id = $request->get('client_id');
                $client = "";
                $provider = "";
                $currency = "";
                $separator = explode("|", $id);
                $client = (int)$separator[0];
                $provider = (int)$separator[1];
                $currency = $separator[2];
            }
            $dataCredentials = [
                'status' => $status
            ];
            $providers = $this->credentialsRepo->update($client, $currency, $provider, $dataCredentials);
            $data = [
                'providers' => $providers
            ];
            $data = [
                'title' => _i('Provider updated'),
                'message' => _i('Provider updated successfully'),
                'close' => _i('Close')
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     * status Providers
     *
     * @param Request $request
     * @return mixed
     */
    public function statusProviders(Request $request)
    {
        try {
            if ($request->get('name') === 'status') {
                $status = ($request->get('value') === 'true' ? true : false);
                $provider = (int)$request->get('provider_id');
            }
            $dataProviders = [
                'status' => $status
            ];
            $this->providersRepo->update($provider, $dataProviders);
            $data = [
                'title' => _i('Provider updated'),
                'message' => _i('Provider updated successfully'),
                'close' => _i('Close')
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     * Store credencials
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'client' => 'required',
            'currency' => 'required',
            'exclude_providers' => 'required',
            'percentage' => 'required'
        ]);
        $usedcredencial = 0;
        $client = (int)$request->client;
        $provider = (int)$request->exclude_providers;
        $currency = $request->currency;
        $percentage = $request->percentage / 100;
        $usedcredencial = $this->credentialsRepo->searchByCredential($client, $provider, $currency);
        if (!is_null($usedcredencial)) {
            $data = [
                'title' => _i('Used credential'),
                'message' => _i('The credential data already exists', $currency),
                'close' => _i('Close')
            ];
            return Utils::errorResponse(Codes::$forbidden, $data);
        }
        $credentialData = [
            'client_id' => $client,
            'provider_id' => $provider,
            'currency_iso' => $currency,
            'percentage' => $percentage,
            'status' => true,
            'data' => []
        ];
        $this->credentialsRepo->store($credentialData);

        $data = [
            'title' => _i('Saved credential'),
            'message' => _i('Credential data was saved correctly'),
            'close' => _i('Close')
        ];
        return Utils::successResponse($data);
    }

     /**
     * Get credentials data
     *
     * @param int $provider Provider
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function providersCredentialsData(Request $request)
    {
        if (count($request->all()) > 1) {
            $this->validate($request, [
                'client' => 'required',
                'provider' => 'required',
                'type' => 'required',
            ]);
        }
        try {
            $client = $request->client;
            $type = $request->type;
            $currency = $request->currency;
            $provider = $request->provider;
            if (!is_null($provider)) {
                $credentials = $this->credentialsRepo->searchByProviderType($client,$type,$currency,$provider);
                if (!is_null($credentials)) {
                    $this->credentialsCollection->formatCredentials($credentials);
                    $data = [
                        'credentials' => $credentials
                    ];
                } else {
                    $data = [
                        'credentials' => []
                    ];
                }
            } else {
                $data = [
                    'credentials' => []
                ];
            }
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
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
            'currencies' => 'required|array|min:1',
            'provider' => 'required',
            'percentage' => 'required'
        ]);

        $usedcredencial = 0;
        $client = (int)$request->client;
        $provider = (int)$request->provider;
        $currencies = $request->currencies;
        $percentage = $request->percentage / 100;
        foreach ($currencies as $currency) {
            $usedcredencial = $this->credentialsRepo->searchByCredential($client, $provider, $currency);
            if (!is_null($usedcredencial)) {
                $data = [
                    'title' => _i('Used credential'),
                    'message' => _i('The credential data already exists', $currency),
                    'close' => _i('Close')
                ];
                return Utils::errorResponse(Codes::$forbidden, $data);
            }
            switch ($provider) {
                case Providers::$pragmatic_play_live_casino:
                {
                    $this->validate($request, [
                        'secure_login' => 'required',
                        'url_launch' => 'required',
                        'url_api' => 'required'
                    ]);
                    $credentialData = [
                        'client_id' => $client,
                        'provider_id' => $provider,
                        'currency_iso' => $currency,
                        'percentage' => $percentage,
                        'status' => true,
                        'data' => [
                            'secure_login' => $request->secure_login,
                            'url_launch' => $request->url_launch,
                            'url_api' => $request->url_api
                        ]
                    ];
                    break;
                }
                case Providers::$play_son:
                {
                    $this->validate($request, [
                        'partner' => 'required'
                    ]);
                    $credentialData = [
                        'client_id' => $client,
                        'provider_id' => $provider,
                        'currency_iso' => $currency,
                        'percentage' => $percentage,
                        'status' => true,
                        'data' => [
                            'partner' => $request->partner
                        ]
                    ];
                    break;
                }
                case Providers::$triple_cherry_original:
                {
                    $this->validate($request, [
                        'client_id' => 'required',
                        'client_secret' => 'required',
                        'partner_id' => 'required'
                    ]);
                    $credentialData = [
                        'client_id' => $client,
                        'provider_id' => $provider,
                        'currency_iso' => $currency,
                        'percentage' => $percentage,
                        'status' => true,
                        'data' => [
                            'client_id' => $request->client_id,
                            'client_secret' => $request->client_secret,
                            'partner_id' => $request->partner_id
                        ]
                    ];
                    break;
                }
                case Providers::$mancala_gaming:
                {
                    $this->validate($request, [
                        'brand_name' => 'required',
                        'partnerID' => 'required',
                        'api_key' => 'required'
                    ]);
                    $credentialData = [
                        'client_id' => $client,
                        'provider_id' => $provider,
                        'currency_iso' => $currency,
                        'percentage' => $percentage,
                        'status' => true,
                        'data' => [
                            'brand_name' => $request->brand_name,
                            'partnerID' => $request->partnerID,
                            'api_key' => $request->api_key
                        ]
                    ];
                    break;
                }
                case Providers::$wazdan:
                {
                    $this->validate($request, [
                        'code' => 'required',
                        'operator' => 'required',
                        'license' => 'required'
                    ]);
                    $credentialData = [
                        'client_id' => $client,
                        'provider_id' => $provider,
                        'currency_iso' => $currency,
                        'percentage' => $percentage,
                        'status' => true,
                        'data' => [
                            'code' => $request->code,
                            'operator' => $request->operator,
                            'license' => $request->license
                        ]
                    ];
                    break;
                }
                case Providers::$red_rake:
                {
                    $this->validate($request, [
                        'operator_id' => 'required',
                        'pass_key' => 'required'
                    ]);
                    $credentialData = [
                        'client_id' => $client,
                        'provider_id' => $provider,
                        'currency_iso' => $currency,
                        'percentage' => $percentage,
                        'status' => true,
                        'data' => [
                            'operator_id' => $request->operator_id,
                            'pass_key' => $request->pass_key
                        ]
                    ];
                    break;
                }
                case Providers::$belatra:
                {
                    $this->validate($request, [
                        'casino_id' => 'required',
                        'token' => 'required'
                    ]);
                    $credentialData = [
                        'client_id' => $client,
                        'provider_id' => $provider,
                        'currency_iso' => $currency,
                        'percentage' => $percentage,
                        'status' => true,
                        'data' => [
                            'casino_id' => $request->casino_id,
                            'token' => $request->token
                        ]
                    ];
                    break;
                }
                case Providers::$telegram:
                {
                    $this->validate($request, [
                        'channel' => 'required',
                        'bot' => 'required',
                    ]);
                    $credentialData = [
                        'client_id' => $client,
                        'provider_id' => $provider,
                        'currency_iso' => $currency,
                        'percentage' => $percentage,
                        'status' => true,
                        'data' => [
                            'channel' => $request->channel,
                            'bot' => $request->bot,
                        ]
                    ];
                    break;
                }
                case Providers::$altenar:
                {
                    $this->validate($request, [
                        'site_id' => 'required',
                        'wallet_code' => 'required',
                        'path' => 'required',
                        'url' => 'required'
                    ]);
                    $credentialData = [
                        'client_id' => $client,
                        'provider_id' => $provider,
                        'currency_iso' => $currency,
                        'percentage' => $percentage,
                        'status' => true,
                        'data' => [
                            'site_id' => $request->site_id,
                            'wallet_code' => $request->wallet_code,
                            'path' => $request->path,
                            'url' => $request->url
                        ]
                    ];
                    break;
                }
                case Providers::$universal_soft:
                {
                    $this->validate($request, [
                        'id' => 'required',
                    ]);
                    $credentialData = [
                        'client_id' => $client,
                        'provider_id' => $provider,
                        'currency_iso' => $currency,
                        'percentage' => $percentage,
                        'status' => true,
                        'data' => [
                            'id' => $request->id,
                        ]
                    ];
                    break;
                }
                case Providers::$booongo_original:
                {
                    $this->validate($request, [
                        'project_name' => 'required',
                    ]);
                    $credentialData = [
                        'client_id' => $client,
                        'provider_id' => $provider,
                        'currency_iso' => $currency,
                        'percentage' => $percentage,
                        'status' => true,
                        'data' => [
                            'project_name' => $request->project_name,
                        ]
                    ];
                    break;
                }
                case Providers::$evo_play:
                {
                    $this->validate($request, [
                        'secret_key' => 'required',
                        'project_id' => 'required',
                    ]);
                    $credentialData = [
                        'client_id' => $client,
                        'provider_id' => $provider,
                        'currency_iso' => $currency,
                        'percentage' => $percentage,
                        'status' => true,
                        'data' => [
                            'secret_key' => $request->secret_key,
                            'project_id' => $request->project_id
                        ]
                    ];
                    break;
                }
                case Providers::$i_soft_bet:
                {
                    $this->validate($request, [
                        'license_id' => 'required',
                    ]);
                    $credentialData = [
                        'client_id' => $client,
                        'provider_id' => $provider,
                        'currency_iso' => $currency,
                        'percentage' => $percentage,
                        'status' => true,
                        'data' => [
                            'license_id' => $request->license_id,
                        ]
                    ];
                    break;
                }
                case Providers::$ortiz_gaming:
                {
                    $this->validate($request, [
                        'operator_id' => 'required',
                        'client_id' => 'required',
                    ]);
                    $credentialData = [
                        'client_id' => $client,
                        'provider_id' => $provider,
                        'currency_iso' => $currency,
                        'percentage' => $percentage,
                        'status' => true,
                        'data' => [
                            'operator_id' => $request->operator_id,
                            'client_id' => $request->client_id,
                        ]
                    ];
                    break;
                }
                case Providers::$urgent_games:
                {
                    $this->validate($request, [
                        'casino_id' => 'required',
                        'token' => 'required',
                        'key' => 'required',
                    ]);
                    $credentialData = [
                        'client_id' => $client,
                        'provider_id' => $provider,
                        'currency_iso' => $currency,
                        'percentage' => $percentage,
                        'status' => true,
                        'data' => [
                            'casino_id' => $request->casino_id,
                            'token' => $request->token,
                            'key' => $request->key,
                        ]
                    ];
                    break;
                }
                case Providers::$mohio:
                {
                    $this->validate($request, [
                        'portalId' => 'required',
                        'platformId' => 'required'
                    ]);
                    $credentialData = [
                        'client_id' => $client,
                        'provider_id' => $provider,
                        'currency_iso' => $currency,
                        'percentage' => $percentage,
                        'status' => true,
                        'data' => [
                            'portalId' => $request->portalId,
                            'platformId' => $request->platformId
                        ]
                    ];
                    break;
                }
                case Providers::$betpay:
                {
                    $this->validate($request, [
                        'client_credentials_grant_id' => 'required|integer',
                        'client_credentials_grant_secret' => 'required',
                        'password_grant_id' => 'required|integer',
                        'password_grant_secret' => 'required',
                    ]);
                    $credentialData = [
                        'client_id' => $client,
                        'provider_id' => $provider,
                        'currency_iso' => $currency,
                        'percentage' => $percentage,
                        'status' => true,
                        'data' => [
                            'client_credentials_grant_id' => $request->client_credentials_grant_id,
                            'client_credentials_grant_secret' => $request->client_credentials_grant_secret,
                            'password_grant_id' => $request->password_grant_id,
                            'password_grant_secret' => $request->password_grant_secret
                        ]
                    ];
                    break;
                }
                case Providers::$vivo_gaming:
                {
                    if ((!is_null($request->operator_id))
                        && (!is_null($request->pass_key))
                        && (!is_null($request->server_id))
                        ) {
                        $credentialData = [
                            'client_id' => $client,
                            'provider_id' => $provider,
                            'currency_iso' => $currency,
                            'percentage' => $percentage,
                            'status' => true,
                            'data' => [
                                'operator_id' => $request->operator_id,
                                'pass_key' => $request->pass_key,
                                'server_id' => $request->server_id
                            ]
                        ];
                    } else {
                        $credentialData = [
                            'client_id' => $client,
                            'provider_id' => $provider,
                            'currency_iso' => $currency,
                            'percentage' => $percentage,
                            'status' => true,
                            'data' => []
                        ];
                    }
                    break;
                }
                case Providers::$caleta_gaming:
                case Providers::$one_touch:
                {
                    $this->validate($request, [
                        'operator_id' => 'required'
                    ]);
                    $credentialData = [
                        'client_id' => $client,
                        'provider_id' => $provider,
                        'currency_iso' => $currency,
                        'percentage' => $percentage,
                        'status' => true,
                        'data' => [
                            'operator_id' => $request->operator_id
                        ]
                    ];
                    break;
                }
                case Providers::$sisvenprol:
                {
                    $this->validate($request, [
                        'client_id' => 'required',
                        'client_secret' => 'required',
                        'intermediary_id' => 'required'
                    ]);
                    $credentialData = [
                        'client_id' => $client,
                        'provider_id' => $provider,
                        'percentage' => $percentage,
                        'currency_iso' => $currency,
                        'percentage' => $percentage,
                        'status' => true,
                        'data' => [
                            'client_id' => $request->client_id,
                            'client_secret' => $request->client_secret,
                            'intermediary_id' => $request->intermediary_id
                        ]
                    ];
                    break;
                }
                case Providers::$xlive:
                {
                    $this->validate($request, [
                        'client_id' => 'required',
                        'client_secret' => 'required'
                    ]);
                    $credentialData = [
                        'client_id' => $client,
                        'provider_id' => $provider,
                        'currency_iso' => $currency,
                        'percentage' => $percentage,
                        'status' => true,
                        'data' => [
                            'client_id' => $request->client_id,
                            'client_secret' => $request->client_secret
                        ]
                    ];
                    break;
                }
                case Providers::$lega_jackpot:
                {
                    $this->validate($request, [
                        'site' => 'required'
                    ]);
                    $credentialData = [
                        'client_id' => $client,
                        'provider_id' => $provider,
                        'currency_iso' => $currency,
                        'percentage' => $percentage,
                        'status' => true,
                        'data' => [
                            'site' => $request->site
                        ]
                    ];
                    break;
                }
                case Providers::$virtual_generation:
                {
                    $this->validate($request, [
                        'private_key' => 'required',
                        'merchant_code' => 'required'
                    ]);
                    $credentialData = [
                        'client_id' => $client,
                        'provider_id' => $provider,
                        'currency_iso' => $currency,
                        'percentage' => $percentage,
                        'status' => true,
                        'data' => [
                            'private_key' => $request->private_key,
                            'merchant_code' => $request->merchant_code
                        ]
                    ];
                    break;
                }
                case Providers::$platipus:
                {
                    $this->validate($request, [
                        'api_key' => 'required',
                    ]);
                    $credentialData = [
                        'client_id' => $client,
                        'provider_id' => $provider,
                        'currency_iso' => $currency,
                        'percentage' => $percentage,
                        'status' => true,
                        'data' => [
                            'api_key' => $request->api_key,
                        ]
                    ];
                    break;
                }
                case Providers::$inmejorable:
                {
                    $this->validate($request, [
                        'api_key' => 'required',
                        'url' => 'required'
                    ]);
                    $credentialData = [
                        'client_id' => $client,
                        'provider_id' => $provider,
                        'currency_iso' => $currency,
                        'percentage' => $percentage,
                        'status' => true,
                        'data' => [
                            'api_key' => $request->api_key,
                            'url' => $request->url
                        ]
                    ];
                    break;
                }
                case Providers::$ka_gaming:
                {
                    $this->validate($request, [
                        'partner_name' => 'required',
                        'partner_access_key' => 'required'
                    ]);
                    $credentialData = [
                        'client_id' => $client,
                        'provider_id' => $provider,
                        'currency_iso' => $currency,
                        'percentage' => $percentage,
                        'status' => true,
                        'data' => [
                            'partner_name' => $request->partner_name,
                            'partner_access_key' => $request->partner_access_key
                        ]
                    ];
                    break;
                }
                case Providers::$gamzix:
                {
                    $this->validate($request, [
                        'code' => 'required',
                        'code_egt' => 'required',
                    ]);
                    $credentialData = [
                        'client_id' => $client,
                        'provider_id' => $provider,
                        'currency_iso' => $currency,
                        'percentage' => $percentage,
                        'status' => true,
                        'data' => [
                            'code' => $request->code,
                            'code_egt' => $request->code_egt
                        ]
                    ];
                    break;
                }
                case Providers::$pragmatic_play:
                {
                    $this->validate($request, [
                        'secure_login' => 'required',
                        'key' => 'required',
                        'url_launch' => 'required',
                        'url_api' => 'required'

                    ]);
                    $credentialData = [
                        'client_id' => $client,
                        'provider_id' => $provider,
                        'currency_iso' => $currency,
                        'percentage' => $percentage,
                        'status' => true,
                        'data' => [
                            'secure_login' => $request->secure_login,
                            'key' => $request->key,
                            'url_launch' => $request->url_launch,
                            'url_api' => $request->url_api
                        ]
                    ];
                    break;
                }
                case Providers::$vibra:
                {
                    $this->validate($request, [
                        'site_id' => 'required'

                    ]);
                    $credentialData = [
                        'client_id' => $client,
                        'provider_id' => $provider,
                        'currency_iso' => $currency,
                        'percentage' => $percentage,
                        'status' => true,
                        'data' => [
                            'site_id' => $request->site_id
                        ]
                    ];
                    break;
                }
                case Providers::$fbm_gaming:
                {
                    $this->validate($request, [
                        'casino_id' => 'required'

                    ]);
                    $credentialData = [
                        'client_id' => $client,
                        'provider_id' => $provider,
                        'currency_iso' => $currency,
                        'percentage' => $percentage,
                        'status' => true,
                        'data' => [
                            'casino_id' => $request->casino_id
                        ]
                    ];
                    break;
                }
                case Providers::$greentube:
                {
                    $this->validate($request, [
                        'secret_key' => 'required',
                        'authorization' => 'required'

                    ]);
                    $credentialData = [
                        'client_id' => $client,
                        'provider_id' => $provider,
                        'currency_iso' => $currency,
                        'percentage' => $percentage,
                        'status' => true,
                        'data' => [
                            'secret_key' => $request->secret_key,
                            'authorization' => $request->authorization
                        ]
                    ];
                    break;
                }
                case Providers::$ocb_slots:
                case Providers::$mascot_gaming:
                {
                    $this->validate($request, [
                        'bank_group' => 'required',
                        'restore_policy' => 'required',
                        'start_balance' => 'required|integer',
                    ]);
                    $credentialData = [
                        'client_id' => $client,
                        'provider_id' => $provider,
                        'currency_iso' => $currency,
                        'percentage' => $percentage,
                        'status' => true,
                        'data' => [
                            'bank_group' => $request->bank_group,
                            'restore_policy' => $request->restore_policy,
                            'start_balance' => $request->start_balance
                        ]
                    ];
                    break;
                }
                case Providers::$ezugi:
                case Providers::$lucky_spins:
                case Providers::$evolution_slots:
                case Providers::$evolution:
                {
                    $this->validate($request, [
                        'operator_id' => 'required|integer',
                        'secret_key' => 'required'
                    ]);
                    $credentialData = [
                        'client_id' => $client,
                        'provider_id' => $provider,
                        'currency_iso' => $currency,
                        'percentage' => $percentage,
                        'status' => true,
                        'data' => [
                            'operator_id' => $request->operator_id,
                            'secret_key' => $request->secret_key
                        ]
                    ];
                    break;
                }
                case Providers::$dlv:
                case Providers::$iq_soft:
                case Providers::$sw3:
                case Providers::$live_player:
                case  Providers::$kalamba:
                {
                    $credentialData = [
                        'client_id' => $client,
                        'provider_id' => $provider,
                        'currency_iso' => $currency,
                        'percentage' => $percentage,
                        'status' => true,
                        'data' => []
                    ];
                    break;
                }
                case Providers::$wnet_games:
                case Providers::$golden_race:
                case Providers::$spinmatic:
                case Providers::$veneto_sportbook:
                {
                    $this->validate($request, [
                        'private_key' => 'required',
                    ]);
                    $credentialData = [
                        'client_id' => $client,
                        'provider_id' => $provider,
                        'currency_iso' => $currency,
                        'percentage' => $percentage,
                        'status' => true,
                        'data' => [
                            'private_key' => $request->private_key
                        ]
                    ];
                    break;
                }
                case Providers::$tv_bet:
                case Providers::$event_bet:
                {
                    $this->validate($request, [
                        'client_id' => 'required',
                        'secret_key' => 'required'
                    ]);
                    $credentialData = [
                        'client_id' => $client,
                        'provider_id' => $provider,
                        'currency_iso' => $currency,
                        'percentage' => $percentage,
                        'status' => true,
                        'data' => [
                            'client_id' => $request->client_id,
                            'secret_key' => $request->secret_key
                        ]
                    ];
                    break;
                }
                case Providers::$center_horses:
                case Providers::$andes_sportbook:
                case Providers::$vls:
                case Providers::$color_spin:
                case Providers::$sportbook:
                {
                    $this->validate($request, [
                        'client_token' => 'required',
                    ]);
                    $credentialData = [
                        'client_id' => $client,
                        'provider_id' => $provider,
                        'currency_iso' => $currency,
                        'percentage' => $percentage,
                        'status' => true,
                        'data' => [
                            'client_token' => $request->client_token
                        ]
                    ];
                    break;
                }
                case Providers::$branka:
                case Providers::$branka_originals:
                {
                    $this->validate($request, [
                        'public_key' => 'required',
                        'secret_key' => 'required'
                    ]);
                    $credentialData = [
                        'client_id' => $client,
                        'provider_id' => $provider,
                        'currency_iso' => $currency,
                        'percentage' => $percentage,
                        'status' => true,
                        'data' => [
                            'public_key' => $request->public_key,
                            'secret_key' => $request->secret_key
                        ]
                    ];
                    break;
                }
                case Providers::$salsa_gaming:
                case Providers::$patagonia:
                case Providers::$pg_soft:
                case Providers::$booongo:
                case Providers::$game_art:
                case Providers::$booming_games:
                case Providers::$kiron_interactive:
                case Providers::$hacksaw_gaming:
                case Providers::$triple_cherry:
                case Providers::$espresso_games:
                {
                    $this->validate($request, [
                        'pn' => 'required',
                        'key' => 'required',
                    ]);
                    $credentialData = [
                        'client_id' => $client,
                        'provider_id' => $provider,
                        'currency_iso' => $currency,
                        'percentage' => $percentage,
                        'status' => true,
                        'data' => [
                            'pn' => $request->pn,
                            'key' => $request->key
                        ]
                    ];
                    break;
                }
                case Providers::$digitain:
                {
                    $this->validate($request, [
                        'private_key' => 'required',
                        'partner_id' => 'required',
                        'url_script' => 'required'
                    ]);
                    $credentialData = [
                        'client_id' => $client,
                        'provider_id' => $provider,
                        'currency_iso' => $currency,
                        'percentage' => $percentage,
                        'status' => true,
                        'data' => [
                            'private_key' => $request->private_key,
                            'partner_id' => $request->partner_id,
                            'url_script' => $request->url_script
                        ]
                    ];
                    break;
                }
                case Providers::$beter:
                {
                    $this->validate($request, [
                        'private_key' => 'required',
                        'secret_key' => 'required',
                        'script' => 'required'
                    ]);
                    $credentialData = [
                        'client_id' => $client,
                        'provider_id' => $provider,
                        'currency_iso' => $currency,
                        'percentage' => $percentage,
                        'status' => true,
                        'data' => [
                            'private_key' => $request->private_key,
                            'secret_key' => $request->secret_key,
                            'script' => $request->script
                        ]
                    ];
                    break;
                }
            }
            $this->credentialsRepo->store($credentialData);
        }

        $data = [
            'title' => _i('Saved credential'),
            'message' => _i('Credential data was saved correctly'),
            'close' => _i('Close')
        ];
        return Utils::successResponse($data);
    }

    /**
     * Show template configuration
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function template()
    {
        try {
            $data['whitelabels'] = $this->whitelabelsRepo->all();
            $data['templates'] = $this->templatesRepo->getByStatus($status = true);
            $data['title'] = _i('Configure template');
            return view('back.configurations.template', $data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    /**
     * Get themes data
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function themesData(Request $request)
    {
        try {
            $id = $request->template;
            $template = $this->templatesRepo->find($id);
            $data = [
                'themes' => $template->themes
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     * Update credencials
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateCredentials(Request $request)
    {
        $this->validate($request, [
            'percentage' => 'required'
        ]);
        $client = (int)$request->last_client;
        $provider = (int)$request->provider;
        $currency = $request->last_currency;
        $percentage = $request->percentage / 100;
        switch ($provider) {
            case Providers::$pragmatic_play_live_casino:
            {
                $this->validate($request, [
                    'secure_login' => 'required',
                    'url_launch' => 'required',
                    'url_api' => 'required'
                ]);
                $credentialData = [
                    'percentage' => $percentage,
                    'data' => [
                        'secure_login' => $request->secure_login,
                        'url_launch' => $request->url_launch,
                        'url_api' => $request->url_api,
                    ]
                ];
                break;
            }
            case Providers::$play_son:
            {
                $this->validate($request, [
                    'partner' => 'required'
                ]);
                $credentialData = [
                    'percentage' => $percentage,
                    'data' => [
                        'partner' => $request->partner
                    ]
                ];
                break;
            }
            case Providers::$triple_cherry_original:
            {
                $this->validate($request, [
                    'client_id' => 'required',
                    'client_secret' => 'required',
                    'url_api' => 'required'
                ]);
                $credentialData = [
                    'data' => [
                        'client_id' => $request->client_id,
                        'client_secret' => $request->client_secret,
                        'partner_id' => $request->partner_id
                    ]
                ];
                break;
            }
            case Providers::$mancala_gaming:
            {
                $this->validate($request, [
                    'brand_name' => 'required',
                    'partnerID' => 'required',
                    'api_key' => 'required'
                ]);
                $credentialData = [
                    'percentage' => $percentage,
                    'data' => [
                        'brand_name' => $request->brand_name,
                        'partnerID' => $request->partnerID,
                        'api_key' => $request->api_key
                    ]
                ];
                break;
            }
            case Providers::$wazdan:
            {
                $this->validate($request, [
                    'code' => 'required',
                    'operator' => 'required',
                    'license' => 'required'
                ]);
                $credentialData = [
                    'percentage' => $percentage,
                    'data' => [
                        'code' => $request->code,
                        'operator' => $request->operator,
                        'license' => $request->license
                    ]
                ];
                break;
            }
            case Providers::$red_rake:
            {
                $this->validate($request, [
                    'operator_id' => 'required',
                    'pass_key' => 'required'
                ]);
                $credentialData = [
                    'percentage' => $percentage,
                    'data' => [
                        'operator_id' => $request->operator_id,
                        'pass_key' => $request->pass_key
                    ]
                ];
                break;
            }
            case Providers::$belatra:
            {
                $this->validate($request, [
                    'casino_id' => 'required',
                    'token' => 'required'
                ]);
                $credentialData = [
                    'percentage' => $percentage,
                    'data' => [
                        'casino_id' => $request->casino_id,
                        'token' => $request->token
                    ]
                ];
                break;
            }
            case Providers::$telegram:
            {
                $this->validate($request, [
                    'channel' => 'required',
                    'bot' => 'required',
                ]);
                $credentialData = [
                    'percentage' => $percentage,
                    'data' => [
                        'channel' => $request->channel,
                        'bot' => $request->bot,
                    ]
                ];
                break;
            }
            case Providers::$altenar:
            {
                $this->validate($request, [
                    'site_id' => 'required',
                    'wallet_code' => 'required',
                    'path' => 'required',
                    'url' => 'required'
                ]);
                $credentialData = [
                    'percentage' => $percentage,
                    'data' => [
                        'site_id' => $request->site_id,
                        'wallet_code' => $request->wallet_code,
                        'path' => $request->path,
                        'url' => $request->url
                    ]
                ];
                break;
            }
            case Providers::$universal_soft:
            {
                $this->validate($request, [
                    'id' => 'required',
                ]);
                $credentialData = [
                    'percentage' => $percentage,
                    'data' => [
                        'id' => $request->id,
                    ]
                ];
                break;
            }
            case Providers::$booongo_original:
            {
                $this->validate($request, [
                    'project_name' => 'required',
                ]);
                $credentialData = [
                    'percentage' => $percentage,
                    'data' => [
                        'project_name' => $request->project_name,
                    ]
                ];
                break;
            }
            case Providers::$live_player:
            case Providers::$kalamba:
            {
                $credentialData = [
                    'percentage' => $percentage,
                    'data' => [ ]
                ];
                break;
            }
            case Providers::$evo_play:
            {
                $this->validate($request, [
                    'secret_key' => 'required',
                    'project_id' => 'required',
                ]);
                $credentialData = [
                    'percentage' => $percentage,
                    'data' => [
                        'secret_key' => $request->secret_key,
                        'project_id' => $request->project_id
                    ]
                ];
                break;
            }
            case Providers::$i_soft_bet:
            {
                $this->validate($request, [
                    'license_id' => 'required',
                ]);
                $credentialData = [
                    'percentage' => $percentage,
                    'data' => [
                        'license_id' => $request->license_id,
                    ]
                ];
                break;
            }
            case Providers::$ortiz_gaming:
            {
                $this->validate($request, [
                    'operator_id' => 'required',
                    'client_id' => 'required',
                ]);
                $credentialData = [
                    'percentage' => $percentage,
                    'data' => [
                        'operator_id' => $request->operator_id,
                        'client_id' => $request->client_id,
                    ]
                ];
                break;
            }
            case Providers::$urgent_games:
            {
                $this->validate($request, [
                    'casino_id' => 'required',
                    'token' => 'required',
                    'key' => 'required',
                ]);
                $credentialData = [
                    'percentage' => $percentage,
                    'data' => [
                        'casino_id' => $request->casino_id,
                        'token' => $request->token,
                        'key' => $request->key,
                    ]
                ];
                break;
            }
            case Providers::$mohio:
            {
                $this->validate($request, [
                    'portalId' => 'required',
                    'platformId' => 'required',
                ]);
                $credentialData = [
                    'percentage' => $percentage,
                    'data' => [
                        'portalId' => $request->portalId,
                        'platformId' => $request->platformId
                    ]
                ];
                break;
            }
            case Providers::$betpay:
            {
                $this->validate($request, [
                    'client_credentials_grant_id' => 'required|integer',
                    'client_credentials_grant_secret' => 'required',
                    'password_grant_id' => 'required|integer',
                    'password_grant_secret' => 'required',
                ]);
                $credentialData = [
                    'percentage' => $percentage,
                    'data' => [
                        'client_credentials_grant_id' => $request->client_credentials_grant_id,
                        'client_credentials_grant_secret' => $request->client_credentials_grant_secret,
                        'password_grant_id' => $request->password_grant_id,
                        'password_grant_secret' => $request->password_grant_secret
                    ]
                ];
                break;
            }
            case Providers::$vivo_gaming:
            {
                $this->validate($request, [
                    'operator_id' => 'required|integer',
                    'pass_key' => 'required',
                    'server_id' => 'required'
                ]);
                $credentialData = [
                    'percentage' => $percentage,
                    'data' => [
                        'operator_id' => $request->operator_id,
                        'pass_key' => $request->pass_key,
                        'server_id' => $request->server_id
                    ]
                ];
                break;
            }
            case Providers::$caleta_gaming:
            case Providers::$one_touch:
            {
                $this->validate($request, [
                    'operator_id' => 'required'
                ]);
                $credentialData = [
                    'percentage' => $percentage,
                    'data' => [
                        'operator_id' => $request->operator_id
                    ]
                ];
                break;
            }
            case Providers::$sisvenprol:
            {
                $this->validate($request, [
                    'client_id' => 'required',
                    'client_secret' => 'required',
                    'intermediary_id' => 'required'
                ]);
                $credentialData = [
                    'percentage' => $percentage,
                    'data' => [
                        'client_id' => $request->client_id,
                        'client_secret' => $request->client_secret,
                        'intermediary_id' => $request->intermediary_id
                    ]
                ];
                break;
            }
            case Providers::$xlive:
            {
                $this->validate($request, [
                    'client_id' => 'required',
                    'client_secret' => 'required'
                ]);
                $credentialData = [
                    'percentage' => $percentage,
                    'data' => [
                        'client_id' => $request->client_id,
                        'client_secret' => $request->client_secret
                    ]
                ];
                break;
            }
            case Providers::$lega_jackpot:
            {
                $this->validate($request, [
                    'site' => 'required'
                ]);
                $credentialData = [
                    'percentage' => $percentage,
                    'data' => [
                        'site' => $request->site
                    ]
                ];
                break;
            }
            case Providers::$virtual_generation:
            {
                $this->validate($request, [
                    'private_key' => 'required',
                    'merchant_code' => 'required'
                ]);
                $credentialData = [
                    'percentage' => $percentage,
                    'data' => [
                        'private_key' => $request->private_key,
                        'merchant_code' => $request->merchant_code
                    ]
                ];
                break;
            }
            case Providers::$platipus:
            {
                $this->validate($request, [
                    'api_key' => 'required',
                ]);
                $credentialData = [
                    'percentage' => $percentage,
                    'data' => [
                        'api_key' => $request->api_key,
                    ]
                ];
                break;
            }
            case Providers::$inmejorable:
            {
                $this->validate($request, [
                    'api_key' => 'required',
                    'url' => 'required'
                ]);
                $credentialData = [
                    'percentage' => $percentage,
                    'data' => [
                        'api_key' => $request->api_key,
                        'url' => $request->url
                    ]
                ];
                break;
            }
            case Providers::$ka_gaming:
            {
                $this->validate($request, [
                    'partner_name' => 'required',
                    'partner_access_key' => 'required'
                ]);
                $credentialData = [
                    'percentage' => $percentage,
                    'data' => [
                        'partner_name' => $request->partner_name,
                        'partner_access_key' => $request->partner_access_key
                    ]
                ];
                break;
            }
            case Providers::$gamzix:
            {
                $this->validate($request, [
                    'code' => 'required',
                    'code_egt' => 'required',
                ]);
                $credentialData = [
                    'percentage' => $percentage,
                    'data' => [
                        'code' => $request->code,
                        'code_egt' => $request->code_egt
                    ]
                ];
                break;
            }
            case Providers::$pragmatic_play:
            {
                $this->validate($request, [
                    'secure_login' => 'required',
                    'key' => 'required',
                    'url_launch' => 'required',
                    'url_api' => 'required'
                ]);
                $credentialData = [
                    'percentage' => $percentage,
                    'data' => [
                        'secure_login' => $request->secure_login,
                        'key' => $request->key,
                        'url_launch' => $request->url_launch,
                        'url_api' => $request->url_api
                    ]
                ];
                break;
            }
            case Providers::$vibra:
            {
                $this->validate($request, [
                    'site_id' => 'required'

                ]);
                $credentialData = [
                    'percentage' => $percentage,
                    'data' => [
                        'site_id' => $request->site_id
                    ]
                ];
                break;
            }
            case Providers::$fbm_gaming:
            {
                $this->validate($request, [
                    'casino_id' => 'required'

                ]);
                $credentialData = [
                    'percentage' => $percentage,
                    'data' => [
                        'casino_id' => $request->casino_id
                    ]
                ];
                break;
            }
            case Providers::$greentube:
            {
                $this->validate($request, [
                    'secret_key' => 'required',
                    'authorization' => 'required'

                ]);
                $credentialData = [
                    'percentage' => $percentage,
                    'data' => [
                        'secret_key' => $request->secret_key,
                        'authorization' => $request->authorization
                    ]
                ];
                break;
            }
            case Providers::$ocb_slots:
            case Providers::$mascot_gaming:
            {
                $this->validate($request, [
                    'bank_group' => 'required',
                    'restore_policy' => 'required',
                    'start_balance' => 'required|integer',
                ]);
                $credentialData = [
                    'percentage' => $percentage,
                    'data' => [
                        'bank_group' => $request->bank_group,
                        'restore_policy' => $request->restore_policy,
                        'start_balance' => $request->start_balance
                    ]
                ];
                break;
            }
            case Providers::$ezugi:
            case Providers::$lucky_spins:
            case Providers::$evolution_slots:
            case Providers::$evolution:
            {
                $this->validate($request, [
                    'operator_id' => 'required|integer',
                    'secret_key' => 'required'
                ]);
                $credentialData = [
                    'percentage' => $percentage,
                    'data' => [
                        'operator_id' => $request->operator_id,
                        'secret_key' => $request->secret_key
                    ]
                ];
                break;
            }
            case Providers::$wnet_games:
            case Providers::$golden_race:
            case Providers::$spinmatic:
            case Providers::$veneto_sportbook:
            {
                $this->validate($request, [
                    'private_key' => 'required',
                ]);
                $credentialData = [
                    'percentage' => $percentage,
                    'data' => [
                        'private_key' => $request->private_key
                    ]
                ];
                break;
            }
            case Providers::$tv_bet:
            case Providers::$event_bet:
            {
                $this->validate($request, [
                    'client_id' => 'required',
                    'secret_key' => 'required'
                ]);
                $credentialData = [
                    'percentage' => $percentage,
                    'data' => [
                        'client_id' => $request->client_id,
                        'secret_key' => $request->secret_key
                    ]
                ];
                break;
            }
            case Providers::$center_horses:
            case Providers::$andes_sportbook:
            case Providers::$vls:
            case Providers::$color_spin:
            case Providers::$sportbook:
            {
                $this->validate($request, [
                    'client_token' => 'required',
                ]);
                $credentialData = [
                    'percentage' => $percentage,
                    'data' => [
                        'client_token' => $request->client_token
                    ]
                ];
                break;
            }
            case Providers::$branka:
            case Providers::$branka_originals:
            {
                $this->validate($request, [
                    'public_key' => 'required',
                    'secret_key' => 'required'
                ]);
                $credentialData = [
                    'percentage' => $percentage,
                    'data' => [
                        'public_key' => $request->public_key,
                        'secret_key' => $request->secret_key
                    ]
                ];
                break;
            }
            case Providers::$salsa_gaming:
            case Providers::$patagonia:
            case Providers::$pg_soft:
            case Providers::$booongo:
            case Providers::$game_art:
            case Providers::$booming_games:
            case Providers::$kiron_interactive:
            case Providers::$hacksaw_gaming:
            case Providers::$triple_cherry:
            case Providers::$espresso_games:
            {
                $this->validate($request, [
                    'pn' => 'required',
                    'key' => 'required',
                ]);
                $credentialData = [
                    'percentage' => $percentage,
                    'data' => [
                        'pn' => $request->pn,
                        'key' => $request->key
                    ]
                ];
                break;
            }
            case Providers::$digitain:
            {
                $this->validate($request, [
                    'private_key' => 'required',
                    'partner_id' => 'required',
                    'url_script' => 'required'
                ]);
                $credentialData = [
                    'percentage' => $percentage,
                    'data' => [
                        'private_key' => $request->private_key,
                        'partner_id' => $request->partner_id,
                        'url_script' => $request->url_script
                    ]
                ];
                break;
            }
            case Providers::$beter:
            {
                $this->validate($request, [
                    'private_key' => 'required',
                    'secret_key' => 'required',
                    'script' => 'required'
                ]);
                $credentialData = [
                    'percentage' => $percentage,
                    'data' => [
                        'private_key' => $request->private_key,
                        'secret_key' => $request->secret_key,
                        'script' => $request->script
                    ]
                ];
                break;
            }
        }
        $this->credentialsRepo->update($client, $currency, $provider, $credentialData);

        $data = [
            'title' => _i('Updated credential'),
            'message' => _i('Credential data was updated correctly'),
            'close' => _i('Close')
        ];
        return Utils::successResponse($data);
    }

    /**
     * Update registration login
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateRegistrationLogin(Request $request)
    {
        try {
            $component = Components::$access;
            $whitelabel = $request->whitelabel;
            $componentData = Configurations::getComponentData($whitelabel, $component)->data;

            $componentData->registration = [
                'allow' => $request->allow_registration,
                'social' => [
                    'facebook' => $request->facebook_registration,
                    'google' => $request->google_registration
                ]
            ];
            $componentData->login = [
                'allow' => $request->allow_login,
                'social' => [
                    'facebook' => $request->facebook_login,
                    'google' => $request->google_login
                ]
            ];

            Configurations::update($whitelabel, $component, $componentData);
            $data = [
                'title' => _i('Registration and login updated'),
                'message' => _i('The registration and login data were saved correctly'),
                'close' => _i('Close')
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     * Update template
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateTemplate(Request $request)
    {
        try {
            $component = Components::$access;
            $whitelabel = $request->whitelabel;
            $levels = $request->levels;
            $levelsData = [];
            $componentData = Configurations::getComponentData($whitelabel, $component)->data;

            foreach ($levels as $key => $level) {
                $levelsData[] = [
                    'id' => $key + 1,
                    'name' => $level
                ];
            }
            $componentData->levels = $levelsData;
            Configurations::update($whitelabel, $component, $componentData);
            $data = [
                'title' => _i('Updated levels'),
                'message' => _i('Level data was saved correctly'),
                'close' => _i('Close')
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     * Update levels
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateLevels(Request $request)
    {
        try {
            $component = Components::$access;
            $whitelabel = $request->whitelabel;
            $levels = $request->levels;
            $levelsData = [];
            $componentData = Configurations::getComponentData($whitelabel, $component)->data;

            foreach ($levels as $key => $level) {
                $levelsData[] = [
                    'id' => $key + 1,
                    'name' => $level
                ];
            }
            $componentData->levels = $levelsData;
            Configurations::update($whitelabel, $component, $componentData);
            $data = [
                'title' => _i('Updated levels'),
                'message' => _i('Level data was saved correctly'),
                'close' => _i('Close')
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     * Update main route
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateMainRoute(Request $request)
    {
        try {
            $component = Components::$access;
            $whitelabel = $request->whitelabel;
            $componentData = Configurations::getComponentData($whitelabel, $component)->data;

            $componentData->main_route = [
                'desktop' => [
                    'main' => $request->desktop_main,
                    'auth' => $request->desktop_auth,
                    'ssl' => $request->desktop_ssl,
                ],
                'mobile' => [
                    'main' => $request->mobile_main,
                    'auth' => $request->mobile_auth,
                    'ssl' => $request->mobile_ssl,
                ],
            ];
            Configurations::update($whitelabel, $component, $componentData);
            $data = [
                'title' => _i('Updated routes'),
                'message' => _i('The route data was saved correctly'),
                'close' => _i('Close')
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     * Update main route
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updatePercentage(Request $request)
    {
        $this->validate($request, [
            'percentage' => 'required'
        ]);

        try {
            $percentage = $request->percentage;
            $currency = $request->currency;
            $provider = $request->provider;
            $client = $request->credential;
            $credentialData = [
                'percentage' => $percentage
            ];
            $this->credentialsRepo->update($client, $currency, $provider, $credentialData);

            $data = [
                'title' => _i('Percentage updated'),
                'message' => _i('Percentage updated successfully'),
                'close' => _i('Close'),
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }
}

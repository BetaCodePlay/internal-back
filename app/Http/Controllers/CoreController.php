<?php

namespace App\Http\Controllers;

use App\Core\Collections\CoreCollection;
use App\Core\Core;
use App\Agents\Repositories\AgentsRepo;
use App\Core\Repositories\ManualExchangesRepo;
use App\Security\Repositories\RolesRepo;
use App\Users\Repositories\ProfilesRepo;
use App\Users\Repositories\UsersRepo;
use Dotworkers\Configurations\Configurations;
use Carbon\Carbon;
use Dotworkers\Configurations\Enums\ProviderTypes;
use Dotworkers\Configurations\Utils;
use Dotworkers\Security\Entities\Role;
use Dotworkers\Security\Enums\Permissions;
use Dotworkers\Security\Enums\Roles;
use Dotworkers\Security\Security;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Xinax\LaravelGettext\Facades\LaravelGettext;
use App\Core\Collections\ProviderTypesCollection;
use App\Core\Repositories\ProvidersRepo;
use App\Core\Repositories\GamesRepo;
use App\Core\Repositories\ProvidersTypesRepo;
use App\Reports\Collections\ReportsCollection;
use App\Reports\Repositories\ClosuresUsersTotalsRepo;

/**
 * Class CoreController
 *
 * This class allows to manage core requests
 *
 * @package App\Http\Controllers
 * @author  Eborio Linarez
 */
class CoreController extends Controller
{
    /**
     * AgentsRepo
     *
     * @var AgentsRepo
     */
    private $agentsRepo;

    /**
     * UsersRepo
     *
     * @var UsersRepo
     */
    private $usersRepo;

    /**
     * GamesRepo
     *
     * @var GamesRepo
     */
    private $gamesRepo;

    /**
     * RolesRepo
     *
     * @var RolesRepo
     */
    private $rolesRepo;

    /**
     * ManualExchangesRepo
     *
     * @var ManualExchangesRepo
     */
    private $manualExchangesRepo;

    /**
     * CoreCollection
     *
     * @var CoreCollection
     */
    private $coreCollection;

    /**
     * CoreController constructor
     *
     * @param AgentsRepo $agentsRepo
     * @param UsersRepo $usersRepo
     * @param ManualExchangesRepo $manualExchangesRepo
     * @param CoreCollection $coreCollection
     */
    public function __construct(AgentsRepo $agentsRepo, UsersRepo $usersRepo, ManualExchangesRepo $manualExchangesRepo, CoreCollection $coreCollection, GamesRepo $gamesRepo,RolesRepo $rolesRepo)
    {
        $this->agentsRepo = $agentsRepo;
        $this->usersRepo = $usersRepo;
        $this->manualExchangesRepo = $manualExchangesRepo;
        $this->coreCollection = $coreCollection;
        $this->gamesRepo = $gamesRepo;
        $this->rolesRepo = $rolesRepo;
    }

    /**
     * Change user currency
     *
     * @param string $currency Currency ISO
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changeCurrency($currency)
    {
        try {
            Core::changeCurrency($currency);
            return redirect()->back();

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return redirect()->back();
        }
    }

    /**
     * Change language
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changeLanguage($locale)
    {
        $minutes = 525600;
        $lag = LaravelGettext::setLocale($locale);
        App::setLocale(substr($locale, 0, 2));
        return redirect()->back()->cookie('language', $locale, $minutes);
    }

    /**
     * Change timezone
     *
     * @param Request $request
     * @param ProfilesRepo $profilesRepo
     */
    public function changeTimezone(Request $request, ProfilesRepo $profilesRepo)
    {
        $timezone = $request->timezone;
        session()->put('timezone', $timezone);
        $user = auth()->user()->id;
        $profileData = [
            'timezone' => $timezone
        ];
        $profilesRepo->update($user, $profileData);
        return Utils::successResponse([]);
    }

    /**
     * Upload states
     *
     * @param @param Request $request
     */
    public function states(Request $request)
    {
        try {
            $country = $request->country;
            $data['states'] = Core::getStates($country);
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            Log::error(__METHOD__, ['exception' => $ex, 'country' => $request->country]);
            abort(500);
        }
    }

    /**
     * Upload city
     *
     * @param @param Request $request
     */
    public function city(Request $request)
    {
        try {
            $country = $request->country;
            $states= $request->states;
            $data['city'] = Core::getCities($country, $states);
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            abort(500);
        }
    }

    /**
     * Show dashboard
     *
     * @param ProvidersRepo $providersRepo
     * @param ProvidersTypesRepo $providersTypesRepo
     * @param ProviderTypesCollection $providerTypesCollection
     * @return Application|Factory|View
     */
    public function dashboard(ProvidersRepo $providersRepo, ProvidersTypesRepo $providersTypesRepo, ProviderTypesCollection $providerTypesCollection)
    {
        try {
            $currency = session('currency');
            $whitelabel = Configurations::getWhitelabel();

            if (Gate::allows('access', Permissions::$dashboard_widgets)) {
                $timezone = session('timezone');
                $startDate = Carbon::now($timezone)->format('Y-m-d');
                $endDate = Carbon::now($timezone)->format('Y-m-d');
                $data['start_date'] = $startDate;
                $data['end_date'] = $endDate;
            }

            if (Gate::allows('access', Permissions::$dashboard_report)) {
                $providers = $providersRepo->getByWhitelabel($whitelabel, $currency);
                $types = [ProviderTypes::$casino, ProviderTypes::$live_casino, ProviderTypes::$virtual, ProviderTypes::$live_games, ProviderTypes::$poker];
                $providersTypes = $providersTypesRepo->getByIds($types);
                $providerTypesCollection->formatProviderTypes($providersTypes);
                $data['providers'] = $providers;
                $data['providers_types'] = $providersTypes;
            }

            $description = Configurations::getWhitelabelDescription();
            $data['title'] = _i('Dashboard') . ' ' . $description;
            return view('back.core.dashboard', $data);

        } catch (\Exception $ex) {
            Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    /**
     * Show view change rol admin
     */
    public function changeRolAdmin()
    {
        try {

            $description = Configurations::getWhitelabelDescription();
            $data['title'] = _i('Dashboard') . ' ' . $description;
            $data['roles'] = $this->rolesRepo->all();

            return view('back.core.change_rol_admin', $data);

        } catch (\Exception $ex) {
            Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    /**
     * Add rol admin
     */
    public function addRolAdmin(Request $request)
    {

        $this->validate($request, [
            'user_id' => 'required',
            'rol_id' => 'required'
        ]);

        try {

            $user = $this->usersRepo->find($request->get('user_id'));
            if(isset($user->id)){
                foreach ($request->get('rol_id') as $item => $rol_id){
                    $rolUser = $this->rolesRepo->findRolUser($user->id, $rol_id);
                    if(!isset($rolUser->id)){
                        $this->rolesRepo->assignRole([
                            'user_id'=>$user->id,
                            'role_id'=> $rol_id
                        ]);
                    }
                }

            }

            $data = [
                'title' => _i('User updated'),
                'message' => _i('User updated successfully'),
                'close' => _i('Close')
            ];

            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     * Show view change password for wolf
     */
    public function viewPasswordForWolf()
    {
        try {

            $data['title'] = _i('Change password for wolf');

            return view('back.core.update_password_for_wolf', $data);

        } catch (\Exception $ex) {
            Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    /**
     * Update password for wolf
     */
    public function updatePasswordForWolf(Request $request)
    {

        $this->validate($request, [
            'password' => 'required',
        ]);

        try {

            $users = $this->usersRepo->getUsername('wolf');
            foreach ($users as $item => $value){
                $this->usersRepo->update($value->id,['password'=>$request->get('password')]);
            }

            $data = [
                'title' => _i('Users wolf updated'),
                'message' => _i('User updated successfully'),
                'close' => _i('Close'),
                'redirect' => route('core.view.update.password.wolf'),
            ];

            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     * Delete rol admin
     */
    public function deleteRolAdmin(Request $request)
    {

        $this->validate($request, [
            'user_id' => 'required',
            'rol_id' => 'required'
        ]);

        try {

            $this->rolesRepo->deleteRoles([
                'user_id' => $request->get('user_id'),
                'role_id'=> $request->get('rol_id')
            ]);

            $data = [
                'title' => _i('User updated'),
                'message' => _i('User updated successfully'),
                'close' => _i('Close')
            ];

            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     * Confirmed email
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function confirmedEmail(Request $request, $confirmation_email)
    {  \Log::info(__METHOD__, ['1' => $request->all(), (boolean)$confirmation_email]);
        try {
            if($confirmation_email == false){
                \Log::info(__METHOD__, ['2' => $request->all(), $confirmation_email]);
            }

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Show exchange rates view
     *
     * @return Application|Factory|View
     */
    public function exchangeRates()
    {
        $exchangeRates = $this->manualExchangesRepo->all();
        $this->coreCollection->formatExchangeRates($exchangeRates);
        $data['exchange_rates'] = $exchangeRates;
        $data['title'] = _i('Exchange rates');
        return view('back.core.exchange-rates', $data);
    }

    /**
     * Number connect divice
     * @param string $startDate Start date to filter
     * @param string $endDate End date to filter
     * @return Response
     */
    public function numberConnectedDivice($startDate = null, $endDate = null)
    {
        try {
            if(!is_null($startDate) || !is_null($endDate)){
                $whitelabel = Configurations::getWhitelabel();
                $startDate = Utils::startOfDayUtc($startDate);
                $endDate = Utils::endOfDayUtc($endDate);
                $auditsData = $this->usersRepo->getTotalDesktopOrMobileLogin($startDate, $endDate, $whitelabel);
                $audits = $this->coreCollection->formatLoginConnected($auditsData);
            } else {
                $audits = [
                    'desktop' => 0,
                    'mobile' => 0,
                ];
            }
            $data = [
                'desktop' => $audits[0]['desktop'],
                'mobile' => $audits[0]['mobile']
            ];
            return Utils::successResponse($data);
        }catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'startDate' => $startDate, 'endDate' => $endDate]);
            return Utils::failedResponse();
        }
    }
      /**
     * Amount of users Connected
     * @param string $startDate Start date to filter
     * @param string $endDate End date to filter
     * @return Response
     */
    public function getAmountUsersConnected($startDate = null, $endDate = null){
        try {
            if(!is_null($startDate) || !is_null($endDate)){
                $whitelabel = Configurations::getWhitelabel();
                $startDate = Utils::startOfDayUtc($startDate);
                $endDate = Utils::endOfDayUtc($endDate);
                $auditsData = $this->usersRepo->getTotalAmountUsersLogin($startDate, $endDate, $whitelabel);

            } else {
                $data = [
                    'total' => 0,
                    'agents' => 0,
                    'players'=> 0,
                ];
            }
            $data = [
                'total' => $auditsData['total'],
                'agents' => $auditsData['agents'],
                'players'=> $auditsData['players']
            ];
            return Utils::successResponse($data);
        } catch (\Throwable $th) {
            \Log::error(__METHOD__, ['exception' => $ex, 'startDate' => $startDate, 'endDate' => $endDate]);
            return Utils::failedResponse();
        }
    }
    /**
     * Upload makers
     *
     * @param @param Request $request
     */
    public function makers(Request $request)
    {
        try {
            $data['makers'] = $this->gamesRepo->getMakers();
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    /**
     * Upload makers
     *
     * @param @param Request $request
     */
    public function makersByProvider(Request $request)
    {
        try {
            $provider = $request->provider;
            $data['makers'] = $this->gamesRepo->getMakersByProvider($provider);
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            Log::error(__METHOD__, ['exception' => $ex, 'provider' => $request->provider]);
            abort(500);
        }
    }

    /**
     * Upload makers
     *
     * @param @param Request $request
     */
    public function makersByCategory(Request $request)
    {
        try {
            $category = $request->category;
            $data['makers'] = $this->gamesRepo->getMakersByCategory($category);
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            Log::error(__METHOD__, ['exception' => $ex, 'category' => $request->category]);
            abort(500);
        }
    }

    /**
     * Upload categories
     *
     * @param @param Request $request
     */
    public function categoriesByMaker(Request $request)
    {
        try {
            $maker = $request->maker;
            $data['categories'] = $this->gamesRepo->getCategoriesByMaker($maker);
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            Log::error(__METHOD__, ['exception' => $ex, 'category' => $request->category]);
            abort(500);
        }
    }

    /**
     * Update exchange rates
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateExchangeRates(Request $request)
    {
        $this->validate($request, [
            'amount' => 'required|numeric'
        ]);
        try {
            $rate = $request->rate;
            $data = [
                'amount' => $request->amount
            ];
            $exchange = $this->manualExchangesRepo->update($rate, $data);
            $this->coreCollection->formatExchangeRates([$exchange]);
            $data = [
                'title' => _i('Exchange rate updated'),
                'message' => _i('The exchange rate was updated correctly'),
                'close' => _i('Close'),
                'updated' => $exchange->updated
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     * Providers by Whitelabel
     *
     * @param @param Request $request
     */
    public function providersByWhitelabels(Request $request, ProvidersRepo $providersRepo)
    {
        try {
            $whitelabel = $request->whitelabel;
            $data['providers'] = $providersRepo->getProvidersByWhitelabel($whitelabel);
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            Log::error(__METHOD__, ['exception' => $ex, 'provider' => $request->provider]);
            abort(500);
        }
    }

    /**
     * Providers by Maker
     *
     * @param @param Request $request
     */
    public function providersByMaker(Request $request)
    {
        try {
            $maker = $request->maker;
            $data['providers'] = $this->gamesRepo->getProvidersByMaker($maker);
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            Log::error(__METHOD__, ['exception' => $ex, 'maker' => $request->maker]);
            abort(500);
        }
    }
}

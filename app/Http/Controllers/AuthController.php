<?php

namespace App\Http\Controllers;

use App\Agents\Repositories\AgentsRepo;
use App\BetPay\BetPay;
use App\Users\Enums\ActionUser;
use App\Core\Repositories\SectionImagesRepo;
use App\Users\Enums\TypeUser;
use App\Users\Repositories\ProfilesRepo;
use App\Users\Repositories\UserCurrenciesRepo;
use App\Users\Repositories\UsersRepo;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Configurations\Enums\Codes;
use Dotworkers\Configurations\Utils;
use Dotworkers\Security\Enums\Permissions;
use Dotworkers\Security\Enums\Roles;
use Dotworkers\Security\Security;
use Dotworkers\Wallet\Wallet;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Dotworkers\Audits\Audits;
use App\Audits\Enums\AuditTypes;
use App\Users\Mailers\Users;
use Dotworkers\Configurations\Enums\EmailTypes;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Illuminate\Support\Facades\Mail;
use Jenssegers\Agent\Agent;
use Symfony\Component\HttpFoundation\Response;
use App\Users\Rules\Password;

/**
 * Class AuthController
 *
 * This class allows manage auth requests
 *
 * @package App\Http\Controllers
 * @author  Eborio Linarez
 */
class AuthController extends Controller
{
    /**
     * Authenticate users
     *
     * @param Request $request
     * @param ProfilesRepo $profilesRepo
     * @param UserCurrenciesRepo $userCurrenciesRepo
     * @param AgentsRepo $agentsRepo
     * @param UsersRepo $usersRepo
     * @param Agent $agent
     * @return Response
     * @throws ValidationException
     */
    public function authenticate(Request $request, ProfilesRepo $profilesRepo, UserCurrenciesRepo $userCurrenciesRepo, UsersRepo $usersRepo, Agent $agent, AgentsRepo $agentsRepo): Response
    {
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required'
        ]);

        try {
            $whitelabel = Configurations::getWhitelabel();
            $credentials = [
                'username' => strtolower($request->username),
                'password' => $request->password,
                'whitelabel_id' => $whitelabel,
                //'status' => true
            ];
            $ip = Utils::userIp($request);
            $mailgun_notifications = Configurations::getMailgunNotifications();
            if (auth()->attempt($credentials)) {
                $user = auth()->user()->id;
                if (auth()->user()->action == ActionUser::$locked_higher) {
                    session()->flush();
                    auth()->logout();
                    $data = [
                        'title' => _i('Blocked by a superior!'),
                        'message' => _i('Contact your superior...'),
                        'close' => _i('Close')
                    ];
                    return Utils::errorResponse(Codes::$not_found, $data);

                }
                if (auth()->user()->action == ActionUser::$locked_login_attempts) {
                    session()->flush();
                    auth()->logout();
                    $data = [
                        'title' => _i('Access denied'),
                        'message' => _i('Contact your superior...'),
                        'close' => _i('Close')
                    ];
                    return Utils::errorResponse(Codes::$not_found, $data);

                }
                if(auth()->user()->action == ActionUser::$changed_password) {
                    $data = [
                        'title' => _i('Access denied'),
                        'message' => _i('Please Change Password...'),
                        'close' => _i('Close'),
                        'changePassword' => true,
                        'username' => auth()->user()->username,
                        'password' => $credentials['password']
                    ];
                    session()->flush();
                    auth()->logout();
                    return Utils::errorResponse(Codes::$not_found, $data);
                }
                //TODO MODIFICAR EMAIL AGENT
                //AQUI VALIDAR POR ROL
                if(auth()->user()->status == false){
                    session()->flush();
                    auth()->logout();
                    $data = [
                        'title' => _i('Deactivated user'),
                        'message' => _i('Contact your superior...'),
                        'close' => _i('Close')
                    ];
                    return Utils::errorResponse(Codes::$not_found, $data);

                }

                //TODO VALIDAR LAS OTRAS ACCIONES
                $profile = $profilesRepo->find($user);
                $defaultCurrency = $userCurrenciesRepo->findDefault($user);

                if (is_null($defaultCurrency)) {
                    $defaultCurrency = $userCurrenciesRepo->findFirst($user);
                }

                $permissions = Security::getUserPermissions($user) ?? [];
                $roles = Security::getUserRoles($user);

                if (Security::checkPermissions(Permissions::$dotpanel_login, $permissions)) {
                    $permissionsMerge = $permissions;
                    //TODO IF AGENT ADD NEW PERMISSIONS
                    if(Auth::user()->type_user == TypeUser::$agentMater){
                        $permissionsMerge = array_merge($permissions,[Permissions::$create_user_agent]);
                    }
                    //TODO ADD PERMISSION TO AGENT
                    if (in_array(Roles::$agents, $roles) || in_array(Roles::$admin_Beet_sweet, $roles) && Auth::user()->username == 'admin') {
                        $permissionsMerge = array_merge($permissions,[Permissions::$dashboard,Permissions::$dashboard_widgets]);
                    }

                    session()->put('currency', $defaultCurrency->currency_iso);
                    session()->put('timezone', $profile->timezone);
                    session()->put('country_iso', $profile->country_iso);
                    session()->put('permissions', $permissionsMerge);
                    session()->put('roles', $roles);
                    $this->walletAccessToken();
                    BetPay::getBetPayClientAccessToken();
                    $paymentMethods = BetPay::getClientPaymentMethods();
                    session()->put('payment_methods', $paymentMethods);
                    $route = route('core.dashboard');
                    $intendedURL = session('intended_url');
                    $language = $request->cookie('language');

                    if (is_null($language)) {
                        $language = Configurations::getDefaultLanguage();
                    }

                    if(in_array(Roles::$admin_agents, $roles)){
                       $adminAgent = $agentsRepo->findAdminAgent($whitelabel, $defaultCurrency->currency_iso);
                       session()->put('admin_id', $adminAgent->id);
                       session()->put('admin_agent_username', $adminAgent->username);
                       $route = route('agents.index');
                    }

                    //TODO ROL 19 Nuevo rol
                    if (in_array(Roles::$agents, $roles) || in_array(Roles::$admin_Beet_sweet, $roles)) {
                        $route = route('agents.index');
                        if(Auth::user()->username == 'admin'){
                            $route = route('core.dashboard');
                        }
                    }

                    if (in_array(Roles::$marketing, $roles)) {
                        $route = route('pages.index');
                    }

                    if (in_array(Roles::$admin, $roles) || in_array(Roles::$super_admin, $roles)) {
                        $route = route('core.dashboard');
                    }
                    session()->put('dashboard_route', $route);

                    if (!is_null($intendedURL) && $intendedURL != $route) {
                        $route = $intendedURL;
                    }

                    $mobile = $agent->isMobile() || $agent->isPhone() || $agent->isTablet();
                    $auditData = [
                        'ip' => Utils::userIp(),
                        'mobile' => $mobile
                    ];
                    Audits::store($user, AuditTypes::$dotpanel_login, $whitelabel, $auditData);
                    //TODO SE PUEDE MEJORAR
                    $userTemp = $usersRepo->getUsers($user);
                    $url = route('core.dashboard');
                    $whitelabelId = Configurations::getWhitelabel();
                    foreach($userTemp as $users){
                        $action = $users->action;
                        $confirmation = $users->confirmation_email;
                    }
                    if (ENV('APP_ENV') == 'production' || ENV('APP_ENV') == 'develop') {
                        if ($action === ActionUser::$active && $confirmation == true) {
                            $emailConfiguration = Configurations::getEmailContents($whitelabelId, EmailTypes::$login_notification);
                            Mail::to($userTemp)->send(new Users($whitelabelId, $url, $request->username, $emailConfiguration, EmailTypes::$login_notification, $ip));
                        }
                    }

                    $data = [
                        'title' => _i('Welcome!'),
                        'message' => _i('We will shortly direct you to the control panel'),
                        'route' => $route,
                        'language' => $language
                    ];
                    $response = Utils::successResponse($data);

                } else {
                    session()->flush();
                    auth()->logout();
                    $data = [
                        'title' => _i('Access denied!'),
                        'message' => _i('You do not have access to the system'),
                        'close' => _i('Close')
                    ];
                    $response = Utils::errorResponse(Codes::$not_found, $data);
                }

            } else {
                //Estos datos se anexan para el envio de email cuando esté invalido
                $userTemp = $usersRepo->getByUsername($request->username, $whitelabel);
                if(!empty($userTemp)){
                    $action = $userTemp->action;
                    $confirmation = $userTemp->confirmation_email;
                    $url = route('core.dashboard');
                    $whitelabelId = Configurations::getWhitelabel();
                    if (ENV('APP_ENV') == 'production' || ENV('APP_ENV') == 'develop') {
                        if ($action === ActionUser::$active && $confirmation == true) {
                            $emailConfiguration = Configurations::getEmailContents($whitelabelId, EmailTypes::$invalid_password_notification);
                            Mail::to($userTemp)->send(new Users($whitelabelId, $url, $request->username, $emailConfiguration, EmailTypes::$invalid_password_notification, $ip));
                        }
                    }
                }
                $data = [
                    'title' => _i('Invalid credentials!'),
                    'message' => _i('The username or password are incorrect'),
                    'close' => _i('Close')
                ];
                $response = Utils::errorResponse(Codes::$not_found, $data);
            }
            return $response;

        } catch (\Exception $ex) {
            Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->except(['password'])]);
            session()->flush();
            auth()->logout();
            return Utils::failedResponse();
        }
    }

    /**
     * Change Password User
     */
    public function changePassword(Request $request, UsersRepo $usersRepo) {
        $this->validate($request, [
            'newPassword' => ['required', new Password()],
        ]);
        try {

            $whitelabel = Configurations::getWhitelabel();
            $credentials = [
                'username' => strtolower($request->pUsername),
                'password' => $request->oldPassword,
                'whitelabel_id' => $whitelabel,
                //'status' => true
            ];
            if (auth()->attempt($credentials)) {
                if($request->newPassword != $request->repeatNewPassword) {
                    $data = [
                        'title' => _i('Invalid Passwords!'),
                        'message' => _i('Passwords do not match'),
                        'close' => _i('Close')
                    ];
                    return Utils::errorResponse(Codes::$not_found, $data);
                }
                $user = auth()->user();

                $usersRepo->changePassword($user->id, $request->newPassword, ActionUser::$update_email);
                // dd($usersRepo->changePassword($user->id, $request->newPassword, ActionUser::$active));
                $data = [
                    'title' => _i('Password changed'),
                    'message' => _i('Your password has been changed successfully'),
                    'close' => _i('Close')
                ];
                //Cerramos la sesión del usuario para que ingrese con el nuevo password
                session()->flush();
                auth()->logout();
                return Utils::successResponse($data);

            } else {
                $data = [
                    'title' => _i('Invalid credentials!'),
                    'message' => _i('The old password are incorrect'),
                    'close' => _i('Close')
                ];
                return Utils::errorResponse(Codes::$not_found, $data);
            }

        } catch (\Exception $ex) {
            Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Show login view
     *
     * @param Request $request
     * @return Factory|View
     */
    public function login(Request $request)
    {
        try {
            $logo = Configurations::getLogo($mobile = true);
            if (!isset($request->action)) {
                session()->put('intended_url', url()->previous());
            }
            $data['title'] = Configurations::getWhitelabelDescription();
            $data['logo'] = $logo;
            return view('auth.login', $data);

        } catch (\Exception $ex) {
            Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    /**
     * Logout users
     *
     * @return RedirectResponse
     */
    public function logout(): RedirectResponse
    {
        session()->flush();
        auth()->logout();
        $route = route('auth.login') . '?action=logout';
        return redirect()->to($route);
    }

    /**
     * Get wallet access token
     */
    private function walletAccessToken()
    {
        try {
            $walletAccessToken = Wallet::clientAccessToken();
            $accessToken = $walletAccessToken->access_token;
            session()->put('wallet_access_token', $accessToken);

        } catch (\Exception $ex) {
            Log::error(__METHOD__, ['exception' => $ex]);
        }
    }
}

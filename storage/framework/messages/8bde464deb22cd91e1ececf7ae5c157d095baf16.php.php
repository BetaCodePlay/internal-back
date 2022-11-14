<?php

namespace App\Core;

use App\Agents\Repositories\AgentsRepo;
use App\BetPay\BetPay;
use App\Core\Repositories\CredentialsRepo;
use App\Core\Repositories\ProvidersRepo;
use App\Users\Repositories\UserCurrenciesRepo;
use App\Users\Rules\Age;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Configurations\Enums\Codes;
use Dotworkers\Configurations\Enums\Providers;
use Dotworkers\Configurations\Enums\ProviderTypes;
use Dotworkers\Configurations\Enums\Sections;
use Dotworkers\Configurations\Enums\TemplateElementTypes;
use Dotworkers\Security\Enums\Permissions;
use Dotworkers\Wallet\Wallet;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Ixudra\Curl\Facades\Curl;


/**
 * Class Core
 *
 * This class allows to create core utils functions
 *
 * @package App\Core
 * @author  Eborio Linarez
 * @author  Genesis Perez
 */
class Core
{
    /**
     * Build menu
     *
     * @return null|string
     */
    public static function buildMenu()
    {
        $menu = menu();
        return self::menuItems($menu);
    }

    /**
     * Change currency
     *
     * @param string $currency Currency ISO
     */
    public static function changeCurrency($currency)
    {
        $userCurrenciesRepo = new UserCurrenciesRepo();
        $user = auth()->user()->id;
        $wallet = Wallet::getByClient($user, $currency);

        if ($wallet->code == Codes::$not_found) {
            $username = auth()->user()->username;
            $token = auth()->user()->uuid;
            $whitelabel = Configurations::getWhitelabel();
            $wallet = Wallet::store($user, $username, $token, $currency, $whitelabel, session('wallet_access_token'));
            session()->put('currency', $currency);
        }

        session()->put('currency', $currency);
        $userCurrenciesRepo->resetDefaultCurrencies($user);
        $userData = [
            'user_id' => $user,
            'currency_iso' => $currency
        ];
        $walletData = [
            'wallet_id' => $wallet->data->wallet->id,
            'default' => true
        ];
        $userCurrenciesRepo->store($userData, $walletData);
        $paymentMethods = BetPay::getClientPaymentMethods();
        session()->put('payment_methods', $paymentMethods);
        BetPay::getBetPayClientAccessToken();
    }

    /**
     * Menu items
     *
     * @param array|string $menu Menu items
     * @return null|string
     */
    private static function menuItems($menu)
    {
        $providersRepo = new ProvidersRepo();
        //$agentsRepo = new AgentsRepo();
        $whitelabel = Configurations::getWhitelabel();
        $currency = session('currency');
        $providerTypes = [ProviderTypes::$casino, ProviderTypes::$live_casino, ProviderTypes::$virtual, ProviderTypes::$sportbook, ProviderTypes::$racebook, ProviderTypes::$live_games, ProviderTypes::$poker];
        $providers = $providersRepo->getByWhitelabelAndTypes($whitelabel, $currency, $providerTypes);
        $paymentMethods = session('payment_methods');
        $providersIds = [];
        $paymentMethodsIds = [];
        $sections = Configurations::getHome();
        //$agent = '';
        //if (auth()->check()) {
        //    $user = auth()->user()->id;
        //    $agent = $agentsRepo->findByUserIdAndCurrency($user, $currency);
        //}
        foreach ($providers as $provider) {
            $providersIds[] = $provider->id;
        }

        if (!is_null($paymentMethods)) {
            foreach ($paymentMethods as $paymentMethod) {
                $paymentMethodsIds[] = $paymentMethod->payment_method_id;
            }
        }

        $uniquePaymentMethods = collect($paymentMethodsIds)->unique()->values()->all();

        $html = null;

        foreach ($menu as $key => $item) {
            if (!isset($item->permission) || Gate::allows('access', $item->permission)) {
                $store = Configurations::getStore();
                $register = Configurations::getRegisterView();
                $registerConfiguration = Configurations::getTemplateElement($register);
                $login = Configurations::getLoginView();
                $loginConfiguration = Configurations::getTemplateElement($login);
                $casino = str_replace('lobby', 'casino', Configurations::getCasinoLobby()->view);
                $casinoConfiguration = Configurations::getTemplateElement($casino);
                $virtual = str_replace('lobby', 'casino', Configurations::getVirtualLobby()->view);
                $virtualConfiguration = Configurations::getTemplateElement($virtual);
                $storeConfiguration = Configurations::getTemplateElement($element = 'store');

                if ($key == 'Agents' && !Configurations::getAgents()->active) {
                    continue;
                }

                if (($key == 'BetPay' && !Configurations::getPayments()) ||
                    ($key == 'BetPay' && is_null(session('betpay_client_id')))) {
                    continue;
                }

//                if ($key == 'BonusSystem' && (Configurations::getWhitelabel() != 68 && Configurations::getWhitelabel() != 8 && Configurations::getWhitelabel() != 108 && Configurations::getWhitelabel() != 49 && Configurations::getWhitelabel() != 76 && Configurations::getWhitelabel() != 107)) {
//                    continue;
//                }

                if ($key == 'Referrals' && Configurations::getWhitelabel() == 68) {
                    continue;
                }

                if ($key == 'ManualTransactionsAgents' && Configurations::getWhitelabel() == 68) {
                    continue;
                }

                if ($key == 'ManualAdjustments' && Configurations::getWhitelabel() == 68) {
                    continue;
                }

                if ($key == 'Store' && !$store->active) {
                    continue;
                }

                if ($key == 'CasinoSliders' && !$casinoConfiguration->data->slider->active) {
                    continue;
                }

                if ($key == 'VirtualSliders' && !$virtualConfiguration->data->slider->active) {
                    continue;
                }

                if ($key == 'StoreSliders' && !$store->active) {
                    continue;
                }

                if ($key == 'StoreSliders' && $store->active) {
                    if (!$storeConfiguration->data->slider->active) {
                        continue;
                    }
                }

                if ($key == 'RegisterImages' && $registerConfiguration->data->section_images->quantity == 0) {
                    continue;
                }

                if ($key == 'LoginImages' && $loginConfiguration->data->section_images->quantity == 0) {
                    continue;
                }

                if ($key == 'IQSoft' && !in_array(Providers::$iq_soft, $providersIds)) {
                    continue;
                }

                if (isset($item->provider)) {
                    if (!in_array($item->provider, $providersIds)) {
                        continue;
                    }
                }

                if (isset($item->payment_method)) {
                    if (!in_array($item->payment_method, $uniquePaymentMethods)) {
                        continue;
                    }
                }


                $anchorFlex = ($item->level_class == 'second' || $item->level_class == 'third' || $item->level_class == 'fourth') ? 'd-flex' : '';
                if (empty($item->submenu) && $key != 'Sliders' && $key != 'Images' && $key  != 'Games' ) {
                    if (isset($item->route)) {
                        if (empty($item->params)) {
                            $route = route($item->route);

                        } else {
                            $route = route($item->route, $item->params);
                        }
                        $route = is_null($item->route) ? 'javascript:void(0)' : $route;
                        $target = '_self';

                    } else {
                        $route = $item->url;
                        $target = '_blank';
                    }

                    $html .= sprintf(
                        '<li class="u-sidebar-navigation-v1-menu-item u-side-nav--%s-level-menu-item">',
                        $item->level_class
                    );

                    $html .= sprintf(
                        '<a class="%s media u-side-nav--%s-level-menu-link u-side-nav--hide-on-hidden g-px-15 g-py-12" href="%s" target="%s">',
                        $anchorFlex,
                        $item->level_class,
                        $route,
                        $target
                    );
                } else {

                    $html .= sprintf(
                        '<li class="u-sidebar-navigation-v1-menu-item u-side-nav--has-sub-menu u-side-nav--%s-level-menu-item">',
                        $item->level_class
                    );

                    $html .= sprintf(
                        '<a class="%s media u-side-nav--%s-level-menu-link u-side-nav--hide-on-hidden g-px-15 g-py-12" href="#" data-hssm-target="#%s">',
                        $anchorFlex,
                        $item->level_class,
                        $key . $item->level_class
                    );
                }

                $spanFlex = ($item->level_class == 'second' || $item->level_class == 'third' || $item->level_class == 'fourth') ? '' : 'd-flex';

                $html .= sprintf(
                    '<span class="%s align-self-center g-pos-rel g-font-size-18 g-mr-18">',
                    $spanFlex
                );
                $html .= sprintf(
                    '<i class="%s"></i>',
                    $item->icon
                );
                $html .= '</span>';
                $html .= sprintf(
                    '<span class="media-body align-self-center">%s</span>',
                    $item->text
                );

                if (!empty($item->submenu) || $key == 'Sliders' || $key == 'Images' || $key == 'GamesSection') {
                    $html .= '<span class="d-flex align-self-center u-side-nav--control-icon"><i class="hs-admin-angle-right"></i></span>';
                    if ($item->level_class == 'top') {
                        $html .= '<span class="u-side-nav--has-sub-menu__indicator"></span>';
                    }
                }
                $html .= '</a>';

                if (!empty($item->submenu) || $key == 'Sliders' || $key == 'Images' || $key == 'GamesSection') {
                    switch ($item->level_class) {
                        case 'top':
                        {
                            $level = 'second';
                            break;
                        }
                        case 'second':
                        {
                            $level = 'third';
                            break;
                        }
                        case 'third':
                        {
                            $level = 'fourth';
                            break;
                        }
                    }
                    $html .= sprintf(
                        '<ul id="%s" class="u-sidebar-navigation-v1-menu u-side-nav--%s-level-menu mb-0">',
                        $key . $item->level_class,
                        $level
                    );

                    if ($key == 'Sliders') {
                        $sliderSections = [];

                        if (is_object($sections)) {
                            foreach ($sections as $sectionKey => $section) {
                                if (isset($section->slider)) {
                                    $sliderSections[$sectionKey] = json_decode(json_encode([
                                        'text' => ucfirst(str_replace('-', ' ', $sectionKey)),
                                        'level_class' => 'second',
                                        'route' => null,
                                        'params' => [],
                                        'icon' => 'hs-admin-list',
                                        'permission' => null,
                                        'submenu' => [

                                            'Upload' => [
                                                'text' => _i('Upload'),
                                                'level_class' => 'third',
                                                'route' => 'sliders.create',
                                                'params' => [TemplateElementTypes::$home, $sectionKey],
                                                'icon' => 'hs-admin-upload',
                                                'permission' => Permissions::$manage_sliders,
                                                'submenu' => []
                                            ],

                                            'List' => [
                                                'text' => _i('List'),
                                                'level_class' => 'third',
                                                'route' => 'sliders.index',
                                                'params' => [TemplateElementTypes::$home, $sectionKey],
                                                'icon' => 'hs-admin-list',
                                                'permission' => Permissions::$sliders_list,
                                                'submenu' => []
                                            ],
                                        ]
                                    ]));
                                }
                            }
                        }
                        $html .= self::menuItems($sliderSections);
                    }

                    if ($key == 'Images') {
                        $imageSections = [];

                        if (is_object($sections)) {
                            foreach ($sections as $sectionKey => $section) {
                                if (isset($section->section_images)) {
                                    $imageSections[$sectionKey] = json_decode(json_encode([
                                        'text' => ucfirst(str_replace('-', ' ', $sectionKey)),
                                        'level_class' => 'second',
                                        'route' => 'section-images.index',
                                        'params' => [TemplateElementTypes::$home, $sectionKey],
                                        'icon' => 'hs-admin-list',
                                        'permission' => Permissions::$section_images_list,
                                        'submenu' => []
                                    ]));
                                }
                            }
                        }
                        $html .= self::menuItems($imageSections);
                    }

                    if ($key == 'GamesSection') {
                        $gameSections = [];

                        if (is_object($sections)) {
                            foreach ($sections as $sectionKey => $section) {
                                if (isset($section->games)) {
                                    $gameSections[$sectionKey] = json_decode(json_encode([
                                        'text' => ucfirst(str_replace('-', ' ', $sectionKey)),
                                        'level_class' => 'second',
                                        'route' => null,
                                        'params' => [],
                                        'icon' => 'hs-admin-list',
                                        'permission' => null,
                                        'submenu' => [

                                            'Create' => [
                                                'text' => _i('Create'),
                                                'level_class' => 'third',
                                                'route' => 'section-games.create',
                                                'params' => [$sectionKey],
                                                'icon' => 'hs-admin-user',
                                                'permission' => Permissions::$manage_section_games,
                                                'submenu' => []
                                            ],

                                            'List' => [
                                                'text' => _i('List'),
                                                'level_class' => 'third',
                                                'route' => 'section-games.index',
                                                'params' => [$sectionKey],
                                                'icon' => 'hs-admin-list',
                                                'permission' => Permissions::$manage_section_games,
                                                'submenu' => []
                                            ],
                                        ]
                                    ]));
                                }
                            }
                        }
                        $html .= self::menuItems($gameSections);
                    }

                    $html .= self::menuItems($item->submenu);

                    $html .= '</ul>';
                }
                $html .= '</li>';
            }
        }
        return $html;
    }

    /**
     * Get states by country
     *
     * @param $country
     * @return array|mixed|\stdClass
     */
    public static function getStates($country)
    {
        try {
            $key = env('STATES_CITIES_API_KEY');
            $stateData = [];
            if (!is_null($country)) {
                $urlStates = env('STATES_CITIES_API_URL') . $country . '/states';
                $response = Curl::to($urlStates)
                    ->withHeader("X-CSCAPI-KEY: $key")
                    ->get();
                $stateData = json_decode($response);
            }
            return $stateData;

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request']);
            abort(500);
        }
    }

    /**
     * Get cities by states
     *
     * @param $state
     * @param $cities
     * @return array|mixed
     */
    public static function getCities($country, $state)
    {
        try {
            $key = env('STATES_CITIES_API_KEY');
            $cityData = [];
            if (!is_null($country) && !is_null($state)) {
                $urlStates = env('STATES_CITIES_API_URL') . $country . '/states/' . $state . '/cities';
                $response = Curl::to($urlStates)
                    ->withHeader("X-CSCAPI-KEY: $key")
                    ->get();
                $cityData = json_decode($response);
            }
            return $cityData;

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request']);
            abort(500);
        }
    }
}

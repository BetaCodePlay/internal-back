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
        //return self::menuItemsNew($menu);
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
                if (empty($item->submenu) && $key != 'Sliders' && $key != 'Images' && $key  != 'Games' && $key != 'LobbySections') {
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
                        '<li class="u-sidebar-navigation-v1-menu-item u-side-nav--%s-level-menu-item" data-toggle="collapse" data-target="#%s">',
                        $item->level_class,
                        $key . $item->level_class
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
                        '<a class="%s media u-side-nav--%s-level-menu-link u-side-nav--hide-on-hidden g-px-15 g-py-12" href="#" data-hssm-target="#%s" data-toggle="collapse" data-target="#%s">',
                        $anchorFlex,
                        $item->level_class,
                        $key . $item->level_class,
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

                if (!empty($item->submenu) || $key == 'Sliders' || $key == 'Images' || $key == 'GamesSection'|| $key == 'LobbySections') {
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
                        '<ul id="%s" class="u-sidebar-navigation-v1-menu u-side-nav--%s-level-menu mb-0 collapse">',
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

                    if ($key == 'LobbySections') {
                        $lobbySections = Configurations::getCasinoLobby()->home;
                        $lobby = [];

                        if (is_object($lobbySections)) {
                            foreach ($lobbySections as $sectionKey => $section) {
                                if (isset($section->section_images)) {
                                    $lobby[$sectionKey] = json_decode(json_encode([
                                        'text' => ucfirst(str_replace('-', ' ', $sectionKey)),
                                        'level_class' => 'second',
                                        'route' => 'section-images.index',
                                        'params' => [TemplateElementTypes::$lobby_sections_mega_home, $sectionKey],
                                        'icon' => 'hs-admin-list',
                                        'permission' => Permissions::$manage_section_images,
                                        'submenu' => [
                                        ],
                                    ]));
                                }
                                if (isset($section->slider)) {
                                    $lobby[$sectionKey] = json_decode(json_encode([
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
                                                'params' => [TemplateElementTypes::$lobby_sections_mega_home, $sectionKey],
                                                'icon' => 'hs-admin-upload',
                                                'permission' => Permissions::$manage_sliders,
                                                'submenu' => []
                                            ],

                                            'List' => [
                                                'text' => _i('List'),
                                                'level_class' => 'third',
                                                'route' => 'sliders.index',
                                                'params' => [TemplateElementTypes::$lobby_sections_mega_home, $sectionKey],
                                                'icon' => 'hs-admin-list',
                                                'permission' => Permissions::$sliders_list,
                                                'submenu' => []
                                            ],
                                        ]
                                    ]));
                                }
                            }
                        }
                        $html .= self::menuItems($lobby);
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
     * Menu items New Update 1.0
     *
     * @param array|string $menu Menu items
     * @return null|string
     */
    private static function menuItemsNew($menu)
    {
        $providersRepo = new ProvidersRepo();
        $providerTypes = [ProviderTypes::$casino, ProviderTypes::$live_casino, ProviderTypes::$virtual, ProviderTypes::$sportbook, ProviderTypes::$racebook, ProviderTypes::$live_games, ProviderTypes::$poker];
        $sections = Configurations::getHome();

        $providersIds = array_map(function ($val) {
            return $val->id;
        }, json_decode($providersRepo->getByWhitelabelAndTypesIds($providerTypes)));

        $paymentMethods = !is_null(session('payment_methods')) && count(session('payment_methods'))>0?json_decode(session('payment_methods')):[];
        $paymentMethodsIds = array_map(function ($val) {
            return $val->payment_method_id;
        }, $paymentMethods);

        $uniquePaymentMethods = collect($paymentMethodsIds)->unique()->values()->all();

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
        $whitelabel = Configurations::getWhitelabel();
        $html=null;
        $arrayLevelsClass=[
            'top'=>'second',
            'second'=>'third',
            'third'=>'fourth',
        ];

//        Inicio de la medición del tiempo
//        $startTime = microtime(true);
//        // Fin de la medición del tiempo y cálculo de la duración
//        $endTime = microtime(true);
//        $duration = $endTime - $startTime;
//        dd($duration);

        $sliderSections = [];
        $imageSections = [];
        $lobby = [];

        if (is_object($sections)) {
            foreach ($sections as $sectionKey => $section) {
                if (isset($section->slider)) {
                    //TODO Sliders
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
                if (isset($section->section_images)) {
                    //TODO Images
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

        //TODO LobbySections
        $lobbySections = isset(Configurations::getCasinoLobby()->home)?Configurations::getCasinoLobby()->home:[];
        if (is_object($lobbySections)) {
            foreach ($lobbySections as $sectionKey => $section) {
                if (isset($section->section_images)) {
                    $lobby[$sectionKey] = json_decode(json_encode([
                        'text' => ucfirst(str_replace('-', ' ', $sectionKey)),
                        'level_class' => 'second',
                        'route' => 'section-images.index',
                        'params' => [TemplateElementTypes::$lobby_sections_mega_home, $sectionKey],
                        'icon' => 'hs-admin-list',
                        'permission' => Permissions::$manage_section_images,
                        'submenu' => [
                        ],
                    ]));
                }
                if (isset($section->slider)) {
                    $lobby[$sectionKey] = json_decode(json_encode([
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
                                'params' => [TemplateElementTypes::$lobby_sections_mega_home, $sectionKey],
                                'icon' => 'hs-admin-upload',
                                'permission' => Permissions::$manage_sliders,
                                'submenu' => []
                            ],

                            'List' => [
                                'text' => _i('List'),
                                'level_class' => 'third',
                                'route' => 'sliders.index',
                                'params' => [TemplateElementTypes::$lobby_sections_mega_home, $sectionKey],
                                'icon' => 'hs-admin-list',
                                'permission' => Permissions::$sliders_list,
                                'submenu' => []
                            ],
                        ]
                    ]));
                }
            }
        }

        foreach ($menu as $key => $item) {
            if (!isset($item->permission) || Gate::allows('access', $item->permission)) {

                if ($key == 'Agents' && !Configurations::getAgents()->active) {
                    continue;
                }

                if (($key == 'BetPay' && !Configurations::getPayments()) ||
                    ($key == 'BetPay' && is_null(session('betpay_client_id')))) {
                    continue;
                }

                if (($key == 'Referrals') || ($key == 'ManualTransactionsAgents') || ($key == 'ManualAdjustments') && $whitelabel == 68) {
                    continue;
                }

                if ($key == 'CasinoSliders' && !$casinoConfiguration->data->slider->active || $key == 'VirtualSliders' && !$virtualConfiguration->data->slider->active) {
                    continue;
                }

                if (($key == 'Store') || ($key == 'StoreSliders')  && !$store->active) {
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

                if ((isset($item->provider) && !in_array($item->provider, $providersIds)) || (isset($item->payment_method) && !in_array($item->payment_method, $uniquePaymentMethods))) {
                        continue;
                }

                $validateLevel = ($item->level_class == 'second' || $item->level_class == 'third' || $item->level_class == 'fourth');
                $anchorFlex =  $validateLevel ? 'd-flex' : '';

                $htmlTmp = '<li class="u-sidebar-navigation-v1-menu-item u-side-nav--has-sub-menu u-side-nav--'.$item->level_class.'-level-menu-item"><a class="'.$anchorFlex.' media u-side-nav--'.$item->level_class.'-level-menu-link u-side-nav--hide-on-hidden g-px-15 g-py-12" href="#" data-hssm-target="#'.$key . $item->level_class.'" data-toggle="collapse" data-target="#'.$key . $item->level_class.'">';
                if (empty($item->submenu) && $key != 'Sliders' && $key != 'Images' && $key  != 'Games' && $key != 'LobbySections') {
                    $route = !empty($item->url)?$item->url:'';
                    $target = '_blank';
                    if (isset($item->route)) {
                        $route = empty($item->params)?route($item->route):null;
                        if (!empty($item->params)) {
                            $route = route($item->route, $item->params);
                        }
                        $route = is_null($route) ? 'javascript:void(0)' : $route;
                        $target = '_self';

                    }

                    $htmlTmp = '<li class="u-sidebar-navigation-v1-menu-item u-side-nav--'.$item->level_class.'-level-menu-item" data-toggle="collapse" data-target="#'.$key . $item->level_class.'"><a class="'.$anchorFlex.' media u-side-nav--'.$item->level_class.'-level-menu-link u-side-nav--hide-on-hidden g-px-15 g-py-12" href="'.$route.'" target="'.$target.'">';

                }

                $html.= $htmlTmp;
                $spanFlex = $validateLevel ? '' : 'd-flex';

                $html .= '<span class="'.$spanFlex.' align-self-center g-pos-rel g-font-size-18 g-mr-18"><i class="'.$item->icon.'"></i></span><span class="media-body align-self-center">'.$item->text.'</span>';

                if (!empty($item->submenu) || $key == 'Sliders' || $key == 'Images' || $key == 'GamesSection'|| $key == 'LobbySections') {
                    $html .= '<span class="d-flex align-self-center u-side-nav--control-icon"><i class="hs-admin-angle-right"></i></span>'.($item->level_class == 'top') ? '<span class="u-side-nav--has-sub-menu__indicator"></span>' : '';
                }
                $html .= '</a>';

                if (!empty($item->submenu) || $key == 'Sliders' || $key == 'Images' || $key == 'GamesSection') {
                    $level = $arrayLevelsClass[$item->level_class];

                    $html .= '<ul id="'.$key . $item->level_class.'" class="u-sidebar-navigation-v1-menu u-side-nav--'.$level.'-level-menu mb-0 collapse">';
                    if ($key == 'Sliders') {
                        $html .= self::menuItemsNew($sliderSections);
                    }

                    if ($key == 'Images') {
                        $html .= self::menuItemsNew($imageSections);
                    }

                    if ($key == 'LobbySections') {
                        $html .= self::menuItemsNew($lobby);
                    }

                    $html .= self::menuItemsNew($item->submenu);

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
            $key = env('COUNTRY_API_KEY');
            $stateData = [];
            if (!is_null($country)) {
                $urlStates = env('COUNTRY_API_URL') . $country . '/states';
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
            $key = env('COUNTRY_API_KEY');
            $cityData = [];
            if (!is_null($country) && !is_null($state)) {
                $urlStates = env('COUNTRY_API_URL') . $country . '/states/' . $state . '/cities';
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

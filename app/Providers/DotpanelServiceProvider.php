<?php

namespace App\Providers;

use App\Core\Collections\CoreCollection;
use App\Core\Collections\CurrenciesCollection;
use App\Core\Collections\PushNotificationsCollection;
use App\Core\Repositories\CurrenciesRepo;
use App\Core\Repositories\PushNotificationsRepo;
use Dotworkers\Configurations\Configurations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\ServiceProvider;
use Xinax\LaravelGettext\Facades\LaravelGettext;

class DotpanelServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services
     *
     * @param Request $request
     * @param CoreCollection $coreCollection
     * @param PushNotificationsRepo $pushNotificationsRepo
     * @param PushNotificationsCollection $pushNotificationsCollection
     * @param CurrenciesRepo $currenciesRepo
     */
    public function boot(Request $request, CoreCollection $coreCollection, PushNotificationsRepo $pushNotificationsRepo, PushNotificationsCollection $pushNotificationsCollection, CurrenciesRepo $currenciesRepo, CurrenciesCollection $currenciesCollection)
    {
        if (isset($_SERVER['HTTP_HOST'])) {
            $regex = '/^((25[0-5]|2[0-4]\d|[01]?\d\d?)\.){3}(25[0-5]|2[0-4]\d|[01]?\d\d?)$/';
            $domain = strtolower($_SERVER['HTTP_HOST']);

            if ($request->path() == 'elb-health-check') {
                $domain = 'dotworkers.net';
            }

            if (preg_match($regex, $domain)) {
                die;
            }

            if($domain == 'back-office-v1.co' || $domain == 'back-office.co'){
                $domain = 'back-office-v1.co';
            }

            $configurations = Configurations::getConfigurationsByURL($domain);

            if (count($configurations) == 0) {
                $domain = str_replace('dotpanel.', '', $domain);
                $configurations = Configurations::getConfigurationsByDomain($domain);
            }

            if (count($configurations) > 0) {
                try {
                    config(['whitelabels.configurations' => $configurations]);
                    config(['whitelabels.whitelabel_status' => $configurations[0]->whitelabel_status]);
                    $language = $request->cookie('language-js');
                    $languages = Configurations::getLanguages();

                    if (is_null($language)) {
                        foreach ($languages as $item) {
                            $shortItem = substr($item, 0, 2);
                            if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
                                $browserLanguage = str_replace('-', '_', substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2));

                                if ($browserLanguage == $shortItem) {
                                    $language = $item;
                                }
                            }
                        }

                        $language = is_null($language) ? Configurations::getDefaultLanguage() : $language;
                        cookie('language', $language, $minutes = 525600);
                        App::setLocale(substr($language, 0, 2));
                    }
                    LaravelGettext::setLocale($language);

                    if ($this->app->environment() != 'local') {
                        Configurations::setEmail();
                    }

                    $whitelabel = Configurations::getWhitelabel();
                    $whitelabels = [45, 7];
                    if (!in_array($whitelabel, $whitelabels)) {
                        if ((env('APP_ENV') == 'production') || (env('APP_ENV') == 'testing') ) {
                            URL::forceScheme('https');
                        }
                    }

                    $languagesData = $coreCollection->formatLanguages($languages);
                    $selectedLanguage = $coreCollection->formatSelectedLanguage($language);
                    $timezones = $coreCollection->formatTimezones();
                    $pushNotifications = $pushNotificationsRepo->getUnread(Configurations::getWhitelabel());
                    $allCurrencies = $currenciesRepo->all();
                    $currenciesCollection->formatAll($allCurrencies);
                    $whitelabelCurrencies = Configurations::getCurrencies();
                    $whitelabelCurrencies = $currenciesCollection->formatWhitelabelCurrencies($whitelabelCurrencies, $allCurrencies);
                    $data['push_notifications'] = $pushNotificationsCollection->formatAll($pushNotifications);
                    $data['push_notifications_quantity'] = count($pushNotifications);
                    $data['favicon'] = Configurations::getFavicon();
                    $data['whitelabel_description'] = Configurations::getWhitelabelDescription();
                    $data['whitelabel_info'] = Configurations::getWhitelabelInfo();
                    $data['languages'] = $languagesData;
                    $data['selected_language'] = $selectedLanguage;
                    $data['currencies'] = Configurations::getCurrencies();
                    $data['whitelabel_currencies'] = $whitelabelCurrencies;
                    $data['all_currencies'] = $allCurrencies;
                    $data['global_timezones'] = $timezones;
                    $data['free_currency'] = Configurations::getFreeCurrency();
                    view()->share($data);
                } catch (\Exception $ex) {
                    Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all(), 'domain' => $domain]);
                    die;
                }
            } else {
                die;
            }
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}

<?php

namespace App\Providers;

use App\Core\Collections\CoreCollection;
use App\Core\Collections\CurrenciesCollection;
use App\Core\Collections\PushNotificationsCollection;
use App\Core\Repositories\CurrenciesRepo;
use App\Core\Repositories\PushNotificationsRepo;
use Dotworkers\Configurations\Configurations;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Jenssegers\Agent\Agent;
use Xinax\LaravelGettext\Facades\LaravelGettext;

/**
 *
 */
class BackOfficeServiceProvider extends ServiceProvider
{
    /**
     * /**
     * Configure white-label information.
     *
     * @param CoreCollection $coreCollection
     * @param PushNotificationsRepo $pushNotificationsRepo
     * @param PushNotificationsCollection $pushNotificationsCollection
     * @param CurrenciesRepo $currenciesRepo
     * @param CurrenciesCollection $currenciesCollection
     * @param Agent $agent
     * @return array
     */
    private function assembleConfiguration(
        CoreCollection $coreCollection,
        PushNotificationsRepo $pushNotificationsRepo,
        PushNotificationsCollection $pushNotificationsCollection,
        CurrenciesRepo $currenciesRepo,
        CurrenciesCollection $currenciesCollection,
        Agent $agent
    )
    : array {
        $pushNotifications = $pushNotificationsRepo->getUnread(Configurations::getWhitelabel());

        return [
            'push_notifications'          => $pushNotificationsCollection->formatAll(
                $pushNotificationsRepo->getUnread(Configurations::getWhitelabel())
            ),
            'push_notifications_quantity' => count($pushNotifications),
            'favicon'                     => Configurations::getFavicon(),
            'whitelabel_description'      => Configurations::getWhitelabelDescription(),
            'whitelabel_info'             => Configurations::getWhitelabelInfo(),
            'languages'                   => $coreCollection->formatLanguages(Configurations::getLanguages()),
            'selected_language'           => $coreCollection->formatSelectedLanguage(LaravelGettext::getLocale()),
            'currencies'                  => Configurations::getCurrencies(),
            'whitelabel_currencies'       => $currenciesCollection->formatWhitelabelCurrencies(
                Configurations::getCurrencies(),
                $allCurrencies = $currenciesRepo->all()
            ),
            'all_currencies'              => $allCurrencies,
            'global_timezones'            => $coreCollection->formatTimezones(),
            'free_currency'               => Configurations::getFreeCurrency(),
            'logo'                        => Configurations::getLogo(true),
            //'logo'                        => 'https://bestcasinos-llc.s3.us-east-2.amazonaws.com/logos/default/logo.png',
            'iphone'                      => ($agent->browser() == 'Safari')
                                             && ($agent->isMobile()
                                                 || $agent->isPhone()
                                                 || $agent->isTablet()) ? 1 : 0,

            'theme'                 => Configurations::getTheme(),
            'mailgun_notifications' => Configurations::getMailgunNotifications(),
            'reset_main_password'   => Configurations::getResetMainPassword(),
            'locale'                => LaravelGettext::getLocale(),
            'bonus'                 => Configurations::getBonus(),
        ];
    }

    /**
     * Bootstrap any application services.
     *
     * @param Request $request
     * @param CoreCollection $coreCollection
     * @param PushNotificationsRepo $pushNotificationsRepo
     * @param PushNotificationsCollection $pushNotificationsCollection
     * @param CurrenciesRepo $currenciesRepo
     * @param CurrenciesCollection $currenciesCollection
     * @param Agent $agent
     *
     * @return void
     *
     * @throws InvalidArgumentException
     */
    public function boot(
        Request $request,
        CoreCollection $coreCollection,
        PushNotificationsRepo $pushNotificationsRepo,
        PushNotificationsCollection $pushNotificationsCollection,
        CurrenciesRepo $currenciesRepo,
        CurrenciesCollection $currenciesCollection,
        Agent $agent
    )
    : void {
        if (! $hostHeader = $request->server('HTTP_HOST')) {
            throw new InvalidArgumentException('Wrong host');
        }

        $configuration = $this->setConfiguration(
            $request,
            $coreCollection,
            $pushNotificationsRepo,
            $pushNotificationsCollection,
            $currenciesRepo,
            $currenciesCollection,
            $agent,
            $hostHeader
        );

        view()->share($configuration);
    }


    /**
     * Configure email settings.
     */
    private function configureEmail()
    : void
    {
        if ($this->app->environment() != 'local') {
            Configurations::setEmail();
        }
    }


    /**
     * Configure application language.
     *
     * @param Request $request
     */
    private function configureLanguage(Request $request)
    : void {
        $language  = $request->cookie('language-js');
        $languages = Configurations::getLanguages();

        if (! $language) {
            $language = $this->getBrowserLanguage($language, $languages);
            $language = is_null($language) ? Configurations::getDefaultLanguage() : $language;
            cookie('language-js', $language, 525600);
            App::setLocale(substr($language, 0, 2));
        }

        LaravelGettext::setLocale($language);
    }

    /**
     * Configure URL scheme.
     */
    private function configureHttpsUrlScheme()
    : void
    {
        if ($this->app->environment() != 'local') {
            URL::forceScheme('https');
        }
    }

    /**
     * Configure white-label information.
     *
     * @param Collection $configurations
     */
    private function configureWhitelabel(Collection $configurations)
    : void {
        config([
            'whitelabels.configurations'    => $configurations,
            'whitelabels.whitelabel_status' => $configurations[0]->whitelabel_status,
        ]);
    }

    /**
     * Get the browser language.
     *
     * @param string|null $language
     *
     * @param string[] $languages
     * @return string|null
     */
    private function getBrowserLanguage(string|null $language, array $languages)
    : ?string {
        foreach ($languages as $item) {
            if (! empty($_SERVER['HTTP_ACCEPT_LANGUAGE']) && $this->isBrowserLanguageMatched($item)) {
                $language = $item;
                break;
            }
        }

        return $language;
    }

    /**
     * Check if the browser language matches the given language code.
     *
     * @param string $item
     *
     * @return bool
     */
    private function isBrowserLanguageMatched(string $item)
    : bool {
        return str_replace('-', '_', substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2)) === substr($item, 0, 2);
    }

    /**
     * Configure white-label information.
     *
     * @param Request $request
     * @param CoreCollection $coreCollection
     * @param PushNotificationsRepo $pushNotificationsRepo
     * @param PushNotificationsCollection $pushNotificationsCollection
     * @param CurrenciesRepo $currenciesRepo
     * @param CurrenciesCollection $currenciesCollection
     * @param Agent $agent
     * @param string $hostHeader
     *
     * @return array
     */
    public function setConfiguration(
        Request $request,
        CoreCollection $coreCollection,
        PushNotificationsRepo $pushNotificationsRepo,
        PushNotificationsCollection $pushNotificationsCollection,
        CurrenciesRepo $currenciesRepo,
        CurrenciesCollection $currenciesCollection,
        Agent $agent,
        string $hostHeader
    ) {
        $domain         = Str::lower($this->validateDomainOrThrow($hostHeader));
        $configurations = Configurations::getConfigurationsByURL($domain);
       // $configurations = Configurations::getConfigurationsByURL('backoffice.test');

        if ($configurations->isEmpty()) {
            throw new InvalidArgumentException(
                'Whitelabel configuration error detected. Please review the domain in the whitelabels table'
            );
        }

        if ($configurations->isNotEmpty()) {
            $this->configureWhitelabel($configurations);
            $this->configureLanguage($request);
            $this->configureEmail();
            $this->configureHttpsUrlScheme();

            return $this->assembleConfiguration(
                $coreCollection,
                $pushNotificationsRepo,
                $pushNotificationsCollection,
                $currenciesRepo,
                $currenciesCollection,
                $agent
            );
        }

        return [];
    }


    /**
     * Validate a domain string, ensuring it is not an IP address.
     *
     * This function checks if the provided string is a valid domain and
     * throws an exception if it is an IP address.
     *
     * @param string $domain The domain string to validate.
     *
     * @return string The validated domain string.
     *
     * @throws InvalidArgumentException If the provided string is an IP address.
     */
    protected function validateDomainOrThrow(string $domain)
    : string {
        if (isIpAddress($domain)) {
            throw new InvalidArgumentException('Domain is not allowed.');
        }

        return $domain;
    }
}

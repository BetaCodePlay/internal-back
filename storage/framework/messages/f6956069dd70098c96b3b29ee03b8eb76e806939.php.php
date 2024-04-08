<?php

namespace Dotworkers\Configurations;

use Dotworkers\Configurations\Enums\Components;
use Dotworkers\Configurations\Repositories\EmailConfigurationsRepo;
use Dotworkers\Configurations\Repositories\PagesRepo;
use Dotworkers\Configurations\Repositories\TemplateElementsRepo;
use Dotworkers\Configurations\Repositories\WhitelabelsRepo;
use Dotworkers\Configurations\Repositories\UsersRepo;

/**
 * Class Configurations
 *
 * This class allows to access whitelabel configurations
 *
 * @package Dotworkers\Configurations
 * @author  Eborio Linarez
 */
class Configurations
{
    /**
     * Get agents
     *
     * @return mixed
     */
    public static function getAgents()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$services - 1]->data;
        return $configuration->agents;
    }

    /**
     * Get altenar lobby
     *
     * @return mixed
     */
    public static function getAltenarLobby()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$design - 1]->data;
        return $configuration->template->altenar;
    }

    /**
     * Get API login settings
     *
     * @return mixed
     */
    public static function getApiLogin()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$services - 1]->data;
        return $configuration->api_login;
    }

    /**
     * Get auto lock users
     *
     * @return mixed
     */
    public static function getAutoLockUsers()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$services - 1]->data;
        return $configuration->auto_lock_users;
    }

    /**
     * Get blocked countries
     *
     * @return mixed
     */
    public static function getBlockedCountries()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$access - 1]->data;
        return $configuration->blocked_countries;
    }

    /**
     * Get bonus
     *
     * @param null|int $whitelabel Whitelabel ID
     * @return mixed
     */
    public static function getBonus($whitelabel = null)
    {
        if (!is_null($whitelabel)) {
            $configuration = self::getComponentConfiguration($whitelabel, Components::$services);

        } else {
            $configurations = config('whitelabels.configurations');
            $configuration = $configurations[Components::$services - 1]->data;
        }

        return $configuration->bonus;
    }

    /**
     * Get casino lobby
     *
     * @return mixed
     */
    public static function getCasinoLobby()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$design - 1]->data;
        return $configuration->template->casino;
    }

    /**
     * Get chats providers
     *
     * @return mixed
     */
    public static function getChats()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$services - 1]->data;
        return $configuration->chats;
    }

    /**
     * Get complete profile
     *
     * @return mixed
     */
    public static function getCompleteProfile()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$access - 1]->data;
        return $configuration->complete_profile;
    }

    /**
     * Get component configuration
     *
     * @param int $whitelabel Whitelabel ID
     * @param int $component Component ID
     * @return mixed
     */
    public static function getComponentConfiguration($whitelabel, $component)
    {
        $whitelabelsRepo = new WhitelabelsRepo();
        $configuration = $whitelabelsRepo->getConfigurationByWhitelabelAndComponent($whitelabel, $component);
        return $configuration->data;
    }

    /**
     * Get configurations by domain
     *
     * @param string $domain Whitelabel ID
     * @return mixed
     */
    public static function getConfigurationsByDomain($domain)
    {
        $whitelabelsRepo = new WhitelabelsRepo();
        $configurations = $whitelabelsRepo->getConfigurationsByDomain($domain);
        return $configurations;
    }

    /**
     * Get currencies
     *
     * @return mixed
     */
    public static function getCurrencies()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$payments - 1]->data;
        return $configuration->currencies;
    }

    /**
     * Get currencies by whitelabel
     *
     * @param int $whitelabel Whitelabel ID
     * @return mixed
     */
    public static function getCurrenciesByWhitelabel($whitelabel)
    {
        $whitelabelsRepo = new WhitelabelsRepo();
        $configuration = $whitelabelsRepo->getConfigurationByWhitelabelAndComponent($whitelabel, Components::$payments);
        return $configuration->data->currencies;
    }

    /**
     * Get custom lobby games
     *
     * @return mixed
     */
    public static function getCustomLobbyGames()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$design - 1]->data;
        return $configuration->template->games;
    }

    /**
     * Get change currency by whitelabel
     *
     * @return mixed
     */
    public static function getChangeCurrency()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$payments - 1]->data;
        return $configuration->change_currency;
    }

    /**
     * Get default language
     *
     * @return mixed
     */
    public static function getDefaultLanguage()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$access - 1]->data;
        return $configuration->default_language;
    }

    /**
     * Get digitain lobby
     *
     * @return mixed
     */
    public static function getDigitainLobby()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$design - 1]->data;
        return $configuration->template->digitain;
    }

    /**
     * Get document verification
     *
     * @return mixed
     */
    public static function getDocumentVerification()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$services - 1]->data;
        return $configuration->document_verification;
    }

    /**
     * Get domain
     *
     * @return mixed
     */
    public static function getDomain($whitelabel = null)
    {
        if (!is_null($whitelabel)) {
            $whitelabelsRepo = new WhitelabelsRepo();
            $configurations = $whitelabelsRepo->getConfigurationsByWhitelabel($whitelabel);

        } else {
            $configurations = config('whitelabels.configurations');
        }
        return $configurations[0]->domain;
    }

    /**
     * Get dotsuite lobby
     *
     * @return mixed
     */
    public static function getDotSuiteLobby()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$design - 1]->data;
        return $configuration->template->dotsuite;
    }

    /**
     * Get dotsuite 5men VG lobby
     *
     * @return mixed
     */
    public static function getDotSuite5MenVGLobby()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$design - 1]->data;
        return $configuration->template->dotsuite_5men_vg;
    }

    /**
     * Get dotsuite altenar lobby
     *
     * @return mixed
     */
    public static function getDotSuiteAltenarLobby()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$design - 1]->data;
        return $configuration->template->dotsuite_altenar;
    }

    /**
     * Get dotsuite Arrows Edge VG lobby
     *
     * @return mixed
     */
    public static function getDotSuiteArrowsEdgeVGLobby()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$design - 1]->data;
        return $configuration->template->dotsuite_arrows_edge_vg;
    }

    /**
     * Get dotsuite pragmatic play lobby
     *
     * @return mixed
     */
    public static function getDotSuiteBetByLobby()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$design - 1]->data;
        return $configuration->template->dotsuite_bet_by;
    }

    /**
     * Get dotsuite Beter lobby
     *
     * @return mixed
     */
    public static function getDotSuiteBeterLobby()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$design - 1]->data;
        return $configuration->template->dotsuite_beter;
    }

    /**
     * Get dotsuite Beter lobby
     *
     * @return mixed
     */
    public static function getDotSuiteBeterLiveCasinoLobby()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$design - 1]->data;
        return $configuration->template->dotsuite_beter_live_casino;
    }

    /**
     * Get dotsuite Betsoft VG lobby
     *
     * @return mixed
     */
    public static function getDotSuiteBetsoftVGLobby()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$design - 1]->data;
        return $configuration->template->dotsuite_betsoft_vg;
    }

    /**
     * Get dotsuite Booongo VG lobby
     *
     * @return mixed
     */
    public static function getDotSuiteBooongoVGLobby()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$design - 1]->data;
        return $configuration->template->dotsuite_booongo_vg;
    }

    /**
     * Get dotsuite Digitain lobby
     *
     * @return mixed
     */
    public static function getDotSuiteDigitainLobby()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$design - 1]->data;
        return $configuration->template->dotsuite_digitain;
    }

    /**
     * Get dotsuite Digitain Esport lobby
     *
     * @return mixed
     */
    public static function getDotSuiteDigitainEsportLobby()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$design - 1]->data;
        return $configuration->template->dotsuite_digitain_esport;
    }

    /**
     * Get dotsuite Event Bet lobby
     *
     * @return mixed
     */
    public static function getDotSuiteEventBetLobby()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$design - 1]->data;
        return $configuration->template->dotsuite_eventbet;
    }

    /**
     * Get dotsuite evolution lobby
     *
     * @return mixed
     */
    public static function getDotSuiteEvolutionLobby()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$design - 1]->data;
        return $configuration->template->dotsuite_evolution;
    }

    /**
     * Get dotsuite ezugi lobby
     *
     * @return mixed
     */
    public static function getDotSuiteEzugiLobby()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$design - 1]->data;
        return $configuration->template->dotsuite_ezugi;
    }

    /**
     * Get dotsuite fresh deck lobby
     *
     * @return mixed
     */
    public static function getDotSuiteFreshDeckLobby()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$design - 1]->data;
        return $configuration->template->dotsuite_fresh_deck;
    }

    /**
     * Get dotsuite Leap VG lobby
     *
     * @return mixed
     */
    public static function getDotSuiteLeapVGLobby()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$design - 1]->data;
        return $configuration->template->dotsuite_leap_vg;
    }

    /**
     * Get dotsuite Playson VG lobby
     *
     * @return mixed
     */
    public static function getDotSuitePlaysonVGLobby()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$design - 1]->data;
        return $configuration->template->dotsuite_playson_vg;
    }

    /**
     * Get dotsuite Platipus VG lobby
     *
     * @return mixed
     */
    public static function getDotSuitePlatipusVGLobby()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$design - 1]->data;
        return $configuration->template->dotsuite_platipus_vg;
    }

    /**
     * Get dotsuite Red Rake VG lobby
     *
     * @return mixed
     */
    public static function getDotSuiteRedRakeVGLobby()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$design - 1]->data;
        return $configuration->template->dotsuite_red_rake_vg;
    }

    /**
     * Get dotsuite Spinomenal VG lobby
     *
     * @return mixed
     */
    public static function getDotSuiteSpinomenalVGLobby()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$design - 1]->data;
        return $configuration->template->dotsuite_spinomenal_vg;
    }

    /**
     * Get dotsuite Tom Horn VG lobby
     *
     * @return mixed
     */
    public static function getDotSuiteTomHornVGLobby()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$design - 1]->data;
        return $configuration->template->dotsuite_tom_horn_vg;
    }

    /**
     * Get dotsuite universal soft lobby
     *
     * @return mixed
     */
    public static function getDotSuiteUniversalSoftLobby()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$design - 1]->data;
        return $configuration->template->dotsuite_universal_soft;
    }

    /**
     * Get dotsuite vivo gaming lobby
     *
     * @return mixed
     */
    public static function getDotSuiteVivoGamingLobby()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$design - 1]->data;
        return $configuration->template->dotsuite_vivo_gaming;
    }

    /**
     * Get dotsuite vivo gaming bingo lobby
     *
     * @return mixed
     */
    public static function getDotSuiteVivoGamingBingoLobby()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$design - 1]->data;
        return $configuration->template->dotsuite_vivo_gaming_bingo;
    }

    /**
     * Get configurations of email
     *
     * @param int $whitelabel Whitelabel ID
     * @param int $emailConfiguration Email configuration
     * @return mixed
     */
    public static function getEmailContents($whitelabel, $emailConfiguration)
    {
        $emailConfigurationsRepo = new EmailConfigurationsRepo();
        $configurations = $emailConfigurationsRepo->getEmailContents($whitelabel, $emailConfiguration);
        return $configurations;
    }

    /**
     * Get email notifications
     *
     * @return mixed
     */
    public static function getEmailNotifications()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$services - 1]->data;
        return $configuration->email_notifications;
    }

    /**
     * Get Google Analytics
     *
     * @return mixed
     */
    public static function getFacebookPixel()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$services - 1]->data;
        return $configuration->facebook_pixel;
    }

    /**
     * Get favicon
     *
     * @return string
     */
    public static function getFavicon()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$design - 1]->data;
        $favicon = null;

        if (!is_null($configuration->favicon)) {
            $favicon = s3_asset("commons/{$configuration->favicon}");
        }
        return $favicon;
    }

    /**
     * Get featured lobby
     *
     * @return mixed
     */
    public static function getFeaturedLobby()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$design - 1]->data;
        return $configuration->template->lobby_featured;
    }

    /**
     * Get footer
     *
     * @return string
     */
    public static function getFooter()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$design - 1]->data;
        return $configuration->template->footer;
    }

    /**
     * Get footer
     *
     * @return string
     */
    public static function getFooterMobile()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$design - 1]->data;
        return $configuration->footer_menu;
    }
    /**
     * Get free currencies
     *
     * @return mixed
     */
    public static function getFreeCurrency()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$services - 1]->data;
        return $configuration->free_currency;
    }

    /**
     * Get GoldenRace lobby
     *
     * @return mixed
     */
    public static function getGoldenRaceLobby()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$design - 1]->data;
        return $configuration->template->golden_race;
    }

    /**
     * Get Google Analytics
     *
     * @return mixed
     */
    public static function getGoogleAnalytics()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$services - 1]->data;
        return $configuration->google_analytics;
    }

    /**
     * Get Google Tag Manager
     *
     * @return mixed
     */
    public static function getGoogleTagManager()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$services - 1]->data;
        return $configuration->google_tag_manager;
    }

    /**
     * Get guest footer
     *
     * @return mixed
     */
    public static function getGuestFooter()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$design - 1]->data;
        return $configuration->template->guest_footer;
    }

    /**
     * Get guest header
     *
     * @return mixed
     */
    public static function getGuestHeader()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$design - 1]->data;
        return $configuration->template->guest_header;
    }

    /**
     * Get header
     *
     * @param null|integer $whitelabel Whitelabel ID
     * @return string
     */
    public static function getHeader($whitelabel = null)
    {
        if (!is_null($whitelabel)) {
            $configuration = self::getComponentConfiguration($whitelabel, Components::$design);

        } else {
            $configurations = config('whitelabels.configurations');
            $configuration = $configurations[Components::$design - 1]->data;
        }
        return $configuration->template->header;
    }

    /**
     * Get home
     *
     * @return string
     */
    public static function getHome()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$design - 1]->data;
        return $configuration->template->home;
    }

    /**
     * Get info lobby
     *
     * @return string
     */
    public static function getInfoLobby()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$design - 1]->data;
        return $configuration->template->lobby_info;
    }

    /**
     * Get languages
     *
     * @return mixed
     */
    public static function getLanguages()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$access - 1]->data;
        return $configuration->languages;
    }

    /**
     * Get level
     *
     * @param int $level Level ID
     *
     * @return mixed
     */
    public static function getLevel($level, $language)
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$access - 1]->data;
        $level = $configuration->levels[$level - 1]->$language;
        return $level;
    }

    /**
     * Get levels
     *
     * @return mixed
     */
    public static function getLevels()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$access - 1]->data;
        return $configuration->levels;
    }

    /**
     * Get live casino lobby
     *
     * @return mixed
     */
    public static function getLiveCasinoLobby()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$design - 1]->data;
        return $configuration->template->live_casino;
    }


    /**
     * Get landing page
     *
     * @return mixed
     */
    public static function getLandingPage()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$access - 1]->data;
        return $configuration->landing_page;
    }

    /**
     * Get login settings
     *
     * @return mixed
     */
    public static function getLogin()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$access - 1]->data;
        return $configuration->login;
    }

    /**
     * Get login view
     *
     * @return mixed
     */
    public static function getLoginView()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$design - 1]->data;
        return $configuration->template->login;
    }

    /**
     * Get logo
     *
     * @param bool $mobile Mobile device
     * @param null|integer $whitelabel Whitelabel ID
     * @return mixed
     */
    public static function getLogo($mobile = false, $whitelabel = null)
    {
        $templateElementsRepo = new TemplateElementsRepo();
        $header = self::getHeader($whitelabel);
        $templateElement = $templateElementsRepo->findByName($header);

        if (!is_null($whitelabel)) {
            $configuration = self::getComponentConfiguration($whitelabel, Components::$design);

        } else {
            $configurations = config('whitelabels.configurations');
            $configuration = $configurations[Components::$design - 1]->data;
        }

        if ($mobile) {
            if (!is_null($configuration->logo->img_mobile_dark)) {
                $configuration->logo->img_dark = s3_asset("commons/{$configuration->logo->img_mobile_dark}", $whitelabel);

            } else {
                if (!is_null($configuration->logo->img_dark)) {
                    $configuration->logo->img_dark = s3_asset("commons/{$configuration->logo->img_dark}", $whitelabel);

                } else {
                    $size = $templateElement->data->section_images->positions->{"logo-dark"};
                    $configuration->logo->img_dark = "https://bestcasinos-llc.s3.us-east-2.amazonaws.com/logos/default/logo.png";
                }
            }

            if (!is_null($configuration->logo->img_mobile_light)) {
                $configuration->logo->img_light = s3_asset("commons/{$configuration->logo->img_mobile_light}", $whitelabel);

            } else {
                if (!is_null($configuration->logo->img_light)) {
                    $configuration->logo->img_light = s3_asset("commons/{$configuration->logo->img_light}", $whitelabel);

                } else {
                    $size = $templateElement->data->section_images->positions->{"logo-light"};
                    $configuration->logo->img_light = "https://bestcasinos-llc.s3.us-east-2.amazonaws.com/logos/default/logo.png";
                }
            }

        } else {
            if (!is_null($configuration->logo->img_dark)) {
                $configuration->logo->img_dark = s3_asset("commons/{$configuration->logo->img_dark}", $whitelabel);

            } else {
                $size = $templateElement->data->section_images->positions->{"logo-dark"};
                $configuration->logo->img_dark = "https://bestcasinos-llc.s3.us-east-2.amazonaws.com/logos/default/logo.png";
            }

            if (!is_null($configuration->logo->img_light)) {
                $configuration->logo->img_light = s3_asset("commons/{$configuration->logo->img_light}", $whitelabel);

            } else {
                $size = $templateElement->data->section_images->positions->{"logo-light"};
                $configuration->logo->img_light = "https://bestcasinos-llc.s3.us-east-2.amazonaws.com/logos/default/logo.png";
            }
        }
        return $configuration->logo;
    }

    /**
     * Get Mailgun Notifications
     *
     * @return mixed
     */
    public static function getMailgunNotifications()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$services - 1]->data;
        return $configuration->mailgun_notifications;
    }

    /**
     * Get main route
     *
     * @param bool $mobile Mobile device
     * @return mixed
     */
    public static function getMainRoute($mobile)
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$access - 1]->data;
        $route = $mobile ? $configuration->main_route->mobile : $configuration->main_route->desktop;
        return $route;
    }

    /**
     * Get menu
     *
     * @return mixed
     */
    public static function getMenu()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$design - 1]->data;
        return $configuration->menu;
    }

    /**
     * Get messages
     *
     * @return mixed
     */
    public static function getMessages()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$services - 1]->data;
        return $configuration->messages;
    }

    /**
     * Get metas
     *
     * @param string $language Language ISO
     * @param bool $default Default metas
     * @return mixed
     */
    public static function getMetas($language, $default = false)
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$design - 1]->data;
        $locale = $language;
        $metas = null;

        if (!$default) {
            foreach ($configuration->menu as $menu) {
                if (route($menu->route) == url()->current()) {
                    $metas = $menu->metas->$locale;
                    break;
                }
            }

            if (is_null($metas)) {
                foreach ($configuration->sections as $section) {
                    if (isset($section->route)) {
                        if (route($section->route) == url()->current()) {
                            $metas = $section->metas->$locale;
                            break;
                        }
                    }
                }
            }
        } else {
            $metas = $configuration->sections->Default->metas->$locale;
        }
        return $metas;
    }

    /**
     * Get notice lobby
     *
     * @return mixed
     */
    public static function getNoticeLobby()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$design - 1]->data;
        return $configuration->template->news;
    }

    /**
     * Get notifications lobby
     *
     * @return string
     */
    public static function getNotificationsLobby()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$design - 1]->data;
        return $configuration->template->lobby_notifications;
    }


    /**
     * Get open games
     *
     * @return mixed
     */
    public static function getOpenGames()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$design - 1]->data;
        return $configuration->template->open_games;
    }

    /**
     * Get page content
     *
     * @param string $slug Page slug
     * @return mixed
     */
    public static function getPageContent($slug)
    {
        $pagesRepo = new PagesRepo();
        $whitelabel = Configurations::getWhitelabel();
        $page = $pagesRepo->find($whitelabel, $slug);
        return $page;
    }

    /**
     * Get password regex
     *
     * @return mixed
     */
    public static function getPasswordRegex()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$access - 1]->data;
        return $configuration->registration->password_regex;
    }

    /**
     * Get payments
     *
     * @return mixed
     */
    public static function getPayments()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$services - 1]->data;
        return $configuration->payments;
    }

    /**
     * Get phone confirmation
     *
     * @return mixed
     */
    public static function getPhoneConfirmation()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$access - 1]->data;
        return $configuration->registration->phone->confirmation;
    }

    /**
     * Get posts view
     *
     * @return mixed
     */
    public static function getPostsView()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$design - 1]->data;
        return $configuration->template->posts;
    }

    /**
     * Get pragmatic casino lobby
     *
     * @return mixed
     */
    public static function getPragmaticCasinoLobby()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$design - 1]->data;
        return $configuration->template->pragmatic_casino;
    }

    /**
     * Get pragmatic play lobby
     *
     * @return mixed
     */
    public static function getPragmaticPlayLobby()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$design - 1]->data;
        return $configuration->template->pragmatic_play;
    }

    /**
     * Get PWA
     *
     * @return mixed
     */
    public static function getPwa()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$services - 1]->data;
        return $configuration->pwa;
    }

    /**
     * Get recommended lobby
     *
     * @return mixed
     */
    public static function getRecommendedLobby()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$design - 1]->data;
        return $configuration->template->lobby_recommended;
    }

    /**
     * Get refer friend
     *
     * @return mixed
     */
    public static function getReferFriend()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$services - 1]->data;
        return $configuration->refer_friend;
    }

    /**
     * Get register view
     *
     * @return mixed
     */
    public static function getRegisterView()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$design - 1]->data;
        return $configuration->template->register;
    }

    /**
     * Get registration settings
     *
     * @return mixed
     */
    public static function getRegistration()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$access - 1]->data;
        return $configuration->registration;
    }

    /**
     * Get roulette lobby
     *
     * @return mixed
     */
    public static function getRouletteLobby()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$design - 1]->data;
        return $configuration->template->roulette;
    }

    /**
     * Get Reset Main Password
     *
     * @return mixed
     */
    public static function getResetMainPassword()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$services - 1]->data;
        return $configuration->reset_main_password;
    }

    /**
     * Get S3 directory
     *
     * @return mixed
     */
    public static function getS3Directory()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$design - 1]->data;
        return $configuration->s3_directory;
    }

    /**
     * Get Services
     *
     * @return mixed
     */
    public static function getServices()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$services - 1]->data;
        return $configuration;
    }

    /**
     * Get sliders mega home
     *
     * @return mixed
     */
    public static function getSlidersMegaHome()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$design - 1]->data;
        return $configuration->template->home->sliders_mega_home;
    }

    /**
     * Get slots lobby
     *
     * @return mixed
     */
    public static function getSlotsLobby()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$design - 1]->data;
        return $configuration->template->slots;
    }

    /**
     * Get social networks
     *
     * @return array
     */
    public static function getSocialNetworks()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$design - 1]->data;
        return $configuration->social_networks;
    }

    /**
     * Get store from whitelabels and Dotpanel
     *
     * @return mixed
     */
    public static function getStore()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$services - 1]->data;
        return $configuration->store;
    }

    /**
     * Get store from integration
     *
     * @param int $whitelabel Whtielabel ID
     * @return mixed
     */
    public static function getStoreIntegration($whitelabel)
    {
        $configuration = self::getComponentConfiguration($whitelabel, Components::$services);
        return $configuration->store;
    }

    /**
     * Get template element
     *
     * @param string $element Element name
     * @return mixed
     */
    public static function getTemplateElement($element)
    {
        $templateElementsRepo = new TemplateElementsRepo();
        $elementData = $templateElementsRepo->findByName($element);
        return $elementData;
    }

    /**
     * Get theme
     *
     * @return mixed
     */
    public static function getTheme()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$design - 1]->data;
        return "{$configuration->template->theme}/theme.min.css";
    }

    /**
     * Get configurations by url
     *
     * @param string $url Whitelabel ID
     * @return mixed
     */
    public static function getConfigurationsByUrl($url)
    {
        $whitelabelsRepo = new WhitelabelsRepo();
        $configurations = $whitelabelsRepo->getConfigurationsByUrl($url);
        return $configurations;
    }

    /**
     * Get username regex
     *
     * @return mixed
     */
    public static function getUsernameRegex()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$access - 1]->data;
        return $configuration->registration->username_regex;
    }

    /**
     * Get virtual lobby
     *
     * @return mixed
     */
    public static function getVirtualLobby()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$design - 1]->data;
        return $configuration->template->virtual;
    }

    /**
     * Get withdrawals cancel bonuses
     *
     * @return mixed
     */
    public static function getWithdrawalsCancelBonuses()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$services - 1]->data;
        return $configuration->withdrawals_cancel_bonuses;
    }

    /**
     * Get whitelabel
     *
     * @return mixed
     */
    public static function getWhitelabel()
    {
        $configurations = config('whitelabels.configurations');
        return $configurations[0]->whitelabel_id;
    }

    /**
     * Get whitelabel description
     *
     * @param null|int $whitelabel Whitelabel ID
     * @return mixed
     */
    public static function getWhitelabelDescription($whitelabel = null)
    {
        if (!is_null($whitelabel)) {
            $whitelabelsRepo = new WhitelabelsRepo();
            $configurations = $whitelabelsRepo->getConfigurationsByWhitelabel($whitelabel);

        } else {
            $configurations = config('whitelabels.configurations');
        }
        return $configurations[0]->whitelabel_description;
    }

    /**
     * Get whitelabel info
     *
     * @return mixed
     */
    public static function getWhitelabelInfo()
    {
        $configurations = config('whitelabels.configurations');
        $configuration = $configurations[Components::$design - 1]->data;
        return $configuration->whitelabel_info;
    }

    /**
     * Get whitelabel name
     *
     * @return mixed
     */
    public static function getWhitelabelName()
    {
        $configurations = config('whitelabels.configurations');
        return $configurations[0]->whitelabel_name;
    }

    /**
     * Get whitelabel social reason
     *
     * @return mixed
     */
    public static function getWhitelabelSocialReason()
    {
        $configurations = config('whitelabels.configurations');
        return $configurations[0]->whitelabel_social_reason;
    }

    /**
     * Generate rand passport
     *
     * @param int $length Password length
     * @return mixed
     */
    public static function generateRandPassword($length = 10)
    {
        $base = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
        $password = '';
        for ($i = 0; $i < $length; $i++) {
            $password .= substr($base, rand(0, 62), 1);
        }
        return $password;
    }

    /**
     * Generate Reference Code
     *
     * @param int $user User ID
     * @param string $whitelabelName Whitelabel name
     * @return mixed
     */
    public static function generateReferenceCode($user, $whitelabelName = null)
    {
        if (is_null($whitelabelName)) {
            $whitelabelName = Configurations::getWhitelabelName();
        }

        $usersRepo = new UsersRepo();
        $nameWhitelabel = substr($whitelabelName, 0, 3);
        $userId = substr($user, -4, 4);
        $rand = mt_rand(1, 9999);
        $rand = str_pad($rand, 4, 0, STR_PAD_LEFT);
        $referenceCode = $nameWhitelabel . $userId . $rand;
        $userData = [
            'referral_code' => $referenceCode
        ];
        return $usersRepo->update($user, $userData);
    }

    /**
     * Set email
     *
     * @param null|int $whitelabel Whitelabel ID
     */
    public static function setEmail($whitelabel = null)
    {
        if (!is_null($whitelabel)) {
            $configuration = self::getComponentConfiguration($whitelabel, Components::$providers);

        } else {
            $configurations = config('whitelabels.configurations');
            $configuration = $configurations[Components::$providers - 1]->data;
        }
        $email = $configuration->email;
        config(['mail.mailers.smtp.host' => $email->host]);
        config(['mail.mailers.smtp.port' => $email->port]);
        config(['mail.mailers.smtp.encryption' => $email->encryption]);
        config(['mail.mailers.smtp.username' => $email->username]);
        config(['mail.mailers.smtp.password' => $email->password]);
        config(['mail.default' => $email->mailer]);
        config(['mail.from.address' => $email->username]);
        config(['mail.from.name' => self::getWhitelabelDescription($whitelabel)]);
        config(['services.mailgun.domain' => $email->mailgun_domain]);
    }

    /**
     * Update configuration
     *
     * @param int $whitelabel Whitelabel ID
     * @param int $component Component ID
     * @param array $data Configuration data
     * @return mixed
     */
    public static function update($whitelabel, $component, $data)
    {
        $whitelabelsRepo = new WhitelabelsRepo();
        $configuration = $whitelabelsRepo->updateConfiguration($whitelabel, $component, $data);
        return $configuration;
    }

    /**
     * Update user
     *
     * @param int $user User ID
     * @param array $data User Data
     * @return mixed
     */
    public static function updateUser($user, $data)
    {
        $userRepo = new UsersRepo();
        $users = $userRepo->update($user, $data);
        return $users;
    }
}

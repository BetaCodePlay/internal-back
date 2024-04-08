<?php

namespace App\Core\Collections;

use App\Core\Enums\Languages;
use Carbon\Carbon;
use Xinax\LaravelGettext\Facades\LaravelGettext;

/**
 * Class CoreCollection
 *
 * This class allows to format core data
 *
 * @package App\Core\Collections
 * @author  Eborio Linarez
 */
class CoreCollection
{
    /**
     * Format exchange rates
     *
     * @param $exchanges
     */
    public function formatExchangeRates($exchanges)
    {
        $timezone = session('timezone');
        foreach ($exchanges as $exchange) {
            $exchange->updated = $exchange->updated_at->setTimezone($timezone)->format('d-m-Y H:i:s');;
        }
    }

    /**
     * Format languages
     *
     * @return array
     */
    public function formatLanguages($languages)
    {
        $languagesData = [];
        foreach ($languages as $language) {
            $country = strtolower(substr($language, 3, 2));
            $languagesData[] = [
                'iso' => $language,
                'country_iso' => $country,
                'flag' => global_asset("flags/circle/$country.png"),
                'name' => Languages::getName($language)
            ];
        }
        return $languagesData;
    }

    /**
     * Formar login Connected
     *
     * @param array $auditsData Audits data login
     * @return array
     */
    public function formatLoginConnected($auditsData)
    {
        $audits = [];
        $desktop = 0;
        $mobile = 0;

        if (count($auditsData) > 0) {
            foreach ($auditsData as $audit) {
                if ($audit->data->mobile) {
                    $mobile++;
                } else {
                    $desktop++;
                }
            }
            $audits[] = [
                'desktop' => $desktop,
                'mobile' => $mobile,
            ];
        } else {
            $audits[] = [
                'desktop' => 0,
                'mobile' => 0,
            ];
        }

        return $audits;
    }

    /**
     * Format selected language
     *
     * @param string $language Language ISO
     * @return array
     */
    public function formatSelectedLanguage($language)
    {
        $country = strtolower(substr($language, 3, 2));
        $isoAbbreviation = substr($language, 0, 2);
        $languagesData = [
            'iso' => $language,
            'iso_abbreviation' => $isoAbbreviation,
            'country_iso' => $country,
            'flag' => global_asset("flags/circle/$country.png"),
            'name' => Languages::getName($language)
        ];
        return $languagesData;
    }

    /**
     * Format timezones
     *
     * @return array
     */
    public function formatTimezones()
    {
        $timezonesData = [];
        $timezones = \DateTimeZone::listIdentifiers();
        foreach ($timezones as $timezone) {
            $date = Carbon::now($timezone);
            $date->utcOffset();
            $timezonesData[] = [
                'timezone' => $timezone,
                'text' => "{$timezone} ({$date->getOffsetString()})"
            ];
        }
        return $timezonesData;
    }

    /**
     * Format whitelabel menu
     *
     * @param array $menu Menu data
     * @return array
     */
    public function formatWhitelabelMenu($menu)
    {
        $items = collect();
        foreach ($menu as $item) {
            $itemObject = new \stdClass();
            $locale = LaravelGettext::getLocale();
            $itemObject->name = $item->metas->$locale->name;
            $itemObject->route = $item->route;
            $items->push($itemObject);
        }
        return $items->sortBy('name')->values()->all();
    }
}

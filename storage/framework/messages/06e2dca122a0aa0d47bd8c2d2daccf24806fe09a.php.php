<?php

namespace App\Core\Collections;

use Xinax\LaravelGettext\Facades\LaravelGettext;

/**
 * Class CurrenciesCollection
 *
 * This class allows format currencies data
 *
 * @package App\Core\Collections
 * @author  Eborio Linarez
 */
class CurrenciesCollection
{
    /**
     * Format all currencies
     *
     * @param array $currencies Currencies data
     */
    public function formatAll($currencies)
    {
        $locale = LaravelGettext::getLocale();

        foreach ($currencies as $currency) {
            if (!is_null($currency->translations)) {
                $translations = get_object_vars($currency->translations);
                $firstLocale = array_keys($translations)[0];
                $currency->name = $currency->translations->$locale ?? $currency->translations->$firstLocale;
            }
        }
    }

    /**
     * Format whitelabel currencies
     *
     * @param array $whitelabelCurrencies Whitelabel currencies
     * @param array $allCurrencies All currencies
     * @return array
     */
    public function formatWhitelabelCurrencies($whitelabelCurrencies, $allCurrencies)
    {
        $locale = LaravelGettext::getLocale();
        $currencies = [];

        foreach ($whitelabelCurrencies as $currency) {
            $currencyObject = new \stdClass();
            if ($currency == 'VEF') {
                $currencyObject->iso = $currency;
                $currencies[] = $currencyObject;

            } else {
                foreach ($allCurrencies as $allCurrency) {
                    if ($currency == $allCurrency->iso) {
                        $translations = get_object_vars($allCurrency->translations);
                        $firstLocale = array_keys($translations)[0];
                        $currencyObject->iso = $currency;
                        $currencyObject->name = $allCurrency->translations->$locale ?? $allCurrency->translations->$firstLocale;
                        $currencies[] = $currencyObject;
                    }
                }
            }
        }
        return $currencies;
    }

    /**
     * Get only currency ISOs
     *
     * @param array $currencies Currencies data
     * @return array
     */
    public function getOnlyIsos($currencies)
    {
        $isos = [];

        foreach ($currencies as $currency) {
            $isos[] = $currency->iso;
        }
        return $isos;
    }
}

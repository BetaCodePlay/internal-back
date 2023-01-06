<?php

namespace Dotworkers\Alerts;

use Dotworkers\Configurations\Utils;
use Dotworkers\Configurations\Enums\Providers;
use Ixudra\Curl\Facades\Curl;

/**
 * Class Alert
 *
 * This class allows to interact with alerts  alerts
 *
 * @package Dotworkers\Alerts
 * @author  Derluin Gonzalez
 */
class Alerts
{
    /**
     * Send telegram
     *
     * @param $text array
     * @param int $whitelabel Whitelabel ID
     * @param string $currency Currency ISO
     */
    public static function sendTelegram($text, $whitelabel, $currency)
    {
        $credential = Utils::getCredentials($whitelabel, Providers::$telegram, $currency);

        if (!is_null($credential)) {
            $service = 'https://api.telegram.org/bot';
            $alertsService = $service . $credential->bot;

            $url = "{$alertsService}/sendmessage";
            $requestData = [
                'chat_id' => $credential->channel,
                'disable_web_page_preview' => 1,
                'text' => $text
            ];
           return $curl = Curl::to($url)
                ->withData($requestData)
                ->withHeader('Accept: application/json')
                ->post();
        }

    }
}

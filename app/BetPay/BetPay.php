<?php

namespace App\BetPay;

use Dotworkers\Configurations\Configurations;
use Dotworkers\Configurations\Enums\Providers;
use Dotworkers\Configurations\Enums\Status;
use Dotworkers\Configurations\Utils;
use Illuminate\Support\Facades\Log;
use Ixudra\Curl\Facades\Curl;

/**
 * Class Core
 *
 * This class allows to create BetPay utils functions
 *
 * @package App\Core
 * @author  Eborio Linarez
 */
class BetPay
{
    /**
     * Get BetPay client access token
     */
    public static function getBetPayClientAccessToken()
    {
        $requestData = null;
        $curl = null;
        $url = null;

        try {
            $payments = Configurations::getPayments();

            if ($payments) {
                $whitelabel = Configurations::getWhitelabel();
                $currency = session('currency');
                $credentials = Utils::getCredentials($whitelabel, Providers::$betpay, $currency);

                if (!is_null($credentials)) {
                    $requestData = [
                        'grant_type' => 'client_credentials',
                        'client_id' => $credentials->client_credentials_grant_id,
                        'client_secret' => $credentials->client_credentials_grant_secret,
                        'scope' => 'get-payment-methods get-banks get-transactions process-transactions get-client-accounts get-user-accounts update-user-accounts'
                    ];
                    $url = env('BETPAY_SERVER') . '/oauth/token';
                    $curl = Curl::to($url)
                        ->withData($requestData)
                        ->post();
                    $response = json_decode($curl);
                    Log::info(__METHOD__, ['  $response ' =>  $response ]);
                    if (!isset($response->error)) {
                        session()->put('betpay_client_id', $credentials->client_credentials_grant_id);
                        session()->put('betpay_client_access_token', $response->access_token);
                    } else {
                        \Log::error(__METHOD__, ['url' => $url, 'curl' => $curl, 'request_data' => $requestData, 'error' => $response->error]);
                    }
                }
            }
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['url' => $url, 'curl' => $curl, 'request_data' => $requestData]);
        }
    }

    /**
     * Get client payment methods
     *
     * @return array
     */
    public static function getClientPaymentMethods($currency = null)
    {
        try {
            $payments = Configurations::getPayments();
            $requestData = null;
            $curl = null;

            if ($payments) {
                $url = env('BETPAY_SERVER') . '/api/clients/payment-methods/all';
                $betPayToken = session('betpay_client_access_token');

                if (!is_null($betPayToken)) {
                    $currencyData = is_null($currency) ? session('currency') : $currency;
                    $requestData = [
                        'currency' => $currencyData
                    ];
                    $curl = Curl::to($url)
                        ->withData($requestData)
                        ->withHeader('Accept: application/json')
                        ->withHeader("Authorization: Bearer $betPayToken")
                        ->get();
                    $response = json_decode($curl);

                    if ($response->status == Status::$ok) {
                        $paymentMethods = $response->data->payment_methods;

                    } else {
                        \Log::error(__METHOD__, ['curl' => $curl, 'curl_request' => $requestData]);
                        $paymentMethods = [];
                    }
                } else {
                    $paymentMethods = [];
                }
            } else {
                $paymentMethods = [];
            }
            return $paymentMethods;

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'curl' => $curl, 'curl_request' => $requestData]);
            return [];
        }
    }
}

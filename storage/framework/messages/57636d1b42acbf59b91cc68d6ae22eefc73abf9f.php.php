<?php

namespace App\Core\Repositories;

use App\Core\Entities\Credential;

/**
 * ClassCredentialsRepo
 *
 * This class allows to interact with credential entity
 *
 * @package App\Core\Repositories
 * @author  Orlando Bravo
 */
class CredentialsRepo
{

    /**
     * Search credentials currency provider
     *
     * @param int $client Client tupe ID
     * @param int $provider Provider type ID
     * @param string $currency Currency iso
     * @return mixed
     */
    public function deleteCredential($client, $provider, $currency)
    {
        $credentials = Credential::where('client_id', $client)
            ->where('provider_id', $provider)
            ->where('currency_iso', $currency)
            ->delete();
        return $credentials;
    }

    /**
     * Ger dot suite credentials by currency provider
     *
     * @param int $client Client  ID
     * @param int $provider Provider type ID
     * @param array $currencies Currency iso
     * @return mixed
     */
    public function getDotSuiteCredentials($client, $provider, $currencies)
    {
        $credentials = Credential::select('credentials.*', 'whitelabels.name as client_name')
            ->join('whitelabels', 'credentials.client_id', '=', 'whitelabels.id')
            ->where('client_id', $client);

            if (!empty($provider)) {
                $credentials->where('provider_id', $provider);
            }

            if (!is_null($currencies) && !in_array(null, (array)$currencies)) {
                $credentials->whereIn('currency_iso', $currencies);
            }

        $data = $credentials->get();
        return $data;
    }

    /**
     * Search credentials currency provider
     *
     * @param int $client Client tupe ID
     * @param int $provider Provider type ID
     * @param string $currency Currency iso
     * @return mixed
     */
    public function searchByCredential($client, $provider, $currency)
    {
        $credentials = Credential::where('client_id', $client)
            ->where('provider_id', $provider)
            ->where('currency_iso', $currency)
            ->first();
        return $credentials;
    }

    /**
     * Search credentials currency provider
     *
     * @param int $provider Provider type ID
     * @return mixed
     */
    public function searchByProvider($provider)
    {
        $credentials = Credential::select('client_id', 'provider_id', 'currency_iso', 'whitelabels.description As client', 'data', 'credentials.status', 'credentials.percentage')
            ->join('whitelabels', 'credentials.client_id', '=', 'whitelabels.id')
            ->where('provider_id', $provider)
            ->orderBy('client_id', 'ASC')
            ->get();
        return $credentials;
    }

    /**
     * Search credentials by params
     *
     * @param int $provider Provider type ID
     * @return mixed
     */
    public function searchByProviderType($client,$type,$currency,$provider)
    {
        $credentials = Credential::select('client_id', 'provider_id', 'currency_iso', 'provider_type_id', 'whitelabels.description As client', 'credentials.percentage')
            ->join('whitelabels', 'credentials.client_id', '=', 'whitelabels.id')
            ->join('providers', 'credentials.provider_id', '=', 'providers.id')
            ->where('client_id', $client)
            ->where('providers.provider_type_id', $type)
            ->where('provider_id', $provider);

            if(!is_null($currency)){
                $credentials->where('currency_iso', $currency);
            }

            $data = $credentials->orderBy('client_id', 'ASC')
            ->get();
            return $data;
        return $credentials;
    }

    /**
     * Search credentials currency whitelabel
     *
     * @param int $whitelabel Whitelabel tupe ID
     * @param string $currency Currency iso
     * @return mixed
     */
    public function searchByWhitelabel($whitelabel, $currency)
    {
        $credentials = Credential::where('client_id', $whitelabel)
            ->join('providers', 'credentials.provider_id', '=', 'providers.id')
            ->whereIn('providers.provider_type_id', [3, 4, 5])
            ->where('currency_iso', $currency)
            ->orderBy('providers.name', 'ASC')
            ->get();
        return $credentials;
    }

    /**
     * Search credentials type providers dotsuite
     *
     * @param int $whitelabel Whitelabel tupe ID
     * @param string $currency Currency iso
     * @return mixed
     */
    public function searchByWhitelabelDotsuite($whitelabel, $currency)
    {
        $credentials = Credential::where('client_id', $whitelabel)
            ->join('providers', 'credentials.provider_id', '=', 'providers.id')
            ->whereNotNull('providers.dotsuite_provider_id')
            ->where('currency_iso', $currency)
            ->whereIn('providers.provider_type_id', [3, 4, 5])
            ->orderBy('providers.name', 'ASC')
            ->get();
        return $credentials;
    }

    /**
     * Store credentials
     *
     * @param array $data Credentials data
     * @return static
     */
    public function store($data)
    {
        $credentials = Credential::create($data);
        return $credentials;
    }

    /**
     * Update credentials
     *
     * @param int $client Client ID
     * @param string $currency Currency ISO
     * @param int $provider Provider ID
     * @param array $data Credentials data
     * @return static
     */
    public function update($client, $currency, $provider, $data)
    {
        $credentials = Credential::where('client_id', $client)
            ->where('currency_iso', $currency)
            ->where('provider_id', $provider)
            ->update($data);
        return $credentials;
    }


}

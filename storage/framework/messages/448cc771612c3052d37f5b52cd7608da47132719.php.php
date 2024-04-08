<?php

namespace Dotworkers\Configurations\Repositories;
/**
 * Class PagesRepo
 *
 * This class allows to interact with credentials table
 *
 * @package Dotworkers\Configurations\Repositories
 * @author  Eborio Linarez
 */
class CredentialsRepo
{
    /**
     * Find credentials
     *
     * @param int $client Client ID
     * @param int $provider Provider ID
     * @param string $currency Currency ISO
     * @return mixed
     */
    public function find($client, $provider, $currency)
    {
        $credentials = \DB::connection(config('configurations.replica'))
            ->table('credentials')
            ->where('client_id', $client)
            ->where('provider_id', $provider)
            ->where('currency_iso', $currency)
            ->where('status', true)
            ->first();
        return $credentials;
    }
}

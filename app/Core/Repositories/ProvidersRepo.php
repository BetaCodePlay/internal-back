<?php

namespace App\Core\Repositories;

use App\Core\Entities\Provider;
use App\Core\Enums\Status;
use Dotworkers\Configurations\Enums\ProviderTypes;
use Illuminate\Support\Facades\DB;

/**
 * Class ProvidersRepo
 *
 * This class allows to interact with Provider entity
 *
 * @package App\Core\Repositories
 * @author  Eborio Linarez
 */
class ProvidersRepo
{
    /**
     * Find provider
     *
     * @param int $provider Provider ID
     * @return mixed
     */
    public function find($provider)
    {
        $providers = Provider::where('providers.id', $provider)
            ->first();
        return $providers;
    }

    public function allIds()
    {
        $providers = Provider::select('providers.id')
            ->where('status', true)
            ->whereNotNull('dotsuite_provider_id')
            ->get(['id']);
        $array = [];
        foreach ($providers as $value){
            $array[]=$value->id;
        }

        return $array;
    }

    /**
     * Get by types
     *
     * @param array $types Provider types
     * @return mixed
     */
    public function getByTypes($types)
    {
        $providers = Provider::whereIn('provider_type_id', $types)
            ->where('status', true)
            ->orderBy('name', 'ASC')
            ->get();
        return $providers;
    }

    /**
     * Get by types exclude
     *
     * @param array $types Provider types
     * @return mixed
     */
    public function getByTypesExclude($types)
    {
        $providers = Provider::select('id')
            ->whereIn('provider_type_id', $types)
            ->orderBy('name', 'ASC')
            ->get();
        return $providers;
    }

    /**
     * Get by types and actives
     *
     * @param array $types Provider types
     * @return mixed
     */
    public function getByTypesAndActives($types, $whitelabel, $currency)
    {
        $providers = Provider::whereIn('provider_type_id', $types)
            ->join('credentials', 'providers.id', '=', 'credentials.provider_id')
            ->where('client_id', $whitelabel)
            ->where('currency_iso', $currency)
            ->whereNotNull('dotsuite_provider_id')
            ->where('credentials.status', true)
            ->where('providers.status', true)
            ->orderBy('name', 'ASC')
            ->get();
        return $providers;
    }

    /**
     * Get by betpay id
     *
     * @param array $ids Segments IDs
     * @return mixed
     */
    public function getByIDs($ids)
    {
        return Provider::whereIn('id', $ids)
            ->get();
    }

    /**
     * Get by betpay id
     *
     * @param array $ids Segments IDs
     * @return mixed
     */
    public function getByBeyPayIDs($ids)
    {
        return Provider::select('providers.name as provider', 'providers.id')
            ->whereIn('betpay_id', $ids)
            ->get();
    }
    /**
     * Get Providers
     *
     * @return mixed
     */
    public function getProviders()
    {
        $providers = Provider::select('providers.*')
            ->orderBy('name', 'ASC')
            ->get();
        return $providers;
    }

    /**
     * Get providers by whitelabel
     *
     * @param int $whitelabel Whitelabel ID
     * @return mixed
     */
    public function getProvidersByWhitelabel(int $whitelabel)
    {
        return Provider::select('providers.*')
            ->join('credentials', 'providers.id', '=', 'credentials.provider_id')
            ->where('client_id', $whitelabel)
            ->where('providers.status', true)
            ->groupBy('providers.id')
            ->orderBy('name', 'ASC')
            ->get();
    }

    /**
     * Get by whitelabel
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $currency Currency ISO
     * @return mixed
     */
    public function getByWhitelabel(int $whitelabel, string $currency)
    {
        return Provider::select('providers.*')
            ->join('credentials', 'providers.id', '=', 'credentials.provider_id')
            ->where('client_id', $whitelabel)
            ->where('currency_iso', $currency)
            ->orderBy('name', 'ASC')
            ->get();
    }

    /**
     * Get by whitelabel and types
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $currency Currency ISO
     * @param array $providerTypes Provider types
     * @return mixed
     */
    public function getByWhitelabelAndTypes($whitelabel, $currency, $providerTypes)
    {
        return Provider::on('replica')
            ->select('providers.*')
            ->whereIn(DB::raw('provider_type_id::integer'), $providerTypes)
            ->join('credentials', 'providers.id', '=', 'credentials.provider_id')
            ->where('client_id', $whitelabel)
            ->where('currency_iso', $currency)
            ->get();
    }

    /**
     * Get by whitelabel and providers
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $currency Currency ISO
     * @param array $providerTypes Provider types
     * @return mixed
     */
    public function getByWhitelabelAndProviders($whitelabel, $provider, $currency)
    {
        $whitelabelsData = Provider::select('providers.id as provider', 'whitelabels.name as whitelabel', 'credentials.currency_iso', 'credentials.percentage', 'credentials.client_id')
            ->join('credentials', 'providers.id', '=', 'credentials.provider_id')
            ->join('whitelabels', 'credentials.client_id', '=', 'whitelabels.id')
            ->where('providers.status', Status::$active);

        if (!is_null($whitelabel)) {
            $whitelabelsData->where('credentials.client_id', $whitelabel);
        }

        if (!is_null($provider)) {
            $whitelabelsData->where('providers.id', $provider);
        }

        if (!is_null($currency)) {
            $whitelabelsData->where('credentials.currency_iso', $currency);
        }

        return $whitelabelsData->get();
    }

    /**
     * Get payment methods
     *
     * @return mixed
     */
    public function getByPaymentMethodsTypes()
    {
        return Provider::select('providers.name', 'providers.betpay_id')
            ->where('provider_type_id', ProviderTypes::$payment)
            ->whereNotNull('betpay_id')
            ->get();
    }

    /**
     * Update Provider
     *
     * @param int $id Provider ID
     * @param array $data Provider data
     * @return mixed
     */
    public function update($id, $data)
    {
        $game = Provider::find($id);
        $game->fill($data);
        $game->save();
        return $game;
    }
}

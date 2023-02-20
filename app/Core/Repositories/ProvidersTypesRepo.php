<?php


namespace App\Core\Repositories;

use App\Core\Entities\ProviderType;

/**
 * Class ProvidersTypesRepo
 *
 * This class allows to interact with ProviderType entity
 *
 * @package App\Core\Repositories
 * @author  Damelys Espinoza
 */
class ProvidersTypesRepo
{

    /**
     * Get all types providers
     *
     * @return mixed
     */
    public function all()
    {
        $providers = ProviderType::orderBy('name', 'ASC')
            ->get();
        return $providers;
    }


    /**
     * Get providers types by ids
     *
     * @param array $ids Provider type ID
     * @return mixed
     */
    public function getByIds($ids)
    {
        $providers = ProviderType::whereIn('id', $ids)
            ->orderBy('name', 'ASC')
            ->get();
        return $providers;
    }

    public function getByIdsOrderId($ids,$order = 'ASC')
    {
        $providers = ProviderType::whereIn('id', $ids)
            ->orderBy('id', $order)
            ->get();
        return $providers;
    }

    /**
     * Get by whitelabel
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $currency Currency ISO
     * @param array $ids Provider type ID
     * @return mixed
     */
    public function getByWhitelabel($whitelabel, $currency, $ids)
    {
        return ProviderType::select('provider_types.id')
            ->distinct()
            ->join('providers', 'providers.provider_type_id', '=', 'provider_types.id')
            ->join('credentials', 'credentials.provider_id', '=', 'providers.id')
            ->where('credentials.client_id', $whitelabel)
            ->where('credentials.currency_iso', $currency)
            ->whereIn('provider_types.id', $ids)
            ->get();
    }

    /**
     * Get by whitelabel and currencies
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $currencies Currencies ISOs
     * @param array $ids Provider type ID
     * @return mixed
     */
    public function getByWhitelabelAndCurrencies($whitelabel, $currencies, $ids)
    {
        return ProviderType::select('provider_types.id')
            ->distinct()
            ->join('providers', 'providers.provider_type_id', '=', 'provider_types.id')
            ->join('credentials', 'credentials.provider_id', '=', 'providers.id')
            ->where('credentials.client_id', $whitelabel)
            ->whereIn('credentials.currency_iso', $currencies)
            ->whereIn('provider_types.id', $ids)
            ->get();
    }
}

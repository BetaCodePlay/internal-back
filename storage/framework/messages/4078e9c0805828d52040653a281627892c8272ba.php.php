<?php

namespace App\Core\Repositories;

use App\Core\Entities\ProductLimit;
use App\Core\Entities\ProviderLimit;
use Illuminate\Support\Facades\DB;

/**
 * Class ProvidersLimitsRepo
 *
 * This class allows to interact with ProductLimit entity
 *
 * @package App\Core\Repositories
 * @author  Eborio Linarez
 */
class ProvidersLimitsRepo
{
    /**
     * Get all limits
     *
     * @param int $provider Provider ID
     * @return mixed
     */
    public function all($provider)
    {
        $limits = ProviderLimit::select('provider_limit_whitelabel.*', 'whitelabels.description')
            ->join('whitelabels', 'provider_limit_whitelabel.whitelabel_id', '=', 'whitelabels.id')
            ->where('provider_id', $provider)
            ->orderBy('whitelabels.description', 'ASC')
            ->get();
        return $limits;
    }

    /**
     * Find limit by ID
     *
     * @param int $id Page ID
     * @return mixed
     */
    public function find($whitelabel, $currency, $provider)
    {
        $limit = ProviderLimit::select('provider_limit_whitelabel.*')
            ->where('whitelabel_id', $whitelabel)
            ->where('currency_iso', $currency)
            ->where('provider_id', $provider)
            ->orderBy('whitelabels.description', 'ASC')
            ->first();;
        return $limit;
    }

    /**
     * Store limits
     *
     * @param array $data Limit data
     * @return static
     */
    public function store($data)
    {
        $limit = ProviderLimit::create($data);
        return $limit;
    }

    /**
     * Update credentials
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $currency Currency ISO
     * @param int $provider Provider ID
     * @param array $data Limits data
     * @return static
     */
    public function update($whitelabel, $currency, $provider, $data)
    {
        $limits = ProviderLimit::where('whitelabel_id', $whitelabel)
            ->where('currency_iso', $currency)
            ->where('provider_id', $provider)
            ->update($data);
        return $limits;
    }

}

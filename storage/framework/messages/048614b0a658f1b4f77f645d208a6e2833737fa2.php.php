<?php

namespace Dotworkers\Bonus\Repositories;

use Dotworkers\Bonus\Entities\Campaign;
use Dotworkers\Bonus\Enums\AllocationCriteria;
use Dotworkers\Bonus\Enums\RolloverStatus;

/**
 * Class CampaignsRepo
 *
 * This class allows to interact with Campaigns entity
 *
 * @package Dotworkers\Store\Repositories
 * @author  Damelys Espinoza
 */
class CampaignsRepo
{
    /**
     * Find campaign
     *
     * @param int $campaign Campaign ID
     * @return mixed
     */
    public function find($campaign)
    {
        return Campaign::on(config('bonus.connection'))
            ->where('campaigns.id', $campaign)
            ->first();
    }

    /**
     * Find campaign by id
     *
     * @param int $campaign Campaign ID
     * @param bool $status Campaign status
     * @return mixed
     */
    public function findById($campaign, $status)
    {
        return Campaign::on(config('bonus.connection'))
            ->where('campaigns.id', $campaign)
            ->where('campaigns.status', $status)
            ->get();
    }

    /**
     * Find campaign by whitelabel and currency
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $currency Currency ID
     * @param int $criteria Allocation criteria ID
     * @return mixed
     */
    public function findCampaign($whitelabel, $currency, $criteria)
    {
        return Campaign::on(config('bonus.connection'))
            ->where('whitelabel_id', $whitelabel)
            ->where('currency_iso', $currency)
            ->where('allocation_criteria_id', $criteria)
            ->first();
    }

    /**
     * Find campaign by whitelabel, currency and provider type
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $currency Currency ID
     * @param int $providerType Provider type ID
     * @param int $user User ID
     * @return mixed
     */
    public function findCampaignByProviderType($whitelabel, $currency, $providerType, $user, $rolloverStatus = null)
    {
        $campaign = Campaign::on(config('bonus.connection'))
            ->withTrashed()
            ->select('campaigns.*', 'rollover_types.exclude_providers', 'rollovers.total', 'rollovers.target', 'rollovers.rollover_type_id',
                'rollovers.status AS rollover_status')
            ->leftJoin('rollover_types', 'campaigns.id', '=', 'rollover_types.campaign_id')
            ->leftJoin('rollovers', 'campaigns.id', '=', 'rollovers.campaign_id')
            ->where('whitelabel_id', $whitelabel)
            ->where('currency_iso', $currency)
            ->where('provider_type_id', $providerType)
            ->where('rollovers.user_id', $user);

        if (!is_null($rolloverStatus)) {
            $campaign->where('rollovers.status', $rolloverStatus);
        }

        return $campaign->first();
    }

    /**
     * Find campaign by whitelabel and currency
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $currency Currency ID
     * @param int $criteria Allocation criteria ID
     * @param bool $status Campaign status
     * @return mixed
     */
    public function getCampaignName($whitelabel, $currency, $criteria, $status)
    {
        return Campaign::on(config('bonus.connection'))
            ->select('campaigns.name', 'campaigns.id')
            ->where('whitelabel_id', $whitelabel)
            ->where('currency_iso', $currency)
            ->where('allocation_criteria_id', $criteria)
            ->where('status', $status)
            ->get();
    }

    /**
     * Get campaigns by status
     *
     * @param bool $status Campaign status
     * @return mixed
     */
    public function getByStatus($status)
    {
        return Campaign::on(config('bonus.connection'))
            ->where('status', $status)
            ->get();
    }

    /**
     * Update
     *
     * @param int $id Campaign ID
     * @param array $data Tournament data
     * @return mixed
     */
    public function update($id, $data)
    {
        return Campaign::on(config('bonus.connection'))
            ->where('id', $id)
            ->update($data);
    }
}
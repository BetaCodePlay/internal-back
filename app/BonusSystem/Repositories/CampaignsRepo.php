<?php

namespace App\BonusSystem\Repositories;

use App\BonusSystem\Entities\Campaign;
use Illuminate\Support\Facades\DB;

/**
 * Class CampaignsRepo
 *
 * This class allows to manage campaigns entity
 *
 * @package App\BonusSystem\Repositories
 * @author Damelys Espinoza
 */
class CampaignsRepo
{
    /**
     * Get all campaign
     *
     * @return mixed
     */
    public function all()
    {
        return Campaign::select('campaigns.*')
            ->whitelabel()
            ->get();
    }

    /**
     * Get all campaign
     *
     * @return mixed
     */
    public function allByVersion()
    {

        return Campaign::select('campaigns.*')
            // ->whereNotIn('campaigns.id', function ($query) {
            //     $query->select('parent_campaign')->from('campaigns')->whereNotNull('parent_campaign');
            // })
            ->whitelabel()
            ->get();
    }

    /**
     * Delete campaign
     *
     * @param int $id Campaign ID
     * @return mixed
     */
    public function delete($id)
    {
        $campaign = Campaign::find($id)
            ->delete();
        return $campaign;
    }

    /**
     * Find campaign
     *
     * @param int $id Campaign ID
     * @return mixed
     */
    public function find($id)
    {
        $campaign = Campaign::select('campaigns.*')
            ->where('campaigns.id', $id)
            ->whitelabel()
            ->first();
        return $campaign;
    }

    /**
     * Get by id
     *
     * @param int $id Campaign ID
     * @return mixed
     */
    public function getById($id)
    {
        $campaign = Campaign::with('rolloverTypes')
            ->where('campaigns.id', $id)
            ->first();
        return $campaign;
    }

    /**
     * Get by Allocation criteria
     *
     * @param int $whitelabel Whitelabel ID
     * @param int $criteria Allocation criteria ID
     * @param array $campaigns Campaign ID
     * @return mixed
     */
    public function getByAllocationCriteria($whitelabel, $allocationCriteria, $campaigns, $currency)
    {
        $query = "SELECT campaigns.*, rollover_types.provider_type_id
                    FROM campaigns
                    JOIN rollover_types ON rollover_types.campaign_id = campaigns.id
                    WHERE campaigns.deleted_at IS NULL
                    AND whitelabel_id = $whitelabel
                    AND campaigns.id NOT IN (SELECT parent_campaign FROM campaigns WHERE parent_campaign IS NOT NULL) ";

        if ($allocationCriteria != '*' && !empty($allocationCriteria)) {
            $query .= "AND STRING_TO_ARRAY(RIGHT(LEFT(REPLACE((data -> 'allocation_criteria')::text, '\"', ''), -1), -1), ',') @> ARRAY ['$allocationCriteria'] ";
        }

        if ($currency != '*' && !empty($currency)) {
            $query .= "AND campaigns.currency_iso = '$currency' ";
        }

        if ($campaigns != ['*'] && !empty($campaigns)) {
            $query .= "AND campaigns.id IN (" . implode(',', $campaigns) . ")";
        }
        return DB::select($query);
    }

    /**
     * Get all by status
     *
     * @return mixed
     */
    public function getByStatus($currency)
    {
        $campaign = Campaign::select('campaigns.*', 'campaign_allocation_criteria.name as type_name')
            ->join('campaign_allocation_criteria', 'campaign_allocation_criteria.id', '=', 'campaigns.allocation_criteria_id')
            ->where('campaigns.status', true)
            ->whereNull('campaigns.deleted_at')
            ->where('campaigns.currency_iso', $currency)
            ->whitelabel()
            ->get();
        return $campaign;
    }

    /**
     * Get campaigns by status and  allocation criteria
     *
     * @param int $whitelabel Whitelabel ID
     * @param int $allocationCriteria Allocation criteria ID
     * @param string $currency Currency ISO
     * @return mixed
     */
    public function getStatusAndAllocationCriteriaAndCurrency($whitelabel, $allocationCriteria, $currency)
    {
        $query = "SELECT campaigns.*
                    FROM campaigns
                    WHERE campaigns.deleted_at IS NULL
                    AND whitelabel_id = $whitelabel ";

        if ($allocationCriteria != '*' && !empty($allocationCriteria)) {
            $query .= "AND STRING_TO_ARRAY(RIGHT(LEFT(REPLACE((data -> 'allocation_criteria')::text, '\"', ''), -1), -1), ',') @> ARRAY ['$allocationCriteria'] ";
        }

        if ($currency != '*' && !empty($currency)) {
            $query .= "AND campaigns.currency_iso = '$currency'";
        }
        return DB::select($query);
    }

    /**
     * Get versions by campaign
     * @param int $id Campaign ID
     * @return mixed
     */
    public function getVersions($id)
    {
        return \DB::connection('pgsql')
            ->select("WITH RECURSIVE all_versions AS (
                SELECT campaigns.id, campaigns.version
                FROM campaigns
                where campaigns.id = ?

                UNION ALL

                SELECT campaigns.id, campaigns.version
                FROM campaigns
                         JOIN all_versions ON campaigns.parent_campaign = all_versions.id
            )
            SELECT *
            FROM all_versions", [$id]);
    }

    /**
     * Get version duplicate
     *
     * @param int $id Campaign ID
     * @param int $version Campaign version
     * @return mixed
     */
    public function getVersionDuplicate($id, $version)
    {
        $campaign = Campaign::select('campaigns.*')
            ->where('campaigns.original_campaign', $id)
            ->where('campaigns.version', $version)
            ->whitelabel()
            ->first();
        return $campaign;
    }

    /**
     * Get versions by campaign
     * @param int $id Campaign ID
     * @return mixed
     */
    public function getMaxByOriginalCampaign($id)
    {
        $campaign = Campaign::select('campaigns.id')
            ->where('campaigns.original_campaign', $id)
            ->orderBy('campaigns.id', 'desc')
            ->first();
        return $campaign;
    }

    /**
     * Get with promo codes
     *
     * @param int $whitelabel Whitelabel ID
     * @param null|int $id Campaign ID
     * @param null|int $parent Parent campaign ID
     * @return mixed
     */
    public function getWithPromoCodes($whitelabel, $id = null, $parent = null)
    {
        $campaigns = Campaign::where('whitelabel_id', $whitelabel)
            ->where('data->promo_codes', '!=', [])
            ->where('status', true);

        if (!is_null($id)) {
            $campaigns->where('id', '!=', $id)
                ->where('id', '!=', $parent);
        }

        return $campaigns->get();
    }

    /**
     * Store campaign
     *
     * @param array $data Campaign data
     * @return mixed
     */
    public function store($data)
    {
        return Campaign::create($data);
    }

    /**
     * Update campaign
     *
     * @param int $id Campaign ID
     * @param array $data Campaign data
     * @return mixed
     */
    public function update($id, $data)
    {
        $campaign = Campaign::find($id);
        $campaign->fill($data);
        $campaign->save();
        return $campaign;
    }

    /**
     * Update campaign
     *
     * @param int $id Campaign ID
     * @param array $data Campaign data
     * @return mixed
     */
    public function updateWithoutVersion($id, $data)
    {
        $campaign = Campaign::where('campaigns.parent_campaign', $id)
            ->update($data);
        return $campaign;
    }

    /**
     * Update campaign
     *
     * @param int $id Campaign ID
     * @param array $data Campaign data
     * @return mixed
     */
    public function updateStatusAndDelete($id, $data)
    {
        $campaign = Campaign::where('campaigns.original_campaign', $id)
            ->update($data);
        return $campaign;
    }

    /**
     * delete original campaign
     *
     * @param int $id Campaign ID
     * @param array $data Campaign data
     * @return mixed
     */
    public function deleteOriginal($id, $data)
    {
        $campaign = Campaign::where('campaigns.id', $id)
            ->update($data);
        return $campaign;
    }
}

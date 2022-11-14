<?php

namespace Dotworkers\Bonus\Repositories;

use Dotworkers\Bonus\Entities\CampaignParticipation;
use Dotworkers\Bonus\Enums\CampaignParticipationStatus;

/**
 * Class CampaignParticipationRepo
 *
 * This class allows interact with CampaignParticipation entity
 *
 * @package Dotworkers\Bonus\Repositories
 * @author  Eborio Linarez
 */
class CampaignParticipationRepo
{
    /**
     * Find next bonus Find the next bonus to claim automatically
     * @param int $user User ID
     * @param string $currency Currency ISO
     * @param array $providerTypes Provider types ID
     * @return mixed
     */
    public function findNextBonusToClaim($user, $currency, $providerTypes)
    {
        return CampaignParticipation::on(config('bonus.connection'))
            ->select('campaigns.id', 'currency_iso', 'start_date', 'allocation_criteria_id', 'provider_type_id')
            ->join('campaigns', 'campaign_participation.campaign_id', '=', 'campaigns.id')
            ->join('rollover_types', 'campaigns.id', '=', 'rollover_types.campaign_id')
            ->where('user_id', $user)
            ->where('currency_iso', $currency)
            ->where('participation_status_id', CampaignParticipationStatus::$assigned)
            ->whereNotIn('provider_type_id', $providerTypes)
            ->orderBy('campaign_participation.created_at', 'DESC')
            ->first();
    }
    /**
     * Get participation by user, currency and status
     *
     * @param int $user User ID
     * @param int $currency Currency ISO
     * @return mixed
     */
    public function getByUserCurrencyAndStatus($user, $currency)
    {
        return CampaignParticipation::on(config('bonus.connection'))
            ->select('campaign_participation.*', 'rollover_types.provider_type_id')
            ->join('campaigns', 'campaign_participation.campaign_id', '=', 'campaigns.id')
            ->join('rollover_types', 'campaigns.id', '=', 'rollover_types.campaign_id')
            ->where('user_id', $user)
            ->where('currency_iso', $currency)
            ->where('participation_status_id', CampaignParticipationStatus::$in_use)
            ->get();
    }

    /**
     * Update
     *
     * @param int $id Campaign ID
     * @param int $user User ID
     * @param array $data Campaign user data
     * @return mixed
     */
    public function update($id, $user, $data)
    {
        return CampaignParticipation::on(config('bonus.connection'))
            ->where('user_id', $user)
            ->where('campaign_id', $id)
            ->update($data);
    }

    /**
     * Update campaign user participation
     *
     * @param int $user User ID
     * @param int $campaign Campaign ID
     * @param array $data Campaign user data
     * @return mixed
     */
    public function upsert($user, $campaign, $data)
    {
        return \DB::connection(config('sessions.connection'))
            ->table('campaign_participation')
            ->updateOrInsert(['user_id' => $user, 'campaign_id' => $campaign],
                $data
            );
    }

    /**
     * Count user by campaign, user and dates
     *
     * @param int $campaign campaign ID
     * @param int $user User ID
     * @param string $dateFrom Start date
     * @param string $dateAt End date
     * @return mixed
     */
    public function userParticipation($campaign, $user, $startDate, $endDate)
    {
        return CampaignParticipation::on(config('bonus.connection'))
            ->where('campaign_id', $campaign)
            ->where('user_id', $user)
            ->where('participation_status_id', CampaignParticipationStatus::$in_use)
            ->whereBetween('updated_at', [$startDate, $endDate])
            ->first();
    }
}
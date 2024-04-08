<?php


namespace App\BonusSystem\Repositories;


use App\BonusSystem\Entities\CampaignParticipation;
use Dotworkers\Bonus\Enums\CampaignParticipationStatus;

/**
 * Class CampaignParticipationRepo
 *
 * This class allows to manage campaign participation entity
 *
 * @package App\BonusSystem\Repositories
 * @author Damelys Espinoza
 */
class CampaignParticipationRepo
{
    /**
     * Get campaigns for user
     *
     * @param int $user User ID
     * @return mixed
     */
    public function getByUser($user)
    {
        $campaigns = CampaignParticipation::select('campaigns.name', 'campaigns.id')
            ->join('campaigns', 'campaign_participation.campaign_id', '=', 'campaigns.id')
            ->where('user_id', $user)
            ->whereIn('participation_status_id', [CampaignParticipationStatus::$assigned, CampaignParticipationStatus::$in_use])
            ->get();
        return $campaigns;
    }
    /**
     * Find participation by campaign and user
     *
     * @param int $campaign Campaign ID
     * @param int $user User ID
     * @return mixed
     */
    public function findByCampaignAndUser($campaign, $user)
    {
        return CampaignParticipation::where('campaign_id', $campaign)
            ->where('user_id', $user)
            ->first();
    }

    /**
     * Find participation by campaign and user
     *
     * @param int $user User ID
     * @param string $currency Currency ISO
     * @param int $providerType Provider type ID
     * @return mixed
     */
    public function findByUserAndProviderType($user, $currency, $providerType)
    {
        return CampaignParticipation::select('participation_status_id')
            ->join('campaigns', 'campaign_participation.campaign_id', '=', 'campaigns.id')
            ->join('rollover_types', 'campaigns.id', '=', 'rollover_types.campaign_id')
            ->where('user_id', $user)
            ->where('currency_iso', $currency)
            ->where('provider_type_id', $providerType)
            ->whereIn('participation_status_id', [CampaignParticipationStatus::$assigned, CampaignParticipationStatus::$in_use])
            ->first();
    }

    /**
     * @param int $campaign Campaign ID
     * @param array $status Status participation ID
     * @return mixed
     */
    public function getByCampaignAndStatus(int $campaign, array $status)
    {
        return CampaignParticipation::select('campaign_id', 'user_id')
            ->whereIn('participation_status_id', $status)
            ->where('campaign_id', $campaign)
            ->groupBy('campaign_id', 'user_id')
            ->get();
    }

    /**
     * Get users by campaign
     *
     * @param int $campaign Campaign ID
     * @return mixed
     */
    public function getByCampaign($campaign)
    {
        return CampaignParticipation::select('campaign_participation.user_id')
            ->where('campaign_participation.campaign_id', $campaign)
            ->where('campaign_participation.participation_status_id', CampaignParticipationStatus::$in_use)
            ->get();
    }

    /**
     * Get participation by user, currency and status
     *
     * @param int $user User ID
     * @param int $currency Currency ISO
     * @param int $status Campaign participation status
     * @return mixed
     */
    public function getByUserCurrencyAndStatus($user, $currency, $status)
    {
        return CampaignParticipation::select('campaign_participation.*', 'rollover_types.provider_type_id')
            ->join('campaigns', 'campaign_participation.campaign_id', '=', 'campaigns.id')
            ->join('rollover_types', 'campaigns.id', '=', 'rollover_types.campaign_id')
            ->where('user_id', $user)
            ->where('currency_iso', $currency)
            ->where('participation_status_id', $status)
            ->get();
    }

    /**
     * Get total participation
     *
     * @param array $campaign Campaign ID
     * @param string $startDate Start date
     * @param string $endDate End date
     * @return mixed
     */
    public function getTotalParticipation($campaign, $startDate, $endDate)
    {
        $participation = CampaignParticipation::whereIn('campaign_participation.campaign_id', $campaign);

        if (!is_null($startDate) && !is_null($endDate)) {
            $participation->whereBetween('created_at', [$startDate, $endDate]);
        }

        return $participation->count();
    }

    /**
     * Get total participation
     *
     * @param int $campaign Campaign ID
     * @param array $status Status
     * @param string $startDate Start date
     * @param string $endDate End date
     * @return mixed
     */
    public function getTotalParticipationAdStatus($campaign, $status, $startDate, $endDate)
    {
        $users = CampaignParticipation::whereIn('campaign_participation.campaign_id', $campaign)
            ->whereIn('campaign_participation.participation_status_id', $status);

        if (!is_null($startDate) && !is_null($endDate)) {
            $users->whereBetween('created_at', [$startDate, $endDate]);
        }

        return $users->count();
    }

    /**
     * Find next bonus Find the next bonus to claim automatically
     *
     * @param int $user User ID
     * @param string $currency Currency ISO
     * @param array $providerTypes Provider types ID
     * @return mixed
     */
    public function findNextBonusToClaim($user, $currency, $providerTypes)
    {
        return CampaignParticipation::select('campaigns.id', 'currency_iso', 'start_date', 'allocation_criteria_id', 'provider_type_id')
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
     * Store campaign participation
     *
     * @param array $data Campaign user data
     * @return mixed
     */
    public function store($data)
    {
        return CampaignParticipation::create($data);
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
        return CampaignParticipation::where('user_id', $user)
            ->where('campaign_id', $id)
            ->update($data);
    }
}

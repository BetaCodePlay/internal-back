<?php


namespace App\BonusSystem\Repositories;


use App\BonusSystem\Entities\Campaign;
use App\BonusSystem\Entities\CampaignParticipationDetails;
use Dotworkers\Bonus\Enums\CampaignParticipationStatus;

/**
 * Class CampaignParticipationDetails
 *
 * This class allows to manage campaign participation details entity
 *
 * @package App\BonusSystem\Repositories
 * @author Mayinis Torrealba
 */
class CampaignParticipationDetailsRepo
{
    /**
     * Get campaign participation details by campaign and status
     *
     * @param int $campaign Campaign ID
     * @param array $status Status participation ID
     * @param string|null $startDate Start date
     * @param string|null $endDate End date
     * @return mixed
     */
    public function getByCampaignAndStatus(int $campaign, array $status)
    {
        return CampaignParticipationDetails::select('campaign_id')
            ->whereIn('participation_status_id', $status)
            ->where('campaign_id', $campaign)
            ->groupBy('campaign_id')
            ->get();
    }

    /**
     * Get campaign participation details by campaign, status and user
     *
     * @param int $campaign Campaign ID
     * @param array $status Status participation ID
     * @param int $user User ID
     * @return mixed
     */
    public function getByCampaignStatusAndUser(int $campaign, array $status, int $user)
    {
        return CampaignParticipationDetails::select('campaign_id')
            ->whereIn('participation_status_id', $status)
            ->where('campaign_id', $campaign)
            ->where('user_id', $user)
            ->groupBy('campaign_id')
            ->get();
    }

    /**
     *  Get by Campaign Participation
     *
     * @param string $startDate Start date to filter
     * @param string $endDate End date to filter
     * @param int $whitelabel Whitelabel ID
     * @param int $criteria Allocation criteria ID
     * @param string $currency Currency ISO
     * @param bool $status Status ID
     * @param int $campaignID Campaign ID
     * @return mixed
     */
    public function getCampaignParticipation($startDate, $endDate, $whitelabel, $criteria, $currency, $status, $campaignID)
    {
        $campaign = Campaign::select('campaigns.name', 'campaigns.data', 'start_date', 'end_date', 'campaigns.status',
            'campaigns.currency_iso', 'campaigns.whitelabel_id', 'username', 'rollover_types.provider_type_id', 'campaign_participation.*',
            'campaigns.allocation_criteria_id')
            ->join('campaign_participation', 'campaign_participation.campaign_id', '=', 'campaigns.id')
            ->join('users', 'users.id', '=', 'campaign_participation.user_id')
            ->join('rollover_types', 'rollover_types.campaign_id', '=', 'campaigns.id')
            ->where('campaigns.whitelabel_id', $whitelabel)
            ->whereBetween('campaign_participation.created_at', [$startDate, $endDate]);

        if ($criteria != '*' && !empty($criteria)) {
            $campaign->where('allocation_criteria_id', $criteria);
        }
        if ($campaignID != '*' && !is_null($campaignID)) {
            $campaign->whereIn('campaigns.id', explode(',', $campaignID));
        }
        if (!is_null($currency)) {
            $campaign->where('campaigns.currency_iso', $currency);
        }
        if ($status != '*' && !is_null($status)) {
            $campaign->where('campaign_participation.participation_status_id', $status);
        }
        $data = $campaign->get();
        return $data;
    }
}

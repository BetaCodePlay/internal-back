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
     * @param array $campaign Campaign ID
     * @param array $status Status participation ID
     * @return mixed
     */
    public function getByCampaignAndStatus(array $campaign, array $status)
    {
        return CampaignParticipationDetails::select('campaign_id')
            ->whereIn('participation_status_id', $status)
            ->whereIn('campaign_id', $campaign)
            ->groupBy('campaign_id')
            ->get();
    }

    /**
     * Get campaign participation details by campaign, status and user
     *
     * @param array $campaigns Campaign IDs
     * @param array $status Status participation ID
     * @param int $user User ID
     * @return mixed
     */
    public function getByCampaignStatusAndUser(array $campaigns, array $status, int $user)
    {
        return CampaignParticipationDetails::select('campaign_id')
            ->whereIn('participation_status_id', $status)
            ->whereIn('campaign_id', $campaigns)
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

            $campaign = Campaign::select('campaigns.name', 'campaigns.data', 'campaigns.status',
            'campaigns.currency_iso', 'campaigns.whitelabel_id', 'campaign_participation.*',
            'campaigns.allocation_criteria_id', 'campaigns.original_campaign', 'start_date')
            ->join('campaign_participation', 'campaign_participation.campaign_id', '=', 'campaigns.id')
            ->join('users', 'users.id', '=', 'campaign_participation.user_id')
            ->where('campaigns.whitelabel_id', $whitelabel)
            ->whereBetween('campaign_participation.created_at', [$startDate, $endDate])
            ->when($campaignID != ['*'] && !is_null($campaignID[0]), function ($query) use ($campaignID) {
                return $query->whereIn('campaigns.id', $campaignID);
            })
            ->when(!is_null($currency), function ($query) use ($currency) {
                return $query->where('campaigns.currency_iso', $currency);
            })
            ->when($status !== '*'  && !is_null($status), function ($query) use ($status) {
                return $query->where('campaign_participation.participation_status_id', $status);
            })
            ->get();
//         $campaign = Campaign::select('campaigns.name', 'campaigns.data', 'campaigns.status',
//             'campaigns.currency_iso', 'campaigns.whitelabel_id', 'campaign_participation.*',
//             'campaigns.allocation_criteria_id', 'campaigns.original_campaign')
//             ->join('campaign_participation', 'campaign_participation.campaign_id', '=', 'campaigns.id')
//             ->join('users', 'users.id', '=', 'campaign_participation.user_id')
//             // ->join('rollover_types', 'rollover_types.campaign_id', '=', 'campaigns.id')
//             ->where('campaigns.whitelabel_id', $whitelabel)
//             ->whereBetween('campaign_participation.created_at', [$startDate, $endDate]);

// //        if ($criteria != '*' && !empty($criteria)) {
// //            $campaign->whereIn('campaigns.data->allocation_criteria', $criteria);
// //        }
//         if ($campaignID != ['*'] && !is_null($campaignID)) {
//             $campaign->whereIn('campaigns.id', $campaignID);
//         }
//         if (!is_null($currency)) {
//             $campaign->where('campaigns.currency_iso', $currency);
//         }
//         if ($status != '*' && !is_null($status)) {
//             $campaign->where('campaign_participation.participation_status_id', $status);
//         }

         return $campaign;
    }

    /**
     * Store campaign participation
     *
     * @param array $data Campaign user data
     * @return mixed
     */
    public function store(array $data)
    {
        return CampaignParticipationDetails::create($data);
    }
}

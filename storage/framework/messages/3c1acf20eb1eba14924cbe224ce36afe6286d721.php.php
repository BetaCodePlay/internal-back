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
     * Get total participation
     *
     * @param int $campaign Campaign ID
     * @param string $startDate Start date
     * @param string $endDate End date
     * @return mixed
     */
    public function getTotalParticipation($campaign, $startDate, $endDate)
    {
        $participation = CampaignParticipation::where('campaign_participation.campaign_id', $campaign);

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
        $users = CampaignParticipation::where('campaign_participation.campaign_id', $campaign)
            ->whereIn('campaign_participation.participation_status_id', $status);

        if (!is_null($startDate) && !is_null($endDate)) {
            $users->whereBetween('created_at', [$startDate, $endDate]);
        }

        return $users->count();
    }
}

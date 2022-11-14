<?php


namespace Dotworkers\Bonus\Repositories;

use Dotworkers\Bonus\Entities\CampaignTotalBet;

/**
 * Class CampaignsRepo
 *
 * This class allows to interact with Campaign total bet entity
 *
 * @package Dotworkers\Store\Repositories
 * @author  Damelys Espinoza
 */
class CampaignTotalBetsRepo
{
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
        $campaignUser = CampaignTotalBet::on(config('bonus.connection'))
            ->where('campaign_id', $campaign)
            ->where('user_id', $user)
            ->whereBetween('updated_at', [$startDate, $endDate])
            ->first();
        return $campaignUser;
    }

    /**
     * User participation by campaign
     *
     * @param int $campaign campaign ID
     * @param int $user User ID
     * @return mixed
     */
    public function userParticipationByCampaign($campaign, $user)
    {
        $campaignUser = CampaignTotalBet::on(config('bonus.connection'))
            ->where('campaign_id', $campaign)
            ->where('user_id', $user)
            ->first();
        return $campaignUser;
    }

    /**
     * Update campaign total  participation
     *
     * @param int $user User ID
     * @param int $campaign Campaign ID
     * @param array $data Campaign user data
     * @return mixed
     */
    public function update($user, $campaign, $data)
    {
        return \DB::connection(config('sessions.connection'))
            ->table('campaign_total_bets')
            ->updateOrInsert(['user_id' => $user, 'campaign_id' => $campaign],
                $data
            );
    }
}
<?php


namespace App\BonusSystem\Repositories;

use App\BonusSystem\Entities\CampaignUser;


/**
 * Class CampaignsUsersRepo
 *
 * This class allows interact with CampaignUser entity
 *
 * @package App\BonusSystem\Repositories
 * @author  Damelys Espinoza
 */
class CampaignsUsersRepo
{
    /**
     * Store campaign user participation
     *
     * @param array $data Campaign user data
     * @return mixed
     */
    public function store($data)
    {
        return CampaignUser::create($data);
    }

    /**
     * Get users by campaign
     *
     * @param int $campaign Campaign ID
     * @return mixed
     */
    public function getByCampaign($campaign)
    {
        $campaigns = CampaignUser::select('campaign_user.user_id')
            ->where('campaign_user.campaign_id', $campaign)
            ->where('campaign_user.status', true)
            ->get();
        return $campaigns;
    }

    /**
     * Get users by campaign and user
     *
     * @param int $campaign Campaign ID
     * @param int $user User ID
     * @return mixed
     */
    public function getByCampaignAndUser($campaign, $user)
    {
        $campaigns = CampaignUser::select('campaign_user.user_id')
            ->where('campaign_user.campaign_id', $campaign)
            ->where('campaign_user.user_id', $user)
            ->first();
        return $campaigns;
    }

    /**
     * Get campaigns for user
     *
     * @param int $user User ID
     * @return mixed
     */
    public function getByUser($user)
    {
        $campaigns = CampaignUser::select('campaigns.name', 'campaigns.id')
            ->join('campaigns', 'campaign_user.campaign_id', '=', 'campaigns.id')
            ->where('campaign_user.user_id', $user)
            ->where('campaign_user.status', true)
            ->get();
        return $campaigns;
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
        $campaigns = CampaignUser::where('campaign_user.user_id', $user)
            ->where('campaign_user.campaign_id', $id)
            ->update($data);
        return $campaigns;
    }
}

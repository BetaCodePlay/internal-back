<?php


namespace App\BonusSystem\Repositories;

use App\BonusSystem\Entities\Rollover;

/**
 * Class RolloversRepo
 *
 * This class allows managing rollover entity
 *
 * @package App\BonusSystem\Repositories
 * @author Damelys Espinoza
 * @author Eborio Linárez
 */
class RolloversRepo
{
    /**
     * Get by campaign
     *
     * @param int $campaign Campaign ID
     * @return mixed
     */
    public function getByCampaign(int $campaign, ?string $startDate, ?string $endDate)
    {
        $rollovers = Rollover::where('campaign_id', $campaign);

        if (!is_null($startDate) && !is_null($endDate)) {
            $rollovers->whereBetween('created_at', [$startDate, $endDate]);
        }
        return $rollovers->get();
    }

    /**
     * Get by campaign
     *
     * @param int $campaign Campaign ID
     * @param int $user User ID
     * @return mixed
     */
    public function getByCampaignAndUser(int $campaign, int $user)
    {
        return Rollover::where('campaign_id', $campaign)
            ->where('user_id', $user)
            ->get();
    }

    /**
     * Chance status
     *
     * @param int $campaign Campaign ID
     * @param int $user User ID
     * @param array $data Rollover data
     * @return mixed
     */
    public function update($campaign, $user, $data)
    {
        return Rollover::where('campaign_id', $campaign)
            ->where('user_id', $user)
            ->update($data);
    }
}

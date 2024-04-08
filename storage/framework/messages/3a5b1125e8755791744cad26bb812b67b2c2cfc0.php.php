<?php


namespace App\BonusSystem\Repositories;

use App\BonusSystem\Entities\CampaignParticipationStatus;

/**
 * Class CampaignParticipationStatusRepo
 *
 * This class allows manging campaign participation status entity
 *
 * @package App\BonusSystem\Repositories
 * @author Mayinis Torrealba
 */
class CampaignParticipationStatusRepo
{
    /**
     * Get all status
     *
     * @return mixed
     */
    public function all()
    {
        return CampaignParticipationStatus::select('campaign_participation_status.*')
            ->get();
    }
}

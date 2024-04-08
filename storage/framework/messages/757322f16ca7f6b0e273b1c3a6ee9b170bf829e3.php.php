<?php

namespace Dotworkers\Bonus\Repositories;

use Dotworkers\Bonus\Entities\CampaignParticipationDetail;

/**
 * Class CampaignParticipationRepo
 *
 * This class allows interact with CampaignParticipationDetail entity
 *
 * @package Dotworkers\Bonus\Repositories
 * @author  Eborio Linarez
 */
class CampaignParticipationDetailsRepo
{
    /**
     * Store campaign participation
     *
     * @param array $data Campaign user data
     * @return mixed
     */
    public function store($data)
    {
        $participation = CampaignParticipationDetail::on(config('bonus.connection'))
            ->where('campaign_id', $data['campaign_id'])
            ->where('user_id', $data['user_id'])
            ->where('participation_status_id', $data['participation_status_id'])
            ->first();

        if (is_null($participation)) {
            $participation = CampaignParticipationDetail::on(config('bonus.connection'))
                ->create($data);
        }
        return $participation;
    }
}
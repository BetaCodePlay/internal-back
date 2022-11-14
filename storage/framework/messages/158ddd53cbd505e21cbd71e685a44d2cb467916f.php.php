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
        return CampaignParticipationDetail::on(config('bonus.connection'))
            ->create($data);
    }
}
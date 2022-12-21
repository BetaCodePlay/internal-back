<?php

namespace Dotworkers\Bonus\Repositories;

use Dotworkers\Bonus\Entities\Rollover;

/**
 * Class RolloversRepo
 *
 * This class allows to interact with Rollovers entity
 *
 * @package Dotworkers\Store\Repositories
 * @author  Damelys Espinoza
 */
class RolloversRepo
{
    /**
     * Find rollovers by provider type ID
     *
     * @param int $campaign Campaign ID
     * @param int $rolloverType Rollover type ID
     * @param int $user User ID
     * @return mixed
     */
    public function find($campaign, $rolloverType, $user)
    {
        return Rollover::on(config('bonus.connection'))
            ->select('rollovers.*')
            ->where('rollover_type_id', $rolloverType)
            ->where('user_id', $user)
            ->where('campaign_id', $campaign)
            ->first();
    }

    /**
     * Get rollovers types by campaign
     *
     * @param int $campaign Campaign ID
     * @return mixed
     */
    public function getTypes($campaign)
    {
        return \DB::connection(config('sessions.connection'))
            ->table('rollover_types')
            ->where('campaign_id', $campaign)
            ->get();
    }

    /**
     * Store
     *
     * @param array $data Rollover data
     * @return Rollover
     */
    public function store($data)
    {
        return \DB::connection(config('sessions.connection'))
            ->table('rollovers')
            ->insert($data);
    }

    /**
     * Update rollovers
     *
     * @param int $campaign Campaign ID
     * @param int $user User ID
     * @param int $type Rollover type ID
     * @param array $data
     * @return mixed
     */
    public function update($campaign, $user, $type, $data)
    {
        return Rollover::on(config('bonus.connection'))
            ->where('campaign_id', $campaign)
            ->where('user_id', $user)
            ->where('rollover_type_id', $type)
            ->update($data);
    }
}
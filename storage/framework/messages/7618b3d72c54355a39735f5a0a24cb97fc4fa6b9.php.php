<?php


namespace App\BonusSystem\Repositories;

use App\BonusSystem\Entities\RolloverType;

/**
 * Class RolloversTypesRepo
 *
 *  This class allows to manage rollover type entity
 *
 * @package App\BonusSystem\Repositories
 * @author Damelys Espinoza
 */
class RolloversTypesRepo
{
    /**
     * Find rollover type
     *
     * @param int $id Rollover tyoe ID
     * @return mixed
     */
    public function find($id)
    {
        return RolloverType::where('id', $id)
            ->first();
    }
    /**
     * Get by campaign
     *
     * @param int $campaign Campaign ID
     * @return mixed
     */
    public function getByCampaign($campaign)
    {
        return RolloverType::where('campaign_id', $campaign)
            ->first();
    }

    /**
     * Store rollover type
     *
     * @param array $data Rollover type data
     * @return mixed
     */
    public function store($data)
    {
        return RolloverType::create($data);
    }

    /**
     * Update rollover type
     *
     * @param int $id Rollover type ID
     * @param array $data Rollover type data
     * @return mixed
     */
    public function update($id, $data)
    {
        $rollover = RolloverType::find($id);
        $rollover->fill($data);
        $rollover->save();
        return $rollover;
    }
}

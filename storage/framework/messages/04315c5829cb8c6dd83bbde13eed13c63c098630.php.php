<?php

namespace App\Store\Repositories;


use App\Store\Entities\StoreReward;

class StoreRewardsRepo
{
    /**
     * Delete reward
     *
     * @param $id
     * @return mixed
     */
    public function deleteReward($id)
    {
        $rewards = StoreReward::find($id)
            ->whitelabel()
            ->first();
        $rewards->delete();
        return $rewards;
    }

    /**
     * Get reward
     *
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        $reward = StoreReward::select('store_rewards.*', 'rewards_categories.name as category_name', 'rewards_categories.id as category')
            ->leftJoin('rewards_categories', 'store_rewards.category_id', '=', 'rewards_categories.id')
            ->where('store_rewards.id', $id)
            ->first();
        return $reward;
    }

    /**
     * Get all rewards
     *
     * @param $currency
     * @param $whitelabel
     * @return mixed
     */
    public function getAllRewards($whitelabel)
    {
        $rewards =  StoreReward::select('store_rewards.*', 'rewards_categories.name as category_name', 'rewards_categories.id as category')
            ->leftJoin('rewards_categories', 'store_rewards.category_id', '=', 'rewards_categories.id')
            ->where('store_rewards.whitelabel_id', $whitelabel)
            ->get();
        return $rewards;
    }

    /**
     * Get all rewards by status
     *
     * @param $currency
     * @param $whitelabel
     * @return mixed
     */
    public function getAllRewardsByStatus($currency, $whitelabel)
    {
        $rewards =  StoreReward::select('store_rewards.*', 'rewards_categories.name as category_name', 'rewards_categories.id as category')
            ->leftJoin('rewards_categories', 'store_rewards.category_id', '=', 'rewards_categories.id')
            ->where('store_rewards.status', true)
            ->where('store_rewards.currency_iso', $currency)
            ->where('store_rewards.whitelabel_id', $whitelabel)
            ->get();
        return $rewards;
    }

    /**
     * Get reward by name
     *
     * @param $name
     * @param $currency
     * @param $whitelabel
     * @return mixed
     */
    public function getRewardByName($name, $currency, $whitelabel)
    {
        $reward = StoreReward::select('store_rewards.*', 'rewards_categories.name as category_name', 'rewards_categories.id as category')
            ->leftJoin('rewards_categories', 'store_rewards.category_id', '=', 'rewards_categories.id')
            ->where('store_rewards.name', $name)
            ->where('store_rewards.currency_iso', $currency)
            ->where('store_rewards.whitelabel_id', $whitelabel)
            ->first();
        return $reward;
    }

    /**
     * Store reward
     *
     * @param $reward
     * @return StoreReward
     */
    public function store($reward)
    {
        $rewards = new StoreReward();
        $rewards->fill($reward);
        $rewards->save();
        return $rewards;
    }

    /**
     * Update reward
     *
     * @param $id
     * @param $data
     * @return mixed
     */
    public function update($id, $data)
    {
        $reward = StoreReward::find($id);
        $reward->fill($data);
        $reward->save();
        return $reward;
    }
}

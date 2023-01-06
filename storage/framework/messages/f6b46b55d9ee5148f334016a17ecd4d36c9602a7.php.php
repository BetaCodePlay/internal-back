<?php


namespace App\Store\Repositories;

use App\Store\Entities\RewardsCategories;

/**
 * Class RewardsCategoriesRepo
 *
 * This class allows to interact with Rewards_categories entity
 *
 * @package App\Store\Repositories
 * @author  Orlando Bravo
 */
class RewardsCategoriesRepo
{
    /**
     * Get all rewards categories
     *
     * @param int $whitelabel Whitelabel ID
     * @return mixed
     */
    public function all($whitelabel)
    {
        $rewards = RewardsCategories::select('*')
            ->where('whitelabel_id', $whitelabel)
            ->orderBy('rewards_categories.name', 'ASC')
            ->get();
        return $rewards;
    }

    /**
     * Delete  categories
     *
     * @param $id
     * @return mixed
     */
    public function deleteCategory($id)
    {
        $category = RewardsCategories::where('id', $id)
            ->delete();
        return $category;
    }

    /**
     * Get rewards categories
     *
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        $reward = RewardsCategories::where('id', $id)
            ->whitelabel()
            ->first();
        return $reward;
    }

    /**
     * Store rewards categories
     *
     * @param $reward
     * @return RewardsCategories
     */
    public function store($reward)
    {
        $rewards = new RewardsCategories();
        $rewards->fill($reward);
        $rewards->save();
        return $rewards;
    }

    /**
     * Update rewards categories
     *
     * @param $id
     * @param $data
     * @return mixed
     */
    public function update($id, $data)
    {
        $reward = RewardsCategories::find($id);
        $reward->fill($data);
        $reward->save();
        return $reward;
    }
}

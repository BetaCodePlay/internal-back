<?php


namespace App\Store\Entities;


use Dotworkers\Configurations\Configurations;
use Illuminate\Database\Eloquent\Model;

/**
 * Class RewardsCategories
 *
 * This class allows to interact with rewards_categories table
 *
 * @package App\Store\Entities
 * @author  Orlando Bravo
 */
class RewardsCategories extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'rewards_categories';

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = ['name', 'whitelabel_id'];


    /**
     * Scope whitelabel
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return mixed
     */
    public function scopeWhitelabel($query)
    {
        return $query->where('whitelabel_id', Configurations::getWhitelabel());
    }
}

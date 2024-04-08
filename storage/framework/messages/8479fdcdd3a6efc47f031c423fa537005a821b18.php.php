<?php


namespace App\BonusSystem\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AllocationCriteriaMet
 *
 * This class define allocation_criteria_met table properties
 *
 * @package App\BonusSystem\Entities
 * @author Damelys Espinoza
 */
class AllocationCriteriaMet extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'campaign_allocation_criteria_met';

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = ['campaign_id', 'user_id', 'created_at', 'updated_at'];

    /**
     * Incrementing
     *
     * @var bool
     */
    public $incrementing = false;
}

<?php

namespace App\BonusSystem\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AllocationCriteria
 *
 * This class define allocation_criteria table properties
 *
 * @package App\BonusSystem\Entities
 * @author Damelys Espinoza
 */
class AllocationCriteria extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'campaign_allocation_criteria';

    /**
     * Timestamps
     *
     * @var bool
     */
    public $timestamps = false;
}

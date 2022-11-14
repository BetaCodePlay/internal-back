<?php


namespace App\BonusSystem\Entities;


use Illuminate\Database\Eloquent\Model;

/**
 * Class Rollover
 *
 * This class define rollovers table properties
 *
 * @package App\BonusSystem\Entities
 * @author Damelys Espinoza
 */
class Rollover extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'rollovers';

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = ['campaign_id', 'user_id', 'status', 'total', 'deposit', 'rollover_type_id', 'created_at', 'updated_at', 'target', 'bonus', 'converted'];
}

<?php


namespace App\Store\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ActionType
 *
 * This class allows to interact with actions_types table
 *
 * @package App\Store\Entities
 * @author  Damelys Espinoza
 *
 */
class ActionType extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'actions_types';

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = ['name'];
}
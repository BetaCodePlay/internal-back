<?php


namespace App\Store\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Action
 *
 * This class allows to interact with actions table
 *
 * @package App\Store\Entities
 * @author  Damelys Espinoza
 */
class Action extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'actions';

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = ['name', 'action_type_id'];

    /**
     * Timestamps
     *
     * @var bool
     */
    public $timestamps = false;
}
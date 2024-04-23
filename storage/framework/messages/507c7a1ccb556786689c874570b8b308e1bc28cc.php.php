<?php

namespace App\DotSuite\Entities;

use Dotworkers\Configurations\Configurations;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DotSuiteGame
 *
 *  This class allows to interact with dotsuite_games table
 *
 * @package App\DotSuite\Entities
 * @author  Carlos Hurtado
 */
class DotSuiteGame extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'dotsuite_games';

    /**
     * Primary key
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'slug', 'image', 'maker', 'provider_game_id', 'status', 'category', 'provider_id', 'types', 'games', 'created_at', 'updated_at'];
}

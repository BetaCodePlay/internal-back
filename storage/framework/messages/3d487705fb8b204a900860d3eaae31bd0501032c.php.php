<?php

namespace App\WhitelabelsGames\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class WhitelabesGames
 *
 * Class to define the WhitelabesGames properties
 *
 * @package App\Core\Entities
 * @author  Carlos Hurtado
 */
class WhitelabelsGames extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'whitelabel_games';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Primary key
     *
     * @var array
     */
    protected $fillable = ['whitelabel_id', 'game_id', 'whitelabel_game_category_id'];

    /**
     * Timestamps
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];
}

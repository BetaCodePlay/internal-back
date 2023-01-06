<?php


namespace App\DotSuite\Entities;
use Illuminate\Database\Eloquent\Model;

/**
 * Class LobbyDotSuiteGames
 *
 * Class to define the LobbyDotSuiteGames properties
 *
 * @package App\Core\Entities
 * @author  Genesis Perez
 */
class LobbyDotSuiteGames extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'lobby_dotsuite_whitelabels';

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
    protected $fillable = ['dotsuite_game_id', 'whitelabel_id', 'data', 'order', 'route', 'image', 'name'];

    /**
     * Cast fields
     *
     * @var array
     */
    public $casts = [
        'data' => 'array'
    ];


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

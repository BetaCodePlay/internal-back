<?php

namespace App\Core\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Lobby-games
 *
 * Class to define the Lobby-games properties
 *
 * @package App\Core\Entities
 * @author  Genesis Perez
 */
class LobbyGames extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'lobby_games';

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
    protected $fillable = ['game_id', 'whitelabel_id', 'data', 'order', 'route', 'image', 'name'];

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


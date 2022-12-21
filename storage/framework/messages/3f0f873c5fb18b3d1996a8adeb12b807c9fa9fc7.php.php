<?php

namespace App\Core\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Lobby-games
 *
 * Class to define the Lobby-games properties
 *
 * @package App\Core\Entities
 * @author  Derluin Gonzalez
 */
class LobbyGames extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'custom_lobby_games';

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
    protected $fillable = ['game_id', 'whitelabel_id'];

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


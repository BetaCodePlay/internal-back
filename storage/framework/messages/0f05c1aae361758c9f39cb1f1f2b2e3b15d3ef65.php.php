<?php

namespace App\WhitelabelsGames\Entities;

use Illuminate\Database\Eloquent\Model;

class WhitelabelsGamesCategories extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'whitelabel_games_categories';

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
    protected $fillable = ['id', 'name', 'translations'];

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

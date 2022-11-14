<?php

namespace App\Reports\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ClosureUserTotal
 *
 * This class define closures_users_totals table properties
 *
 * @package App\Reports\Entities
 * @author Damelys Espinoza
 */
class ClosureUserTotal extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'closures_users_totals';

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = ['user_id', 'username', 'played', 'won', 'profit', 'rtp', 'bets', 'start_date', 'end_date', 'game_id', 'currency_iso', 'whitelabel_id', 'provider_id'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['start_date', 'end_date'];
}

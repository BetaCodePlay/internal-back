<?php


namespace App\Reports\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ClosureGameTotal
 *
 * This class define closures_games_totals table properties
 *
 * @package App\Reports\Entities
 * @author Damelys Espinoza
 */
class ClosureGameTotal extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'closures_games_totals';

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = ['game_id', 'game_name', 'mobile', 'played', 'won', 'profit', 'rtp', 'bets', 'start_date', 'end_date', 'currency_iso', 'whitelabel_id', 'provider_id'];
}

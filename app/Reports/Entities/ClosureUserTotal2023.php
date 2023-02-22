<?php

namespace App\Reports\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ClosureUserTotal2023
 *
 * This class define closures_users_totals_2023 table properties
 *
 * @package App\Reports\Entities
 * @author Estarly Olivar
 */
class ClosureUserTotal2023 extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'closures_users_totals_2023';

    protected $fillable = [
        'user_id',
        'username',
        'played',
        'won',
        'profit',
        'rtp',
        'bets',
        'start_date',
        'end_date',
        'game_id',
        'currency_iso',
        'whitelabel_id',
        'provider_id'
    ];

    protected $dates = ['start_date', 'end_date'];
}

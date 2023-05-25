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
 * @author Jhonattan Bullones
 */
class ClosureUserTotal2023Hour extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'closures_users_totals_2023_hour';
    protected $fillable = [
        'id',
        'user_id',
        'username',
        'played',
        'won',
        'profit',
        'rtp',
        'bets',
        'game_id',
        'currency_iso',
        'whitelabel_id',
        'provider_id',
        'start_date',
        'end_date',
    ];

    protected $dates = ['start_date', 'end_date'];
}

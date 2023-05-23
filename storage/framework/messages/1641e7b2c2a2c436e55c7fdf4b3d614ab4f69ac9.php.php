<?php


namespace Dotworkers\Bonus\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TournamentRanking
 *
 * This class allows to interact with tournament_ranking table
 *
 * @package Dotworkers\Bonus\Entities
 * @author  Damelys Espinoza
 */
class TournamentRanking extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'tournament_ranking';

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = ['id', 'campaign_id', 'user_id', 'whitelabel_id', 'currency_iso', 'provider_id', 'total', 'created_at', 'updated_at'];
}
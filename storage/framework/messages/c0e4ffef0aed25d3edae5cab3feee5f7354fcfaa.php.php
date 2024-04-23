<?php


namespace Dotworkers\Bonus\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CampaignTotalBet
 *
 * This class allows to interact with Campaign total bet entity
 *
 * @package Dotworkers\Bonus\Entities
 * @author  Damelys Espinoza
 */
class CampaignTotalBet extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'campaign_total_bets';

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = ['campaign_id', 'user_id', 'amount'];

    /**
     * Incrementing
     *
     * @var bool
     */
    public $incrementing = false;
}
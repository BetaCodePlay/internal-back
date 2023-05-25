<?php


namespace App\BonusSystem\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CampaignParticipation
 * @package App\BonusSystem\Entities
 */
class CampaignParticipation extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'campaign_participation';

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = ['campaign_id', 'user_id', 'participation_status_id', 'created_at', 'updated_at'];

    /**
     * Incrementing
     *
     * @var bool
     */
    public $incrementing = false;
}

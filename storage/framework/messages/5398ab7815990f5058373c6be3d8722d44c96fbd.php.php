<?php


namespace App\BonusSystem\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CampaignParticipationDetails
 * @package App\BonusSystem\Entities
 */
class CampaignParticipationDetails extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'campaign_participation_details';

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = ['campaign_id', 'user_id', 'participation_status_id'];

    /**
     * Incrementing
     *
     * @var bool
     */
    public $incrementing = false;
}

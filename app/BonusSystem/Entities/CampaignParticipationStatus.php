<?php


namespace App\BonusSystem\Entities;


use Illuminate\Database\Eloquent\Model;

/**
 * Class CampaignParticipation
 *
 * This class define campaign participation table properties
 *
 * @package App\BonusSystem\Entities
 * @author Damelys Espinoza
 */
class CampaignParticipationStatus extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'campaign_participation_status';

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = ['name'];
}

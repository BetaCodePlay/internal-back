<?php

namespace Dotworkers\Bonus\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CampaignUser
 *
 * This class allows interact with campaign_participation table
 *
 * @package Dotworkers\Bonus\Entities
 * @author  Eborio Linarez
 */
class CampaignParticipationDetail extends Model
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
    protected $fillable = ['campaign_id', 'user_id', 'participation_status_id', 'created_at', 'updated_at'];

    /**
     * Incrementing
     *
     * @var bool
     */
    public $incrementing = false;
}
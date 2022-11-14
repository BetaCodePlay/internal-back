<?php


namespace App\BonusSystem\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CampaignUser
 *
 * This class allows interact with campaign_user table
 *
 * @package App\BonusSystem\Entities
 * @author  Damelys Espinoza
 */
class CampaignUser extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'campaign_user';

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = ['campaign_id', 'user_id'];

    /**
     * Incrementing
     *
     * @var bool
     */
    public $incrementing = false;
}

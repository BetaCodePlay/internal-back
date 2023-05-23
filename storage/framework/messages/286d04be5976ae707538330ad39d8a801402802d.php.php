<?php


namespace App\CRM\Entities;


use Dotworkers\Configurations\Configurations;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MarketingCampaign
 *
 * This class allows to interact with marketing campaign table
 *
 * @package App\CRM\Entities
 * @author  Damelys Espinoza
 */
class MarketingCampaign extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'marketing_campaigns';

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = ['title', 'status', 'language', 'currency_iso', 'whitelabel_id', 'segment_id', 'email_template_id', 'scheduled_date'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['scheduled_date'];

    /**
     * Scope whitelabel
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return mixed
     */
    public function scopeWhitelabel($query)
    {
        return $query->where('whitelabel_id', Configurations::getWhitelabel());
    }
}

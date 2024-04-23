<?php


namespace App\Notifications\Entities;

use Dotworkers\Configurations\Configurations;
use Illuminate\Database\Eloquent\Model;

/**
 * Class NotificationGroup
 *
 * Class to define the notifications groups table attributes
 *
 * @package App\Notifications\Entities
 */
class NotificationGroup extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'notifications_groups';

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = ['name', 'description', 'currency_iso', 'whitelabel_id', 'created_at', 'update_at', 'operator_id'];

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

    /**
     * Scope whitelabel and currency
     *
     * @param \Illuminate\Database\Eloquent\Builder  $query
     * @return mixed
     */
    public function scopeWhitelabelCurrency($query)
    {
        return $query->where('whitelabel_id', session('whitelabel'))
            ->where('currency_iso', session('currency'));
    }
}

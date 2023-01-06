<?php

namespace App\Notifications\Entities;

use Dotworkers\Configurations\Configurations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Notification
 *
 * Class to define the notifications table attributes
 *
 * @package App\Notifications\Entities
 */
class Notification extends Model
{

    use SoftDeletes;

    /**
     * Table
     *
     * @var string
     */
    protected $table = 'notifications';

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = ['title', 'content', 'image', 'notification_type_id', 'language', 'currency_iso', 'status', 'whitelabel_id', 'created_at', 'update_at', 'deleted_at', 'operator_id'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

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

    /**
     * Relationship with the Users entity
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany('App\Users\Entities\User', 'notification_user', 'notification_id', 'user_id');
    }

    /**
     * Relationship with the Segment entity
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function segment()
    {
        return $this->belongsToMany('App\CRM\Entities\Segment', 'notification_segment_user', 'notification_id', 'segment_id');
    }
}

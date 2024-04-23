<?php

namespace App\Agents\Entities;

use App\Users\Entities\User;
use Dotworkers\Configurations\Configurations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Agent
 *
 * This class allows to interact with agents table
 *
 * @package App\Agents\Entities
 * @author  Eborio Linarez
 */
class Agent extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'agents';

    /**
     * Fillable
     * @var array
     */
    protected $fillable = ['user_id', 'owner_id', 'master', 'percentage'];

    /**
     * Scope currency
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return mixed
     */
    public function scopeCurrency($query)
    {
        return $query->where('currency_iso', session('currency'));
    }

    /**
     * Scope currency
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return mixed
     */
    public function scopeWhitelabel($query)
    {
        return $query->where('users.whitelabel_id', Configurations::getWhitelabel());
    }

    /**
     * Relationship with User entity
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    /**
     * @return BelongsTo
     */
    public function ownerAgent()
    : BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}

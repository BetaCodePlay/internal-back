<?php

namespace App\Core\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Slider
 *
 * This class allows to interact with transactions table
 *
 * @package App\Core\Entities
 * @author  Eborio Linarez
 */
class Transaction extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'transactions';

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = ['user_id', 'amount', 'currency_iso', 'transaction_type_id', 'transaction_status_id', 'data', 'provider_id', 'whitelabel_id'];

    /**
     * Relationship with TransactionStatus entity
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function details()
    {
        return $this->belongsToMany(TransactionStatus::class, 'transaction_details', 'transaction_id', 'transaction_status_id')->withPivot('data')->withTimestamps();
    }

    /**
     * Get data attribute
     *
     * @param array $data Transaction data
     * @return mixed
     */
    public function getDataAttribute($data)
    {
        return json_decode($data);
    }

    /**
     * Set data attribute
     *
     * @param array $data Transaction data
     */
    public function setDataAttribute($data)
    {
        $this->attributes['data'] = json_encode($data);
    }

    /**
     * Scope conditions
     *
     * @param \Illuminate\Database\Eloquent\Builder  $query
     * @param string $currency User Currency iso
     * @return string
     */
    public function scopeConditions($query, $currency)
    {
        if (!empty($currency)) {
            $query->where('currency_iso', $currency);
        }

        return $query;
    }
}

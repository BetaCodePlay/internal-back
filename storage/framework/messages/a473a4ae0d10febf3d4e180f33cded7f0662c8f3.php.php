<?php

namespace Dotworkers\Bonus\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Transaction
 *
 * This class allows to interact with transactions table
 *
 * @package App\WhitelabelsTransactions\Entities
 * @author  Damelys Espinoza
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
    protected $fillable = ['user_id', 'amount', 'currency_iso', 'transaction_type_id', 'transaction_status_id', 'data', 'provider_id', 'created_at', 'whitelabel_id'];

    /**
     * Cast fields
     *
     * @var array
     */
    protected $casts = [
        'data' => 'array',
    ];

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
     * Relationship with TransactionStatus entity
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function details()
    {
        return $this->belongsToMany(TransactionStatus::class, 'transaction_details', 'transaction_id', 'transaction_status_id')->withPivot('data')->withTimestamps();
    }
}

<?php

namespace App\Core\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Credential
 *
 * This class allows to interact with provider_whitelabel_limit table
 *
 * @package App\Core\Entities
 * @author  Eborio Linarez
 */
class ProviderLimit extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'provider_limit_whitelabel';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = ['whitelabel_id', 'provider_id', 'currency_iso', 'data'];

    /**
     * Cast fields
     *
     * @var array
     */
    public $casts = [
        'data' => 'array'
    ];

    /**
     * Get data attribute
     *
     * @param array $data credential data
     * @return mixed
     */
    public function getDataAttribute($data)
    {
        return json_decode($data);
    }
}

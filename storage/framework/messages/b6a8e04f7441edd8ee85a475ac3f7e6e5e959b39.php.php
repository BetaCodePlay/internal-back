<?php

namespace App\Audits\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Audit
 *
 * Class to define the Audits table attributes
 *
 * @package App\Audits\Entities
 * @author  Gabriel Santiago
 */
class Audit extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'audits';
    /**
     * Primary key
     *
     * @var array
     */
    protected $fillable = ['user_id', 'audit_type_id', 'whitelabel_id', 'data'];

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

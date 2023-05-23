<?php


namespace App\Core\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Credential
 *
 * This class allows to interact with credentials table
 *
 * @package App\Core\Entities
 * @author  Orlando Bravo
 */
class Credential extends Model
{

    /**
     * Table
     *
     * @var string
     */
    protected $table = 'credentials';

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
    protected $fillable = ['client_id', 'provider_id', 'currency_iso', 'data','percentage'];

    /**
     * Cast fields
     *
     * @var array
     */
    public $casts = [
        'data' => 'array'
    ];

    /**
     * Timestamps
     *
     * @var bool
     */
    public $timestamps = true;

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

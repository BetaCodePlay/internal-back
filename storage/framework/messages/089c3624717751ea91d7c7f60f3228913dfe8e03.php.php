<?php


namespace App\Users\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class AutoLockUser
 *
 * This class allows to interact with auto lock users table
 *
 * @package App\Core\Entities
 * @author  Orlando Bravo
 */
class AutoLockUser extends Model
{

    use SoftDeletes;

    /**
     * Table
     *
     * @var string
     */
    public $table = 'auto_lock_users';

    /**
     * Incrementing field
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * Primary key for the table
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Timestamps of the table
     *
     * @var boolean
     */
    public $timestamps = true;

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = ['user_id', 'data', 'currency_iso', 'whitelabel_id', 'start_date', 'end_date', 'active'];

    /**
     * Cast fields
     *
     * @var array
     */
    public $casts = [
        'data' => 'array'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['start_date', 'end_date', 'deleted_at'];

    /**
     * Get data attribute
     *
     * @param array $data AutoLockUser data
     * @return mixed
     */
    public function getDataAttribute($data)
    {
        return json_decode($data);
    }
}

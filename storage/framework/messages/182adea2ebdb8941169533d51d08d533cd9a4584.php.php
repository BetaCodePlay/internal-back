<?php


namespace App\Users\Entities;

use Dotworkers\Configurations\Configurations;
use Illuminate\Database\Eloquent\Model;

/**
 * This class allows to interact with documents table
 *
 * @package App\Users\Entities
 * @author  Damelys Espinoza
 */
class Document extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'documents';

    /**
     * Primary key
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['whitelabel_id', 'currency_iso', 'name', 'translations', 'status', 'created_at', 'update_at'];

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

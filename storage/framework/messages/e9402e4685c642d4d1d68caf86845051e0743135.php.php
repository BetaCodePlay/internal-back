<?php


namespace App\Users\Entities;


use Dotworkers\Configurations\Configurations;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserDocument
 *
 * This class allows to interact with user_documents table
 *
 * @package App\Users\Entities
 * @author  Damelys Espinoza
 */
class UserDocument extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'user_documents';

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
    protected $fillable = ['name', 'status', 'user_id', 'whitelabel_id', 'document_type_id', 'created_at', 'update_at'];

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

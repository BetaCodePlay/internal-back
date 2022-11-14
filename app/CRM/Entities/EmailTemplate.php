<?php


namespace App\CRM\Entities;


use Dotworkers\Configurations\Configurations;
use Illuminate\Database\Eloquent\Model;

/**
 * Class EmailTemplate
 *
 * This class allows to interact with emailTemplates table
 *
 * @package App\CRM\Entities
 * @author  Carlos Hurtado
 */
class EmailTemplate extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'email_templates';

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = ['title', 'metadata', 'content', 'html', 'language', 'currency_iso', 'status', 'whitelabel_id', 'subject'];

    /**
     * Scope whitelabel
     *
     * @param \Illuminate\Database\Eloquent\Builder  $query
     * @return mixed
     */
    public function scopeWhitelabel($query)
    {
        return $query->where('whitelabel_id', Configurations::getWhitelabel());
    }
}

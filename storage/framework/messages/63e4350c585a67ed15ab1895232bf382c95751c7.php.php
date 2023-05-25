<?php

namespace App\SectionModals\Entities;

use Dotworkers\Configurations\Configurations;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SectionModal
 *
 * Class to define the posts table attributes
 *
 * @package App\SectionModals\Entities
 * @author  Eborio Linarez
 */
class SectionModal extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'section_modals';

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = ['image', 'route', 'status', 'one_time', 'scroll', 'language', 'currency_iso', 'whitelabel_id', 'url'];

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

<?php

namespace App\Core\Entities;

use Dotworkers\Configurations\Configurations;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SectionImage
 *
 * This class allows to interact with section_images table
 *
 * @package App\Core\Entities
 * @author  Eborio Linarez
 */
class SectionImage extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'section_images';

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = ['title', 'description', 'button', 'whitelabel_id', 'image', 'url', 'language', 'currency_iso', 'status', 'element_type_id', 'position', 'mobile', 'section', 'front', 'category'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['start_date', 'end_date'];

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

    /**
     * Scope whitelabel and currency
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return mixed
     */
    public function scopeWhitelabelCurrency($query)
    {
        return $query->where('whitelabel_id', Configurations::getWhitelabel())
            ->where('currency_iso', session('currency'));
    }
}

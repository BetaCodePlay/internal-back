<?php

namespace App\Core\Entities;

use Dotworkers\Configurations\Configurations;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Game Sections
 *
 * Class to define the games properties
 *
 * @package App\Core\Entities
 * @author  Genesis Perez
 */
class SectionGame extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'game_section';

       /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = ['game_id', 'additional_info', 'section', 'whitelabel_id'];


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

<?php

namespace App\Posts\Entities;

use Dotworkers\Configurations\Configurations;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Post
 *
 * Class to define the posts table attributes
 *
 * @package App\Posts\Entities
 * @author  Eborio Linarez
 */
class Post extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'posts';

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = ['title', 'slug', 'content', 'image', 'start_date', 'end_date', 'language', 'currency_iso', 'status', 'whitelabel_id', 'main_image', 'post_categories_id'];

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
     * @param \Illuminate\Database\Eloquent\Builder  $query
     * @return mixed
     */
    public function scopeWhitelabelCurrency($query)
    {
        return $query->where('whitelabel_id', session('whitelabel'))
            ->where('currency_iso', session('currency'));
    }
}

<?php

namespace App\Core\Entities;

use Dotworkers\Configurations\Configurations;
use Illuminate\Database\Eloquent\Model;
use Xinax\LaravelGettext\Facades\LaravelGettext;

/**
 * Class Page
 *
 * This class allows to interact with pages table
 *
 * @package App\Core\Entities
 * @author  Eborio Linarez
 */
class Page extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'pages';

    /**
     * Scope whitelabel and currency
     *
     * @param \Illuminate\Database\Eloquent\Builder  $query
     * @return mixed
     */
    public function scopeWhitelabelLanguage($query)
    {
        return $query->where('page_whitelabel.whitelabel_id', Configurations::getWhitelabel())
            ->where('page_whitelabel.language', LaravelGettext::getLocale());
    }
}

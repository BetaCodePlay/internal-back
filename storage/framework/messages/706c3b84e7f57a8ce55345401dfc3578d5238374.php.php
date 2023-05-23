<?php

namespace App\CRM\Entities;

use Dotworkers\Configurations\Configurations;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Expr\Array_;

/**
 * Class Slider
 *
 * This class allows to interact with sliders table
 *
 * @package App\CRM\Entities
 * @author  Eborio Linarez
 */
class Slider extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'sliders';

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = ['whitelabel_id', 'image', 'url','route', 'start_date', 'end_date', 'language', 'currency_iso', 'status', 'order', 'element_type_id', 'mobile', 'section'];

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

    /**
     * Scope conditions
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param null|string $device Device String
     * @param null|string $language Language String
     * @param null|string $currency Currency String
     * @param null|Boolean $status Status Boolean
     * @return string
     */
    public function scopeConditions($query, $device, $language, $currency, $status)
    {
        if (!empty($device)) {
            $query->where('mobile', $device);
        }

        if (!empty($language)) {
            $query->where('language', $language);
        }

        if (!empty($currency)) {
            $query->where('currency_iso', $currency);
        }

        if (!empty($status)) {
            $query->where('status', $status);
        }

        return $query;
    }

    /**
     * Scope conditions route
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param null|string $device Device String
     * @param null|string $language Language String
     * @param null|string $currency Currency String
     * @param null|Boolean $status Status Boolean
     * @param null|string $route Route String
     * @return string
     */
    public function scopeConditionsRoute($query, $device, $language, $currency, $status, $route)
    {
        if (!empty($device)) {
            $query->where('mobile', $device);
        }

        if (!empty($language)) {
            $query->where('language', $language);
        }

        if (!empty($currency)) {
            $query->where('currency_iso', $currency);
        }

        if (!empty($status)) {
            $query->where('status', $status);
        }

        if (!empty($route)) {
            $query->where('route', $route);
        }

        return $query;
    }

    /**
     * Scope multiple
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param null|Array_ $device Device Array
     * @param null|Array_ $language Language Array
     * @param null|Array_ $currency Currency Array
     * @param null|Boolean $status Status Boolean
     * @return string
     */
    public function scopeMultiple($query, $device, $language, $currency, $status)
    {
        if (!empty($device)) {
            $query->whereIn('mobile', $device);
        }

        if (!empty($language)) {
            $query->whereIn('language', $language);
        }

        if (!empty($currency)) {
            $query->whereIn('currency_iso', $currency);
        }

        if (!empty($status)) {
            $query->where('status', $status);
        }

        return $query;
    }

    /**
     * Scope multiple route
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param null|Array_ $device Device Array
     * @param null|Array_ $language Language Array
     * @param null|Array_ $currency Currency Array
     * @param null|Boolean $status Status Boolean
     * @param null|Array_ $route Route Array_
     * @return string
     */
    public function scopeMultipleRoute($query, $device, $language, $currency, $status, $route)
    {
        if (!empty($device)) {
            $query->whereIn('mobile', $device);
        }

        if (!empty($language)) {
            $query->whereIn('language', $language);
        }

        if (!empty($currency)) {
            //\Log::info(__METHOD__, ['2.3 $currency' => $currency]);
            $query->whereIn('currency_iso', $currency);
        }

        if (!empty($status)) {
            $query->where('status', $status);
        }

        if (!empty($route)) {
            $query->whereIn('route', $route);
        }

        return $query;
    }
}

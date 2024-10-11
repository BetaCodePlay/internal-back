<?php

namespace App\FinancialReport\Entities;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Financial Report
 *
 * Class to define the financial report properties
 *
 * @package App\FinancialReport\Entities
 * @author  Genesis Perez
 */
class FinancialReport extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'financial_report';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Primary key
     *
     * @var array
     */
    protected $fillable = ['id', 'user_id', 'currency_iso', 'amount', 'provider_id', 'load_amount', 'maker', 'total_played', 'load_date', 'limit'];

    /**
     * Timestamps
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];
}

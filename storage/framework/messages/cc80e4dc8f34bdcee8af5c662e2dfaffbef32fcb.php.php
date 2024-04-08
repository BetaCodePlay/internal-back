<?php


namespace App\Core\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Currency
 *
 * This class allows to interact with currencies table
 *
 * @package App\Core\Entities
 * @author  Orlando Bravo
 */
class Currency extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'currencies';

    /**
     * Primary key
     *
     * @var string
     */
    protected $primaryKey = 'iso';

    /**
     * Incrementing
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Get data attribute
     *
     * @param string $translations Campaign translations
     * @return mixed
     */
    public function getTranslationsAttribute($translations)
    {
        return json_decode($translations);
    }
}

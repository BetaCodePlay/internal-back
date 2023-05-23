<?php


namespace App\Whitelabels\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Whitelabel
 *
 * This class allows to interact with whitelabels status table
 *
 * @package App\Core\Entities
 * @author  Genesis Perez
 */
class WhitelabelsStatus extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'whitelabels_status';
}

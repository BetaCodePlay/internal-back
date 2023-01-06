<?php

namespace App\Core\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TransactionStatus
 *
 * This class allows to interact with transaction_status table
 *
 * @package App\Core\Entities
 * @author  Orlando Bravo
 */
class TransactionStatus extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'transaction_status';
}

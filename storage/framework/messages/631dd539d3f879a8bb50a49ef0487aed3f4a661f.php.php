<?php

namespace Dotworkers\Bonus\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TransactionStatus
 *
 * This class allows to interact with transaction_status table
 *
 * @package App\WhitelabelsTransactions\Entities
 * @author  Damelys Espinoza
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

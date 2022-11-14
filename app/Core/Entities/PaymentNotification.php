<?php

namespace App\Core\Entities;

use App\Users\Entities\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Provider
 *
 * This class allows to interact with PaymentsNotifcation table
 *
 * @package App\Core\Entities
 * @author  Orlando Bravo
 */
class PaymentNotification extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'payments_notification';

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = ['user_id', 'amount', 'currency_iso', 'whitelabel_id'];

}

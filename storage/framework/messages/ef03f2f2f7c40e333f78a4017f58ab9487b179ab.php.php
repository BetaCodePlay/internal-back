<?php

namespace App\Core\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PushNotification
 *
 * This class allows to interact with push_notifications table
 *
 * @package App\Core\Entities
 * @author  Eborio Linarez
 */
class PushNotification extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'push_notifications';

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = ['payment_method_id', 'amount', 'read', 'whitelabel_id', 'currency_iso'];
}

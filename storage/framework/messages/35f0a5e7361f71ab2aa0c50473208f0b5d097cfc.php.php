<?php


namespace App\Notifications\Entities;


use Illuminate\Database\Eloquent\Model;

/**
 * Class NotificationGroupUser
 *
 * Class to define the notifications groups user table attributes
 *
 * @package App\Notifications\Entities
 */
class NotificationGroupUser extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'notifications_groups_user';

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = ['notification_group_id', 'user_id'];

    /**
     * Incrementing
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Timestamps
     *
     * @var bool
     */
    public $timestamps = false;
}

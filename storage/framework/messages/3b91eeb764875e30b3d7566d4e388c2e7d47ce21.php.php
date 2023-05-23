<?php

namespace App\Notifications\Entities;


use Illuminate\Database\Eloquent\Model;

/**
 * Class NotificationType
 *
 * Class to define the notifications types table attributes
 *
 * @package App\Notifications\Entities
 */
class NotificationType extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'notifications_types';
}
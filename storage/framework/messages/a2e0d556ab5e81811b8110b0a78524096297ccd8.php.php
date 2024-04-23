<?php

namespace App\Core\Repositories;

use App\Core\Entities\PushNotification;

/**
 * Class PushNotificationsRepo
 *
 * This class allows to interact with PushNotification entity
 *
 * @package App\Core\Repositories
 * @author  Eborio Linarez
 */
class PushNotificationsRepo
{
    /**
     * Get unread
     *
     * @param int $whitelabel Whitelabel ID
     * @return mixed
     */
    public function getUnread($whitelabel)
    {
        $notifications = PushNotification::select('currency_iso', 'payment_method_id', \DB::raw('count(*) AS quantity'))
            ->where('whitelabel_id', $whitelabel)
            ->where('read', false)
            ->groupBy('currency_iso', 'payment_method_id')
            ->get();
        return $notifications;
    }

    /**
     * Store notification
     *
     * @param array $data Notification data
     * @return mixed
     */
    public function store($data)
    {
        $notification = PushNotification::create($data);
        return $notification;
    }
}

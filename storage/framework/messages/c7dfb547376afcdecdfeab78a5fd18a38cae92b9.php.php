<?php

namespace App\Core\Collections;

use Dotworkers\Configurations\Enums\PaymentMethods;

/**
 * Class PushNotificationsCollection
 *
 * This class allows to format push notifications data
 *
 * @package App\Core\Collections
 * @author  Eborio Linarez
 */
class PushNotificationsCollection
{
    /**
     * /**
     * Format all notifications
     *
     * @param $notifications
     * @return string
     */
    public function formatAll($notifications)
    {
        $html = '';

        foreach ($notifications as $notification) {
            $paymentMethod = PaymentMethods::getName($notification->payment_method_id);
            $notification->title = _i('There are %s pending deposits in %s through %s', [$notification->quantity, $notification->currency_iso, $paymentMethod]);
            $data['notification'] = $notification;
            $html .= view('back.layout.push-notifications', $data)->render();
        }
        return $html;
    }
}

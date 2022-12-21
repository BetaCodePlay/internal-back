<?php

namespace Dotworkers\Notifications\Repositories;

use Dotworkers\Notifications\Entities\ProviderNotification;

/**
 * Class ProviderNotificationsRepo
 *
 * This class allows to interact with provider notification table
 *
 * @package Dotworkers\Notifications\Repositories
 * @author  Orlando Bravo
 */
class ProviderNotificationsRepo
{
    
     /**
     * Get provider notifications by whitelabel and currency
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $currency Currency ISO
     * @return mixed
     */
    public function getNotificationByWhitelabelAndCurrency($whitelabel, $currency)
    {
        $notifications = ProviderNotification::where('whitelabel_id', $whitelabel)
            ->where('currency_iso', $currency)
            ->limit(20)
            ->orderBy('id', 'desc')
            ->get();
        return $notifications;
    }
    
    /**
     * Store provider notification
     *
     * @param array $data provider notification data
     * @return mixed
     */
    public function store($data)
    {
        $notification = ProviderNotification::create($data);
        return $notification;
    }

}


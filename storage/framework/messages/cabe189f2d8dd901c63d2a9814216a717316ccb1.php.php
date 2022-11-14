<?php

namespace Dotworkers\Notifications;

use App\Users\Mailers\SendEmail;
use Dotworkers\Configurations\Enums\TransactionStatus;
use Dotworkers\Notifications\Repositories\ProviderNotificationsRepo;
use Dotworkers\Store\Enums\TransactionTypes;

/**
 * Class Notifications
 *
 * This class allows to interact with provider notification notifications
 *
 * @package Dotworkers\Notifications
 * @author  Orlando Bravo
 */
class Notifications
{
     /**
     * Get provider notifications by whitelabel
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $currency Currency ISO
     * @return mixed
     */
    public static function get($whitelabel, $currency)
    {
        $providerNotificationsRepo = new ProviderNotificationsRepo();
        $notifications = $providerNotificationsRepo->getNotificationByWhitelabelAndCurrency($whitelabel, $currency);
        return $notifications;
    }

    /**
     * Send notifications via email
     *
     * @param object $user User data
     * @param object $transaction Transaction data
     * @param object $paymentMethod Payment method data
     * @param string $description Transaction description
     * @param int $transactionType Transaction type
     * @param int $status Transaction status
     * @return mixed
     */
    public static function sendEmail($user, $transaction, $paymentMethod, $description, $transactionType, $status)
    {
        if (!is_null($user)) {
            if (!is_null($user->email)) {
                if ($transactionType == TransactionTypes::$credit) {
                    $typeDescription = _i('Credit');

                    if ($status == TransactionStatus::$approved) {
                        $subject = _i('Your deposit was approved');
                        $statusDescription = _i('approved');

                    } else {
                        $subject = _i('Your deposit was rejected');
                        $statusDescription = _i('rejected');
                    }
                } else {
                    $typeDescription = _i('Debit');

                    if ($status == TransactionStatus::$approved) {
                        $subject = _i('Your withdrawal was approved');
                        $statusDescription = _i('approved');

                    } else {
                        $subject = _i('Your withdrawal was rejected');
                        $statusDescription = _i('rejected');
                    }
                }
                return Mail::to($user->email)->send(new NotificationEmail($transaction, $subject, $paymentMethod, $statusDescription, $typeDescription, $description));
            }
        }
    }

    /**
     * Store notification
     *
     * @param int $user User ID
     * @param float $amount Transaction amount
     * @param int $whitelabel Whitelabel ID
     * @param string $currency Currency ISO
     * @param int $provider Provider ID
     * @param array $data Transaction data
     * @return mixed
     */
    public static function store($user, $amount, $whitelabel, $currency, $provider, $data)
    {
        $providerNotificationsRepo = new ProviderNotificationsRepo();
        $notificationData = [
            'user_id' => $user,
            'amount' => $amount,
            'whitelabel_id' => $whitelabel,
            'currency_iso' => $currency,
            'provider_id' => $provider,
            'data' => $data
        ];
        $notification = $providerNotificationsRepo->store($notificationData);
        return $notification;
    }
}

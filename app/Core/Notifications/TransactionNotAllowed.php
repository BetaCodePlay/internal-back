<?php

namespace App\Core\Notifications;

use App\Core\Enums\TransactionNotificationsTypes;
use App\Users\Repositories\UsersRepo;
use Aws\Laravel\AwsFacade;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Configurations\Enums\Providers;
use Ixudra\Curl\Facades\Curl;
use Dotworkers\Alerts\Alerts;

/**
 * Class TransactionNotAllowed
 *
 * This class allows to send transactions notifications
 *
 * @package App\Core\Notifications
 * @author  Eborio Linárez
 * @author  Orlando Bravo
 */
class TransactionNotAllowed
{
    /**
     * TransactionNotAllowed constructor
     *
     * @param float $amount Transaction amount
     * @param int $user User ID
     * @param int $provider Provider ID
     */
    public function __construct($amount, $user, $provider, $notificationType)
    {
        $usersRepo = new UsersRepo();
        $whitelabel = Configurations::getWhitelabelDescription();
        $currency = session('currency');
        $operator = auth()->user()->username;
        $providerName = Providers::getName($provider);
        $userData = $usersRepo->find($user);
        $whitelabelId = Configurations::getWhitelabel();
        $amount = number_format($amount, 2);

        switch ($notificationType) {
            case TransactionNotificationsTypes::$transaction_greater_than_allowed: {
                break;
            }
            case TransactionNotificationsTypes::$transaction_greater_than_available_amount: {
                break;
            }
        }

        $message = "⚠ Alerta de transferencia o ajuste ⚠" . PHP_EOL . "WL: {$whitelabel}" . PHP_EOL . "Monto: {$currency} {$amount}" . PHP_EOL . "Operador: {$operator}" . PHP_EOL . "Usuario: {$userData->username}" . PHP_EOL . "Método: {$providerName}";
     //   $this->sendTelegram($message, $whitelabelId, $currency);
        $this->sendSms($message);
    }

    /**
     * Send SMS
     *
     * @param string $message Message
     */
    private function sendSms($message)
    {

        $sms = AwsFacade::createClient('sns');
        $numbers = [
            'Victor Digitel' => '+584123601639',
            'Orlando Digitel' => '+584123298857'

        ];
        /*
               foreach ($numbers as $number) {
                   $sms->publish([
                       'Message' => $message,
                       'PhoneNumber' => $number,
                       'MessageAttributes' => [
                           'AWS.SNS.SMS.SMSType' => [
                               'DataType' => 'String',
                               'StringValue' => 'Transactional',
                           ],
                           'AWS.SNS.SMS.SenderID' => [
                               'DataType' => 'String',
                               'StringValue' => 'back-office'
                           ],
                       ],
                   ]);
               }
        */


               $sms = AwsFacade::createClient('sns');
               $theme = 'arn:aws:sns:us-east-1:072423260887:Alertas-SMS';

               $sms->publish([
                   'Message' => $message,
                   'TopicArn' => $theme
               ]);
        \Log::notice(__METHOD__, ['message' =>  $message, 'sms' =>  $sms ]);
    }

    /**
     * Send Telegram notification
     *
     * @param string $message Message
     * @param int $id ID WHitelabels
     * @param string $currency currency
     */
    private function sendTelegram($message, $id, $currency)
    {
        Alerts::sendTelegram($message, $id, $currency);
    }
}

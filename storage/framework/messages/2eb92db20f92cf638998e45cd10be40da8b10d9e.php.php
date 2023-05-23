<?php

namespace App\Http\Controllers;

use App\Core\Collections\PushNotificationsCollection;
use App\Core\Repositories\PushNotificationsRepo;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Configurations\Utils;
use Illuminate\Http\Request;

/**
 * Class ProvidersLimitsController
 *
 * This class allows to manage push notifications requests
 *
 * @package App\Http\Controllers
 * @author  Eborio Linarez
 */
class PushNotificationsController extends Controller
{
    /**
     * PushNotificationsRepo
     *
     * @var PushNotificationsRepo
     */
    private $pushNotificationsRepo;

    /**
     * PushNotificationsCollection
     *
     * @var PushNotificationsCollection
     */
    private $pushNotificationsCollection;

    /**
     * PushNotificationsController constructor
     *
     * @param PushNotificationsRepo $pushNotificationsRepo
     * @param PushNotificationsCollection $pushNotificationsCollection
     */
    public function __construct(PushNotificationsRepo $pushNotificationsRepo, PushNotificationsCollection $pushNotificationsCollection)
    {
        $this->pushNotificationsRepo = $pushNotificationsRepo;
        $this->pushNotificationsCollection = $pushNotificationsCollection;
    }

    public function store(Request $request)
    {
        try {
            $whitelabel = Configurations::getWhitelabel();
            $notificationData = [
                'payment_method_id' => $request->payment_method,
                'amount' => $request->amount,
                'whitelabel_id' => $whitelabel,
                'currency_iso' => $request->currency,
            ];
            $this->pushNotificationsRepo->store($notificationData);
            $pushNotifications = $this->pushNotificationsRepo->getUnread($whitelabel);
            $pushNotificationsData = $this->pushNotificationsCollection->formatAll($pushNotifications);
            $data = [
                'quantity' => count($pushNotifications),
                'notifications' => $pushNotificationsData
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {

        }
    }
}

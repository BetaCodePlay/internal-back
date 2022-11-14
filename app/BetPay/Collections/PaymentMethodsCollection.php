<?php


namespace App\BetPay\Collections;

use App\Core\Repositories\ProvidersRepo;
use Dotworkers\Configurations\Enums\PaymentMethods;
use Dotworkers\Configurations\Enums\Providers;

/**
 * Class PaymentMethodsCollection
 *
 * This class allows to format payment methods
 *
 * @package App\BetPay\Collections
 * @author  Eborio Linarez
 */
class PaymentMethodsCollection
{
    /**
     * Format all payment methods
     *
     * @param $paymentMethods
     */
    public function formatAll($paymentMethods)
    {
        foreach ($paymentMethods as $paymentMethod)
        {
            $paymentMethod->name = PaymentMethods::getName($paymentMethod->payment_method_id);
        }
    }

    /**
     * Format providers all
     *
     * @param array $providers providers data
     * @return array
     */
    public function formatByProviders($providers)
    {
        foreach ($providers as $provider) {
            $provider->name = Providers::getName($provider->id);
        }
    }

    /**
     * Format distinct by payment methods
     *
     * @param $paymentMethods
     */
    public function formatDistinctByPaymentMethods($paymentMethods)
    {
        $paymentMethodsData = [];
        $auxData = [];
        foreach ($paymentMethods as $key => $paymentMethod) {
            $paymentMethod->name = PaymentMethods::getName($paymentMethod->payment_method_id);
            $position = array_search($paymentMethod->payment_method_id, $auxData);
            if ($position === false) {
                array_push($auxData, $paymentMethod->payment_method_id);
                $totalObject = new \stdClass();
                $totalObject->id = $paymentMethod->payment_method_id;
                $totalObject->name = $paymentMethod->name;
                array_push($paymentMethodsData, $totalObject);
            }
        }
        return json_decode(json_encode($paymentMethodsData));
   }

    /**
     * Format providers all
     *
     * @param array $paymentMethods Payment methods data
     * @return array
     */
    public function fomartByProviderAndCurrency($paymentMethods)
    {
        $paymentMethodsIds = [];
        $providersRepo = new ProvidersRepo();
        if (!is_null($paymentMethods)) {
            foreach ($paymentMethods as $paymentMethod) {
                $paymentMethodsIds[] = $paymentMethod->payment_method_id;
            }
        }
        $uniquePaymentMethods = collect($paymentMethodsIds)->unique()->values()->all();
        $paymentMethodsData = $providersRepo->getByBeyPayIDs($uniquePaymentMethods);

        foreach ($paymentMethodsData as $provider) {
            $provider->name = Providers::getName($provider->id);
        }
        return $paymentMethodsData;
    }
}

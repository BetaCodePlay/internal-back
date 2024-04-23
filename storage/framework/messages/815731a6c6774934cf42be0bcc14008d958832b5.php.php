<?php

if (! function_exists('getUniquePaymentMethods')) {
    function getUniquePaymentMethods(): array
    {
        $uniquePaymentMethods = [];

        if (! is_null(session('payment_methods'))) {
            $paymentMethodsIds = array_map(function ($val) {
                return $val->payment_method_id;
            }, session('payment_methods'));

            $uniquePaymentMethods = collect($paymentMethodsIds)->unique()->values()->all();
        }

        return $uniquePaymentMethods;
    }
}

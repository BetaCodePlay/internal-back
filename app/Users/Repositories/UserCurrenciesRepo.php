<?php

namespace App\Users\Repositories;

use App\Users\Entities\UserCurrency;

/**
 * Class UserCurrenciesRepo
 *
 * This class allows to interact with UserCurrency entity
 *
 * @package App\Users\Repositories
 * @author  Eborio Linarez
 */
class UserCurrenciesRepo
{
    /**
     * Find default currency
     *
     * @param int $user User ID
     * @return mixed
     */
    public function findDefault($user)
    {
        return UserCurrency::where('user_id', $user)
            ->where('default', true)
            ->first();
    }

    /**
     * Find the first currency and set it as default
     *
     * @param $user
     * @return mixed
     */
    public function findFirst($user)
    {
        $currency = UserCurrency::where('user_id', $user)
            ->first();
        $currency->default = true;
        $currency->save();
        return $currency;
    }

    /**
     * Reset default currencies
     *
     * @param int $user User ID
     */
    public function resetDefaultCurrencies($user)
    {
        UserCurrency::where('user_id', $user)
            ->update([
                'default' => false
            ]);
    }

    /**
     * Store user currency
     *
     * @param array $userData User currency data
     * @param array $walletData Wallet data
     * @return mixed
     */
    public function store($userData, $walletData)
    {
        $currency = UserCurrency::updateOrCreate(
            $userData,
            $walletData
        );
        return $currency;
    }
}

<?php

namespace App\Wallets\Collections;

use Dotworkers\Configurations\Enums\ProviderTypes;

/**
 * Class WalletsCollection
 *
 * This class allows to format wallets data
 *
 * @package App\Wallets\Collections
 * @author  Eborio Linarez
 */
class WalletsCollection
{
    /**
     * Format user wallet
     *
     * @param object $wallet Wallet data
     */
    public function formatWallet($wallet)
    {
        $wallet->balance = number_format($wallet->balance, 2);
        $wallet->balance_locked = number_format($wallet->balance_locked, 2);
    }

    /**
     * Format user wallets
     *
     * @param array $wallets Wallets data
     */
    public function formatWallets($wallets)
    {
        foreach ($wallets as $wallet) {
            $wallet->balance = number_format($wallet->balance, 2);
            $wallet->balance_locked = number_format($wallet->balance_locked, 2);
        }
    }

    /**
     * Format user wallets bonuses
     *
     * @param array $wallet Wallets data
     */
    public function formatWalletsBonuses($wallet)
    {
        $bonusBalance = 0;
        $walletsData = [];

        if (isset($wallet->data->bonus)) {
            foreach ($wallet->data->bonus as $bonusWallet) {
                $bonusWallet->provider_type = ProviderTypes::getName($bonusWallet->provider_type_id);
                $bonusWallet->balance = number_format($bonusWallet->balance,2);
            }
            $walletsData = $wallet->data->bonus;
        }
        return $walletsData;
    }
}

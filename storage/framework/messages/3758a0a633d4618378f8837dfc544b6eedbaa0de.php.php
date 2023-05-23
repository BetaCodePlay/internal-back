<?php

namespace Dotworkers\Store;

use Carbon\Carbon;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Store\Enums\Actions;
use Dotworkers\Store\Enums\ActionTypes;
use Dotworkers\Store\Enums\TransactionTypes;
use Dotworkers\Store\Repositories\ActionsConfigurationsRepo;
use Dotworkers\Store\Repositories\PointsTransactionsRepo;
use Dotworkers\Store\Repositories\PointsWalletsRepo;
use Dotworkers\Store\Repositories\StoreExchangesRepo;
use Jenssegers\Agent\Agent;

/**
 * Class Store
 *
 * This class allows managing whitelabels store
 *
 * @package Dotworkers\Store
 * @author  Damelys Espinoza
 * @author  Eborio Linarez
 */
class Store
{
    /**
     * Execute action
     *
     * @param int $action Action ID
     * @param int $user User ID
     * @param int $whitelabel Whitelabel ID
     * @param int $provider Provider ID
     * @param string $currency Currency ISO
     * @param string $userAgent User agent
     * @param int $providerType Type provider ID
     * @param double $amount Action amount
     * @param null|array $additionalData Additional transaction data
     * @return float|int
     */
    public static function action($action, $user, $whitelabel, $provider, $currency, $userAgent, $providerType, $amount, $additionalData = null)
    {
        $store = Configurations::getStoreIntegration($whitelabel)->active;

        if ($store) {
            $actionsRepo = new ActionsConfigurationsRepo();
            $agent = new Agent();
            $status = true;
            $action = $actionsRepo->getAction($action, $providerType, $whitelabel, $currency, $status);
            $mobile = $agent->isMobile($userAgent);

            if (!is_null($action)) {
                $date = Carbon::now();
                $originalPoints = 0;

                if ($action->action_type_id == ActionTypes::$action) {
                    $actionData = $action->data;

                    switch ($action->action_id) {
                        case Actions::$bet:
                        {
                            $actionAmount = $mobile ? $actionData->mobile_amount : $actionData->amount;
                            $actionPoints = $mobile ? $actionData->mobile_points : $actionData->points;
                            break;
                        }
                        case Actions::$deposit:
                        {
                            if (is_null($actionData->min) && is_null($actionData->max)) {
                                $actionAmount = $mobile ? $actionData->mobile_amount : $actionData->amount;
                                $actionPoints = $mobile ? $actionData->mobile_points : $actionData->points;

                            } else {
                                if ($amount >= $actionData->min && $amount <= $actionData->max) {
                                    $actionAmount = $mobile ? $actionData->mobile_amount : $actionData->amount;
                                    $actionPoints = $mobile ? $actionData->mobile_points : $actionData->points;
                                }
                            }
                            break;
                        }
                    }

                    if (isset($actionAmount) && isset($actionPoints)) {
                        $excludeProviders = $action->exclude_providers;

                        if (is_null($excludeProviders) || !in_array($provider, $excludeProviders)) {
                            $factor = $amount / $actionAmount;
                            $points = $originalPoints = round($factor * $actionPoints, 2);

                            if ($points > 0) {
                                $provider = is_null($provider) ?: $provider;
                                if (is_null($actionData->start_date) && is_null($actionData->end_date)) {
                                    self::transaction($user, $currency, $whitelabel, $points, $provider, TransactionTypes::$credit);
                                } else {
                                    if ($date >= $actionData->start_date && $date <= $actionData->end_date) {
                                        self::transaction($user, $currency, $whitelabel, $points, $provider, TransactionTypes::$credit);
                                    }
                                }
                            }
                        }
                    }
                }
                return $originalPoints;
            }
        }
    }

    /**
     * Credit transactions
     *
     * @param int $user User ID
     * @param string $currency Currency ISO
     * @param int $whitelabel Whitelabel ID
     * @param float $amount Transaction amount
     * @param int $provider Provider ID
     * @return mixed
     */
    public static function credit($user, $currency, $whitelabel, $amount, $provider)
    {
        $transactionType = TransactionTypes::$credit;
        return self::transaction($user, $currency, $whitelabel, $amount, $provider, $transactionType);
    }

    /**
     * Debit transactions
     *
     * @param int $user User ID
     * @param string $currency Currency ISO
     * @param int $whitelabel Whitelabel ID
     * @param float $amount Transaction amount
     * @param int $provider Provider ID
     * @return mixed
     */
    public static function debit($user, $currency, $whitelabel, $amount, $provider)
    {
        $transactionType = TransactionTypes::$debit;
        return self::transaction($user, $currency, $whitelabel, $amount, $provider, $transactionType);
    }

    /**
     * Store exchange
     *
     * @param array $data Exchange data
     * @return Entities\StoreExchange
     */
    public static function exchange($data)
    {
        $storeExchangesRepo = new StoreExchangesRepo();
        return $storeExchangesRepo->store($data);
    }

    /**
     * Get user exchanges
     *
     * @param int $user User ID
     * @param string $currency Currency ISO
     * @return mixed
     */
    public static function getUserExchanges($user, $currency)
    {
        $storeExchangesRepo = new StoreExchangesRepo();
        return $storeExchangesRepo->getByUser($user, $currency);
    }

    /**
     * Get user transactions
     *
     * @param int $user User ID
     * @param string $currency Currency ISO
     * @return mixed
     */
    public static function getUserTransactions($user, $currency)
    {
        $pointsTransactionsRepo = new PointsTransactionsRepo();
        return $pointsTransactionsRepo->getByUserV2($user, $currency);
    }

    /**
     * Get points wallet by user and currency
     *
     * @param int $user User ID
     * @param string $currency Currency ISO
     * @return mixed
     */
    public static function getWallet($user, $currency)
    {
        $pointsWalletsRepo = new PointsWalletsRepo();
        return $pointsWalletsRepo->findByUserAndCurrency($user, $currency);
    }

    /**
     * Execute login action
     *
     * @param int $user User ID
     * @param int $whitelabel Whitelabel ID
     * @param int $provider Provider ID
     * @param string $currency Currency ISO
     * @param string $userAgent User agent
     * @param int $providerType Type provider ID
     * @param int $logins Number of logins diary
     * @return float|int
     */
    public static function login($user, $whitelabel, $provider, $currency, $userAgent, $providerType, $logins)
    {
        $store = Configurations::getStoreIntegration($whitelabel)->active;

        if ($store) {
            $actionsRepo = new ActionsConfigurationsRepo();
            $agent = new Agent();
            $status = true;
            $action = $actionsRepo->getAction(ActionTypes::$action, $providerType, $whitelabel, $currency, $status);
            $mobile = $agent->isMobile($userAgent);

            if (!is_null($action)) {
                $date = Carbon::now();
                $actionData = $action->data;
                $amount = 1;
                $actionAmount = 1;
                $actionPoints = 0;

                if ($mobile) {
                    if ($logins == $actionAmount) {
                        $actionPoints = $actionData->mobile_points;
                    }
                } else {
                    if ($logins == $actionAmount) {
                        $actionPoints = $actionData->points;
                    }
                }

                $factor = $amount / $actionAmount;
                $points = $originalPoints = round($factor * $actionPoints, 2);

                if ($points > 0) {
                    $provider = is_null($provider) ?: $provider;
                    if (is_null($actionData->start_date) && is_null($actionData->end_date)) {
                        self::transaction($user, $currency, $whitelabel, $points, $provider, TransactionTypes::$credit);
                    } else {
                        if ($date >= $actionData->start_date && $date <= $actionData->end_date) {
                            self::transaction($user, $currency, $whitelabel, $points, $provider, TransactionTypes::$credit);
                        }
                    }
                }
                return $originalPoints;
            }
        }
    }

    /**
     * Execute login action
     *
     * @param int $user User ID
     * @param int $whitelabel Whitelabel ID
     * @param int $provider Provider ID
     * @param int $providerType Provider type ID
     * @param string $currency Currency ISO
     * @param float $profit User profit¡
     * @return float
     */
    public static function profit($user, $whitelabel, $provider, $providerType, $currency, $profit)
    {
        $store = Configurations::getStoreIntegration($whitelabel)->active;

        if ($store) {
            $actionsRepo = new ActionsConfigurationsRepo();
            $status = true;
            $action = $actionsRepo->getAction(Actions::$profit, $providerType, $whitelabel, $currency, $status);

            if (!is_null($action)) {
                $date = Carbon::now();
                $actionData = $action->data;

                if ($profit > 0) {
                    $actionPoints = $actionData->points;
                    $actionAmount = $actionData->amount;
                    $factor = $profit / $actionAmount;
                    $points = $originalPoints = round($factor * $actionPoints, 2);

                    $provider = is_null($provider) ?: $provider;
                    if (is_null($actionData->start_date) && is_null($actionData->end_date)) {
                        self::transaction($user, $currency, $whitelabel, $points, $provider, TransactionTypes::$credit);
                    } else {
                        if ($date >= $actionData->start_date && $date <= $actionData->end_date) {
                            self::transaction($user, $currency, $whitelabel, $points, $provider, TransactionTypes::$credit);
                        }
                    }
                    return $originalPoints;
                }
            }
        }
    }

    /**
     * Store points wallets
     *
     * @param int $user User ID
     * @param string $currency Currency ISO
     * @return mixed
     */
    public static function storeWallet($user, $currency)
    {
        $pointsWalletsRepo = new PointsWalletsRepo();
        $wallet = Store::getWallet($user, $currency);

        if (is_null($wallet)) {
            $data = [
                'user_id' => $user,
                'currency_iso' => $currency,
                'balance' => 0
            ];
            $wallet = $pointsWalletsRepo->store($data);
        }
        return $wallet;
    }

    /**
     * @param int $user User ID
     * @param string $currency Currency ISO
     * @param int $whitelabel Whitelabel ID
     * @param float $amount Transaction amount
     * @param int $provider Provider ID
     * @param int $transactionType Transaction type
     * @return mixed
     */
    private static function transaction($user, $currency, $whitelabel, $amount, $provider, $transactionType)
    {
        $pointsWalletsRepo = new PointsWalletsRepo();
        $pointsTransactionsRepo = new PointsTransactionsRepo();
        $wallet = Store::getWallet($user, $currency);

        if (is_null($wallet)) {
            $wallet = self::storeWallet($user, $currency);
            $pointsWalletsRepo->updateBalance($user, $currency, $amount, $transactionType);
            $balance = $amount;

        } else {
            $pointsWalletsRepo->updateBalance($user, $currency, $amount, $transactionType);
            if ($transactionType == TransactionTypes::$credit) {
                $balance = $wallet->balance + $amount;
            } else {
                $balance = $wallet->balance - $amount;
            }
        }

        $data = [
            'points_wallet_id' => $wallet->id,
            'amount' => $amount,
            'balance' => $balance,
            'provider_id' => $provider,
            'transaction_type_id' => $transactionType,
            'currency_iso' => $currency,
            'whitelabel_id' => $whitelabel,
            'user_id' => $user,
            'data' => null
        ];
        if ($whitelabel == 68) {
            return $pointsTransactionsRepo->store($data);
        } else {
            return $pointsTransactionsRepo->storeV2($data);
        }
    }
}
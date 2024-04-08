<?php

namespace Dotworkers\Wallet;

use Dotworkers\Wallet\Enums\Actions;
use Ixudra\Curl\Facades\Curl;

/**
 * Class Wallet
 *
 * This class allows to manage Wallet requests
 *
 * @package Dotworkers\Wallet
 * @author  Eborio Linarez
 * @author  Gabriel Santiago
 */
class Wallet
{
    /**
     * clear wallet to user
     *
     * @param int $user User ID
     * @param string $currency Wallet currency
     * @param int $providerType Provider type ID
     * @param string|null $token Access token
     * @return mixed
     */
    public static function clearWallet($user, $currency, $providerType, $token = null)
    {
        $token = is_null($token) ? session('wallet_access_token') : $token;
        $url = config('wallet.url') . '/api/wallets/clear-wallet';
        $data = [
            'user' => $user,
            'currency' => $currency,
            'provider_type' => $providerType
        ];
        $curl = Curl::to($url)
            ->withData($data)
            ->withHeader('Accept: application/json')
            ->withHeader("Authorization: Bearer $token")
            ->post();
        return json_decode($curl);
    }

    /**
     * clear wallets to users
     *
     * @param array $users Users IDs
     * @param null|string $currency Wallet currency
     * @param int $providerType Provider type ID
     * @param string|null $token Access token
     * @return mixed
     */
    public static function clearWallets($users, $currency, $providerType, $token = null)
    {
        $token = is_null($token) ? session('wallet_access_token') : $token;
        $url = config('wallet.url') . '/api/wallets/clear-wallets';
        $data = [
            'users' => $users,
            'currency' => $currency,
            'provider_type' => $providerType
        ];
        $curl = Curl::to($url)
            ->withData($data)
            ->withHeader('Accept: application/json')
            ->withHeader("Authorization: Bearer $token")
            ->post();
        return json_decode($curl);
    }

    /**
     * Get client access token
     *
     * @return mixed
     */
    public static function clientAccessToken()
    {
        $url = config('wallet.url') . '/oauth/token';
        $data = [
            'grant_type' => 'client_credentials',
            'client_id' => config('wallet.client_credentials_grant.client_id'),
            'client_secret' => config('wallet.client_credentials_grant.client_secret'),
            'scope' => 'store-wallets get-wallet credit-transactions debit-transactions get-transactions'
        ];
        $curl = Curl::to($url)
            ->withData($data)
            ->post();
        return json_decode($curl);
    }

    /**
     * Credit generic transactions
     *
     * @param float $amount Transaction amount
     * @param int $provider Provider ID
     * @param array $data Transaction additional data
     * @param int $wallet Wallet ID
     * @param string $token Access token
     * @return mixed
     */
    public static function credit($amount, $provider, $data, $wallet, $token)
    {
        $url = config('wallet.url') . '/api/transactions/credit';
        $data = [
            'amount' => $amount,
            'provider' => $provider,
            'data' => $data,
            'action' => Actions::$generic,
            'wallet' => $wallet
        ];
        $curl = Curl::to($url)
            ->withData($data)
            ->withHeader('Accept: application/json')
            ->withHeader("Authorization: Bearer $token")
            ->post();
        return json_decode($curl);
    }

    /**
     * Credit manual transactions
     *
     * @param float $amount Transaction amount
     * @param int $provider Provider ID
     * @param array $data Transaction additional data
     * @param int $wallet Wallet ID
     * @param null|string $token Access token
     * @return mixed
     */
    public static function creditManualTransactions($amount, $provider, $data, $wallet, $token = null)
    {
        $token = is_null($token) ? session('wallet_access_token') : $token;
        return self::creditTransactions($amount, $provider, $data, Actions::$manual, $wallet, $token);
    }

    /**
     * Credit rollback transactions
     *
     * @param float $amount Transaction amount
     * @param int $provider Provider ID
     * @param array $data Transaction additional data
     * @param int $wallet Wallet ID
     * @param string $token Access token
     * @return mixed
     */
    public static function creditRollbackTransactions($amount, $provider, $data, $wallet, $token)
    {
        $token = is_null($token) ? session('wallet_access_token') : $token;
        return self::credit($amount, $provider, $data, $wallet, $token);
    }

    /**
     * Credit transactions
     *
     * @param float $amount Transaction amount
     * @param int $provider Provider ID
     * @param array $data Transaction additional data
     * @param $action
     * @param string $currency Transaction currency
     * @param int $wallet Wallet ID
     * @param string $token Access token
     * @return mixed
     */
    public static function creditTransactions($amount, $provider, $data, $action, $wallet, $token)
    {
        $url = config('wallet.url') . '/api/transactions/credit-by-client';
        $data = [
            'amount' => $amount,
            'provider' => $provider,
            'data' => $data,
            'action' => $action,
            'wallet' => $wallet
        ];
        $curl = Curl::to($url)
            ->withData($data)
            ->withHeader('Accept: application/json')
            ->withHeader("Authorization: Bearer $token")
            ->post();
        return json_decode($curl);
    }

    /**
     * Credit unlock transactions
     *
     * @param float $amount Transaction amount
     * @param int $provider Provider ID
     * @param array $data Transaction additional data
     * @param int $wallet Wallet ID
     * @param null|string $token Access token
     * @return mixed
     */
    public static function creditUnlockTransactions($amount, $provider, $data, $wallet, $token = null)
    {
        $token = is_null($token) ? session('wallet_access_token') : $token;
        return self::creditTransactions($amount, $provider, $data, Actions::$unlock, $wallet, $token);
    }

    /**
     * Debit generic transactions
     *
     * @param float $amount Transaction amount
     * @param int $provider Provider ID
     * @param array $data Transaction additional data
     * @param int $wallet Wallet ID
     * @param string $token Access token
     * @return mixed
     */
    public static function debit($amount, $provider, $data, $wallet, $token)
    {

        $url = config('wallet.url') . '/api/transactions/debit';
        $data = [
            'amount' => $amount,
            'provider' => $provider,
            'data' => $data,
            'action' => Actions::$generic,
            'wallet' => $wallet
        ];
        $curl = Curl::to($url)
            ->withData($data)
            ->withHeader('Accept: application/json')
            ->withHeader("Authorization: Bearer $token")
            ->post();
        return json_decode($curl);
    }

    /**
     * Debit lock transactions
     *
     * @param float $amount Transaction amount
     * @param int $provider Provider ID
     * @param array $data Transaction additional data
     * @param int $wallet Wallet ID
     * @param null|string $token Access token
     * @return mixed
     */
    public static function debitLockTransactions($amount, $provider, $data, $wallet, $token = null)
    {
        $token = is_null($token) ? session('wallet_access_token') : $token;
        return self::debitTransactions($amount, $provider, $data, Actions::$lock, $wallet, $token);
    }

    /**
     * Debit manual transactions
     *
     * @param float $amount Transaction amount
     * @param int $provider Provider ID
     * @param array $data Transaction additional data
     * @param int $wallet Wallet ID
     * @param null|string $token Access token
     * @return mixed
     */
    public static function debitManualTransactions($amount, $provider, $data, $wallet, $token = null)
    {
        $token = is_null($token) ? session('wallet_access_token') : $token;
        return self::debitTransactions($amount, $provider, $data, Actions::$manual, $wallet, $token);
    }

    /**
     * Debit transactions
     *
     * @param float $amount Transaction amount
     * @param int $provider Provider ID
     * @param array $data Transaction additional data
     * @param $action
     * @param string $token Access token
     * @param int $wallet Wallet ID
     * @return mixed
     */
    private static function debitTransactions($amount, $provider, $data, $action, $wallet, $token)
    {
        $url = config('wallet.url') . '/api/transactions/debit-by-client';
        $data = [
            'amount' => $amount,
            'provider' => $provider,
            'data' => $data,
            'action' => $action,
            'wallet' => $wallet
        ];
        $curl = Curl::to($url)
            ->withData($data)
            ->withHeader('Accept: application/json')
            ->withHeader("Authorization: Bearer $token")
            ->post();
        return json_decode($curl);
    }

    /**
     * Debit rollback transactions
     *
     * @param float $amount Transaction amount
     * @param int $provider Provider ID
     * @param array $data Transaction additional data
     * @param int $wallet Wallet ID
     * @param null|string $token Access token
     * @return mixed
     */
    public static function debitRollbackTransactions($amount, $provider, $data, $wallet, $token = null)
    {
        $token = is_null($token) ? session('wallet_access_token') : $token;
        return self::debitTransactions($amount, $provider, $data, Actions::$manual, $wallet, $token);
    }

    /**
     * Debit unlock transactions
     *
     * @param float $amount Transaction amount
     * @param int $provider Provider ID
     * @param array $data Transaction additional data
     * @param int $wallet Wallet ID
     * @param null|string $token Access token
     * @return mixed
     */
    public static function debitUnlockTransactions($amount, $provider, $data, $wallet, $token = null)
    {
        $token = is_null($token) ? session('wallet_access_token') : $token;
        return self::debitTransactions($amount, $provider, $data, Actions::$unlock, $wallet, $token);
    }

    /**
     * Get user wallet by user
     *
     * @param null|string $currency Wallet currency
     * @param bool $bonus Bonus wallet
     * @param string|null $token Access token
     * @param null|int $wallet Wallet ID
     * @return mixed
     */
    public static function get($currency, $bonus = false, $token = null, $wallet = null)
    {
        $token = is_null($token) ? auth()->user()->wallet_access_token : $token;
        $url = config('wallet.url') . '/api/wallets/get';
        $data = [
            'currency' => $currency,
            'wallet' => $wallet,
            'bonus' => $bonus
        ];
        $curl = Curl::to($url)
            ->withData($data)
            ->withHeader('Accept: application/json')
            ->withHeader("Authorization: Bearer $token")
            ->post();
        return json_decode($curl);
    }

    /**
     * Get total bonus by campaigns
     *
     * @param array $campaigns Campaigns IDs
     * @param null $token
     * @return mixed
     */
    public static function getTotalBonusByCampaigns($campaigns, $token = null)
    {
        $token = is_null($token) ? session('wallet_access_token') : $token;
        $url = config('wallet.url') . '/api/wallets/get-total-bonus-by-campaigns';
        $data = [
            'campaigns' => $campaigns
        ];
        $curl = Curl::to($url)
            ->withData($data)
            ->withHeader('Accept: application/json')
            ->withHeader("Authorization: Bearer $token")
            ->post();
        return json_decode($curl);
    }

    /**
     * Get total bonus by campaigns and users
     *
     * @param array $campaigns Campaigns IDs
     * @param array $users Users IDs
     * @param null $token
     * @return mixed
     */
    public static function getTotalBonusByCampaignsAndUsers($campaigns, $users, $token = null)
    {
        $token = is_null($token) ? session('wallet_access_token') : $token;
        $url = config('wallet.url') . '/api/wallets/get-total-bonus-by-campaigns-and-users';
        $data = [
            'campaigns' => $campaigns,
            'users' => $users
        ];
        $curl = Curl::to($url)
            ->withData($data)
            ->withHeader('Accept: application/json')
            ->withHeader("Authorization: Bearer $token")
            ->post();
        return json_decode($curl);
    }

    /**
     * Get user wallet by client
     *
     * @param int $user User ID
     * @param null|string $currency Wallet currency
     * @param bool $bonus Bonus system
     * @param null|string $token Access token
     * @return mixed
     */
    public static function getByClient($user, $currency, $bonus = false, $token = null)
    {
        $token = is_null($token) ? session('wallet_access_token') : $token;
        $url = config('wallet.url') . '/api/wallets/get-by-client';
        $data = [
            'user' => $user,
            'currency' => $currency,
            'bonus' => $bonus
        ];
        $curl = Curl::to($url)
            ->withData($data)
            ->withHeader('Accept: application/json')
            ->withHeader("Authorization: Bearer $token")
            ->post();
        return json_decode($curl);
    }

    /**
     * Get user wallets by users
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $currency Currency ISO
     * @param null|string $token Access token
     * @return mixed
     */
    public static function getByCurrency($whitelabel, $currency, $token = null)
    {
        $token = is_null($token) ? session('wallet_access_token') : $token;
        $url = config('wallet.url') . '/api/wallets/get-by-currency';
        $data = [
            'whitelabel' => $whitelabel,
            'currency' => $currency
        ];
        $curl = Curl::to($url)
            ->withData($data)
            ->withHeader('Accept: application/json')
            ->withHeader("Authorization: Bearer $token")
            ->post();
        return json_decode($curl);
    }

    /**
     * Get user wallet by integration
     *
     * @param int $providerType Provider type ID
     * @param null|string $currency Wallet currency
     * @param null|string $token Access token
     * @param null|int $wallet Wallet ID
     * @return mixed
     */
    public static function getByIntegration($providerType, $currency, $token, $wallet = null)
    {
        $url = config('wallet.url') . '/api/wallets/get-by-integration';
        $data = [
            'provider_type' => $providerType,
            'currency' => $currency,
            'wallet' => $wallet
        ];
        $curl = Curl::to($url)
            ->withData($data)
            ->withHeader('Accept: application/json')
            ->withHeader("Authorization: Bearer $token")
            ->post();
        return json_decode($curl);
    }

    /**
     * Get user wallets by currencies
     *
     * @param int $user User ID
     * @param null|array $currencies Wallet currency
     * @param null|string $token Access token
     * @return mixed
     */
    public static function getByUserAndCurrencies($user, $currencies, $token = null)
    {
        $token = is_null($token) ? session('wallet_access_token') : $token;
        $url = config('wallet.url') . '/api/wallets/get-by-user-and-currencies';
        $data = [
            'user' => $user,
            'currencies' => $currencies
        ];
        $curl = Curl::to($url)
            ->withData($data)
            ->withHeader('Accept: application/json')
            ->withHeader("Authorization: Bearer $token")
            ->post();
        return json_decode($curl);
    }

    /**
     * Get transactions by dates
     *
     * @param int $wallet Wallet ID
     * @param int $limit Limit
     * @param int $offset Offset
     * @param null $token
     * @return mixed
     */
    public static function getTransactiosByDates($user, $startOffset, $endOffset, $token = null)
    {
        $token = is_null($token) ? session('wallet_access_token') : $token;
        $url = config('wallet.url') . '/api/transactions-wallet/transactions';
        $data = [
            'userId' => $user,
            'createdAt' => $startOffset,
            'updatedAt' => $endOffset,
        ];
        $curl = Curl::to($url)
            ->withData($data)
            ->withHeader('Accept: application/json')
            ->withHeader("Authorization: Bearer $token")
            ->post();
        return json_decode($curl);
    }

    /**
     * Get transactions by wallet
     *
     * @param int $wallet Wallet ID
     * @param int $limit Limit
     * @param int $offset Offset
     * @param null $token
     * @return mixed
     */
    public static function getTransactionsByWallet($wallet, $limit, $offset, $token = null)
    {
        $token = is_null($token) ? session('wallet_access_token') : $token;
        $url = config('wallet.url') . '/api/transactions/get-by-wallet';
        $data = [
            'wallet' => $wallet,
            'limit' => $limit,
            'offset' => $offset,
        ];
        $curl = Curl::to($url)
            ->withData($data)
            ->withHeader('Accept: application/json')
            ->withHeader("Authorization: Bearer $token")
            ->post();
        return json_decode($curl);
    }

    /**
     * Get transactions by wallet and client
     *
     * @param int $wallet Wallet ID
     * @param int $limit Limit
     * @param int $offset Offset
     * @param null $token
     * @return mixed
     */
    public static function getTransactionsByWalletAndClient($wallet, $limit, $offset, $token = null)
    {
        $token = is_null($token) ? session('wallet_access_token') : $token;
        $url = config('wallet.url') . '/api/transactions/get-by-wallet-and-client';
        $data = [
            'wallet' => $wallet,
            'limit' => $limit,
            'offset' => $offset,
        ];
        $curl = Curl::to($url)
            ->withData($data)
            ->withHeader('Accept: application/json')
            ->withHeader("Authorization: Bearer $token")
            ->post();
        return json_decode($curl);
    }

    /**
     * Get transactions by wallet historic
     *
     * @param int $wallet Wallet ID
     * @param int $limit Limit
     * @param int $offset Offset
     * @param null $token
     * @return mixed
     */
    public static function getTransactionsByWalletHistoric($wallet, $limit, $offset, $token = null)
    {
        $token = is_null($token) ? session('wallet_access_token') : $token;
        $url = config('wallet.url') . '/api/transactions/get-by-wallet-historic';
        $data = [
            'wallet' => $wallet,
            'limit' => $limit,
            'offset' => $offset,
        ];
        $curl = Curl::to($url)
            ->withData($data)
            ->withHeader('Accept: application/json')
            ->withHeader("Authorization: Bearer $token")
            ->post();
        return json_decode($curl);
    }

    /**
     * Get transactions by wallet and client historic
     *
     * @param int $wallet Wallet ID
     * @param int $limit Limit
     * @param int $offset Offset
     * @param null $token
     * @return mixed
     */
    public static function getTransactionsByWalletAndClientHistoric($wallet, $limit, $offset, $token = null)
    {
        $token = is_null($token) ? session('wallet_access_token') : $token;
        $url = config('wallet.url') . '/api/transactions/get-by-wallet-and-client-historic';
        $data = [
            'wallet' => $wallet,
            'limit' => $limit,
            'offset' => $offset,
        ];
        $curl = Curl::to($url)
            ->withData($data)
            ->withHeader('Accept: application/json')
            ->withHeader("Authorization: Bearer $token")
            ->post();
        return json_decode($curl);
    }

    /**
     * Get users balance by amounts
     *
     * @param array $wallets Wallet ID
     * @param string $options Options to filter
     * @param float $balance Balance to filter
     * @param null $token Access token
     * @return mixed
     */
    public static function getUsersBalancesByAmounts($wallets, $options, $balance, $token = null)
    {
        $token = is_null($token) ? session('wallet_access_token') : $token;
        $url = config('wallet.url') . '/api/wallets/get-users-balances-by-amounts';
        $data = [
            'wallets' => $wallets,
            'options' => $options,
            'balance' => $balance
        ];
        $curl = Curl::to($url)
            ->withData($data)
            ->withHeader('Accept: application/json')
            ->withHeader("Authorization: Bearer $token")
            ->post();
        return json_decode($curl);
    }

    /**
     * Get users balances by IDs
     *
     * @param array $users Users IDs
     * @param string $currency Currency ISO
     * @param null $token
     * @return mixed
     */
    public static function getUsersBalancesByIds($users, $currency, $token = null)
    {
        $token = is_null($token) ? session('wallet_access_token') : $token;
        $url = config('wallet.url') . '/api/wallets/get-users-balances-by-ids';
        $data = [
            'users' => $users,
            'currency' => $currency
        ];
        $curl = Curl::to($url)
            ->withData($data)
            ->withHeader('Accept: application/json')
            ->withHeader("Authorization: Bearer $token")
            ->post();
        return json_decode($curl);
    }

    /**
     * Store wallet
     *
     * @param int $user User ID
     * @param string $username User username
     * @param string $key User key
     * @param string $currency User currency
     * @param int $whitelabel Whitelabel ID
     * @param string $token Access token
     * @param bool $bonus Bonus system
     * @param null|array $providerTypes Provider types for bonus wallets
     * @param null|int $campaign Campaign ID
     * @return mixed
     */
    public static function store($user, $username, $key, $currency, $whitelabel, $token, $bonus = false, $providerTypes = null, $campaign = null)
    {
        $url = config('wallet.url') . '/api/wallets/store';
        $data = [
            'user' => $user,
            'username' => $username,
            'key' => $key,
            'currency' => $currency,
            'whitelabel' => $whitelabel,
            'bonus' => $bonus,
            'provider_types' => $providerTypes,
            'campaign_id' => $campaign
        ];
        $curl = Curl::to($url)
            ->withData($data)
            ->withHeader('Accept: application/json')
            ->withHeader("Authorization: Bearer $token")
            ->post();
        return json_decode($curl);
    }

    /**
     * Update wallet
     *
     * @param int $wallet Wallet ID
     * @param array $data Wallet data
     * @param string|null $token Access token
     * @return mixed
     */
    public static function update($wallet, $data, $token = null)
    {
        $token = is_null($token) ? session('wallet_access_token') : $token;
        $url = config('wallet.url') . '/api/wallets/update';
        $data = [
            'id' => $wallet,
            'data' => $data
        ];
        $curl = Curl::to($url)
            ->withData($data)
            ->withHeader('Accept: application/json')
            ->withHeader("Authorization: Bearer $token")
            ->post();
        return json_decode($curl);
    }

    /**
     * Update wallet by user
     *
     * @param int $wallet Wallet ID
     * @param array $data Wallet data
     * @param string|null $token Access token
     * @return mixed
     */
    public static function updateByUser($wallet, $data, $token = null)
    {
        $token = is_null($token) ? session('wallet_access_token') : $token;
        $url = config('wallet.url') . '/api/wallets/update-by-user';
        $data = [
            'id' => $wallet,
            'data' => $data
        ];
        $curl = Curl::to($url)
            ->withData($data)
            ->withHeader('Accept: application/json')
            ->withHeader("Authorization: Bearer $token")
            ->post();
        return json_decode($curl);
    }

    /**
     * Get user access token
     *
     * @param string $user User ID
     * @param string $key User key
     * @return mixed
     */
    public static function userAccessToken($user, $key)
    {
        $url = config('wallet.url') . '/oauth/token';
        $data = [
            'grant_type' => 'password',
            'client_id' => config('wallet.password_grant.client_id'),
            'client_secret' => config('wallet.password_grant.client_secret'),
            'username' => $user,
            'password' => $key,
            'scope' => 'get-wallet credit-transactions debit-transactions get-transactions store-wallets'
        ];
        $curl = Curl::to($url)
            ->withData($data)
            ->post();
        return json_decode($curl);
    }
}

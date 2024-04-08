<?php

namespace Dotworkers\Sessions\Repositories;

use Dotworkers\Sessions\Entities\Session;

/**
 * Class SessionsRepo
 *
 * This class allows to interact with Session entity
 *
 * @package Dotworkers\Sessions\Repositories
 * @author  Eborio Linarez
 */
class SessionsRepo
{
    /**
     * Delete sessions by user ID
     *
     * @param int $user User ID
     * @return mixed
     */
    public function deleteByUser($user)
    {
        return Session::where('user_id', $user)
            ->delete();
    }

    /**
     * Find session by ID
     *
     * @param string $id Session ID
     * @param bool|null $status User status
     * @return mixed
     */
    public function find($id, $status)
    {
        $session = Session::on(config('sessions.connection'))
            ->select('users.id AS user', 'users.username', 'users.email', 'users.whitelabel_id', 'users.status',
                'users.wallet_access_token', 'users.created_at', 'profiles.first_name', 'profiles.last_name', 'profiles.gender', 'profiles.phone',
                'profiles.birth_date', 'profiles.dni', 'profiles.country_iso', 'user_currencies.currency_iso', 'user_currencies.wallet_id', 'users.tester',
                'sessions.user_agent', 'profiles.language')
            ->join('users', 'sessions.user_id', '=', 'users.id')
            ->join('profiles', 'users.id', '=', 'profiles.user_id')
            ->join('user_currencies', 'users.id', '=', 'user_currencies.user_id')
            ->where('sessions.id', $id)
            ->where('user_currencies.default', true);

        if (!is_null($status)) {
            $session->where('users.status', $status);
        }

        $sessionData = $session->first();

        if (!is_null($sessionData)) {
            $sessionData->last_activity = time();
            $sessionData->save();
        }
        return $sessionData;
    }

    /**
     * Find user by ID
     *
     * @param int $user User ID
     * @param bool|null $status User status
     * @return mixed
     */
    public function findUserByID($user, $status)
    {
        return \DB::connection(config('sessions.connection'))
            ->table('users')
            ->select('users.id AS user', 'users.username', 'users.email', 'users.whitelabel_id', 'users.status',
                'users.wallet_access_token', 'users.created_at', 'profiles.first_name', 'profiles.last_name', 'profiles.gender', 'profiles.phone',
                'profiles.birth_date', 'profiles.dni', 'profiles.country_iso', 'user_currencies.currency_iso', 'user_currencies.wallet_id', 'users.tester',
                'sessions.user_agent', 'profiles.language')
            ->join('profiles', 'users.id', '=', 'profiles.user_id')
            ->join('user_currencies', 'users.id', '=', 'user_currencies.user_id')
            ->leftJoin('sessions', 'users.id', '=', 'sessions.user_id')
            ->where('users.id', $user)
            ->where('user_currencies.default', true)
            ->where('users.status', $status)
            ->first();
    }

    /**
     * Find user by wallet
     *
     * @param int $wallet Wallet ID
     * @param bool|null $status User status
     * @return mixed
     */
    public function findUserByWallet($wallet, $status)
    {
        return \DB::connection(config('sessions.connection'))
            ->table('users')
            ->select('users.id AS user', 'users.username', 'users.email', 'users.whitelabel_id', 'users.status',
                'users.wallet_access_token', 'users.created_at', 'profiles.first_name', 'profiles.last_name', 'profiles.gender', 'profiles.phone',
                'profiles.birth_date', 'profiles.dni', 'profiles.country_iso', 'user_currencies.currency_iso', 'user_currencies.wallet_id', 'users.tester',
                'sessions.user_agent', 'profiles.language')
            ->join('profiles', 'users.id', '=', 'profiles.user_id')
            ->join('user_currencies', 'users.id', '=', 'user_currencies.user_id')
            ->leftJoin('sessions', 'users.id', '=', 'sessions.user_id')
            ->where('user_currencies.wallet_id', $wallet)
            ->where('users.status', $status)
            ->first();
    }

    /**
     * Find user by ID
     *
     * @param int $user User ID
     * @param bool|null $status User status
     * @return mixed
     */
    public function validateUserSessionByID($user, $status)
    {
        return \DB::connection(config('sessions.connection'))
            ->table('users')
            ->select('users.id AS user', 'users.username', 'users.email', 'users.whitelabel_id', 'users.status',
                'users.wallet_access_token', 'users.created_at', 'profiles.first_name', 'profiles.last_name', 'profiles.gender', 'profiles.phone',
                'profiles.birth_date', 'profiles.dni', 'profiles.country_iso', 'user_currencies.currency_iso', 'user_currencies.wallet_id', 'users.tester',
                'sessions.user_agent', 'profiles.language')
            ->join('profiles', 'users.id', '=', 'profiles.user_id')
            ->join('user_currencies', 'users.id', '=', 'user_currencies.user_id')
            ->join('sessions', 'users.id', '=', 'sessions.user_id')
            ->where('users.id', $user)
            ->where('user_currencies.default', true)
            ->where('users.status', $status)
            ->first();
    }

    /**
     * Find user by wallet
     *
     * @param int $wallet Wallet ID
     * @param bool|null $status User status
     * @return mixed
     */
    public function validateUserSessionByWallet($wallet, $status)
    {
        return \DB::connection(config('sessions.connection'))
            ->table('users')
            ->select('users.id AS user', 'users.username', 'users.email', 'users.whitelabel_id', 'users.status',
                'users.wallet_access_token', 'users.created_at', 'profiles.first_name', 'profiles.last_name', 'profiles.gender', 'profiles.phone',
                'profiles.birth_date', 'profiles.dni', 'profiles.country_iso', 'user_currencies.currency_iso', 'user_currencies.wallet_id', 'users.tester',
                'sessions.user_agent', 'profiles.language')
            ->join('profiles', 'users.id', '=', 'profiles.user_id')
            ->join('user_currencies', 'users.id', '=', 'user_currencies.user_id')
            ->join('sessions', 'users.id', '=', 'sessions.user_id')
            ->where('user_currencies.wallet_id', $wallet)
            ->where('users.status', $status)
            ->first();
    }
}

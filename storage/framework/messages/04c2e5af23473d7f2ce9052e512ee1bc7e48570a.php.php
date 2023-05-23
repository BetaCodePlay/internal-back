<?php

namespace Dotworkers\Sessions;

use Dotworkers\Sessions\Repositories\SessionsRepo;

/**
 * Class Sessions
 *
 * This class allows to manage whitelabels sessions
 *
 * @package Dotworkers\Sessions
 * @author  Eborio Linarez
 */
class Sessions
{
    /**
     * Delete session by user ID
     *
     * @param int $user User ID
     * @return mixed
     */
    public static function deleteByUser($user)
    {
        $sessionsRepo = new SessionsRepo();
        return $sessionsRepo->deleteByUser($user);
    }

    /**
     * Find sessions by ID
     *
     * @param int $id Session ID
     * @param bool|null $status User status
     * @return mixed
     */
    public static function find($id, $status = true)
    {
        $sessionsRepo = new SessionsRepo();
        return $sessionsRepo->find($id, $status);
    }

    /**
     * Find user by ID
     *
     * @param int $user User ID
     * @param bool|null $status User status
     * @return mixed
     */
    public static function findUserByID($user, $status = true)
    {
        $sessionsRepo = new SessionsRepo();
        return $sessionsRepo->findUserByID($user, $status);
    }

    /**
     * Find user by wallet ID
     *
     * @param int $wallet Wallet ID
     * @param bool|null $status User status
     * @return mixed
     */
    public static function findUserByWallet($wallet, $status = true)
    {
        $sessionsRepo = new SessionsRepo();
        return $sessionsRepo->findUserByWallet($wallet, $status);
    }

    /**
     * Validate user session by ID
     *
     * @param int $user User ID
     * @param bool|null $status User status
     * @return mixed
     */
    public static function validateUserSessionByID($user, $status = true)
    {
        $sessionsRepo = new SessionsRepo();
        return $sessionsRepo->validateUserSessionByID($user, $status);
    }

    /**
     * Validate user session by wallet ID
     *
     * @param int $wallet Wallet ID
     * @param bool|null $status User status
     * @return mixed
     */
    public static function validateUserSessionByWallet($wallet, $status = true)
    {
        $sessionsRepo = new SessionsRepo();
        return $sessionsRepo->validateUserSessionByWallet($wallet, $status);
    }
}

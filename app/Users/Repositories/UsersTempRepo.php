<?php

namespace App\Users\Repositories;

use App\Users\Entities\UserTemp;

/**
 * Class UsersRepo
 *
 * This class allows to interact with UserTemp entity
 *
 * @package App\Users\Repositories
 * @author  Eborio Linarez
 */
class UsersTempRepo
{
    /**
     * Delete user temp
     *
     * @param int $username Username
     * @return mixed
     */
    public function delete($username)
    {
        $user = UserTemp::where('username', $username)
            ->whitelabel()
            ->first();
        $user->delete();
        return $user;
    }

    /**
     * Get users
     *
     * @param $whitelabel
     * @return mixed
     */
    public function getUsers($whitelabel)
    {
        $users = UserTemp::select('users_temp.*')
            ->where('whitelabel_id', $whitelabel)
            ->orderBy('users_temp.created_at', 'DESC')
            ->get();
        return $users;
    }

    /**
     * Get user by username
     *
     * @param $whitelabel
     * @param $currency
     * @return mixed
     */
    public function getUserByUsername($whitelabel, $username)
    {
        return UserTemp::select('users_temp.*')
            ->where('whitelabel_id', $whitelabel)
            ->where('username', $username)
            ->first();
    }

    /**
     * Unique email
     *
     * @param int $id User ID
     * @param string $email User email
     * @return mixed
     */
    public function uniqueEmail($email)
    {
        $user = UserTemp::where('email', $email)
            ->whitelabel()
            ->first();
        return $user;
    }

    /**
     * Unique username
     *
     * @param string $username User username
     * @return mixed
     */
    public function uniqueUsername($username)
    {
        $user = UserTemp::where('username', $username)
            ->whitelabel()
            ->first();
        return $user;
    }
}

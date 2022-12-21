<?php


namespace Dotworkers\Configurations\Repositories;

use Dotworkers\Configurations\Entities\User;

/**
 * Class UserRepo
 *
 * This class allows to manage user entity data
 *
 * @package Dotworkers\Configurations\Repositories
 * @author  Derluin Gonzalez
 */
class UsersRepo
{
    /**
     * Find user
     *
     * @param int $id User ID
     * @return mixed
     */
    public function find($id)
    {
        $user = User::select('users.*')
            ->where('users.id', $id)
            ->first();
        return $user;
    }

    /**
     * Update user
     *
     * @param int $id User ID
     * @param array $data User data
     * @return mixed
     */
    public function update($id, $data)
    {
        $user = UsersRepo::find($id);
        $user->fill($data);
        $user->save();
        return $user;
    }
}
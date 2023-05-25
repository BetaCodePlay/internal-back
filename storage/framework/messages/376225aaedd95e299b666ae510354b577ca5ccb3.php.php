<?php

namespace Dotworkers\Bonus\Repositories;

use Dotworkers\Configurations\Entities\User;

/**
 * Class TransactionsRepo
 *
 * This class allows to manage the data of User entity
 *
 * @package Dotworkers\Bonus\Repositories
 * @author  Eborio Linarez
 */
class UsersRepo
{
    /**
     * Find user by ID
     *
     * @param int $id User ID
     * @return mixed
     */
    public function find($id)
    {
        return User::on(config('bonus.connection'))->find($id);
    }
}

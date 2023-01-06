<?php

namespace App\Users\Repositories;

use App\Users\Entities\Profile;

/**
 * Class ProfilesRepo
 *
 * This class allows to interact with Profile entity
 *
 * @package App\Users\Repositories
 * @author  Eborio Linarez
 */
class ProfilesRepo
{
    /**
     * Find
     *
     * @param int $user User ID
     * @return mixed
     */
    public function find($user)
    {
        $profile = Profile::find($user);
        return $profile;
    }

    /**
     * Update profile
     *
     * @param int $user User ID
     * @param array $data Profile data
     * @return mixed
     */
    public function update($user, $data)
    {
        $profile = Profile::find($user);
        $profile->fill($data);
        $profile->save();
        return $profile;
    }
}

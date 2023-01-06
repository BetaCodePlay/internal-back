<?php

namespace App\Users;

use App\Users\Repositories\UsersRepo;
use Dotworkers\Configurations\Configurations;

/**
 * Class Users
 *
 * This class allows to define users utils methods
 *
 * @package App\Users
 * @author  Orlando Bravo
 */
class Users
{
    /**
     * Generate Reference Code
     *
     * @param $user
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public static function generateReferenceCode($user)
    {
        $usersRepo = new UsersRepo();
        $whitelabels = Configurations::getWhitelabelName();
        $nameWhitelabel = substr($whitelabels, 0, 3);
        $userId = substr($user, 0, 3);
        $rand = rand(1, 999);
        $longRand = strlen($rand);
        if ($longRand > 2) {
            $rand = $rand;
        } elseif ($longRand > 1) {
            $rand = "0{$rand}";
        } else {
            $rand = "00{$rand}";
        }
        $referenceCode = $nameWhitelabel . $userId . $rand;
        $userData = [
            'referral_code' => $referenceCode
        ];
        $data = $usersRepo->update($user, $userData);
        return $data;
    }

    /**
     * Get avatar user
     *
     * @return mixed
     */
    public static function getAvatar()
    {
        if (auth()->check()) {
            $user = auth()->user()->id;

            try {
                $usersRepo = new UsersRepo();
                $userData = $usersRepo->find($user);
                $avatar = null;
                if (!is_null($userData->avatar)) {
                    $avatar = s3_asset("avatar/{$userData->avatar}");
                }
                return $avatar;

            } catch (\Exception $ex) {
                \Log::error(__METHOD__, ['exception' => $ex, 'user' => $user]);
                return null;
            }
        } else {
            return null;
        }
    }
}

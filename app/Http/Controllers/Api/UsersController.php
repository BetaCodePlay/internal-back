<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Users\Repositories\UsersRepo;
use Dotworkers\Configurations\Utils;
use Illuminate\Http\Request;

/**
 * Class UsersController
 *
 * This class allows to manage users requests
 *
 * @package App\Http\Controllers\Api
 * @author  Eborio Linarez
 */
class UsersController extends Controller
{
    /**
     * UsersRepo
     *
     * @var UsersRepo
     */
    private $usersRepo;

    /**
     * UsersController constructor
     *
     * @param UsersRepo $usersRepo
     */
    public function __construct(UsersRepo $usersRepo)
    {
        $this->usersRepo = $usersRepo;
    }

    /**
     * Update users
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(Request $request)
    {
        try {
            $user = $request->user;
            $this->usersRepo->update($user, $request->except('user'));
            return Utils::successResponse([]);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }
}

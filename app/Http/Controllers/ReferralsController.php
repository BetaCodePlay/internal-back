<?php

namespace App\Http\Controllers;

use App\Users\Collections\UsersCollection;
use App\Users\Repositories\UsersRepo;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Configurations\Enums\Codes;
use Dotworkers\Configurations\Utils;
use Illuminate\Http\Request;

/**
 * Class ReferralsController
 *
 * This class allows to manage referrals requests
 *
 * @package App\Http\Controllers
 * @author Carlos Hurtado
 */
class ReferralsController extends Controller
{
    /**
     * UsersRepo
     *
     * @var UsersRepo
     */
    private $usersRepo;

    /**
     * UsersCollection
     *
     * @var UsersCollection
     */
    private $usersCollection;

    /***
     * AgentsController constructor.
     *
     * @param UsersRepo $usersRepo
     * @param UsersCollection $usersCollection
     */
    public function __construct(UsersRepo $usersRepo, UsersCollection $usersCollection)
    {
        $this->usersRepo = $usersRepo;
        $this->usersCollection = $usersCollection;
    }

    /**
     * Show index
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $data['title'] = _i('List of referred users');
        $data['filter'] = _i('filter by user');
        return view('back.referrals.index', $data);
    }

    /**
     * Show add users
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function user()
    {
        $data['title'] = _i('Refer user');
        return view('back.referrals.create', $data);
    }

    /**
     * Add referral users
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function userData(Request $request)
    {
        $this->validate($request, [
            'user' => 'required',
            'user_refer' => 'required',
            'currency' => 'required',
        ]);
        try {
            $user = $request->user;
            $currency = $request->currency;
            $userRefer = $request->user_refer;
            $whitelabel = Configurations::getWhitelabel();
            $userData = $this->usersRepo->getUserByCurrency($userRefer, $currency, $whitelabel);

            if (!is_null($userData)) {
                $userReferral = $this->usersRepo->verifyReferral($userData->id);
                if (is_null($userReferral)) {
                    $this->usersRepo->updateReferralUser($userData->id, $user);
                    $data = [
                        'title' => _i('User referral'),
                        'message' => _i('User associated with the referred successfully'),
                        'close' => _i('Close')
                    ];
                    return Utils::successResponse($data);
                } else {
                    $data = [
                        'title' => _i('Existing user'),
                        'message' => _i('The user has already been referred by another user'),
                        'close' => _i('Close')
                    ];
                    return Utils::errorResponse(Codes::$forbidden, $data);
                }
            } else {
                $data = [
                    'title' => _i('User not found'),
                    'message' => _i('User not found by currency: %s ', [$currency]),
                    'close' => _i('Close')
                ];
                return Utils::errorResponse(Codes::$forbidden, $data);
            }
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     * Referral users list
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function usersList(Request $request)
    {
        try {
            $user = is_null($request->user) ? auth()->user()->id : $request->user;
            if (!is_null($user)) {
                $currency = $request->currency;
                $whitelabel = Configurations::getWhitelabel();
                $usersData = $this->usersRepo->getReferralListByUser($user, $currency, $whitelabel);
                $this->usersCollection->formatReferralList($usersData);
            } else {
                $usersData = [];
            }
            $data = [
                'users' => $usersData
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     *  Remove users to agent
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function removeReferralUserData($user)
    {
        try {
            $userReferral = $this->usersRepo->verifyReferral($user);
            if (!is_null($userReferral)) {
                $this->usersRepo->removeReferral($user);
                $data = [
                    'title' => _i('User referral'),
                    'message' => _i('User removed from referral'),
                    'close' => _i('Close')
                ];
                return Utils::successResponse($data);
            } else {
                $data = [
                    'title' => _i('User referrel'),
                    'message' => _i('The user is not referred'),
                    'close' => _i('Close')
                ];
                return Utils::errorResponse(Codes::$forbidden, $data);
            }
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'user' => $user]);
            return Utils::failedResponse();
        }
    }

    /**
     * Referral totals 
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function referralsTotals()
    {
        $data['title'] = _i('List of totals referred');
        $data['filter'] = _i('filter by user');
        return view('back.referrals.referral-totals', $data);
    }

    /**
     * Referral total list
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function referralsTotalsList(Request $request)
    {
        try {
            $startDate = $request->start_date;
            $endDate = $request->end_date;
            if (!is_null($startDate) && !is_null($endDate)) {
                $startDate = Utils::startOfDayUtc($startDate);
                $endDate = Utils::endOfDayUtc($endDate);
                $currency = $request->currency;
                $user = auth()->user()->id;
                $whitelabel = Configurations::getWhitelabel();
                $usersData = $this->usersRepo->getTotalsReferralListByUser($user, $currency, $whitelabel, $startDate, $endDate);
                $this->usersCollection->formatReferralListTotals($usersData);
            } else {
                $usersData = [];
            }
            $data = [
                'users' => $usersData
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }
}

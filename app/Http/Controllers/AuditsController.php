<?php

namespace App\Http\Controllers;

use App\Audits\Repositories\AuditsRepo;
use App\Users\Repositories\UsersRepo;
use App\Audits\Collections\AuditsCollection;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Configurations\Enums\Codes;
use Dotworkers\Configurations\Enums\Status;
use Dotworkers\Configurations\Utils;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Carbon\Carbon;


/**
 * Class AuditsController
 *
 * This class allows managing audits requests
 *
 * @package App\Http\Controllers
 * @author  Mayinis Torrealba
 */
class AuditsController extends Controller
{
    /**
     * AuditsRepo
     *
     * @var AuditsRepo
     */
    private $auditsRepo;

    /**
     * UsersRepo
     *
     * @var UsersRepo
     */
    private $usersRepo;

    /**
     * UsersRepo
     *
     * @var AuditsCollection
     */
    private $auditsCollection;

    /**
     * AuditsController constructor
     *
     * @param AuditsRepo $auditsRepo
     * @param UsersRepo $usersRepo
     * @param AuditsCollection $auditsCollection
     *
     */
    public function __construct(AuditsRepo $auditsRepo, UsersRepo $usersRepo, AuditsCollection $auditsCollection)
    {
        $this->auditsRepo = $auditsRepo;
        $this->usersRepo = $usersRepo;
        $this->auditsCollection = $auditsCollection;
    }

    /**
     * Show audits view
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        try {
            $types = $this->auditsRepo->getTypes();
            $data['title'] = _i('Audits overview');
            $data['types'] = $types;
            return view('back.audits.index', $data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    /**
     * Get audits data
     *
     * @param Request $request
     * @param null $startDate
     * @param null $endDate
     * @return Response
     */
    public function auditsData(Request $request, $startDate = null, $endDate = null)
    {
        try {
            $users = $request->users;
            $type = $request->type;
            if (!is_null($startDate) && !is_null($endDate)) {
                $timezone = session('timezone');
                $whitelabel = Configurations::getWhitelabel();
                $startDate = Utils::startOfDayUtc($startDate);
                $endDate = Utils::endOfDayUtc($endDate);
                \Log::debug($users);
                $audits = $this->auditsRepo->getAudits($whitelabel, $startDate, $endDate, $users, $type);
            } else {
                $audits = [];
            }
            $this->auditsCollection->formatSearch($audits);
            $data = [
                'audits' => $audits
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'start_date' => $startDate, 'end_date' => $endDate]);
            return Utils::failedResponse();
        }
    }
}

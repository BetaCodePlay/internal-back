<?php

namespace App\Http\Controllers;

use App\Reports\Repositories\ReportAgentRepo;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Configurations\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;


/**
 * Class AgentsController
 *
 * This class allows to manage agents requests
 *
 * @package App\Http\Controllers
 * 
 * 
 */
class AgentsReportsController extends Controller
{

    /**
     * ReportAgentRepo
     *
     * @var ReportAgentRepo
     */
    private $reportAgentRepo;

    /***
     * AgentsController constructor.
     *
     * @param ReportAgentRepo $reportAgentRepo
     */
    public function __construct(
        ReportAgentRepo $reportAgentRepo
    ) {
        $this->reportAgentRepo = $reportAgentRepo;
    }

    /**
     * Data Financial State New "for support"
     * @param $user
     * @param $startDate
     * @param $endDate
     * @return Response
     * @deprecated
     */
    public function financialStateData(
        Request $request,
        $user = null,
        $startDate = null,
        $endDate = null
    ) {
        try {
            $timezone = $request->get('timezone');

            if (is_null($user)) {
                $user = Auth::id();
            }

            $data = $this->reportAgentRepo->getClosureFinancialState(
                Utils::startOfDayUtc($startDate),
                Utils::endOfDayUtc($endDate),
                session('currency'),
                Configurations::getWhitelabel(),
                $user
            );

            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            Log::error(__METHOD__, ['exception' => $ex, 'start_date' => $startDate, 'end_date' => $endDate]);
            return Utils::failedResponse();
        }
    }

    
    /**
     * getChildrens
     * 
     * @return Response
     * 
     */
    public function getChildrens(
        Request $request
    ) {
        try {
            $timezone = $request->get('timezone');

            $user = Auth::id();

            $data = $this->reportAgentRepo->getChildrens(
                $user,
                session('currency'),
                Configurations::getWhitelabel(),
            );

            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            return $ex->getMessage();
            Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }
}

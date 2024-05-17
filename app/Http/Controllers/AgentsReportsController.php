<?php

namespace App\Http\Controllers;

use App\Core\Collections\CoreCollection;
use App\Reports\Repositories\ReportAgentRepo;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Configurations\Enums\Codes;
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

    private $reportAgentRepo;

    private $coreCollection;

    /**
     * AgentsController constructor.
     * @param CoreCollection $coreCollection
     * @param ReportAgentRepo $reportAgentRepo
     */
    public function __construct(
        CoreCollection $coreCollection,
        ReportAgentRepo $reportAgentRepo
    ) {
        $this->reportAgentRepo = $reportAgentRepo;
        $this->coreCollection  = $coreCollection;
    }


    /**
     * getTimezones
     * @return Response
     * @deprecated
     */
    public function getProviders()
    {
        try {
            $data = $this->reportAgentRepo->getProviders();
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }


    /**
     * getTimezones
     * @return Response
     * @deprecated
     */
    public function getTimezones()
    {
        try {
            $data = $this->coreCollection->formatTimezones();
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }


    /**
     * Data Financial State New "for support"
     *
     * @param Request $request
     * @param $user
     * @param $startDate
     * @param $endDate
     * @return array|Response
     */
    public function financialStateData(
        Request $request,
        $user = null,
        $startDate = null,
        $endDate = null
    ) {
        try {
            if (is_null($user)) {
                $user = Auth::id();
            }

            $data = $this->reportAgentRepo->getFinancialState(
                Utils::startOfDayUtc($startDate),
                Utils::endOfDayUtc($endDate),
                session('currency'),
                Configurations::getWhitelabel(),
                $user,
                ! is_null($request->get('timezone')) && $request->get('timezone') !== 'null' ? $request->get(
                    'timezone'
                ) : null,
                ! is_null($request->get('provider')) && $request->get('provider') !== 'null' ? $request->get(
                    'provider'
                ) : null,
                ! is_null($request->get('child')) && $request->get('child') !== 'null' ? $request->get('child') : null,
                ! is_null($request->get('text')) && $request->get('text') !== 'null' ? $request->get('text') : null,

            );

            $totalCommission = 0;
            foreach ($data as $item) {
                $totalCommission  += $item->commission;
                $item->played     = formatAmount($item->played);
                $item->won        = formatAmount($item->won);
                $item->profit     = formatAmount($item->profit);
                $item->commission = formatAmount($item->commission);
            }

            return [
                'status'          => Response::HTTP_OK,
                'code'            => Codes::$ok,
                'data'            => $data,
                'totalCommission' => formatAmount($totalCommission)
            ];
        } catch (\Exception $ex) {
            Log::error(__METHOD__, ['exception' => $ex, 'start_date' => $startDate, 'end_date' => $endDate]);
            return Utils::failedResponse();
        }
    }

    /**
     * Data Financial State New "for support"
     * @param $user
     * @param $startDate
     * @param $endDate
     * @return Response
     * @deprecated
     */
    public function financialStateByCategoryData(
        Request $request,
        $user = null,
        $startDate = null,
        $endDate = null,
        $category,
    ) {
        try {
            if (is_null($user)) {
                $user = Auth::id();
            }

            $data = $this->reportAgentRepo->getFinancialStateByCategory(
                Utils::startOfDayUtc($startDate),
                Utils::endOfDayUtc($endDate),
                session('currency'),
                Configurations::getWhitelabel(),
                $user,
                $category,
                ! is_null($request->get('timezone')) && $request->get('timezone') !== 'null' ? $request->get(
                    'timezone'
                ) : null,
                ! is_null($request->get('provider')) && $request->get('provider') !== 'null' ? $request->get(
                    'provider'
                ) : null,
                ! is_null($request->get('child')) && $request->get('child') !== 'null' ? $request->get('child') : null
            );

            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            return $ex->getMessage();
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

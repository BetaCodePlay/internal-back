<?php

namespace App\Http\Controllers;

use App\Core\Collections\CoreCollection;
use App\Reports\Repositories\ReportAgentRepo;
use App\Users\Entities\User;
use App\Users\Enums\TypeUser;
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
        $userId = null,
        $startDate = null,
        $endDate = null
    ) {
        try {
            $currency     = session('currency');
            $whitelabelId = Configurations::getWhitelabel();

            $user = is_null($userId)
                ? auth()->user()
                : User::find($userId);

            $userIds = $user->type_user == TypeUser::$player
                ? [$user?->id]
                : $this->reportAgentRepo->getIdsChildrenFromFather($user->id, $currency, $whitelabelId);

            $startDate    = Utils::startOfDayUtc($startDate);
            $endDate      = Utils::startOfDayUtc($endDate);
            $currency     = session('currency');
            $whitelabelId = Configurations::getWhitelabel();

            $timezone = ! is_null($request->get('timezone')) && $request->get('timezone') !== 'null' ? $request->get(
                'timezone'
            ) : null;

            $provider = ! is_null($request->get('provider')) && $request->get('provider') !== 'null' ? $request->get(
                'provider'
            ) : null;

            $child = ! is_null($request->get('child')) && $request->get('child') !== 'null' ? $request->get(
                'child'
            ) : null;

            $childIds = [];
            if ($child) {
                $searchChild = User::find($child);

                if ($searchChild) {
                    $childIds = $searchChild->type_user == TypeUser::$player
                        ? [$searchChild?->id]
                        : $this->reportAgentRepo->getIdsChildrenFromFather($child, $currency, $whitelabelId);
                }
            }

            $totalCommission = 0;

            $timezone = $request->filled('timezone') && $request->get('timezone') !== 'null'
                ? $request->get('timezone')
                : null;

            $category = $request->filled('text') && $request->get('text') !== 'null'
                ? $request->get('text')
                : null;

            $provider = $request->filled('provider') && $request->get('provider') !== 'null'
                ? $request->get('provider')
                : null;

            $child = $request->filled('child') && $request->get('child') !== 'null'
                ? $request->get('child')
                :
                null;

            $financialData = $this->reportAgentRepo->getCommissionByCategory(
                $child ?: $user->id,
                $currency,
                $whitelabelId,
                $startDate,
                $endDate,
                $timezone,
                $category,
                $provider
            );

            foreach ($financialData as $transaction) {
                $totalCommission         += $transaction->commission;
                $transaction->played     = formatAmount($transaction->played);
                $transaction->won        = formatAmount($transaction->won);
                $transaction->profit     = formatAmount($transaction->profit);
                $transaction->commission = formatAmount($transaction->commission);
            }

            return [
                'status'          => Response::HTTP_OK,
                'code'            => Codes::$ok,
                'data'            => $financialData,
                'totalCommission' => formatAmount($totalCommission)
            ];
        } catch (\Exception $ex) {
            Log::error(__METHOD__, ['exception' => $ex, 'start_date' => $startDate, 'end_date' => $endDate]);
            return Utils::failedResponse();
        }
    }

    /**
     * @param Request $request
     * @param $userId
     * @param $startDate
     * @param $endDate
     * @return array|Response
     */
    public function userFinancialReport(
        Request $request,
        $userId = null,
        $startDate = null,
        $endDate = null
    ) {
        try {
            $user = is_null($userId) ? auth()->user() : User::find($userId);

            //  $startDate    = Utils::startOfDayUtc($startDate);
            //  $endDate      = Utils::startOfDayUtc($endDate);
            $currency     = session('currency');
            $whitelabelId = Configurations::getWhitelabel();

            $timezone = $request->filled('timezone') && $request->get('timezone') !== 'null'
                ? $request->get('timezone')
                : null;

            $child = $request->filled('child') && $request->get('child') !== 'null'
                ? $request->get('child')
                : null;

            $startTime = $request->filled('timeStart') ? $request->input('timeStart') : '00:00:00';
            $endTime   = $request->filled('timeEnd') ? $request->input('timeEnd') : '23:59:59';
            $username  = $request->filled('text') ? $request->input('text') : '';

            $startDate = "{$startDate} {$startTime}";
            $endDate   = "{$endDate} {$endTime}";

            if ($whitelabelId === 1) {
                Log::info('Info Magda', [
                    (int)$child ?: $user->id,
                    $currency,
                    $whitelabelId,
                    $startDate,
                    $endDate,
                    $timezone,
                    $username
                ]);
            }

            $financialData = $this->reportAgentRepo->getTotalByUserFromAgent(
                (int)$child ?: $user->id,
                $currency,
                $whitelabelId,
                $startDate,
                $endDate,
                $timezone,
                $username
            );

            foreach ($financialData as $transaction) {
                $transaction->played    = formatAmount($transaction->played);
                $transaction->won       = formatAmount($transaction->won);
                $transaction->profit    = formatAmount($transaction->profit);
                $transaction->type_user = $transaction->type_user == 5 ? 'Jugador' : 'Agente';
            }

            return [
                'status' => Response::HTTP_OK,
                'code'   => Codes::$ok,
                'data'   => $financialData,
            ];
        } catch (\Exception $ex) {
            Log::error(__METHOD__, ['exception' => $ex, 'start_date' => $startDate, 'end_date' => $endDate]);
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
     * @param $category
     * @return array|string
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

            foreach ($data as $item) {
                $item->played     = formatAmount($item->played);
                $item->won        = formatAmount($item->won);
                $item->profit     = formatAmount($item->profit);
                $item->commission = formatAmount($item->commission);
            }

            return [
                'status' => Response::HTTP_OK,
                'code'   => Codes::$ok,
                'data'   => $data,
            ];
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

<?php

namespace App\Http\Controllers;

use App\Core\Collections\TransactionsCollection;
use App\Core\Repositories\TransactionsRepo;
use App\Reports\Repositories\ClosuresUsersTotalsRepo;
use App\Transactions\Services\UserTransactionService;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Configurations\Enums\ProviderTypes;
use Dotworkers\Configurations\Utils;
use Dotworkers\Wallet\Wallet;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class TransactionsController
 *
 * This class allows to manage transactions requests
 *
 * @package App\Http\Controllers
 * @author  Eborio Linárez
 */
class TransactionsController extends Controller
{
    /**
     * TransactionsRepo
     *
     * @var TransactionsRepo
     */
    private $transactionsRepo;

    /**
     * TransactionsCollection
     *
     * @var TransactionsCollection
     */
    private $transactionsCollection;

    /**
     * ClosuresUsersTotalsRepo
     *
     * @var ClosuresUsersTotalsRepo
     */
    private $closuresUserTotalsRepo;

    private $transactionsService;


    public function __construct(
        TransactionsRepo $transactionsRepo,
        TransactionsCollection $transactionsCollection,
        ClosuresUsersTotalsRepo $closuresUserTotalsRepo,
        UserTransactionService $transactionsService
    ) {
        $this->transactionsRepo       = $transactionsRepo;
        $this->transactionsCollection = $transactionsCollection;
        $this->closuresUserTotalsRepo = $closuresUserTotalsRepo;
        $this->transactionsService    = $transactionsService;
    }

    /**
     * Get Closure
     *
     *
     * @param Request $request
     * @return Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function closure(Request $request)
    {
        try {
            $startDate  = str_replace('+', ' ', $request->start_date);
            $endDate    = str_replace('+', ' ', $request->end_date);
            $whitelabel = $request->whitelabel_id;

            if (! is_null($startDate) && ! is_null($endDate) && ! is_null($whitelabel)) {
                $closure = $this->closuresUserTotalsRepo->getClosureUserTotals($startDate, $endDate, $whitelabel);
            } else {
                $closure = [];
            }
            $data = [
                'closure' => $closure
            ];

            return response()->json($data);
        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Count by type and ates
     *
     * @param int $transactionType Transaction type
     * @param string $startDate Start date to filter
     * @param string $endDate End date to filter
     * @return Response
     */
    public function countByType($transactionType, $status, $startDate, $endDate)
    {
        try {
            $whitelabel    = Configurations::getWhitelabel();
            $currency      = session('currency');
            $startDate     = Utils::startOfDayUtc($startDate);
            $endDate       = Utils::endOfDayUtc($endDate);
            $providerTypes = [ProviderTypes::$dotworkers, ProviderTypes::$payment];
            $count         = $this->transactionsRepo->countByProviderTypes(
                $whitelabel,
                $transactionType,
                $currency,
                $providerTypes,
                $startDate,
                $endDate,
                $status
            );
            $data          = [
                'count' => number_format($count)
            ];
            return Utils::successResponse($data);
        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Dashboard graphic data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function dashboardGraphicData()
    {
        $months        = 6;
        $timezone      = session('timezone');
        $endDate       = Carbon::now()->setTimezone($timezone)->format('Y-m-d');
        $startDate     = Carbon::now()->setTimezone($timezone)->subMonth($months)->format('Y-m-d');
        $newStartDate  = date("Y-m", strtotime($startDate));
        $newEndDate    = date("Y-m", strtotime($endDate));
        $period        = CarbonPeriod::create($newStartDate, $newEndDate);
        $whitelabel    = Configurations::getWhitelabel();
        $currency      = session('currency');
        $providerTypes = [ProviderTypes::$dotworkers, ProviderTypes::$payment];
        $financialData = $this->transactionsRepo->getFinancialData(
            $whitelabel,
            $currency,
            $providerTypes,
            $startDate,
            $endDate
        );
        $financial     = $this->transactionsCollection->formatDashboardGraphic($period, $financialData);
        return response()->json($financial);
    }

    /**
     * Totals by type and ates
     *
     * @param int $transactionType Transaction type
     * @param string $startDate Start date to filter
     * @param string $endDate End date to filter
     * @return Response
     */
    public function totalsByType($transactionType, $startDate, $endDate)
    : Response {
        try {
            $whitelabel    = Configurations::getWhitelabel();
            $currency      = session('currency');
            $startDate     = Utils::startOfDayUtc($startDate);
            $endDate       = Utils::endOfDayUtc($endDate);
            $providerTypes = [ProviderTypes::$dotworkers, ProviderTypes::$payment];
            $total         = $this->transactionsRepo->totalByProviderTypes(
                $whitelabel,
                $transactionType,
                $currency,
                $providerTypes,
                $startDate,
                $endDate
            );
            $data          = [
                'total' => number_format($total, 2)
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Get user transactions
     *
     * @param null $user
     * @param null $currency
     * @return Response
     */
    public function userTransactions($user = null, $currency = null)
    : Response {
        try {
            if (! is_null($user) && ! is_null($currency)) {
                $providerTypes = [ProviderTypes::$dotworkers, ProviderTypes::$payment];
                $transactions  = $this->transactionsRepo->getByUserAndProviderTypes($user, $currency, $providerTypes);
                $this->transactionsCollection->formatTransactions($transactions);
                return Utils::successResponse(['transactions' => $transactions]);
            }

            return Utils::successResponse(['transactions' => []]);
        } catch (Exception $ex) {
            Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * @param Request $request
     * @return array
     */
    public function agentsTransactions(Request $request)
    : array {
        return $this->transactionsRepo->getAgentTransactionsForDataTable($request, session('currency'));
    }

    /**
     * @param Request $request
     * @return array|mixed
     */
    public function playersTransactions(Request $request)
    : mixed {
        try {
            $userId = getUserIdByUsernameOrCurrent($request);
            $bonus  = Configurations::getBonus();
            $wallet = Wallet::getByClient($userId, session('currency'), $bonus);

            if (is_array($wallet->data)) {
                Log::info(__METHOD__ . " Error in user wallet array {$userId}", [$wallet]);
            }

            $token = session('wallet_access_token');
            $url   = config('wallet.url') . '/api/transactions/get-player-transactions-by-wallet';

            $data           = $request->all();
            $data['wallet'] = ! is_array($wallet->data) ? $wallet?->data?->wallet?->id : 0;

            $response = Http::withHeaders([
                'Accept'        => 'application/json',
                'Authorization' => 'Bearer ' . $token,
            ])->post($url, $data);

            return $response->json();
        } catch (Exception $e) {
            Log::error('Error al realizar la solicitud HTTP: ' . $e->getMessage());
            return null;
        }
    }

    public function getDailyMovementsOfChildren()
    {
        $this->transactionsRepo->getDailyMovementsOfChildren();
    }
}

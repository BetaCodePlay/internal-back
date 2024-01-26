<?php

namespace App\Http\Controllers;

use App\Audits\Repositories\AuditsRepo;
use App\BetPay\Collections\PaymentsCollection;
use App\BetPay\Collections\TransactionsCollection as BetPayTransactionsCollection;
use App\Core\Collections\ProviderTypesCollection;
use App\Core\Collections\TransactionsCollection;
use App\Core\Repositories\CoreRepo;
use App\Core\Repositories\CountriesRepo;
use App\Core\Repositories\CurrenciesRepo;
use App\Core\Repositories\ProvidersRepo;
use App\Core\Repositories\ProvidersTypesRepo;
use App\Core\Repositories\TransactionsRepo;
use App\IQSoft\Collections\IQSoftTicketsCollection;
use App\IQSoft\Repositories\IQSoftTicketsRepo;
use App\Reports\Collections\ReportsCollection;
use App\Reports\Repositories\ClosuresGamesTotalsRepo;
use App\Reports\Repositories\ClosuresUsersTotalsRepo;
use App\Reports\Repositories\ReportRepo;
use App\Users\Repositories\UsersRepo;
use App\Wallets\Collections\WalletsCollection;
use App\Whitelabels\Repositories\WhitelabelsRepo;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Configurations\Enums\PaymentMethods;
use Dotworkers\Configurations\Enums\Providers;
use Dotworkers\Configurations\Enums\ProviderTypes;
use Dotworkers\Configurations\Enums\Status;
use Dotworkers\Configurations\Enums\TransactionStatus;
use Dotworkers\Configurations\Enums\TransactionTypes;
use Dotworkers\Configurations\Utils;
use Dotworkers\Security\Enums\Roles;
use Dotworkers\Wallet\Wallet;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Ixudra\Curl\Facades\Curl;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ReportsController
 *
 * This class allows managing reports requests
 *
 * @package App\Http\Controllers
 * @author  Damelys Espinoza
 * @author  Eborio Linarez
 */
class ReportsController extends Controller
{
    /**
     * ReportsCollection
     *
     * @var ReportsCollection
     */
    private $reportsCollection;

    /**
     * ClosuresUsersTotalsRepo
     *
     * @var ClosuresUsersTotalsRepo
     */
    private $closuresUsersTotalsRepo;

    /**
     * ClosuresGamesTotalsRepo
     *
     * @var ClosuresGamesTotalsRepo
     */
    private $closuresGamesTotalsRepo;

    /**
     * UsersRepo
     *
     * @var UsersRepo
     */
    private $usersRepo;

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
     * CoreRepo
     *
     * @var CoreRepo
     */
    private $coreRepo;

    /**
     * ProvidersRepo
     *
     * @var ProvidersRepo
     */
    private $providersRepo;

    /**
     * WhitelabelsRepo
     *
     * @var WhitelabelsRepo
     */
    private $whitelabelsRepo;

    /**
     * CurrenciesRepo
     *
     * @var CurrenciesRepo
     */
    private $currenciesRepo;

    /**
     * CountriesRepo
     *
     * @var CountriesRepo
     */
    private $countriesRepo;

    /**
     * BetPayTransactionsCollection
     *
     * @var BetPayTransactionsCollection
     */
    private $betPayTransactionsCollection;

    /**
     * WalletsCollection
     *
     * @var WalletsCollection
     */
    private $walletsCollection;

    /**
     * BetPay URL
     *
     * @var string
     */
    private $betPayURL;

    /**
     * ProvidersTypesRepo
     *
     * @var ProvidersTypesRepo
     */
    private $providersTypesRepo;

    /**
     * ProviderTypesCollection
     *
     * @var ProviderTypesCollection
     */
    private $providerTypesCollection;

    /**
     * @var ReportRepo
     */
    private ReportRepo $reportRepo;


    /**
     * ReportsController constructor.
     *
     * @param ClosuresUsersTotalsRepo $closuresUsersTotalsRepo
     * @param ClosuresGamesTotalsRepo $closuresGamesTotalsRepo
     * @param ReportsCollection $reportsCollection
     * @param CountriesRepo $countriesRepo
     * @param UsersRepo $usersRepo
     * @param TransactionsRepo $transactionsRepo
     * @param TransactionsCollection $transactionsCollection
     * @param ProvidersRepo $providersRepo
     * @param WhitelabelsRepo $whitelabelsRepo
     * @param CurrenciesRepo $currenciesRepo
     * @param CoreRepo $coreRepo
     * @param BetPayTransactionsCollection $betPayTransactionsCollection
     * @param WalletsCollection $walletsCollection
     * @param ProvidersTypesRepo $providersTypesRepo
     * @param ProviderTypesCollection $providerTypesCollection
     */
    public function __construct(
        ClosuresUsersTotalsRepo $closuresUsersTotalsRepo,
        ClosuresGamesTotalsRepo $closuresGamesTotalsRepo,
        ReportsCollection $reportsCollection,
        CountriesRepo $countriesRepo,
        UsersRepo $usersRepo,
        TransactionsRepo $transactionsRepo,
        TransactionsCollection $transactionsCollection,
        ProvidersRepo $providersRepo,
        WhitelabelsRepo $whitelabelsRepo,
        CurrenciesRepo $currenciesRepo,
        CoreRepo $coreRepo,
        BetPayTransactionsCollection $betPayTransactionsCollection,
        WalletsCollection $walletsCollection,
        ProvidersTypesRepo $providersTypesRepo,
        ProviderTypesCollection $providerTypesCollection,
        ReportRepo $reportRepo,
    ) {
        $this->closuresUsersTotalsRepo      = $closuresUsersTotalsRepo;
        $this->reportsCollection            = $reportsCollection;
        $this->closuresGamesTotalsRepo      = $closuresGamesTotalsRepo;
        $this->countriesRepo                = $countriesRepo;
        $this->usersRepo                    = $usersRepo;
        $this->transactionsRepo             = $transactionsRepo;
        $this->transactionsCollection       = $transactionsCollection;
        $this->coreRepo                     = $coreRepo;
        $this->providersRepo                = $providersRepo;
        $this->whitelabelsRepo              = $whitelabelsRepo;
        $this->currenciesRepo               = $currenciesRepo;
        $this->betPayTransactionsCollection = $betPayTransactionsCollection;
        $this->walletsCollection            = $walletsCollection;
        $this->providersTypesRepo           = $providersTypesRepo;
        $this->providerTypesCollection      = $providerTypesCollection;
        $this->betPayURL                    = env('BETPAY_SERVER') . '/api';
        $this->reportRepo                   = $reportRepo;
    }

    /**
     * Show bonus transactions report
     *
     * @return \Illuminate\Contracts\Foundation\Application|Factory|View
     */
    public function bonusTransactions()
    {
        $data['title'] = _i('Bonus transactions');
        return view('back.reports.financial.bonus-transactions', $data);
    }

    /**
     * Get bonus transactions data
     *
     * @param null|string $startDate Start date to filter
     * @param null|string $endDate End date to filter
     * @return Response
     */
    public function bonusTransactionsData($startDate = null, $endDate = null)
    {
        try {
            if (!is_null($startDate) && !is_null($endDate)) {
                $startDate = Utils::startOfDayUtc($startDate);
                $endDate = Utils::endOfDayUtc($endDate);
                $whitelabel = Configurations::getWhitelabel();
                $currency = session('currency');
                $providers = [Providers::$bonus];
                $transactions = $this->transactionsRepo->getByTransactionTypeAndProviders($whitelabel, $currency, TransactionTypes::$credit, $providers, $startDate, $endDate, TransactionStatus::$approved);
            } else {
                $transactions = [];
            }
            $transactionsData = $this->transactionsCollection->formatDepositsAndWithdrawals($transactions);
            return Utils::successResponse($transactionsData);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'start_date' => $startDate, 'end_date' => $endDate]);
            return Utils::failedResponse();
        }
    }

    /**
     *  Show sales daily through the web
     *
     * @return Factory|View
     */
    public function dailySales()
    {
        $years = 2;
        $endYear = Carbon::now()->format('Y');
        $startYear = Carbon::now()->subYear($years)->format('Y');
        $data['title'] = _i('Sales daily');
        $data['start_year'] = $startYear;
        $data['end_year'] = $endYear;
        return view('back.reports.financial.daily-sales', $data);
    }

    /**
     * Get sales daily data
     *
     * @param Request $request
     * @return Response
     */
    public function dailySalesData(Request $request)
    {
        if (count($request->all()) > 1) {
            $this->validate($request, [
                'month' => 'required',
                'year' => 'required',
                'convert' => 'required_without:currency'
            ]);
        }

        try {
            $year = $request->year;
            $month = $request->month;
            $convert = $request->convert;
            $currency = $request->currency;
            $startDate = null;
            $endDate = null;
            $whitelabel = Configurations::getWhitelabel();
            $period = [];

            if (!is_null($year) && !is_null($month)) {
                if (is_null($currency) && is_null($convert)) {
                    $sales = [];
                } else {
                    $timezone = session('timezone');
                    $startDate = "{$year}-{$month}";
                    $endDate = "{$year}-{$month}";
                    $startDate = Carbon::createFromFormat('Y-m', $startDate)->startOfMonth();
                    $endDate = Carbon::createFromFormat('Y-m', $endDate)->endOfMonth();
                    $startDate = Utils::startOfDayUtc($startDate, $originalFormat = 'Y-m-d H:i:s');
                    $endDate = Utils::endOfDayUtc($endDate, $originalFormat = 'Y-m-d H:i:s');
                    $period = CarbonPeriod::create($startDate, $endDate);
                    $sales = $this->transactionsRepo->getSalesData($currency, $startDate, $endDate, $whitelabel, $timezone);
                }
            } else {
                $sales = [];
            }
            $salesData = $this->transactionsCollection->formatDailySales($sales, $period, $convert, $currency, $startDate, $endDate, $whitelabel);
            return Utils::successResponse($salesData);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all(), 'year' => $year, 'month' => $month]);
            return Utils::failedResponse();
        }
    }

    /**
     * @return Response
     */
    public function dashboard()
    : Response
    {
        return Utils::successResponse($this->reportRepo->dashboard());
    }

    /**
     * Show deposits report
     *
     * @return \Illuminate\Contracts\Foundation\Application|Factory|View
     */
    public function deposits()
    {
        $data['title'] = _i('Deposits');
        return view('back.reports.financial.deposits', $data);
    }

    /**
     * Get deposits and withdrawals data
     *
     * @param Request $request
     * @param null|string $startDate Start date to filter
     * @param null|string $endDate End date to filter
     * @param null|int $transactionType Transaction type ID
     * @return Response
     */
    public function depositsWithdrawalsData(Request $request, $startDate = null, $endDate = null, $transactionType = null)
    {
        try {
            $status = $request->status;
            $currency = session('currency');
            if (!is_null($startDate) && !is_null($endDate) && !is_null($status)) {
                $startDate = Utils::startOfDayUtc($startDate);
                $endDate = Utils::endOfDayUtc($endDate);
                $whitelabel = Configurations::getWhitelabel();
                $providerTypes = [ProviderTypes::$payment];
                $transactions = $this->transactionsRepo->getByTransactionTypeAndProviderTypes($whitelabel, $currency, $transactionType, $providerTypes, $startDate, $endDate, $status);
            } else {
                $transactions = [];
            }
            $transactionsData = $this->transactionsCollection->formatDepositsAndWithdrawals($transactions, $currency);
            return Utils::successResponse($transactionsData);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'start_date' => $startDate, 'end_date' => $endDate]);
            return Utils::failedResponse();
        }
    }

    /**
     *  Show view games played by user report
     *
     * @return Factory|View
     */
    public function gamesPlayedByUser($provider)
    {
        $data['provider'] = $provider;
        $data['title'] = _i('Games played by user') . ' | ' . Providers::getName($provider);
        return view('back.reports.products.games-played-by-user', $data);
    }

    /**
     * Get games played by user report data
     *
     * @param Request $request
     * @param null|string $startDate Start date to filter
     * @param null|string $endDate End date to filter
     * @param null|int $provider Provider ID
     * @param null|int $game Game ID
     * @return Response
     */
    public function gamesPlayedByUserData(Request $request, $startDate = null, $endDate = null, $provider = null)
    {
        try {
            if (!is_null($startDate) && !is_null($endDate)) {
                $startDate = Utils::startOfDayUtc($startDate);
                $endDate = Utils::endOfDayUtc($endDate);
                $whitelabel = Configurations::getWhitelabel();
                $currency = session('currency');
                $usersBets = $this->closuresUsersTotalsRepo->getUsersPlayed($whitelabel, $startDate, $endDate, $currency, $provider);
            } else {
                $usersBets = [];
            }
            $data = $this->reportsCollection->usersBets($usersBets);
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all(), 'start_date' => $startDate, 'end_date' => $endDate]);
            return Utils::failedResponse();
        }
    }

    /**
     * Get games report data
     *
     * @param Request $request
     * @param null|string $startDate Start date to filter
     * @param null|string $endDate End date to filter
     * @param null|int $provider Provider ID
     * @return Response
     */
    public function gamesTotalsData(Request $request, $startDate = null, $endDate = null, $provider = null)
    {
        try {
            $nowGamesTotals = null;

            if (!is_null($startDate) && !is_null($endDate)) {
                $whitelabel = Configurations::getWhitelabel();
                $currency = session('currency');
                $timezone = session('timezone');
                $today = Carbon::now()->setTimezone($timezone);
                $endDateOriginal = $endDate;
                $startDate = Utils::startOfDayUtc($startDate);
                $endDate = Utils::endOfDayUtc($endDate);
                $gamesTotals = $this->closuresGamesTotalsRepo->getGamesTotals($whitelabel, $startDate, $endDate, $currency, $provider);

                if ($endDateOriginal == $today->copy()->format('Y-m-d')) {
                    if (
                        $provider == Providers::$patagonia ||
                        $provider == Providers::$pg_soft ||
                        $provider == Providers::$booongo ||
                        $provider == Providers::$game_art ||
                        $provider == Providers::$booming_games ||
                        $provider == Providers::$kiron_interactive ||
                        $provider == Providers::$hacksaw_gaming ||
                        $provider == Providers::$triple_cherry ||
                        $provider == Providers::$espresso_games
                    ) {
                        $provider = Providers::$salsa_gaming;
                    }

                    if ($provider == Providers::$spinmatic) {
                        $provider = Providers::$golden_race;
                    }

                    $nowStartDate = $today->copy()->setTimezone('UTC')->startOfHour();
                    $nowEndDate = $today->copy()->setTimezone('UTC')->endOfHour();
                    $providerData = $this->providersRepo->find($provider);
                    $nowGamesTotals = $this->coreRepo->getGamesTotals($whitelabel, $nowStartDate, $nowEndDate, $currency, $providerData->tickets_table, $provider);
                }
            } else {
                $gamesTotals = [];
            }
            $totals = $this->reportsCollection->gamesTotals($gamesTotals, $nowGamesTotals);
            return Utils::successResponse($totals);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all(), 'start_date' => $startDate, 'end_date' => $endDate]);
            return Utils::failedResponse();
        }
    }

    /**
     * Show view games totals report
     *
     * @return Factory|View
     */
    public function gamesTotals($provider)
    {
        $data['provider'] = $provider;
        $data['title'] = _i('Game totals report') . ' | ' . Providers::getName($provider);
        return view('back.reports.products.games-totals', $data);
    }

    /**
     * IQ Soft tickets report
     *
     * @return Factory|View
     */
    public function iqSoftTickets()
    {
        $data['title'] = _i('IQ Soft tickets');
        return view('back.reports.iq-soft.tickets', $data);
    }

    /**
     * Get IQ Soft tickets data
     *
     * @param IQSoftTicketsRepo $iqSoftTicketsRepo
     * @param IQSoftTicketsCollection $iqSoftTicketsCollection
     * @param null|string $startDate Start date to filter
     * @param null|string $endDate End date to filter
     * @return Response
     */
    public function iqSoftTicketsData(IQSoftTicketsRepo $iqSoftTicketsRepo, IQSoftTicketsCollection $iqSoftTicketsCollection, $startDate = null, $endDate = null)
    {
        try {
            if (!is_null($startDate) && !is_null($endDate)) {
                $startDate = Utils::startOfDayUtc($startDate);
                $endDate = Utils::endOfDayUtc($endDate);
                $whitelabel = Configurations::getWhitelabel();
                $currency = session('currency');
                $tickets = $iqSoftTicketsRepo->getByDates($whitelabel, $currency, $startDate, $endDate);
            } else {
                $tickets = [];
            }

            $iqSoftTicketsCollection->formatAll($tickets);
            $data = [
                'tickets' => $tickets
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'start_date' => $startDate, 'end_date' => $endDate]);
            return Utils::failedResponse();
        }
    }

    /**
     * Show JustPay credit report
     *
     * @return Factory|View
     */
    public function justPayCredit()
    {
        $data['title'] = _i('Credit transactions');
        return view('back.reports.justpay.credit', $data);
    }

    /**
     * Get JustPay credit data
     *
     * @param null $startDate
     * @param null $endDate
     * @return Response
     */
    public function justPayCreditData($startDate = null, $endDate = null)
    {
        $requestData = null;
        $curl = null;

        try {
            if (!is_null($startDate) && !is_null($endDate)) {
                $startDate = Utils::startOfDayUtc($startDate);
                $endDate = Utils::endOfDayUtc($endDate);
                $status = TransactionStatus::$approved;
                $requestData = [
                    'transaction_type' => TransactionTypes::$credit,
                    'status' => $status,
                    'payment_method' => PaymentMethods::$just_pay,
                    'start_date' => $startDate,
                    'end_date' => $endDate
                ];
                $betPayToken = session('betpay_client_access_token');
                $url = "{$this->betPayURL}/transactions/all";
                $curl = Curl::to($url)
                    ->withData($requestData)
                    ->withHeader('Accept: application/json')
                    ->withHeader("Authorization: Bearer $betPayToken")
                    ->get();
                $response = json_decode($curl);

                if ($response->status == Status::$ok) {
                    $transactions = $response->data->transactions;
                    $data = $this->betPayTransactionsCollection->formatCreditTransactionsReport($transactions, PaymentMethods::$just_pay, $status);
                    return Utils::successResponse($data);
                } else {
                    return Utils::errorResponse($response->code, $response->data);
                }
            } else {
                $totals['total'] = number_format(0, 2);
                $data = [
                    'transactions' => [],
                    'totals' => $totals
                ];
                return Utils::successResponse($data);
            }
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'curl' => $curl, 'curl_request' => $requestData]);
            return Utils::failedResponse();
        }
    }

    /**
     * Show JustPay debit report
     *
     * @return Factory|View
     */
    public function justPayDebit()
    {
        $data['title'] = _i('Debit transactions');
        return view('back.reports.justpay.debit', $data);
    }

    /**
     * Get JustPay debit data
     *
     * @param null $startDate
     * @param null $endDate
     * @return Response
     */
    public function justPayDebitData($startDate = null, $endDate = null)
    {
        try {
            if (!is_null($startDate) && !is_null($endDate)) {
                $startDate = Utils::startOfDayUtc($startDate);
                $endDate = Utils::endOfDayUtc($endDate);
                $status = TransactionStatus::$approved;
                $requestData = [
                    'transaction_type' => TransactionTypes::$debit,
                    'status' => $status,
                    'payment_method' => PaymentMethods::$just_pay,
                    'start_date' => $startDate,
                    'end_date' => $endDate
                ];
                $betPayToken = session('betpay_client_access_token');
                $url = "{$this->betPayURL}/transactions/all";
                $curl = Curl::to($url)
                    ->withData($requestData)
                    ->withHeader('Accept: application/json')
                    ->withHeader("Authorization: Bearer $betPayToken")
                    ->get();
                $response = json_decode($curl);

                if ($response->status == Status::$ok) {
                    $transactions = $response->data->transactions;
                    $data = $this->betPayTransactionsCollection->formatDebitTransactionsReport($transactions, PaymentMethods::$just_pay, $status);
                    return Utils::successResponse($data);
                } else {
                    return Utils::errorResponse($response->code, $response->data);
                }
            } else {
                $totals['total'] = number_format(0, 2);
                $data = [
                    'transactions' => [],
                    'totals' => $totals
                ];
                return Utils::successResponse($data);
            }
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'curl' => $curl, 'curl_request' => $requestData]);
            return Utils::failedResponse();
        }
    }

    /**
     * Show deposits report
     *
     * @return \Illuminate\Contracts\Foundation\Application|Factory|View
     */
    public function manualAdjustments()
    {
        $data['title'] = _i('Manual adjustments');
        $data['whitelabels'] = $this->whitelabelsRepo->all();
        return view('back.reports.financial.manual-adjustments', $data);
    }

    /**
     * Get manual transactions data
     *
     * @param Request $request
     * @return Response
     */
    public function manualAdjustmentsData(Request $request)
    {
        try {
            $startDate = $request->start_date;
            $endDate = $request->end_date;
            $transactionType = $request->transactionType;
            $currency = $request->currency;
            $whitelabel = $request->whitelabel;
            if (!is_null($startDate) && !is_null($endDate)) {
                $startDate = Utils::startOfDayUtc($startDate);
                $endDate = Utils::endOfDayUtc($endDate);
                $providers = [Providers::$manual_adjustments];
                $transactions = $this->transactionsRepo->getManualAdjustmentsByTransactionTypeAndProviders($whitelabel, $currency, $transactionType, $providers, $startDate, $endDate, TransactionStatus::$approved);
            } else {
                $transactions = [];
            }
            $transactionsData = $this->transactionsCollection->formatManualAdjustmentsDepositsAndWithdrawals($transactions);
            return Utils::successResponse($transactionsData);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'start_date' => $startDate, 'end_date' => $endDate]);
            return Utils::failedResponse();
        }
    }

    /**
     * Show deposits report
     *
     * @return \Illuminate\Contracts\Foundation\Application|Factory|View
     */
    public function manualAdjustmentsWhitelabel()
    {
        $data['title'] = _i('Manual adjustments');
        return view('back.reports.financial.manual-adjustments-whitelabel', $data);
    }

    /**
     * Get manual transactions data
     *
     * @param Request $request
     * @return Response
     */
    public function manualAdjustmentsWhitelabelData(Request $request)
    {
        try {
            $startDate = $request->start_date;
            $endDate = $request->end_date;
            $transactionType = $request->transactionType;
            $currency = $request->currency;
            $whitelabel = Configurations::getWhitelabel();
            if (!is_null($startDate) && !is_null($endDate)) {
                $startDate = Utils::startOfDayUtc($startDate);
                $endDate = Utils::endOfDayUtc($endDate);
                $providers = [Providers::$manual_adjustments];
                $transactions = $this->transactionsRepo->getManualAdjustmentsByTransactionTypeAndProviders($whitelabel, $currency, $transactionType, $providers, $startDate, $endDate, TransactionStatus::$approved);
            } else {
                $transactions = [];
            }
            $transactionsData = $this->transactionsCollection->formatManualAdjustmentsDepositsAndWithdrawals($transactions);
            return Utils::successResponse($transactionsData);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'start_date' => $startDate, 'end_date' => $endDate]);
            return Utils::failedResponse();
        }
    }

    /**
     * Show deposits report
     *
     * @return \Illuminate\Contracts\Foundation\Application|Factory|View
     */
    public function manualTransactions()
    {
        $data['title'] = _i('Manual transactions');
        return view('back.reports.financial.manual-transactions', $data);
    }

    /**
     * Get manual transactions data
     *
     * @param string|null $startDate Start date to filter
     * @param string|null $endDate End date to filter
     * @param int|null $transactionType Transaction type ID
     * @return Response
     */
    public function manualTransactionsData(string $startDate = null, string $endDate = null, int $transactionType = null)
    {
        try {
            $currency = session('currency');
            if (!is_null($startDate) && !is_null($endDate)) {
                $startDate = Utils::startOfDayUtc($startDate);
                $endDate = Utils::endOfDayUtc($endDate);
                $whitelabel = Configurations::getWhitelabel();
                $providers = [Providers::$dotworkers];
                $transactions = $this->transactionsRepo->getByTransactionTypeAndProviders($whitelabel, $currency, $transactionType, $providers, $startDate, $endDate, TransactionStatus::$approved);
            } else {
                $transactions = [];
            }
            $transactionsData = $this->transactionsCollection->formatDepositsAndWithdrawals($transactions, $currency);
            return Utils::successResponse($transactionsData);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'start_date' => $startDate, 'end_date' => $endDate]);
            return Utils::failedResponse();
        }
    }

    /**
     *  Show sales daily through the web
     *
     * @return Factory|View
     */
    public function monthlySales()
    {
        $years = 2;
        $endYear = Carbon::now()->format('Y');
        $startYear = Carbon::now()->subYear($years)->format('Y');
        $data['title'] = _i('Monthly sales');
        $data['start_year'] = $startYear;
        $data['end_year'] = $endYear;
        return view('back.reports.financial.monthly-sales', $data);
    }

    /**
     * Get sales daily data
     *
     * @param Request $request
     * @return Response
     */
    public function monthlySalesData(Request $request)
    {
        if (count($request->all()) > 1) {
            $this->validate($request, [
                'month' => 'required',
                'year' => 'required',
                'convert' => 'required_without:currency'
            ]);
        }

        try {
            $year = $request->year;
            $month = $request->month;
            $convert = $request->convert;
            $currency = $request->currency;
            $startDate = null;
            $endDate = null;
            $whitelabel = Configurations::getWhitelabel();
            $period = [];
            if (!is_null($year) && !is_null($month)) {
                if (is_null($currency) && is_null($convert)) {
                    $sales = [];
                } else {
                    $timezone = session('timezone');
                    $months = explode(',', $month);
                    if (count($months) == 2) {
                        $startDate = "{$year}-{$months[0]}";
                        $endDate = "{$year}-{$months[1]}";
                        $startDate = Carbon::createFromFormat('!Y-m', $startDate)->startOfMonth();
                        $endDate = Carbon::createFromFormat('!Y-m', $endDate)->endOfMonth();
                        $startDate = Utils::startOfDayUtc($startDate, $originalFormat = 'Y-m-d H:i:s');
                        $endDate = Utils::endOfDayUtc($endDate, $originalFormat = 'Y-m-d H:i:s');
                    }

                    if (count($months) == 1) {
                        $startDate = "{$year}-{$months[0]}";
                        $endDate = "{$year}-{$months[0]}";
                        $startDate = Carbon::createFromFormat('!Y-m', $startDate)->startOfMonth();
                        $endDate = Carbon::createFromFormat('!Y-m', $endDate)->endOfMonth();
                        $startDate = Utils::startOfDayUtc($startDate, $originalFormat = 'Y-m-d H:i:s');
                        $endDate = Utils::endOfDayUtc($endDate, $originalFormat = 'Y-m-d H:i:s');
                    }
                    $period = CarbonPeriod::create($startDate, $endDate);
                    $sales = $this->transactionsRepo->getSalesData($currency, $startDate, $endDate, $whitelabel, $timezone);
                }
            } else {
                $sales = [];
            }
            $monthlySales = $this->transactionsCollection->formatMonthlySales($sales, $period, $convert, $currency, $startDate, $endDate, $whitelabel);
            return Utils::successResponse($monthlySales);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all(), 'year' => $year, 'month' => $month]);
            return Utils::failedResponse();
        }
    }

    /**
     * Get most played by providers report data
     *
     * @param Request $request
     * @param null|string $startDate Start date to filter
     * @param null|string $endDate End date to filter
     * @param null|int $provider Provider ID
     * @return Response
     */
    public function mostPlayedByProvidersData(Request $request, $startDate = null, $endDate = null, $currency = null)
    {
        try {
            if (!is_null($startDate) && !is_null($endDate)) {
                $startDate = Utils::startOfDayUtc($startDate);
                $endDate = Utils::endOfDayUtc($endDate);
                $whitelabel = Configurations::getWhitelabel();
                $mostPlayedByProvider = $this->closuresUsersTotalsRepo->closuresTotalsByProviders($whitelabel, $startDate, $endDate, $currency);
            } else {
                $mostPlayedByProvider = [];
            }
            $this->reportsCollection->mostPlayedByProviders($mostPlayedByProvider);
            $data = [
                'games' => $mostPlayedByProvider
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all(), 'start_date' => $startDate, 'end_date' => $endDate]);
            return Utils::failedResponse();
        }
    }

    /**
     * Show view most played by providers report
     *
     * @return Factory|View
     */
    public function mostPlayedByProviders()
    {
        $data['title'] = _i('Most played by providers report');
        return view('back.reports.products.most-played-by-providers', $data);
    }

    /**
     * Get most played report data
     *
     * @param Request $request
     * @param null|string $startDate Start date to filter
     * @param null|string $endDate End date to filter
     * @param null|int $provider Provider ID
     * @return Response
     */
    public function mostPlayedGamesData(Request $request, $startDate = null, $endDate = null, $provider = null)
    {
        try {
            if (!is_null($startDate) && !is_null($endDate)) {
                if (
                    $provider == Providers::$patagonia ||
                    $provider == Providers::$pg_soft ||
                    $provider == Providers::$booongo ||
                    $provider == Providers::$game_art ||
                    $provider == Providers::$booming_games ||
                    $provider == Providers::$kiron_interactive ||
                    $provider == Providers::$hacksaw_gaming ||
                    $provider == Providers::$triple_cherry ||
                    $provider == Providers::$espresso_games
                ) {
                    $provider = Providers::$salsa_gaming;
                }

                if ($provider == Providers::$spinmatic) {
                    $provider = Providers::$golden_race;
                }

                $startDate = Utils::startOfDayUtc($startDate);
                $endDate = Utils::endOfDayUtc($endDate);
                $whitelabel = Configurations::getWhitelabel();
                $currency = session('currency');

                $providerData = $this->providersRepo->find($provider);
                $mostPlayedGames = $this->coreRepo->getMostPlayedGames($whitelabel, $startDate, $endDate, $currency, $providerData->tickets_table);
            } else {
                $mostPlayedGames = [];
            }
            $this->reportsCollection->mostPlayedGames($mostPlayedGames);
            $data = [
                'games' => $mostPlayedGames
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all(), 'start_date' => $startDate, 'end_date' => $endDate]);
            return Utils::failedResponse();
        }
    }

    /**
     * Show view most played games report
     *
     * @param int $provider Provider ID
     * @return Factory|View
     */
    public function mostPlayedGames(int $provider)
    {
        $data['provider'] = $provider;
        $data['title'] = _i('Most played games report');
        return view('back.reports.products.most-played-games', $data);
    }

    /***
     * Show view payment totals report
     *
     * @return Factory|View
     */
    public function paymentMethodsTotals()
    {
        try {
            $paymentMethods = session('payment_methods');
            $paymentMethodsData = [];

            foreach ($paymentMethods as $paymentMethod) {
                $paymentMethodsData[] = [
                    'id' => $paymentMethod->payment_method_id,
                    'name' => $paymentMethod->name
                ];
            }
            $uniquePaymentMethods = collect($paymentMethodsData)->unique()->values()->all();
            $whitelabel = Configurations::getWhitelabel();
            $data['currency_client'] = Configurations::getCurrenciesByWhitelabel($whitelabel);
            $data['currencies'] = Configurations::getCurrencies();
            $data['payment_methods'] = $uniquePaymentMethods;
            $data['title'] = _i('Totals by payment method');
            return view('back.reports.payment-methods.totals', $data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request']);
            abort(500);
        }
    }

    /***
     * Get payment totals report data
     *
     * @param null $startDate
     * @param null $endDate
     * @param Request $request
     * @return Response
     */
    public function paymentMethodsTotalsData(Request $request, $startDate = null, $endDate = null)
    {
        $requestData = null;
        $curl = null;

        try {
            $currency = $request->currency;

            if (!is_null($request->startDate) || !is_null($request->endDate)) {
                $startDate = Utils::startOfDayUtc($startDate);
                $endDate = Utils::endOfDayUtc($endDate);
                $paymentMethod = $request->payment_method;

                $requestData = [
                    'currency' => $currency,
                    'payment_method' => $paymentMethod,
                    'start_date' => $startDate,
                    'end_date' => $endDate
                ];
                $betPayToken = session('betpay_client_access_token');
                $url = "{$this->betPayURL}/transactions/payment-method";
                $curl = Curl::to($url)
                    ->withData($requestData)
                    ->withHeader('Accept: application/json')
                    ->withHeader("Authorization: Bearer $betPayToken")
                    ->get();
                $response = json_decode($curl);

                if ($response->status == Status::$ok) {
                    $totalsData = $response->data->transactions;
                } else {
                    return Utils::errorResponse($response->code, $response->data);
                }
            } else {
                $totalsData = [];
            }
            $totals = $this->transactionsCollection->formatPaymentMethods($totalsData);
            return Utils::successResponse($totals);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'curl' => $curl, 'curl_request' => $requestData]);
            abort(500);
        }
    }

    /**
     * Get products totals report data
     *
     * @param null $startDate
     * @param null $endDate
     * @return Response
     */
    public function productsTotalsData(Request $request, $startDate = null, $endDate = null)
    {
        if (count($request->all()) > 1) {
            $this->validate($request, [
                'convert' => 'required_without:currency'
            ]);
        }

        try {
            $whitelabel = Configurations::getWhitelabel();
            $currency = $request->currency;
            $convert = $request->convert;

            if (!is_null($startDate) && !is_null($endDate)) {
                $startDate = Utils::startOfDayUtc($startDate);
                $endDate = Utils::endOfDayUtc($endDate);
                $providerType = $request->type;
                $provider = $request->provider;

                if (!empty($provider)) {
                    $providerData = $this->providersRepo->find($provider);
                    $providerType = $providerData->provider_type_id;
                }

                $totals = $this->closuresUsersTotalsRepo->getProductsTotals($whitelabel, $startDate, $endDate, $currency, $providerType, $provider);
                $lastDate = Carbon::today()->subDays(30);
                $today = Carbon::today();
                $lastDate = Utils::startOfDayUtc($lastDate, $originalFormat = 'Y-m-d H:i:s');
                $today = Utils::endOfDayUtc($today, $originalFormat = 'Y-m-d H:i:s');
                $users = $this->closuresUsersTotalsRepo->getProductsUsers($whitelabel, $startDate, $endDate, $currency, $providerType, $provider);
                $bets = $this->closuresUsersTotalsRepo->getProductsBets($whitelabel, $startDate, $endDate, $currency, $providerType, $provider);
                $latestUsers = $this->closuresUsersTotalsRepo->getProductsUsers($whitelabel, $lastDate, $today, $currency, $providerType, $provider);
                $latestBets = $this->closuresUsersTotalsRepo->getProductsBets($whitelabel, $lastDate, $today, $currency, $providerType, $provider);
            } else {
                $totals = [];
                $users = [];
                $bets = [];
                $latestUsers = [];
                $latestBets = [];
            }
            $data = $this->reportsCollection->productsTotals($totals, $users, $latestUsers, $bets, $latestBets, $convert, $currency, $startDate, $endDate);
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all(), 'start_date' => $startDate, 'end_date' => $endDate]);
            return Utils::failedResponse();
        }
    }

    /***
     * Show view products totals report
     *
     * @return Factory|View
     */
    public function productsTotals()
    {
        $whitelabel = Configurations::getWhitelabel();
        $currency = session('currency');
        $providers = $this->providersRepo->getByWhitelabel($whitelabel, $currency);
        $types = [ProviderTypes::$casino, ProviderTypes::$live_casino, ProviderTypes::$virtual, ProviderTypes::$live_games, ProviderTypes::$poker];
        $providersTypes = $this->providersTypesRepo->getByIds($types);
        $this->providerTypesCollection->formatProviderTypes($providersTypes);
        $data['currencies'] = Configurations::getCurrencies();
        $data['providers'] = $providers;
        $data['providers_types'] = $providersTypes;
        $data['title'] = _i('Products totals');
        return view('back.reports.products.totals', $data);
    }

    /**
     * Get products totals overview report data
     *
     * @param null $startDate
     * @param null $endDate
     * @return Response
     */
    public function productsTotalsOverviewData(Request $request, $startDate = null, $endDate = null)
    {
        if (count($request->all()) > 1) {
            $this->validate($request, [
                'convert' => 'required_without:currency'
            ]);
        }
        try {
            $convert = $request->convert;
            $currency = $request->currency;
            $vesRate = $request->ves_rate;
            $arsRate = $request->ars_rate;

            if (!is_null($startDate) || !is_null($endDate)) {
                $startDate = Utils::startOfDayUtc($startDate);
                $endDate = Utils::endOfDayUtc($endDate);
                $provider = $request->provider;
                $totals = $this->closuresUsersTotalsRepo->getProductsTotalsOverview($startDate, $endDate, $currency, $provider);
            } else {
                $totals = [];
            }
            $totalsData = $this->reportsCollection->productsTotalsOverview($totals, $convert, $currency, $vesRate, $arsRate);
            $data = [
                'totals' => $totalsData
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'start_date' => $startDate, 'end_date' => $endDate]);
            return Utils::failedResponse();
        }
    }

    /***
     * Show view products totals overview report
     *
     * @return Factory|View
     */
    public function productsTotalsOverview()
    {
        $currencies = $this->currenciesRepo->all();
        $data['currencies'] = $currencies;
        $data['providers'] = $this->providersRepo->getByTypes([ProviderTypes::$casino, ProviderTypes::$live_casino, ProviderTypes::$virtual, ProviderTypes::$sportbook, ProviderTypes::$racebook, ProviderTypes::$live_games, ProviderTypes::$poker]);
        $data['title'] = _i('Products totals');
        return view('back.reports.products.totals-overview', $data);
    }

    /**
     * Get deposit withdrawal by user
     *
     * @return Response
     */
    public function profitByUserData(Request $request)
    {
        try {
            $currency = $request->currency;
            $level = $request->level;
            if (!is_null($currency)) {
                $whitelabel = Configurations::getWhitelabel();
                $transactionsTotals = $this->transactionsRepo->getDepositWithdrawalByUser($whitelabel, $currency, $level, [ProviderTypes::$payment]);
                $totalsData = $this->transactionsCollection->formatDepositWithdrawalByUser($transactionsTotals, $currency);
            } else {
                $totalsData = [];
            }

            $data = [
                'totals' => $totalsData
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     *  Show view profit by user view report
     *
     * @return Factory|View
     */
    public function profitByUserView()
    {
        $data['title'] = _i('Profit by user');
        return view('back.reports.financial.profit-by-user', $data);
    }

    /**
     * Show view profit report for hour closure
     *
     * @return Factory|View
     */
    public function profitHourClosure()
    {
        $data['title'] = _i('Profit General');
        $data['providers'] = $this->providersRepo->getByTypes([ProviderTypes::$casino, ProviderTypes::$live_casino, ProviderTypes::$virtual, ProviderTypes::$sportbook, ProviderTypes::$racebook, ProviderTypes::$live_games, ProviderTypes::$poker]);
        $data['whitelabels'] = $this->whitelabelsRepo->all();
        $data['currenciest'] = $this->currenciesRepo->all();
        return view('back.reports.hour-closures.profit', $data);
    }

    /**
     * Get referred user data
     *
     * @param null $startDate
     * @param null $endDate
     * @return Response
     */
    public function referredUsersData($startDate = null, $endDate = null)
    {
        try {
            if (!is_null($startDate) && !is_null($endDate)) {
                $whitelabel = Configurations::getWhitelabel();
                $startDate = Utils::startOfDayUtc($startDate);
                $endDate = Utils::endOfDayUtc($endDate);
                $users = $this->usersRepo->getReferredUsers($whitelabel, $startDate, $endDate);
            } else {
                $users = [];
            }
            $this->reportsCollection->referredUsers($users);
            $data = [
                'users' => $users
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'start_date' => $startDate, 'end_date' => $endDate]);
            return Utils::failedResponse();
        }
    }

    /**
     * Show view referred users report
     *
     * @return Factory|View
     */
    public function referredUsers()
    {
        $data['title'] = _i('Referred users');
        return view('back.reports.users.referred-users', $data);
    }

    /**
     * Get user registration through data
     *
     * @param Request $request
     * @param null|string $startDate Start date to filter
     * @param null|string $endDate End date to filter
     * @return Response
     */
    public function registeredUsersData(Request $request, $startDate = null, $endDate = null)
    {
        try {
            $deposits = $request->deposits;
            $options = $request->options;

            if (!is_null($request->start_date) || !is_null($request->end_date)) {
                $startDate = Utils::startOfDayUtc($request->start_date);
                $endDate = Utils::endOfDayUtc($request->end_date);
                $country = $request->country;
                $webRegister = $request->web_register;
                $status = $request->status;
                $level = $request->level;
                $currency = session('currency');
                $whitelabel = Configurations::getWhitelabel();

                if (in_array(Roles::$admin_Beet_sweet, session('roles'))) {
                    //TODO REPLICA DE TREE
                    $tree = $this->usersRepo->treeSqlByUser(Auth::id(), $currency, $whitelabel);
                    $usersData = $this->usersRepo->getRegisteredUsersReportTree($country, $currency, $endDate, $startDate, $status, $webRegister, $whitelabel, $level, $tree);
                } else {
                    $usersData = $this->usersRepo->getRegisteredUsersReport($country, $currency, $endDate, $startDate, $status, $webRegister, $whitelabel, $level);
                }

            } else {
                $usersData = [];
            }
            $users = $this->reportsCollection->registeredUsers($usersData, $deposits, $options);
            $data = [
                'users' => $users
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all(), 'start_date' => $startDate, 'end_date' => $endDate]);
            return Utils::failedResponse();
        }
    }

    /**
     *  Show user registration through the web
     *
     * @return Factory|View
     */
    public function registersUsers()
    {
        $countries = $this->countriesRepo->all();
        $levels = Configurations::getLevels();
        $data['levels'] = $levels;
        $data['countries'] = $countries;
        $data['title'] = _i('Registered users');
        return view('back.reports.users.registered-users', $data);
    }

    /**
     * Get total logins data
     *
     * @param null $startDate
     * @param null $endDate
     * @return Response
     */
    public function totalLoginsData(AuditsRepo $auditsRepo, $startDate = null, $endDate = null)
    {
        try {
            if (!is_null($startDate) && !is_null($endDate)) {
                $whitelabel = Configurations::getWhitelabel();
                $startDate = Utils::startOfDayUtc($startDate);
                $endDate = Utils::endOfDayUtc($endDate);

                if (in_array(Roles::$admin_Beet_sweet, session('roles'))) {
                    //TODO REPLICA DE TREE
                    $tree = $this->usersRepo->treeSqlByUser(Auth::id(), session('currency'), $whitelabel);
                    $logins = $auditsRepo->getLoginsTree($whitelabel, $startDate, $endDate, $tree);
                } else {
                    $logins = $auditsRepo->getLogins($whitelabel, $startDate, $endDate);
                }

            } else {
                $logins = [];
            }
            $this->reportsCollection->totalLogins($logins);
            $data = [
                'logins' => $logins
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'start_date' => $startDate, 'end_date' => $endDate]);
            return Utils::failedResponse();
        }
    }

    /**
     * Show view totals logins report
     *
     * @return Factory|View
     */
    public function totalLogins()
    {
        $data['title'] = _i('Total logins');
        return view('back.reports.users.total-logins', $data);
    }

    /**
     * Show view financial totals report
     *
     * @return Factory|View
     */
    public function totals()
    {
        try {
            $paymentMethods = session('payment_methods');
            $data['title'] = _i('Totals');
            $data['payment_methods'] = $this->reportsCollection->formatPaymentMethod($paymentMethods);
            return view('back.reports.financial.totals', $data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Get financial totals data
     *
     * @param null $startDate
     * @param null $endDate
     * @param null $paymentMethod
     * @return Response
     */
    public function totalsData(Request $request, $startDate = null, $endDate = null)
    {
        try {
            if (!is_null($startDate) && !is_null($endDate)) {
                $timezone = session('timezone');
                $originalStartDate = $startDate;
                $originalEndDate = $endDate;
                $startDate = Utils::startOfDayUtc($startDate);
                $endDate = Utils::endOfDayUtc($endDate);
                $currency = session('currency');
                $paymentMethod = $request->payment_method;
                $whitelabel = Configurations::getWhitelabel();
                $providerTypes = [ProviderTypes::$dotworkers, ProviderTypes::$payment];
                $financialData = $this->transactionsRepo->getFinancialDataByPaymentMethod($whitelabel, $currency, $providerTypes, $startDate, $endDate, $timezone, $paymentMethod);
            } else {
                $originalStartDate = null;
                $originalEndDate = null;
                $financialData = [];
            }
            $financial = $this->transactionsCollection->formatFinancialDataByDates($financialData, $originalStartDate, $originalEndDate);
            return Utils::successResponse($financial);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'start_date' => $startDate, 'end_date' => $endDate]);
            return Utils::failedResponse();
        }
    }

    /**
     * Get transactions data
     * @param int $paymentMethod PaymentMethods
     * @param int $transactionType TransactionTypes
     *
     * @return Response
     */
    public function transactionsData($paymentMethod, $transactionType, $startDate = null, $endDate = null)
    {
        try {
            if (!is_null($startDate) && !is_null($endDate)) {
                $startDate = Utils::startOfDayUtc($startDate);
                $endDate = Utils::endOfDayUtc($endDate);
                $status = TransactionStatus::$approved;
                $requestData = [
                    'transaction_type' => $transactionType,
                    'status' => $status,
                    'payment_method' => $paymentMethod,
                    'start_date' => $startDate,
                    'end_date' => $endDate
                ];
                $betPayToken = session('betpay_client_access_token');
                $url = "{$this->betPayURL}/transactions/all";
                $curl = Curl::to($url)
                    ->withData($requestData)
                    ->withHeader('Accept: application/json')
                    ->withHeader("Authorization: Bearer $betPayToken")
                    ->get();
                $response = json_decode($curl);

                if ($response->status == Status::$ok) {
                    $transactions = $response->data->transactions;
                    if ($transactionType == TransactionTypes::$debit) {
                        $data = $this->betPayTransactionsCollection->formatDebitTransactionsReport($transactions, $paymentMethod, $status);
                    } else {
                        $data = $this->betPayTransactionsCollection->formatCreditTransactionsReport($transactions, $paymentMethod, $status);
                    }
                    return Utils::successResponse($data);
                } else {
                    return Utils::errorResponse($response->code, $response->data);
                }
            } else {
                $totals['total'] = number_format(0, 2);
                $data = [
                    'transactions' => [],
                    'totals' => $totals
                ];
                return Utils::successResponse($data);
            }
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'paymentMethod' => $paymentMethod, 'transactionType' => $transactionType]);
            return Utils::failedResponse();
        }
    }

    /**
     *  Show view users actives report
     *
     * @return Factory|View
     */
    public function usersActives()
    {
        $data['title'] = _i('Active users on platforms');
        return view('back.reports.users.active-users-platforms', $data);
    }

    /**
     * Get users actives data
     *
     * @param null|string $startDate Start date to filter
     * @param null|string $endDate End date to filter
     * @return Response
     */
    public function usersActivesData($startDate = null, $endDate = null)
    {
        try {
            if (!is_null($startDate) && !is_null($endDate)) {
                $startDate = Utils::startOfDayUtc($startDate, $originalFormat = 'Y-m-d', $finalFormat = 'Y-m-d');
                $endDate = Utils::endOfDayUtc($endDate, $originalFormat = 'Y-m-d', $finalFormat = 'Y-m-d');
                $whitelabel = Configurations::getWhitelabel();
                $users = $this->closuresUsersTotalsRepo->getActiveUsers($whitelabel, $startDate, $endDate);
            } else {
                $users = [];
            }
            $this->reportsCollection->registeredUsers($users, $deposits = null, $options = null);
            $data = [
                'users' => $users
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'start_date' => $startDate, 'end_date' => $endDate]);
            return Utils::failedResponse();
        }
    }

    /**
     * Get users balances report data
     *
     * @param null|string $currency Currency ISO
     * @return Response
     */
    public function usersBalancesData(Request $request, $currency = null)
    {
        try {
            if (!is_null($currency)) {
                //$usersId = [];
                /*$columns = $request->columns;
                $order = $request->order;
                $start = $request->start;
                $length = $request->length;
                $search = $request''->search['value'];
                $orderColumn = $columns[$order[0]['column']]['data'];

                $wallets = Wallet::getUsersBalancesByIds($usersId, $currency)->data->wallets;
                $wallets = Wallet::getByCurrency($whitelabel, $currency)->data->wallets;*/
//                $users = $this->usersRepo->getUsersByCurrency($currency);
//                foreach ($users as $user) {
//                    $usersId[] = $user->id;
//                }

                $whitelabel = Configurations::getWhitelabel();

                if (in_array(Roles::$admin_Beet_sweet, session('roles'))) {
                    //TODO REPLICA DE TREE
                    $tree = $this->usersRepo->treeSqlByUser(Auth::id(), $currency, $whitelabel);
                    $users = $this->usersRepo->getByWhitelabelAndCurrencyTree($whitelabel, $currency, $tree);
                } else {
                    $users = $this->usersRepo->getByWhitelabelAndCurrency($whitelabel, $currency);
                }

                $usersWallets = collect();
                $walletsData = collect();

                foreach ($users as $user) {
                    $usersWallets->push($user->wallet_id);
                }
                if (count($usersWallets) > 0) {
                    foreach ($usersWallets->chunk(1000) as $chunk) {
                        $walletAccessToken = Wallet::clientAccessToken();
                        $wallets = $chunk->toArray();
                        $wallets = Wallet::getUsersBalancesByAmounts($wallets, $balanceOptions = '>=', $balance = 0, $walletAccessToken->access_token);
                        $walletsData->push($wallets->data->wallets);
                    }
                }
                $walletsData = $walletsData->collapse()->all();


                //$wallets = Wallet::getByCurrency($whitelabel, $currency)->data->wallets;
            } else {
                $walletsData = [];
            }
            $balancesData = $this->reportsCollection->usersBalances($walletsData);
            return Utils::successResponse($balancesData);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     *  Show view users balance report
     *
     * @return Factory|View
     */
    public function usersBalances()
    {
        $data['title'] = _i('Users balances');
        return view('back.reports.users.balances', $data);
    }

    /**
     * Get birthdays report data
     *
     * @return Response
     */
    public function usersBirthdaysData($date = null)
    {
        try {
            $whitelabel = Configurations::getWhitelabel();
            $timezone = session('timezone');
            if (is_null($date)) {
                $birthMoth = Carbon::now($timezone)->format('m');
                $birthDay = Carbon::now($timezone)->format('d');
            } else {
                $birthMoth = Carbon::createFromFormat('d-m-Y', $date, $timezone)->format('m');
                $birthDay = Carbon::createFromFormat('d-m-Y', $date, $timezone)->format('d');
            }
            $users = $this->usersRepo->getUsersBirthdays($whitelabel, $birthMoth, $birthDay);
            $this->reportsCollection->usersBirthdays($users);
            return Utils::successResponse($users);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     *  Show view users birthdays report
     *
     * @return Factory|View
     */
    public function usersBirthdays()
    {
        $data['title'] = _i('Users birthdays');
        return view('back.reports.users.users-birthdays', $data);
    }

    /**
     * Get conversion report data
     *
     * @param null|string $startDate Start date to filter
     * @param null|string $endDate End date to filter
     * @return Response
     */
    public function usersConversionData($startDate = null, $endDate = null)
    {
        try {
            if (!is_null($startDate) && !is_null($endDate)) {
                $startDate = Utils::startOfDayUtc($startDate);
                $endDate = Utils::endOfDayUtc($endDate);
                $whitelabel = Configurations::getWhitelabel();
                $currency = session('currency');
                $users = $this->usersRepo->getUsersConversion($whitelabel, $currency, $startDate, $endDate);
            } else {
                $users = [];
            }
            $totals = $this->reportsCollection->usersConversion($users);
            return Utils::successResponse($totals);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'start_date' => $startDate, 'end_date' => $endDate]);
            return Utils::failedResponse();
        }
    }

    /**
     *  Show view users conversion report
     *
     * @return Factory|View
     */
    public function usersConversion()
    {
        $data['title'] = _i('Users conversion');
        return view('back.reports.users.users-conversion', $data);
    }

    /**
     * Get users report data
     *
     * @param Request $request
     * @param null|string $startDate Start date to filter
     * @param null|string $endDate End date to filter
     * @param null|int $provider Provider ID
     * @return Response
     */
    public function usersTotalsData(Request $request, $startDate = null, $endDate = null, $provider = null)
    {
        try {
            $nowUsersTotals = null;
            $providerData = $this->providersRepo->find($provider);
            if (!is_null($providerData) && ($providerData->provider_type_id == ProviderTypes::$sportbook)) {
                $whitelabel = Configurations::getWhitelabel();
                $currency = session('currency');
                $startDate = Utils::startOfDayUtc($startDate);
                $endDate = Utils::endOfDayUtc($endDate);
                $nowUsersTotals = $this->coreRepo->getUsersTotals($whitelabel, $startDate, $endDate, $currency, $providerData->tickets_table, $providerData->id);
                $totals = $this->reportsCollection->usersTotals([], $nowUsersTotals);
                return Utils::successResponse($totals);
            } else {
                if (!is_null($startDate) && !is_null($endDate)) {
                    $whitelabel = Configurations::getWhitelabel();
                    $currency = session('currency');
                    $timezone = session('timezone');
                    $today = Carbon::now()->setTimezone($timezone);
                    $endDateOriginal = $endDate;
                    $startDate = Utils::startOfDayUtc($startDate);
                    $endDate = Utils::endOfDayUtc($endDate);
                    $usersTotals = $this->closuresUsersTotalsRepo->getUsersTotals($whitelabel, $startDate, $endDate, $currency, $provider);

                    if ($endDateOriginal == $today->copy()->format('Y-m-d')) {
                        $nowStartDate = $today->copy()->setTimezone('UTC')->startOfHour();
                        $nowEndDate = $today->copy()->setTimezone('UTC')->endOfHour();
                        $providerData = $this->providersRepo->find($provider);
                        $nowUsersTotals = $this->coreRepo->getUsersTotals($whitelabel, $nowStartDate, $nowEndDate, $currency, $providerData->tickets_table, $provider);
                    }
                } else {
                    $usersTotals = [];
                }
                $totals = $this->reportsCollection->usersTotals($usersTotals, $nowUsersTotals);
                return Utils::successResponse($totals);
            }
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all(), 'start_date' => $startDate, 'end_date' => $endDate]);
            return Utils::failedResponse();
        }
    }

    /**
     * Show view users totals report
     *
     * @return Factory|View
     */
    public function usersTotals($provider)
    {
        $data['provider'] = $provider;
        $data['title'] = _i('Users totals report') . ' | ' . Providers::getName($provider);
        return view('back.reports.products.users-totals', $data);
    }

    /**
     *  Show whitelabels active providers
     *
     * @return Factory|View
     */
    public function whitelabelsActiveProviders()
    {
        $data['title'] = _i('Active providers');
        $data['whitelabels'] = $this->whitelabelsRepo->all();
        $data['providers'] = $this->providersRepo->getByTypes([ProviderTypes::$casino, ProviderTypes::$live_casino, ProviderTypes::$virtual, ProviderTypes::$sportbook, ProviderTypes::$racebook, ProviderTypes::$live_games, ProviderTypes::$poker]);
        return view('back.reports.products.whitelabels-active-providers', $data);
    }

    /**
     * Get whitelabels active providers data
     *
     * @param Request $request
     * @return Response
     */
    public function whitelabelsActiveProvidersData(Request $request)
    {
        try {
            $whitelabel = $request->whitelabel;
            $provider = $request->provider;
            $currency = $request->currency;
            $whitelabels = $this->providersRepo->getByWhitelabelAndProviders($whitelabel, $provider, $currency);
            $this->reportsCollection->whitelabelsAndProviders($whitelabels);
            $data = [
                'products' => $whitelabels
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'whitelabel' => $whitelabel, 'provider' => $provider, 'currency' => $currency]);
            return Utils::failedResponse();
        }

    }

    /**
     *  Show sales whitelabels through the web
     *
     * @return Factory|View
     */
    public function whitelabelsSales()
    {
        $currencies = $this->currenciesRepo->all();
        $data['title'] = _i('Sales by whitelabels');
        $data['currencies'] = $currencies;
        return view('back.reports.financial.whitelabels-sales', $data);
    }

    /**
     * Get sales whitelabels data
     *
     * @param Request $request
     * @return Response
     */
    public function whitelabelsSalesData(Request $request)
    {
        try {
            $startDate = $request->start_date;
            $endDate = $request->end_date;
            $currency = $request->currency;

            if (!is_null($startDate) && !is_null($endDate)) {
                $startDate = Utils::startOfDayUtc($startDate);
                $endDate = Utils::endOfDayUtc($endDate);
                $sales = $this->transactionsRepo->getWhitelabelsSalesData($currency, $startDate, $endDate);
            } else {
                $sales = [];
            }
            $whitelabelsSales = $this->transactionsCollection->formatWhitelabelsSales($sales, $currency, $startDate, $endDate);
            return Utils::successResponse($whitelabelsSales);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all(), 'startDate' => $startDate, 'endDate' => $endDate]);
            return Utils::failedResponse();
        }
    }

    /**
     * Get whitelabels totals data
     *
     * @param Request $request
     * @param null|string $startDate Start date to filter
     * @param null|string $endDate End date to filter
     * @return Response
     */
    public function whitelabelsTotalsData(Request $request, $startDate = null, $endDate = null)
    {
        try {
            if (!is_null($startDate) || !is_null($endDate)) {
                $startDate = Utils::startOfDayUtc($startDate);
                $endDate = Utils::endOfDayUtc($endDate);
                $provider = $request->provider;
                $whitelabel = $request->whitelabel;
                $currency = $request->currency;
                $totals = $this->closuresUsersTotalsRepo->whitelabelsTotals($startDate, $endDate, $currency, $provider, $whitelabel);
            } else {
                $totals = [];
            }

            $this->reportsCollection->whitelabelsTotals($totals);
            $data = [
                'totals' => $totals
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'start_date' => $startDate, 'end_date' => $endDate]);
            return Utils::failedResponse();
        }
    }

    /**
     * Show view whitelabels totals report
     *
     * @return Factory|View
     */
    public function whitelabelsTotals()
    {
        $currencies = $this->currenciesRepo->all();
        $data['currencies'] = $currencies;
        $data['providers'] = $this->providersRepo->getByTypes([ProviderTypes::$casino, ProviderTypes::$live_casino, ProviderTypes::$virtual, ProviderTypes::$sportbook, ProviderTypes::$racebook, ProviderTypes::$live_games, ProviderTypes::$poker]);
        $data['whitelabels'] = $this->whitelabelsRepo->all();
        $data['title'] = _i('Whitelabels totals');
        return view('back.reports.products.whitelabels-totals', $data);
    }

    /**
     * Get whitelabels totals data
     *
     * @param Request $request
     * @param null|string $startDate Start date to filter
     * @param null|string $endDate End date to filter
     * @return Response
     */
    public function whitelabelsTotalsDataNew(Request $request, $startDate = null, $endDate = null)
    {
        if (count($request->all()) > 1) {
            $this->validate($request, [
                'convert' => 'required_without:currency'
            ]);
        }
        try {
            $convert = $request->convert;
            $currency = $request->currency;
            $provider = $request->provider;
            if (!is_null($startDate) || !is_null($endDate)) {
                $startDate = Utils::startOfDayUtc($startDate);
                $endDate = Utils::endOfDayUtc($endDate);
                $whitelabel = $request->whitelabel;
                $totalsWl = $this->closuresUsersTotalsRepo->whitelabelsClosuresTotals($startDate, $endDate, $currency, $provider, $whitelabel);
                $totals = $this->closuresUsersTotalsRepo->getWhitelabelsTotal($totalsWl);
            } else {
                $totals = [];
            }
            $this->reportsCollection->whitelabelsTotalsNew($totals, $convert, $currency, $startDate, $endDate, $provider);
            $data = [
                'totals' => $totals
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'start_date' => $startDate, 'end_date' => $endDate]);
            return Utils::failedResponse();
        }
    }

    /**
     * Show view whitelabels totals report
     *
     * @return Factory|View
     */
    public function whitelabelsTotalsNew()
    {
        $data['providers'] = $this->providersRepo->getByTypes([ProviderTypes::$casino, ProviderTypes::$live_casino, ProviderTypes::$virtual, ProviderTypes::$sportbook, ProviderTypes::$racebook, ProviderTypes::$live_games, ProviderTypes::$poker]);
        $data['whitelabels'] = $this->whitelabelsRepo->all();
        $data['title'] = _i('Whitelabels totals');
        return view('back.reports.products.whitelabels-totals-new', $data);
    }

    /**
     * Show withdrawals report
     *
     * @return \Illuminate\Contracts\Foundation\Application|Factory|View
     */
    public function withdrawals()
    {
        $data['title'] = _i('Withdrawals');
        return view('back.reports.financial.withdrawals', $data);
    }
}

<?php

namespace App\Http\Controllers;

use App\BetPay\Collections\ClientsCollection;
use App\BetPay\Collections\AccountsCollection;
use App\BetPay\Collections\PaymentMethodsCollection;
use App\BetPay\Collections\TransactionsCollection;
use App\Audits\Enums\AuditTypes;
use App\Core\Notifications\TransactionNotAllowed;
use App\Core\Repositories\CountriesRepo;
use App\Core\Repositories\CredentialsRepo;
use App\Core\Repositories\CurrenciesRepo;
use App\Core\Repositories\TransactionsRepo;
use App\Users\Repositories\UsersRepo;
use App\Whitelabels\Repositories\OperationalBalancesRepo;
use App\Whitelabels\Repositories\OperationalBalancesTransactionsRepo;
use App\Whitelabels\Repositories\WhitelabelsRepo;
use Carbon\Carbon;
use Dotworkers\Audits\Audits;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Configurations\Enums\BetPayCodes;
use Dotworkers\Configurations\Enums\Codes;
use Dotworkers\Configurations\Enums\PaymentMethods;
use Dotworkers\Configurations\Enums\Providers;
use Dotworkers\Configurations\Enums\Status;
use Dotworkers\Configurations\Enums\TransactionStatus;
use Dotworkers\Configurations\Enums\TransactionTypes;
use Dotworkers\Configurations\Utils;
use Dotworkers\Wallet\Enums\Actions;
use Dotworkers\Wallet\Wallet;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Ixudra\Curl\Facades\Curl;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class BetPayController
 *
 * This class allows to manage BetPay requests
 *
 * @package App\Http\Controllers
 * @author  Eborio Linarez
 */
class BetPayController extends Controller
{
    /**
     * BetPay URL
     *
     * @var string
     */
    private $betPayURL;

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
     * CurrenciesRepo
     *
     * @var CurrenciesRepo
     */
    private $currenciesRepo;

    /**
     * AccountsCollection
     *
     * @var AccountsCollection
     */
    private $accountsCollection;

    /**
     * PaymentMethodsCollection
     *
     * @var PaymentMethodsCollection
     */
    private $paymentMethodsCollection;

    /**
     * WhitelabelsRepo
     *
     * @var WhitelabelsRepo
     */
    private $whitelabelsRepo;

    /**
     * CredentialsRepo
     *
     * @var CredentialsRepo
     */
    private $credentialsRepo;

    /**
     * UsersRepo
     *
     * @var UsersRepo
     */
    private $usersRepo;

    /**
     * CountriesRepo
     *
     * @var CountriesRepo
     */
    private $countriesRepo;

    /**
     * ClientsCollection
     *
     * @var ClientsCollection
     */
    private $clients;

    /**
     * BetPayController constructor
     *
     * @param UsersRepo $usersRepo
     * @param TransactionsRepo $transactionsRepo
     * @param TransactionsCollection $transactionsCollection
     * @param CurrenciesRepo $currenciesRepo
     * @param AccountsCollection $accountsCollection
     * @param PaymentMethodsCollection $paymentMethodsCollection
     * @param WhitelabelsRepo $whitelabelsRepo
     * @param CredentialsRepo $credentialsRepo
     * @param CountriesRepo $countriesRepo
     * @param ClientsCollection $clientsCollection
     *
     */
    public function __construct(UsersRepo $usersRepo, TransactionsRepo $transactionsRepo, TransactionsCollection $transactionsCollection, CurrenciesRepo $currenciesRepo, AccountsCollection $accountsCollection, PaymentMethodsCollection $paymentMethodsCollection, WhitelabelsRepo $whitelabelsRepo, CredentialsRepo $credentialsRepo, CountriesRepo $countriesRepo, ClientsCollection $clientsCollection)
    {
        $this->betPayURL = env('BETPAY_SERVER') . '/api';
        $this->transactionsRepo = $transactionsRepo;
        $this->transactionsCollection = $transactionsCollection;
        $this->currenciesRepo = $currenciesRepo;
        $this->accountsCollection = $accountsCollection;
        $this->paymentMethodsCollection = $paymentMethodsCollection;
        $this->whitelabelsRepo = $whitelabelsRepo;
        $this->credentialsRepo = $credentialsRepo;
        $this->usersRepo = $usersRepo;
        $this->countriesRepo = $countriesRepo;
        $this->clientsCollection = $clientsCollection;

    }

    /**
     *  Accounts search
     *
     * @return Factory|View
     */
    public function accountsSearch()
    {
        try {
            $betPayToken = session('betpay_client_access_token');
            $paymentMethods = [];
            $urlPaymentMethodsAll = "{$this->betPayURL}/clients/accounts/payment-methods";
            if (!is_null($betPayToken)) {
                $requestData = [
                    'currency' => session('currency')
                ];
                $curlPaymentMethodsAll = Curl::to($urlPaymentMethodsAll)
                    ->withData($requestData)
                    ->withHeader('Accept: application/json')
                    ->withHeader("Authorization: Bearer $betPayToken")
                    ->get();
                $responsePaymentMethodsAll = json_decode($curlPaymentMethodsAll);
                if ($responsePaymentMethodsAll->status == Status::$ok) {
                    $paymentMethods = $responsePaymentMethodsAll->data->payment_methods;
                }
            }
            $paymentMethodsUnique = $this->paymentMethodsCollection->formatDistinctByPaymentMethods($paymentMethods);
            $data['payment_methods'] = $paymentMethodsUnique;
            $data['title'] = _i('Accounts search');
            return view('back.betpay.accounts.search', $data);

        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request']);
            abort(500);
        }
    }

    /**
     *  Accounts search data users
     *
     * @param Request $request
     * @return Response|void
     */
    public function accountsSearchData(Request $request)
    {
        try {
            $account = $request->account;
            $payment = $request->payment;

            if ((empty($payment))) {
                $accounts = [
                    'accounts' => []
                ];
            } else {
                $betPayToken = session('betpay_client_access_token');
                $urlPaymentMethodsAll = "{$this->betPayURL}/clients/accounts/search";
                $requestData = [
                    'account' => $account,
                    'payment_method' => $payment
                ];
                if (!is_null($betPayToken)) {
                    $curlPaymentMethodsAll = Curl::to($urlPaymentMethodsAll)
                        ->withData($requestData)
                        ->withHeader('Accept: application/json')
                        ->withHeader("Authorization: Bearer $betPayToken")
                        ->get();
                    $responsePaymentMethodsAll = json_decode($curlPaymentMethodsAll);
                    if ($responsePaymentMethodsAll->status == Status::$ok) {
                        $data = $responsePaymentMethodsAll->data->accounts;
                        $this->accountsCollection->formatAccounts($data);
                        $accounts = [
                            'accounts' => $data
                        ];
                    } else {
                        $accounts = [
                            'accounts' => []
                        ];
                    }
                } else {
                    $accounts = [
                        'accounts' => []
                    ];
                }
            }
            return Utils::successResponse($accounts);
        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            abort(500);
        }
    }

    /**
     * Get all clients
     *
     * @return Response
     */
    public function allClients(): Response
    {
        try {
            $betPayToken = session('betpay_client_access_token');
            $urlAllClients = "{$this->betPayURL}/clients/all";
            if (!is_null($betPayToken)) {
                $curlAllClients = Curl::to($urlAllClients)
                    ->withHeader('Accept: application/json')
                    ->withHeader("Authorization: Bearer $betPayToken")
                    ->get();
                $responseAllClients = json_decode($curlAllClients);
                if ($responseAllClients->status == Status::$ok) {
                    $all = $responseAllClients->data->client;
                    $this->transactionsCollection->formatClient($all);
                } else {

                    $all = [];
                }
            }
            $data = [
                'clients' => $all
            ];
            return Utils::successResponse($data);

        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }


    public function banksData(Request $request)
    {
        try {
            $currency = $request->currency;
            $country = $request->country;
            if (!is_null($currency) && !is_null($country)) {
                $betPayToken = session('betpay_client_access_token');
                $urlBanks = "{$this->betPayURL}/banks";
                if (!is_null($betPayToken)) {
                    $requestData = [
                        'currency' => $currency,
                        'country' => $country
                    ];
                    $curl = Curl::to($urlBanks)
                        ->withData($requestData)
                        ->withHeader('Accept: application/json')
                        ->withHeader("Authorization: Bearer $betPayToken")
                        ->get();
                    $response = json_decode($curl);
                    if ($response->status == Status::$ok) {
                        $banks = $response->data->banks;
                    } else {
                        $banks = [];
                    }
                }
            } else {
                $banks = [];
            }
            $data = [
                'banks' => $banks
            ];
            return Utils::successResponse($data);

        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     * Show payment limits
     *
     * @return Factory|View
     */
    public function createPaymentLimits()
    {
        $data['title'] = _i('Create payment limits');
        $data['whitelabels'] = $this->whitelabelsRepo->all();
        $data['currency_client'] = $this->currenciesRepo->all();
        $paymentMethods = [];
        $betPayToken = session('betpay_client_access_token');
        $urlPaymentMethodsAll = "{$this->betPayURL}/payment-methods/all";
        if (!is_null($betPayToken)) {
            $curlPaymentMethodsAll = Curl::to($urlPaymentMethodsAll)
                ->withHeader('Accept: application/json')
                ->withHeader("Authorization: Bearer $betPayToken")
                ->get();
            $responsePaymentMethodsAll = json_decode($curlPaymentMethodsAll);
            if ($responsePaymentMethodsAll->status == Status::$ok) {
                $paymentMethods = $responsePaymentMethodsAll->data->payment_methods;
            }
        }
        $data['payment_methods'] = $paymentMethods;
        return view('back.betpay.clients.payment-limits.create', $data);
    }

    /**
     * Get client accounts
     *
     * @param int $paymentMethod Payment method ID
     * @return array
     */
    private function clientAccounts($paymentMethod)
    {
        try {
            $payments = Configurations::getPayments();
            $requestData = null;
            $curl = null;

            if ($payments) {
                $betPayToken = session('betpay_client_access_token');
                $url = "{$this->betPayURL}/clients/accounts";

                if (!is_null($betPayToken)) {
                    $requestData = [
                        'currency' => session('currency'),
                        'payment_method' => $paymentMethod
                    ];
                    $curl = Curl::to($url)
                        ->withData($requestData)
                        ->withHeader('Accept: application/json')
                        ->withHeader("Authorization: Bearer $betPayToken")
                        ->get();
                    $response = json_decode($curl);
                    if ($response->status == Status::$ok) {
                        $accounts = $response->data->accounts;
                    } else {
                        $accounts = [];
                    }
                } else {
                    $accounts = [];
                }
            } else {
                $accounts = [];
            }
            return $accounts;

        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'curl' => $curl, 'curl_request' => $requestData]);
            return [];
        }
    }

    /**
     *  Client account list
     *
     * @return Factory|View
     */
    public function clientAccountList()
    {
        try{
            $data['title'] = _i('Client account list');
            $data['whitelabels'] = $this->whitelabelsRepo->all();
            $data['currency_client'] = $this->currenciesRepo->all();
            $betPayToken = session('betpay_client_access_token');
            $urlPaymentMethodsAll = "{$this->betPayURL}/payment-methods/all";
            $urlClientsAll = "{$this->betPayURL}/clients/all";
            if (!is_null($betPayToken)) {
                $curlPaymentMethodsAll = Curl::to($urlPaymentMethodsAll)
                    ->withHeader('Accept: application/json')
                    ->withHeader("Authorization: Bearer $betPayToken")
                    ->get();

                $curlClientsAll = Curl::to($urlClientsAll)
                    ->withHeader('Accept: application/json')
                    ->withHeader("Authorization: Bearer $betPayToken")
                    ->get();

                $responsePaymentMethodsAll = json_decode($curlPaymentMethodsAll);
                $responseClientsAll = json_decode($curlClientsAll);

                if ($responsePaymentMethodsAll->status == Status::$ok) {
                    $paymentMethods = $responsePaymentMethodsAll->data->payment_methods;
                } else {
                    $paymentMethods = [];
                }

                if ($responseClientsAll->status == Status::$ok) {
                    $clients = $responseClientsAll->data->client;
                    $allClients =  $this->clientsCollection->formatClientsAll($clients);
                } else {
                    $allClients = [];
                }
            }
            $data['payment_methods'] = $paymentMethods;
            $data['clients'] = $allClients;
            return view('back.betpay.clients.accounts.index', $data);
        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     *  Client account list data
     *
     * @return Response
     */
    public function clientAccountListData(Request $request)
    {
        try {
            $client = $request->client;
            $currency = $request->currency;
            $paymentMethod = $request->payment_method;

            $betPayToken = session('betpay_client_access_token');
            $urlClientAccount = "{$this->betPayURL}/clients/accounts/get-by-client-payment-methods-currency";
            if (!is_null($betPayToken)) {
                $requestData = [
                    'client' => $client,
                    'currency' => $currency,
                    'payment_method' => $paymentMethod,
                ];
                $curlClientAccount = Curl::to( $urlClientAccount )
                    ->withData($requestData)
                    ->withHeader('Accept: application/json')
                    ->withHeader("Authorization: Bearer $betPayToken")
                    ->get();
                $responseClientAccount = json_decode($curlClientAccount);
                if ($responseClientAccount->status == Status::$ok) {
                    $accounts = $responseClientAccount->data->accounts;
                    $this->accountsCollection->formatClientAccount($accounts);
                } else {
                    $accounts = [];
                }
            }

            $data = [
                'accounts' => $accounts
            ];
            return Utils::successResponse($data);

        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Show create view
     *
     * @return Factory|View
     */
    public function createClients()
    {
        try {
            $data['title'] = _i('Create client');
            $data['whitelabels'] = $this->whitelabelsRepo->all();
            $data['currency_client'] = $this->currenciesRepo->all();
            $betPayToken = session('betpay_client_access_token');
            $urlPaymentMethodsAll = "{$this->betPayURL}/payment-methods/get-all";
            $paymentMethods = [];

            if (!is_null($betPayToken)) {
                $curlPaymentMethodsAll = Curl::to($urlPaymentMethodsAll)
                    ->withHeader('Accept: application/json')
                    ->withHeader("Authorization: Bearer $betPayToken")
                    ->get();
                $responsePaymentMethodsAll = json_decode($curlPaymentMethodsAll);

                if ($responsePaymentMethodsAll->status == Status::$ok) {
                    $paymentMethods = $responsePaymentMethodsAll->data->payment_methods;
                } else {
                    $paymentMethods = [];
                }
            }

            $data['payment_methods'] = $paymentMethods;

            return view('back.betpay.clients.create', $data);
        } catch (Exception $ex) {
            Log::error(__METHOD__, ['exception' => $ex]);

            abort(500);
        }
    }

    /**
     * Show create view
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createClientsPaymentMethod()
    {
        try {
            $data['title'] = _i('Create client');
            $data['whitelabels'] = $this->whitelabelsRepo->all();
            $data['currency_client'] = $this->currenciesRepo->all();
            $data['countries'] = $this->countriesRepo->all();
            $betPayToken = session('betpay_client_access_token');
            $urlPaymentMethodsAll = "{$this->betPayURL}/payment-methods/get-all";
            $paymentMethods = [];
            if (!is_null($betPayToken)) {
                $curlPaymentMethodsAll = Curl::to($urlPaymentMethodsAll)
                    ->withHeader('Accept: application/json')
                    ->withHeader("Authorization: Bearer $betPayToken")
                    ->get();
                $responsePaymentMethodsAll = json_decode($curlPaymentMethodsAll);
                if ($responsePaymentMethodsAll->status == Status::$ok) {
                    $paymentMethods = $responsePaymentMethodsAll->data->payment_methods;
                } else {
                    $paymentMethods = [];
                }
            }
            $data['payment_methods'] = $paymentMethods;
            return view('back.betpay.clients.create-client-payment-methods', $data);
        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    /**
     * Show create client account
     *
     * @return Factory|View
     */
    public function createClientAccount()
    {
        try {
            $data['title'] = _i('Active Payment Methods');
            $paymentMethods = [];
            $betPayToken = session('betpay_client_access_token');
            $urlPaymentMethodsAll = "{$this->betPayURL}/payment-methods/get-all-active";
            if (!is_null($betPayToken)) {
                $curlPaymentMethodsAll = Curl::to($urlPaymentMethodsAll)
                    ->withHeader('Accept: application/json')
                    ->withHeader("Authorization: Bearer $betPayToken")
                    ->get();
                $responsePaymentMethodsAll = json_decode($curlPaymentMethodsAll);
                if (($responsePaymentMethodsAll->status == Status::$ok)) {
                    $paymentMethods = $responsePaymentMethodsAll->data->payment_methods;
                } else {
                    $paymentMethods = [];
                }
            }
            $data['payment_methods'] = $paymentMethods;
            return view('back.betpay.clients.accounts.create', $data);
        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    /**
     * Show pending credit binance
     *
     * @return Application|Factory|View
     */
    public function creditBinance()
    {
        $data['transaction_type'] = TransactionTypes::$credit;
        $data['payment_method'] = PaymentMethods::$binance;
        $data['provider'] = Providers::$binance;
        $data['title'] = _i('Pending Binance credit transactions');
        return view('back.betpay.binance.credit', $data);
    }

    /**
     * Show credit report
     *
     * @param int $paymentMethod Payment method ID
     * @return Application|Factory|View
     */
    public function creditReport($paymentMethod)
    {
        $data['payment_method'] = $paymentMethod;
        $data['title'] = _i('Credit report');
        return view('back.betpay.reports.credit', $data);
    }

    /**
     * Get credit report data
     *
     * @param null $startDate
     * @param null $endDate
     * @param null $paymentMethod Payment method ID
     * @return Response
     */
    public function creditReportData(Request $request, $startDate = null, $endDate = null, $paymentMethod = null)
    {
        try {
            if (!is_null($startDate) && !is_null($endDate)) {
                $startDate = Utils::startOfDayUtc($startDate);
                $endDate = Utils::endOfDayUtc($endDate);
                $status = $request->status;
                $whitelabel = Configurations::getWhitelabel();
                $transactionType = TransactionTypes::$credit;
                $currency = session('currency');

                if ($whitelabel == 68) {
                    $transactions = $this->transactionsRepo->getByTransactionTypeAndProviders($whitelabel, $currency, $transactionType, [Providers::$just_pay], $startDate, $endDate, $status);
                    $data = $this->transactionsCollection->formatCreditTransactionsReport($transactions, $paymentMethod, $status);
                    return Utils::successResponse($data);
                } else if ($paymentMethod == PaymentMethods::$charging_point) {
                    $transactions = $this->transactionsRepo->getByTransactionTypeAndProviders($whitelabel, $currency, $transactionType, [Providers::$charging_point], $startDate, $endDate, $status);
                    $data = $this->transactionsCollection->formatCreditTransactionsReport($transactions, $paymentMethod, $status);
                    return Utils::successResponse($data);
                } else {
                    $requestData = [
                        'currency' => session('currency'),
                        'transaction_type' => TransactionTypes::$credit,
                        'status' => $status,
                        'payment_method' => $paymentMethod,
                        'start_date' => $startDate,
                        'end_date' => $endDate
                    ];
                    $betPayToken = session('betpay_client_access_token');
                    $url = "{$this->betPayURL}/transactions/dates-and-status";
                    $curl = Curl::to($url)
                        ->withData($requestData)
                        ->withHeader('Accept: application/json')
                        ->withHeader("Authorization: Bearer $betPayToken")
                        ->get();
                    $response = json_decode($curl);

                    if ($response->status == Status::$ok) {
                        $transactions = $response->data->transactions;
                        $data = $this->transactionsCollection->formatCreditTransactionsReport($transactions, $paymentMethod, $status);
                        return Utils::successResponse($data);

                    } else {
                        return Utils::errorResponse($response->code, $response->data);
                    }
                }
            } else {
                $totals['total'] = number_format(0, 2);
                $data = [
                    'transactions' => [],
                    'totals' => $totals
                ];
                return Utils::successResponse($data);
            }


        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Show payment limits
     *
     * @return Factory|View
     */
    public function dataPaymentLimits()
    {
        $data['title'] = _i('Create payment limits');
        $data['whitelabels'] = $this->whitelabelsRepo->all();
        $data['currency_client'] = $this->currenciesRepo->all();
        $paymentMethods = [];
        $betPayToken = session('betpay_client_access_token');
        $urlPaymentMethodsAll = "{$this->betPayURL}/payment-methods/all";
        if (!is_null($betPayToken)) {
            $curlPaymentMethodsAll = Curl::to($urlPaymentMethodsAll)
                ->withHeader('Accept: application/json')
                ->withHeader("Authorization: Bearer $betPayToken")
                ->get();
            $responsePaymentMethodsAll = json_decode($curlPaymentMethodsAll);

            if ($responsePaymentMethodsAll->status == Status::$ok) {
                $paymentMethods = $responsePaymentMethodsAll->data->payment_methods;
            }
        }
        $data['payment_methods'] = $paymentMethods;
        return view('back.betpay.clients.payment-limits.create', $data);
    }

    /**
     * Show pending debit binance
     *
     * @return Application|Factory|View
     */
    public function debitBinance()
    {
        $paymentMethod = PaymentMethods::$binance;
        $data['transaction_type'] = TransactionTypes::$debit;
        $data['payment_method'] = $paymentMethod;
        $data['provider'] = Providers::$binance;
        $data['accounts'] = $this->clientAccounts($paymentMethod);
        $data['title'] = _i('Pending Binance debit transactions');
        return view('back.betpay.binance.debit', $data);
    }

    /**
     * Show debit report
     *
     * @param int $paymentMethod Payment method ID
     * @return Application|Factory|View
     */
    public function debitReport($paymentMethod)
    {
        $data['payment_method'] = $paymentMethod;
        $data['title'] = _i('Debit report');
        return view('back.betpay.reports.debit', $data);
    }

    /**
     * Get debit report data
     *
     * @param null $startDate
     * @param null $endDate
     * @param null $paymentMethod Payment method ID
     * @return Response
     */
    public function debitReportData(Request $request, $startDate = null, $endDate = null, $paymentMethod = null)
    {
        try {
            if (!is_null($startDate) && !is_null($endDate)) {
                $startDate = Utils::startOfDayUtc($startDate);
                $endDate = Utils::endOfDayUtc($endDate);
                $status = $request->status;
                $requestData = [
                    'currency' => session('currency'),
                    'transaction_type' => TransactionTypes::$debit,
                    'status' => $status,
                    'payment_method' => $paymentMethod,
                    'start_date' => $startDate,
                    'end_date' => $endDate
                ];

                $betPayToken = session('betpay_client_access_token');
                $url = "{$this->betPayURL}/transactions/dates-and-status";
                $curl = Curl::to($url)
                    ->withData($requestData)
                    ->withHeader('Accept: application/json')
                    ->withHeader("Authorization: Bearer $betPayToken")
                    ->get();
                $response = json_decode($curl);

                if ($response->status == Status::$ok) {
                    $transactions = $response->data->transactions;
                    $data = $this->transactionsCollection->formatDebitTransactionsReport($transactions, $paymentMethod, $status);
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
        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'curl' => $curl, 'curl_request' => $requestData]);
            return Utils::failedResponse();
        }
    }

    /**
     *  Delete clients
     *
     * @param int $id Client
     * @return Factory|View
     */
    public function deleteClients($id)
    {
        try {
            $client = (int)$id;
            $betPayToken = session('betpay_client_access_token');
            $requestData = [
                'client' => $client,
            ];

            $url = "{$this->betPayURL}/clients/delete";
            $curl = Curl::to($url)
                ->withData($requestData)
                ->withHeader('Accept: application/json')
                ->withHeader("Authorization: Bearer $betPayToken")
                ->get();

            $data = [
                'title' => _i('Deleted Client'),
                'message' => _i('Client data was delete correctly'),
                'close' => _i('Close')
            ];
            return Utils::successResponse($data);
        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    /**
     * Disable user account
     *
     * @param int $id User account ID
     * @param int $user User ID
     * @return Factory|View
     */
    public function disableUserAccounts($id)
    {
        try {
            $requestData = [
                'user_account_id' => $id
            ];

            $betPayToken = session('betpay_client_access_token');
            $url = "{$this->betPayURL}/users/accounts/disable";
            $curl = Curl::to($url)
                ->withData($requestData)
                ->withHeader('Accept: application/json')
                ->withHeader("Authorization: Bearer $betPayToken")
                ->post();
            $response = json_decode($curl);
            if ($response->status == Status::$ok) {
                $data = [
                    'title' => _i('Account deleted'),
                    'message' => _i('Account data was delete correctly'),
                    'close' => _i('Close')
                ];
                return Utils::successResponse($data);
            } else {

                switch ($response->code) {
                    case BetPayCodes::$user_account_not_found:
                    {
                        $data = [
                            'title' => _i('Account not found'),
                            'message' => _i('The account entered not found in our system'),
                            'close' => _i('Close'),
                        ];
                        break;
                    }
                    default:
                    {
                        $data = [
                            'title' => _i('Failed'),
                            'message' => _i('Please reload and try again'),
                            'close' => _i('Close'),
                        ];
                        break;
                    }
                }
                return Utils::errorResponse(Codes::$forbidden, $data);
            }

        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'user_account' => $id]);
            abort(500);
        }
    }

    /**
     * Show edit view
     *
     * @param int $id whitelabel ID
     * @return Factory|View
     */
    public function editClients($id)
    {
        $betPayToken = session('betpay_client_access_token');
        $requestData = [
            'client' => $id,
        ];
        $url = "{$this->betPayURL}/clients/find";
        $curl = Curl::to($url)
            ->withData($requestData)
            ->withHeader('Accept: application/json')
            ->withHeader("Authorization: Bearer $betPayToken")
            ->get();
        $response = json_decode($curl);
        if ($response->status == Status::$ok) {
            $client = $response->data->client;

            $data = [
                'client' => $client
            ];

            $data['title'] = _i('Update Client');
            return view('back.betpay.clients.edit', $data);

        } else {
            abort(500);
        }
    }

    /**
     * Show edit view
     *
     * @return Factory|View
     */
    public function limitClients()
    {
        try {
            $data['title'] = _i('Client account payment');
            $data['whitelabels'] = $this->whitelabelsRepo->all();
            $data['currency_client'] = $this->currenciesRepo->all();
            $betPayToken = session('betpay_client_access_token');
            $urlPaymentMethodsAll = "{$this->betPayURL}/payment-methods/all";
            $urlClientsAll = "{$this->betPayURL}/clients/all";
            $paymentMethods = [];
            $allClients = [];
            if (!is_null($betPayToken)) {
                $curlPaymentMethodsAll = Curl::to($urlPaymentMethodsAll)
                    ->withHeader('Accept: application/json')
                    ->withHeader("Authorization: Bearer $betPayToken")
                    ->get();

                $curlClientsAll = Curl::to($urlClientsAll)
                    ->withHeader('Accept: application/json')
                    ->withHeader("Authorization: Bearer $betPayToken")
                    ->get();

                $responsePaymentMethodsAll = json_decode($curlPaymentMethodsAll);
                $responseClientsAll = json_decode($curlClientsAll);

                if ($responsePaymentMethodsAll->status == Status::$ok) {
                    $paymentMethods = $responsePaymentMethodsAll->data->payment_methods;
                } else {
                    $paymentMethods = [];
                }

                if ($responseClientsAll->status == Status::$ok) {
                    $clients = $responseClientsAll->data->client;
                    $allClients =  $this->clientsCollection->formatClientsAll($clients);
                } else {
                    $allClients = [];
                }
            }
            $data['payment_methods'] = $paymentMethods;
            $data['clients'] = $allClients;
            return view('back.betpay.clients.payment-limits.index', $data);
        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    /**
     * Limit client data
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function limitClientData(Request $request)
    {
        try{
            $currency = $request->currency;
            $client = $request->client;
            $transactionType = $request->transaction_type;
            $paymentMethod = $request->payment_method;

            $betPayToken = session('betpay_client_access_token');
            $urlClientAccountLimit = "{$this->betPayURL}/clients/accounts/limit";
            $clientAccounts = [];
            if (!is_null($betPayToken)) {
                $requestData = [
                    'currency' => $currency,
                    'client' => $client,
                    'transactionType' => $transactionType,
                    'paymentMethod' => $paymentMethod,
                ];
                $limitAccount = Curl::to($urlClientAccountLimit)
                    ->withData($requestData)
                    ->withHeader('Accept: application/json')
                    ->withHeader("Authorization: Bearer $betPayToken")
                    ->get();
                $responseClientAccount = json_decode($limitAccount);
                if ($responseClientAccount->status == Status::$ok) {
                    $clientAccounts = $responseClientAccount->data->accounts;
                } else {
                    $clientAccounts = [];
                }
            }

            $data = [
                'client' => $clientAccounts
            ];
            return Utils::successResponse($data);
        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'curl_request' => $requestData]);
            return Utils::failedResponse();
        }
    }

    /**
     * Show edit client account
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editClientAccount($id)
    {
        $data['title'] = _i('Edit client account');
        $data['currency_client'] = $this->currenciesRepo->all();
        $data['countries'] = $this->countriesRepo->all();
        $betPayToken = session('betpay_client_access_token');
        $url = "{$this->betPayURL}/clients/accounts/find";
        $urlPaymentMethodsAll = "{$this->betPayURL}/payment-methods/all";
        if (!is_null($betPayToken)) {
            $requestData = [
                'client' => $id,
            ];
            $curl = Curl::to($url)
                ->withData($requestData)
                ->withHeader('Accept: application/json')
                ->withHeader("Authorization: Bearer $betPayToken")
                ->get();
            $response = json_decode($curl);
            if ($response->status == Status::$ok) {
                $clientAccount = $response->data->clientAccount;
            }

            $curlPaymentMethodsAll = Curl::to($urlPaymentMethodsAll)
                ->withHeader('Accept: application/json')
                ->withHeader("Authorization: Bearer $betPayToken")
                ->get();
            $responsePaymentMethodsAll = json_decode($curlPaymentMethodsAll);
            if ($responsePaymentMethodsAll->status == Status::$ok) {
                $paymentMethods = $responsePaymentMethodsAll->data->payment_methods;
            } else {
                $paymentMethods = [];
            }
        }
        $data['client'] = $clientAccount;
        $data['payment_methods'] = $paymentMethods;
        return view('back.betpay.clients.accounts.edit', $data);
    }

    /**
     * Show payment limits
     *
     * @return Factory|View
     */
    public function editPaymentLimits()
    {
        $data['title'] = _i('Create payment limits');
        $data['whitelabels'] = $this->whitelabelsRepo->all();
        $data['currency_client'] = $this->currenciesRepo->all();
        $paymentMethods = [];
        $betPayToken = session('betpay_client_access_token');
        $urlPaymentMethodsAll = "{$this->betPayURL}/payment-methods/all";
        if (!is_null($betPayToken)) {
            $curlPaymentMethodsAll = Curl::to($urlPaymentMethodsAll)
                ->withHeader('Accept: application/json')
                ->withHeader("Authorization: Bearer $betPayToken")
                ->get();
            $responsePaymentMethodsAll = json_decode($curlPaymentMethodsAll);

            if ($responsePaymentMethodsAll->status == Status::$ok) {
                $paymentMethods = $responsePaymentMethodsAll->data->payment_methods;
            }
        }
        $data['payment_methods'] = $paymentMethods;
        return view('back.betpay.clients.payment-limits.create', $data);
    }

    /**
     * Show clients list
     *
     * @return Factory|View
     */
    public function indexClients()
    {
        $data['title'] = _i('BetPay clients');
        return view('back.betpay.clients.index', $data);
    }

    /**
     * Process credit transactions
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function processCreditTransactions(Request $request): Response
    {
        $this->validate($request, [
            'description' => 'required_if:action,0',
            'action' => 'required'
        ]);
        $requestData = null;
        $curl = null;

        try {
            $transaction = $request->transaction;
            $transactionData = $this->transactionsRepo->findByBetPayTransaction($transaction);
            if ($transactionData->transaction_status_id == TransactionStatus::$pending) {
                $betPayToken = session('betpay_client_access_token');
                $action = $request->action;
                $operator = auth()->user()->username;
                $requestData = [
                    'transaction' => $transaction,
                    'description' => $request->description,
                    'action' => $action,
                    'user' => $request->user,
                    'operator' => $operator,
                ];
                $url = "{$this->betPayURL}/transactions/process-credit";
                $curl = Curl::to($url)
                    ->withData($requestData)
                    ->withHeader('Accept: application/json')
                    ->withHeader("Authorization: Bearer $betPayToken")
                    ->post();
                $response = json_decode($curl);

                if ($response->status == Status::$ok) {
                    $betPayTransaction = $response->data->transaction;

                    $transactionAdditionalData = [
                        'betpay_transaction' => $betPayTransaction->id,
                        'provider_transaction' => Str::uuid()->toString(),
                    ];

                    if ($action) {
                        $status = TransactionStatus::$approved;
                        $provider = $request->provider;

                        if ($provider == Providers::$ves_to_usd) {
                            $transactionAdditionalData['ves_amount'] = $betPayTransaction->data->ves_amount;
                            $transactionAdditionalData['rate'] = $betPayTransaction->data->rate;
                            $transactionAdditionalData['commission'] = $betPayTransaction->data->commission;
                        }

                        if ($provider == Providers::$cryptocurrencies) {
                            $transactionAdditionalData['cryptocurrency_amount'] = $betPayTransaction->data->cryptocurrency_amount;
                        }

                        $walletTransaction = Wallet::creditTransactions($betPayTransaction->amount, $provider, $transactionAdditionalData, Actions::$generic, $request->wallet, session('wallet_access_token'));
                        $detailsAdditionalData = [
                            'wallet_transaction' => $walletTransaction->data->transaction->id,
                            'operator' => $operator
                        ];
                        $detailsData = [
                            'data' => json_encode($detailsAdditionalData)
                        ];

                        $userData = [
                            'last_deposit' => Carbon::now(),
                            'last_deposit_amount' => $betPayTransaction->amount,
                            'last_deposit_currency' => $betPayTransaction->currency_iso
                        ];
                        $this->usersRepo->update($request->user, $userData);

                    } else {
                        $status = TransactionStatus::$rejected;
                        $detailsAdditionalData = [
                            'reason' => $request->description
                        ];
                        $detailsData = [
                            'data' => json_encode($detailsAdditionalData)
                        ];
                    }

                    $this->transactionsRepo->storeTransactionsDetails($transactionData->id, $status, $detailsData);
                    $data = [
                        'title' => _i('Deposit processed'),
                        'message' => _i('The deposit was processed correctly'),
                        'close' => _i('Close')
                    ];
                    return Utils::successResponse($data);

                } else {
                    return Utils::errorResponse($response->code, $response->data);
                }
            } else {
                $data = [
                    'title' => _i('Deposit already processed'),
                    'message' => _i('The deposit is already processed. Please refresh the page to update the list'),
                    'close' => _i('Close')
                ];
                return Utils::errorResponse(Codes::$forbidden, $data);
            }
        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'curl' => $curl, 'curl_request' => $requestData]);
            return Utils::failedResponse();
        }
    }

    /**
     * Process debit transactions
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function processDebitTransactions(Request $request)
    {
        $this->validate($request, [
            'reference' => 'required_if:action,1',
            'client_account' => 'required_if:action,1',
            'action' => 'required',
            'description' => 'required_if:action,0',
        ]);
        $requestData = null;
        $curl = null;

        try {
            $user = $request->user;
            $currency = session('currency');
            $wallet = Wallet::getByClient($user, $currency);
            $action = $request->action;
            $provider = $request->provider;
            $transactionData = $this->transactionsRepo->findByBetPayTransaction($request->transaction);

            if ($transactionData->transaction_status_id == TransactionStatus::$pending) {
                if ($wallet->data->wallet->balance_locked == 0 && $action) {
                    $data = [
                        'title' => _i('Withdrawal not processed'),
                        'message' => _i('Before processing a withdrawal you must lock the user\'s balance'),
                        'close' => _i('Close')
                    ];
                    return Utils::errorResponse(Codes::$forbidden, $data);

                } else {
                    $betPayToken = session('betpay_client_access_token');
                    $operator = auth()->user()->username;
                    $requestData = [
                        'transaction' => $request->transaction,
                        'reference' => $request->reference,
                        'action' => $action,
                        'client_account' => $request->client_account,
                        'payment_method' => $request->payment_method,
                        'operator' => $operator,
                    ];

                    $url = "{$this->betPayURL}/transactions/process-debit";
                    $curl = Curl::to($url)
                        ->withData($requestData)
                        ->withHeader('Accept: application/json')
                        ->withHeader("Authorization: Bearer $betPayToken")
                        ->post();
                    $response = json_decode($curl);

                    if ($response->status == Status::$ok) {
                        $betPayTransaction = $response->data->transaction;
                        $transaction = $this->transactionsRepo->findByBetPayTransaction($betPayTransaction->id);
                        $detailsData = [];

                        $transactionAdditionalData = [
                            'betpay_transaction' => $betPayTransaction->id,
                            'reference' => $betPayTransaction->reference,
                            'provider_transaction' => Str::uuid()->toString(),
                        ];

                        if ($action) {
                            $status = TransactionStatus::$approved;
                            $walletTransaction = Wallet::debitUnlockTransactions($betPayTransaction->amount, $provider, $transactionAdditionalData, $wallet->data->wallet->id, session('wallet_access_token'));
                            if ($walletTransaction->status != 'OK') {
                                $data = [
                                    'title' => _i('Error'),
                                    'message' => _i('The amount to be unlock must be the same as the amount locked'),
                                    'close' => _i('Close')
                                ];
                                return Utils::errorResponse(Codes::$forbidden, $data);
                            }
                            $detailsAdditionalData = [
                                'wallet_transaction' => $walletTransaction->data->transaction->id,
                                'operator' => $operator
                            ];
                            $detailsData = [
                                'data' => json_encode($detailsAdditionalData)
                            ];

                            $userData = [
                                'last_debit' => Carbon::now(),
                                'last_debit_amount' => $betPayTransaction->amount,
                                'last_debit_currency' => $betPayTransaction->currency_iso
                            ];
                            $this->usersRepo->update($user, $userData);

                        } else {
                            $status = TransactionStatus::$rejected;
                            $wallet = Wallet::getByClient($transaction->user_id, $transaction->currency_iso);
                            if ($wallet->data->wallet->balance_locked > 0) {
                                $walletTransaction = Wallet::creditUnlockTransactions($betPayTransaction->amount, $provider, $transactionAdditionalData, $wallet->data->wallet->id, session('wallet_access_token'));
                                if ($walletTransaction->status != 'OK') {
                                    $data = [
                                        'title' => _i('Error'),
                                        'message' => _i('The amount to be unlock must be the same as the amount locked'),
                                        'close' => _i('Close')
                                    ];
                                    return Utils::errorResponse(Codes::$forbidden, $data);
                                }
                                $detailsAdditionalData = [
                                    'wallet_transaction' => $walletTransaction->data->transaction->id,
                                    'operator' => $operator,
                                    'reason' => $request->description
                                ];
                                $detailsData = [
                                    'data' => json_encode($detailsAdditionalData)
                                ];
                            }
                        }
                        $this->transactionsRepo->storeTransactionsDetails($transaction->id, $status, $detailsData);
                        $transactionData = [
                            'data' => $transactionAdditionalData
                        ];
                        $this->transactionsRepo->update($transaction->id, $transactionData);
                        $data = [
                            'title' => _i('Withdrawal processed'),
                            'message' => _i('The withdrawal was processed correctly'),
                            'close' => _i('Close')
                        ];
                        return Utils::successResponse($data);

                    } else {
                        switch ($response->code) {
                            case BetPayCodes::$used_reference:
                            {
                                $data = [
                                    'title' => _i('Bank reference in use'),
                                    'message' => _i('The bank reference entered is already registered in our system'),
                                    'close' => _i('Close'),
                                ];
                                break;
                            }
                            default:
                            {
                                $data = [
                                    'title' => _i('Failed transaction'),
                                    'message' => _i('The transaction could not be executed. Please reload and try again'),
                                    'close' => _i('Close'),
                                ];
                                break;
                            }
                        }
                        return Utils::errorResponse(Codes::$forbidden, $data);
                    }
                }
            } else {
                $data = [
                    'title' => _i('Withdrawal already processed'),
                    'message' => _i('The withdrawal is already processed. Please refresh the page to update the list'),
                    'close' => _i('Close')
                ];
                return Utils::errorResponse(Codes::$forbidden, $data);
            }
        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'curl' => $curl, 'curl_request' => $requestData]);
            return Utils::failedResponse();
        }
    }

    /**
     * status Client account
     *
     * @param Request $request
     * @return mixed
     */
    public function statusClientAccount(Request $request)
    {
        try {
            $status = ($request->get('value') === 'true' ? true : false);
            $id = $request->get('id');
            $dataCredentials = [
                'status' => $status,
                'clientAccount_id' => $id
            ];
            $betPayToken = session('betpay_client_access_token');
            $url = "{$this->betPayURL}/clients/accounts/status-accounts";
            $curl = Curl::to($url)
                ->withData($dataCredentials)
                ->withHeader('Accept: application/json')
                ->withHeader("Authorization: Bearer $betPayToken")
                ->get();

            $data = [
                'title' => _i('Client updated'),
                'message' => _i('Client updated successfully'),
                'close' => _i('Close')
            ];
            return Utils::successResponse($data);

        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     * status clients
     *
     * @param Request $request
     * @return mixed
     */
    public function statusClients(Request $request)
    {
        try {
            $dataCredentials = [
            ];
            if ($request->get('name') === 'personal_access_client') {
                $personalAccessClient = ($request->get('value') === 'true' ? true : false);
                $id = $request->get('client_id');
                $dataCredentials = [
                    'personal_access_client' => $personalAccessClient,
                    'id' => $id
                ];

            } elseif ($request->get('name') === 'revoked') {
                $revoked = ($request->get('value') === 'true' ? true : false);
                $id = $request->get('client_id');
                $dataCredentials = [
                    'revoked' => $revoked,
                    'id' => $id
                ];
            } elseif ($request->get('name') === 'password_client') {
                $passwordClient = ($request->get('value') === 'true' ? true : false);
                $id = $request->get('client_id');
                $dataCredentials = [
                    'password_client' => $passwordClient,
                    'id' => $id
                ];
            } else {
                $dataCredentials = [
                ];
            }
            $betPayToken = session('betpay_client_access_token');
            $url = "{$this->betPayURL}/clients/status";
            $curl = Curl::to($url)
                ->withData($dataCredentials)
                ->withHeader('Accept: application/json')
                ->withHeader("Authorization: Bearer $betPayToken")
                ->post();

            $data = [
                'title' => _i('Client updated'),
                'message' => _i('Client updated successfully'),
                'close' => _i('Close')
            ];
            return Utils::successResponse($data);

        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     * status Client account
     *
     * @param Request $request
     * @return mixed
     */
    public function statusPaymentLimits(Request $request)
    {
        try {
            $status = $request->get('value') === 'true';
            $id = $request->get('id');
            $dataCredentials = [
                'status' => $status,
                'clientAccount_id' => $id
            ];
            $betPayToken = session('betpay_client_access_token');
            $url = "{$this->betPayURL}clients/payment-methods/status-payment-methods";
            $curl = Curl::to($url)
                ->withData($dataCredentials)
                ->withHeader('Accept: application/json')
                ->withHeader("Authorization: Bearer $betPayToken")
                ->get();

            $data = [
                'title' => _i('Client updated'),
                'message' => _i('Client updated successfully'),
                'close' => _i('Close')
            ];
            return Utils::successResponse($data);

        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     * Store clients
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function storeClients(Request $request)
    {
        $this->validate($request, [
            'client' => 'required',
            'currency' => 'required',
            'payments' => 'required'
        ]);
        try {
            $betPayToken = session('betpay_client_access_token');
            $whitelabel = $this->whitelabelsRepo->find($request->client);
            $requestData = [
                'name' => $whitelabel->description,
                'endpoint' => 'http://localhost',
            ];
            $url = "{$this->betPayURL}/clients/store";
            $curl = Curl::to($url)
                ->withData($requestData)
                ->withHeader('Accept: application/json')
                ->withHeader("Authorization: Bearer $betPayToken")
                ->post();
            $response = json_decode($curl);
            if ($response->status == Status::$ok) {
                $clientData = $response->data;
                foreach ($request->payments as $payment) {
                    $accountsData = [
                        'client' => $clientData->client_credentials->id,
                        'payment_method' => $payment,
                        'currency' => $request->currency,
                        'data' => [],
                        'status' => false,
                        'transactionType' => $request->transaction_type
                    ];
                    $urlAccounts = "{$this->betPayURL}/clients/accounts/store";
                    $curlAccounts = Curl::to($urlAccounts)
                        ->withData($accountsData)
                        ->withHeader('Accept: application/json')
                        ->withHeader("Authorization: Bearer $betPayToken")
                        ->post();
                }
                $credentialData = [
                    'client_id' => $request->client,
                    'provider_id' => Providers::$betpay,
                    'currency_iso' => $request->currency,
                    'status' => true,
                    'data' => [
                        'client_credentials_grant_id' => $clientData->client_credentials->id,
                        'client_credentials_grant_secret' => $clientData->client_credentials->secret,
                        'password_grant_id' => $clientData->password_credentials->id,
                        'password_grant_secret' => $clientData->password_credentials->secret,
                    ]
                ];

                $this->credentialsRepo->store($credentialData);
                $data = [
                    'title' => _i('Saved credential'),
                    'message' => _i('Credential data was saved correctly'),
                    'close' => _i('Close')
                ];
                return Utils::successResponse($data);
            } elseif ($response->code == 403){
                $data = [
                    'title' => _i('Used client'),
                    'message' => _i('The client data already exists'),
                    'close' => _i('Close'),
                ];
                return Utils::errorResponse(Codes::$forbidden, $data);
            } else {
                $data = [
                    'title' => _i('Failed'),
                    'message' => _i('Please reload and try again'),
                    'close' => _i('Close'),
                ];
                return Utils::errorResponse(Codes::$forbidden, $data);
            }
        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     * Store clients and payment method
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function storeClientsPaymentMethod(Request $request)
    {
        $this->validate($request, [
            'client' => 'required',
            'currency' => 'required',
            'payments' => 'required'
        ]);

        try {
            $betPayToken = session('betpay_client_access_token');
            $whitelabel = $this->whitelabelsRepo->find($request->client);
            $requestData = [
                'name' => $whitelabel->description,
                'endpoint' => 'http://localhost',
            ];
            $url = "{$this->betPayURL}/clients/store";
            $curl = Curl::to($url)
                ->withData($requestData)
                ->withHeader('Accept: application/json')
                ->withHeader("Authorization: Bearer $betPayToken")
                ->post();
            $response = json_decode($curl);
            if ($response->status == Status::$ok) {
                $clientData = $response->data;
                $paymentMethod = $request->payments;
                switch ($paymentMethod) {
                    case PaymentMethods::$cryptocurrencies:
                    {
                        $clientAccountData = [
                            'cryptocurrency' => $request->crypto_currencies,
                            'wallet' => $request->crypto_wallet,
                        ];
                        break;
                    }
                    case PaymentMethods::$zelle:
                    {
                        $clientAccountData = [
                            'email' => $request->account_email,
                            'first_name' => $request->first_name,
                            'last_name' => $request->last_name,
                        ];
                        break;
                    }
                    case PaymentMethods::$wire_transfers:
                    case PaymentMethods::$ves_to_usd:
                    {
                        $clientAccountData = [
                            'bank_id' => $request->bank,
                            'bank_name' => $request->bank_name,
                            'account_number' => $request->account_number,
                            'account_type' => $request->account_type,
                            'social_reason' => $request->social_reason,
                            'dni' => $request->account_dni,
                            'title' => $request->title
                        ];
                        break;
                    }
                    case PaymentMethods::$vcreditos_api:
                    {
                        $clientAccountData = [
                            'vcreditos_user' => $request->vcreditos_user,
                            'vcreditos_secure_id' => $request->vcreditos_secure_id
                        ];
                        break;
                    }
                    case PaymentMethods::$paypal:
                    case PaymentMethods::$skrill:
                    case PaymentMethods::$neteller:
                    case PaymentMethods::$airtm:
                    case PaymentMethods::$uphold:
                    case PaymentMethods::$reserve:
                    {
                        $clientAccountData = [
                            'email' => $request->account_email
                        ];
                        break;
                    }
                    default:
                        $clientAccountData = [];
                        break;
                }

                $accountsData = [
                    'client' => $clientData->client_credentials->id,
                    'payment_method' => $paymentMethod,
                    'currency' => $request->currency,
                    'data' => $clientAccountData,
                    'status' => false,
                    'transactionType' => $request->transaction_type
                ];
                $urlAccounts = "{$this->betPayURL}/clients/accounts/store-client-accounts-payment-methods";
                $curlAccounts = Curl::to($urlAccounts)
                    ->withData($accountsData)
                    ->withHeader('Accept: application/json')
                    ->withHeader("Authorization: Bearer $betPayToken")
                    ->post();

                $credentialData = [
                    'client_id' => $request->client,
                    'provider_id' => Providers::$betpay,
                    'currency_iso' => $request->currency,
                    'status' => true,
                    'data' => [
                        'client_credentials_grant_id' => $clientData->client_credentials->id,
                        'client_credentials_grant_secret' => $clientData->client_credentials->secret,
                        'password_grant_id' => $clientData->password_credentials->id,
                        'password_grant_secret' => $clientData->password_credentials->secret,
                    ]
                ];
                $this->credentialsRepo->store($credentialData);
                $data = [
                    'title' => _i('Saved credential'),
                    'message' => _i('Credential data was saved correctly'),
                    'close' => _i('Close')
                ];
                return Utils::successResponse($data);
            } elseif ($response->code == 403){
                $data = [
                    'title' => _i('Used client'),
                    'message' => _i('The client data already exists'),
                    'close' => _i('Close'),
                ];
                return Utils::errorResponse(Codes::$forbidden, $data);
            } else {
                $data = [
                    'title' => _i('Failed'),
                    'message' => _i('Please reload and try again'),
                    'close' => _i('Close'),
                ];
                return Utils::errorResponse(Codes::$forbidden, $data);
            }
        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     * Store clients account
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function storeClientAccount(Request $request)
    {
        $this->validate($request, [
            'currency' => 'required',
            'payments' => 'required',
        ]);
        //$this->getRulesClientAccountData($request->payments, $request);
        $rules['email'] = 'required';
        $this->validate($request, $rules);
    
        try {
            $credential = $this->credentialsRepo->searchByCredential(Configurations::getWhitelabel(), Providers::$betpay, $request->currency);
            if (!is_null($credential)) {
                $payment = [];
                $paymentMethod = $request->payments;
                $betPayToken = session('betpay_client_access_token');
                $dataPayment = [
                    'payment_method' => $paymentMethod,
                    'currency_iso' => $request->currency
                ];
                $urlPayment = "{$this->betPayURL}/payment-methods/payment-and-currency";
                $curlPayment = Curl::to($urlPayment)
                    ->withData($dataPayment)
                    ->withHeader('Accept: application/json')
                    ->withHeader("Authorization: Bearer $betPayToken")
                    ->get();
                $responsePayment = json_decode($curlPayment);
                if ($responsePayment->status == Status::$ok) {
                    $payment = (array) $responsePayment->data->payment_methods;
                }
                if (!empty($payment)) {
                    $transactionType = $request->transaction_type;
                    foreach($payment as $paymentStatus){
                        $paymentStatusCredit = $paymentStatus->credit;
                        $paymentStatusDebit = $paymentStatus->debit;
                    }
                    if ($transactionType == TransactionTypes::$credit) {
                        if (!$paymentStatusCredit) {
                            $data = [
                                'title' => _i('Payment method not available'),
                                'message' => _i('The payment method is not available for credit'),
                                'close' => _i('Close'),
                            ];
                            return Utils::errorResponse(Codes::$forbidden, $data);
                        }
                    } elseif ($transactionType == TransactionTypes::$debit) {
                        if (!$paymentStatusDebit) {
                            $data = [
                                'title' => _i('Payment method not available'),
                                'message' => _i('The payment method is not available for this debit'),
                                'close' => _i('Close'),
                            ];
                            return Utils::errorResponse(Codes::$forbidden, $data);
                        }
                    } else {
                        if (!$paymentStatusCredit || !$paymentStatusDebit) {
                            $data = [
                                'title' => _i('Payment method not available'),
                                'message' => _i('The payment method is not available for credit or debit'),
                                'close' => _i('Close'),
                            ];
                            return Utils::errorResponse(Codes::$forbidden, $data);
                        }
                    } 

                    $clientAccountData = $this->getClientAccountData($paymentMethod, $request);
                    $accountsData = [
                        'client' => $credential->data->client_credentials_grant_id,
                        'payment_method' => $paymentMethod,
                        'currency' => $request->currency,
                        'data' => $clientAccountData,
                        'status' => true,
                        'transactionType' => $transactionType
                    ];
                    $urlAccounts = "{$this->betPayURL}/clients/accounts/store";
                    $curlAccounts = Curl::to($urlAccounts)
                        ->withData($accountsData)
                        ->withHeader('Accept: application/json')
                        ->withHeader("Authorization: Bearer $betPayToken")
                        ->post();

                    $data = [
                        'title' => _i('Saved credential'),
                        'message' => _i('Credential data was saved correctly'),
                        'close' => _i('Close')
                    ];
                    return Utils::successResponse($data);

                }else{
                    $data = [
                        'title' => _i('Payment method not available'),
                        'message' => _i('The payment method is not available for this currency'),
                        'close' => _i('Close'),
                    ];
                    return Utils::errorResponse(Codes::$forbidden, $data);
                }
            } else {
                $data = [
                    'title' => _i('Credentials error'),
                    'message' => _i('Customer has no credentials'),
                    'close' => _i('Close'),
                ];
                return Utils::errorResponse(Codes::$forbidden, $data);
            }
        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     * Get Client Account Data
     * @param int $paymentMethod PaymentMethods
     * @param Request $request
     *
     * @return array
     */
    private function getClientAccountData($paymentMethod, $request)
    {
        $clientAccountDataFunctions = [
            PaymentMethods::$binance => function ($request) {
                return [
                    'cryptocurrency' => $request->crypto_currencies,
                    'email' => $request->email,
                    'pay_id' => $request->pay_id,
                    'qr' => $request->qr,
                    'binance_id' => $request->binance_id,
                    'phone' => $request->phone,
                ];
            },
        ];
        $clientAccountDataFunction = $clientAccountDataFunctions[$paymentMethod] ?? function () {
            return [];
        };
        return $clientAccountDataFunction($request);
    }

    /**
     * Get Rules Client Account Data
     * @param int $paymentMethod PaymentMethods
     *
     * @return array
     */
    private function getRulesClientAccountData($paymentMethod, $request)
    {

        $rulesClientAccountDataFunctions = [
            PaymentMethods::$binance => [
                $rules['cryptocurrency'] = 'required',
                $rules['email'] = 'required',
                $rules['phone'] = 'required',
                $rules['pay_id'] = 'required',
                $rules['binance_id'] = 'required'
            ]
        ];
        $rulesClientAccountDataFunctions = $this->validate($request, $rulesClientAccountDataFunctions[$paymentMethod]) ?? function () {
            return [];
        };
        return $rulesClientAccountDataFunctions();
    }

    /**
     * Get transactions data
     * @param int $paymentMethod PaymentMethods
     * @param int $provider Provider ID
     * @param int $transactionType TransactionTypes
     *
     * @return Response
     */
    public function transactionsData($paymentMethod, $provider, $transactionType)
    {
        $requestData = null;
        $curl = null;
        $status = TransactionStatus::$pending;
        try {
            $betPayToken = session('betpay_client_access_token');
            $requestData = [
                'currency' => session('currency'),
                'transaction_type' => $transactionType,
                'status' => $status,
                'payment_method' => $paymentMethod
            ];

            $url = "{$this->betPayURL}/transactions/status";
            $curl = Curl::to($url)
                ->withData($requestData)
                ->withHeader('Accept: application/json')
                ->withHeader("Authorization: Bearer $betPayToken")
                ->get();
            $response = json_decode($curl);
            if ($response->status == Status::$ok) {
                $transactions = $response->data->transactions;

                if ($transactionType == TransactionTypes::$credit) {
                    $this->transactionsCollection->formatCreditTransactions($transactions, $paymentMethod);
                } else {
                    $this->transactionsCollection->formatDebitTransactions($transactions, $paymentMethod, $provider);
                }

                $data = [
                    'transactions' => $transactions
                ];
                return Utils::successResponse($data);

            } else {
                return Utils::errorResponse($response->code, $response->data);
            }

        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'curl' => $curl, 'curl_request' => $requestData]);
            return Utils::failedResponse();
        }
    }

    /**
     * Get transactions data by code
     * @param int $paymentMethod PaymentMethods
     * @param int $provider Provider ID
     * @param int $transactionType TransactionTypes
     *
     * @return Response
     */
    public function transactionsDataByCode($paymentMethod, $provider, $transactionType, $code = null)
    {
        $requestData = null;
        $curl = null;
        $status = TransactionStatus::$pending;
        try {
            if(!is_null($code)){
                $betPayToken = session('betpay_client_access_token');
                $requestData = [
                    'currency' => session('currency'),
                    'transaction_type' => $transactionType,
                    'status' => $status,
                    'code' => $code,
                    'payment_method' => $paymentMethod
                ];
                $url = "{$this->betPayURL}/transactions/code";
                $curl = Curl::to($url)
                    ->withData($requestData)
                    ->withHeader('Accept: application/json')
                    ->withHeader("Authorization: Bearer $betPayToken")
                    ->get();
                $response = json_decode($curl);
                if ($response->status == Status::$ok) {
                    $transactions = $response->data->transactions;
                    $transactionType == TransactionTypes::$debit;
                    $this->transactionsCollection->formatDebitTransactions($transactions, $paymentMethod, $provider);
                    $data = [
                        'transactions' => $transactions
                    ];
                } else {
                    return Utils::errorResponse($response->code, $response->data);
                }
            }else{
                $data = [
                    'transactions' => []
                ];
            }

            return Utils::successResponse($data);

        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'curl' => $curl, 'curl_request' => $requestData]);
            return Utils::failedResponse();
        }
    }

    /**
     * Update clients
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function updateClients(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'endpoint' => 'required',
            'secret' => 'required',
        ]);

        try {
            $betPayToken = session('betpay_client_access_token');
            $requestData = [
                'name' => $request->name,
                'endpoint' => $request->endpoint,
                'secret' => $request->secret,
                'client' => $request->id,
            ];
            $url = "{$this->betPayURL}/clients/update";
            $curl = Curl::to($url)
                ->withData($requestData)
                ->withHeader('Accept: application/json')
                ->withHeader("Authorization: Bearer $betPayToken")
                ->post();
            $data = [
                'title' => _i('Updated client'),
                'message' => _i('Client data was updated correctly'),
                'close' => _i('Close')
            ];
            return Utils::successResponse($data);
        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'curl' => $curl, 'curl_request' => $requestData]);
            return Utils::failedResponse();
        }
    }

    /**
     * Update client account
     *
     * @param Request $request
     * @return mixed
     */
    public function updateClientAccount(Request $request)
    {
        try {

            $paymentMethod = $request->payments;
            switch ($paymentMethod) {
                case PaymentMethods::$cryptocurrencies:
                {
                    $dataAccount = [
                        'cryptocurrency' => $request->crypto_currencies,
                        'wallet' => $request->crypto_wallet,
                    ];
                    break;
                }
                case PaymentMethods::$zelle:
                {
                    $dataAccount = [
                        'email' => $request->account_email,
                        'first_name' => $request->first_name,
                        'last_name' => $request->last_name,
                    ];
                    break;
                }
                case PaymentMethods::$wire_transfers:
                case PaymentMethods::$ves_to_usd:
                {
                    if (is_null($request->bank) && isset($request->bank_name)) {
                        $bank_id = $request->old_bank_id;
                        $bank_name = $request->old_bank_name;
                    } elseif($request->bank == $request->old_bank_id  && $request->bank_name == $request->old_bank_name){
                        $bank_id = $request->old_bank_id;
                        $bank_name = $request->old_bank_name;
                    } else {
                        $bank_id = $request->bank_id;
                        $bank_name = $request->bank_name;
                    }
                    $dataAccount = [
                        'bank_id' => $bank_id,
                        'bank_name' => $bank_name,
                        'account_number' => $request->account_number,
                        'account_type' => $request->account_type,
                        'social_reason' => $request->social_reason,
                        'dni' => $request->account_dni,
                        'title' => $request->title
                    ];
                    break;
                }
                case PaymentMethods::$vcreditos_api:
                {
                    $dataAccount = [
                        'vcreditos_user' => $request->vcreditos_user,
                        'vcreditos_secure_id' => $request->vcreditos_secure_id
                    ];
                    break;
                }
                case PaymentMethods::$paypal:
                case PaymentMethods::$skrill:
                case PaymentMethods::$neteller:
                case PaymentMethods::$airtm:
                case PaymentMethods::$uphold:
                case PaymentMethods::$reserve:
                {
                    $dataAccount = [
                        'email' => $request->account_email
                    ];
                    break;
                }
                case PaymentMethods::$just_pay:
                {
                    $dataAccount = [
                        'public_key' => $request->public_key,
                        'secret_key' => $request->secret_key,
                        'username' => $request->username,
                        'password' => $request->password,
                    ];
                    break;
                }
                default:
                    $dataAccount = [];
                    break;
            }

            $dataRequest = [
                'id' => $request->client_account,
                'payment_method_id' => $request->payments,
                'currency_iso' => $request->currency,
                'status' => $request->status,
                'data' => $dataAccount
            ];
            $betPayToken = session('betpay_client_access_token');
            $url = "{$this->betPayURL}/clients/accounts/update";
            $curl = Curl::to($url)
                ->withData($dataRequest)
                ->withHeader('Accept: application/json')
                ->withHeader("Authorization: Bearer $betPayToken")
                ->post();

            $response = json_decode($curl);

            if ($response->status == Status::$ok) {
                $data = [
                    'title' => _i('Updated account'),
                    'message' => _i('Account data was updated correctly'),
                    'close' => _i('Close')
                ];
                return Utils::successResponse($data);
            } else {
                $data = [
                    'title' => _i('Failed'),
                    'message' => _i('Please reload and try again'),
                    'close' => _i('Close'),
                ];
                return Utils::errorResponse(Codes::$forbidden, $data);
            }

        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     * Update client limit
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateLimit(Request $request)
    {
        $this->validate($request, [
            'status' => 'required',
            'min' => 'required',
            'max' => 'required',
        ]);

        try {
            $betPayToken = session('betpay_client_access_token');
            $urlClientAccountUpdateLimit = "{$this->betPayURL}/clients/payment-methods/update-limit";
            if (!is_null($betPayToken)) {
                $requestData = [
                    'clientPaymentMethodId' => $request->client,
                    'status' => $request->status,
                    'min' => $request->min,
                    'max' => $request->max,
                    'currency' => $request->currency,
                    'transaction_type' => $request->transaction_type,
                ];
                $curl = Curl::to($urlClientAccountUpdateLimit)
                    ->withData($requestData)
                    ->withHeader('Accept: application/json')
                    ->withHeader("Authorization: Bearer $betPayToken")
                    ->post();

                $response = json_decode($curl);
                return Utils::successResponse($response->data);

            }

        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'curl' => $curl, 'curl_request' => $requestData]);
            return Utils::failedResponse();
        }
    }

    /**
     * Update user accounts
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function updateUserAccounts(Request $request)
    {
        $this->validate($request, [
            'payment_method' => 'required'
        ]);

        try {
            $paymentMethod = $request->payment_method;
            $user = $request->user;

            switch ($paymentMethod) {
                case PaymentMethods::$cryptocurrencies:
                {
                    $requestData = [
                        'cryptocurrency' => $request->crypto_currencies,
                        'wallet' => $request->crypto_wallet,
                        'network' => $request->network
                    ];
                    break;
                }
                case PaymentMethods::$zelle:
                {
                    $requestData = [
                        'email' => $request->account_email,
                        'first_name' => $request->first_name_account,
                        'last_name' => $request->last_name_account,
                    ];
                    break;
                }
                case PaymentMethods::$wire_transfers:
                case PaymentMethods::$ves_to_usd:
                {
                    $requestData = [
                        'bank_id' => $request->bank_id,
                        'bank_name' => $request->bank_name,
                        'account_number' => $request->account_number,
                        'account_type' => $request->account_type,
                        'social_reason' => $request->social_reason,
                        'dni' => $request->account_dni
                    ];
                    break;
                }
                case PaymentMethods::$vcreditos_api:
                {
                    $requestData = [
                        'vcreditos_user' => $request->vcreditos_user,
                        'vcreditos_secure_id' => $request->vcreditos_secure_id
                    ];
                    break;
                }
                case PaymentMethods::$paypal:
                case PaymentMethods::$skrill:
                case PaymentMethods::$neteller:
                case PaymentMethods::$airtm:
                case PaymentMethods::$uphold:
                case PaymentMethods::$reserve:
                {
                    $requestData = [
                        'email' => $request->account_email
                    ];
                    break;
                }
                case PaymentMethods::$bizum:
                {
                    $requestData = [
                        'name' => $request->bizum_name,
                        'phone' => $request->bizum_phone
                    ];
                }
                case PaymentMethods::$binance:
                {
                    $requestData = [
                        'email' => $request->binance_email,
                        'phone' => $request->binance_phone,
                        'pay_id' => $request->binance_pay_id,
                        'binance_id' => $request->binance_id
                    ];
                }
            }

            $requestData['currency'] = session('currency');
            $requestData['payment_method'] = $paymentMethod;
            $requestData['id'] = $request->user_account_id;

            $betPayToken = session('betpay_client_access_token');
            $url = "{$this->betPayURL}/users/accounts/update";
            $curl = Curl::to($url)
                ->withData($requestData)
                ->withHeader('Accept: application/json')
                ->withHeader("Authorization: Bearer $betPayToken")
                ->post();
            $response = json_decode($curl);
            if ($response->status == Status::$ok) {
                $data = [
                    'title' => _i('Updated account'),
                    'message' => _i('Account data was updated correctly'),
                    'route' => route('users.details', [$user]),
                    'close' => _i('Close')
                ];
                return Utils::successResponse($data);
            } else {

                switch ($response->code) {
                    case BetPayCodes::$duplicate_account:
                    {
                        $data = [
                            'title' => _i('Account in use'),
                            'message' => _i('The account entered is already registered in our system'),
                            'close' => _i('Close'),
                        ];
                        break;
                    }
                    default:
                    {
                        $data = [
                            'title' => _i('Failed'),
                            'message' => _i('Please reload and try again'),
                            'close' => _i('Close'),
                        ];
                        break;
                    }
                }
                return Utils::errorResponse(Codes::$forbidden, $data);
            }
        } catch (Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'curl' => $curl, 'curl_request' => $requestData]);
            return Utils::failedResponse();
        }
    }
}

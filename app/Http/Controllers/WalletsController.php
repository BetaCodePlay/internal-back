<?php

namespace App\Http\Controllers;

use App\Users\Repositories\UserCurrenciesRepo;
use App\Wallets\Collections\TransactionsCollection;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Configurations\Enums\Codes;
use Dotworkers\Configurations\Utils;
use Dotworkers\Store\Store;
use Dotworkers\Wallet\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class WalletsController
 *
 *  This class allows to manage wallet requests
 *
 * @package App\Http\Controllers
 * @author  Eborio Linarez
 */
class WalletsController extends Controller
{
    /**
     * @var TransactionsCollection
     */
    private $transactionsCollection;

    private $userCurrenciesRepo;

    /**
     * WalletsController constructor
     *
     * @param TransactionsCollection $transactionsCollection
     * @param UserCurrenciesRepo $userCurrenciesRepo
     */
    public function __construct(TransactionsCollection $transactionsCollection, UserCurrenciesRepo $userCurrenciesRepo)
    {
        $this->transactionsCollection = $transactionsCollection;
        $this->userCurrenciesRepo     = $userCurrenciesRepo;
    }

    /**
     * Create whitelabel
     *
     * @param UserCurrenciesRepo $userCurrenciesRepo
     * @param int $user User ID
     * @param string $username Username
     * @param string $uuid User UUID
     * @param string $currency User currency
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(UserCurrenciesRepo $userCurrenciesRepo, $user, $username, $uuid, $currency)
    {
        try {
            $whitelabel = Configurations::getWhitelabel();
            $wallet     = Wallet::store(
                $user,
                $username,
                $uuid,
                $currency,
                $whitelabel,
                session('wallet_access_token')
            );

            $userData   = [
                'user_id'      => $user,
                'currency_iso' => $currency
            ];
            $walletData = [
                'wallet_id' => $wallet->data->wallet->id,
                'default'   => false
            ];
            $userCurrenciesRepo->store($userData, $walletData);

            $store = Configurations::getStore()->active;
            if ($store) {
                Store::storeWallet($user, $currency);
            }
        } catch (\Exception $ex) {
            \Log::error(
                __METHOD__,
                [
                    'exception'  => $ex,
                    'user'       => $user,
                    'username'   => $username,
                    'uuid'       => $uuid,
                    'currency'   => $currency,
                    'whitelabel' => $whitelabel
                ]
            );
        }
        return redirect()->back();
    }

    /**
     * Lock user balance
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function lockBalance(Request $request)
    {
        try {
            $user       = $request->user;
            $wallet     = $request->wallet;
            $amount     = $request->amount;
            $provider   = $request->provider;
            $currency   = session('currency');
            $walletData = Wallet::getByClient($user, $currency);

            if ($walletData->data->wallet->balance >= $amount) {
                $transactionData = [
                    'provider_transaction' => Str::uuid()->toString()
                ];
                $lockBalance     = Wallet::debitLockTransactions($amount, $provider, $transactionData, $wallet);
                if ($lockBalance->status == 'OK') {
                    $data = [
                        'title'   => _i('Balance locked'),
                        'message' => _i('User balance was successfully locked'),
                        'close'   => _i('Close')
                    ];
                    return Utils::successResponse($data);
                } else {
                    $data = [
                        'title'   => _i('Error'),
                        'message' => _i('It is not allowed to lock balance if the user already has a locked amount'),
                        'close'   => _i('Close')
                    ];
                    return Utils::errorResponse(Codes::$forbidden, $data);
                }
            } else {
                $data = [
                    'title'   => _i('Insufficient balance'),
                    'message' => _i("The user's balance is less than the one you want to block"),
                    'close'   => _i('Close')
                ];
                return Utils::errorResponse(Codes::$forbidden, $data);
            }
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Get transactions by user
     *
     * @param int $wallet Wallet ID
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function transactions(Request $request, $wallet = null)
    {
        try {
            $offset = $request->has('start') ? $request->get('start') : 0;
            $limit  = $request->has('length') ? $request->get('length') : 2000;
            $data   = [];
            if (! is_null(($wallet))) {
                $transactions     = Wallet::getTransactionsByWalletAndClient($wallet, $limit, $offset);
                $transactionsData = $transactions->data->transactions;
                //Log::info('transactions user',[$transactions->data]);
                $this->transactionsCollection->formatTransactions($transactionsData);
                //$data = $this->transactionsCollection->formatTransactionsPage($transactionsData,2000,$request);
                $data = [
                    'transactions' => $transactionsData
                ];
            } else {
                $data = [
                    'transactions' => []
                ];
            }
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Get transactions by user historic
     *
     * @param int $wallet Wallet ID
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function transactionsHistoric($wallet = null)
    {
        try {
            if (! is_null(($wallet))) {
                $transactions     = Wallet::getTransactionsByWalletAndClientHistoric(
                    $wallet,
                    $limit = 2000,
                    $offset = 0
                );
                $transactionsData = $transactions->data->transactions;
                $this->transactionsCollection->formatTransactions($transactionsData);
                $data = [
                    'transactions' => $transactionsData
                ];
            } else {
                $data = [
                    'transactions' => []
                ];
            }
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }


    /**
     * Retrieves and formats Assiria transactions.
     *
     * This method retrieves transactions from the Assiria collection and formats them according to
     * Assiria specifications.
     *
     * @param Request $request The HTTP request containing necessary data for processing.
     *
     * @return array|Response The formatted transaction data or an error response.
     *
     * @throws ContainerExceptionInterface If a container exception occurs during processing.
     * @throws NotFoundExceptionInterface If a dependency is not found during processing.
     */
    public function transactionsAssiria(Request $request)
    : array|Response {
        \Log::info(__METHOD__, ['$request' => $request]);
        $wallet = $this->userCurrenciesRepo->findDefault($request->input('userId'));
        dd($wallet);
        return $this->transactionsCollection->formatTransactionsAssiria($request);
    }
}

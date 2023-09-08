<?php

namespace App\Agents\Services;

use App\Agents\Enums\AgentType;
use App\Agents\Repositories\AgentsRepo;
use App\Agents\Services\App\Models\User;
use App\Core\Repositories\TransactionsRepo;
use App\Http\Requests\TransactionRequest;
use App\Users\Enums\ActionUser;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Configurations\Enums\Codes;
use Dotworkers\Configurations\Enums\Providers;
use Dotworkers\Configurations\Enums\TransactionStatus;
use Dotworkers\Configurations\Enums\TransactionTypes;
use Dotworkers\Configurations\Utils;
use Dotworkers\Wallet\Wallet;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

/**
 *
 */
class TransactionService
{

    public function __construct(
        private AgentsRepo       $agentsRepo,
        private TransactionsRepo $transactionsRepo,
    )
    {
    }

    /**
     * @param $transactionType
     * @param $amount
     * @param $ownerAgent
     * @return Response|null
     */
    public function checkInsufficientBalance($transactionType, $amount, $ownerAgent): ?Response
    {
        $isCreditTransaction = $transactionType == TransactionTypes::$credit;
        $isWolfAgent = $ownerAgent->username == AgentType::WOLF;

        if ($isCreditTransaction && $amount > $ownerAgent->balance && !$isWolfAgent) {
            return Utils::errorResponse(Codes::$forbidden, [
                'title' => _i('Insufficient balance'),
                'message' => _i("The agent's operational balance is insufficient to perform the transaction"),
                'close' => _i('Close')
            ]);
        }

        return null;
    }

    public function isGameAmountGreaterThanBalance($request, $walletDetail): bool|Response
    {
        if ($request->get('amount') > $walletDetail->data->wallet->balance) {
            $data = [
                'title' => _i('Insufficient balance'),
                'message' => _i("The user's balance is insufficient to perform the transaction"),
                'close' => _i('Close')
            ];
            return Utils::errorResponse(Codes::$forbidden, $data);
        }

        return false;
    }

    /**
     * @param $user
     * @return bool|Response
     */
    public function isUserBlocked($user): bool|Response
    {
        if ($user->action == ActionUser::$locked_higher) {
            return Utils::errorResponse(Codes::$not_found, [
                'title' => _i('Blocked by a superior!'),
                'message' => _i('Contact your superior...'),
                'close' => _i('Close')
            ]);
        }

        return false;
    }

    /**
     * Handle the case of an empty wallet and return an appropriate response.
     *
     * This method checks if the wallet is empty for a specific user and currency.
     * If the wallet is empty, it logs an error and returns a forbidden response.
     * Otherwise, it returns false, indicating that the wallet is not empty.
     *
     * @param TransactionRequest $request The HTTP request object.
     * @param string $currency The currency for which to check the wallet.
     *
     * @return bool|\Illuminate\Http\Response False if the wallet is not empty, or a forbidden
     *                                        response if the wallet is empty.
     */
    public function handleEmptyWallet(TransactionRequest $request, string $currency, $transaction): bool|Response
    {
        if (empty($transaction) || empty($transaction->data)) {
            Log::error('error data, wallet getByClient', [
                'currency' => $currency,
                'request' => $request->all(),
                'userAuthId' => $request->user()->id,
                'transaction' => $transaction,
            ]);

            return Utils::errorResponse(Codes::$forbidden, [
                'title' => _i('An error occurred'),
                'message' => _i('please contact support'),
                'close' => _i('Close')
            ]);
        }

        return false;
    }


    public function managePlayerUser(TransactionRequest $request)
    {
        $userToAddBalance = $request->get('user');
        $playerDetails = $this->agentsRepo->findUser($userToAddBalance);
        $userIsBlocked = $this->isUserBlocked($playerDetails);
        if ($userIsBlocked instanceof Response) {
            return $userIsBlocked;
        }

        $currency = session('currency');
        $walletDetail = Wallet::getByClient($playerDetails->id, $currency);
        $walletHandlingResult = $this->handleEmptyWallet($request, $currency, $walletDetail);

        if ($walletHandlingResult instanceof Response) {
            return $walletHandlingResult;
        }

        $transactionType = $request->get('transaction_type');

        if ($transactionType == TransactionTypes::$credit) {
            $creditTransactionResult = $this->performCreditTransaction($request, $playerDetails, $walletDetail);

            if ($creditTransactionResult instanceof Response) {
                return $walletHandlingResult;
            }

            $ownerBalance = $creditTransactionResult->ownerBalance;
            $agentBalanceFinal = $creditTransactionResult->agentBalanceFinal;
            $transaction = $creditTransactionResult->transaction;
            $additionalData = $creditTransactionResult->additionalData;
        }

        if ($transactionType == TransactionTypes::$debit) {
            $isAmountGreaterThanBalance = $this->isGameAmountGreaterThanBalance($request, $walletDetail);

            if ($isAmountGreaterThanBalance instanceof Response) {
                return $isAmountGreaterThanBalance;
            }

            $debitTransactionResult = $this->performDebitTransaction($request, $playerDetails, $walletDetail);

            if ($debitTransactionResult instanceof Response) {
                return $debitTransactionResult;
            }

            $ownerBalance = $debitTransactionResult->ownerBalance;
            $transaction = $debitTransactionResult->transaction;
            $additionalData = $debitTransactionResult->additionalData;
        }

        $transactionData = [
            'user_id' => $userToAddBalance,
            'amount' => $request->get('amount'),
            'currency_iso' => $currency,
            'transaction_type_id' => $transactionType,
            'transaction_status_id' => TransactionStatus::$approved,
            'provider_id' => Providers::$agents_users,
            'data' => $additionalData ?? [],
            'whitelabel_id' => Configurations::getWhitelabel()
        ];

        $ticket = $this->transactionsRepo->store($transactionData, TransactionStatus::$approved, []);

        $response = $this->handleEmptyWallet($request, $currency, $ticket);

        if ($response instanceof Response) {
            return $response;
        }

        $button = sprintf(
            '<a class="btn u-btn-3d u-btn-blue btn-block" id="ticket" href="%s" target="_blank">%s</a>',
            route('agents.ticket', [$ticket->id]),
            _i('Print ticket')
        );

        return arrayToObject([
            'agentBalanceFinal' => $agentBalanceFinal ?? 0,
            'ownerBalance' => $ownerBalance ?? 0,
            'transactionIdCreated' => $ticket->id,
            'balance' => $transaction?->data?->wallet?->balance ?? 0,
            'status' => $transaction?->status ?? '',
            'button' => $button
        ]);
    }

    public function performCreditTransaction($request, $userData, $walletDetail)
    {
        $currency = session('currency');
        $transactionAmount = $request->get('amount');
        $ownerAgent = $this->agentsRepo->findByUserIdAndCurrency($request->user()->id, $currency);

        $additionalData = [
            'provider_transaction' => Str::uuid()->toString(),
            'from' => $ownerAgent->username,
            'to' => $userData->username
        ];

        $transaction = Wallet::creditManualTransactions(
            $transactionAmount,
            Providers::$agents_users,
            $additionalData,
            $request->get('wallet'),
        );

        $walletHandlingResult = $this->handleEmptyWallet($request, $currency, $transaction);

        if ($walletHandlingResult instanceof Response) {
            return $walletHandlingResult;
        }

        return arrayToObject([
            'additionalData' => $additionalData,
            'agentBalanceFinal' => $walletDetail->data->wallet->balance,
            'ownerBalance' => $ownerAgent->balance - $transactionAmount,
            'transaction' => $transaction,
        ]);
    }

    public function performDebitTransaction($request, $userData, $walletDetail)
    {
        $currency = session('currency');
        $transactionAmount = $request->get('amount');
        $ownerAgent = $this->agentsRepo->findByUserIdAndCurrency($request->user()->id, $currency);

        $additionalData = [
            'provider_transaction' => Str::uuid()->toString(),
            'from' => $ownerAgent->username,
            'to' => $userData->username
        ];

        $transaction = Wallet::debitManualTransactions(
            $transactionAmount,
            Providers::$agents_users,
            $additionalData,
            $walletDetail
        );

        $walletHandlingResult = $this->handleEmptyWallet($request, $currency, $transaction);

        if ($walletHandlingResult instanceof Response) {
            return $walletHandlingResult;
        }

        return arrayToObject([
            'additionalData' => $additionalData,
            'ownerBalance' => $ownerAgent->balance + $transactionAmount,
            'transaction' => $transaction,
        ]);
    }


    /**
     * Send an error response for self-transaction attempt.
     *
     * This method generates and returns an error response with a specific message
     * when a user attempts to make a transaction to themselves.
     *
     * @return Response
     */
    public static function sendSelfTransactionError(): Response
    {
        return Utils::errorResponse(Codes::$forbidden, [
            'title' => _i('Error'),
            'message' => _i('You cannot make transactions to yourself'),
            'close' => _i('Close')
        ]);
    }

}

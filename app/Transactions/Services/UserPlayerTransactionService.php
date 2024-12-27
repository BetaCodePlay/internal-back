<?php

namespace App\Transactions\Services;

use App\Agents\Repositories\AgentsRepo;
use App\Core\Repositories\TransactionsRepo;
use App\Http\Requests\TransactionRequest;
use Dotworkers\Bonus\Bonus;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Configurations\Enums\Codes;
use Dotworkers\Configurations\Enums\Providers;
use Dotworkers\Configurations\Enums\TransactionTypes;
use Dotworkers\Wallet\Wallet;
use Symfony\Component\HttpFoundation\Response;

/**
 *
 */
class UserPlayerTransactionService extends BaseTransactionService
{
    /**
     * @param AgentsRepo $agentsRepo
     */
    public function __construct(
        private AgentsRepo $agentsRepo
    ) {
        parent::__construct(new TransactionsRepo);
    }

    /**
     * Manage player user transactions.
     *
     * This method orchestrates the processing of transactions for a player user based on
     * the provided TransactionRequest and the user's balance.
     *
     * @param TransactionRequest $request The request object containing transaction details.
     * @return mixed The response object indicating the result of the transaction.
     *                  It can be a success response or an error response.
     */
    public function processTransaction(TransactionRequest $request)
    : mixed {
        $userToAddBalance = $request->get('user');
        $playerDetails    = $this->agentsRepo->findUser($userToAddBalance);
        $userIsBlocked    = $this->isUserBlocked($playerDetails);

        if ($userIsBlocked instanceof Response) {
            return $userIsBlocked;
        }

        $currency             = session('currency');
        $bonus                = Configurations::getBonus(Configurations::getWhitelabel());

        $walletDetail         = Wallet::getByClient($playerDetails->id, $currency, $bonus);
        $walletHandlingResult = $this->handleEmptyTransactionObject($request, $walletDetail);

        if ($walletHandlingResult instanceof Response) {
            return $walletHandlingResult;
        }

        if ($request->get('transaction_type') == TransactionTypes::$credit) {
            $startTime = microtime(true);
            $creditTransactionResult = $this->processCreditTransaction($request, $playerDetails, $walletDetail);
            $endTime = microtime(true);
            $executionTime = $endTime - $startTime;
            \Log::info('$creditTransactionResult', [
                'executionTime' =>$executionTime
            ]);
            return $this->processAndStoreTransaction($request, $creditTransactionResult, Providers::$agents_users);
        }

        $isAmountGreaterThanBalance = $this->isGameAmountGreaterThanBalance($request, $walletDetail);

        if ($isAmountGreaterThanBalance instanceof Response) {
            return $isAmountGreaterThanBalance;
        }
        $startTime = microtime(true);
        $debitTransactionResult = $this->processDebitTransaction($request, $playerDetails, $walletDetail);
        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;
        \Log::info('$debitTransactionResult', [
            'executionTime' =>$executionTime
        ]);
        return $this->processAndStoreTransaction($request, $debitTransactionResult, Providers::$agents_users);
    }

    /**
     * Process a bonus transaction for a player.
     *
     * This method handles a bonus transaction for a given player and updates their balance.
     *
     * @param string $typeTransaction The type of transaction (e.g., 'credit' or 'debit').
     * @param object $playerDetails An object containing player details.
     * @param float $transactionAmount The transaction amount.
     * @param object $walletDetail An object containing wallet details.
     *
     * @return float The updated bonus balance of the player.
     */
    public function processBonus(
        string $typeTransaction,
        object $playerDetails,
        float $transactionAmount,
        object $walletDetail
    ) {
        if (Configurations::getBonus(Configurations::getWhitelabel())) {
            if ($typeTransaction == TransactionTypes::$credit) {
                // Deposit Bonus Agents
                Bonus::depositBonusAgents(
                    Configurations::getWhitelabel(),
                    session('currency'),
                    $playerDetails->id,
                    $walletDetail->data->bonus[0]->id,
                    session('wallet_access_token'),
                    $transactionAmount
                );

                // Unlimited Deposit Bonus
                Bonus::unlimitedDepositBonus(
                    Configurations::getWhitelabel(),
                    session('currency'),
                    $playerDetails->id,
                    $walletDetail->data->bonus[0]->id,
                    session('wallet_access_token'),
                    $transactionAmount
                );
            } else {
                Bonus::removeBalanceBonus($walletDetail->data->bonus[0]->id, $playerDetails->id);
            }

            // Update Balance Wallet
            $updateBalanceBonus = Wallet::getByClient($playerDetails->id, session('currency'), true);
            return $updateBalanceBonus->data->bonus[0]->balance;
        }
    }


    /**
     * Process a credit transaction for a player user.
     *
     * This method handles the processing of a credit transaction for a player user,
     * including generating additional data, performing the transaction, and updating balances.
     *
     * @param TransactionRequest $request The request object containing transaction details.
     * @param object $playerDetails The player details object.
     * @param object $walletDetail The wallet detail object.
     *
     * @return mixed An object containing additional data, agent and owner balances, and transaction information
     *               or a Response object in case of an error.
     */
    public function processCreditTransaction(
        TransactionRequest $request,
        object $playerDetails,
        object $walletDetail
    )
    : mixed {
        $currency          = session('currency');
        $whitelabel        = Configurations::getWhitelabel();
        $bonus             = Configurations::getBonus($whitelabel);
        $transactionAmount = $request->get('amount');
        $userAuthId        = $request->user()->id;
        $startTime = microtime(true);
        $ownerAgent        = $this->agentsRepo->findByUserIdAndCurrency($userAuthId, $currency);
        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;
        \Log::info('$ownerAgent', [
            'executionTime' =>$executionTime
        ]);
        $startTime = microtime(true);
        $transactionResult = Wallet::creditManualTransactions(
            $transactionAmount,
            Providers::$agents_users,
            $this->generateAdditionalTransactionData($ownerAgent, $playerDetails),
            $request->get('wallet'),
        );
        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;
        \Log::info('$transactionResult', [
            'executionTime' =>$executionTime
        ]);
        if (($walletDetail && isset($walletDetail->data->bonus))) {
            $balanceBonus = $this->processBonus(
                TransactionTypes::$credit,
                $playerDetails,
                $transactionAmount,
                $walletDetail
            );
        } else {
            $walletBonus = Wallet::store(
                $playerDetails->id,
                $playerDetails->username,
                $playerDetails->uuid,
                $currency,
                $whitelabel,
                session('wallet_access_token'),
                $bonus
            );
            if ($walletBonus->code == Codes::$ok) {
                $walletDetail = Wallet::getByClient($playerDetails->id, $currency, $bonus);
                $balanceBonus = $this->processBonus(
                    TransactionTypes::$credit,
                    $playerDetails,
                    $transactionAmount,
                    $walletDetail
                );
            }
        }

        $walletHandlingResult = $this->handleEmptyTransactionObject($request, $transactionResult);

        if ($walletHandlingResult instanceof Response) {
            return $walletHandlingResult;
        }

        $transaction                        = $transactionResult->data;
        $additionalData                     = $transaction?->transaction->data;
        $additionalData->wallet_transaction = $transaction?->transaction->id;
        $balance                            = $transaction?->wallet?->balance ?? 0;
        $additionalData->balance            = $balance;
        $additionalData                     = get_object_vars((object)$additionalData);

        return (object)[
            'additionalData'    => $additionalData,
            'agentBalanceFinal' => $walletDetail->data->wallet->balance,
            'balance'           => $balance,
            'balanceBonus'      => $balanceBonus ?? 0,
            'ownerBalance'      => $ownerAgent->balance - $transactionAmount,
            'status'            => $transactionResult->status,
        ];
    }

    /**
     * Process a debit transaction.
     *
     * @param TransactionRequest $request The request object.
     * @param object $playerDetails The player details object.
     * @param object $walletDetail The wallet detail object.
     *
     * @return mixed An object containing additional data, owner balance, and transaction information
     *               or a Response object in case of an error.
     */
    public function processDebitTransaction(
        TransactionRequest $request,
        object $playerDetails,
        object $walletDetail
    )
    : mixed {
        $currency          = session('currency');
        $transactionAmount = $request->get('amount');
        $ownerAgent        = $this->agentsRepo->findByUserIdAndCurrency($request->user()->id, $currency);

        $transactionResult = Wallet::debitManualTransactions(
            $transactionAmount,
            Providers::$agents_users,
            $this->generateAdditionalTransactionData($ownerAgent, $playerDetails),
            $request->get('wallet'),
        );

        if ($walletDetail && isset($walletDetail->data->bonus)) {
            $balanceBonus = $this->processBonus(
                TransactionTypes::$debit,
                $playerDetails,
                $transactionAmount,
                $walletDetail
            );
        }
        $walletHandlingResult = $this->handleEmptyTransactionObject($request, $transactionResult);

        if ($walletHandlingResult instanceof Response) {
            return $walletHandlingResult;
        }

        $transaction                        = $transactionResult->data;
        $additionalData                     = $transaction?->transaction->data;
        $additionalData->wallet_transaction = $transaction->transaction->id;
        $balance                            = $transaction?->wallet?->balance ?? 0;
        $additionalData->balance            = $balance;
        $additionalData                     = get_object_vars((object)$additionalData);

        return (object)[
            'additionalData'    => $additionalData,
            'agentBalanceFinal' => $walletDetail->data->wallet->balance,
            'balance'           => $balance,
            'balanceBonus'      => $balanceBonus ?? 0,
            'ownerBalance'      => $ownerAgent->balance + $transactionAmount,
            'status'            => $transactionResult->status,
        ];
    }

}

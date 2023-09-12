<?php

namespace App\Agents\Services;

use App\Agents\Enums\AgentType;
use App\Agents\Enums\UserType;
use App\Agents\Repositories\AgentCurrenciesRepo;
use App\Agents\Repositories\AgentsRepo;
use App\Agents\Services\App\Models\User;
use App\Core\Repositories\TransactionsRepo;
use App\Http\Requests\TransactionRequest;
use App\Users\Enums\ActionUser;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Configurations\Enums\Codes;
use Dotworkers\Configurations\Enums\Providers;
use Dotworkers\Configurations\Enums\Status;
use Dotworkers\Configurations\Enums\TransactionStatus;
use Dotworkers\Configurations\Enums\TransactionTypes;
use Dotworkers\Configurations\Utils;
use Dotworkers\Wallet\Wallet;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

/**
 *
 */
class TransactionService
{
    /**
     * Constructor for the TransactionManager class.
     *
     * @param AgentsRepo $agentsRepo The AgentsRepo instance.
     * @param TransactionsRepo $transactionsRepo The TransactionsRepo instance.
     * @param AgentCurrenciesRepo $agentCurrenciesRepo The AgentCurrenciesRepo instance.
     */
    public function __construct(
        private AgentsRepo       $agentsRepo,
        private TransactionsRepo $transactionsRepo,
        private AgentCurrenciesRepo $agentCurrenciesRepo
    )
    {
    }

    /**
     * Generate additional transaction data.
     *
     * @param object $ownerAgent The owner agent object.
     * @param object $playerDetails The player details object.
     *
     * @return array Additional transaction data.
     */
    public function generateAdditionalTransactionData(object $ownerAgent, object $playerDetails): array
    {
        return [
            'provider_transaction' => Str::uuid()->toString(),
            'from' => $ownerAgent->username,
            'to' => $playerDetails->username
        ];
    }

    /**
     * Generate an error response.
     *
     * @param string $title The error title.
     * @param string $message The error message.
     *
     * @return Response The error response.
     */
    public function generateErrorResponse(string $title, string $message): Response
    {
        return Utils::errorResponse(Codes::$forbidden, [
            'title' => $title,
            'message' => $message,
            'close' => _i('Close')
        ]);
    }

    /**
     * Handle an empty transaction object.
     *
     * @param TransactionRequest $request The request object containing transaction details.
     * @param string $currency The currency ISO code.
     * @param mixed $transaction The transaction object.
     *
     * @return bool|Response False if the transaction is not empty, otherwise a response indicating an error.
     */
    public function handleEmptyTransactionObject(TransactionRequest $request, string $currency, mixed $transaction): bool|Response
    {
        if (empty($transaction) || empty($transaction->data)) {
            Log::error('error data, wallet getByClient', [
                'currency' => $currency,
                'request' => $request->all(),
                'userAuthId' => $request->user()->id,
                'transaction' => $transaction,
            ]);

            return $this->generateErrorResponse(
                _i('An error occurred'),
                _i('please contact support')
            );
        }

        return false;
    }

    /**
     * Check if the game amount is greater than the wallet balance.
     *
     * @param TransactionRequest $request The request object containing transaction details.
     * @param object $walletDetail The wallet detail object.
     *
     * @return bool|Response False if the game amount is not greater than the balance, otherwise a response indicating an error.
     */
    public function isGameAmountGreaterThanBalance(TransactionRequest $request, object $walletDetail): bool|Response
    {
        if ($request->get('amount') > $walletDetail->data->wallet->balance) {
            return $this->generateErrorResponse(
                _i('Insufficient balance'),
                _i("The user's balance is insufficient to perform the transaction")
            );
        }

        return false;
    }

    /**
     * Check if there is insufficient balance for a transaction.
     *
     * @param string $transactionType The transaction type.
     * @param string $transactionAmount The transaction amount.
     * @param object $ownerAgent The owner agent object.
     *
     * @return Response|null A response indicating an error if there is insufficient balance, otherwise null.
     */
    public function isInsufficientBalance(string $transactionType, string $transactionAmount, object $ownerAgent): ?Response
    {
        $isCreditTransaction = $transactionType == TransactionTypes::$credit;
        $isWolfAgent = $ownerAgent->username == AgentType::WOLF;

        if ($isCreditTransaction && $transactionAmount > $ownerAgent->balance && !$isWolfAgent) {
            return $this->generateErrorResponse(
                _i('Insufficient balance'),
                _i("The agents's operational balance is insufficient to perform the transaction")
            );
        }

        return null;
    }

    /**
     * Check if a user is blocked.
     *
     * @param object $user The user object.
     *
     * @return bool|Response False if the user is not blocked, otherwise a response indicating a block.
     */
    public function isUserBlocked(object $user): bool|Response
    {
        if ($user->action == ActionUser::$locked_higher) {
            return $this->generateErrorResponse(
                _i('Blocked by a superior!'),
                _i('Contact your superior...'),
            );
        }

        return false;
    }

    /**
     * Manage agent user balance transaction.
     *
     * This method processes and manages the balance of an agent user based on a transaction request.
     *
     * @param TransactionRequest $request The transaction request object.
     * @param string $userToAddBalance The user for whom the balance is managed.
     * @param string $currency The currency of the transaction.
     *
     * @return mixed An object containing the result of the transaction with the following properties:
     *   - balance: The final balance of the agent user after the transaction.
     *   - status: The status of the transaction (e.g., Status::$ok or Status::$failed).
     *
     */
    public function manageAgentUser(TransactionRequest $request, string $userToAddBalance, string $currency): mixed
    {
        $agentDetails = $this->agentsRepo->findByUserIdAndCurrency($userToAddBalance, $currency);
        $userIsBlocked = $this->isUserBlocked($agentDetails);

        if ($userIsBlocked instanceof Response) {
            return $userIsBlocked;
        }

        $transactionType = $request->get('transaction_type');
        $transactionAmount = $request->get('amount');
        $ownerAgent = $this->agentsRepo->findByUserIdAndCurrency($userToAddBalance, $currency);

        $agentBalance = round($agentDetails->balance, 2);

        if ($transactionType == TransactionTypes::$credit) {
            $transactionResult = $this->processCreditTransactionForAgent($agentDetails, $agentBalance, $transactionAmount, $currency, $ownerAgent);

            return $this->processAndStoreTransaction($request, $userToAddBalance, $currency, $transactionType, $transactionResult);
        }

        if ($transactionAmount > $agentBalance) {
            return (object)['balance' => $agentBalance, 'status' => Status::$failed,];
        }

        $transactionResult = $this->processDebitTransactionForAgent($agentDetails, $agentBalance, $transactionAmount, $currency, $ownerAgent);
        return $this->processAndStoreTransaction($request, $userToAddBalance, $currency, $transactionType, $transactionResult);
    }

    /**
     * Manage credit and debit transactions based on the provided request.
     *
     * This method orchestrates the processing of credit and debit transactions
     * based on the information provided in the TransactionRequest object.
     *
     * @param TransactionRequest $request The request object containing transaction details.
     *
     * @return mixed The response object indicating the result of the transaction.
     *                  It can be a success response or an error response.
     */
    public function manageCreditDebitTransactions(TransactionRequest $request): mixed
    {
        $userAuthId = $request->user()->id;
        $userToAddBalance = $request->get('user');

        if ($userAuthId == $userToAddBalance) {
            return $this->generateErrorResponse(
                _i('Error'),
                _i('You cannot make transactions to yourself')
            );
        }

        $transactionType = $request->get('transaction_type');
        $transactionAmount = $request->get('amount');
        $currency = session('currency');
        $ownerAgent = $this->agentsRepo->findByUserIdAndCurrency($userAuthId, $currency);

        if ($isBalanceInsufficient = $this->isInsufficientBalance($transactionType, $transactionAmount, $ownerAgent)) {
            return $isBalanceInsufficient;
        }
        $userType = $request->get('type');

        $userManagementResult = ($userType == UserType::USER_TYPE_PLAYER)
            ? $this->managePlayerUser($request, $userToAddBalance, $currency)
            : $this->manageAgentUser($request, $userToAddBalance, $currency);

        if ($userManagementResult instanceof Response) {
            return $userManagementResult;
        }

        if ($userManagementResult?->status != Status::$ok) {
            return $this->generateErrorResponse(
                _i('Insufficient balance'),
                _i("The user's balance is insufficient to perform the transaction")
            );
        }

        if ($ownerAgent->username != AgentType::WOLF) {
            $this->agentCurrenciesRepo->store(
                ['agent_id' => $ownerAgent->agent, 'currency_iso' => $currency],
                ['balance' => $userManagementResult->ownerBalance]
            );
        }

        $ownerAgent = $this->agentsRepo->findByUserIdAndCurrency($userAuthId, $currency);

        return $this->processTransactionAndGenerateResponse(
            $userType,
            $ownerAgent,
            $userManagementResult,
            $userAuthId,
            $transactionAmount,
            $currency,
            $transactionType
        );
    }

    /**
     * Process and store a transaction, and generate a response.
     *
     * This method processes a transaction based on the provided details, stores it,
     * and generates a response object with transaction information and a ticket link.
     *
     * @param TransactionRequest $request The request object containing transaction details.
     * @param int|string $userToAddBalance The user identifier for whom the transaction is being processed.
     * @param string $currency The currency ISO code.
     * @param string $transactionType The transaction type identifier.
     * @param mixed $transactionResult The result of the transaction processing.
     *
     * @return mixed An object containing transaction and ticket information or a Response object in case of an error.
     */
    public function processAndStoreTransaction(
        TransactionRequest $request,
        int|string         $userToAddBalance,
        string             $currency,
        string             $transactionType,
        mixed              $transactionResult): mixed
    {
        $transactionData = [
            'user_id' => $userToAddBalance,
            'amount' => $request->get('amount'),
            'currency_iso' => $currency,
            'transaction_type_id' => $transactionType,
            'transaction_status_id' => TransactionStatus::$approved,
            'provider_id' => Providers::$agents_users,
            'data' => $transactionResult->additionalData ?? [],
            'whitelabel_id' => Configurations::getWhitelabel()
        ];

        $ticket = $this->transactionsRepo->store($transactionData, TransactionStatus::$approved, []);
        $response = $this->handleEmptyTransactionObject($request, $currency, $ticket);

        if ($response instanceof Response) {
            return $response;
        }

        $button = sprintf(
            '<a class="btn u-btn-3d u-btn-blue btn-block" id="ticket" href="%s" target="_blank">%s</a>',
            route('agents.ticket', [$ticket->id]),
            _i('Print ticket')
        );

        return (object)[
            'agentBalanceFinal' => $transactionResult->agentBalanceFinal ?? 0,
            'ownerBalance' => $transactionResult->ownerBalance ?? 0,
            'transactionIdCreated' => $ticket->id,
            'balance' => $transactionResult->transaction?->data?->wallet?->balance ?? 0,
            'status' => $transactionResult->transaction?->status ?? $transactionResult->status,
            'button' => $button,
            'additionalData' => $transactionResult->additionalData,
        ];
    }

    /**
     * Manage player user transactions.
     *
     * This method orchestrates the processing of transactions for a player user based on
     * the provided TransactionRequest and the user's balance.
     *
     * @param TransactionRequest $request The request object containing transaction details.
     * @param string $userToAddBalance The user identifier for whom the transaction is being processed.
     *
     * @return mixed The response object indicating the result of the transaction.
     *                  It can be a success response or an error response.
     */
    public function managePlayerUser(TransactionRequest $request, string $userToAddBalance, string $currency): mixed
    {
        $playerDetails = $this->agentsRepo->findUser($userToAddBalance);
        $userIsBlocked = $this->isUserBlocked($playerDetails);

        if ($userIsBlocked instanceof Response) {
            return $userIsBlocked;
        }

        $walletDetail = Wallet::getByClient($playerDetails->id, $currency);
        $walletHandlingResult = $this->handleEmptyTransactionObject($request, $currency, $walletDetail);

        if ($walletHandlingResult instanceof Response) {
            return $walletHandlingResult;
        }

        $transactionType = $request->get('transaction_type');

        if ($transactionType == TransactionTypes::$credit) {
            $transactionResult = $this->processCreditTransactionForPlayerUser($request, $playerDetails, $walletDetail);

            return $this->processAndStoreTransaction($request, $userToAddBalance, $currency, $transactionType, $transactionResult);
        }

        $isAmountGreaterThanBalance = $this->isGameAmountGreaterThanBalance($request, $walletDetail);

        if ($isAmountGreaterThanBalance instanceof Response) {
            return $isAmountGreaterThanBalance;
        }

        $transactionResult = $this->processDebitTransactionForPlayerUser($request, $playerDetails, $walletDetail);

        return $this->processAndStoreTransaction($request, $userToAddBalance, $currency, $transactionType, $transactionResult);
    }

    /**
     * Process a credit transaction for an agent.
     *
     * This method handles a credit transaction for a given agent and updates their balance.
     *
     * @param object $agentDetails An object containing agent details.
     * @param float $agentBalance The current balance of the agent.
     * @param float $transactionAmount The amount of the credit transaction.
     * @param string $currency The currency of the transaction.
     * @param object $ownerAgent An object representing the owner agent.
     *
     * @return object An object containing the result of the transaction with the following properties:
     *   - additionalData: An array of additional transaction data.
     *   - agentBalanceFinal: The final balance of the agent after the transaction.
     *   - ownerBalance: The balance of the owner agent after deducting the transaction amount.
     *   - status: The status of the transaction (e.g., Status::$ok).
     */
    public function processCreditTransactionForAgent(object $agentDetails, float $agentBalance, float $transactionAmount, string $currency, object $ownerAgent): object
    {
        $balance = $agentBalance + $transactionAmount;
        if ($agentDetails->username != AgentType::WOLF) {
            $this->agentCurrenciesRepo->store(
                ['agent_id' => $agentDetails->agent, 'currency_iso' => $currency],
                ['balance' => $balance]
            );
        }

        $additionalData = Arr::collapse([
            $this->generateAdditionalTransactionData($ownerAgent, $agentDetails),
            ['balance' => $balance]
        ]);

        return (object)[
            'additionalData' => $additionalData,
            'agentBalanceFinal' => $agentDetails->balance + $transactionAmount,
            'ownerBalance' => $ownerAgent->balance - $transactionAmount,
            'status' => Status::$ok,
        ];
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
    public function processCreditTransactionForPlayerUser(TransactionRequest $request, object $playerDetails, object $walletDetail): mixed
    {
        $currency = session('currency');
        $transactionAmount = $request->get('amount');
        $ownerAgent = $this->agentsRepo->findByUserIdAndCurrency($request->user()->id, $currency);

        $additionalData = $this->generateAdditionalTransactionData($ownerAgent, $playerDetails);

        $transaction = Wallet::creditManualTransactions(
            $transactionAmount,
            Providers::$agents_users,
            $this->generateAdditionalTransactionData($ownerAgent, $playerDetails),
            $request->get('wallet'),
        );

        $walletHandlingResult = $this->handleEmptyTransactionObject($request, $currency, $transaction);

        if ($walletHandlingResult instanceof Response) {
            return $walletHandlingResult;
        }

        return (object)[
            'additionalData' => $additionalData,
            'agentBalanceFinal' => $walletDetail->data->wallet->balance,
            'ownerBalance' => $ownerAgent->balance - $transactionAmount,
            'transaction' => $transaction,
        ];
    }

    /**
     * Process a debit transaction for an agent.
     *
     * This method handles a debit transaction for a given agent and updates their balance.
     *
     * @param object $agentDetails An object containing agent details.
     * @param float $agentBalance The current balance of the agent.
     * @param float $transactionAmount The amount of the debit transaction.
     * @param string $currency The currency of the transaction.
     * @param object $ownerAgent An object representing the owner agent.
     *
     * @return object An object containing the result of the transaction with the following properties:
     *   - additionalData: An array of additional transaction data.
     *   - agentBalanceFinal: The final balance of the agent after the transaction.
     *   - ownerBalance: The balance of the owner agent after adding the transaction amount.
     *   - status: The status of the transaction (e.g., Status::$ok).
     */
    public function processDebitTransactionForAgent(object $agentDetails, float $agentBalance, float $transactionAmount, string $currency, object $ownerAgent): object
    {
        $balance = $agentBalance - $transactionAmount;

        if ($agentDetails->username != AgentType::WOLF) {
            $this->agentCurrenciesRepo->store(
                ['agent_id' => $agentDetails->agent, 'currency_iso' => $currency],
                ['balance' => $balance]
            );
        }

        $additionalData = Arr::collapse([
            $this->generateAdditionalTransactionData($ownerAgent, $agentDetails),
            ['balance' => $balance]
        ]);

        return (object)[
            'additionalData' => $additionalData,
            'agentBalanceFinal' => $agentDetails->balance,
            'ownerBalance' => $ownerAgent->balance + $transactionAmount,
            'status' => Status::$ok,
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
    public function processDebitTransactionForPlayerUser(TransactionRequest $request, object $playerDetails,  object $walletDetail): mixed
    {
        $currency = session('currency');
        $transactionAmount = $request->get('amount');
        $ownerAgent = $this->agentsRepo->findByUserIdAndCurrency($request->user()->id, $currency);

        $additionalData = $this->generateAdditionalTransactionData($ownerAgent, $playerDetails);

        $transaction = Wallet::debitManualTransactions(
            $transactionAmount,
            Providers::$agents_users,
            $this->generateAdditionalTransactionData($ownerAgent, $playerDetails),
            $request->get('wallet')
        );

        $walletHandlingResult = $this->handleEmptyTransactionObject($request, $currency, $transaction);

        if ($walletHandlingResult instanceof Response) {
            return $walletHandlingResult;
        }

        return (object)[
            'additionalData' => $additionalData,
            'agentBalanceFinal' => $walletDetail->data->wallet->balance,
            'ownerBalance' => $ownerAgent->balance + $transactionAmount,
            'transaction' => $transaction,
        ];
    }

    /**
     * Process a transaction and generate a response.
     *
     * @param string $userType The user type.
     * @param object $ownerAgent The owner agent object.
     * @param object $userManagementResult The user management result object.
     * @param int $userAuthId The user authentication ID.
     * @param float $transactionAmount The transaction amount.
     * @param string $currency The currency ISO code.
     * @param string $transactionType The transaction type identifier.
     *
     * @return Response The generated response.
     */
    public function processTransactionAndGenerateResponse(
        string $userType,
        object $ownerAgent,
        object $userManagementResult,
        int    $userAuthId,
        float  $transactionAmount,
        string $currency,
        string $transactionType
    ): Response
    {
        $balance = ($userType == UserType::USER_TYPE_PLAYER || $ownerAgent->username != AgentType::WOLF)
            ? $userManagementResult->ownerBalance
            : 0;

        $secondBalance = $transactionType == TransactionTypes::$credit
            ? round($userManagementResult->agentBalanceFinal, 2)
            : round($userManagementResult->agentBalanceFinal, 2) - $transactionAmount;

        $additionalData = Arr::collapse([$userManagementResult->additionalData, [
            'balance' => $balance,
            'transaction_id' => $userManagementResult->transactionIdCreated,
            'second_balance' => $secondBalance,
        ]]);

        $transactionData = [
            'user_id' => $userAuthId,
            'amount' => $transactionAmount,
            'currency_iso' => $currency,
            'transaction_type_id' => $transactionType == TransactionTypes::$credit ? TransactionTypes::$debit : TransactionTypes::$credit,
            'transaction_status_id' => TransactionStatus::$approved,
            'provider_id' => Providers::$agents,
            'data' => $additionalData,
            'whitelabel_id' => Configurations::getWhitelabel()
        ];

        $transactionFinal = $this->transactionsRepo->store($transactionData, TransactionStatus::$approved, []);
        if (empty($transactionFinal)) {
            return $this->generateErrorResponse(
                _i('An error occurred'),
                _i('please contact support"')
            );
        }

        $ownerBalanceFinal = $ownerAgent->balance;

        $transactionUpdate = $this->transactionsRepo->updateData(
            $userManagementResult->transactionIdCreated,
            $transactionFinal->id,
            $transactionType == TransactionTypes::$credit
                ? round($ownerBalanceFinal, 2) - $transactionAmount
                : round($ownerBalanceFinal, 2) + $transactionAmount
        );

        if (empty($transactionUpdate)) {
            return $this->generateErrorResponse(
                _i('An error occurred'),
                _i('please contact support"')
            );
        }

        return Utils::successResponse([
            'title' => _i('Transaction performed'),
            'message' => _i('The transaction was successfully made to the user'),
            'close' => _i('Close'),
            'balance' => number_format($balance, 2),
            'button' => $userManagementResult->button,
        ]);
    }
}

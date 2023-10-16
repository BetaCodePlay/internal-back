<?php

namespace App\Agents\Services;

use App\Agents\Enums\AgentType;
use App\Agents\Enums\UserType;

use App\Agents\Repositories\AgentCurrenciesRepo;
use App\Agents\Repositories\AgentsRepo;
use App\Core\Repositories\TransactionsRepo;
use App\Core\Services\BaseService;
use App\Http\Requests\TransactionRequest;
use App\Users\Enums\ActionUser;
use Dotworkers\Bonus\Bonus;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Configurations\Enums\Codes;
use Dotworkers\Configurations\Enums\Providers;
use Dotworkers\Configurations\Enums\Status;
use Dotworkers\Configurations\Enums\TransactionStatus;
use Dotworkers\Configurations\Enums\TransactionTypes;
use Dotworkers\Configurations\Utils;
use Dotworkers\Wallet\Wallet;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;


/**
 *
 */
class TransactionService extends BaseService
{
    /**
     * Create a new instance of the TransactionService class.
     *
     * This constructor initializes an instance of the TransactionService class with the
     * provided dependencies for various repositories. These repositories are used
     * for performing operations within the class.
     *
     * @param AgentCurrenciesRepo $agentCurrenciesRepo The repository for agent currencies.
     * @param AgentsRepo $agentsRepo The repository for agents.
     * @param TransactionsRepo $transactionsRepo The repository for transactions.
     */
    public function __construct(
        private AgentCurrenciesRepo $agentCurrenciesRepo,
        private AgentsRepo $agentsRepo,
        private TransactionsRepo $transactionsRepo
    ) {
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
            'from'                 => $ownerAgent->username,
            'to'                   => $playerDetails->username,
        ];
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
                _i("The user's balance is insufficient to perform the transaction"),
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
    public function isInsufficientBalance(
        string $transactionType,
        string $transactionAmount,
        object $ownerAgent
    ): ?Response {
        $isCreditTransaction = $transactionType == TransactionTypes::$credit;
        $isWolfAgent = $ownerAgent?->username == AgentType::WOLF;

        if ($isCreditTransaction && $transactionAmount > $ownerAgent?->balance && !$isWolfAgent) {
            return $this->generateErrorResponse(
                _i('Insufficient balance'),
                _i("The agents's operational balance is insufficient to perform the transaction"),
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
     * @return mixed An object containing the result of the transaction with the following properties:
     *   - balance: The final balance of the agent user after the transaction.
     *   - status: The status of the transaction (e.g., Status::$ok or Status::$failed).
     */
    public function manageAgentUser(TransactionRequest $request): mixed
    {
        $userToAddBalance = $request->get('user');
        $currency = session('currency');
        $agentDetails = $this->agentsRepo->findByUserIdAndCurrency($userToAddBalance, $currency);
        $userIsBlocked = $this->isUserBlocked($agentDetails);

        if ($userIsBlocked instanceof Response) {
            return $userIsBlocked;
        }

        $transactionType = $request->get('transaction_type');
        $transactionAmount = $request->get('amount');
        $userAuthId = $request->user()->id;

        $ownerAgent = $this->agentsRepo->findByUserIdAndCurrency($userAuthId, $currency);
        $agentBalance = round($agentDetails->balance, 2);

        if ($transactionType == TransactionTypes::$credit) {
            $creditTransactionInfoForAgent = $this->processCreditTransactionForAgent(
                $request,
                $agentDetails,
                $agentBalance,
                $ownerAgent,
            );
            return $this->processAndStoreTransaction($request, $creditTransactionInfoForAgent, Providers::$agents);
        }

        if ($transactionAmount > $agentBalance) {
            return (object)[
                'balance' => $agentBalance,
                'status'  => Status::$failed,
            ];
        }

        $debitTransactionInfoForAgent = $this->processDebitTransactionForAgent(
            $request,
            $agentDetails,
            $agentBalance,
            $ownerAgent,
        );

        return $this->processAndStoreTransaction($request, $debitTransactionInfoForAgent, Providers::$agents);
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
                _i('You cannot make transactions to yourself'),
            );
        }

        $transactionType = $request->get('transaction_type');
        $transactionAmount = $request->get('amount');
        $currency = session('currency');
        $ownerAgent = $this->agentsRepo->findByUserIdAndCurrency($userAuthId, $currency);

        if ($isBalanceInsufficient = $this->isInsufficientBalance($transactionType, $transactionAmount, $ownerAgent)) {
            return $isBalanceInsufficient;
        }

        $userManagementResult = ($request->get('type') == UserType::USER_TYPE_PLAYER)
            ? $this->managePlayerUser($request)
            : $this->manageAgentUser($request);

        if ($userManagementResult instanceof Response) {
            return $userManagementResult;
        }

        if ($userManagementResult?->status != Status::$ok) {
            return $this->generateErrorResponse(
                _i('Insufficient balance'),
                _i("The user's balance is insufficient to perform the transaction"),
            );
        }

        if ($ownerAgent->username != AgentType::WOLF) {
            $this->agentCurrenciesRepo->store(
                [
                    'agent_id'     => $ownerAgent->agent,
                    'currency_iso' => $currency,
                ],
                ['balance' => $userManagementResult->ownerBalance],
            );
        }

        return $this->processTransactionAndGenerateResponse($request, $ownerAgent, $userManagementResult);
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
    public function managePlayerUser(TransactionRequest $request): mixed
    {
        $userToAddBalance = $request->get('user');
        $playerDetails = $this->agentsRepo->findUser($userToAddBalance);
        $userIsBlocked = $this->isUserBlocked($playerDetails);

        if ($userIsBlocked instanceof Response) {
            return $userIsBlocked;
        }

        $currency = session('currency');
        $bonus = Configurations::getBonus(Configurations::getWhitelabel());
        $walletDetail = Wallet::getByClient($playerDetails->id, $currency, $bonus);
        \Log::debug(['$walletDetail' => $walletDetail]);
        $walletHandlingResult = $this->handleEmptyTransactionObject($request, $walletDetail, true);

        if ($walletHandlingResult instanceof Response) {
            return $walletHandlingResult;
        }

        if ($request->get('transaction_type') == TransactionTypes::$credit) {
            $creditTransactionResult = $this->processCreditTransactionForPlayerUser(
                $request,
                $playerDetails,
                $walletDetail,
            );

            return $this->processAndStoreTransaction($request, $creditTransactionResult, Providers::$agents_users);
        }

        $isAmountGreaterThanBalance = $this->isGameAmountGreaterThanBalance($request, $walletDetail);

        if ($isAmountGreaterThanBalance instanceof Response) {
            return $isAmountGreaterThanBalance;
        }

        $debitTransactionResult = $this->processDebitTransactionForPlayerUser($request, $playerDetails, $walletDetail);

        return $this->processAndStoreTransaction($request, $debitTransactionResult, Providers::$agents_users);
    }

    /**
     * Process and store a transaction, and generate a response.
     *
     * This method processes a transaction based on the provided details, stores it,
     * and generates a response object with transaction information and a ticket link.
     *
     * @param TransactionRequest $request The request object containing transaction details.
     * @param mixed $transactionResult The result of the transaction processing.
     * @param int $providerId The provider ID.
     *
     * @return mixed An object containing transaction and ticket information or a Response object in case of an error.
     */
    public function processAndStoreTransaction(
        TransactionRequest $request,
        mixed $transactionResult,
        int $providerId
    ): mixed {
        $transactionData = [
            'user_id'               => $request->get('user'),
            'amount'                => $request->get('amount'),
            'currency_iso'          => session('currency'),
            'transaction_type_id'   => $request->get('transaction_type'),
            'transaction_status_id' => TransactionStatus::$approved,
            'provider_id'           => $providerId,
            'data'                  => $transactionResult->additionalData,
            'whitelabel_id'         => Configurations::getWhitelabel(),
        ];

        $ticket = $this->transactionsRepo->store($transactionData, TransactionStatus::$approved, []);
        $response = $this->handleEmptyTransactionObject($request, $ticket);

        if ($response instanceof Response) {
            return $response;
        }

        $ticketId = $ticket->id;

        $buttonHTML = view('back.partials.ticket_print_button', [
            'ticketRoute'     => route('agents.ticket', [$ticketId]),
            'printTicketText' => __('Print ticket'),
        ])->render();
        // \Log::debug(['balanceBonus processAndStoreTransaction' => $transactionResult->balanceBonus]);
        return (object)[
            'additionalData'       => $transactionResult->additionalData,
            'agentBalanceFinal'    => $transactionResult->agentBalanceFinal ?? 0,
            'balance'              => $transactionResult->balance,
            'balanceBonus'         => $transactionResult?->balanceBonus ?? 0,
            'button'               => $buttonHTML,
            'ownerBalance'         => $transactionResult->ownerBalance ?? 0,
            'status'               => $transactionResult->status,
            'transactionIdCreated' => $ticketId,
        ];
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
    public function processBonusForPlayer(
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
            \Log::debug(['BalanceBonusUpdate' => $updateBalanceBonus]);
            return $updateBalanceBonus->data->bonus[0]->balance;
        }
    }


    /**
     * Process a credit transaction for an agent.
     *
     * This method handles a credit transaction for a given agent and updates their balance.
     *
     * @param TransactionRequest $request
     * @param object $agentDetails An object containing agent details.
     * @param float $agentBalance The current balance of the agent.
     * @param object $ownerAgent An object representing the owner agent.
     *
     * @return object An object containing the result of the transaction with the following properties:
     *   - additionalData: An array of additional transaction data.
     *   - agentBalanceFinal: The final balance of the agent after the transaction.
     *   - ownerBalance: The balance of the owner agent after deducting the transaction amount.
     *   - status: The status of the transaction (e.g., Status::$ok).
     */
    public function processCreditTransactionForAgent(
        TransactionRequest $request,
        object $agentDetails,
        float $agentBalance,
        object $ownerAgent
    ): object {
        $transactionAmount = $request->get('amount');
        $balance = $agentBalance + $transactionAmount;
        if ($agentDetails->username != AgentType::WOLF) {
            $this->agentCurrenciesRepo->store(
                [
                    'agent_id'     => $agentDetails->agent,
                    'currency_iso' => session('currency'),
                ],
                ['balance' => $balance],
            );
        }

        $additionalData = Arr::collapse([
            $this->generateAdditionalTransactionData($ownerAgent, $agentDetails),
            ['balance' => $balance],
        ]);

        return (object)[
            'additionalData'    => $additionalData,
            'agentBalanceFinal' => $agentDetails->balance + $transactionAmount,
            'balance'           => $balance,
            'ownerBalance'      => $ownerAgent->balance - $transactionAmount,
            'status'            => Status::$ok,
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
    public function processCreditTransactionForPlayerUser(
        TransactionRequest $request,
        object $playerDetails,
        object $walletDetail
    ): mixed {
        $currency = session('currency');
        $whitelabel = Configurations::getWhitelabel();
        $bonus = Configurations::getBonus($whitelabel);
        $transactionAmount = $request->get('amount');
        $userAuthId = $request->user()->id;
        $ownerAgent = $this->agentsRepo->findByUserIdAndCurrency($userAuthId, $currency);

        $transactionResult = Wallet::creditManualTransactions(
            $transactionAmount,
            Providers::$agents_users,
            $this->generateAdditionalTransactionData($ownerAgent, $playerDetails),
            $request->get('wallet'),
        );

        if($walletDetail?->data?->bonus) {
            $balanceBonus = $this->processBonusForPlayer(TransactionTypes::$credit, $playerDetails, $transactionAmount, $walletDetail);
        } else {
            $walletBonus = Wallet::store($playerDetails->id, $playerDetails->username, $playerDetails->uuid, $currency, $whitelabel, session('wallet_access_token'), $bonus, null, null);
            if($walletBonus->code == Codes::$ok) {
                $walletDetail = Wallet::getByClient($playerDetails->id, $currency, $bonus);
                $balanceBonus = $this->processBonusForPlayer(TransactionTypes::$credit, $playerDetails, $transactionAmount, $walletDetail);
            }

        }

        $walletHandlingResult = $this->handleEmptyTransactionObject($request, $transactionResult);

        if ($walletHandlingResult instanceof Response) {
            return $walletHandlingResult;
        }

        $transaction = $transactionResult->data;
        $additionalData = $transaction?->transaction->data;
        $additionalData->wallet_transaction = $transaction?->transaction->id;
        $additionalData = get_object_vars((object)$additionalData);

        return (object)[
            'additionalData'    => $additionalData,
            'agentBalanceFinal' => $walletDetail->data->wallet->balance,
            'balance'           => $transaction?->wallet?->balance ?? 0,
            'balanceBonus'      => $balanceBonus ?? 0,
            'ownerBalance'      => $ownerAgent->balance - $transactionAmount,
            'status'            => $transactionResult->status,
        ];
    }

    /**
     * Process a debit transaction for an agent.
     *
     * This method handles a debit transaction for a given agent and updates their balance.
     *
     * @param TransactionRequest $request
     * @param object $agentDetails An object containing agent details.
     * @param float $agentBalance The current balance of the agent.
     * @param object $ownerAgent An object representing the owner agent.
     *
     * @return object An object containing the result of the transaction with the following properties:
     *   - additionalData: An array of additional transaction data.
     *   - agentBalanceFinal: The final balance of the agent after the transaction.
     *   - ownerBalance: The balance of the owner agent after adding the transaction amount.
     *   - status: The status of the transaction (e.g., Status::$ok).
     */
    public function processDebitTransactionForAgent(
        TransactionRequest $request,
        object $agentDetails,
        float $agentBalance,
        object $ownerAgent
    ): object {
        $transactionAmount = $request->get('amount');
        $balance = $agentBalance - $transactionAmount;

        if ($agentDetails->username != AgentType::WOLF) {
            $this->agentCurrenciesRepo->store(
                [
                    'agent_id'     => $agentDetails->agent,
                    'currency_iso' => session('currency'),
                ],
                ['balance' => $balance],
            );
        }

        $additionalData = Arr::collapse([
            $this->generateAdditionalTransactionData($ownerAgent, $agentDetails),
            ['balance' => $balance],
        ]);

        return (object)[
            'additionalData'    => $additionalData,
            'agentBalanceFinal' => $agentDetails->balance,
            'balance'           => $balance,
            'ownerBalance'      => $ownerAgent->balance + $transactionAmount,
            'status'            => Status::$ok,
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
    public function processDebitTransactionForPlayerUser(
        TransactionRequest $request,
        object $playerDetails,
        object $walletDetail
    ): mixed {
        $currency = session('currency');
        $transactionAmount = $request->get('amount');
        $ownerAgent = $this->agentsRepo->findByUserIdAndCurrency($request->user()->id, $currency);

        $transactionResult = Wallet::debitManualTransactions(
            $transactionAmount,
            Providers::$agents_users,
            $this->generateAdditionalTransactionData($ownerAgent, $playerDetails),
            $request->get('wallet'),
        );
        \Log::debug(['$walletDetail' => $walletDetail]);
        if($walletDetail?->data?->bonus) {
            $balanceBonus = $this->processBonusForPlayer(TransactionTypes::$debit, $playerDetails, $transactionAmount, $walletDetail);
        }
        $walletHandlingResult = $this->handleEmptyTransactionObject($request, $transactionResult);

        if ($walletHandlingResult instanceof Response) {
            return $walletHandlingResult;
        }

        $transaction = $transactionResult->data;
        $additionalData = $transaction?->transaction->data;
        $additionalData->wallet_transaction = $transaction->transaction->id;
        $additionalData = get_object_vars((object)$additionalData);

        return (object)[
            'additionalData'    => $additionalData,
            'agentBalanceFinal' => $walletDetail->data->wallet->balance,
            'balance'           => $transaction?->wallet?->balance ?? 0,
            'balanceBonus'      => $balanceBonus ?? 0,
            'ownerBalance'      => $ownerAgent->balance + $transactionAmount,
            'status'            => $transactionResult->status,
        ];
    }

    /**
     * Process a transaction and generate a response.
     *
     * @param TransactionRequest $request
     * @param object $ownerAgent The owner agent object.
     * @param object $userManagementResult The user management result object.
     * @return Response The generated response.
     */
    public function processTransactionAndGenerateResponse(
        TransactionRequest $request,
        object $ownerAgent,
        object $userManagementResult
    ): Response {
        $userType = $request->get('type');
        $transactionAmount = $request->get('amount');
        $transactionType = $request->get('transaction_type');
        $balance = ($userType == UserType::USER_TYPE_PLAYER || $ownerAgent->username != AgentType::WOLF)
            ? $userManagementResult->ownerBalance
            : 0;

        $agentBalanceFinal = $userManagementResult->agentBalanceFinal;

        $secondBalance = $transactionType == TransactionTypes::$credit
            ? round($agentBalanceFinal, 2)
            : round($agentBalanceFinal, 2) - $transactionAmount;

        $additionalData = Arr::collapse([
            $userManagementResult->additionalData,
            [
                'balance'        => $balance,
                'transaction_id' => $userManagementResult->transactionIdCreated,
                'second_balance' => $secondBalance,
            ],
        ]);

        $transactionData = [
            'user_id'               => $request->user()->id,
            'amount'                => $transactionAmount,
            'currency_iso'          => session('currency'),
            'transaction_type_id'   => $transactionType == TransactionTypes::$credit ? TransactionTypes::$debit : TransactionTypes::$credit,
            'transaction_status_id' => TransactionStatus::$approved,
            'provider_id'           => Providers::$agents,
            'data'                  => $additionalData,
            'whitelabel_id'         => Configurations::getWhitelabel(),
        ];

        $transactionFinal = $this->transactionsRepo->store($transactionData, TransactionStatus::$approved, []);
        if (empty($transactionFinal)) {
            return $this->generateErrorResponse(
                _i('An error occurred'),
                _i('please contact support"'),
            );
        }

        $ownerBalanceFinal = $ownerAgent->balance;

        $transactionUpdate = $this->transactionsRepo->updateData(
            $userManagementResult->transactionIdCreated,
            $transactionFinal->id,
            $transactionType == TransactionTypes::$credit
                ? round($ownerBalanceFinal, 2) - $transactionAmount
                : round($ownerBalanceFinal, 2) + $transactionAmount,
        );

        if (empty($transactionUpdate)) {
            return $this->generateErrorResponse(
                _i('An error occurred'),
                _i('please contact support"'),
            );
        }
        \Log::debug(['processTransactionAndGenerateResponse' => $userManagementResult->balanceBonus]);
        return Utils::successResponse([
            'title'   => _i('Transaction performed'),
            'message' => _i('The transaction was successfully made to the user'),
            'close'   => _i('Close'),
            'balance' => number_format($userManagementResult->balance, 2),
            'balanceBonus' => number_format($userManagementResult->balanceBonus, 2),
            'button'  => $userManagementResult->button,
        ]);
    }
}

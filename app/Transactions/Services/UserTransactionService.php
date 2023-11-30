<?php

namespace App\Transactions\Services;

use App\Agents\Enums\AgentType;
use App\Agents\Enums\UserType;
use App\Agents\Repositories\AgentCurrenciesRepo;
use App\Agents\Repositories\AgentsRepo;
use App\Core\Repositories\TransactionsRepo;
use App\Http\Requests\TransactionRequest;
use Dotworkers\Configurations\Enums\Status;
use Symfony\Component\HttpFoundation\Response;


class UserTransactionService extends BaseTransactionService
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
     */
    public function __construct(
        private AgentCurrenciesRepo $agentCurrenciesRepo,
        private AgentsRepo $agentsRepo,
        private UserAgentTransactionService $agentTransactionService,
        private UserPlayerTransactionService $playerTransactionService
    ) {
        parent::__construct(new TransactionsRepo);
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
    public function manageCreditDebitTransactions(TransactionRequest $request)
    : mixed {
        $userAuthId       = $request->user()->id;
        $userToAddBalance = $request->get('user');

        if ($userAuthId == $userToAddBalance) {
            return $this->generateErrorResponse(
                _i('Error'),
                _i('You cannot make transactions to yourself'),
            );
        }

        $transactionType   = $request->get('transaction_type');
        $transactionAmount = $request->get('amount');
        $currency          = session('currency');
        $ownerAgent        = $this->agentsRepo->findByUserIdAndCurrency($userAuthId, $currency);

        if ($isBalanceInsufficient = $this->isInsufficientBalance($transactionType, $transactionAmount, $ownerAgent)) {
            return $isBalanceInsufficient;
        }

        $userManagementResult = ($request->get('type') == UserType::USER_TYPE_PLAYER)
            ? $this->playerTransactionService->processTransaction($request)
            : $this->agentTransactionService->processTransaction($request);

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
}

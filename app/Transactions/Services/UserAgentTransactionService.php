<?php

namespace App\Transactions\Services;

use App\Agents\Enums\AgentType;
use App\Agents\Repositories\AgentCurrenciesRepo;
use App\Agents\Repositories\AgentsRepo;
use App\Core\Repositories\TransactionsRepo;
use App\Http\Requests\TransactionRequest;
use Dotworkers\Configurations\Enums\Providers;
use Dotworkers\Configurations\Enums\Status;
use Dotworkers\Configurations\Enums\TransactionTypes;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 *
 */
class UserAgentTransactionService extends BaseTransactionService
{

    /**
     * @param AgentCurrenciesRepo|null $agentCurrenciesRepo
     * @param AgentsRepo|null $agentsRepo
     */
    public function __construct(
        private ?AgentCurrenciesRepo $agentCurrenciesRepo,
        private ?AgentsRepo $agentsRepo
    ) {
        parent::__construct(new TransactionsRepo);
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
    public function processTransaction(TransactionRequest $request)
    : mixed {
        $userToAddBalance = $request->get('user');
        $currency         = session('currency');
        $agentDetails     = $this->agentsRepo->findByUserIdAndCurrency($userToAddBalance, $currency);
        Log::info(__METHOD__, ['userToAddBalance' => $userToAddBalance, 'agentDetails' => $agentDetails ]);
        $userIsBlocked    = $this->isUserBlocked($agentDetails);

        if ($userIsBlocked instanceof Response) {
            return $userIsBlocked;
        }

        $transactionType   = $request->get('transaction_type');
        $transactionAmount = $request->get('amount');
        $userAuthId        = $request->user()->id;

        $ownerAgent   = $this->agentsRepo->findByUserIdAndCurrency($userAuthId, $currency);
        $agentBalance = round($agentDetails->balance, 2);

        if ($transactionType == TransactionTypes::$credit) {
            $creditTransactionInfo = $this->processCreditTransaction(
                $request,
                $agentDetails,
                $agentBalance,
                $ownerAgent
            );
            return $this->processAndStoreTransaction($request, $creditTransactionInfo, Providers::$agents);
        }

        if ($transactionAmount > $agentBalance) {
            return (object)[
                'balance' => $agentBalance,
                'status'  => Status::$failed,
            ];
        }

        $debitTransactionInfoForAgent = $this->processDebitTransaction(
            $request,
            $agentDetails,
            $agentBalance,
            $ownerAgent,
        );

        return $this->processAndStoreTransaction($request, $debitTransactionInfoForAgent, Providers::$agents);
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
    public function processCreditTransaction(
        TransactionRequest $request,
        object $agentDetails,
        float $agentBalance,
        object $ownerAgent
    )
    : object {
        $transactionAmount = $request->get('amount');
        $balance           = $agentBalance + $transactionAmount;
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
    public function processDebitTransaction(
        TransactionRequest $request,
        object $agentDetails,
        float $agentBalance,
        object $ownerAgent
    )
    : object {
        $transactionAmount = $request->get('amount');
        $balance           = $agentBalance - $transactionAmount;

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
}

<?php

namespace App\Transactions\Services;

use App\Agents\Enums\AgentType;
use App\Agents\Enums\UserType;
use App\Core\Repositories\TransactionsRepo;
use App\Core\Services\BaseService;
use App\Http\Requests\TransactionRequest;
use App\Users\Enums\ActionUser;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Configurations\Enums\Providers;
use Dotworkers\Configurations\Enums\TransactionStatus;
use Dotworkers\Configurations\Enums\TransactionTypes;
use Dotworkers\Configurations\Utils;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class BaseTransactionService extends BaseService
{
    /**
     * Create a new instance of the TransactionService class.
     *
     * This constructor initializes an instance of the TransactionService class with the
     * provided dependencies for various repositories. These repositories are used
     * for performing operations within the class.
     *
     * @param TransactionsRepo|null $transactionsRepo The repository for transactions.
     */
    protected ?TransactionsRepo $transactionsRepo;

    public function __construct(?TransactionsRepo $transactionsRepo)
    {
        $this->transactionsRepo = $transactionsRepo;
    }

    /**
     * Generate additional transaction data.
     *
     * @param object $ownerAgent The owner agent object.
     * @param object $playerDetails The player details object.
     *
     * @return array Additional transaction data.
     */
    public function generateAdditionalTransactionData(object $ownerAgent, object $playerDetails)
    : array {
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
    public function isGameAmountGreaterThanBalance(TransactionRequest $request, object $walletDetail)
    : bool|Response {
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
    )
    : ?Response {
        $isCreditTransaction = $transactionType == TransactionTypes::$credit;
        $isWolfAgent         = $ownerAgent?->username == AgentType::WOLF;

        if ($isCreditTransaction && $transactionAmount > $ownerAgent?->balance && ! $isWolfAgent) {
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
    public function isUserBlocked(object $user)
    : bool|Response {
        if ($user->action == ActionUser::$locked_higher) {
            return $this->generateErrorResponse(
                _i('Blocked by a superior!'),
                _i('Contact your superior...'),
            );
        }

        return false;
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
    )
    : Response {
        $userType          = $request->get('type');
        $transactionAmount = $request->get('amount');
        $transactionType   = $request->get('transaction_type');
        $balance           = ($userType == UserType::USER_TYPE_PLAYER || $ownerAgent->username != AgentType::WOLF)
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
            'provider_id'           => ($request->get(
                    'type'
                ) == UserType::USER_TYPE_PLAYER) ? Providers::$agents_users : Providers::$agents,
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
        return Utils::successResponse([
            'authUserId'   => $request->user()->id,
            'authBalance'  => number_format($balance, 2),
            'balance'      => number_format($userManagementResult->balance, 2),
            'balanceBonus' => number_format($userManagementResult->balanceBonus, 2),
            'button'       => $userManagementResult->button,
            'close'        => _i('Close'),
            'message'      => _i('The transaction was successfully made to the user'),
            'title'        => _i('Transaction performed'),
        ]);
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
    public function processAndStoreTransaction(TransactionRequest $request, mixed $transactionResult, int $providerId)
    : mixed {
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

        $ticket   = $this->transactionsRepo->store($transactionData, TransactionStatus::$approved, []);
        $response = $this->handleEmptyTransactionObject($request, $ticket);

        if ($response instanceof Response) {
            return $response;
        }

        $ticketId = $ticket->id;

        $buttonHTML = view('back.partials.ticket_print_button', [
            'ticketRoute'     => route('agents.ticket', [$ticketId]),
            'printTicketText' => __('Print ticket'),
        ])->render();
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

}

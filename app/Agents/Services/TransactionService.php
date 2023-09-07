<?php

namespace App\Agents\Services;

use App\Agents\Enums\AgentType;
use App\Agents\Repositories\AgentsRepo;
use App\Users\Enums\ActionUser;
use Dotworkers\Configurations\Enums\Codes;
use Dotworkers\Configurations\Enums\TransactionTypes;
use Dotworkers\Configurations\Utils;
use Symfony\Component\HttpFoundation\Response;

/**
 *
 */
class TransactionService
{
    public function __construct(private AgentsRepo $agentsRepo)
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

    public function processUserTransaction($userToAddBalance)
    {
        $userData = $this->agentsRepo->findUser($userToAddBalance);
        if ($userData->action == ActionUser::$locked_higher) {
            $data = [
                'title' => _i('Blocked by a superior!'),
                'message' => _i('Contact your superior...'),
                'close' => _i('Close')
            ];
            return Utils::errorResponse(Codes::$not_found, $data);
        }
    }

}

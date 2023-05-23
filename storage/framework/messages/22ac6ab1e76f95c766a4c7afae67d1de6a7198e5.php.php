<?php

namespace App\IQSoft\Repositories;

use App\IQSoft\Entities\IQSoftTicket;
use Dotworkers\Configurations\Enums\TransactionTypes;

/**
 * Class IQSoftTicketsRepo
 *
 * This class allows to interact with IQSoftTicket entity
 *
 * @package App\IQSoft\Repositories
 * @author  Eborio Linarez
 */
class IQSoftTicketsRepo
{
    /**
     * Find by provider transaction
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $currency Currency ISO
     * @param int $providerTransaction Provider transaction
     * @return mixed
     */
    public function findByProviderTransaction($whitelabel, $currency, $providerTransaction)
    {
        $ticket = IQSoftTicket::select('iq_soft_tickets.*', 'users.username')
            ->join('users', 'iq_soft_tickets.user_id', '=', 'users.id')
            ->where('provider_transaction', $providerTransaction)
            ->where('iq_soft_tickets.whitelabel_id', $whitelabel)
            ->where('iq_soft_tickets.currency_iso', $currency)
            ->where('iq_soft_tickets.transaction_type_id', TransactionTypes::$debit)
            ->first();
        return $ticket;
    }

    /**
     * Get tickets by user
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $currency Currency ISO
     * @param int $limit Transactions limit
     * @param int $offset Transactions offset
     * @return mixed
     */
    public function getByDates($whitelabel, $currency, $startDate, $endDate)
    {
        $tickets = IQSoftTicket::select('provider_transaction', 'transaction_type_id', 'created_at', 'balance', 'amount', 'status')
            ->where('iq_soft_tickets.whitelabel_id', $whitelabel)
            ->where('iq_soft_tickets.currency_iso', $currency)
            ->where('iq_soft_tickets.transaction_type_id', TransactionTypes::$debit)
            ->whereBetween('iq_soft_tickets.created_at', [$startDate, $endDate])
            ->orderBy('id', 'DESC')
            ->get();
        return $tickets;
    }

    /**
     * Get tickets by user
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $currency Currency ISO
     * @param int $limit Transactions limit
     * @param int $offset Transactions offset
     * @return mixed
     */
    public function getByUserDates($whitelabel, $currency, $startDate, $endDate)
    {
        $tickets = IQSoftTicket::select('iq_soft_tickets.*', 'users.username')
            ->join('users', 'iq_soft_tickets.user_id', '=', 'users.id')
            ->where('iq_soft_tickets.whitelabel_id', $whitelabel)
            ->where('iq_soft_tickets.currency_iso', $currency)
            ->where('transaction_type_id', TransactionTypes::$debit)
            ->whereBetween('iq_soft_tickets.created_at', [$startDate, $endDate])
            ->orderBy('id', 'DESC')
            ->get();
        return $tickets;
    }
}

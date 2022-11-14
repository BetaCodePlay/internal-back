<?php

namespace App\Altenar\Repositories;

use App\Altenar\Entities\AltenarTicket;
use Dotworkers\Configurations\Enums\TransactionTypes;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AltenarTicketsRepo
 *
 * This class allows interacting with AltenarTicket entity
 *
 * @package App\Altenar\Repositories
 * @author  Miguel Sira
 */
class AltenarTicketsRepo
{
    /**
     * Find by provider transaction
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $currency Currency ISO
     * @param int $providerTransaction Provider transaction
     * @return AltenarTicket|Model|object|null
     */
    public function findByProviderTransaction(int $whitelabel, string $currency, int $providerTransaction): ?Object
    {
        return (new AltenarTicket)->select('altenar_tickets.*', 'users.username')
            ->join('users', 'altenar_tickets.user_id', '=', 'users.id')
            ->where('provider_transaction', $providerTransaction)
            ->where('altenar_tickets.whitelabel_id', $whitelabel)
            ->where('altenar_tickets.currency_iso', $currency)
            ->where('altenar_tickets.transaction_type_id', TransactionTypes::$debit)
            ->first();
    }

    /**
     * Find by provider transaction
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $currency Currency ISO
     * @param int $providerTransaction Provider transaction
     * @return AltenarTicket|Model|object|null
     */
    public function findByReference(int $whitelabel, string $currency, int $providerTransaction): ?Object
    {
        return (new AltenarTicket)->select('altenar_tickets.*', 'users.username')
            ->join('users', 'altenar_tickets.user_id', '=', 'users.id')
            ->where('reference', $providerTransaction)
            ->where('altenar_tickets.whitelabel_id', $whitelabel)
            ->where('altenar_tickets.currency_iso', $currency)
            ->where('altenar_tickets.transaction_type_id', TransactionTypes::$debit)
            ->first();
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
        $tickets = AltenarTicket::select('provider_transaction', 'transaction_type_id', 'created_at', 'balance', 'amount', 'status')
            ->where('altenar_tickets.whitelabel_id', $whitelabel)
            ->where('altenar_tickets.currency_iso', $currency)
            ->where('altenar_tickets.transaction_type_id', TransactionTypes::$debit)
            ->whereBetween('altenar_tickets.created_at', [$startDate, $endDate])
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
        $tickets = AltenarTicket::select('altenar_tickets.*', 'users.username')
            ->join('users', 'altenar_tickets.user_id', '=', 'users.id')
            ->where('altenar_tickets.whitelabel_id', $whitelabel)
            ->where('altenar_tickets.currency_iso', $currency)
            ->where('transaction_type_id', TransactionTypes::$debit)
            ->whereBetween('altenar_tickets.created_at', [$startDate, $endDate])
            ->orderBy('id', 'DESC')
            ->get();
        return $tickets;
    }
}

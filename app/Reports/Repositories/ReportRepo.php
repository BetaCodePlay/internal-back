<?php

namespace App\Reports\Repositories;

use Dotworkers\Configurations\Configurations;
use Illuminate\Support\Facades\DB;

class ReportRepo
{
    public function dashboard()
    : array
    {
        $currency     = session('currency');
        $whitelabelId = Configurations::getWhitelabel();

        $transactions = DB::table('transactions')
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->latest('transactions.created_at')
            ->take(10)
            ->select([
                'users.username',
                DB::raw("TO_CHAR(transactions.amount, 'FM999999999.00') as amount"),
                DB::raw("to_char(transactions.created_at, 'DD Mon HH:MIAM') as date")
            ])
            ->where([
                'transactions.currency_iso'  => $currency,
                'transactions.whitelabel_id' => $whitelabelId,
            ])
            ->get();

        return [
            'transactions' => $transactions
        ];
    }
}

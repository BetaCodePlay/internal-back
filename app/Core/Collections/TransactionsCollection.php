<?php

namespace App\Core\Collections;

use App\Core\Repositories\TransactionsRepo;
use App\Reports\Repositories\ClosuresUsersTotalsRepo;
use App\Users\Repositories\UsersRepo;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Dotworkers\Bonus\Enums\AllocationCriteria;
use Dotworkers\Configurations\Enums\Codes;
use Dotworkers\Security\Enums\Roles;
use Dotworkers\Wallet\Wallet;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Configurations\Enums\PaymentMethods;
use Dotworkers\Configurations\Enums\Providers;
use Dotworkers\Configurations\Enums\TransactionStatus;
use Dotworkers\Configurations\Enums\TransactionTypes;
use Dotworkers\Configurations\Utils;
use Illuminate\Support\Facades\Log;
use Ixudra\Curl\Facades\Curl;

/**
 * Class TransactionsCollection
 *
 * This class allow to format transactions data
 *
 * @package App\Core\Collections
 * @author  Damelys Espinoza
 */
class TransactionsCollection
{
    /**
     * Format financial cash flow data grouped by users
     *
     * @param array $financial Financial data
     * @param string $startDate Start date to filter
     * @param string $endDate End date to filter
     * @param string $currency Currency Iso
     * @return array
     */
    public function formatCashFlowDataByUsers($financial, $whitelabel, $currency, $startDate, $endDate)
    {
        $financialData = [];
        $generalTotals = [];
        $credit = 0;
        $debit = 0;
        $transactionsRepo = new TransactionsRepo();

        if (count((array)$financial) > 0) {
            foreach ($financial['deposits'] as $depositsKey => $deposits) {
                foreach ($financial['withdrawals'] as $withdrawalsKey => $withdrawals) {
                    if ($deposits->id == $withdrawals->id) {
                        $firstTransaction = $transactionsRepo->getFirstAgentTransaction($deposits->id, $whitelabel, $currency, $startDate, $endDate);
                        $lastTransaction = $transactionsRepo->getLastAgentTransaction($deposits->id, $whitelabel, $currency, $startDate, $endDate);

                        if ($firstTransaction->transaction_type_id == TransactionTypes::$debit) {
                            $initialBalance = $firstTransaction->data->balance + $firstTransaction->amount;
                        } else {
                            $initialBalance = $firstTransaction->data->balance - $firstTransaction->amount;
                        }

                        $finalBalance = $lastTransaction->data->balance;
                        $dateProfit = $deposits->total - $withdrawals->total;
                        $credit += $deposits->total;
                        $debit += $withdrawals->total;
                        $user = sprintf(
                            '<a href="%s" class="btn u-btn-3d u-btn-primary btn-sm" target="_blank">%s</a>',
                            route('users.details', $deposits->id),
                            $deposits->id
                        );
                        $financialData[] = [
                            'user' => $user,
                            'username' => $deposits->username,
                            'debit' => number_format($deposits->total, 2),
                            'credit' => number_format($withdrawals->total, 2),
                            'profit' => number_format($dateProfit, 2),
                            'initial_balance' => number_format($initialBalance, 2),
                            'final_balance' => number_format($finalBalance, 2)
                        ];
                        unset($financial['deposits'][$depositsKey]);
                        unset($financial['withdrawals'][$withdrawalsKey]);
                    }
                }
            }
            foreach ($financial['deposits'] as $depositItem) {
                $firstTransaction = $transactionsRepo->getFirstAgentTransaction($depositItem->id, $whitelabel, $currency, $startDate, $endDate);
                $lastTransaction = $transactionsRepo->getLastAgentTransaction($depositItem->id, $whitelabel, $currency, $startDate, $endDate);

                if ($firstTransaction->transaction_type_id == TransactionTypes::$debit) {
                    $initialBalance = $firstTransaction->data->balance + $firstTransaction->amount;
                } else {
                    $initialBalance = $firstTransaction->data->balance - $firstTransaction->amount;
                }

                $finalBalance = $lastTransaction->data->balance;
                $credit += $depositItem->total;
                $user = sprintf(
                    '<a href="%s" class="btn u-btn-3d u-btn-primary btn-sm" target="_blank">%s</a>',
                    route('users.details', $depositItem->id),
                    $depositItem->id
                );
                $financialData[] = [
                    'user' => $user,
                    'username' => $depositItem->username,
                    'debit' => number_format($depositItem->total, 2),
                    'credit' => number_format(0, 2),
                    'profit' => number_format($depositItem->total, 2),
                    'initial_balance' => number_format($initialBalance, 2),
                    'final_balance' => number_format($finalBalance, 2)
                ];
            }
            foreach ($financial['withdrawals'] as $debitItem) {
                $debit += $debitItem->total;
                $firstTransaction = $transactionsRepo->getFirstAgentTransaction($debitItem->id, $whitelabel, $currency, $startDate, $endDate);
                $lastTransaction = $transactionsRepo->getLastAgentTransaction($debitItem->id, $whitelabel, $currency, $startDate, $endDate);

                if ($firstTransaction->transaction_type_id == TransactionTypes::$debit) {
                    $initialBalance = $firstTransaction->data->balance + $firstTransaction->amount;
                } else {
                    $initialBalance = $firstTransaction->data->balance - $firstTransaction->amount;
                }

                $user = sprintf(
                    '<a href="%s" class="btn u-btn-3d u-btn-primary btn-sm" target="_blank">%s</a>',
                    route('users.details', $debitItem->id),
                    $debitItem->id
                );
                $finalBalance = $lastTransaction->data->balance;
                $financialData[] = [
                    'user' => $user,
                    'username' => $debitItem->username,
                    'debit' => number_format(0, 2),
                    'credit' => number_format($debitItem->total, 2),
                    'profit' => number_format(-$debitItem->total, 2),
                    'initial_balance' => number_format($initialBalance, 2),
                    'final_balance' => number_format($finalBalance, 2)
                ];
            }
        }

        $generalTotals['credit'] = number_format($credit, 2);
        $generalTotals['debit'] = number_format($debit, 2);

        return [
            'financial' => $financialData,
            'totals' => $generalTotals
        ];
    }

    /**
     * Format daily sales
     *
     * @param array $sales Totals sales data
     * @param array $period Dates period
     * @param string $convert Currency for convert to
     * @param string $currency Currency for convert from
     * @param int $whitelabel Whitelabel ID
     * @param string $startDate Start date to filter
     * @param string $endDate End date to filter
     */
    public function formatDailySales($sales, $period, $convert, $currency, $startDate, $endDate, $whitelabel)
    {

        $closuresUsersTotalsRepo = new ClosuresUsersTotalsRepo();
        $usersRepo = new UsersRepo();
        $transactionsRepo = new TransactionsRepo();
        $totalsData = [];
        $auxDate = [];
        $exchangeRates = new \stdClass();
        $dayQuantity = 0;
        $timezone = session('timezone');
        $currentDate = Carbon::now($timezone);
        $currency = $currency == 'VES' ? 'VEF' : $currency;
        $convert = $convert == 'VES' ? 'VEF' : $convert;
        $totalNewRegisters = 0;
        $totalUniqueDepositors = 0;
        $totalFtd = 0;
        $totalBonus = 0;
        $totalCreditRejected = 0;
        $totalCreditApproved = 0;
        $totalCredit = 0;
        $totalDebitRejected = 0;
        $totalDebitApproved = 0;
        $totalDebit = 0;
        $totalCreditManual = 0;
        $totalDebitManual = 0;
        $totalPlayed = 0;
        $totalProfit = 0;
        $percentageNewRegisters = 0;
        $percentageUniqueDepositors = 0;
        $percentageFtd = 0;
        $percentageBonus = 0;
        $percentageCreditRejected = 0;
        $percentageCreditApproved = 0;
        $percentageDebitRejected = 0;
        $percentageDebitApproved = 0;
        $percentageCreditManual = 0;
        $percentageDebitManual = 0;
        $percentagePlayed = 0;
        $percentageProfit = 0;
        $avgNewRegistred = 0;
        $avgUniqueDeposit = 0;
        $avgFtd = 0;
        $avgBonus = 0;
        $avgCreditRejected = 0;
        $avgCreditApproved = 0;
        $avgCredit = 0;
        $avgDebit = 0;
        $avgDebitRejected = 0;
        $avgDebitApproved = 0;
        $avgProfit = 0;

        if (!is_null($convert)) {
            $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $startDate)->format('Y-m-d');
            $endDate = Carbon::createFromFormat('Y-m-d H:i:s', $endDate)->format('Y-m-d');
            $baseURL = env('FIXER_TIME_SERIES_URL') . '?access_key=' . env('FIXER_API_KEY') . "&start_date=$startDate&end_date=$endDate";

            if (is_null($currency)) {
                $whilabelCurrencies = Configurations::getCurrencies();

                foreach ($whilabelCurrencies as $whilabelCurrency) {
                    $url = $baseURL . "&base=$whilabelCurrency&symbols=$convert";
                    $curl = Curl::to($url)->get();
                    $exchangeResponse = json_decode($curl);
                    $exchangeRates->$whilabelCurrency = $exchangeResponse;
                }
            } else {
                $url = $baseURL . "&base=$currency&symbols=$convert";
                $curl = Curl::to($url)->get();
                $exchangeResponse = json_decode($curl);
                $exchangeRates->$currency = $exchangeResponse;
            }
        }

        foreach ($period as $date) {
            $dateConverted = $date->format('d-m-Y');
            $shortDate = $date->format('Y-m-d');
            if ($date->format('m-Y') == $currentDate->format('m-Y')) {
                if ($dateConverted <= $currentDate) {
                    $dayQuantity++;
                }
            } else {
                $dayQuantity++;
            }

            if (!in_array($dateConverted, $auxDate)) {
                $auxDate[] = $dateConverted;
                $startOfDay = Utils::startOfDayUtc($date->copy()->startOfDay()->format('Y-m-d'));
                $endOfDay = Utils::endOfDayUtc($date->copy()->endOfDay()->format('Y-m-d'));

                $totalObject = new \stdClass();
                $totalObject->date = $dateConverted;
                $totalObject->new_registers = $usersRepo->getRegisteredUsers($whitelabel, $startOfDay, $endOfDay)->count();
                $totalObject->unique_depositors = $transactionsRepo->getUniqueDepositors($whitelabel, $currency, $startOfDay, $endOfDay)->count();
                $totalObject->active_users = $closuresUsersTotalsRepo->getActiveUsers($whitelabel, $startOfDay, $endOfDay)->count();
                $totalObject->ftd = $usersRepo->getFirstDepositUsers($whitelabel, $startOfDay, $endOfDay)->count();
                $totalObject->bonus = 0;
                $totalObject->credit_rejected = 0;
                $totalObject->credit_approved = 0;
                $totalObject->credit_total = 0;
                $totalObject->debit_rejected = 0;
                $totalObject->debit_approved = 0;
                $totalObject->debit_total = 0;
                $totalObject->credit_manual = 0;
                $totalObject->debit_manual = 0;
                $totalObject->played = 0;
                $totalObject->profit = 0;
                $totalsData[] = $totalObject;
            }

            foreach ($sales as $saleKey => $sale) {
                $startOfDay = Utils::startOfDayUtc($sale->date);
                $endOfDay = Utils::endOfDayUtc($sale->date);
                $closure = $closuresUsersTotalsRepo->getUsersTotalsByWhitelabelAndDates($startOfDay, $endOfDay, $whitelabel, $sale->currency_iso);

                if (!is_null($convert)) {
                    $exchangeRate = $exchangeRates->{$sale->currency_iso}->rates->{$shortDate}->{$convert};
                    $bonus = is_null($exchangeRate) ? 0 : $sale->bonus * $exchangeRate;
                    $creditApproved = is_null($exchangeRate) ? 0 : $sale->credit_approved * $exchangeRate;
                    $creditRejected = is_null($exchangeRate) ? 0 : $sale->credit_rejected * $exchangeRate;
                    $debitRejected = is_null($exchangeRate) ? 0 : $sale->debit_rejected * $exchangeRate;
                    $debitApproved = is_null($exchangeRate) ? 0 : $sale->debit_approved * $exchangeRate;
                    $creditManual = is_null($exchangeRate) ? 0 : $sale->credit_manual * $exchangeRate;
                    $debitManual = is_null($exchangeRate) ? 0 : $sale->debit_manual * $exchangeRate;
                    $played = is_null($exchangeRate) ? 0 : $closure->played * $exchangeRate;
                    $profit = is_null($exchangeRate) ? 0 : $closure->profit * $exchangeRate;

                } else {
                    $bonus = $sale->bonus;
                    $creditApproved = $sale->credit_approved;
                    $creditRejected = $sale->credit_rejected;
                    $debitRejected = $sale->debit_rejected;
                    $debitApproved = $sale->debit_approved;
                    $creditManual = $sale->credit_manual;
                    $debitManual = $sale->debit_manual;
                    $played = $closure->played;
                    $profit = $closure->profit;
                }

                if ($shortDate == $sale->date) {
                    foreach ($totalsData as $totalData) {
                        if ($totalData->date == $dateConverted) {
                            $totalData->bonus += $bonus;
                            $totalData->credit_rejected += $creditRejected;
                            $totalData->credit_approved += $creditApproved;
                            $totalData->debit_rejected += $debitRejected;
                            $totalData->debit_approved += $debitApproved;
                            $totalData->credit_manual += $creditManual;
                            $totalData->debit_manual += $debitManual;
                            $totalData->played = $played;
                            $totalData->profit = $profit;
                            $totalBonus += $bonus;
                            $totalCreditRejected += $creditRejected;
                            $totalCreditApproved += $creditApproved;
                            $totalDebitRejected += $debitRejected;
                            $totalDebitApproved += $debitApproved;
                            $totalCreditManual += $creditManual;
                            $totalDebitManual += $debitManual;
                            unset($sales[$saleKey]);
                        }
                    }
                }
            }
        }

        foreach ($totalsData as $totalData) {
            $sumCredit = 0;
            $sumDebit = 0;
            $sumCredit = $totalData->credit_rejected + $totalData->credit_approved;
            $sumDebit = $totalData->debit_rejected + $totalData->debit_approved;
            $totalCredit += $sumCredit;
            $totalDebit += $sumDebit;
            $totalNewRegisters += $totalData->new_registers;
            $totalUniqueDepositors += $totalData->unique_depositors;
            $totalFtd += $totalData->ftd;
            $totalPlayed += $totalData->played;
            $totalProfit += $totalData->profit;
            $totalData->bonus = number_format($totalData->bonus, 2);
            $totalData->credit_rejected = number_format($totalData->credit_rejected, 2);
            $totalData->credit_approved = number_format($totalData->credit_approved, 2);
            $totalData->credit_total = number_format($sumCredit, 2);
            $totalData->debit_rejected = number_format($totalData->debit_rejected, 2);
            $totalData->debit_approved = number_format($totalData->debit_approved, 2);
            $totalData->debit_total = number_format($sumDebit, 2);
            $totalData->credit_manual = number_format($totalData->credit_manual, 2);
            $totalData->debit_manual = number_format($totalData->debit_manual, 2);
            $totalData->played = number_format($totalData->played, 2);
            $totalData->profit = number_format($totalData->profit, 2);
        }


        if ($totalCredit !== 0) {
            $avgBonus = ($totalBonus / $totalCredit) * 100;
        }
        if ($totalCreditRejected > 0) {
            $avgCreditRejected = ($totalCredit / $totalCreditRejected) * 100;
        }
        if ($totalCreditApproved !== 0) {
            $avgCreditApproved = ($totalCredit / $totalCreditApproved) * 100;
        }
        if ($totalDebitRejected !== 0) {
            $avgDebitRejected = ($totalDebit / $totalDebitRejected) * 100;
        }
        if ($totalDebitApproved !== 0) {
            $avgDebitApproved = ($totalDebit / $totalDebitApproved) * 100;
        }
        if ($totalCredit !== 0) {
            $avgCredit = ($totalDebit / $totalCredit) * 100;
        }
        if ($totalDebit !== 0) {
            $avgDebit = ($totalCredit / $totalDebit) * 100;
        }

        if ($dayQuantity !== 0) {
            if ($totalNewRegisters !== 0) {
                $percentageNewRegisters = $dayQuantity / $totalNewRegisters;
            }
            if ($totalUniqueDepositors !== 0) {
                $percentageUniqueDepositors = $dayQuantity / $totalUniqueDepositors;
            }
            if ($totalFtd !== 0) {
                $percentageFtd = $dayQuantity / $totalFtd;
            }
            if ($totalBonus !== 0) {
                $percentageBonus = $dayQuantity / $totalBonus;
            }
            if ($totalCreditRejected > 0) {
                $percentageCreditRejected = $dayQuantity / $totalCreditRejected;
            }
            if ($totalCreditApproved !== 0) {
                $percentageCreditApproved = $dayQuantity / $totalCreditApproved;
            }
            if ($totalDebitRejected !== 0) {
                $percentageDebitRejected = $dayQuantity / $totalDebitRejected;
            }
            if ($totalDebitApproved !== 0) {
                $percentageDebitApproved = $dayQuantity / $totalDebitApproved;
            }
            if ($totalCreditManual !== 0) {
                $percentageCreditManual = $dayQuantity / $totalCreditManual;
            }
            if ($totalDebitManual !== 0) {
                $percentageDebitManual = $dayQuantity / $totalDebitManual;
            }
            if ($totalPlayed !== 0) {
                $percentagePlayed = $dayQuantity / $totalPlayed;
            }
            if ($totalProfit !== 0) {
                $percentageProfit = $dayQuantity / $totalProfit;
            }
            if ($totalNewRegisters !== 0) {
                $avgNewRegistred = $totalNewRegisters / $dayQuantity;
            }
            if ($totalUniqueDepositors !== 0) {
                $avgUniqueDeposit = $totalUniqueDepositors / $dayQuantity;
            }
            if ($totalFtd !== 0) {
                $avgFtd = $totalFtd / $dayQuantity;
            }
            if ($totalProfit !== 0) {
                $avgProfit = $totalProfit / $dayQuantity;
            }
        }

        return [
            'sales' => $totalsData,
            'total_new_registers' => $totalNewRegisters,
            'total_unique_depositors' => $totalUniqueDepositors,
            'total_ftd' => $totalFtd,
            'total_bonus' => number_format($totalBonus, 2),
            'total_credit_rejected' => number_format($totalCreditRejected, 2),
            'total_credit_approved' => number_format($totalCreditApproved, 2),
            'total_debit_rejected' => number_format($totalDebitRejected, 2),
            'total_debit_approved' => number_format($totalDebitApproved, 2),
            'total_credit_manual' => number_format($totalCreditManual, 2),
            'total_debit_manual' => number_format($totalDebitManual, 2),
            'total_played' => number_format($totalPlayed, 2),
            'total_profit' => number_format($totalProfit, 2),
            'percentage_new_registers' => number_format($percentageNewRegisters, 2) . '%',
            'percentage_unique_depositors' => number_format($percentageUniqueDepositors, 2) . '%',
            'percentage_ftd' => number_format($percentageFtd, 2) . '%',
            'percentage_bonus' => number_format($percentageBonus, 2) . '%',
            'percentage_credit_rejected' => number_format(substr($percentageCreditRejected, 0, 6), 2) . '%',
            'percentage_credit_approved' => number_format(substr($percentageCreditApproved, 0, 6), 2) . '%',
            'percentage_debit_rejected' => number_format(substr($percentageDebitRejected, 0, 6), 2) . '%',
            'percentage_debit_approved' => number_format(substr($percentageDebitApproved, 0, 6), 2) . '%',
            'percentage_credit_manual' => number_format(substr($percentageCreditManual, 0, 6), 2) . '%',
            'percentage_debit_manual' => number_format(substr($percentageDebitManual, 0, 6), 2) . '%',
            'percentage_played' => number_format(substr($percentagePlayed, 0, 6), 2) . '%',
            'percentage_profit' => number_format(substr($percentageProfit, 0, 6), 2) . '%',
            'average_new_registered' => number_format($avgNewRegistred, 2) . '%',
            'average_unique_deposit' => number_format($avgUniqueDeposit, 2) . '%',
            'average_ftd' => number_format($avgFtd, 2) . '%',
            'average_bonus' => number_format($avgBonus, 2) . '%',
            'average_credit_approved' => number_format($avgCreditApproved, 2) . '%',
            'average_credit_rejected' => number_format($avgCreditRejected, 2) . '%',
            'average_debit_approved' => number_format($avgDebitApproved, 2) . '%',
            'average_debit_rejected' => number_format($avgDebitRejected, 2) . '%',
            'average_credit' => number_format($avgCredit, 2) . '%',
            'average_debit' => number_format($avgDebit, 2) . '%',
            'average_profit' => number_format($avgProfit, 2) . '%',
        ];
    }

    /**
     * Format dashboard graphic
     *
     * @param array $period Dates period
     * @param array $transactions Transactions data
     * @return array
     */
    public function formatDashboardGraphic($period, $transactions)
    {
        $financialData = [];

        foreach ($period as $key => $date) {
            $date = $date->format('Y-m');
            $dateCredit = 0;
            $dateDebit = 0;
            if (count((array)$transactions) > 0) {
                foreach ($transactions['deposits'] as $depositKey => $deposit) {
                    $newDate = date("Y-m", strtotime($deposit->created_at));
                    if ($newDate == $date) {
                        $dateCredit += $deposit->amount;
                        unset($transactions['deposits'][$depositKey]);
                    }
                }

                foreach ($transactions['withdrawals'] as $withdrawalKey => $withdrawal) {
                    $newDate = date("Y-m", strtotime($withdrawal->created_at));
                    if ($newDate == $date) {
                        $dateDebit += $withdrawal->amount;
                        unset($transactions['withdrawals'][$withdrawalKey]);
                    }
                }
                $dateProfit = $dateCredit - $dateDebit;

                $financialData[] = [
                    $date => [
                        _i('Deposits') => number_format($dateCredit, 2),
                        _i('Withdrawals') => number_format($dateDebit, 2),
                        _i('Profit') => number_format($dateProfit, 2)
                    ]
                ];
            }
        }
        return $financialData;
    }

    /**
     * Format deposits and withdrawals
     *
     * @param array $transactions Transactions data
     * @param string $currency Currency Iso
     */
    public function formatDepositsAndWithdrawals($transactions, $currency)
    {
        $total = 0;
        foreach ($transactions as $transaction) {
            $total += $transaction->amount;
            $timezone = session('timezone');
            $transaction->created = $transaction->created_at->setTimezone($timezone)->format('d-m-Y H:i:s');
            $transaction->updated = $transaction->updated_at->setTimezone($timezone)->format('d-m-Y H:i:s');
            $transaction->amount = number_format($transaction->amount, 2);
            $transaction->description = Providers::getDescription($transaction->provider_id, $transaction->transaction_type_id, $action = null, $transaction->data);
            $transaction->details = json_decode($transaction->details);
            $transaction->info = '';

            if (isset($transaction->data->operator)) {
                $transaction->operator = $transaction->data->operator;
            } else {
                $transaction->operator = null;
            }

            $wallet = Wallet::getByClient($transaction->user, $currency, false);
            if ($wallet->code == Codes::$ok) {
                $transaction->wallet = $wallet->data->wallet->id;
            } else {
                $transaction->wallet = '';
            }

            switch ($transaction->transaction_status_id) {
                case TransactionStatus::$pending:
                {
                    $statusText = _i('Pending');
                    break;
                }
                case TransactionStatus::$approved:
                {
                    $statusText = _i('Approved');
                    break;
                }
                case TransactionStatus::$processing:
                {
                    $statusText = _i('Processing');
                    break;
                }
                case TransactionStatus::$rejected:
                {
                    $statusText = _i('Rejected');
                    break;
                }
                case TransactionStatus::$rejected_by_bank:
                {
                    $statusText = _i('Rejected by bank');
                    break;
                }
                case TransactionStatus::$expired:
                {
                    $statusText = _i('Expired');
                    break;
                }
            }
            $transaction->user = sprintf(
                '<a href="%s" class="btn u-btn-3d u-btn-primary btn-sm" target="_blank">%s</a>',
                route('users.details', $transaction->user),
                $transaction->user
            );
            $transaction->status = $statusText;
            $transaction->provider = Providers::getName($transaction->provider_id);
            $transaction->currency = $transaction->currency_iso;

            if (!is_null($transaction->details) || !is_null($transaction->data)) {
                switch ($transaction->provider_id) {
                    case Providers::$vcreditos_api:
                    {
                        if (isset($transaction->details->vcreditos_transaction_id)) {
                            $vcreditosApi = $transaction->details->vcreditos_transaction_id;
                        } else {
                            $vcreditosApi = '';
                        }
                        $transaction->info = $vcreditosApi;
                        break;
                    }
                    case Providers::$just_pay:
                    {
                        if (isset($transaction->data->betpay_transaction)) {
                            $justPay = $transaction->data->betpay_transaction;
                        } else {
                            $justPay = '';
                        }
                        $transaction->info = $justPay;
                        break;
                    }
                    default:
                    {
                        $transaction->info = '';
                        break;
                    }
                }
            } else {
                $transaction->info = '';
            }
        }
        return [
            'transactions' => $transactions,
            'total' => number_format($total, 2)
        ];
    }

    /**
     * Format deposits and withdrawals
     *
     * @param array $transactions Transactions data
     */
    public function formatManualAdjustmentsDepositsAndWithdrawals($transactions)
    {
        foreach ($transactions as $transaction) {
            $timezone = session('timezone');
            $transaction->created = $transaction->created_at->setTimezone($timezone)->format('d-m-Y H:i:s');
            $transaction->updated = $transaction->updated_at->setTimezone($timezone)->format('d-m-Y H:i:s');
            $transaction->amount = number_format($transaction->amount, 2);
            $transaction->description = Providers::getDescription($transaction->provider_id, $transaction->transaction_type_id, $action = null, $transaction->data);
            $transaction->details = json_decode($transaction->details);
            $transaction->transaction_type = $transaction->transaction_type_id == TransactionTypes::$debit ?  _i('Debit')  : _i('Credit');

            if (isset($transaction->details->operator)) {
                $transaction->operator = $transaction->details->operator;
            } else {
                $transaction->operator = null;
            }

            switch ($transaction->transaction_status_id) {
                case TransactionStatus::$pending:
                {
                    $statusText = _i('Pending');
                    break;
                }
                case TransactionStatus::$approved:
                {
                    $statusText = _i('Approved');
                    break;
                }
                case TransactionStatus::$processing:
                {
                    $statusText = _i('Processing');
                    break;
                }
                case TransactionStatus::$rejected:
                {
                    $statusText = _i('Rejected');
                    break;
                }
            }
            $transaction->user = sprintf(
                '<a href="%s" class="btn u-btn-3d u-btn-primary btn-sm" target="_blank">%s</a>',
                route('users.details', $transaction->user),
                $transaction->user
            );
            $transaction->status = $statusText;
            $transaction->provider = Providers::getName($transaction->provider_id);
            $transaction->currency =  $transaction->currency_iso;

            if (!is_null($transaction->details)) {
                switch ($transaction->provider_id) {
                    case Providers::$vcreditos_api:
                    {
                        if (isset($transaction->details->vcreditos_transaction_id)) {
                            $vcreditosApi = $transaction->details->vcreditos_transaction_id;
                        } else {
                            $vcreditosApi = '';
                        }
                        $transaction->info = $vcreditosApi;
                        break;
                    }
                    default:
                    {
                        $transaction->info = '';
                        break;
                    }
                }
            } else {
                $transaction->info = '';
            }
        }
        return [
            'transactions' => $transactions,
        ];
    }

    /**
     * Format deposists withdrawals provider
     *
     * @param array $transactions Transactions  data
     */
    public function formatDeposistsWithdrawalsProvider($transactions)
    {
        $transactionsData = [];
        $auxUser = [];

        foreach ($transactions as $key => $transaction) {

            if (!in_array($transaction->user, $auxUser)) {
                $auxUser[] = $transaction->user;
                $totalObject = new \stdClass();
                $totalObject->username = $transaction->username;
                $totalObject->amount = $transaction->amount;
                $totalObject->user = sprintf(
                    '<a href="%s" class="btn u-btn-3d u-btn-primary btn-sm" target="_blank">%s</a>',
                    route('users.details', $transaction->user),
                    $transaction->user
                );
                $totalObject->id = $transaction->user;
                $transactionsData[] = $totalObject;
            }

            foreach ($transactionsData as $transactionData) {
                if ($transactionData->id == $transaction->user) {
                    $transactionData->amount += $transaction->amount;
                }
            }
        }

        foreach ($transactionsData as $transactionData) {
            $transactionData->amount = number_format($transactionData->amount, 2);
        }

        return [
            'agents' => $transactionsData
        ];
    }

    /**
     * Format financial totals by currency
     *
     * @param array $totals Totals financial currencies data
     */
    public function formatFinancialTotalsByCurrency($totals)
    {
        $profit = 0;
        $totalsData = [];

        foreach ($totals as $total) {
            $credit = 0;
            $total_operations_credit = 0;
            $debit = 0;
            $total_operations_debit = 0;
            $provider = Providers::getName($total->provider_id);

            if ($total->transaction_type_id == TransactionTypes::$credit) {
                $credit = $total->amount;
                $total_operations_credit++;
            }
            if ($total->transaction_type_id == TransactionTypes::$debit) {
                $debit = $total->amount;
                $total_operations_debit++;
            }

            if (count($totalsData) > 0) {
                foreach ($totalsData as $totalData) {
                    if ($totalData->provider_id == $total->provider_id) {
                        $totalData->total_operations_credit += $total_operations_credit;
                        $totalData->credit += $credit;
                        $totalData->total_operations_debit += $total_operations_debit;
                        $totalData->debit += $debit;
                    } else {
                        $totalObject = new \stdClass();
                        $totalObject->provider_id = $total->provider_id;
                        $totalObject->provider = $provider;
                        $totalObject->total_operations_credit = $total_operations_credit;
                        $totalObject->credit = $credit;
                        $totalObject->total_operations_debit = $total_operations_debit;
                        $totalObject->debit = $debit;
                        $totalsData[] = $totalObject;
                    }
                }
            } else {
                $totalObject = new \stdClass();
                $totalObject->provider_id = $total->provider_id;
                $totalObject->provider = $provider;
                $totalObject->total_operations_credit = $total_operations_credit;
                $totalObject->credit = $credit;
                $totalObject->total_operations_debit = $total_operations_debit;
                $totalObject->debit = $debit;
                $totalObject->profit = $profit;
                $totalsData[] = $totalObject;
            }
        }

        foreach ($totalsData as $totalData) {
            $profit = $totalData->credit - $totalData->debit;
            $totalData->credit = number_format($totalData->credit, 2);
            $totalData->debit = number_format($totalData->debit, 2);
            $totalData->profit = number_format($profit, 2);
        }

        return [
            'totals' => $totalsData,
        ];
    }

    /**
     * Format payment method
     *
     * @param array $totals Totals payment method data4
     */
    public function formatPaymentMethods($totals)
    {
        $generalTotals = [];
        $creditPendingTotal = 0;
        $creditApprovedTotal = 0;
        $debitPendingTotal = 0;
        $debitApprovedTotal = 0;
        $profitTotal = 0;
        $count = 0;

        foreach ($totals as $total) {
            $creditPendingTotal += $total->credit_pending;
            $creditApprovedTotal += $total->credit_approved;
            $debitPendingTotal += $total->debit_pending;
            $debitApprovedTotal += $total->debit_approved;
            $total->profit = $total->credit_approved - $total->debit_approved;
            $profitTotal += $total->profit;
            $total->payment_method = PaymentMethods::getName($total->payment_method_id);
            $total->credit_pending = number_format($total->credit_pending, 2);
            $total->credit_approved = number_format($total->credit_approved, 2);
            $total->debit_pending = number_format($total->debit_pending, 2);
            $total->debit_approved = number_format($total->debit_approved, 2);
            $total->profit = number_format($total->profit, 2);
        }

        $generalTotals['credit_pending'] = number_format($creditPendingTotal, 2);
        $generalTotals['credit_approved'] = number_format($creditApprovedTotal, 2);
        $generalTotals['debit_pending'] = number_format($debitPendingTotal, 2);
        $generalTotals['debit_approved'] = number_format($debitApprovedTotal, 2);
        $generalTotals['profit'] = number_format($profitTotal, 2);

        return [
            'totals' => $totals,
            'general_totals' => $generalTotals
        ];
    }

    /**
     * Format financial data by dates
     *
     * @param array $transactions Transactions data
     * @param null|string $startDate Start date to filter
     * @param null|string $endDate End date to filter
     * @return array[]
     */
    public function formatFinancialDataByDates($transactions, $startDate, $endDate)
    {
        $financialData = [];
        $generalTotals = [];
        $credit = 0;
        $debit = 0;
        $profit = 0;

        if (!is_null($startDate) && !is_null($endDate)) {
            $timezone = session('timezone');
            $period = CarbonPeriod::create($startDate, $endDate);
            foreach ($period as $date) {
                $date = $date->format('d-m-Y');
                $dateCredit = 0;
                $dateDebit = 0;
                foreach ($transactions['deposits'] as $depositKey => $deposit) {
                    $depositDate = Carbon::createFromFormat('Y-m-d', $deposit->date, $timezone)->format('d-m-Y');
                    if ($depositDate == $date) {
                        $dateCredit += $deposit->total;
                        unset($transactions['deposits'][$depositKey]);
                    }
                }

                foreach ($transactions['withdrawals'] as $withdrawalKey => $withdrawal) {
                    $withdrawalDate = Carbon::createFromFormat('Y-m-d', $withdrawal->date, $timezone)->format('d-m-Y');
                    if ($withdrawalDate == $date) {
                        $dateDebit += $withdrawal->total;
                        unset($transactions['withdrawals'][$withdrawalKey]);
                    }
                }

                $dateProfit = $dateCredit - $dateDebit;
                $credit += $dateCredit;
                $debit += $dateDebit;
                $profit += $dateProfit;

                $financialData[] = [
                    'date' => $date,
                    'debit' => number_format($dateDebit, 2),
                    'credit' => number_format($dateCredit, 2),
                    'profit' => number_format($dateProfit, 2)
                ];

            }
        }

        $generalTotals['credit'] = number_format($credit, 2);
        $generalTotals['debit'] = number_format($debit, 2);
        $generalTotals['profit'] = number_format($profit, 2);

        return [
            'financial' => $financialData,
            'totals' => $generalTotals
        ];
    }

    /**
     * Format financial data grouped by dates
     *
     * @param array $financial Financial data
     * @return array
     */
    public function formatFinancialData($financial)
    {
        $financialData = [];
        $generalTotals = [];
        $credit = 0;
        $debit = 0;
        $profit = 0;

        if (count((array)$financial) > 0) {
            foreach ($financial['deposits'] as $key => $deposits) {
                foreach ($financial['withdrawals'] as $withdrawals) {
                    if ($deposits->date == $withdrawals->date) {
                        $dateProfit = $deposits->total - $withdrawals->total;
                        $credit += $deposits->total;
                        $debit += $withdrawals->total;
                        $profit += $dateProfit;
                        $financialData[] = [
                            'date' => date('d-m-Y', strtotime($deposits->date)),
                            'debit' => number_format($deposits->total, 2),
                            'credit' => number_format($withdrawals->total, 2),
                            'profit' => number_format($dateProfit, 2)
                        ];
                        unset($financial['deposits'][$key]);
                    }
                }
            }
            foreach ($financial['deposits'] as $depositItem) {
                $credit += $depositItem->total;
                $profit += $depositItem->total;
                $financialData[] = [
                    'date' => date('d-m-Y', strtotime($depositItem->date)),
                    'debit' => number_format($depositItem->total, 2),
                    'credit' => number_format(0, 2),
                    'profit' => number_format($depositItem->total, 2),
                ];
            }
        }

        $generalTotals['credit'] = number_format($credit, 2);
        $generalTotals['debit'] = number_format($debit, 2);
        $generalTotals['profit'] = number_format($profit, 2);

        return [
            'financial' => $financialData,
            'totals' => $generalTotals
        ];
    }

    /**
     * Format monthly sales data
     *
     * @param object $sales Totals sales data
     * @param object $period Dates period
     * @param string|null $convert Currency for convert to
     * @param string $currency Currency for convert
     * @param string $startDate Start date to filter
     * @param string $endDate End date to filter
     * @param int $whitelabel Whitelabel ID
     * @return array
     */
    public function formatMonthlySales($sales, $period, $convert, $currency, $startDate, $endDate, $whitelabel)
    {
        $closuresUsersTotalsRepo = new ClosuresUsersTotalsRepo();
        $usersRepo = new UsersRepo();
        $transactionsRepo = new TransactionsRepo();
        $totalsData = [];
        $auxDate = [];
        $exchangeRates = new \stdClass();
        $currency = $currency == 'VES' ? 'VEF' : $currency;
        $convert = $convert == 'VES' ? 'VEF' : $convert;
        $dayQuantity = 0;
        $timezone = session('timezone');
        $totalNewRegisters = 0;
        $totalUniqueDepositors = 0;
        $totalFtd = 0;
        $totalBonus = 0;
        $totalCredit = 0;
        $totalCreditRejected = 0;
        $totalCreditApproved = 0;
        $totalDebit = 0;
        $totalDebitRejected = 0;
        $totalDebitApproved = 0;
        $totalCreditManual = 0;
        $totalDebitManual = 0;
        $totalPlayed = 0;
        $totalProfit = 0;
        $percentageNewRegisters = 0;
        $percentageUniqueDepositors = 0;
        $percentageFtd = 0;
        $percentageBonus = 0;
        $percentageCreditRejected = 0;
        $percentageCreditApproved = 0;
        $percentageDebitRejected = 0;
        $percentageDebitApproved = 0;
        $percentageCreditManual = 0;
        $percentageDebitManual = 0;
        $percentagePlayed = 0;
        $percentageProfit = 0;
        $avgNewRegistred = 0;
        $avgUniqueDeposit = 0;
        $avgFtd = 0;
        $avgBonus = 0;
        $avgCreditRejected = 0;
        $avgCreditApproved = 0;
        $avgCredit = 0;
        $avgDebit = 0;
        $avgDebitRejected = 0;
        $avgDebitApproved = 0;
        $avgProfit = 0;


        if (!is_null($convert)) {
            $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $startDate)->format('Y-m-d');
            $endDate = Carbon::createFromFormat('Y-m-d H:i:s', $endDate)->format('Y-m-d');
            $baseURL = env('FIXER_TIME_SERIES_URL') . '?access_key=' . env('FIXER_API_KEY') . "&start_date=$startDate&end_date=$endDate";

            if (is_null($currency)) {
                $whilabelCurrencies = Configurations::getCurrencies();

                foreach ($whilabelCurrencies as $whilabelCurrency) {
                    $url = $baseURL . "&base=$whilabelCurrency&symbols=$convert";
                    $curl = Curl::to($url)->get();
                    $exchangeResponse = json_decode($curl);
                    $exchangeRates->$whilabelCurrency = $exchangeResponse;
                }
            } else {
                $url = $baseURL . "&base=$currency&symbols=$convert";
                $curl = Curl::to($url)->get();
                $exchangeResponse = json_decode($curl);
                $exchangeRates->$currency = $exchangeResponse;
            }
        }

        foreach ($period as $date) {
            $dateConverted = $date->format('m-Y');
            $shortDate = $date->format('Y-m-d');

            if (!in_array($dateConverted, $auxDate)) {
                $auxDate[] = $dateConverted;
                $startOfMonth = Utils::startOfDayUtc($date->copy()->startOfMonth()->format('Y-m-d'));
                $endOfMonth = Utils::endOfDayUtc($date->copy()->endOfMonth()->format('Y-m-d'));
                $totalObject = new \stdClass();
                $totalObject->date = $dateConverted;
                $totalObject->new_registers = $usersRepo->getRegisteredUsers($whitelabel, $startOfMonth, $endOfMonth)->count();
                $totalObject->unique_depositors = $transactionsRepo->getUniqueDepositors($whitelabel, $currency, $startOfMonth, $endOfMonth)->count();
                $totalObject->active_users = $closuresUsersTotalsRepo->getActiveUsers($whitelabel, $startOfMonth, $endOfMonth)->count();
                $totalObject->ftd = $usersRepo->getFirstDepositUsers($whitelabel, $startOfMonth, $endOfMonth)->count();
                $totalObject->bonus = 0;
                $totalObject->credit_rejected = 0;
                $totalObject->credit_approved = 0;
                $totalObject->credit_total = 0;
                $totalObject->debit_rejected = 0;
                $totalObject->debit_approved = 0;
                $totalObject->debit_total = 0;
                $totalObject->credit_manual = 0;
                $totalObject->debit_manual = 0;
                $totalObject->played = 0;
                $totalObject->profit = 0;
                $totalsData[] = $totalObject;
                $dayQuantity++;
            }

            foreach ($sales as $saleKey => $sale) {
                $startOfDay = Utils::startOfDayUtc($sale->date);
                $endOfDay = Utils::endOfDayUtc($sale->date);
                $closure = $closuresUsersTotalsRepo->getUsersTotalsByWhitelabelAndDates($startOfDay, $endOfDay, $whitelabel, $sale->currency_iso);

                if (!is_null($convert)) {
                    $exchangeRate = $exchangeRates->{$sale->currency_iso}->rates->{$sale->date}->{$convert};
                    $bonus = is_null($exchangeRate) ? 0 : $sale->bonus * $exchangeRate;
                    $creditApproved = is_null($exchangeRate) ? 0 : $sale->credit_approved * $exchangeRate;
                    $creditRejected = is_null($exchangeRate) ? 0 : $sale->credit_rejected * $exchangeRate;
                    $debitRejected = is_null($exchangeRate) ? 0 : $sale->debit_rejected * $exchangeRate;
                    $debitApproved = is_null($exchangeRate) ? 0 : $sale->debit_approved * $exchangeRate;
                    $creditManual = is_null($exchangeRate) ? 0 : $sale->credit_manual * $exchangeRate;
                    $debitManual = is_null($exchangeRate) ? 0 : $sale->debit_manual * $exchangeRate;
                    $played = is_null($exchangeRate) ? 0 : $closure->played * $exchangeRate;
                    $profit = is_null($exchangeRate) ? 0 : $closure->profit * $exchangeRate;

                } else {
                    $bonus = $sale->bonus;
                    $creditApproved = $sale->credit_approved;
                    $creditRejected = $sale->credit_rejected;
                    $debitRejected = $sale->debit_rejected;
                    $debitApproved = $sale->debit_approved;
                    $creditManual = $sale->credit_manual;
                    $debitManual = $sale->debit_manual;
                    $played = $closure->played;
                    $profit = $closure->profit;
                }

                if ($shortDate == $sale->date) {
                    foreach ($totalsData as $totalData) {
                        if ($totalData->date == $dateConverted) {
                            $totalData->bonus += $bonus;
                            $totalData->credit_rejected += $creditRejected;
                            $totalData->credit_approved += $creditApproved;
                            $totalData->debit_rejected += $debitRejected;
                            $totalData->debit_approved += $debitApproved;
                            $totalData->credit_manual += $creditManual;
                            $totalData->debit_manual += $debitManual;
                            $totalData->played += $played;
                            $totalData->profit += $profit;
                            $totalBonus += $bonus;
                            $totalCreditRejected += $creditRejected;
                            $totalCreditApproved += $creditApproved;
                            $totalDebitRejected += $debitRejected;
                            $totalDebitApproved += $debitApproved;
                            $totalCreditManual += $creditManual;
                            $totalDebitManual += $debitManual;
                            unset($sales[$saleKey]);
                        }
                    }
                }
            }
        }

        foreach ($totalsData as $totalData) {
            $sumCredit = 0;
            $sumDebit = 0;
            $sumCredit = $totalData->credit_rejected + $totalData->credit_approved;
            $sumDebit = $totalData->debit_rejected + $totalData->debit_approved;
            $totalCredit += $sumCredit;
            $totalDebit += $sumDebit;
            $totalNewRegisters += $totalData->new_registers;
            $totalUniqueDepositors += $totalData->unique_depositors;
            $totalFtd += $totalData->ftd;
            $totalPlayed += $totalData->played;
            $totalProfit += $totalData->profit;
            $totalData->bonus = number_format($totalData->bonus, 2);
            $totalData->credit_rejected = number_format($totalData->credit_rejected, 2);
            $totalData->credit_approved = number_format($totalData->credit_approved, 2);
            $totalData->credit_total = number_format($sumCredit, 2);
            $totalData->debit_rejected = number_format($totalData->debit_rejected, 2);
            $totalData->debit_approved = number_format($totalData->debit_approved, 2);
            $totalData->debit_total = number_format($sumDebit, 2);
            $totalData->credit_manual = number_format($totalData->credit_manual, 2);
            $totalData->debit_manual = number_format($totalData->debit_manual, 2);
            $totalData->played = number_format($totalData->played, 2);
            $totalData->profit = number_format($totalData->profit, 2);
        }

        if ($totalCredit !== 0) {
            $avgBonus = ($totalBonus / $totalCredit) * 100;
        }
        if ($totalCreditRejected > 0) {
            $avgCreditRejected = ($totalCredit / $totalCreditRejected) * 100;
        }
        if ($totalCreditApproved !== 0) {
            $avgCreditApproved = ($totalCredit / $totalCreditApproved) * 100;
        }
        if ($totalDebitRejected !== 0) {
            $avgDebitRejected = ($totalDebit / $totalDebitRejected) * 100;
        }
        if ($totalDebitApproved !== 0) {
            $avgDebitApproved = ($totalDebit / $totalDebitApproved) * 100;
        }
        if ($totalCredit !== 0) {
            $avgCredit = ($totalDebit / $totalCredit) * 100;
        }
        if ($totalDebit !== 0) {
            $avgDebit = ($totalCredit / $totalDebit) * 100;
        }


        if ($dayQuantity !== 0) {
            if ($totalNewRegisters !== 0) {
                $percentageNewRegisters = $dayQuantity / $totalNewRegisters;
            }
            if ($totalUniqueDepositors !== 0) {
                $percentageUniqueDepositors = $dayQuantity / $totalUniqueDepositors;
            }
            if ($totalFtd !== 0) {
                $percentageFtd = $dayQuantity / $totalFtd;
            }
            if ($totalBonus !== 0) {
                $percentageBonus = $dayQuantity / $totalBonus;
            }
            if ($totalCreditRejected > 0) {
                $percentageCreditRejected = $dayQuantity / $totalCreditRejected;
            }
            if ($totalCreditApproved !== 0) {
                $percentageCreditApproved = $dayQuantity / $totalCreditApproved;
            }
            if ($totalDebitRejected !== 0) {
                $percentageDebitRejected = $dayQuantity / $totalDebitRejected;
            }
            if ($totalDebitApproved !== 0) {
                $percentageDebitApproved = $dayQuantity / $totalDebitApproved;
            }
            if ($totalCreditManual > 0) {
                $percentageCreditManual = $dayQuantity / $totalCreditManual;
            }
            if ($totalDebitManual > 0) {
                $percentageDebitManual = $dayQuantity / $totalDebitManual;
            }
            if ($totalPlayed !== 0) {
                $percentagePlayed = $dayQuantity / $totalPlayed;
            }
            if ($totalDebitManual > 0) {
                $percentageProfit = $dayQuantity / $totalDebitManual;
            }
            if ($totalNewRegisters !== 0) {
                $avgNewRegistred = $totalNewRegisters / $dayQuantity;
            }
            if ($totalUniqueDepositors !== 0) {
                $avgUniqueDeposit = $totalUniqueDepositors / $dayQuantity;
            }
            if ($totalFtd !== 0) {
                $avgFtd = $totalFtd / $dayQuantity;
            }
            if ($totalProfit !== 0) {
                $avgProfit = $totalProfit / $dayQuantity;
            }
        }

        return [
            'sales' => $totalsData,
            'total_new_registers' => $totalNewRegisters,
            'total_unique_depositors' => $totalUniqueDepositors,
            'total_ftd' => $totalFtd,
            'total_bonus' => number_format($totalBonus, 2),
            'total_credit_rejected' => number_format($totalCreditRejected, 2),
            'total_credit_approved' => number_format($totalCreditApproved, 2),
            'total_credit' => number_format($totalCredit, 2),
            'total_debit_rejected' => number_format($totalDebitRejected, 2),
            'total_debit_approved' => number_format($totalDebitApproved, 2),
            'total_debit' => number_format($totalDebit, 2),
            'total_credit_manual' => number_format($totalCreditManual, 2),
            'total_debit_manual' => number_format($totalDebitManual, 2),
            'total_played' => number_format($totalPlayed, 2),
            'total_profit' => number_format($totalProfit, 2),
            'percentage_new_registers' => number_format($percentageNewRegisters, 2) . '%',
            'percentage_unique_depositors' => number_format($percentageUniqueDepositors, 2) . '%',
            'percentage_ftd' => number_format($percentageFtd, 2) . '%',
            'percentage_bonus' => number_format($percentageBonus, 2) . '%',
            'percentage_credit_rejected' => number_format(substr($percentageCreditRejected, 0, 6), 2) . '%',
            'percentage_credit_approved' => number_format(substr($percentageCreditApproved, 0, 6), 2) . '%',
            'percentage_debit_rejected' => number_format(substr($percentageDebitRejected, 0, 6), 2) . '%',
            'percentage_debit_approved' => number_format(substr($percentageDebitApproved, 0, 6), 2) . '%',
            'percentage_credit_manual' => number_format(substr($percentageCreditManual, 0, 6), 2) . '%',
            'percentage_debit_manual' => number_format(substr($percentageDebitManual, 0, 6), 2) . '%',
            'percentage_played' => number_format(substr($percentagePlayed, 0, 6), 2) . '%',
            'percentage_profit' => number_format(substr($percentageProfit, 0, 6), 2) . '%',
            'average_new_registered' => number_format($avgNewRegistred, 2) . '%',
            'average_unique_deposit' => number_format($avgUniqueDeposit, 2) . '%',
            'average_ftd' => number_format($avgFtd, 2) . '%',
            'average_bonus' => number_format($avgBonus, 2) . '%',
            'average_credit_approved' => number_format($avgCreditApproved, 2) . '%',
            'average_credit_rejected' => number_format($avgCreditRejected, 2) . '%',
            'average_debit_approved' => number_format($avgDebitApproved, 2) . '%',
            'average_debit_rejected' => number_format($avgDebitRejected, 2) . '%',
            'average_credit' => number_format($avgCredit, 2) . '%',
            'average_debit' => number_format($avgDebit, 2) . '%',
            'average_profit' => number_format($avgProfit, 2) . '%',
        ];
    }

    /**
     * Format deposit withdrawal by user
     *
     * @param array $transactions Transaction data
     * @param string $currency Currency Iso
     * @return array
     */
    public function formatDepositWithdrawalByUser($transactionsTotals, $currency)
    {
        $totalsData = [];
        $auxData = [];
        foreach ($transactionsTotals['deposits'] as $depositsKey => $deposits) {
            if (!in_array($deposits->id, $auxData)) {
                $auxData[] = $deposits->id;
                $totalObject = new \stdClass();
                $totalObject->id = $deposits->id;
                $totalObject->login = !is_null($deposits->last_login) ? Carbon::createFromFormat('Y-m-d H:i:s', $deposits->last_login)->format('d-m-Y H:i:s') : '';
                $totalObject->username = $deposits->username;
                $totalObject->deposits = is_null($deposits->amount) ? 0 : $deposits->amount;
                $totalObject->withdrawals = 0;
                $totalObject->percentage = 0;
                $totalObject->balance = 0;
                $totalObject->user = sprintf(
                    '<a href="%s" class="btn u-btn-3d u-btn-primary btn-sm" target="_blank">%s</a>',
                    route('users.details', [$deposits->id]),
                    $deposits->id
                );
                $totalsData[] = $totalObject;
            }

            foreach ($totalsData as $totalData) {
                foreach ($transactionsTotals['withdrawals'] as $withdrawalsKey => $withdrawals) {
                    if ($totalData->id == $withdrawals->id) {
                        $totalData->withdrawals += $withdrawals->amount;
                        unset($transactionsTotals['withdrawals'][$withdrawalsKey]);
                    }
                }
            }
            unset($transactionsTotals['deposits'][$depositsKey]);
        }

        //$balances =  Wallet::getUsersBalancesByIds($users, $currency);
        foreach ($totalsData as $total) {
            $rtp = ($total->deposits == 0) ? 0 : ($total->withdrawals / $total->deposits) * 100;
            $total->percentage = number_format($rtp, 2) . ' %';;
            $total->deposits = number_format($total->deposits, 2);
            $total->withdrawals = number_format($total->withdrawals, 2);
        }
        return $totalsData;
    }

    /**
     * Format transactions
     *
     * @param $transactions
     */
    public function formatTransactions($transactions)
    {
        foreach ($transactions as $transaction) {
            $timezone = session('timezone');
            $transaction->id = $transaction->id;
            $transaction->date = $transaction->created_at->setTimezone($timezone)->format('d-m-Y H:i:s');
            $transaction->amount = number_format($transaction->amount, 2);
            $transaction->modified_amount = $transaction->transaction_type_id == TransactionTypes::$debit ? "-{$transaction->amount}" : "+{$transaction->amount}";
            $transaction->debit = $transaction->transaction_type_id == TransactionTypes::$debit ? $transaction->amount : '-';
            $transaction->credit = $transaction->transaction_type_id == TransactionTypes::$credit ? $transaction->amount : '-';
            $transaction->provider = Providers::getName($transaction->provider_id);
            if (isset($transaction->data->betpay_transaction)) {
                $description = Providers::getDescription($transaction->provider_id, $transaction->transaction_type_id, $action = null, $transaction->data);
                $betPay = _i('BetPay ID');
                if (isset($transaction->data->reference)) {
                    $reference = _i('Reference');
                    $transaction->description = "$description - $betPay: {$transaction->data->betpay_transaction} - $reference: {$transaction->data->reference}";
                } else {
                    $transaction->description = "$description - $betPay: {$transaction->data->betpay_transaction}";
                }
            } else {
                $transaction->description = Providers::getDescription($transaction->provider_id, $transaction->transaction_type_id, $action = null, $transaction->data);
            }
            $details = json_decode($transaction->details);
            if (!is_null($details) && isset($details->reason)) {
                $reason = _i('Reason');
                $transaction->description .= " $reason: {$details->reason}";
            }

//            if (Configurations::getWhitelabel() == 45 && auth()->user()->username == 'wolf') {
            if (!is_null($details) && isset($details->payment_code)) {
                $paymentCode = _i('Reference');
                $transaction->description .= " - $paymentCode: {$details->payment_code}";
            }

            if (!is_null($details) && isset($details->card_number) && isset($details->auth_number) && isset($details->card_brand)) {
                $authNumber = _i('Reference');
                $cardNumber = _i('Card number');
                $cardBrand = _i('Card brand');
                $transaction->description .= " - $authNumber: {$details->auth_number}";
                $transaction->description .= " - $cardNumber: {$details->card_number}";
                $transaction->description .= " - $cardBrand: {$details->card_brand}";
            }
//            }

            switch ($transaction->transaction_status_id) {
                case TransactionStatus::$pending:
                {
                    $statusText = _i('Pending');
                    break;
                }
                case TransactionStatus::$approved:
                {
                    $statusText = _i('Approved');
                    break;
                }
                case TransactionStatus::$processing:
                {
                    $statusText = _i('Processing');
                    break;
                }
                case TransactionStatus::$rejected:
                {
                    $statusText = _i('Rejected');
                    break;
                }
                case TransactionStatus::$rejected_by_bank:
                {
                    $statusText = _i('Rejected by bank');
                    break;
                }
                case TransactionStatus::$expired:
                {
                    $statusText = _i('Expired');
                    break;
                }
            }
            $transaction->status = $statusText;
        }
    }

    /**
     * Collection Example Sql and Datatable
     * Format transactions timeline
     *
     * @param string $timezone Times Zone Format Date
     * @param array $transactions Array Transactions
     * @param $request
     */
    public function formatTransactionTimeline($transactions,$timezone,$request,$currency)
    {
        $total = 0;
        $data = array();
        if(!empty($transactions)){
            $total = $transactions[0]->total_items;
            foreach ($transactions as $transaction)
            {
                $dataTmp = json_decode($transaction->data);
                $newData['date'] = Carbon::create($transaction->created_at)->setTimezone($timezone)->format('d-m-Y H:i:s');

                $balanceOld = number_format(isset($dataTmp->second_balance)? round($dataTmp->second_balance,2):0,2);
                $name = _('from').' <strong>'.$dataTmp->from .' </strong>'._('to').' '.$dataTmp->to;
                if($transaction->transaction_type_id == TransactionTypes::$debit) {
                    $name = _('from').' '.$dataTmp->from .' <br>'._('to').' <strong>'.$dataTmp->to .' </strong> ';
                }
                // $name = _('from').' <strong>'.$dataTmp->from .' </strong> '._i('Current balance').': '.$balanceOld.''.$currency.' <br> '._('to').' '.$dataTmp->to;
                // if($transaction->transaction_type_id == TransactionTypes::$debit){
                //     $name = _('from').' '.$dataTmp->from .' <br>'._('to').' <strong>'.$dataTmp->to .' </strong> '._i('Current balance').': '.$balanceOld.''.$currency.'';
                // }

                $newData['id'] = $transaction->id;
                $newData['names'] =  $name;
                $newData['from'] = $dataTmp->from;
                $newData['to'] = $dataTmp->to;
                $newData['data'] = $dataTmp;
                $newData['amount'] = $transaction->amount;
                $newData['debit'] = $transaction->transaction_type_id == TransactionTypes::$debit ? number_format($transaction->amount, 2) : '-';
                $newData['credit'] = $transaction->transaction_type_id == TransactionTypes::$credit ? number_format($transaction->amount, 2) : '-';
                $newData['debit_'] = $transaction->transaction_type_id == TransactionTypes::$debit ? $transaction->amount : 0;
                $newData['credit_'] = $transaction->transaction_type_id == TransactionTypes::$credit ? $transaction->amount : 0;
                $newData['transaction_type_id'] = $transaction->transaction_type_id;
                $newData['balance'] = '0.00';
                $newData['balanceFrom'] = '0.00';

                if($transaction->transaction_type_id == TransactionTypes::$debit) {
                    if (isset($dataTmp->balance)) {
                        $newData['balance'] = number_format($dataTmp->balance, 2);
                    }
                    if(isset($dataTmp->second_balance)) {
                        $newData['balanceFrom'] = number_format((float) $dataTmp->second_balance + (float) $newData['debit'], 2);
                    }
                }
                if($transaction->transaction_type_id == TransactionTypes::$credit) {
                    if (isset($dataTmp->balance)) {
                        $newData['balance'] =  number_format((float) $dataTmp->second_balance - (float) $newData['credit_'], 2);
                    }
                    if(isset($dataTmp->second_balance)) {
                        $newData['balanceFrom'] =  number_format($dataTmp->balance, 2);
                    }
                }

                $data[] = $newData;
            }
        }
        if(in_array(Roles::$admin_Beet_sweet, session('roles'))){
            $debitTotal = array_sum(array_map(function($var) {
                return $var['debit_'];
            }, $data));
            $creditTotal = array_sum(array_map(function($var) {
                return $var['credit_'];
            }, $data));

            $data[] = [
                'id'=>'',
                'date'=>'',
                'names'=>'',
                'from'=>'',
                'to'=>'',
                'data'=>'',
                'debit'=>'<strong>'.number_format($debitTotal,2).'</strong>', // ingreso por cargas
                'credit'=>'<strong>'.number_format($creditTotal,2).'</strong>', // egreso por descargas
                'debit_'=>0,
                'credit_'=>0,
                'transaction_type_id'=>'',
                'balance'=>'',
                'balanceFrom' => ''
//                'balance'=>'<strong>'.number_format(($debitTotal-$creditTotal),2).'</strong>',
//                'balanceFrom' => '<strong>'.number_format(($creditTotal-$debitTotal),2).'</strong>'
            ];

        }

        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($total),
            "recordsFiltered" => intval($total),
            "data"            => $data
        );
        // dd($json_data);
        return $json_data;
    }

    /**
     * @param $sales
     * @param string $currency Currency Iso
     * @param string $startDate Start date to filter
     * @param string $endDate End date to filter
     * @return array[]
     */
    public function formatWhitelabelsSales($sales, $currency, $startDate, $endDate)
    {
        $closuresUsersTotalsRepo = new ClosuresUsersTotalsRepo();
        $usersRepo = new UsersRepo();
        $transactionsRepo = new TransactionsRepo();
        $totalsData = [];
        $auxWhitelabel = [];

        foreach ($sales as $saleKey => $sale) {
            if (!in_array($sale->whitelabel_id, $auxWhitelabel)) {
                $auxWhitelabel[] = $sale->whitelabel_id;
                $closure = $closuresUsersTotalsRepo->getUsersTotalsByWhitelabelAndDates($startDate, $endDate, $sale->whitelabel_id, $currency);
                $totalObject = new \stdClass();
                $totalObject->whitelabel = $sale->whitelabel_id;
                $totalObject->description = $sale->description;
                $totalObject->new_registers = $usersRepo->getRegisteredUsers($sale->whitelabel_id, $startDate, $endDate)->count();
                $totalObject->unique_depositors = $transactionsRepo->getUniqueDepositors($sale->whitelabel_id, $currency, $startDate, $endDate)->count();
                $totalObject->bonus = $sale->bonus;
                $totalObject->credit_rejected = $sale->credit_rejected;
                $totalObject->credit_approved = $sale->credit_approved;
                $totalObject->debit_rejected = $sale->debit_rejected;
                $totalObject->debit_approved = $sale->debit_approved;
                $totalObject->credit_manual = $sale->credit_manual;
                $totalObject->debit_manual = $sale->debit_manual;
                $totalObject->played = $closure->played;
                $totalObject->profit = $closure->profit;
                $totalsData[] = $totalObject;
            }

            foreach ($totalsData as $totalData) {
                if ($totalData->whitelabel == $sale->whitelabel_id) {
                    $totalData->bonus += $sale->bonus;
                    $totalData->credit_rejected += $sale->credit_rejected;
                    $totalData->credit_approved += $sale->credit_approved;
                    $totalData->debit_rejected += $sale->debit_rejected;
                    $totalData->debit_approved += $sale->debit_approved;
                    $totalData->credit_manual += $sale->credit_manual;
                    $totalData->debit_manual += $sale->debit_manual;
                    unset($sales[$saleKey]);
                }
            }
        }

        foreach ($totalsData as $totalData) {
            $totalData->bonus = number_format($totalData->bonus, 2);
            $totalData->credit_rejected = number_format($totalData->credit_rejected, 2);
            $totalData->credit_approved = number_format($totalData->credit_approved, 2);
            $totalData->debit_rejected = number_format($totalData->debit_rejected, 2);
            $totalData->debit_approved = number_format($totalData->debit_approved, 2);
            $totalData->credit_manual = number_format($totalData->credit_manual, 2);
            $totalData->debit_manual = number_format($totalData->debit_manual, 2);
            $totalData->played = number_format($totalData->played, 2);
            $totalData->profit = number_format($totalData->profit, 2);
        }

        return [
            'sales' => $totalsData,
        ];
    }
}

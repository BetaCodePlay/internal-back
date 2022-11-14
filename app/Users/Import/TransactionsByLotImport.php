<?php

namespace App\Users\Import;

use App\Audits\Enums\AuditTypes;
use App\Whitelabels\Repositories\OperationalBalancesRepo;
use App\Whitelabels\Repositories\OperationalBalancesTransactionsRepo;
use App\Core\Repositories\TransactionsRepo;
use App\Users\Entities\User;
use Dotworkers\Audits\Audits;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Configurations\Enums\Codes;
use Dotworkers\Configurations\Enums\Providers;
use Dotworkers\Configurations\Enums\Status;
use Dotworkers\Configurations\Enums\TransactionStatus;
use Dotworkers\Configurations\Enums\TransactionTypes;
use Dotworkers\Configurations\Utils;
use Dotworkers\Wallet\Wallet;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

/**
 * Class TransactionByLot
 *
 * This class allows to manage users requests
 *
 * @package App\Users\Import
 * @author  Carlos Hurtado
 */
class TransactionsByLotImport implements ToCollection, WithHeadingRow, WithValidation, SkipsOnFailure, SkipsOnError
{
    use Importable, SkipsFailures, SkipsErrors;

    public $ip;

    public $description;

    public $data = [];

    /**
     * Create a new import instance.
     *
     * @return void
     */
    public function __construct($ip, $description)
    {
        $this->ip = $ip;
        $this->description = $description;
    }

    /**
     * Collection
     *
     * @param array $rows Transaction data
     */
    public function collection(Collection $rows)
    {
        $transactionsRepo = new TransactionsRepo();
        $operationalBalanceRepo = new OperationalBalancesRepo();
        $operationalBalanceTransactionsRepo = new OperationalBalancesTransactionsRepo();
        $whitelabel = Configurations::getWhitelabel();

        foreach ($rows as $row) {
            $user = User::select('users.id')
                ->join('user_currencies', 'user_currencies.user_id', '=', 'users.id')
                ->where('username', $row['username'])
                ->where('currency_iso', $row['currency'])
                ->where('whitelabel_id', $whitelabel)
                ->first();

            if (!is_null($user)) {
                $walletData = Wallet::getByClient($user->id, $row['currency']);
                $operator = auth()->user()->username;
                $whitelabel = Configurations::getWhitelabel();
                $operationalBalanceData = $operationalBalanceRepo->find($whitelabel, $row['currency']);
                $provider = Providers::$dotworkers;

                $additionalData = [
                    'description' => $this->description,
                    'operator' => $operator
                ];

                if (!is_null($operationalBalanceData) && $row['amount'] > $operationalBalanceData->balance) {
                    $operationalBalance = number_format($operationalBalanceData->balance, 2);
                    $data = [
                        'title' => _i('Transaction not allowed'),
                        'message' => _i('The amount is higher than the operational balance. Available: %s %s', [$row['currency'], $operationalBalance]),
                        'close' => _i('Close')
                    ];
                    return Utils::errorResponse(Codes::$forbidden, $data);
                } else {
                    $transaction = Wallet::creditManualTransactions($row['amount'], $provider, $additionalData, $walletData->data->wallet->id);
                }

                if ($transaction->status == Status::$ok) {
                    $transactionData = [
                        'user_id' => $user->id,
                        'amount' => $row['amount'],
                        'currency_iso' => $row['currency'],
                        'transaction_type_id' => TransactionTypes::$credit,
                        'transaction_status_id' => TransactionStatus::$approved,
                        'provider_id' => $provider,
                        'data' => $additionalData,
                        'whitelabel_id' => Configurations::getWhitelabel()
                    ];
                    $additionalData['wallet_transaction'] = $transaction->data->transaction->id;
                    $detailsData = [
                        'data' => json_encode($additionalData)
                    ];

                    $transactionsRepo->store($transactionData, TransactionStatus::$approved, $detailsData);

                    $operationalBalanceTransaction = [
                        'amount' => $row['amount'],
                        'user_id' => $user->id,
                        'operator' => $operator,
                        'provider_id' => $provider,
                        'whitelabel_id' => $whitelabel,
                        'currency_iso' => $row['currency'],
                        'transaction_type_id' => TransactionTypes::$credit,
                    ];
                    $operationalBalanceTransactionsRepo->store($operationalBalanceTransaction);
                    $operationalBalanceRepo->decrement($whitelabel, $row['currency'], $row['amount']);

                    $auditData = [
                        'ip' => $this->ip,
                        'user_id' => auth()->user()->id,
                        'username' => auth()->user()->username,
                        'transaction' => [
                            'amount' => $row['amount'],
                            'currency' => $row['currency'],
                            'transaction_type_id' => TransactionTypes::$credit,
                            'description' => $this->description
                        ]
                    ];
                    Audits::store($user->id, AuditTypes::$manual_transactions, Configurations::getWhitelabel(), $auditData);
                    $totalObject = new \stdClass();
                    $totalObject->username = $row['username'];
                    $totalObject->amount = $row['amount'];
                    $totalObject->currency = $row['currency'];
                    $totalObject->description = _i('Completed transaction');
                    $this->data[] = $totalObject;
                } else {
                    $totalObject = new \stdClass();
                    $totalObject->username = $row['username'];
                    $totalObject->amount = $row['amount'];
                    $totalObject->currency = $row['currency'];
                    $totalObject->description = _i('Something went wrong');
                    $this->data[] = $totalObject;
                }
            } else {
                $totalObject = new \stdClass();
                $totalObject->username = $row['username'];
                $totalObject->amount = $row['amount'];
                $totalObject->currency = $row['currency'];
                $totalObject->description = _i('User not found');
                $this->data[] = $totalObject;
            }
        }

        return $this->data;
    }

    /**
     * Rules
     *
     */
    public function rules(): array
    {
        return [
            'username' => 'required',
            'amount' => 'required|numeric',
            'currency' => 'required|size:3'
        ];
    }
}

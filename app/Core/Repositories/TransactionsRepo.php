<?php

namespace App\Core\Repositories;

use App\Core\Entities\Transaction;
use App\Reports\Repositories\ReportAgentRepo;
use App\Users\Enums\TypeUser;
use Carbon\Carbon;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Configurations\Enums\Providers;
use Dotworkers\Configurations\Enums\ProviderTypes;
use Dotworkers\Configurations\Enums\TransactionStatus;
use Dotworkers\Configurations\Enums\TransactionTypes;
use Dotworkers\Configurations\Utils;
use Dotworkers\Security\Enums\Permissions;
use Dotworkers\Wallet\Wallet;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Yajra\DataTables\Utilities\Helper;


/**
 *
 */
class TransactionsRepo
{

    /**
     * @param ReportAgentRepo|null $reportAgentRepo
     */
    public function __construct(private ?ReportAgentRepo $reportAgentRepo = null) { }

    /**
     * @param $user
     * @param $currency
     * @param $startDate
     * @return mixed
     */
    public function approvedByUserAndDate($user, $currency, $startDate)
    : mixed {
        return Transaction::join('providers', 'transactions.provider_id', '=', 'providers.id')
            ->where('transactions.user_id', $user)
            ->where('transactions.currency_iso', $currency)
            ->where('transaction_status_id', TransactionStatus::$approved)
            ->where('transaction_type_id', TransactionTypes::$credit)
            ->whereIn('providers.provider_type_id', [ProviderTypes::$dotworkers, ProviderTypes::$payment])
            ->where('transactions.created_at', '>=', $startDate)
            ->orderBy('transactions.id', 'ASC')
            ->limit(1)
            ->first();
    }

    /**
     * @param $whitelabel
     * @param $transactionType
     * @param $currency
     * @param $providerTypes
     * @param $startDate
     * @param $endDate
     * @param $status
     * @return int
     */
    public function countByProviderTypes(
        $whitelabel,
        $transactionType,
        $currency,
        $providerTypes,
        $startDate,
        $endDate,
        $status
    )
    : int {
        $transactions = Transaction::on('replica')
            ->join('providers', 'transactions.provider_id', '=', 'providers.id')
            ->where('transactions.whitelabel_id', $whitelabel)
            ->where('transactions.currency_iso', $currency)
            ->whereIn('providers.provider_type_id', $providerTypes)
            ->where('transaction_type_id', $transactionType)
            ->where('transaction_status_id', $status);

        if (! is_null($startDate) && ! is_null($endDate)) {
            $transactions->whereBetween('transactions.created_at', [$startDate, $endDate]);
        }

        return $transactions->count();
    }

    /**
     * @param $betPayTransaction
     * @return mixed
     */
    public function findByBetPayTransaction($betPayTransaction)
    : mixed {
        return Transaction::whereRaw("data::json->>'betpay_transaction' = ?", $betPayTransaction)
            ->where('whitelabel_id', Configurations::getWhitelabel())
            ->where('currency_iso', session('currency'))
            ->first();
    }

    /**
     * @param int $user
     * @param string $currency
     * @return mixed
     */
    public function findFirstDeposit(int $user, string $currency)
    : mixed {
        return Transaction::join('providers', 'transactions.provider_id', '=', 'providers.id')
            ->where('user_id', $user)
            ->where('currency_iso', $currency)
            ->where('transaction_status_id', TransactionStatus::$approved)
            ->where('transaction_type_id', TransactionTypes::$credit)
            ->whereIn('providers.provider_type_id', [ProviderTypes::$dotworkers, ProviderTypes::$payment])
            ->orderBy('transactions.id', 'DESC')
            ->first();
    }

    /**
     * @param $user
     * @param $providers
     * @param $currency
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public function getAgentsTransactions($user, $providers, $currency, $startDate, $endDate)
    : array {
        $debit = Transaction::select('users.id', 'users.username', DB::raw('sum(amount) AS total'))
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->where('transactions.user_id', $user)
            ->where('transactions.currency_iso', $currency)
            ->whereIn('transactions.provider_id', $providers)
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->where('transaction_type_id', TransactionTypes::$debit)
            ->groupBy('users.id', 'users.username')
            ->get();

        $credit = Transaction::select('users.id', 'users.username', DB::raw('sum(amount) AS total'))
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->where('transactions.user_id', $user)
            ->where('transactions.currency_iso', $currency)
            ->whereIn('transactions.provider_id', $providers)
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->where('transaction_type_id', TransactionTypes::$credit)
            ->groupBy('users.id', 'users.username')
            ->get();

        return [
            'debit'  => $debit,
            'credit' => $credit
        ];
    }

    /**
     * @param $user
     * @param $currency
     * @return mixed
     */
    public function getBonusTotalByUser($user, $currency)
    : mixed {
        return Transaction::join('providers', 'transactions.provider_id', '=', 'providers.id')
            ->where('transactions.user_id', $user)
            ->where('transactions.currency_iso', $currency)
            ->where('transaction_type_id', TransactionTypes::$credit)
            ->where('transaction_status_id', TransactionStatus::$approved)
            ->whereIn('providers.provider_type_id', [ProviderTypes::$bonus_transactions])
            ->sum('amount');
    }

    /**
     * @param $users
     * @param $currency
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public function getBonusTotalByUsers($users, $currency, $startDate, $endDate)
    : array {
        return DB::select(
            "SELECT user_id, sum(amount) AS bonus
            FROM transactions
            JOIN providers ON transactions.provider_id = providers.id
            WHERE user_id IN (" . implode(',', $users) . ")
            AND transactions.currency_iso = ?
            AND transaction_type_id = ?
            AND transaction_status_id = ?
            AND providers.provider_type_id IN (?)
            AND transactions.created_at BETWEEN ? AND ?
            GROUP BY user_id",
            [
                $currency,
                TransactionTypes::$credit,
                TransactionStatus::$approved,
                ProviderTypes::$bonus_transactions,
                $startDate,
                $endDate
            ]
        );
    }


    /**
     * @param $provider
     * @param $transactionType
     * @param $currency
     * @param $status
     * @param $startDate
     * @param $endDate
     * @return mixed
     */
    public function getByStatusAndDates($provider, $transactionType, $currency, $status, $startDate, $endDate)
    : mixed {
        return Transaction::select(
            'transactions.id',
            'amount',
            'transactions.currency_iso',
            'transactions.transaction_status_id',
            'transactions.created_at',
            'transactions.updated_at',
            'users.id',
            'users.username',
            'transactions.data',
            'transactions.reference',
            'transaction_details.data AS details_data'
        )
            ->leftJoin('users', 'transactions.user_id', '=', 'users.id')
            ->join('transaction_details', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->where('transaction_type_id', $transactionType)
            ->where('transactions.currency_iso', $currency)
            ->where('transactions.transaction_status_id', $status)
            ->where('transaction_details.transaction_status_id', $status)
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->where('transactions.provider_id', $provider)
            ->orderBy('created_at', 'DESC')
            ->get();
    }

    /**
     * @param $whitelabel
     * @param $currency
     * @param $transactionType
     * @param $providerTypes
     * @param $startDate
     * @param $endDate
     * @param $status
     * @return mixed
     */
    public function getByTransactionTypeAndProviderTypes(
        $whitelabel,
        $currency,
        $transactionType,
        $providerTypes,
        $startDate,
        $endDate,
        $status
    )
    : mixed {
        return Transaction::select(
            'transactions.*',
            'users.username',
            'users.id as user',
            'transaction_details.data as details'
        )
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->rightJoin('providers', 'transactions.provider_id', '=', 'providers.id')
            ->join('transaction_details', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->where('users.whitelabel_id', $whitelabel)
            ->where('transactions.currency_iso', $currency)
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->where('transactions.transaction_type_id', $transactionType)
            ->where('transactions.transaction_status_id', $status)
            ->where('transaction_details.transaction_status_id', $status)
            ->whereIn('providers.provider_type_id', $providerTypes)
            ->get();
    }

    /**
     * @param $whitelabel
     * @param $currency
     * @param $transactionType
     * @param $providers
     * @param $startDate
     * @param $endDate
     * @param $status
     * @return mixed
     */
    public function getByTransactionTypeAndProviders(
        $whitelabel,
        $currency,
        $transactionType,
        $providers,
        $startDate,
        $endDate,
        $status
    )
    : mixed {
        return Transaction::select('transactions.*', 'users.username', 'users.id as user')
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->join('transaction_details', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->where('transaction_details.transaction_status_id', $status)
            ->where('users.whitelabel_id', $whitelabel)
            ->where('transactions.currency_iso', $currency)
            ->whereIn('transactions.provider_id', $providers)
            ->whereBetween('transactions.updated_at', [$startDate, $endDate])
            ->where('transactions.transaction_type_id', $transactionType)
            ->where('transactions.transaction_status_id', $status)
            ->orderBy('transactions.updated_at', 'DESC')
            ->get();
    }

    /**
     * @param $user
     * @param $currency
     * @param $provideTypes
     * @param $limit
     * @param $offset
     * @return mixed
     */
    public function getByUserAndProviderTypes($user, $currency, $provideTypes, $limit = 2000, $offset = 0)
    : mixed {
        return Transaction::select(
            'transactions.id',
            'transactions.amount',
            'transactions.transaction_type_id',
            'transactions.created_at',
            'transactions.provider_id',
            'transactions.data',
            'transactions.transaction_status_id',
            'transaction_details.data as details'
        )
            ->join('providers', 'transactions.provider_id', '=', 'providers.id')
            ->join('transaction_details', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->where('transactions.user_id', $user)
            ->where('transactions.currency_iso', $currency)
            ->whereIn('providers.provider_type_id', $provideTypes)
            ->whereRaw("transactions.transaction_status_id = transaction_details.transaction_status_id")
            ->orderBy('transactions.id', 'DESC')
            ->limit($limit)
            ->offset($offset)
            ->get();
    }

    /**
     * @param $user
     * @param $providers
     * @param $currency
     * @param $limit
     * @param $offset
     * @return mixed
     */
    public function getByUserAndProviders($user, $providers, $currency, $limit = 2000, $offset = 0)
    : mixed {
        return Transaction::select(
            'transactions.id',
            'transactions.amount',
            'transactions.transaction_type_id',
            'transactions.created_at',
            'transactions.provider_id',
            'transactions.data',
            'transactions.transaction_status_id'
        )
            ->where('transactions.user_id', $user)
            ->where('transactions.currency_iso', $currency)
            ->whereIn('transactions.provider_id', $providers)
            ->orderBy('transactions.id', 'DESC')
            ->limit($limit)
            ->offset($offset)
            ->get();
    }

    /**
     * @param Request $request
     * @param string $currency
     * @return array
     */
    public function getAgentTransactionsForDataTable(Request $request, string $currency)
    : array {
        $draw        = $request->input('draw', 1);
        $start       = $request->input('start', 0);
        $length      = $request->input('length', 10);
        $searchValue = $request->input('search.value');
        $userId      = getUserIdByUsernameOrCurrent($request);
        $providers   = [Providers::$agents, Providers::$agents_users];

        $startDate       = Utils::startOfDayUtc(
            $request->has('startDate') ? $request->get('startDate') : date('Y-m-d')
        );
        $endDate         = Utils::endOfDayUtc($request->has('endDate') ? $request->get('endDate') : date('Y-m-d'));
        $typeUser        = $request->has('typeUser') ? $request->get('typeUser') : 'all';
        $typeTransaction = $request->has('typeTransaction') ? $request->get('typeTransaction') : 'all';
        $username        = $request->get('search')['value'] ?? null;
        $childrenIds     = $this->reportAgentRepo->getIdsChildrenFromFather(
            $userId,
            $currency,
            Configurations::getWhitelabel()
        );

        $orderCol = [
            'column' => 'date',
            'order'  => 'asc',
        ];

        if ($request->has('order') && ! empty($request->get('order'))) {
            $orderCol = [
                'column' => $request->get('columns')[$request->get('order')[0]['column']]['data'],
                'order'  => $request->get('order')[0]['dir']
            ];
        }

        $transactionsQuery = Transaction::select([
            'users.username',
            'users.type_user as typeUser',
            'users.id as userId',
            'transactions.user_id',
            'transactions.id',
            'transactions.amount',
            'transactions.transaction_type_id',
            'transactions.created_at',
            'transactions.provider_id',
            'transactions.data',
            'transactions.transaction_status_id',
            'transactions.data->balance as balance_final'
        ])
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->whereIn('transactions.user_id', $childrenIds)
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->where('transactions.currency_iso', $currency)
            ->orderBy('transactions.created_at', 'desc');

        if ($typeUser !== 'all') {
            $transactionsQuery->where(function ($query) use ($typeUser) {
                if ($typeUser === 'agent') {
                    $query->where('transactions.provider_id', Providers::$agents);
                } else {
                    $query->where('transactions.provider_id', Providers::$agents_users);
                }
            });
        } else {
            $transactionsQuery->whereIn('transactions.provider_id', $providers);
        }

        if ($typeTransaction !== 'all') {
            if ($typeTransaction === 'credit') {
                $transactionsQuery->where('transactions.transaction_type_id', TransactionTypes::$credit);
            } else {
                $transactionsQuery->where('transactions.transaction_type_id', TransactionTypes::$debit);
            }
        }

        if (! is_null($username)) {
            $transactionsQuery->where('transactions.data->from', 'like', "%$username%")->orWhere(
                'transactions.data->to',
                'like',
                "%$username%"
            );
        }

        if (! is_null($searchValue)) {
            $transactionsQuery->where(function ($query) use ($searchValue) {
                $query->where('transactions.id', 'like', "%$searchValue%")
                    ->orWhere('transactions.amount', 'like', "%$searchValue%")
                    ->orWhere('transactions.transaction_type_id', 'like', "%$searchValue%")
                    ->orWhere('transactions.created_at', 'like', "%$searchValue%")
                    ->orWhere('transactions.provider_id', 'like', "%$searchValue%")
                    ->orWhere('transactions.data', 'like', "%$searchValue%")
                    ->orWhere('transactions.transaction_status_id', 'like', "%$searchValue%");
            });
        }

        if (! empty($orderCol)) {
            if ($orderCol['column'] == 'date') {
                $transactionsQuery->orderBy('transactions.created_at', $orderCol['order']);
            } elseif ($orderCol['column'] == 'data.from') {
                $transactionsQuery->orderBy('transactions.data->from', $orderCol['order']);
            } elseif ($orderCol['column'] == 'data.to') {
                $transactionsQuery->orderBy('transactions.data->to', $orderCol['order']);
            } elseif ($orderCol['column'] == 'debit' || $orderCol['column'] == 'credit') {
                $transactionsQuery->orderBy('transactions.transaction_type_id', $orderCol['order'])->orderBy(
                    'transactions.amount',
                    $orderCol['order']
                );
            } elseif ($orderCol['column'] == 'balance') {
                if ($orderCol['order'] == 'asc') {
                    $transactionsQuery->orderByRaw(
                        "(site.transactions.data::json->>'balance')::numeric ASC"
                    );
                } else {
                    $transactionsQuery->orderByRaw(
                        "(site.transactions.data::json->>'balance')::numeric DESC"
                    );
                }
            } elseif ($orderCol['column'] == 'new_amount') {
                if ($orderCol['order'] == 'asc') {
                    $transactionsQuery->orderByRaw("(site.transactions.amount)::numeric ASC");
                } else {
                    $transactionsQuery->orderByRaw("(site.transactions.amount)::numeric DESC");
                }
            } else {
                $transactionsQuery->orderBy('transactions.id', $orderCol['order']);
            }
        }

        $resultCount      = $transactionsQuery->count();
        $slicedResults    = $transactionsQuery->offset($start)->limit($length)->get();
        $formattedResults = $slicedResults->map(function ($transaction) {
            $formattedDateTime             = Carbon::parse($transaction->created_at)->format('Y-m-d H:i:s');
            $formattedDateTimeWithTimezone = Carbon::parse($formattedDateTime)->setTimezone(
                session('timezone')
            )->toDateTimeString();

            $from     = $transaction->data->from ?? null;
            $to       = $transaction->data->to ?? null;
            $balance  = $transaction->data->balance ?? null;
            // $receiver = $to; Todo: La forma anterior que así estaba.

            $nameAffect = $from === $transaction->username ? $from : $to;

            return [
                $formattedDateTimeWithTimezone,
                $from,
                $nameAffect,
                [formatAmount($transaction->amount), $transaction->transaction_type_id],
                formatAmount((float)$balance)
            ];
        })->toArray();

        return [
            'draw'            => (int)$draw,
            'recordsTotal'    => $resultCount,
            'recordsFiltered' => $resultCount,
            'data'            => $formattedResults,
        ];
    }


    /**
     * @param string $startDate
     * @param string $endDate
     * @param string $currency
     * @param $provider
     * @param $whitelabel
     * @return array
     */
    public function getFinancialDataMakersTotals(
        string $startDate,
        string $endDate,
        string $currency,
        $provider,
        $whitelabel
    )
    : array {
        return DB::select(
            'SELECT * FROM site.get_closure_totals_by_provider_and_maker_global_total(?,?,?,?,?)',
            [$whitelabel, $currency, $startDate, $endDate, $provider]
        );
    }

    /**
     * @param $user
     * @param $providers
     * @param $currency
     * @param $startDate
     * @param $endDate
     * @param $limit
     * @param $offset
     * @param $username
     * @param $typeUser
     * @param $arraySonIds
     * @param $orderCol
     * @param $typeTransaction
     * @return array
     */
    public function getByUserAndProvidersPaginate(
        $user,
        $providers,
        $currency,
        $startDate,
        $endDate,
        $limit = 2000,
        $offset = 0,
        $username = null,
        $typeUser = null,
        $arraySonIds = [],
        $orderCol,
        $typeTransaction = null
    )
    : array {
        $transactions = Transaction::select(
            'users.username',
            'transactions.user_id',
            'transactions.id',
            'transactions.amount',
            'transactions.transaction_type_id',
            'transactions.created_at',
            'transactions.provider_id',
            'transactions.data',
            'transactions.transaction_status_id',
            'transactions.data->balance AS balance_final'
        )
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->whereIn('transactions.user_id', $arraySonIds)
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->where('transactions.currency_iso', $currency)
            ->whereIn('transactions.provider_id', $providers);

        //->orderBy('transactions.id', 'DESC');

        if (is_null($typeUser) || $typeUser === 'all') {
        } elseif ($typeUser === 'agent') {
            $transactions = $transactions->whereNull('data->provider_transaction');
        } else {
            $transactions = $transactions->whereNotNull('data->provider_transaction');
        }

        if (is_null($typeTransaction) || $typeTransaction === 'all') {
        } elseif ($typeTransaction === 'credit') {
            $typeTransaction = 1;
            $transactions    = $transactions->where('transactions.transaction_type_id', $typeTransaction);
        } else {
            $typeTransaction = 2;
            $transactions    = $transactions->where('transactions.transaction_type_id', $typeTransaction);
        }

        if (! is_null($username)) {
            $transactions = $transactions->where('transactions.data->from', 'like', "%$username%")->orWhere(
                'transactions.data->to',
                'like',
                "%$username%"
            );
        }

        if (! empty($orderCol)) {
            if ($orderCol['column'] == 'date') {
                $transactions = $transactions->orderBy('transactions.created_at', $orderCol['order']);
            } elseif ($orderCol['column'] == 'data.from') {
                $transactions = $transactions->orderBy('transactions.data->from', $orderCol['order']);
            } elseif ($orderCol['column'] == 'data.to') {
                $transactions = $transactions->orderBy('transactions.data->to', $orderCol['order']);
            } elseif ($orderCol['column'] == 'debit' || $orderCol['column'] == 'credit') {
                $transactions = $transactions->orderBy('transactions.transaction_type_id', $orderCol['order'])->orderBy(
                    'transactions.amount',
                    $orderCol['order']
                );
            } elseif ($orderCol['column'] == 'balance') {
                if ($orderCol['order'] == 'asc') {
                    $transactions = $transactions->orderByRaw(
                        "(site.transactions.data::json->>'balance')::numeric ASC"
                    );
                } else {
                    $transactions = $transactions->orderByRaw(
                        "(site.transactions.data::json->>'balance')::numeric DESC"
                    );
                }
            } elseif ($orderCol['column'] == 'new_amount') {
                if ($orderCol['order'] == 'asc') {
                    $transactions = $transactions->orderByRaw("(site.transactions.amount)::numeric ASC");
                } else {
                    $transactions = $transactions->orderByRaw("(site.transactions.amount)::numeric DESC");
                }
            } else {
                $transactions = $transactions->orderBy('transactions.id', $orderCol['order']);
            }
        }

        $countTransactions = $transactions->count();
        $transactions      = $transactions->limit($limit)->offset($offset)->get();

        return [$transactions, $countTransactions];
    }

    /**
     * Get user and provider transactions paginated.
     *
     * Retrieve paginated transaction data based on a specified user and provider(s).
     * Transactions can be filtered based on a specific type.
     *
     * @param Request $request
     * @param string|int $agent
     * @return LengthAwarePaginator
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getUserProviderTransactionsPaginated(Request $request, string|int $agent)
    : LengthAwarePaginator {
        $timezone  = $request->input('timezone', session()->get('timezone'));
        $startDate = Utils::startOfDayUtc(
            $request->has('startDate') ? $request->get('startDate') : date('Y-m-d'),
            'Y-m-d',
            'Y-m-d H:i:s',
            $timezone
        );
        $endDate   = Utils::endOfDayUtc(
            $request->has('endDate') ? $request->get('endDate') : date('Y-m-d'),
            'Y-m-d',
            'Y-m-d H:i:s',
            $timezone
        );
        $typeUser  = $request->has('typeUser') ? $request->get('typeUser') : 'all';

        $typeTransaction = 'credit';
        if (Gate::allows('access', Permissions::$users_search)) {
            $typeTransaction = $request->has('typeTransaction') ? $request->get('typeTransaction') : 'all';
        }

        $currency  = session('currency');
        $providers = [Providers::$agents, Providers::$agents_users];

        $arraySonIds  = $this->reportAgentRepo->getIdsChildrenFromFather(
            $agent,
            session('currency'),
            Configurations::getWhitelabel()
        );
        $transactions = Transaction::select(
            'users.username',
            'users.id as userId',
            'users.type_user as typeUser',
            'transactions.user_id',
            'transactions.id',
            'transactions.amount',
            'transactions.transaction_type_id',
            'transactions.created_at',
            'transactions.provider_id',
            'transactions.data',
            'transactions.transaction_status_id',
            'transactions.data->balance AS balance_final'
        )
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->whereIn('transactions.user_id', $arraySonIds)
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->where('transactions.currency_iso', $currency)
            ->whereIn('transactions.provider_id', $providers);

        if ($typeUser === 'agent') {
            $transactions->whereNull('data->provider_transaction');
        } elseif ($typeUser === 'provider') {
            $transactions->whereNotNull('data->provider_transaction');
        }

        if (! empty($request->get('query'))) {
            $query = $request->get('query');
            $transactions->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('data->from', 'LIKE', "%$query%")
                    ->orWhere('data->to', 'LIKE', "%$query%");
            });
        }

        $typeTransactionId = null;

        if ($typeTransaction === 'credit') {
            $typeTransactionId = 1;
        }

        if ($typeTransaction === 'debit') {
            $typeTransactionId = 2;
        }

        if (! is_null($typeTransactionId)) {
            $transactions = $transactions->where('transactions.transaction_type_id', $typeTransactionId);
        }

        return $transactions->orderBy('transactions.created_at', 'desc')
            ->paginate($request->input('per_page', 10));
    }


    /**
     * @param $user
     * @param $providers
     * @param $currency
     * @param $startDate
     * @param $endDate
     * @param $typeUser
     * @return array
     */
    public function getByUserAndProvidersTotales($user, $providers, $currency, $startDate, $endDate, $typeUser = null)
    : array {
        $countTransactions = Transaction::select(
            'transactions.id',
            'transactions.user_id',
            'transactions.data',
            'transactions.amount',
            'transactions.transaction_type_id'
        )
            ->where('transactions.user_id', $user)
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->where('transactions.currency_iso', $currency)
            ->whereIn('transactions.provider_id', $providers)
            ->orderBy('transactions.id', 'DESC');

        if (! is_null($typeUser) && $typeUser == 'user') {
            $countTransactions = $countTransactions->whereNotNull('data->provider_transaction');
        }
        if (! is_null($typeUser) && $typeUser == 'agent') {
            $countTransactions = $countTransactions->whereNull('data->provider_transaction');
        }
        $countTransactions = $countTransactions->get();

        $totalDebit  = 0;
        $totalCredit = 0;
        foreach ($countTransactions as $item => $value) {
            if ($value->transaction_type_id == TransactionTypes::$debit) {
                $totalDebit = $totalDebit + $value->amount;
                if ($value->user_id === Auth::user()->id) {
                    $totalDebit  = $totalDebit - $value->amount;
                    $totalCredit = $totalCredit + $value->amount;
                }

                if ($value->data->from != Auth::user()->username) {
                    $totalDebit  = $totalDebit + $value->amount;
                    $totalCredit = $totalCredit - $value->amount;
                }
            }
            if ($value->transaction_type_id == TransactionTypes::$credit) {
                $totalCredit = $totalCredit + $value->amount;
                if ($value->user_id === Auth::user()->id) {
                    $totalCredit = $totalCredit - $value->amount;
                    $totalDebit  = $totalDebit + $value->amount;
                }
                if ($value->data->from != Auth::user()->username) {
                    $totalCredit = $totalCredit + $value->amount;
                    $totalDebit  = $totalDebit - $value->amount;
                }
            }
        }

        return [$totalCredit, $totalDebit];
    }

    /**
     * @param $username
     * @param $agents
     * @param $whitelabel
     * @param $currency
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public function getCashFlowTransactions($username, $agents, $whitelabel, $currency, $startDate, $endDate)
    : array {
        $deposits = Transaction::select('users.id', 'users.username', DB::raw('sum(amount) AS total'))
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->where('transactions.provider_id', Providers::$agents)
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->where('users.whitelabel_id', $whitelabel)
            ->where('transactions.currency_iso', $currency)
            ->where('transaction_type_id', TransactionTypes::$credit)
            ->where('transaction_status_id', TransactionStatus::$approved)
            ->whereIn('transactions.user_id', $agents)
            ->where('transactions.data->from', $username)
            ->groupBy('users.id', 'users.username')
            ->get();

        $withdrawals = Transaction::select('users.id', 'users.username', DB::raw('sum(amount) AS total'))
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->where('transactions.provider_id', Providers::$agents)
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->where('users.whitelabel_id', $whitelabel)
            ->where('transactions.currency_iso', $currency)
            ->where('transaction_type_id', TransactionTypes::$debit)
            ->where('transaction_status_id', TransactionStatus::$approved)
            ->whereIn('transactions.user_id', $agents)
            ->where('transactions.data->to', $username)
            ->groupBy('users.id', 'users.username')
            ->get();

        return [
            'deposits'    => $deposits,
            'withdrawals' => $withdrawals
        ];
    }

    /**
     * @param $username
     * @param $agents
     * @param $whitelabel
     * @param $currency
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public function getCashFlowTransactionsNew($username, $agents, $whitelabel, $currency, $startDate, $endDate)
    : array {
        $providersArray = [
            Providers::$agents,
            Providers::$agents_users,
            Providers::$dotworkers,
            Providers::$manual_adjustments
        ];
        $result         = DB::SELECT(
            "
                             SELECT u.id,
                               u.username,
                               SUM(CASE WHEN t.transaction_type_id = 1 THEN t.amount ELSE 0 END) AS debit,
                               SUM(CASE WHEN t.transaction_type_id = 2 THEN t.amount ELSE 0 END) AS credit
                                FROM site.transactions as t
                                INNER JOIN site.users as u ON t.user_id = u.id
                                WHERE t.provider_id IN (" . implode(',', $providersArray) . ")
                                AND t.created_at BETWEEN ? AND ?
                                AND u.whitelabel_id = ?
                                AND t.currency_iso = ?
                                AND t.transaction_status_id = ?
                                AND t.user_id IN (" . implode(',', $agents) . ")

                                AND ((t.data->>'from' = ? AND t.transaction_type_id = 1) OR (t.data->>'to' = ? AND t.transaction_type_id = 2))
                                GROUP BY u.id, u.username",
            [$startDate, $endDate, $whitelabel, $currency, TransactionStatus::$approved, $username, $username]
        );

        $financialDataExample = [];
        foreach ($result as $item => $value) {
            $debit                                 = [
                'id'       => $value->id,
                'username' => $value->username,
                'total'    => $value->debit,
            ];
            $credit                                = [
                'id'       => $value->id,
                'username' => $value->username,
                'total'    => $value->credit,
            ];
            $financialDataExample['deposits'][]    = json_decode(json_encode($debit));
            $financialDataExample['withdrawals'][] = json_decode(json_encode($credit));
        }

        return $financialDataExample;
    }

    /**
     * @param $currency
     * @param $startDate
     * @param $endDate
     * @param $transactionType
     * @param $whitelabel
     * @return mixed
     */
    public function getDeposistsWithdrawalsProvider($currency, $startDate, $endDate, $transactionType, $whitelabel)
    : mixed {
        return Transaction::select('transactions.*', 'users.username', 'users.id as user')
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->where('users.whitelabel_id', $whitelabel)
            ->where('transactions.currency_iso', $currency)
            ->where('transactions.provider_id', Providers::$agents_users)
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->where('transactions.transaction_type_id', $transactionType)
            ->where('transaction_status_id', TransactionStatus::$approved)
            ->get();
    }

    /**
     * @param $whitelabel
     * @param $currency
     * @param $level
     * @param $providerTypes
     * @return array
     */
    public function getDepositWithdrawalByUser($whitelabel, $currency, $level, $providerTypes)
    : array {
        $deposits = Transaction::select(
            'users.id',
            'users.username',
            'users.last_login',
            DB::raw('sum(transactions.amount) AS amount')
        )
            ->join('providers', 'transactions.provider_id', '=', 'providers.id')
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->join('profiles', 'users.id', '=', 'profiles.user_id')
            ->where('transactions.whitelabel_id', $whitelabel)
            ->where('transactions.currency_iso', $currency)
            ->where('transaction_type_id', TransactionTypes::$credit)
            ->where('transaction_status_id', TransactionStatus::$approved)
            ->whereIn('providers.provider_type_id', $providerTypes);

        if (! is_null($level)) {
            $deposits->where('profiles.level', $level);
        }
        $totalDeposits = $deposits->groupBy('users.id', 'users.username')->get();

        $withdrawals = Transaction::select('users.id', 'users.username', DB::raw('sum(transactions.amount) AS amount'))
            ->join('providers', 'transactions.provider_id', '=', 'providers.id')
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->join('profiles', 'users.id', '=', 'profiles.user_id')
            ->where('transactions.whitelabel_id', $whitelabel)
            ->where('transactions.currency_iso', $currency)
            ->where('transaction_type_id', TransactionTypes::$debit)
            ->where('transaction_status_id', TransactionStatus::$approved)
            ->whereIn('providers.provider_type_id', $providerTypes);

        if (! is_null($level)) {
            $withdrawals->where('profiles.level', $level);
        }
        $totalWithdrawals = $withdrawals->groupBy('users.id', 'users.username')->get();

        return [
            'deposits'    => $totalDeposits,
            'withdrawals' => $totalWithdrawals
        ];
    }

    /**
     * @param $whitelabel
     * @param $currency
     * @param $providerTypes
     * @param $startDate
     * @param $endDate
     * @param $timezone
     * @return array
     */
    public function getFinancialData($whitelabel, $currency, $providerTypes, $startDate, $endDate, $timezone)
    : array {
        $deposits = Transaction::select(
            DB::raw("(transactions.created_at::TIMESTAMP WITH TIME ZONE AT TIME ZONE '$timezone')::DATE AS date"),
            DB::raw('sum(amount) AS total')
        )
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->join('providers', 'transactions.provider_id', '=', 'providers.id')
            ->join('transaction_details', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->whereIn('providers.provider_type_id', $providerTypes)
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->where('users.whitelabel_id', $whitelabel)
            ->where('transactions.currency_iso', $currency)
            ->where('transactions.transaction_type_id', TransactionTypes::$credit)
            ->where('transactions.transaction_status_id', TransactionStatus::$approved)
            ->where('transaction_details.transaction_status_id', TransactionStatus::$approved)
            ->groupBy(DB::raw("(transactions.created_at::TIMESTAMP WITH TIME ZONE AT TIME ZONE '$timezone')::DATE"))
            ->get();

        $withdrawals = Transaction::select(
            DB::raw("(transactions.created_at::TIMESTAMP WITH TIME ZONE AT TIME ZONE '$timezone')::DATE AS date"),
            DB::raw('sum(amount) AS total')
        )
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->join('providers', 'transactions.provider_id', '=', 'providers.id')
            ->join('transaction_details', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->whereIn('providers.provider_type_id', $providerTypes)
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->where('users.whitelabel_id', $whitelabel)
            ->where('transactions.currency_iso', $currency)
            ->where('transactions.transaction_type_id', TransactionTypes::$debit)
            ->where('transactions.transaction_status_id', TransactionStatus::$approved)
            ->where('transaction_details.transaction_status_id', TransactionStatus::$approved)
            ->groupBy(DB::raw("(transactions.created_at::TIMESTAMP WITH TIME ZONE AT TIME ZONE '$timezone')::DATE"))
            ->get();

        return [
            'deposits'    => $deposits,
            'withdrawals' => $withdrawals
        ];
    }

    /**
     * @param $whitelabel
     * @param $currency
     * @param $providerTypes
     * @param $startDate
     * @param $endDate
     * @param $timezone
     * @param $paymentMethod
     * @return array
     */
    public function getFinancialDataByPaymentMethod(
        $whitelabel,
        $currency,
        $providerTypes,
        $startDate,
        $endDate,
        $timezone,
        $paymentMethod
    )
    : array {
        $deposits = Transaction::select(
            DB::raw("(transactions.created_at::TIMESTAMP WITH TIME ZONE AT TIME ZONE '$timezone')::DATE AS date"),
            DB::raw('sum(amount) AS total')
        )
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->join('providers', 'transactions.provider_id', '=', 'providers.id')
            ->join('transaction_details', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->whereIn('providers.provider_type_id', $providerTypes)
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->where('users.whitelabel_id', $whitelabel)
            ->where('transactions.currency_iso', $currency)
            ->where('transactions.provider_id', $paymentMethod)
            ->where('transactions.transaction_type_id', TransactionTypes::$credit)
            ->where('transactions.transaction_status_id', TransactionStatus::$approved)
            ->where('transaction_details.transaction_status_id', TransactionStatus::$approved)
            ->groupBy(
                DB::raw("(transactions.created_at::TIMESTAMP WITH TIME ZONE AT TIME ZONE '$timezone')::DATE")
            )->get();

        $withdrawals = Transaction::select(
            DB::raw("(transactions.created_at::TIMESTAMP WITH TIME ZONE AT TIME ZONE '$timezone')::DATE AS date"),
            DB::raw('sum(amount) AS total')
        )
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->join('providers', 'transactions.provider_id', '=', 'providers.id')
            ->join('transaction_details', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->whereIn('providers.provider_type_id', $providerTypes)
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->where('users.whitelabel_id', $whitelabel)
            ->where('transactions.currency_iso', $currency)
            ->where('transactions.provider_id', $paymentMethod)
            ->where('transactions.transaction_type_id', TransactionTypes::$debit)
            ->where('transactions.transaction_status_id', TransactionStatus::$approved)
            ->where('transaction_details.transaction_status_id', TransactionStatus::$approved)
            ->groupBy(
                DB::raw("(transactions.created_at::TIMESTAMP WITH TIME ZONE AT TIME ZONE '$timezone')::DATE")
            )->get();

        return [
            'deposits'    => $deposits,
            'withdrawals' => $withdrawals
        ];
    }

    /**
     * @param $currency
     * @param $startDate
     * @param $endDate
     * @param $whitelabel
     * @return mixed
     */
    public function getFinancialTotalsByCurrency($currency, $startDate, $endDate, $whitelabel)
    : mixed {
        $totals = Transaction::select('amount', 'transaction_type_id', 'provider_id')
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->where('transaction_status_id', TransactionStatus::$approved)
            ->where('users.whitelabel_id', $whitelabel);

        if (! empty($currency)) {
            $totals->where('transactions.currency_iso', $currency);
        }

        return $totals->get();
    }

    /**
     * @param $agents
     * @param $whitelabel
     * @param $currency
     * @param $startDate
     * @param $endDate
     * @return mixed
     */
    public function getFirstAgentTransaction($agents, $whitelabel, $currency, $startDate, $endDate)
    : mixed {
        return Transaction::select('transactions.*')
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->where('transactions.provider_id', Providers::$agents)
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->where('users.whitelabel_id', $whitelabel)
            ->where('transactions.currency_iso', $currency)
            ->where('transaction_status_id', TransactionStatus::$approved)
            ->where('transactions.user_id', $agents)
            ->orderBy('transactions.id', 'ASC')
            ->first();
    }

    /**
     * @param $agents
     * @param $whitelabel
     * @param $currency
     * @param $startDate
     * @param $endDate
     * @return mixed
     */
    public function getLastAgentTransaction($agents, $whitelabel, $currency, $startDate, $endDate)
    : mixed {
        return Transaction::select('transactions.*')
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->where('transactions.provider_id', Providers::$agents)
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->where('users.whitelabel_id', $whitelabel)
            ->where('transactions.currency_iso', $currency)
            ->where('transaction_status_id', TransactionStatus::$approved)
            ->where('transactions.user_id', $agents)
            ->orderBy('transactions.id', 'DESC')
            ->first();
    }

    /**
     * @param $whitelabel
     * @param $currency
     * @param $transactionType
     * @param $providers
     * @param $startDate
     * @param $endDate
     * @param $status
     * @return mixed
     */
    public function getManualAdjustmentsByTransactionTypeAndProviders(
        $whitelabel,
        $currency,
        $transactionType,
        $providers,
        $startDate,
        $endDate,
        $status
    )
    : mixed {
        $transactions = Transaction::select(
            'transactions.*',
            'users.username',
            'users.id as user',
            'transaction_details.data as details',
            'whitelabels.description as whitelabel_description'
        )
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->join('transaction_details', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->join('whitelabels', 'transactions.whitelabel_id', '=', 'whitelabels.id')
            ->where('transaction_details.transaction_status_id', $status)
            ->whereIn('transactions.provider_id', $providers)
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->where('transactions.transaction_status_id', $status);

        if (! empty($transactionType)) {
            $transactions->where('transactions.transaction_type_id', $transactionType);
        }

        if (! empty($currency)) {
            $transactions->where('transactions.currency_iso', $currency);
        }

        if (! empty($whitelabel)) {
            $transactions->where('users.whitelabel_id', $whitelabel);
        }
        return $transactions->orderBy('transactions.created_at', 'DESC')->get();
    }

    /**
     * @param $users
     * @param $startDate
     * @param $endDate
     * @param $providers
     * @param $currency
     * @param $whitelabel
     * @param $limit
     * @param $offset
     * @return mixed
     */
    public function getManualTransactionsFromAgents(
        $users,
        $startDate,
        $endDate,
        $providers,
        $currency,
        $whitelabel,
        $limit = 2000,
        $offset = 0
    )
    : mixed {
        $transactions = Transaction::select(
            'transactions.id',
            'transactions.amount',
            'transactions.transaction_type_id',
            'transactions.created_at',
            'transactions.provider_id',
            'transactions.data',
            'transactions.transaction_status_id',
            'transactions.whitelabel_id'
        )
            ->where('transactions.currency_iso', $currency)
            ->where('transactions.whitelabel_id', $whitelabel)
            ->whereIn('transactions.provider_id', $providers)
            ->whereIn('transactions.user_id', $users)
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->orderBy('transactions.id', 'DESC')
            ->limit($limit)
            ->offset($offset)
            ->get();
        return $transactions;
    }

    /**
     * @return mixed
     */
    public function getNextValue()
    : mixed
    {
        $nextValue = DB::select("select nextval('transactions_id_seq')");
        return $nextValue[0]->nextval;
    }

    /**
     * @param string $currency
     * @param int $whitelabelId
     * @param string $timezone
     * @param array $authUserAndChildrenIds
     * @return Collection
     */
    public function getRecentTransactions(
        string $currency,
        int $whitelabelId,
        string $timezone,
        int | string $authUserId
    )
    : Collection {
        return DB::table('transactions')
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->latest('transactions.created_at')
            ->take(10)
            ->select([
                'users.username',
                'transactions.transaction_type_id as transactionType',
                DB::raw("TO_CHAR(transactions.amount, 'FM999999999.00') as amount"),
                DB::raw(
                    "TO_CHAR(transactions.created_at AT TIME ZONE 'UTC' AT TIME ZONE '$timezone', 'YYYY-MM-DD hh:MI:SS AM') AS date"
                ),
            ])
            //->whereIn('transactions.user_id', $authUserAndChildrenIds)
            ->where('transactions.user_id', $authUserId)
            ->where([
                'transactions.currency_iso'  => $currency,
                'transactions.whitelabel_id' => $whitelabelId,
            ])
            ->get();
    }

    /**
     * @param $currency
     * @param $startDate
     * @param $endDate
     * @param $whitelabel
     * @param $timezone
     * @return mixed
     */
    public function getSalesData($currency, $startDate, $endDate, $whitelabel, $timezone)
    : mixed {
        $credit     = TransactionTypes::$credit;
        $debit      = TransactionTypes::$debit;
        $rejected   = TransactionStatus::$rejected;
        $approved   = TransactionStatus::$approved;
        $payments   = ProviderTypes::$payment;
        $dotworkers = Providers::$dotworkers;
        $bonus      = Providers::$bonus;

        $sales = Transaction::selectRaw(
            "(created_at::TIMESTAMP WITH TIME ZONE AT TIME ZONE '$timezone')::DATE AS date, currency_iso,
            CASE WHEN (transaction_type_id = $credit AND transaction_status_id = $rejected AND provider_type_id = $payments) THEN
              sum(amount)
              END AS credit_rejected,
            CASE WHEN (transaction_type_id = $credit AND transaction_status_id = $approved AND provider_type_id = $payments) THEN
              sum(amount)
              END AS credit_approved,
            CASE WHEN (transaction_type_id = $debit AND transaction_status_id = $rejected AND provider_type_id = $payments) THEN
              sum(amount)
              END AS debit_rejected,
            CASE WHEN (transaction_type_id = $debit AND transaction_status_id = $approved AND provider_type_id = $payments) THEN
              sum(amount)
              END AS debit_approved,
            CASE WHEN (transaction_type_id = $credit and transaction_status_id = $approved and provider_id = $dotworkers) THEN
              sum(amount)
              END AS credit_manual,
            CASE WHEN (transaction_type_id = $debit and transaction_status_id = $approved and provider_id = $dotworkers) THEN
              sum(amount)
              END AS debit_manual,
            CASE WHEN (provider_id = $bonus) THEN
              sum(amount)
              END AS bonus
            "
        )
            ->join('providers', 'transactions.provider_id', '=', 'providers.id')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('whitelabel_id', $whitelabel);

        if (! is_null($currency)) {
            $sales->where('transactions.currency_iso', $currency);
        }

        return $sales->groupBy(
            DB::raw("(created_at::TIMESTAMP WITH TIME ZONE AT TIME ZONE '$timezone')::DATE"),
            'transaction_type_id',
            'transaction_status_id',
            'provider_id',
            'provider_type_id',
            'currency_iso'
        )
            ->get();
    }

    /**
     * @param $whitelabel
     * @param $language
     * @param $currency
     * @param $country
     * @param $initialBalance
     * @param $finalBalance
     * @return mixed
     */
    public function getSegmentation($whitelabel, $language, $currency, $country, $initialBalance, $finalBalance)
    : mixed {
        return Transaction::select(
            'transactions.currency_iso',
            'profiles.first_name',
            'profiles.last_name',
            'profiles.phone',
            'countries.name as country',
            'users.id',
            'users.username',
            'users.email',
            'users.uuid'
        )
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->join('profiles', 'users.id ', '=', 'profiles.user_id')
            ->join('countries', 'profiles.country_iso ', '=', 'countries.iso')
            //  ->whereBetween('transactions.created_at', [$startDateDeposit, $endDateDeposit])
            //  ->where('profiles.country_iso',$country)
            //  ->where('users.status', $status)
            ->where('users.whitelabel_id', $whitelabel)
            ->conditions($currency)
            ->where('transactions.transaction_type_id', TransactionTypes::$debit)
            ->where('transactions.transaction_status_id', TransactionStatus::$approved)
            ->orderBy('transactions.id', 'DESC')
            ->get();
    }

    /**
     * @param $user
     * @param $currency
     * @param $providerTypes
     * @return array
     */
    public function getTotalsByProviderTypes($user, $currency, $providerTypes)
    : array {
        $deposits = Transaction::join('providers', 'transactions.provider_id', '=', 'providers.id')
            ->where('transactions.user_id', $user)
            ->where('transactions.currency_iso', $currency)
            ->where('transaction_type_id', TransactionTypes::$credit)
            ->where('transaction_status_id', TransactionStatus::$approved)
            ->whereIn('providers.provider_type_id', $providerTypes)
            ->sum('amount');

        $withdrawals = Transaction::join('providers', 'transactions.provider_id', '=', 'providers.id')
            ->where('transactions.user_id', $user)
            ->where('transactions.currency_iso', $currency)
            ->where('transaction_type_id', TransactionTypes::$debit)
            ->where('transaction_status_id', TransactionStatus::$approved)
            ->whereIn('providers.provider_type_id', $providerTypes)
            ->sum('amount');

        return [
            'deposits'    => $deposits,
            'withdrawals' => $withdrawals
        ];
    }

    /**
     * @param $providers
     * @param $currency
     * @param $whitelabel
     * @param $startDate
     * @param $endDate
     * @param $limit
     * @param $offset
     * @return mixed
     */
    public function getTransactions($providers, $currency, $whitelabel, $startDate, $endDate, $limit = 10, $offset = 0)
    : mixed {
        return Transaction::select(
            'transactions.id',
            'transactions.amount',
            'transactions.transaction_type_id',
            'transactions.created_at',
            'transactions.provider_id',
            'transactions.data',
            'transactions.transaction_status_id'
        )
            ->where('transactions.currency_iso', $currency)
            ->where('transactions.whitelabel_id', $whitelabel)
            ->whereIn('transactions.provider_id', $providers)
            ->orderBy('transactions.id', 'DESC')
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->limit($limit)
            ->offset($offset)
            ->get();
    }


    /**
     * @param $providers
     * @param $currency
     * @param $whitelabel
     * @param $user
     * @param $startDate
     * @param $endDate
     * @param $limit
     * @param $offset
     * @return mixed
     */
    public function getTransactionsByUser(
        $providers,
        $currency,
        $whitelabel,
        $user,
        $startDate,
        $endDate,
        $limit = 10,
        $offset = 0
    )
    : mixed {
        return Transaction::select(
            'transactions.id',
            'transactions.amount',
            'transactions.transaction_type_id',
            'transactions.created_at',
            'transactions.provider_id',
            'transactions.data',
            'transactions.transaction_status_id'
        )
            ->where('transactions.user_id', $user)
            ->where('transactions.currency_iso', $currency)
            ->where('transactions.whitelabel_id', $whitelabel)
            ->whereIn('transactions.provider_id', $providers)
            ->orderBy('transactions.id', 'DESC')
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->limit($limit)
            ->offset($offset)
            ->get();
    }

    /**
     * @param $user
     * @param $transactionType
     * @param $currency
     * @param $providerTypes
     * @return mixed
     */
    public function getTransactionsHistory($user, $transactionType, $currency, $providerTypes)
    : mixed {
        return Transaction::join('providers', 'transactions.provider_id', '=', 'providers.id')
            ->where('transactions.user_id', $user)
            ->where('transactions.currency_iso', $currency)
            ->whereIn('providers.provider_type_id', $providerTypes)
            ->where('transaction_type_id', $transactionType)
            ->where('transaction_status_id', TransactionStatus::$approved)
            ->sum('amount');
    }

    /**
     * @param $whitelabel
     * @param $currency
     * @param $startDate
     * @param $endDate
     * @param $providers
     * @param $user
     * @param $limit
     * @param $offset
     * @return array
     */
    public function getTransactionsTimelinePage(
        $whitelabel,
        $currency,
        $startDate,
        $endDate,
        $providers,
        $user,
        $limit = 10,
        $offset = 0
    )
    : array {
        return DB::select(
            'SELECT * FROM site.get_transactions_timeline_page(?,?,?,?,?,?,?,?)',
            [$whitelabel, $currency, $startDate, $endDate, $providers, $user, $limit, $offset]
        );
    }

    /**
     * @param $whitelabel
     * @param $currency
     * @param $startDate
     * @param $endDate
     * @return mixed
     */
    public function getUniqueDepositors($whitelabel, $currency, $startDate, $endDate)
    : mixed {
        $depositors = Transaction::select('user_id')
            ->join('providers', 'transactions.provider_id', '=', 'providers.id')
            ->where('whitelabel_id', $whitelabel)
            ->where('transaction_status_id', TransactionStatus::$approved)
            ->where('transaction_type_id', TransactionTypes::$credit)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('providers.provider_type_id', ProviderTypes::$payment)
            ->groupBy('user_id');

        if (! is_null($currency)) {
            $depositors->where('currency_iso', $currency);
        }
        return $depositors->get();
    }

    /**
     * @param $userId
     * @param $currency
     * @param $whitelabel
     * @return mixed
     */
    public function getUniqueDepositorsByUserId($userId, $currency, $whitelabel)
    : mixed {
        $depositors = Transaction::select('transactions.user_id')
            ->join('providers', 'transactions.provider_id', '=', 'providers.id')
            ->where('transactions.user_id', $userId)
            ->where('whitelabel_id', $whitelabel)
            ->where('transaction_status_id', TransactionStatus::$approved)
            ->where('transaction_type_id', TransactionTypes::$credit)
            ->where('providers.provider_type_id', ProviderTypes::$payment);

        if (! is_null($currency)) {
            $depositors->where('transactions.currency_iso', $currency);
        }

        return $depositors->get();
    }

    /**
     * @param $currency
     * @param $startDate
     * @param $endDate
     * @return mixed
     */
    public function getWhitelabelsSalesData($currency, $startDate, $endDate)
    : mixed {
        $credit     = TransactionTypes::$credit;
        $debit      = TransactionTypes::$debit;
        $rejected   = TransactionStatus::$rejected;
        $approved   = TransactionStatus::$approved;
        $payments   = ProviderTypes::$payment;
        $dotworkers = Providers::$dotworkers;
        $bonus      = Providers::$bonus;

        return Transaction::selectRaw(
            "whitelabels.description, transactions.whitelabel_id,
            CASE WHEN (transaction_type_id = $credit AND transaction_status_id = $rejected AND provider_type_id = $payments) THEN
              sum(amount)
              END AS credit_rejected,
            CASE WHEN (transaction_type_id = $credit AND transaction_status_id = $approved AND provider_type_id = $payments) THEN
              sum(amount)
              END AS credit_approved,
            CASE WHEN (transaction_type_id = $debit AND transaction_status_id = $rejected AND provider_type_id = $payments) THEN
              sum(amount)
              END AS debit_rejected,
            CASE WHEN (transaction_type_id = $debit AND transaction_status_id = $approved AND provider_type_id = $payments) THEN
              sum(amount)
              END AS debit_approved,
            CASE WHEN (transaction_type_id = $credit and transaction_status_id = $approved and provider_id = $dotworkers) THEN
              sum(amount)
              END AS credit_manual,
            CASE WHEN (transaction_type_id = $debit and transaction_status_id = $approved and provider_id = $dotworkers) THEN
              sum(amount)
              END AS debit_manual,
            CASE WHEN (provider_id = $bonus) THEN
              sum(amount)
              END AS bonus
            "
        )
            ->join('providers', 'transactions.provider_id', '=', 'providers.id')
            ->join('whitelabels', 'transactions.whitelabel_id', '=', 'whitelabels.id')
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->where('currency_iso', $currency)
            ->groupBy(
                'transaction_type_id',
                'transaction_status_id',
                'provider_id',
                'provider_type_id',
                'whitelabel_id',
                'whitelabels.description'
            )
            ->get();
    }

    /**
     * @param $data
     * @param $status
     * @param $detailsData
     * @return mixed
     */
    public function store($data, $status, $detailsData)
    : mixed {
        $transaction = Transaction::create($data);
        $transaction->details()->attach($status, $detailsData);
        return $transaction;
    }

    /**
     * @param $id
     * @param $status
     * @param $data
     * @return mixed
     */
    public function storeTransactionsDetails($id, $status, $data)
    : mixed {
        $transaction                        = Transaction::find($id);
        $transaction->transaction_status_id = $status;
        $transaction->save();
        $transaction->details()->attach($status, $data);
        return $transaction;
    }

    /**
     * @param $id
     * @param $whitelabel
     * @return mixed
     */
    public function ticketTransactionsUser($id, $whitelabel)
    : mixed {
        return Transaction::select(
            'transactions.id',
            'transactions.currency_iso',
            'transactions.amount',
            'transactions.data',
            'transactions.transaction_type_id',
            'transactions.created_at',
            'users.username',
            'providers.name'
        )
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->join('providers', 'transactions.provider_id', '=', 'providers.id')
            ->where('transactions.id', $id)
            ->where('transactions.whitelabel_id', $whitelabel)
            ->first();
    }

    /**
     * @param $whitelabel
     * @param $transactionType
     * @param $currency
     * @param $providerTypes
     * @param $startDate
     * @param $endDate
     * @return int|mixed
     */
    public function totalByProviderTypes($whitelabel, $transactionType, $currency, $providerTypes, $startDate, $endDate)
    : mixed {
        return Transaction::on('replica')
            ->join('providers', 'transactions.provider_id', '=', 'providers.id')
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->where('transactions.whitelabel_id', $whitelabel)
            ->where('transactions.currency_iso', $currency)
            ->whereIn('providers.provider_type_id', $providerTypes)
            ->where('transaction_type_id', $transactionType)
            ->where('transaction_status_id', TransactionStatus::$approved)
            ->sum('amount');
    }

    /**
     * @param $whitelabel
     * @param $transactionType
     * @param $currency
     * @param $providerTypes
     * @param $startDate
     * @param $endDate
     * @param $userId
     * @return mixed
     */
    public function totalByProviderTypesWithUser(
        $whitelabel,
        $transactionType,
        $currency,
        $providerTypes,
        $startDate,
        $endDate,
        $userId
    )
    : mixed {
        return Transaction::on('replica')
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->join('providers', 'transactions.provider_id', '=', 'providers.id')
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->whereIn('providers.provider_type_id', $providerTypes)
            ->where([
                'transactions.whitelabel_id' => $whitelabel,
                'transactions.currency_iso'  => $currency,
                'transaction_type_id'        => $transactionType,
                'transaction_status_id'      => TransactionStatus::$approved,
                'transactions.user_id'       => $userId,
            ])
            ->sum('amount');
    }

    /**
     * @param $id
     * @param $newId
     * @param $balance
     * @return mixed
     */
    public function updateData($id, $newId, $balance = null)
    : mixed {
        $transaction               = Transaction::find($id);
        $dataTmp                   = Helper::convertToArray($transaction->data);
        $dataTmp['transaction_id'] = $newId;
        if (! is_null($balance)) {
            $dataTmp['second_balance'] = $balance;
        }
        $transaction->data = $dataTmp;
        $transaction->update();
        return $transaction;
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     */
    public function update($id, $data)
    : mixed {
        $transaction = Transaction::find($id);
        $transaction->fill($data);
        $transaction->save();
        return $transaction;
    }

    /**
     * Get daily movements of children.
     *
     * Retrieves the daily movements of children for a given user ID, whitelabel ID, and currency.
     *
     * @param int|string $userId The ID of the user.
     * @param int|string $whitelabelId The ID of the whitelabel.
     * @param string $currency The currency.
     *
     * @return array Returns an array containing the daily movements of children, including deposits, withdrawals,
     *               profit, start date, end date, and children IDs.
     *               - 'deposits': Total deposits made by children.
     *               - 'withdrawals': Total withdrawals made by children.
     *               - 'profit': Total profit made by children.
     *               - 'startDate': The start date of the daily movements.
     *               - 'endDate': The end date of the daily movements.
     *               - 'childrenIds': The IDs of children belonging to the user.
     */
    public function getDailyMovementsOfChildren(int|string $userId, int|string $whitelabelId, string $currency)
    : array {
        $today         = date('Y-m-d');
        $startDate     = Utils::startOfDayUtc($today);
        $endDate       = Utils::endOfDayUtc($today);
        $providerTypes = [ProviderTypes::$dotworkers, ProviderTypes::$payment, ProviderTypes::$agents];

        $deposits = $this->totalByProviderTypesWithUser(
            $whitelabelId,
            TransactionTypes::$credit,
            $currency,
            $providerTypes,
            $startDate,
            $endDate,
            $userId
        );

        $withdrawals = $this->totalByProviderTypesWithUser(
            $whitelabelId,
            TransactionTypes::$debit,
            $currency,
            $providerTypes,
            $startDate,
            $endDate,
            $userId
        );

        $childrenIds = $this->reportAgentRepo->getIdsChildrenFromFather($userId, $currency, $whitelabelId);
        $totalProfit = $this->calculateTotalProfit($childrenIds, $currency, $whitelabelId, $startDate, $endDate);

        return [
            'deposits'    => formatAmount($deposits, $currency),
            'withdrawals' => formatAmount($withdrawals, $currency),
            'profit'      => formatAmount($totalProfit, $currency),
            'startDate'   => $startDate,
            'endDate'     => $endDate,
            'childrenIds' => $childrenIds,
        ];
    }

    /**
     * Calculate the total profit of a user's children from the start of the current month to today.
     *
     * @param string|int $userId The ID of the parent user.
     * @param string $currency The currency code (ISO 4217 format).
     * @param string|int $whitelabelId The ID of the whitelabel.
     * @return float The total profit of the user's children for the current month up to today.
     */
    public function calculateChildrenTotalProfitForCurrentMonthToDate(
        string|int $userId,
        string $currency,
        string|int $whitelabelId
    )
    : float {
        $childrenIds = $this->reportAgentRepo->getIdsChildrenFromFather($userId, $currency, $whitelabelId);
        $startDate   = now()->startOfMonth()->format('Y-m-d H:i:s');
        $endDate     = now()->format('Y-m-d H:i:s');

        return $this->calculateTotalProfit($childrenIds, $currency, $whitelabelId, $startDate, $endDate);
    }

    /**
     * Calculate the total profit for a given set of user IDs within a date range.
     *
     * @param array $userIds Array of user IDs.
     * @param string $currency The currency code (ISO 4217 format).
     * @param string|int $whitelabelId The ID of the whitelabel.
     * @param string $startDate The start date for the profit calculation (Y-m-d H:i:s format).
     * @param string $endDate The end date for the profit calculation (Y-m-d H:i:s format).
     * @return float The total profit for the given user IDs and date range.
     */
    private function calculateTotalProfit(
        array $userIds,
        string $currency,
        string|int $whitelabelId,
        string $startDate,
        string $endDate
    )
    : float {
        return DB::table('closures_users_totals_2023_hour')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('user_id', $userIds)
            ->where([
                'whitelabel_id' => $whitelabelId,
                'currency_iso'  => $currency,
            ])
            ->sum('profit');
    }

    /**
     * Sum the values of a specific field in the closures_users_totals_2023_hour table.
     *
     * @param array $params An associative array containing the parameters for the query:
     *                        - userId: The user ID.
     *                        - whitelabelId: The whitelabel ID.
     *                        - currency: The currency ISO.
     *                        - startDate: The start date for the date range.
     *                        - endDate: The end date for the date range.
     *                        - field: The name of the field to sum.
     * @return float|null      The sum of the specified field, or null if no records match the criteria.
     */
    public function sumByField(array $params)
    : ?float {
        return DB::table('closures_users_totals_2023_hour')
            ->whereIn(
                'user_id',
                $this->reportAgentRepo->getIdsChildrenFromFather(
                    $params['userId'],
                    $params['currency'],
                    $params['whitelabelId']
                )
            )
            ->where('created_at', '>=', $params['lastMonth'])
            ->where([
                'whitelabel_id' => $params['whitelabelId'],
                'currency_iso'  => $params['currency'],
            ])
            ->sum($params['field']);
    }

}

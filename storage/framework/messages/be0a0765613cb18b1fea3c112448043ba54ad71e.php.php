<?php

namespace App\BetPay\Collections;

use App\Users\Repositories\UsersRepo;
use Carbon\Carbon;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Configurations\Enums\PaymentMethods;
use Dotworkers\Configurations\Enums\TransactionStatus;
use Dotworkers\Security\Enums\Permissions;
use Dotworkers\Wallet\Wallet;
use Illuminate\Support\Facades\Gate;

/**
 * Class TransactionsCollection
 *
 * This class allows to format transactions data
 *
 * @package App\BetPay\Collections
 * @author  Eborio Linarez
 */
class TransactionsCollection
{
    /**
     * Format client
     * @param array $clients Credentials data
     */
    public function formatClient($clients)
    {
        foreach ($clients as $client) {
            $data ="";

            $client->client = sprintf(
                '<a href="%s" class="btn u-btn-3d u-btn-primary btn-sm">%s</a>',
                route('betpay.clients.edit', [ $client->id]),
                $client->id
            );

            $client->revoked = sprintf(
                '<div class="checkbox checkbox-primary">
                          <input class="revoked_checkbox %s" id="revoked_%s" value="" type="checkbox" %s data-id="%s" data-name="revoked" data-url="" />
                                            <label for="revoked_%s">&nbsp;</label>
                    </div>', ($client->revoked ? 'active' : ''), $client->id,  ($client->revoked ? 'checked' : ''), $client->id, $client->id
            );

        }
    }

    /**
     * Format credit transactions
     *
     * @param array $transactions Transactions data
     * @param int $paymentMethod Payment method ID
     */
    public function formatCreditTransactions($transactions, $paymentMethod)
    {
        $usersRepo = new UsersRepo();
        foreach ($transactions as $transaction) {
            $wallet = Wallet::getByClient($transaction->external_user, $transaction->currency_iso);
            $timezone = session('timezone');

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
            $userData = $usersRepo->find($transaction->external_user);
            if (!is_null($userData)) {
                $transaction->level = $userData->level;
            }
            if ($paymentMethod == PaymentMethods::$zelle) {
                $transaction->full_name = "{$transaction->user_account->first_name} {$transaction->user_account->last_name}";
            }

            if ($paymentMethod == PaymentMethods::$ves_to_usd) {
                $transaction->data->ves_amount = number_format($transaction->data->ves_amount, 2);
                $transaction->data->rate = number_format($transaction->data->rate, 2);
                $transaction->data->commission = number_format($transaction->data->commission, 2);
            }

            if ($paymentMethod == PaymentMethods::$skrill) {
                $transaction->reference_data = $transaction->reference;
            }

            if ($paymentMethod == PaymentMethods::$bizum)
            {
                $transaction->user_account_origin = sprintf(
                    '<li><strong>%s</strong>: %s</li>',
                    _i('Name'),
                    $transaction->user_account->name
                );
                $transaction->user_account_origin .= sprintf(
                    '<li><strong>%s</strong>: %s</li>',
                    _i('Phone'),
                    $transaction->user_account->phone
                );

                $transaction->client_account_destination = sprintf(
                    '<li><strong>%s</strong>: %s</li>',
                    _i('Name'),
                    $transaction->client_account->name
                );
                $transaction->client_account_destination .= sprintf(
                    '<li><strong>%s:</strong> %s</li>',
                    _i('Phone'),
                    $transaction->client_account->phone
                );
            }

            if($paymentMethod == PaymentMethods::$binance) {
                $transaction->origin_account = '';
                if(isset($transaction->user_account->email)){
                    $transaction->origin_account .= sprintf(
                        '<strong>%s:</strong> %s<br>',
                        _i('Email'),
                        $transaction->user_account->email
                    );
                }
                if(isset($transaction->user_account->binance_id)){
                    $transaction->origin_account .= sprintf(
                        '<strong>%s:</strong> %s<br>',
                        _i('Binance Id'),
                        $transaction->user_account->binance_id
                    );
                }
                if(isset($transaction->user_account->pay_id)){
                    $transaction->origin_account .= sprintf(
                        '<strong>%s:</strong> %s<br>',
                        _i('Pay Id'),
                        $transaction->user_account->pay_id
                    );
                }
                if(isset($transaction->user_account->phone)){
                    $transaction->origin_account .= sprintf(
                        '<strong>%s:</strong> %s<br>',
                        _i('Phone'),
                        $transaction->user_account->phone
                    );
                }
                if(isset($transaction->user_account->qr)){
                    $url = s3_asset("sliders/static/photo165220498613.jpg");
                    $image = "<img src='$url' class='img-responsive' width='100%'>";
                    $transaction->origin_account .= sprintf(
                        '<strong>%s:</strong>&nbsp<button class="btn u-btn-3d btn-sm u-btn-bluegray mr-2" data-toggle="modal" data-target="#watch-binance-qr-modal" data-qr="%s"><i class="hs-admin-eye"></i> %s</button><br>',
                        _i('Qr'),
                        $image,
                        _i('View')
                    );
                }
            }

            $transaction->actions = '';
            if (Gate::allows('access', Permissions::$process_credit)) {
                $transaction->actions .= sprintf(
                    '<button class="btn u-btn-3d btn-sm u-btn-bluegray mr-2" data-toggle="modal" data-target="#process-credit-modal" data-transaction="%s" data-wallet="%s" data-user="%s">%s</button>',
                    $transaction->id,
                    $wallet->data->wallet->id,
                    $transaction->external_user,
                    _i('Process')
                );

            }

            $transaction->user = sprintf(
                '<a href="%s" class="btn u-btn-3d btn-sm u-btn-primary mr-2" target="_blank">%s</a>',
                route('users.details', [$transaction->external_user]),
                $transaction->external_user
            );

            $transaction->created = Carbon::createFromFormat('Y-m-d H:i:s', $transaction->created_at)->setTimezone($timezone)->format('d-m-Y H:i:s');
            $transaction->data->date = Carbon::createFromFormat('Y-m-d', $transaction->data->date)->format('d-m-Y');
            $transaction->amount = number_format($transaction->amount, 2);
        }
    }

    /**
     * Format credit transactions report
     *
     * @param array $transactions Transactions data
     * @param int $paymentMethod Payment method
     * @param int $status Transaction status
     */
    public function formatCreditTransactionsReport($transactions, $paymentMethod, $status)
    {
        $total = 0;
        foreach ($transactions as $transaction) {
            $total += $transaction->amount;
            $timezone = session('timezone');
            $operator = null;
            $description = null;

            if (Configurations::getWhitelabel() == 68) {
                $transaction->user = sprintf(
                    '<a href="%s" class="btn u-btn-3d btn-sm u-btn-primary mr-2" target="_blank">%s</a>',
                    route('users.details', [$transaction->user_id]),
                    $transaction->user_id
                );
                $transaction->reference = $transaction->data->reference;
            } else if($paymentMethod == PaymentMethods::$charging_point) {
                $transaction->user = sprintf(
                    '<a href="%s" class="btn u-btn-3d btn-sm u-btn-primary mr-2" target="_blank">%s</a>',
                    route('users.details', [$transaction->user_id]),
                    $transaction->user_id
                );
                $transaction->reference = $transaction->data->provider_transaction;
            }  else {
                $transaction->user = sprintf(
                    '<a href="%s" class="btn u-btn-3d btn-sm u-btn-primary mr-2" target="_blank">%s</a>',
                    route('users.details', [$transaction->external_user]),
                    $transaction->external_user
                );
            }

            switch ($status) {
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

            if (!is_null($transaction->details_data)) {
                $operator = isset($transaction->details_data->operator) ? $transaction->details_data->operator : null;
                $description = isset($transaction->details_data->description) ? $transaction->details_data->description : null;
            }

            $transaction->details = '<ul>';
            if (Configurations::getWhitelabel() == 68) {
                $transaction->details .= sprintf(
                    '<li><strong>%s</strong>: %s</li>',
                    _i('Betpay ID'),
                    $transaction->data->betpay_transaction
                );
            }

            if (Configurations::getWhitelabel() != 68) {
                switch ($paymentMethod) {
                    case PaymentMethods::$wire_transfers:
                    {
                        $transaction->details .= sprintf(
                            '<li><strong>%s</strong>: %s</li>',
                            _i('Date'),
                            Carbon::createFromFormat('Y-m-d', $transaction->data->date)->format('d-m-Y')
                        );
                        $transaction->details .= sprintf(
                            '<li><strong>%s</strong>: %s</li>',
                            _i('Origin bank'),
                            $transaction->user_account->bank_name
                        );
                        $transaction->details .= sprintf(
                            '<li><strong>%s</strong>: %s</li>',
                            _i('Destination bank'),
                            $transaction->client_account->bank_name
                        );
                        break;
                    }
                    case PaymentMethods::$cryptocurrencies:
                    {
                        $transaction->details .= sprintf(
                            '<li><strong>%s</strong>: %s</li>',
                            _i('Date'),
                            Carbon::createFromFormat('Y-m-d', $transaction->data->date)->format('d-m-Y')
                        );
                        $transaction->details .= sprintf(
                            '<li><strong>%s</strong>: %s</li>',
                            _i('Cryptocurrency'),
                            $transaction->data->cryptocurrency
                        );
                        $transaction->details .= sprintf(
                            '<li><strong>%s</strong>: %s</li>',
                            _i('Cryptocurrency amount'),
                            $transaction->data->cryptocurrency_amount
                        );
                        break;
                    }
                    case PaymentMethods::$zelle:
                    {
                        $transaction->details .= sprintf(
                            '<li><strong>%s</strong>: %s</li>',
                            _i('Date'),
                            Carbon::createFromFormat('Y-m-d', $transaction->data->date)->format('d-m-Y')
                        );
                        $transaction->details .= sprintf(
                            '<li><strong>%s</strong>: %s</li>',
                            _i('Full name'),
                            "{$transaction->user_account->first_name} {$transaction->user_account->last_name}"
                        );
                        $transaction->details .= sprintf(
                            '<li><strong>%s</strong>: %s</li>',
                            _i('Email'),
                            $transaction->user_account->email
                        );
                        break;
                    }
                    case PaymentMethods::$mobile_payment:
                    {
                        $transaction->details .= sprintf(
                            '<li><strong>%s</strong>: %s</li>',
                            _i('Date'),
                            Carbon::createFromFormat('Y-m-d', $transaction->data->date)->format('d-m-Y')
                        );
                        $transaction->details .= sprintf(
                            '<li><strong>%s</strong>: %s</li>',
                            _i('Destination bank'),
                            $transaction->client_account->bank_name
                        );
                        $transaction->details .= sprintf(
                            '<li><strong>%s</strong>: %s</li>',
                            _i('Phone'),
                            $transaction->data->phone
                        );
                        break;
                    }
                    case PaymentMethods::$total_pago:
                    {
                        $transaction->details .= sprintf(
                            '<li><strong>%s</strong>: %s</li>',
                            _i('Date'),
                            Carbon::createFromFormat('Y-m-d', $transaction->data->date)->format('d-m-Y')
                        );
                        if (!is_null($transaction->details_data)) {
                            $transaction->details .= sprintf(
                                '<li><strong>%s</strong>: %s</li>',
                                _i('Reason'),
                                ucfirst(strtolower(str_replace('_', ' ', $transaction->details_data->reason)))
                            );
                        }
                        break;
                    }
                    case PaymentMethods::$paypal:
                    case PaymentMethods::$skrill:
                    case PaymentMethods::$neteller:
                    case PaymentMethods::$airtm:
                    case PaymentMethods::$uphold:
                    case PaymentMethods::$zippy:
                    case PaymentMethods::$binance:
                    case PaymentMethods::$reserve:
                    {
                        $transaction->details .= sprintf(
                            '<li><strong>%s</strong>: %s</li>',
                            _i('Date'),
                            Carbon::createFromFormat('Y-m-d', $transaction->data->date)->format('d-m-Y')
                        );
                        break;
                    }
                    case PaymentMethods::$ves_to_usd:
                    {
                        $transaction->details .= sprintf(
                            '<li><strong>%s</strong>: %s</li>',
                            _i('Date'),
                            Carbon::createFromFormat('Y-m-d', $transaction->data->date)->format('d-m-Y')
                        );
                        $transaction->details .= sprintf(
                            '<li><strong>%s</strong>: %s</li>',
                            _i('Origin bank'),
                            $transaction->user_account->bank_name
                        );
                        $transaction->details .= sprintf(
                            '<li><strong>%s</strong>: %s</li>',
                            _i('Destination bank'),
                            $transaction->client_account->bank_name
                        );
                        $transaction->details .= sprintf(
                            '<li><strong>%s</strong>: %s</li>',
                            _i('VES amount'),
                            number_format($transaction->data->ves_amount, 2)
                        );
                        $transaction->details .= sprintf(
                            '<li><strong>%s</strong>: %s</li>',
                            _i('Rate'),
                            number_format($transaction->data->rate, 2)
                        );
                        $transaction->details .= sprintf(
                            '<li><strong>%s</strong>: %s</li>',
                            _i('Commission'),
                            number_format($transaction->data->commission, 2)
                        );
                        break;
                    }
                    case PaymentMethods::$abitab:
                    case PaymentMethods::$red_pagos:
                    case PaymentMethods::$vcreditos_api:
                    {
                        $transaction->details .= sprintf(
                            '<li><strong>%s</strong>: %s</li>',
                            _i('Date'),
                            Carbon::createFromFormat('Y-m-d', $transaction->data->date)->format('d-m-Y')
                        );
                        break;
                    }
                    case PaymentMethods::$monnet:
                    {
                        if (isset($transaction->data->document)) {
                            $transaction->details .= sprintf(
                                '<li><strong>%s</strong>: %s</li>',
                                _i('Document'),
                                $transaction->data->document
                            );
                        }
                        break;
                    }
                    case PaymentMethods::$bizum:
                    {
                        $transaction->details .= sprintf(
                            '<li><strong>%s</strong>: %s</li>',
                            _i('Date'),
                            Carbon::createFromFormat('Y-m-d', $transaction->data->date)->format('d-m-Y')
                        );
                        $transaction->details .= sprintf(
                            '<li><strong>%s</strong>: %s</li>',
                            _i('Name'),
                            $transaction->user_account->name
                        );
                        $transaction->details .= sprintf(
                            '<li><strong>%s</strong>: %s</li>',
                            _i('Phone'),
                            $transaction->user_account->phone
                        );
                        break;
                    }
                    case PaymentMethods::$payku:
                    {
                        if( $status == TransactionStatus::$approved || $status == TransactionStatus::$rejected){
                            $transaction->details .= sprintf(
                                '<li><strong>%s</strong>: %s</li>',
                                _i('Date'),
                                Carbon::createFromFormat('Y-m-d', $transaction->data->date)->format('d-m-Y')
                            );
                            if (!is_null($transaction->details_data)) {
                            $transaction->details .= sprintf(
                                '<li><strong>%s</strong>: %s</li>',
                                _i('Transaction ID'),
                                $transaction->details_data->transaction_id
                            );
                            $transaction->details .= sprintf(
                                '<li><strong>%s</strong>: %s</li>',
                                _i('Payment Key'),
                                $transaction->details_data->payment_key
                            );
                        }
                        }else{
                            $transaction->details .= sprintf(
                                '<li><strong>%s</strong>: %s</li>',
                                _i('Date'),
                                Carbon::createFromFormat('Y-m-d', $transaction->data->date)->format('d-m-Y')
                            );
                        }
                        break;
                    }
                    case PaymentMethods::$pronto_paga:
                    {
                        if( $status == TransactionStatus::$approved || $status == TransactionStatus::$rejected){
                            $transaction->details .= sprintf(
                                    '<li><strong>%s</strong>: %s</li>',
                                    _i('Date'),
                                    Carbon::createFromFormat('Y-m-d', $transaction->data->date)->format('d-m-Y')
                            );
                            if (!is_null($transaction->details_data)) {
                                $transaction->details .= sprintf(
                                    '<li><strong>%s</strong>: %s</li>',
                                    _i('Transaction ID'),
                                    $transaction->details_data->order
                                );
                                $transaction->details .= sprintf(
                                    '<li><strong>%s</strong>: %s</li>',
                                    _i('UID'),
                                    $transaction->details_data->uid
                                );
                                $transaction->details .= sprintf(
                                    '<li><strong>%s</strong>: %s</li>',
                                    _i('Reference'),
                                    $transaction->details_data->reference
                                );
                           }
                        }else{
                            $transaction->details .= sprintf(
                                '<li><strong>%s</strong>: %s</li>',
                                _i('Date'),
                                Carbon::createFromFormat('Y-m-d', $transaction->data->date)->format('d-m-Y')
                            );
                        }
                        break;
                    }
                    case PaymentMethods::$pay_for_fun_go:
                    {
                        if (!is_null($transaction->details_data)) {
                            $transaction->details .= sprintf(
                                '<li><strong>%s</strong>: %s</li>',
                                    _i('Reference'),
                                    $transaction->details_data->reference
                                );
                            }
                        break;
                    }
                    case PaymentMethods::$personal:
                    {
                        if( $status == TransactionStatus::$approved || $status == TransactionStatus::$rejected){
                            $transaction->details .= sprintf(
                                '<li><strong>%s</strong>: %s</li>',
                                _i('Date'),
                                Carbon::createFromFormat('Y-m-d', $transaction->data->date)->format('d-m-Y')
                            );
                            $transaction->details .= sprintf(
                                '<li><strong>%s</strong>: %s</li>',
                                _i('Reference'),
                                $transaction->reference
                           );
                        }else{
                            $transaction->details .= sprintf(
                                '<li><strong>%s</strong>: %s</li>',
                                    _i('Date'),
                                    Carbon::createFromFormat('Y-m-d', $transaction->data->date)->format('d-m-Y')
                                );
                            }
                        break;
                    }
                    case PaymentMethods::$nequi:
                    {
                        if( $status == TransactionStatus::$approved || $status == TransactionStatus::$rejected){
                            $transaction->details .= sprintf(
                                '<li><strong>%s</strong>: %s</li>',
                                _i('Date'),
                                Carbon::createFromFormat('Y-m-d', $transaction->data->date)->format('d-m-Y')
                            );
                            $transaction->details .= sprintf(
                                '<li><strong>%s</strong>: %s</li>',
                                _i('Phone'),
                                $transaction->data->phone
                           );
                            $transaction->details .= sprintf(
                                '<li><strong>%s</strong>: %s</li>',
                                _i('Reference'),
                                $transaction->reference
                           );
                        }else{
                            $transaction->details .= sprintf(
                                '<li><strong>%s</strong>: %s</li>',
                                    _i('Date'),
                                    Carbon::createFromFormat('Y-m-d', $transaction->data->date)->format('d-m-Y')
                                );
                            }
                        break;
                    }
                }

                if (!is_null($operator)) {
                    $transaction->details .= sprintf(
                        '<li><strong>%s</strong>: %s</li>',
                        _i('Operator'),
                        $operator
                    );
                }
                if (!is_null($description)) {
                    $transaction->details .= sprintf(
                        '<li><strong>%s</strong>: %s</li>',
                        _i('Description'),
                        $description
                    );
                }
            }

            $transaction->details .= '</ul>';
            $transaction->created = Carbon::createFromFormat('Y-m-d H:i:s', $transaction->created_at)->setTimezone($timezone)->format('d-m-Y H:i:s');
            $transaction->amount = number_format($transaction->amount, 2);
        }

        $totals['total'] = number_format($total, 2);

        return [
            'transactions' => $transactions,
            'totals' => $totals
        ];
    }

    /**
     * Format debit JustPay
     *
     * @param array $transactions Transactions data
     * @param int $provider Provider ID
     */
    public function formatDebitJustPay($transactions, $provider)
    {
        foreach ($transactions as $transaction) {
            $wallet = Wallet::getByClient($transaction->external_user, $transaction->currency_iso);
            $timezone = session('timezone');

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
            if ($wallet->data->wallet->balance_locked == 0) {
                $lockButtonDisabled = '';
            } else {
                $lockButtonDisabled = 'disabled';
            }

            if (Gate::allows('access', Permissions::$process_debit)) {
                $transaction->actions = sprintf(
                    '<button class="btn u-btn-3d btn-sm u-btn-bluegray mr-2 lock-balance" data-user="%s" data-wallet="%s"
                data-amount="%s" data-route="%s" data-loading-text="<i class=\'fa fa-spin fa-spinner\'></i> %s" %s>%s</button>',
                    $transaction->external_user,
                    $wallet->data->wallet->id,
                    $transaction->amount,
                    route('wallets.lock-balance'),
                    _i('Please wait...'),
                    $lockButtonDisabled,
                    _i('Lock balance')
                );

                $transaction->actions .= sprintf(
                    '<button class="btn u-btn-3d btn-sm u-btn-bluegray mr-2 process-debit" data-toggle="modal" data-target="#process-debit-modal"
                data-transaction="%s" data-wallet="%s" data-user="%s">%s</button>',
                    $transaction->id,
                    $wallet->data->wallet->id,
                    $transaction->external_user,
                    _i('Process')
                );
            }

            $transaction->user = sprintf(
                '<a href="%s" class="btn u-btn-3d btn-sm u-btn-primary mr-2" target="_blank">%s</a>',
                route('users.details', [$transaction->external_user]),
                $transaction->external_user
            );

            $transaction->created = Carbon::createFromFormat('Y-m-d H:i:s', $transaction->created_at)->setTimezone($timezone)->format('d-m-Y H:i:s');
            $transaction->amount = number_format($transaction->amount, 2);
        }
    }

    /**
     * Format debit transactions
     *
     * @param array $transactions Transactions data
     * @param int $paymentMethod Payment method ID
     * @param int $provider Provider ID
     */
    public function formatDebitTransactions($transactions, $paymentMethod, $provider)
    {
        $usersRepo = new UsersRepo();
        foreach ($transactions as $transaction) {
            $wallet = Wallet::getByClient($transaction->external_user, $transaction->currency_iso);
            $timezone = session('timezone');

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
            if ($wallet->data->wallet->balance_locked == 0) {
                $lockButtonDisabled = '';
            } else {
                $lockButtonDisabled = 'disabled';
            }

            switch ($paymentMethod) {
                case PaymentMethods::$wire_transfers:
                {
                    $accountType = $transaction->user_account->account_type == 'C' ? _i('Checking') : _i('Saving');
                    $transaction->withdrawal_data = sprintf(
                        '<strong>%s:</strong> %s<br>',
                        _i('Bank'),
                        $transaction->user_account->bank_name
                    );
                    $transaction->withdrawal_data .= sprintf(
                        '<strong>%s:</strong> %s<br>',
                        _i('Account'),
                        $transaction->user_account->account_number
                    );
                    $transaction->withdrawal_data .= sprintf(
                        '<strong>%s:</strong> %s<br>',
                        _i('Account type'),
                        $accountType
                    );
                    $transaction->withdrawal_data .= sprintf(
                        '<strong>%s:</strong> %s<br>',
                        _i('Name'),
                        $transaction->user_account->social_reason
                    );
                    $transaction->withdrawal_data .= sprintf(
                        '<strong>%s:</strong> %s<br>',
                        _i('DNI'),
                        $transaction->user_account->dni
                    );
                    if (isset($transaction->user_account->itc) && !is_null($transaction->user_account->itc)) {
                        $transaction->withdrawal_data .= sprintf(
                            '<strong>%s:</strong> %s<br>',
                            _i('Interbank transfer code'),
                            $transaction->user_account->itc
                        );
                    }
                    break;
                }
                case PaymentMethods::$cryptocurrencies:
                {
                    $transaction->withdrawal_data = sprintf(
                        '<strong>%s:</strong> %s<br>',
                        _i('Wallet'),
                        $transaction->user_account->wallet
                    );
                    $transaction->withdrawal_data .= sprintf(
                        '<strong>%s:</strong> %s<br>',
                        _i('Cryptocurrency'),
                        $transaction->user_account->cryptocurrency
                    );
                    if(isset($transaction->user_account->network)){
                        $transaction->withdrawal_data .= sprintf(
                            '<strong>%s:</strong> %s<br>',
                            _i('Network'),
                            $transaction->user_account->network
                        );
                    }
                    break;
                }
                case PaymentMethods::$charging_point:
                {
                    $transaction->code = sprintf(
                        '<strong>%s:</strong> %s<br>',
                        _i('Code'),
                        $transaction->data->code
                    );
                    break;
                }
                case PaymentMethods::$bizum:
                {
                    $transaction->withdrawal_data = sprintf(
                        '<strong>%s:</strong> %s<br>',
                        _i('Name'),
                        $transaction->user_account->name
                    );
                    $transaction->withdrawal_data .= sprintf(
                        '<strong>%s:</strong> %s<br>',
                        _i('Phone'),
                        $transaction->user_account->phone
                    );
                }
                case PaymentMethods::$binance:
                {
                    $transaction->payment_method = '';
                    if(isset($transaction->user_account->email)){
                        $transaction->payment_method .= sprintf(
                            '<strong>%s:</strong> %s<br>',
                            _i('Email'),
                            $transaction->user_account->email
                        );
                    }
                    if(isset($transaction->user_account->binance_id)){
                        $transaction->payment_method .= sprintf(
                            '<strong>%s:</strong> %s<br>',
                            _i('Binance Id'),
                            $transaction->user_account->binance_id
                        );
                    }
                    if(isset($transaction->user_account->pay_id)){
                        $transaction->payment_method .= sprintf(
                            '<strong>%s:</strong> %s<br>',
                            _i('Pay Id'),
                            $transaction->user_account->pay_id
                        );
                    }
                    if(isset($transaction->user_account->phone)){
                        $transaction->payment_method .= sprintf(
                            '<strong>%s:</strong> %s<br>',
                            _i('Phone'),
                            $transaction->user_account->phone
                        );
                    }
                    if(isset($transaction->user_account->qr)){
                        $url = s3_asset("sliders/static/photo165220498613.jpg");
                        $image = "<img src='$url' class='img-responsive' width='100%'>";
                        $transaction->payment_method .= sprintf(
                            '<strong>%s:</strong>&nbsp<button class="btn u-btn-3d btn-sm u-btn-bluegray mr-2" data-toggle="modal" data-target="#watch-binance-qr-modal" data-qr="%s"><i class="hs-admin-eye"></i> %s</button><br>',
                            _i('Qr'),
                            $image,
                            _i('View')
                        );
                    }
                }
                case PaymentMethods::$personal:
                {
                    $transaction->withdrawal_data = '';
                    if(isset($transaction->data->phone)){
                        $transaction->withdrawal_data .= sprintf(
                            '<strong>%s:</strong> %s<br>',
                            _i('Phone'),
                            $transaction->data->phone
                        );
                    }
                }
            }

            $userData = $usersRepo->find($transaction->external_user);
            if (!is_null($userData)) {
                $transaction->level = $userData->level;
            }

            $transaction->actions = '';
            if (Gate::allows('access', Permissions::$process_debit)) {
                $transaction->actions .= sprintf(
                    '<button class="btn u-btn-3d btn-sm u-btn-bluegray mr-2 lock-balance" data-user="%s" data-wallet="%s"
                data-amount="%s" data-provider="%s" data-route="%s" data-loading-text="<i class=\'fa fa-spin fa-spinner\'></i> %s" %s>%s</button>',
                    $transaction->external_user,
                    $wallet->data->wallet->id,
                    $transaction->amount,
                    $provider,
                    route('wallets.lock-balance'),
                    _i('Please wait...'),
                    $lockButtonDisabled,
                    _i('Lock balance')
                );

                if ($paymentMethod == PaymentMethods::$wire_transfers) {
                    $transaction->actions .= sprintf(
                        '<button class="btn u-btn-3d btn-sm u-btn-bluegray mr-2 process-debit" data-toggle="modal" data-target="#process-debit-modal"
                data-transaction="%s" data-wallet="%s" data-user="%s" data-bank="%s" data-account="%s" data-account-type="%s" data-social-reason="%s"
                 data-dni="%s">%s</button>',
                        $transaction->id,
                        $wallet->data->wallet->id,
                        $transaction->external_user,
                        $transaction->user_account->bank_name,
                        $transaction->user_account->account_number,
                        $accountType,
                        $transaction->user_account->social_reason,
                        $transaction->user_account->dni,
                        _i('Process')
                    );
                } elseif($paymentMethod == PaymentMethods::$charging_point) {
                    $transaction->actions .= sprintf(
                        '<button class="btn u-btn-3d btn-sm u-btn-bluegray mr-2 process-debit" data-toggle="modal" data-target="#process-debit-modal"
                data-transaction="%s" data-wallet="%s" data-user="%s" data-reference="%s">%s</button>',
                        $transaction->id,
                        $wallet->data->wallet->id,
                        $transaction->external_user,
                        $transaction->data->code,
                        _i('Process')
                    );
                } else {
                    $transaction->actions .= sprintf(
                        '<button class="btn u-btn-3d btn-sm u-btn-bluegray mr-2 process-debit" data-toggle="modal" data-target="#process-debit-modal"
                data-transaction="%s" data-wallet="%s" data-user="%s">%s</button>',
                        $transaction->id,
                        $wallet->data->wallet->id,
                        $transaction->external_user,
                        _i('Process')
                    );
                }
            }

            $transaction->user = sprintf(
                '<a href="%s" class="btn u-btn-3d btn-sm u-btn-primary mr-2" target="_blank">%s</a>',
                route('users.details', [$transaction->external_user]),
                $transaction->external_user
            );

            $transaction->created = Carbon::createFromFormat('Y-m-d H:i:s', $transaction->created_at)->setTimezone($timezone)->format('d-m-Y H:i:s');
            $transaction->amount = number_format($transaction->amount, 2);
        }
    }

    /**
     * Format debit wire transfers
     *
     * @param array $transactions Transactions data
     * @param int $paymentMethod Payment method ID
     * @param int $status Transaction status
     */
    public function formatDebitTransactionsReport($transactions, $paymentMethod, $status)
    {
        $total = 0;
        foreach ($transactions as $transaction) {
            $total += $transaction->amount;
            $timezone = session('timezone');
            $transaction->withdrawal_data = '';

            $transaction->user = sprintf(
                '<a href="%s" class="btn u-btn-3d btn-sm u-btn-primary mr-2" target="_blank">%s</a>',
                route('users.details', [$transaction->external_user]),
                $transaction->external_user
            );

            switch ($status) {
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

            switch ($paymentMethod) {
                case PaymentMethods::$wire_transfers:
                {
                    $accountType = $transaction->user_account->account_type == 'C' ? _i('Checking') : _i('Saving');
                    $transaction->withdrawal_data .= sprintf(
                        '<strong>%s:</strong><br>',
                        _i('User account'),
                    );
                    $transaction->withdrawal_data .= sprintf(
                        '<strong>%s:</strong> %s<br>',
                        _i('Bank'),
                        $transaction->user_account->bank_name
                    );
                    $transaction->withdrawal_data .= sprintf(
                        '<strong>%s:</strong> %s<br>',
                        _i('Account'),
                        $transaction->user_account->account_number
                    );
                    $transaction->withdrawal_data .= sprintf(
                        '<strong>%s:</strong> %s<br>',
                        _i('Account type'),
                        $accountType
                    );
                    $transaction->withdrawal_data .= sprintf(
                        '<strong>%s:</strong> %s<br>',
                        _i('Name'),
                        $transaction->user_account->social_reason
                    );
                    $transaction->withdrawal_data .= sprintf(
                        '<strong>%s:</strong> %s<br><br>',
                        _i('DNI'),
                        $transaction->user_account->dni
                    );
                    if (isset($transaction->user_account->itc) && !is_null($transaction->user_account->itc)) {
                        $transaction->withdrawal_data .= sprintf(
                            '<strong>%s:</strong> %s<br>',
                            _i('Interbank transfer code'),
                            $transaction->user_account->itc
                        );
                    }
                    if ($status == TransactionStatus::$approved) {
                        $transaction->withdrawal_data .= sprintf(
                            '<strong>%s:</strong><br>',
                            _i('Client account'),
                        );
                        $transaction->withdrawal_data .= sprintf(
                            '<strong>%s:</strong> %s<br>',
                            _i('Bank'),
                            $transaction->client_account->bank_name
                        );
                        $transaction->withdrawal_data .= sprintf(
                            '<strong>%s:</strong> %s<br>',
                            _i('Account'),
                            $transaction->client_account->account_number
                        );
                        $transaction->withdrawal_data .= sprintf(
                            '<strong>%s:</strong> %s<br>',
                            _i('Account type'),
                            $accountType
                        );
                        $transaction->withdrawal_data .= sprintf(
                            '<strong>%s:</strong> %s<br>',
                            _i('Name'),
                            $transaction->client_account->social_reason
                        );
                        $transaction->withdrawal_data .= sprintf(
                            '<strong>%s:</strong> %s<br>',
                            _i('DNI'),
                            $transaction->client_account->dni
                        );
                        if (isset($transaction->client_account->itc) && !is_null($transaction->client_account->itc)) {
                            $transaction->withdrawal_data .= sprintf(
                                '<strong>%s:</strong> %s<br>',
                                _i('Interbank transfer code'),
                                $transaction->client_account->itc
                            );
                        }
                        if (isset($transaction->client_account->branch_office) && !is_null($transaction->client_account->branch_office)) {
                            $transaction->withdrawal_data .= sprintf(
                                '<strong>%s:</strong> %s<br>',
                                _i('Branch office'),
                                $transaction->client_account->branch_office
                            );
                        }
                        if (isset($transaction->client_account->cuit_cuil) && !is_null($transaction->client_account->cuit_cuil)) {
                            $transaction->withdrawal_data .= sprintf(
                                '<strong>%s:</strong> %s<br>',
                                _i('CUIT/CUIL'),
                                $transaction->client_account->cuit_cuil
                            );
                        }
                        if (isset($transaction->client_account->alias) && !is_null($transaction->client_account->alias)) {
                            $transaction->withdrawal_data .= sprintf(
                                '<strong>%s:</strong> %s<br>',
                                _i('Alias'),
                                $transaction->client_account->alias
                            );
                        }
                        if (isset($transaction->client_account->bru) && !is_null($transaction->client_account->bru)) {
                            $transaction->withdrawal_data .= sprintf(
                                '<strong>%s:</strong> %s<br>',
                                _i('BRU'),
                                $transaction->client_account->bru
                            );
                        }
                    }
                    break;
                }
                case PaymentMethods::$cryptocurrencies:
                {
                    $transaction->withdrawal_data .= sprintf(
                        '<strong>%s:</strong> %s<br>',
                        _i('Wallet'),
                        $transaction->user_account->wallet
                    );
                    $transaction->withdrawal_data .= sprintf(
                        '<strong>%s:</strong> %s<br>',
                        _i('Cryptocurrency'),
                        $transaction->user_account->cryptocurrency
                    );
                    $transaction->withdrawal_data .= sprintf(
                        '<strong>%s:</strong> %s<br>',
                        _i('Cryptocurrency amount'),
                        $transaction->data->cryptocurrency_amount
                    );
                    break;
                }
                case PaymentMethods::$zelle:
                {
                    $transaction->withdrawal_data .= sprintf(
                        '<strong>%s:</strong> %s<br>',
                        _i('Email'),
                        $transaction->user_account->email
                    );
                    $transaction->withdrawal_data .= sprintf(
                        '<strong>%s:</strong> %s<br>',
                        _i('Full name'),
                        "{$transaction->user_account->first_name} {$transaction->user_account->last_name}"
                    );
                    break;
                }
                case PaymentMethods::$paypal:
                case PaymentMethods::$skrill:
                case PaymentMethods::$neteller:
                case PaymentMethods::$airtm:
                case PaymentMethods::$uphold:
                case PaymentMethods::$reserve:
                {
                    $transaction->withdrawal_data .= sprintf(
                        '<strong>%s:</strong> %s<br>',
                        _i('Email'),
                        $transaction->user_account->email
                    );
                    break;
                }
                case PaymentMethods::$binance:
                {
                    if(isset($transaction->user_account->email)){
                        $transaction->withdrawal_data .= sprintf(
                            '<strong>%s:</strong> %s<br>',
                            _i('Email'),
                            $transaction->user_account->email
                        );
                    }
                    if(isset($transaction->user_account->binance_id)){
                        $transaction->withdrawal_data .= sprintf(
                            '<strong>%s:</strong> %s<br>',
                            _i('Binance Id'),
                            $transaction->user_account->binance_id
                        );
                    }
                    if(isset($transaction->user_account->pay_id)){
                        $transaction->withdrawal_data .= sprintf(
                            '<strong>%s:</strong> %s<br>',
                            _i('Pay Id'),
                            $transaction->user_account->pay_id
                        );
                    }
                    if(isset($transaction->user_account->phone)){
                        $transaction->withdrawal_data .= sprintf(
                            '<strong>%s:</strong> %s<br>',
                            _i('Phone'),
                            $transaction->user_account->phone
                        );
                    }
                    if(isset($transaction->user_account->qr)){
                        $transaction->withdrawal_data .= sprintf(
                            '<strong>%s:</strong> %s<br>',
                            _i('Qr'),
                            $transaction->user_account->qr
                        );
                    }
                    break;
                }
                case PaymentMethods::$vcreditos_api:
                {
                    $transaction->withdrawal_data .= sprintf(
                        '<strong>%s:</strong> %s<br>',
                        _i('Date'),
                        $transaction->date
                    );
                    break;
                }
                case PaymentMethods::$bizum:
                {
                    $transaction->withdrawal_data .= sprintf(
                        '<strong>%s:</strong> %s<br>',
                        _i('Name'),
                        $transaction->user_account->name
                    );
                    $transaction->withdrawal_data .= sprintf(
                        '<strong>%s:</strong> %s<br>',
                        _i('Phone'),
                        $transaction->user_account->phone
                    );
                    break;
                }
                case PaymentMethods::$pronto_paga:
                {
                    $transaction->withdrawal_data .= sprintf(
                        '<strong>%s:</strong> %s<br>',
                        _i('Name'),
                        $transaction->data->name
                    );
                    $transaction->withdrawal_data .= sprintf(
                        '<strong>%s:</strong> %s<br>',
                        _i('Last name'),
                        $transaction->data->lastname
                    );
                    $transaction->withdrawal_data .= sprintf(
                        '<strong>%s:</strong> %s<br>',
                        _i('Document'),
                        $transaction->data->document
                    );
                    $transaction->withdrawal_data .= sprintf(
                        '<strong>%s:</strong> %s<br>',
                        _i('Phone'),
                        $transaction->data->phone
                    );
                    $transaction->withdrawal_data .= sprintf(
                        '<strong>%s:</strong> %s<br>',
                        _i('Account number'),
                        $transaction->data->account_number
                    );
                    $transaction->withdrawal_data .= sprintf(
                        '<strong>%s:</strong> %s<br>',
                        _i('Account type'),
                        $transaction->data->account_type
                    );
                    $transaction->withdrawal_data .= sprintf(
                        '<strong>%s:</strong> %s<br>',
                        _i('Bank'),
                        $transaction->data->bank
                    );
                    break;
                }
                case PaymentMethods::$pay_for_fun_go:
                {
                    $transaction->withdrawal_data .= sprintf(
                        '<strong>%s:</strong> %s<br>',
                        _i('Email'),
                        $transaction->data->email
                    );
                    $transaction->withdrawal_data .= sprintf(
                        '<strong>%s:</strong> %s<br>',
                        _i('Customer ID'),
                        $transaction->data->customer_main_id
                    );
                    break;
                }
                case PaymentMethods::$personal:
                {
                    $transaction->withdrawal_data .= sprintf(
                        '<strong>%s:</strong> %s<br>',
                        _i('Phone'),
                        $transaction->data->phone
                    );
                    $transaction->withdrawal_data .= sprintf(
                        '<strong>%s:</strong> %s<br>',
                        _i('DNI'),
                        $transaction->data->dni
                    );
                    $transaction->withdrawal_data .= sprintf(
                        '<strong>%s:</strong> %s<br>',
                        _i('Name'),
                        $transaction->data->name
                    );
                    $transaction->withdrawal_data .= sprintf(
                        '<strong>%s:</strong> %s<br>',
                        _i('Last name'),
                        $transaction->data->lastname
                    );
                    break;
                }
                case PaymentMethods::$payku:
                {
                    $transaction->withdrawal_data .= sprintf(
                        '<strong>%s:</strong> %s<br>',
                        _i('Name'),
                        $transaction->data->name
                    );
                    $transaction->withdrawal_data .= sprintf(
                        '<strong>%s:</strong> %s<br>',
                        _i('Document'),
                        $transaction->data->document
                    );
                    $transaction->withdrawal_data .= sprintf(
                        '<strong>%s:</strong> %s<br>',
                        _i('Phone'),
                        $transaction->data->phone
                    );
                    $transaction->withdrawal_data .= sprintf(
                        '<strong>%s:</strong> %s<br>',
                        _i('Account number'),
                        $transaction->data->account_number
                    );
                    $transaction->withdrawal_data .= sprintf(
                        '<strong>%s:</strong> %s<br>',
                        _i('Account type'),
                        $transaction->data->account_type
                    );
                    $transaction->withdrawal_data .= sprintf(
                        '<strong>%s:</strong> %s<br>',
                        _i('Bank'),
                        $transaction->data->bank
                    );
                    $transaction->withdrawal_data .= sprintf(
                        '<strong>%s:</strong> %s<br>',
                        _i('Email'),
                        $transaction->data->email
                    );
                     break;
                }
            }

            $transaction->details = '<ul>';
            $transaction->details .= sprintf(
                '<li><strong>%s</strong>: %s</li>',
                _i('Betpay ID'),
                $transaction->id
            );
            if ($status == TransactionStatus::$approved || $status == TransactionStatus::$processing) {
                if ($paymentMethod == PaymentMethods::$charging_point)   {
                    $transaction->details .= sprintf(
                        '<li><strong>%s</strong>: %s</li>',
                        _i('Code'),
                        $transaction->data->code
                    );
                } else {
                    $transaction->details .= sprintf(
                        '<li><strong>%s</strong>: %s</li>',
                        _i('Reference'),
                        $transaction->reference
                    );
                }
            }
            if (!is_null($transaction->details_data)) {
                if(isset($transaction->details_data->operator)){
                    $transaction->details .= sprintf(
                        '<li><strong>%s</strong>: %s</li>',
                        _i('Operator'),
                        $transaction->details_data->operator
                    );
                }
                if(isset($transaction->details_data->uid)){
                    $transaction->details .= sprintf(
                        '<li><strong>%s</strong>: %s</li>',
                        _i('Uid'),
                        $transaction->details_data->uid
                    );
                }
            }
            $transaction->details .= '</ul>';

            $transaction->created = Carbon::createFromFormat('Y-m-d H:i:s', $transaction->created_at)->setTimezone($timezone)->format('d-m-Y H:i:s');
            $transaction->updated = Carbon::createFromFormat('Y-m-d H:i:s', $transaction->updated_at)->setTimezone($timezone)->format('d-m-Y H:i:s');
            $transaction->amount = number_format($transaction->amount, 2);
        }

        $totals['total'] = number_format($total, 2);

        return [
            'transactions' => $transactions,
            'totals' => $totals
        ];
    }

    /**
     * Format transactions personal
     *
     * @param array $transactions Transactions data
     * @param int $paymentMethod Payment method ID
     */
    public function formatTransactionsPersonal($transactions)
    {
        $usersRepo = new UsersRepo();
        $timezone = session('timezone');

        $userData = $usersRepo->find($transactions->transaction->external_user);
          if (!is_null($userData)) {
            $transactions->level = $userData->level;
            $transactions->username = $userData->username;
          }

          $transactions->user = sprintf(
            '<a href="%s" class="btn u-btn-3d btn-sm u-btn-primary mr-2" target="_blank">%s</a>',
            route('users.details', [$transactions->transaction->external_user]),
            $transactions->transaction->external_user
            );

            $transactions->date = Carbon::createFromFormat('Y-m-d', $transactions->transaction->data->date)->format('d-m-Y');
            $transactions->created = Carbon::createFromFormat('Y-m-d H:i:s', $transactions->transaction->created_at)->setTimezone($timezone)->format('d-m-Y H:i:s');
            $transactions->currency_iso = $transactions->transaction->currency_iso;
            $transactions->amount = number_format($transactions->transaction->amount, 2);

            $transactions->status = $transactions->status;
            $transactions->details = '';
            $transactions->details .= sprintf(
                '<li><strong>%s</strong>: %s</li>',
                _i('Client Number'),
                $transactions->clientNumber
            );
            $transactions->details .= sprintf(
                '<li><strong>%s</strong>: %s</li>',
                _i('Reference'),
                $transactions->transaction->reference
            );
    }

        /**
     * Format transactions personal
     *
     * @param array $transactions Transactions data
     * @param int $paymentMethod Payment method ID
     */
    public function formatCancelTransactionsPersonal($transactions)
    {
        $usersRepo = new UsersRepo();
        $timezone = session('timezone');
        $wallet = Wallet::getByClient($transactions->transaction->external_user, $transactions->transaction->currency_iso);

        $userData = $usersRepo->find($transactions->transaction->external_user);
          if (!is_null($userData)) {
            $transactions->level = $userData->level;
            $transactions->username = $userData->username;
          }

          $transactions->user = sprintf(
            '<a href="%s" class="btn u-btn-3d btn-sm u-btn-primary mr-2" target="_blank">%s</a>',
            route('users.details', [$transactions->transaction->external_user]),
            $transactions->transaction->external_user
            );

            $transactions->date = Carbon::createFromFormat('Y-m-d', $transactions->transaction->data->date)->format('d-m-Y');
            $transactions->created = Carbon::createFromFormat('Y-m-d H:i:s', $transactions->transaction->created_at)->setTimezone($timezone)->format('d-m-Y H:i:s');
            $transactions->currency_iso = $transactions->transaction->currency_iso;
            $transactions->amount = number_format($transactions->transaction->amount, 2);

            $transactions->status = $transactions->status;
            $transactions->details = '';
            $transactions->details .= sprintf(
                '<li><strong>%s</strong>: %s</li>',
                _i('Client Number'),
                $transactions->clientNumber
            );
            $transactions->details .= sprintf(
                '<li><strong>%s</strong>: %s</li>',
                _i('Reference'),
                $transactions->voucher
            );

            $transactions->actions = '';
            $transactions->actions .= sprintf(
                '<button class="btn u-btn-3d btn-sm u-btn-bluegray mr-2" data-toggle="modal" data-target="#process-payment-modal" data-transaction="%s" data-reference="%s" data-wallet="%s" data-user="%s" >%s</button>',
                $transactions->transaction->id,
                $transactions->transaction->reference,
                $wallet->data->wallet->id,
                $transactions->transaction->external_user,
                _i('Process')
            );
    }
}




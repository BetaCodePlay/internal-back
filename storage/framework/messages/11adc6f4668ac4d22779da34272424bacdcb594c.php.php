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

            $transaction->cryptocurrency = $transaction->data->cryptocurrency;
            $transaction->created = Carbon::createFromFormat('Y-m-d H:i:s', $transaction->created_at)->setTimezone($timezone)->format('d-m-Y H:i:s');
            $transaction->data->date = Carbon::createFromFormat('Y-m-d', $transaction->data->date)->format('d-m-Y');
            $transaction->amount = number_format($transaction->amount, 2);
            $transaction->crypto_amount = number_format($transaction->data->crypto_amount, 2);
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
                    case PaymentMethods::$binance:
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
                            _i('Amount in Cryptocurrency'),
                            number_format($transaction->data->crypto_amount, 2)
                        );
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

            $transaction->cryptocurrency = $transaction->data->cryptocurrency;
            $transaction->crypto_amount = number_format($transaction->data->crypto_amount, 2);
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

            $transaction->details = '<ul>';
            $transaction->details .= sprintf(
                '<li><strong>%s</strong>: %s</li>',
                _i('Betpay ID'),
                $transaction->id
            );

            switch ($paymentMethod) {
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
                    $transaction->details .= sprintf(
                        '<li><strong>%s</strong>: %s</li>',
                        _i('Cryptocurrency'),
                        $transaction->data->cryptocurrency
                    );
                    $transaction->details .= sprintf(
                        '<li><strong>%s</strong>: %s</li>',
                        _i('Amount in Cryptocurrency'),
                        number_format($transaction->data->crypto_amount, 2)
                    );
                    break;
                }
            }

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
}




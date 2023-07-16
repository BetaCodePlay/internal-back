<?php

namespace App\BetPay\Collections;

use Dotworkers\Configurations\Enums\PaymentMethods;
use Dotworkers\Configurations\Enums\Providers;
use Dotworkers\Security\Enums\Permissions;
use Illuminate\Support\Facades\Gate;

/**
 * Class AccountsCollection
 *
 * This class allows to format users accounts data
 *
 * @package App\Users\Collections
 * @author  Eborio Linarez
 */
class AccountsCollection
{
    /**
     * Format client account data
     * @param array $accounts Clients accounts data
     */
    public function formatClientAccount($accounts)
    {
        foreach ($accounts as $account) {
            $details = json_decode(json_encode($account->data));
            $account->details = "";
            switch ($account->payment_method_id) {
                case PaymentMethods::$cryptocurrencies:
                {
                    if(!is_null($details->wallet)){
                        $account->details .= sprintf(
                            '<ul><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li></ul>',
                            _i('Wallet'),
                            ': ',
                            $details->wallet,
                            _i('Cryptocurrency'),
                            ': ',
                            $details->cryptocurrency,
                        );
                    }
                    if(!is_null($details->qr)){
                        $url = s3_asset("payment/{$details->qr}");
                        $image = "<img src='$url' class='img-responsive' width='30%'>";
                        $account->details .= sprintf(
                            '<ul><li><button class="btn u-btn-3d btn-sm u-btn-bluegray mr-2" data-toggle="modal" data-target="#watch-crypto-qr-modal" data-qr="%s" ><i class="hs-admin-eye"></i> %s</button></li></ul><br>',
                            $image,
                            _i('Qr')
                        );
                    }
                    break;
                }
                case PaymentMethods::$binance:
                {
                    if(!is_null($details->email)){
                        $account->details .= sprintf(
                            '<ul><li><strong>%s</strong>%s%s</li></ul>',
                            _i('Email'),
                            ': ',
                            $details->email
                        );
                    }
                    if(!is_null($details->phone)){
                        $account->details .= sprintf(
                            '<ul><li><strong>%s</strong>%s%s</li></ul>',
                            _i('Phone'),
                            ': ',
                            $details->phone
                        );
                    }
                    if(!is_null($details->binance_id)){
                        $account->details .= sprintf(
                            '<ul><li><strong>%s</strong>%s%s</li></ul>',
                            _i('Binance ID'),
                            ': ',
                            $details->binance_id
                        );
                    }
                    if(!is_null($details->pay_id)){
                        $account->details .= sprintf(
                            '<ul><li><strong>%s</strong>%s%s</li></ul>',
                            _i('Pay ID'),
                            ': ',
                            $details->pay_id
                        );
                    }
                    if(!is_null($details->qr)){
                        $url = s3_asset("payment/{$details->qr}");
                        $image = "<img src='$url' class='img-responsive' width='30%'>";
                        $account->details .= sprintf(
                            '<ul><li><button class="btn u-btn-3d btn-sm u-btn-bluegray mr-2" data-toggle="modal" data-target="#watch-binance-qr-modal" data-qr="%s" ><i class="hs-admin-eye"></i> %s</button></li></ul><br>',
                            $image,
                            _i('Qr')
                        );
                    }
                    break;
                }
                case PaymentMethods::$mercado_pago:
                {
                    if(!is_null($details->email)){
                        $account->details = sprintf(
                            '<ul><li><strong>%s</strong>%s%s</li></ul>',
                            _i('Email'),
                            ': ',
                            $details->email,
                        );
                        $account->details .= sprintf(
                            '<ul><li><strong>%s</strong>%s%s</li></ul>',
                            _i('CBU'),
                            ': ',
                            $details->cbu
                        );
                        $account->details .= sprintf(
                            '<ul><li><strong>%s</strong>%s%s</li></ul>',
                            _i('CVU'),
                            ': ',
                            $details->cvu
                        );
                        $account->details .= sprintf(
                            '<ul><li><strong>%s</strong>%s%s</li></ul>',
                            _i('Alias'),
                            ': ',
                            $details->alias
                        );
                    }else{
                        $account->details ="";
                    }
                    break;
                }
            }
            $account->status = sprintf(
                '<div class="checkbox checkbox-primary">
                          <input class="status_checkbox %s" id="status_%s" value="" type="checkbox" %s data-id="%s" data-name="status" data-url="" />
                                            <label for="status_%s">&nbsp;</label>
                    </div>', ($account->status ? 'active' : ''), $account->id,  ($account->status ? 'checked' : ''), $account->id, $account->id
            );

            $account->action = sprintf(
                '<a href="%s" class="btn u-btn-3d btn-sm u-btn-bluegray mr-2" ><i class="hs-admin-pencil"></i> %s</a>',
                route('betpay.clients.accounts.edit', [$account->id]),
                _i('Edit')
            );
        }
    }

    /**
     * Format user accounts
     *
     * @param array $accounts User accounts data
     */
    public function formatUserAccounts($accounts)
    {
        foreach ($accounts as $account) {
            $account->logo = sprintf(
                '<img src="%s" class="img-fluid g-width-40">',
                $account->logo
            );

            switch ($account->payment_method_id) {
                case PaymentMethods::$cryptocurrencies:
                {
                    $account->info = sprintf(
                        '<strong>%s:</strong> %s<br>',
                        _i('Wallet'),
                        $account->data->wallet
                    );
                    $account->info .= sprintf(
                        '<strong>%s:</strong> %s<br>',
                        _i('Currency'),
                        $account->data->cryptocurrency
                    );
                    if(isset($account->data->network)){
                        $account->info .= sprintf(
                            '<strong>%s:</strong> %s<br>',
                            _i('Network'),
                            $account->data->network
                        );
                        $network = $account->data->network;
                    } else {
                       $network = '';
                    }
                    $account->info .= sprintf(
                        '<a href="#edit-accounts-modal" data-toggle="modal" data-payment-method="%s" data-payment-method-type="cryptocurrencies" data-wallet="%s" data-crypto-currency="%s" data-user-account-id="%s" data-network="%s" class="btn u-btn-3d u-btn-bluegray g-mt-5 mr-2 btn-sm"><i class="hs-admin-pencil"></i> %s</a>',
                        $account->payment_method_id,
                        $account->data->wallet,
                        $account->data->cryptocurrency,
                        $account->id,
                        $network,
                        _i('Edit')
                    );
                    if (Gate::allows('access', Permissions::$disable_user_account)) {
                        $account->info .= sprintf(
                            '<button type="button" id="disable-account" data-route="%s" data-payment-method-account="%s" class="btn u-btn-3d u-btn-primary g-mt-5 btn-sm"><i class="hs-admin-trash"></i> %s</button>',
                            route('betpay.accounts.user.disable', [$account->id]),
                            $account->payment_method_id,
                            _i('Delete')
                        );
                    }
                    break;
                }
                case PaymentMethods::$binance:
                {
                    $account->info = sprintf(
                        '<strong>%s:</strong> %s<br>',
                        _i('Email'),
                        $account->data->email
                    );
                    $account->info .= sprintf(
                        '<strong>%s:</strong> %s<br>',
                        _i('Phone'),
                        $account->data->phone
                    );
                    $account->info .= sprintf(
                        '<strong>%s:</strong> %s<br>',
                        _i('Pay ID'),
                        $account->data->pay_id
                    );
                    $account->info .= sprintf(
                        '<strong>%s:</strong> %s<br>',
                        _i('Binance ID'),
                        $account->data->binance_id
                    );
                    $account->info .= sprintf(
                        '<a href="#edit-accounts-modal" data-toggle="modal" data-payment-method="%s" data-payment-method-type="binance" data-binance-email="%s" data-binance-phone="%s" data-binance-pay-id="%s" data-binance-id="%s" data-user-account-id="%s" class="btn u-btn-3d u-btn-bluegray g-mt-5 mr-2 btn-sm"><i class="hs-admin-pencil"></i> %s</a>',
                        $account->payment_method_id,
                        $account->data->email,
                        $account->data->phone,
                        $account->data->pay_id,
                        $account->data->binance_id,
                        $account->id,
                        _i('Edit')
                    );
                    if (Gate::allows('access', Permissions::$disable_user_account)) {
                        $account->info .= sprintf(
                            '<button type="button" id="disable-account" data-route="%s" data-payment-method-account="%s" class="btn u-btn-3d u-btn-primary g-mt-5 btn-sm"><i class="hs-admin-trash"></i> %s</button>',
                            route('betpay.accounts.user.disable', [$account->id]),
                            $account->payment_method_id,
                            _i('Delete')
                        );
                    }
                    break;
                }
                case PaymentMethods::$mercado_pago:
                {
                    $account->info = sprintf(
                        '<strong>%s:</strong> %s<br>',
                        _i('Email'),
                        $account->data->email
                    );
                    $account->info .= sprintf(
                        '<strong>%s:</strong> %s<br>',
                        _i('CBU'),
                        $account->data->cbu
                    );
                    $account->info .= sprintf(
                        '<strong>%s:</strong> %s<br>',
                        _i('CVU'),
                        $account->data->cvu
                    );
                    $account->info .= sprintf(
                        '<strong>%s:</strong> %s<br>',
                        _i('Alias'),
                        $account->data->alias
                    );
                    $account->info .= sprintf(
                        '<a href="#edit-accounts-modal" data-toggle="modal" data-payment-method="%s" data-payment-method-type="mercado-pago" data-mercado-pago-email="%s" data-mercado-pago-cbu="%s" data-mercado-pago-cvu="%s" data-mercado-pago-alias="%s" data-user-account-id="%s" class="btn u-btn-3d u-btn-bluegray g-mt-5 mr-2 btn-sm"><i class="hs-admin-pencil"></i> %s</a>',
                        $account->payment_method_id,
                        $account->data->email,
                        $account->data->cbu,
                        $account->data->cvu,
                        $account->data->alias,
                        $account->id,
                        _i('Edit')
                    );
                    if (Gate::allows('access', Permissions::$disable_user_account)) {
                        $account->info .= sprintf(
                            '<button type="button" id="disable-account" data-route="%s" data-payment-method-account="%s" class="btn u-btn-3d u-btn-primary g-mt-5 btn-sm"><i class="hs-admin-trash"></i> %s</button>',
                            route('betpay.accounts.user.disable', [$account->id]),
                            $account->payment_method_id,
                            _i('Delete')
                        );
                    }
                    break;
                }
            }
        }
    }

    /**
     * Format accounts
     *
     * @param array $accounts Accounts data
     */
    public function formatAccounts($accounts)
    {
        foreach ($accounts as $account) {
            $account->users = sprintf(
                '<a href="%s" class="btn u-btn-3d u-btn-primary btn-sm" target="_blank">%s</a>',
                route('users.details', [$account->external_user]),
                $account->external_user
            );
            switch ($account->payment_method_id) {
                case PaymentMethods::$cryptocurrencies:
                {
                    $account->info = sprintf(
                        '<strong>%s:</strong> %s<br>',
                        _i('Wallet'),
                        $account->data->wallet
                    );
                    $account->info .= sprintf(
                        '<strong>%s:</strong> %s<br>',
                        _i('Currency'),
                        $account->data->cryptocurrency
                    );
                    break;
                }
                case PaymentMethods::$zelle:
                {
                    $account->info = sprintf(
                        '<strong>%s:</strong> %s<br>',
                        _i('Email'),
                        $account->data->email
                    );
                    $account->info .= sprintf(
                        '<strong>%s:</strong> %s<br>',
                        _i('Full name'),
                        "{$account->data->first_name} {$account->data->first_name}"
                    );
                    break;
                }
                case PaymentMethods::$wire_transfers:
                case PaymentMethods::$ves_to_usd:
                {
                    $accountType = $account->data->account_type == 'C' ? _i('Checking') : _i('Saving');;
                    $account->info = sprintf(
                        '<strong>%s:</strong> %s<br>',
                        _i('Bank'),
                        $account->data->bank_name
                    );
                    $account->info .= sprintf(
                        '<strong>%s:</strong> %s<br>',
                        _i('Number'),
                        $account->data->account_number
                    );
                    $account->info .= sprintf(
                        '<strong>%s:</strong> %s<br>',
                        _i('Type'),
                        $accountType
                    );
                    $account->info .= sprintf(
                        '<strong>%s:</strong> %s<br>',
                        _i('Holder'),
                        $account->data->social_reason
                    );
                    $account->info .= sprintf(
                        '<strong>%s:</strong> %s<br>',
                        _i('DNI'),
                        $account->data->dni
                    );
                    break;
                }
                case PaymentMethods::$skrill:
                case PaymentMethods::$neteller:
                case PaymentMethods::$airtm:
                case PaymentMethods::$uphold:
                case PaymentMethods::$reserve:
                {
                    $account->info = sprintf(
                        '<strong>%s:</strong> %s<br>',
                        _i('Email'),
                        $account->data->email
                    );
                    break;
                }
            }
        }
    }
}

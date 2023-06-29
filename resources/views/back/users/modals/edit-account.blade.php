<div class="modal fade" id="edit-accounts-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ _i('Edit account') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('betpay.accounts.user.update') }}" method="post" id="update-user-accounts-form">
                <div class="modal-body">
                    <div id="cryptocurrencies" class="account d-none">
                        <div class="form-group">
                            <label for="crypto_wallet">{{ _i('Wallet') }}</label>
                            <input type="text" name="crypto_wallet" id="crypto_wallet" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="crypto_currencies">{{ _i('Cripto Currency') }}</label>
                            <input type="text" name="crypto_currencies" id="crypto_currencies" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="network">{{ _i('Network') }}</label>
                            <input type="text" name="network" id="network" class="form-control">
                        </div>
                    </div>
                    <div id="zelle" class="account d-none">
                        <div class="form-group">
                            <label for="account_email">{{ _i('Email') }}</label>
                            <input type="email" name="account_email" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="first_name_account">{{ _i('First name') }}</label>
                            <input type="text" name="first_name_account" id="first_name_account" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="last_name_account">{{ _i('Last name') }}</label>
                            <input type="text" name="last_name_account" id="last_name_account" class="form-control">
                        </div>
                    </div>
                    <div id="wire-transfers" class="account d-none">
                        <input type="hidden" name="bank_name" id="bank_name" class="form-control">
                        <input type="hidden" name="bank_id" id="bank_id" class="form-control">
                        <div class="form-group">
                            <label for="account_number">{{ _i('Account number') }}</label>
                            <input type="text" name="account_number" id="account_number" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="account_type">{{ _i('Account type') }}</label>
                            <input type="text" name="account_type" id="account_type" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="social_reason">{{ _i('Social reason') }}</label>
                            <input type="text" name="social_reason" id="social_reason" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="account_dni">{{ _i('DNI') }}</label>
                            <input type="text" name="account_dni" id="account_dni" class="form-control">
                        </div>
                    </div>
                    <div id="electronic-wallets" class="account d-none">
                        <div class="form-group">
                            <label for="account_email">{{ _i('Email') }}</label>
                            <input type="email" name="account_email" class="form-control">
                        </div>
                    </div>
                    <div id="vcreditos" class="account d-none">
                        <div class="form-group">
                            <label for="vcreditos_user">{{ _i('VCreditos user') }}</label>
                            <input type="text" name="vcreditos_user" id="vcreditos_user" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="vcreditos_secure_id">{{ _i('VCreditos secure ID') }}</label>
                            <input type="text" name="vcreditos_secure_id" id="vcreditos_secure_id" class="form-control">
                        </div>
                    </div>
                    <div id="bizum" class="account d-none">
                        <div class="form-group">
                            <label for="bizum_name">{{ _i('Bizum name') }}</label>
                            <input type="text" name="bizum_name" id="bizum_name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="bizum_phone">{{ _i('Bizum phone') }}</label>
                            <input type="text" name="bizum_phone" id="bizum_phone" class="form-control">
                        </div>
                    </div>
                    <div id="binance" class="account d-none">
                        <div class="form-group">
                            <label for="binance_email">{{ _i('Binance Email') }}</label>
                            <input type="email" name="binance_email" id="binance_email" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="binance_phone">{{ _i('Binance Phone') }}</label>
                            <input type="text" name="binance_phone" id="binance_phone" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="binance_pay_id">{{ _i('Pay ID') }}</label>
                            <input type="text" name="binance_pay_id" id="binance_pay_id" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="binance_id">{{ _i('Binance ID') }}</label>
                            <input type="text" name="binance_id" id="binance_id" class="form-control">
                        </div>
                    </div>
                    <div id="mercado_pago" class="account d-none">
                        <div class="form-group">
                            <label for="mercado_pago_email">{{ _i('MercadoPago Email') }}</label>
                            <input type="email" name="mercado_pago_email" id="mercado_pago_email" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="mercado_pago_cbu">{{ _i('MercadoPago CBU') }}</label>
                            <input type="number" name="mercado_pago_cbu" id="mercado_pago_cbu" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="mercado_pago_cvu">{{ _i('MercadoPago CVU') }}</label>
                            <input type="number" name="mercado_pago_cvu" id="mercado_pago_cvu" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="mercado_pago_alias">{{ _i('MercadoPago Alias') }}</label>
                            <input type="text" name="mercado_pago_alias" id="mercado_pago_alias" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="payment_method" id="payment_method">
                    <input type="hidden" name="user_account_id" id="user_account_id">
                    <input type="hidden" name="user" class="user" value="{{ isset($user) ? $user->id : '' }}">
                    <button type="submit" class="btn u-btn-primary u-btn-3d" id="update-user-accounts" data-loading-text="{{ _i('Updating...') }}">
                        {{ _i('Update') }}
                    </button>
                    <button type="button" class="btn u-btn-3d u-btn-bluegray u-btn-3d" data-dismiss="modal">
                        {{ _i('Close') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

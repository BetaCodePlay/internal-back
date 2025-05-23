<div class="modal fade" id="process-debit-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('betpay.process-debit') }}" method="post" id="process-debit-form">
                <div class="modal-header">
                    <h5 class="modal-title">{{ _i('Process debit') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="action">{{ _i('Action') }}</label>
                        <select name="action" id="action" class="form-control">
                            <option value="">{{ _i('Select...') }}</option>
                            <option value="1">{{ _i('Approve') }}</option>
                            <option value="0">{{ _i('Reject') }}</option>
                        </select>
                    </div>
                    {{--<div class="form-group">
                        <label for="client_account">{{ _i('Account from where you made the payment') }}</label>
                        <select name="client_account" id="client_account" class="form-control">
                            <option value="1">{{ _i('Select...') }}</option>
                        </select>
                    </div>--}}
                    <div class="form-group">
                        <label for="reference">{{ _i('Reference') }}</label>
                        <input type="text" name="reference" id="reference" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="description">{{ _i('Description') }}</label>
                        <textarea name="description" id="description" cols="30" rows="5" class="form-control"></textarea>
                        <small class="form-text text-muted">
                            {{ _i('This description will be shown to the user') }}
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="client_account" id="client_account" value="1">
                    <input type="hidden" name="transaction" id="transaction">
                    <input type="hidden" name="wallet" id="wallet">
                    <input type="hidden" name="user" id="user">
                    <input type="hidden" name="payment_method" value="{{ \Dotworkers\Configurations\Enums\PaymentMethods::$mercado_pago }}">
                    <input type="hidden" name="provider" value="{{ \Dotworkers\Configurations\Enums\Providers::$mercado_pago }}">
                    <button type="button" class="btn u-btn-primary u-btn-3d" id="process-debit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Please wait...') }}">
                        {{ _i('Process') }}
                    </button>
                    <button type="button" class="btn u-btn-3d u-btn-bluegray u-btn-3d" data-dismiss="modal">
                        {{ _i('Close') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

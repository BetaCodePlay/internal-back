<div class="modal fade" id="process-payment-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('betpay.process-payment-personal') }}" method="post" id="process-payment-personal-form">
                <div class="modal-header">
                    <h5 class="modal-title">{{ _i('Process Payment') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="action">{{ _i('Action') }}</label>
                        <select name="action" id="action" class="form-control">
                            <option value="">{{ _i('Select...') }}</option>
                            <option value="0">{{ _i('Reject') }}</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                <input type="hidden" name="transaction" id="transaction">
                <input type="hidden" name="reference" id="ref">
                <input type="hidden" name="wallet" id="wallet">
                <input type="hidden" name="user" id="user">
                <input type="hidden" name="payment_method" value="{{ \Dotworkers\Configurations\Enums\PaymentMethods::$personal }}">
                <input type="hidden" name="provider" value="{{ \Dotworkers\Configurations\Enums\Providers::$personal }}">
                    <button type="button" class="btn u-btn-primary u-btn-3d" id="process-payment" data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Please wait...') }}">
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

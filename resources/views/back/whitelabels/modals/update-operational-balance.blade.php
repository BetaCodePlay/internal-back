<div class="modal fade" id="update-operational-balance">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('whitelabels.update-operational-balance') }}" method="post" id="update-operational-balance-form">
                <div class="modal-header">
                    <h5 class="modal-title">{{ _i('Update operational balance') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="action">{{ _i('Transaction type') }}</label>
                        <select name="transaction_type" id="transaction_type" class="form-control">
                            <option value="">{{ _i('Select...') }}</option>
                            <option value="{{ \Dotworkers\Store\Enums\TransactionTypes::$credit }}">{{ _i('Credit') }}</option>
                            <option value="{{ \Dotworkers\Store\Enums\TransactionTypes::$debit }}">{{ _i('Debit') }}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="amount">{{ _i('Amount') }}</label>
                        <input type="text" class="form-control" name="amount" id="amount">
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="whitelabel" id="whitelabel">
                    <input type="hidden" name="currency" id="currency">
                    <button type="button" class="btn u-btn-primary u-btn-3d" id="update" data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Please wait...') }}">
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

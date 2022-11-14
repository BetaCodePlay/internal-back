<div class="modal fade" id="transaction-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ _i('Balance adjustments') }}: <span class="manual-transaction-type"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('agents.perform-transactions') }}" id="transactions-modal-form" method="post">
                <div class="modal-body">
                    <input type="hidden" name="wallet" class="wallet">
                    <input type="hidden" name="user" class="user">
                    <input type="hidden" name="type" class="type">
                    <input type="hidden" name="transaction_type" class="transaction_type" />
                    <div class="form-group">
                        <label for="amount">{{ _i('Amount') }}</label>
                        <input type="number" name="amount" class="form-control" min="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn u-btn-primary u-btn-3d" id="transactions-button"
                            data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Please wait...') }}">
                        {{ _i('Accept') }}
                    </button>
                    <button type="button" class="btn u-btn-3d u-btn-bluegray u-btn-3d" data-dismiss="modal">
                        {{ _i('Close') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
